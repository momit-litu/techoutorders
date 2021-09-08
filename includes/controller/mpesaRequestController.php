<?php
require "../../mpesa/src/autoload.php";
use Kabangi\Mpesa\Init as Mpesa;


function mpesaSTKPush($invoiceNo,$tableName, $amount, $customerMobile, $dbClass){
	try {
		//echo round($amount);die;
		$amount = round($amount);
		$mpesa = new Mpesa();
		$response = $mpesa->STKPush([
			"Amount"=> $amount,
			"PartyA"=> "254".$customerMobile,
			"PartyB"=> 174379,
			"PhoneNumber"=> "254".$customerMobile,
			'accountReference' => $invoiceNo,
			'Remarks' => 'Test from momit',
			'TransactionDesc'=>'Online order payment'
		]);
		if(isset($response->MerchantRequestID)){
			$columnValue = array(
				'payment_reference_no'=>$response->MerchantRequestID
			);
			$conditionArray = array(
				'invoice_no'=>$invoiceNo
			);
			$result = $dbClass->update($tableName,$columnValue,$conditionArray);
			return ($result)?1:0;
		}
	}catch (Exception $e){
		//$response = json_decode($e->getMessage());
		//header('Content-Type: application/json');
		//echo json_encode($response);
		return 0;
	}
}


?>
