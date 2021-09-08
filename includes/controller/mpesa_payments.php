<?php
include("../dbConnect.php");
include("../dbClass.php");

$dbClass = new dbClass;
$dbClass->getDbConn();

include("orderConfirm.php");
$orderConfirm = new orderConfirm($dbClass);
//{"Body":{"stkCallback":{"MerchantRequestID":"18805-9154556-2","CheckoutRequestID":"ws_CO_020320212003276331","ResultCode":0,"ResultDesc":"The service request is processed successfully.","CallbackMetadata":{"Item":[{"Name":"Amount","Value":1.00},{"Name":"MpesaReceiptNumber","Value":"PC2895V9S6"},{"Name":"Balance"},{"Name":"TransactionDate","Value":20210302200434},{"Name":"PhoneNumber","Value":254719400124}]}}}}
//echo "MOMIT";die;
$callbackJSONData="";
$callbackJSONData=file_get_contents('php://input');
$callbackData=json_decode($callbackJSONData);
$resultCode=$callbackData->Body->stkCallback->ResultCode;
$resultDesc=$callbackData->Body->stkCallback->ResultDesc;
$merchantRequestID=$callbackData->Body->stkCallback->MerchantRequestID;
$checkoutRequestID=$callbackData->Body->stkCallback->CheckoutRequestID;

$amount=$callbackData->stkCallback->Body->CallbackMetadata->Item[0]->Value;
$mpesaReceiptNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[1]->Value;
$balance=$callbackData->stkCallback->Body->CallbackMetadata->Item[2]->Value;
$b2CUtilityAccountAvailableFunds=$callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;
$transactionDate=$callbackData->Body->stkCallback->CallbackMetadata->Item[4]->Value;
$phoneNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[5]->Value;

$result=[
	"resultDesc"=>$resultDesc,
	"resultCode"=>$resultCode,
	"merchantRequestID"=>$merchantRequestID,
	"checkoutRequestID"=>$checkoutRequestID,
	"amount"=>$amount,
	"mpesaReceiptNumber"=>$mpesaReceiptNumber,
	"balance"=>$balance,
	"b2CUtilityAccountAvailableFunds"=>$b2CUtilityAccountAvailableFunds,
	"transactionDate"=>$transactionDate,
	"phoneNumber"=>$phoneNumber
];
//return json_encode($result);
$string_result = json_encode($result);

//$amount = 555;
//$ResultCode = 0;
//$merchantRequestID = '17129-8864836-2';

if($resultCode == 0 && $merchantRequestID){
	$order_details =$dbClass->getSingleRow("SELECT o_id, invoice_no, payment_reference_no, table_nm 
						FROM 
						(
							SELECT order_id AS o_id, invoice_no,payment_reference_no, 'order_master' AS table_nm FROM order_master 
							UNION
							SELECT order_id AS o_id, invoice_no,payment_reference_no, 'group_order' AS table_nm FROM group_order 
						)A
						WHERE payment_reference_no='".$merchantRequestID."'");
	$invoice_no = $order_details['invoice_no'];
	$table_nm 	= $order_details['table_nm'];
	
	$columnValue = array(
		'payment_status'=>2,
		'payment_method'=>5,
		'remarks'=>"Mpesa receipt No: ".$mpesaReceiptNumber,
		'payment_time'=>date('Y-m-d H:i:s')
	);
	$conditionArray = array(
		'payment_reference_no'=>$merchantRequestID
	);
	$results = $dbClass->update($table_nm,$columnValue,$conditionArray);
	
	$columnValuePayment = array(
        'txnid'=>$merchantRequestID,
        'payment_amount'=>$amount,
        'payment_status'=>'success',
        'method'=>4,
        'itemid'=>$invoice_no,
        'createdtime'=>date('Y-m-d H:i:s')
    );
	//$dbClass->print_arrays($columnValuePayment);
    $dbClass->insert('payments',$columnValuePayment);
	//echo "3";
	$orderConfirm->afterPayment($invoice_no);
	echo "4";
}
else{
	$columnValue = array(
		'remarks'=>'Payment Error:'.$callbackData,
		'payment_time'=>date('Y-m-d H:i:s')
	);
	$conditionArray = array(
		'payment_reference_no'=>$merchantRequestID
	);
	$results = $dbClass->update($table_nm,$columnValue,$conditionArray);	
}

