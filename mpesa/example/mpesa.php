<?php
require "../src/autoload.php";

use Kabangi\Mpesa\Init as Mpesa;

// You can also pass your own config here.
// Check the folder ./config/mpesa.php for reference

$mpesa = new Mpesa();
try {
    $response = $mpesa->STKPush([
		"Amount"=> 1,
		"PartyA"=> 254708374149,
		"PartyB"=> 174379,
		"PhoneNumber"=> 254708374149,
        'accountReference' => 'MOMIT1',
        'Remarks' => 'Test from momit',
		'TransactionDesc'=>'Here is the description'
    ]);
	echo "marchent id - ".$response->MerchantRequestID;
}catch(\Exception $e){
    $response = json_decode($e->getMessage());
}

header('Content-Type: application/json');
echo json_encode($response);

