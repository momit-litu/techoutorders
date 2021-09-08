<?php
include '../dbConnect.php';
include("../dbClass.php");
$dbClass = new dbClass;
$project_url = $dbClass->getDescription('website_url');

extract($_REQUEST);

# Note this line needs to change if you don't use Composer:
# require('connect-php-sdk/autoload.php');
require 'vendor/autoload.php';

# dotenv is used to read from the '.env' file created for credentials
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
echo "MOMIT";die;
# Replace these values. You probably want to start with your Sandbox credentials
# to start: https://developer.squareup.com/docs/testing/sandbox

# The access token to use in all Connect API requests. Use your *sandbox* access
# token if you're just testing things out.
$access_token = ($_ENV["USE_PROD"] == 'true')  ?  $_ENV["PROD_ACCESS_TOKEN"]
    :  $_ENV["SANDBOX_ACCESS_TOKEN"];

# Set 'Host' url to switch between sandbox env and production env
# sandbox: https://connect.squareupsandbox.com
# production: https://connect.squareup.com
$host_url = ($_ENV["USE_PROD"] == 'true')  ?  "https://connect.squareup.com"
    :  "https://connect.squareupsandbox.com";

$api_config = new \SquareConnect\Configuration();
$api_config->setHost($host_url);
# Initialize the authorization for Square
$api_config->setAccessToken($access_token);
$api_client = new \SquareConnect\ApiClient($api_config);

# Helps ensure this code has been reached via form submission
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    error_log("Received a non-POST request");
    echo "Request not allowed";
    http_response_code(405);
    return;
}

# Fail if the card form didn't send a value for `nonce` to the server
$nonce = $_POST['nonce'];
if (is_null($nonce)) {
    echo "Invalid card data";
    http_response_code(422);
    return;
}

$payments_api = new \SquareConnect\Api\PaymentsApi($api_client);

# To learn more about splitting payments with additional recipients,
# see the Payments API documentation on our [developer site]
# (https://developer.squareup.com/docs/payments-api/overview).
$request_body = array (
    "source_id" => $nonce,
    # Monetary amounts are specified in the smallest unit of the applicable currency.
    # This amount is in cents. It's also hard-coded for $1.00, which isn't very useful.
    "amount_money" => array (
        "amount" =>(int)($amount*100),
        "currency" => "USD"
    ),
    # Every payment you process with the SDK must have a unique idempotency key.
    # If you're unsure whether a particular payment succeeded, you can reattempt
    # it with the same idempotency key without worrying about double charging
    # the buyer.
    "idempotency_key" => $invoice_no
);

# The SDK throws an exception if a Connect endpoint responds with anything besides
# a 200-level HTTP code. This block catches any exceptions that occur from the request.
try {
    $result = $payments_api->createPayment($request_body);
    $invoice_no = preg_replace('/^[ \t]*[\r\n]+/m', '', $invoice_no);


    try {
        $columnValue = array(
            'payment_status'=>2,
            'payment_method'=>3,
            'payment_time'=>date('Y-m-d H:i:s')
        );
        $conditionArray = array(
            'invoice_no'=>$invoice_no
        );
        if (strpos($invoice_no, 'LBG') !== false){
            $results = $dbClass->update('group_order',$columnValue,$conditionArray);
        }
        $results = $dbClass->update('order_master',$columnValue,$conditionArray);

    }catch (Exception $e){
        echo 'master===='.$e;
    }

    $columnValue = array(
        'txnid'=>$result['payment']['id'],
        'payment_amount'=>$amount,
        'payment_status'=>'success',
        'method'=>1,
        'itemid'=>$invoice_no,
        'createdtime'=>date('Y-m-d H:i:s')
    );
    try {
        //echo json_encode($columnValue);
        $dbClass->insert('payments',$columnValue);
        //echo 1;
        header("Location: ".$project_url.$url_redirect);

    }catch (Exception $e){
        echo 'payment ===='.$e;
    }

    echo "<pre>";
    //print_r($result);
    echo "</pre>";
} catch (\SquareConnect\ApiException $e) {
    echo "Caught exception!<br/>";
    print_r("<strong>Response body:</strong><br/>");
    echo "<pre>"; var_dump($e->getResponseBody()); echo "</pre>";
    echo "<br/><strong>Response headers:</strong><br/>";
    echo "<pre>"; var_dump($e->getResponseHeaders()); echo "</pre>";
}
