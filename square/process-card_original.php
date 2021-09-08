<?php
include '../includes/dbConnect.php';
include("../includes/dbClass.php");
$dbClass = new dbClass;
include("../includes/controller/orderConfirm.php");
$orderConfirm = new orderConfirm($dbClass);
$project_url = $dbClass->getDescription('website_url');

extract($_REQUEST);

require 'vendor/autoload.php';


use Square\Environment;
use Square\SquareClient;
use Square\Models\CreatePaymentRequest;
use Square\Exceptions\ApiException;
use Square\Models\Money;


$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();


$access_token = ($_ENV["USE_PROD"] == 'true')  ?  $_ENV["PROD_ACCESS_TOKEN"]
    :  $_ENV["SANDBOX_ACCESS_TOKEN"];


$host_url = ($_ENV["USE_PROD"] == 'true')  ?  "https://connect.squareup.com"
    :  "https://connect.squareupsandbox.com";

// Initialize the Square client.
$api_client = new SquareClient([
  'accessToken' => $access_token,
  'environment' => Environment::SANDBOX
]); 


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
$payments_api = $api_client->getPaymentsApi();
$money = new Money();
$money->setAmount((int)(floatval($amount)*100));
$money->setCurrency('USD');
$create_payment_request = new CreatePaymentRequest($nonce, uniqid(), $money);
try {
  $apiResponse  = $payments_api->createPayment($create_payment_request);
  if ($apiResponse ->isError()) {
    echo 'Api response has Errors';
    $errors = $apiResponse ->getErrors();
	  echo '<pre>';
	  print_r($errors);
	  echo '</pre>';
    exit();
  }
  else if ($apiResponse ->isSuccess()) {
    $createPaymentResponse = json_decode($apiResponse->getBody(),true);
	$txnid = $createPaymentResponse['payment']['id'];
	//echo $txnid;die;
	$invoice_no = preg_replace('/^[ \t]*[\r\n]+/m', '', $invoice_no);

	try {
		$columnValue = array(
		
			'payment_status'=>2,
			'payment_method'=>4,
			'payment_time'=>date('Y-m-d H:i:s')
		);
		$conditionArray = array(
			'invoice_no'=>$invoice_no
		);
		if (strpos($invoice_no, 'BBG') !== false){
			$results = $dbClass->update('group_order',$columnValue,$conditionArray);
		}
		$results = $dbClass->update('order_master',$columnValue,$conditionArray);

	}catch (Exception $e){
		echo 'master===='.$e;
	}

	$columnValue = array(
		'txnid'=>$txnid,
		'payment_amount'=>intval($amount),
		'payment_status'=>'success',
		'$method'=>1,
		'itemid'=>$invoice_no,
		'createdtime'=>date('Y-m-d H:i:s')
	);
	try {

		$orderConfirm->paymentAdd($txnid, floatval($amount), 'success', 1, $invoice_no);
		$orderConfirm->afterPayment($invoice_no);

		//header("Location: ".$project_url.$url_redirect);

	}catch (Exception $e){
		echo 'payment ===='.$e;
	}

  }

  
  echo '<pre>';
 // print_r($response);
  echo '</pre>';
} catch (ApiException $e) {
echo 'Caught exception!<br/>';
	echo '<pre>';
  print_r($e);
  echo '</pre>';
  
  exit();
}

