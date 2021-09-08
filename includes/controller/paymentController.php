<?php
//include '../dbConnect.php';
//include("../dbClass.php");

$dbClass = new dbClass;
extract($_POST);

$web_url = $dbClass->getSingleRow("SELECT website_url FROM general_settings WHERE id=1");


/**
 * Verify transaction is authentic
 *
 * @param array $data Post data from Paypal
 * @return bool True if the transaction is verified by PayPal
 * @throws Exception
 */
function verifyTransaction($data) {
    global $paypalUrl;

    $req = 'cmd=_notify-validate';
    foreach ($data as $key => $value) {
        $value = urlencode(stripslashes($value));
        $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
        $req .= "&$key=$value";
    }

    $ch = curl_init($paypalUrl);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
    $res = curl_exec($ch);

    if (!$res) {
        $errno = curl_errno($ch);
        $errstr = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL error: ");
    }

    $info = curl_getinfo($ch);

    // Check the http response
    $httpCode = $info['http_code'];
    if ($httpCode != 200) {
        throw new Exception("PayPal responded with http code $httpCode");
    }

    curl_close($ch);

    return $res === 'VERIFIED';
}

/**
 * Check we've not already processed a transaction
 *
 * @param string $txnid Transaction ID
 * @return bool True if the transaction ID has not been seen before, false if already processed
 */
function checkTxnid($txnid) {
    global $dbClass;

    $txnid = $dbClass->real_escape_string($txnid);
    $results = $dbClass->query('SELECT * FROM `payments` WHERE txnid = \'' . $txnid . '\'');

    return ! $results->num_rows;
}

/**
 * Add payment to database
 *
 * @param array $data Payment data
 * @return int|bool ID of new payment or false if failed
 */

function addPayment($data) {
    global $dbClass;



    if (is_array($data)) {
        $stmt = $dbClass->prepare('INSERT INTO `payments` (txnid, payment_amount, payment_status, itemid, createdtime) VALUES(?, ?, ?, ?, ?)');
        $stmt->bind_param(
            $data['txn_id'],
            $data['payment_amount'],
            $data['payment_status'],
            $data['item_number'],
            date('Y-m-d H:i:s')
        );
        $stmt->execute();
        $stmt->close();

        return $dbClass->insert_id;
    }

    return false;
}






function makePayment($item_name, $amount ){
    global $web_url, $dbClass;

    $enableSandbox = true;

    $paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

	// Grab the post data so that we can set up the query string for PayPal.
    // Ideally we'd use a whitelist here to check nothing is being injected into

    // our post data.
    $data = [];

    // Set the PayPal account.
    $data['business'] = 'sb-h43fjp802272@business.example.com';

    // Set the PayPal return addresses.
    $data['return'] = stripslashes($web_url['website_url'].'includes/controller/paymentController.php');
    $data['cancel_return'] = stripslashes($web_url['website_url'].'includes/controller/paymentController.php');
    $data['notify_url'] = stripslashes($web_url['website_url'].'includes/controller/paymentController.php');
    $data['item_number']=$item_name;
    // Set the details about the product being purchased, including the amount
    // and currency so that these aren't overridden by the form data.
    $data['item_name'] = $item_name;
    $data['amount'] = $amount;
    $data['currency_code'] = 'USD';

    // Add any custom fields for the query string.
    //$data['custom'] = USERID;

    // Build the query string from the data.
    $queryString = http_build_query($data);

    // Redirect to paypal IPN
    header('location:' . $paypalUrl . '?' . $queryString);
    exit();
}


//return (json_encode($_POST));
/*
if (isset($_POST) && verifyTransaction($_POST) && checkTxnid($data['txn_id'])) {
    if (addPayment($data) !== false) {
        // Payment successfully added.
        return 2;
    }
    return 1;
}
else{
    return 0;
}
*/
?>