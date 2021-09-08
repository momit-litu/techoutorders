<?php
require 'vendor/autoload.php';
// dotenv is used to read from the '.env' file created for credentials
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
?>
<html>
<head>
    <title>Square Payment Form</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- link to the SqPaymentForm library -->
    <script type="text/javascript" src=
    <?php
    echo "\"";
    echo ($_ENV["USE_PROD"] == 'true')  ?  "https://js.squareup.com/v2/paymentform"
        :  "https://js.squareupsandbox.com/v2/paymentform";
    echo "\"";
    ?>
    ></script>
    <script type="text/javascript">
        window.applicationId =
        <?php
        echo "\"";
        echo ($_ENV["USE_PROD"] == 'true')  ?  $_ENV["PROD_APP_ID"]
            :  $_ENV["SANDBOX_APP_ID"];
        echo "\"";
        ?>;
        window.locationId =
        <?php
        echo "\"";
        echo ($_ENV["USE_PROD"] == 'true')  ?  $_ENV["PROD_LOCATION_ID"]
            :  $_ENV["SANDBOX_LOCATION_ID"];
        echo "\"";
        ?>;
    </script>

    <!-- link to the local SqPaymentForm initialization -->
    <script type="text/javascript" src="js/sq-payment-form.js"></script>
    <!-- link to the custom styles for SqPaymentForm -->
    <link rel="stylesheet" type="text/css" href="css/sq-payment-form.css">
</head>
<body>
<!-- Begin Payment Form -->
<div class="sq-payment-form">
    <div id="sq-ccbox">
       <h1>Ourburrito brothers<h1>
	   <h3 id="order_no"></h3>
        <form id="nonce-form" novalidate action="process-card.php" method="post">
            <div class="sq-field">
                <label class="sq-label">Card Number</label>
                <div id="sq-card-number"></div>
            </div>
            <div class="sq-field-wrapper">
                <div class="sq-field sq-field--in-wrapper">
                    <label class="sq-label">CVV</label>
                    <div id="sq-cvv"></div>
                </div>
                <div class="sq-field sq-field--in-wrapper">
                    <label class="sq-label">Expiration</label>
                    <div id="sq-expiration-date"></div>
                </div>
                <div class="sq-field sq-field--in-wrapper">
                    <label class="sq-label">Postal</label>
                    <div id="sq-postal-code"></div>
                </div>
            </div>
            <div class="sq-field">
                <button id="sq-creditcard" class="sq-button" onclick="onGetCardNonce(event)">
                    Make Square Payment
                </button>
            </div>
            <!--
              After a nonce is generated it will be assigned to this hidden input field.
            -->
            <div id="error"></div>
            <input type="hidden" id="card-nonce" name="nonce">
            <input type="hidden" id="amount" name="amount">
            <input type="hidden" id="invoice_no" name="invoice_no">
            <input type="hidden" id="url_redirect" name="url_redirect">
        </form>
    </div>
</div>
<!-- End Payment Form -->
</body>
</html>
<script>
    amount 		= localStorage.getItem('amount')
    bill_id 	= localStorage.getItem('bill_id').replace(/(^[ \t]*\n)/gm, "")
    url_redirect= localStorage.getItem('sq_success');
	if(bill_id=="" && data.substring(0, 2)!="BB"){
		window.location.href = url_redirect;
	}
		
    document.getElementById('order_no').innerHTML="Your Order No is <b>"+bill_id+"</b><br>Total Amount: <b>$"+amount+"</b>"
    document.getElementById('amount').value = amount
    document.getElementById('invoice_no').value = bill_id
    document.getElementById('url_redirect').value = url_redirect


</script>
