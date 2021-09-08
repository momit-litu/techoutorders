<?php
include("../dbConnect.php");
include("../dbClass.php");

$dbClass = new dbClass;
$dbClass->getDbConn();

include("orderConfirm.php");

//var_dump($_POST);die;
$orderConfirm = new orderConfirm($dbClass);

//$orderConfirm->afterPayment('LB082004861');
//$orderConfirm->afterPayment('LB082004737');

$paypal_email = $dbClass->getDescription("paypal_email");
$project_url = $dbClass->getDescription('website_url');

$paypal_sandbox = 'sb-edrba1353003@business.example.com';
extract($_POST);

// For test payments we want to enable the sandbox mode. If you want to put live
// payments through then this setting needs changing to `false`.
$enableSandbox = true;

// Database settings. Change these for your database configuration.
$dbConfig = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => 'Burro@2020',
    'name' => 'burrito_db'
];



//	global $db;


//$sql = "SELECT website_url, paypal_email FROM general_settings WHERE id=1";
//$results = $db->query($sql);

//return json_encode($results); die;
// PayPal settings. Change these to your account details and the relevant URLs
// for your site.


$paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

// Product being purchased.
//echo $item_name;
//echo $amount_total;


// Include Functions
require 'functions.php';

// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])) {

    //echo 1;
    if(isset($order)){
        $paypalConfig = [
            'email' => $enableSandbox ? $paypal_sandbox : $paypal_email,
            'return_url' => $next_url,
            'cancel_url' => $project_url.'account',
            'notify_url' => $project_url.'includes/controller/payments.php'
        ];
    }
    else if (strpos($item_name, 'LBG') !== false) {
        $paypalConfig = [
            'email' => $enableSandbox ? $paypal_sandbox : $paypal_email,
            'return_url' => $next_url,
            'cancel_url' => $project_url.'account',
            'notify_url' => $project_url.'includes/controller/payments.php'
        ];
    }else{
        $paypalConfig = [
            'email' => $enableSandbox ? $paypal_sandbox : $paypal_email,
            'return_url' => $next_url,
            'cancel_url' => $project_url,
            'notify_url' => $project_url.'includes/controller/payments.php'
        ];
    }

    $itemName = $item_name;
    $itemAmount = $amount_total;


    // Grab the post data so that we can set up the query string for PayPal.
    // Ideally we'd use a whitelist here to check nothing is being injected into
    // our post data.
    $data = [];
    foreach ($_POST as $key => $value) {
        $data[$key] = stripslashes($value);
    }
    //echo json_encode($date);

    // Set the PayPal account.
    $data['business'] = $paypalConfig['email'];

    // Set the PayPal return addresses.
    $data['return'] = stripslashes($paypalConfig['return_url']);
    $data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
    $data['notify_url'] = stripslashes($paypalConfig['notify_url']);

    // Set the details about the product being purchased, including the amount
    // and currency so that these aren't overridden by the form data.
    $data['item_name'] = $itemName;
    $data['amount'] = $itemAmount;
    $data['currency_code'] = 'USD';

    // Add any custom fields for the query string.
    //$data['custom'] = USERID;

    // Build the query string from the data.
    $queryString = http_build_query($data);

    // Redirect to paypal IPN
    echo($paypalUrl . '?' . $queryString);
    //header('location:' . $paypalUrl . '?' . $queryString);
    exit();

}
else {
    //$orderConfirm->afterPayment('LB082004862');

    //echo 2;
    // Handle the PayPal response.

    // Create a connection to the database.
    $db = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['name']);

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

    // We need to verify the transaction comes from PayPal and check we've not
    // already processed the transaction before adding the payment to our
    // database.
    //    if (verifyTransaction($_POST) && checkTxnid($data['txn_id'])) {
    //$orderConfirm->afterPayment('LB082004864');

    if (checkTxnid($data['txn_id'])) {
        $item_number= trim(preg_replace('/\s+/', '', $data['item_number']));
        //$orderConfirm->afterPayment('LB082004864');
        try {
            $orderConfirm->afterPayment($item_number);
        }catch (Exception $e){}

        try {
            $orderConfirm->paymentAdd($data['txn_id'], $data['payment_amount'], $data['payment_status'], 0, $item_number);
        }catch (Exception $e){}

        /*
        if (addPayment($data) !== false) {
            // echo 3;
            // Payment successfully added.
        }*/
    }
}