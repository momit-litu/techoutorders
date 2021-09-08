<?php
include ('../dbConnect.php');
include("../dbClass.php");

$dbClass = new dbClass;
extract($_POST);

// For test payments we want to enable the sandbox mode. If you want to put live
// payments through then this setting needs changing to `false`.
$enableSandbox = true;

$paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';


// Assign posted variables to local data array.
$data = [
    'item_name' => $_POST['item_name'],
    'item_number' => $_POST['item_number'],
    'payment_status' => $_POST['payment_status'],
    'payment_amount' => $_POST['mc_gross'],
    'payment_currency' => $_POST['mc_currency'],
    'txn_id' => $_POST['txn_id'],
    'receiver_email' => $_POST['receiver_email'],
    'payer_email' => $_POST['payer_email'],
    'custom' => $_POST['custom'],
];


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
        throw new Exception("cURL error: [$errno] $errstr");
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



if ( verifyTransaction($_POST)) {
    if (is_array($data)) {
        //for removing extra line
        $item_number = trim(preg_replace('/\s+/', '', $data['item_number']));

        try {
            $columnValue = array(
                'payment_status'=>2,
                'payment_method'=>3,
                'payment_time'=>date('Y-m-d H:i:s')
            );
            $conditionArray = array(
                'invoice_no'=>$item_number
            );
            if (strpos($item_number, 'LBG') !== false){
                $results = $dbClass->update('group_order',$columnValue,$conditionArray);
            }
            $results = $dbClass->update('order_master',$columnValue,$conditionArray);

        }catch (Exception $e){}

        $columnValue = array(
            'txnid'=>$data['txn_id'],
            'payment_amount'=>$data['payment_amount'],
            'payment_status'=>$data['payment_status'],
            'itemid'=>$item_number,
            'createdtime'=>date('Y-m-d H:i:s')
        );
        try {
            $dbClass->insert('payments',$columnValue);
            echo 1;
        }catch (Exception $e){
            echo 0;
        }
    }
}
