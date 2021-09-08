<?php
session_start();
include("../includes/dbConnect.php");
include("../includes/dbClass.php");
$dbClass = new dbClass;

if(isset($_SESSION['customer_id']) && $_SESSION['customer_id']!=""){
    $is_logged_in_customer = 1; // here will be the customer id that will come from session when the customer will login
    $customer_info = $dbClass->getSingleRow("select * from customer_infos where customer_id=".$_SESSION['customer_id']);
    $customer_id = $_SESSION['customer_id'];
}
else $is_logged_in_customer = "";


//var_dump($customer_info)

$order_id = '';
if(isset($_GET['order_id']) && $_GET['order_id']!="") $order_id =  $_GET['order_id'];

//var_dump($customer_info);
if(!isset($_SESSION['cart']) || !count($_SESSION['cart'])>0) {
    echo'<script> window.location=project_url+"index.php?page=categories"; </script> ';
}
?>


<section class="home-icon shop-cart bg-skeen" style="background-color: rgba(244,242,237,1)">
    <div class="icon-default icon-skeen">
        <img src="./images/scroll-arrow.png" alt="">
    </div>
    <div class="container">
          <div class="checkout-wrap wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
               <ul class="checkout-bar">
                        <li class="done-proceed"><a href="index.php?page=cart">Shopping Cart</a></li>
                        <li class="active"><a href="index.php?page=checkout">Checkout</a></li>
                        <li>Complete</li>
                    </ul>
          </div>
          <div class="row">
               <div class="col-md-12 col-sm-12 col-xs-12 wow fadeInDown  tab-content" data-wow-duration="1000ms" data-wow-delay="300ms" >
                   <ul class="nav nav-tabs" role="tablist" style="margin-right: 43%; margin-top: 40px;  border-radius: 15px 15px 0px 0px">
                            <li role="presentation" onclick="user_details()" id="userDetails" style="background-color: #EAEAEA">
                                <a href="#description" aria-controls="account" role="tab" data-toggle="tab">Your Details</a>
                            </li>
                            <li role="presentation" onclick="take_out()" id="take_out_menu" style="background-color: #EAEAEA">
                                <a href="#reviews" aria-controls="pickUp" role="tab" data-toggle="tab">Take Out</a>
                            </li>
                            <li role="presentation" onclick="payments()" id="payments_menu" style="background-color: #EAEAEA">
                                <a href="#reviews" aria-controls="payments" role="tab" data-toggle="tab">Payments</a>
                            </li>
                        </ul>
                   <div class="col-md-7 col-sm-7 col-xs-12" style="background-color: white; border-radius: 0px 12px 12px 12px; padding-top: 25px; padding-bottom: 20px">
                        <div role="tabpanel" class="tab-pane" id="description">

                            <div id="login_div" style="padding-bottom: 20px">
                                <div id="done_login">
                                    <div class="title text-center">
                                        <h3 class="text-coffee">Login</h3>
                                    </div>
                                    <form class="login-form" method="post" name="login_form" id="login_form">
                                        <div class="row">
                                            <div >
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="text" name="username" id="username_" placeholder="Username or email address" class="input-fields" required >
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="password" name="password" id="password_" placeholder="Password" class="input-fields" required >
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <label><input type="checkbox" name="chkbox">Remember me</label>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <a  onclick="forgetPass()" class="pull-right" id="send_password_"><i class="fa fa-user" aria-hidden="true"></i> Lost your password?</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div id="loginerror_" class="text-center" style="display:none"></div>
                                                <input type="submit" name="submit" id="login" value="LOGIN" class="button-default button-default-submit">
                                            </div>
                                        </div>
                                    </form>
                                    <div class="divider-login">
                                        <hr>
                                        <span>Or</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12" >
                                            <a onclick="registration()" class="facebook-btn btn-change button-default " id="log_reg_"><i class="fa fa-user" aria-hidden="true"></i> Dont have an account? Register yourself</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 center hide" 	id="done_login_msg" >
                                    <div class="alert alert-success alert-custom">
                                        <p>You have logged in successfully</p>
                                    </div>
                                    <a href="index.php?page=account" id="" class="facebook-btn btn-change button-default"><i class="fa fa-user"></i>Browse your account?</a>
                                </div>
                            </div>
                            <div id="register_div" style="display: none">
                                <div class="title text-center">
                                    <h3 class="text-coffee">Register</h3>
                                </div>
                                <div class="done_registration">
                                    <form class="register-form" method="post" name="register-form" id="register_form">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" name="cust_name" id="cust_name_" placeholder="Name" class="input-fields" required>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" name="cust_username" id="cust_username_" placeholder="User Name" class="input-fields" required>
                                                <div id="username_error" class="text-center" style="display:none"></div>
                                            </div>

                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="email" name="cust_email" id="cust_email_" placeholder="Email address" class="input-fields" required>
                                                <div id="email_error" class="text-center" style="display:none"></div>
                                            </div>

                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="password" name="cust_password" id="cust_password_" placeholder="Password" class="input-fields" required>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="password" name="cust_conf_password" id="cust_conf_password_"  placeholder="Confirm Password" class="input-fields" required>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="number" name="cust_contact" id="cust_contact_" pattern="[0-9]{11}" placeholder="Contact No" class="input-fields" required>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" name="cust_address" id="cust_address_" placeholder="Address" class="input-fields" >
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">

                                                <div id="registration_submit_error_" class="text-center" style="display:none"></div>
                                                <input type="submit" name="submit" id="register_submit_" class="button-default button-default-submit" value="Register now">
                                            </div>
                                        </div>
                                    </form>
                                    <p>By clicking on <b>Register Now</b> button you are accepting the <a href="terms.php">Terms &amp; Conditions</a></p>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 done_registration_msg text-center hide" >
                                    <div class="alert alert-success">
                                        <p>Your registration is completed. Please login with provided credentials</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 text-center" >
                                        <a onclick="login()" class="facebook-btn btn-change button-default " id="log_reg_"><i class="fa fa-user" aria-hidden="true"></i> Login Here</a>
                                    </div>
                                </div>

                            </div>
                            <div id="forget_pass_div" style="display: none">
                                <div class="title text-center">
                                    <h3 class="text-coffee">Enter email address</h3>
                                </div>
                                <form class="register-form" method="post" name="forget-pass-form" id="forget_pass_form">
                                    <div class="row">
                                        <div class="sent_password">
                                            <div class="col-md-12 col-sm-12 col-xs-12 ">
                                                <input type="email" name="forget_email" id="forget_email_" placeholder="Enter email address" class="input-fields">
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12 ">
                                                <div id="foget_pass_submit_error_" class="text-center" style="display:none"></div>
                                                <input type="submit" name="submit" id="foget_pass_submit_"  class="button-default button-default-submit" value="Send Password">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12 sent_password_msg center hide" >
                                            <div class="alert alert-success">
                                                <p>A new password has been sent to your provided email address. please check and login</p>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12" >
                                        <a onclick="login()" class="facebook-btn btn-change button-default " id="log_reg_"><i class="fa fa-user" aria-hidden="true"></i> Login Here</a>
                                    </div>
                                </div>
                            </div>
                            <div id="profile" style="display: none" class="team-single-right" >
                                <h3 id='customer_name'></h3>
                                <h6 >Customer Id # <span id='customer_id' ></span> </h6>
                                <h6 >Customer Status : <span id='customer_status' ></span> </h6>
                                <p>Contact No: <a href="#" id="contact_no"></a>
                                    <br> E-mail: <a href="#" id="email"></a></p>
                                <p > Address: <span id="address"></span></p>
                                <p > Loyalty Points: <span id="loyalty_points"></span></p>

                                <div class="checkout-button">
                                    <button class="button-default btn-large btn-primary-gold" name="proceed_payments" id="proceed_payments" onclick="take_out()">PROCEED TO TAKE-OUT</button>
                                </div>

                            </div>

                            <form method="post" name="checkout-form" id="checkout-form">
                            <div id="take_out" style="display: none">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h5>Coupon and Tips</h5>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label> Do you have any coupon code </label>
                                    <input type="text" name="coupon" id="coupon" placeholder="Enter The Coupon Code" class="input-fields" style="border-radius: 10px">
                                    <div id="coupon_error" class="text-center" style="display:none"></div>

                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12" style="margin: auto" id="tips_entry">
                                    <label> Want to give some Tips </label><br>
                                    <input type="radio" value="0" class="icheckbox_flat" name="tips_percentage" id="0percente" style="margin-right: 5px"> <label>  Not today</label>
                                    <input type="radio" value="18" name="tips_percentage" id="18percente" style="margin-right: 5px; margin-left: 10px"> <label>  18% </label>
                                    <input type="radio" value="25" name="tips_percentage" id="25percente" style="margin-right: 5px; margin-left: 10px"> <label>  25% </label>
                                    <input type="radio" value="30" name="tips_percentage" id="30percente" style="margin-right: 5px; margin-left: 10px"> <label>  30% </label>
                                    <input type="radio" value="100" name="tips_percentage" id="100percente" style="margin-right: 5px; margin-left: 10px"> <span><label>  Custom</label></span>
                                    <span><input type="number" step="0.01" name="tips" id="tips" placeholder="Tips amount" class="input-fields" value="0" style="border-radius: 10px; display: block;margin-top: 10px"></span>
                                    <div id="tips_error" class="text-center" style="display:none"></div>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h5>Takeout Details</h5>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">

                                    <br />
                                    <label> Confirm TakeOut Address </label>
                                    <div class="payment-mode" style="margin: auto">
                                        <span><input type="checkbox" name="take_out_location" id="take_out_location" ><label id="take_out_location_" style="padding-left: 10px; padding-top: 10px;"></label></span>
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <br />
                                    <label> Please select date and time (Washington, DC, USA) </label>
                                    <input type="text" name="pickup_date_time" id="pickup_date_time" placeholder="Date and Time" class="input-fields date-picker" required style="border-radius: 10px">
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label> Order Notes </label>
                                    <textarea placeholder="Order Notes" name="secial_notes" id="secial_notes" style="border-radius: 10px"></textarea>
                                </div>

                                <div class="checkout-button">
                                    <input class="button-default btn-large btn-primary-gold" type="button" value="PROCEED TO PAYMENT" onclick="payments()">
                                </div>

                            </div>
                            <div id="payments" style="display: none">

                                <h4>Payment Methods</h4>
                                <input type="hidden"  id="grand_total">
                                <div class="payment_body" id="payment_body"></div>
                                <div id="payment_alert" class="text-center" style="display:none"></div>

                                <div class="checkout-terms">
                                    <input type="checkbox" name="tarm_condition" id="tarm_condition"><label style="padding-left: 10px; padding-top: 10px;">I’ve read and accept the <a href="index.php?page=termsCondition">terms &amp; conditions </a>*</label>
                                </div>
                                <input type="hidden" name="total_order_amt" id="total_order_amt">
                                 <input type="hidden" name="tax_amount" id="tax_amount">
                                 <input type="hidden" name="total_paid_amount" id="total_paid_amount">


                                <div class="checkout-button">
                                    <div id="logn_reg_error" class="text-center" style="display:none"></div>
                                    <input type="submit" name="submit" id="checkout_submit" class="button-default btn-large btn-primary-gold" value="PLACE ORDER">
                                </div>
                            </div>
                            </form>

                        </div>
                       </div>
                   <div class="col-md-5 col-sm-5 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                            <div class="shop-checkout-right">
                                <div class="shop-checkout-box">
                                    <h5>YOUR ORDER</h5>
                                    <div class="shop-checkout-title">
                                        <h6>PRODUCT <span>TOTAL</span></h6>
                                    </div>
                                    <div class="shop-checkout-row" id="cart_summary">

                                    </div>
                                    <div class="checkout-total">
                                        <h6>CART SUBTOTAL <small id="cart_total_"></small></h6>
                                    </div>
                                    <div class="checkout-total">
                                        <h6>DISCOUNT <small id="discount_"></small></h6>
                                    </div>
                                    <div class="checkout-total">
                                        <h6>TAX <small id="tax_"></small></h6>

                                    </div>
                                    <div class="checkout-total">
                                        <h6>TIPS <small id="tips_"></small></h6>
                                    </div>
                                    <div class="checkout-total">
                                        <h6>ORDER TOTAL <small class="price-big" id="total_amount_"></small></h6>
                                    </div>
                                    <div class="text-center" style=" background-color: #add8e6; border-radius:4px;" >
                                        <label id="loyalty_point_earn" style="text-align: center; margin: auto; padding: 8px; margin-left: 10px"></label>
                                    </div>

                                </div>
                            </div>
                        </div>
               </div>
          </div>
        <form class="paypal" method="post" id="paypal_form">
            <input type="hidden" name="cmd" value="_xclick" />
            <input type="hidden" name="no_note" value="1" />
            <input type="hidden" name="lc" value="UK" />
            <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
            <input type="hidden" name="first_name" value="Customer's First Name" />
            <input type="hidden" name="last_name" value="Customer's Last Name" />
            <input type="hidden" name="payer_email" value="customer@example.com" />
            <input type="hidden" name="item_number" id="item_number" value="123456" />
            <input type="hidden" name="item_name" id="item_name" value="123456" />
            <input type="hidden" name="amount_total" id="amount_total" value="123456" />
        </form>

    </div>
</section>

<script>
var loyalty_points=0;
var loyalty_point_value=0;
var loyalty_reserve_value=0;

$('select.select-dropbox, input[type="radio"], input[type="checkbox"]').styler({selectSearch:true,});


$('.date-picker').daterangepicker({
	singleDatePicker: true,
	/*autoUpdateInput: false,*/
	calender_style: "picker_2",
	timePicker:true,
	locale: {
		format: 'YYYY-MM-DD h:mm A',
		separator: " - ",
	},
    minDate:new Date(Date.parse(new Date().toLocaleString("en-US", {timeZone: "America/New_York"})))
});



$('#cust_username_').on('change', function () {
    //alert($('#cust_username_').val())
    var username= $('#cust_username_').val();
    //alert(username)

    $.ajax({
        url: project_url +"includes/controller/customerController.php",
        dataType: "json",
        type: "post",
        async: false,
        data: {
            q: "duplicate_id_check",
            userInfo: username,
            type: "username",
        },
        success: function(data){
            if(data==0){
                $('#cust_username_').focus();
                $('#cust_username_').css("background-color","FFCCCB");
                success_or_error_msg('#username_error','danger',"Username is not available","#emp_name");
            }
            else {
                $('#cust_username_').css("background-color","");
            }
        }
    });


})

$('#cust_email_').on('change', function () {
    var userMail= $('#cust_email_').val();
    $.ajax({
        url: project_url +"includes/controller/customerController.php",
            dataType: "json",
            type: "post",
            async: false,
            data: {
                q: "duplicate_id_check",
                userInfo: userMail,
                type: "usermail",
            },
        success: function(data){
            //alert(data)
            if(data==0){
                $('#cust_email_').focus();
                $('#cust_email_').css("background-color","#FFCCCB");
                success_or_error_msg('#email_error','danger',"Email is already registered","#emp_name");
            }
            else {
                $('#cust_email_').css("background-color","");
            }
        }
    });




})



$('#coupon').on('change',function () {
    //alert('sdf')
    var coupon_code = $('#coupon').val();
    if(coupon_code !=""){
        $.ajax({
            url: project_url +"includes/controller/ecommerceController.php",
            type:'POST',
            async:false,
            data: "q=apply_cupon&cupon_code="+coupon_code,
            success: function(data){

                if(data==1){
                    success_or_error_msg('#coupon_error','success',"Coupon Code added. ","#coupon");


                }else if(data==2){
                    success_or_error_msg('#coupon_error','danger',"Coupon Code is not valid. ","#coupon");

                }
                //alert(data)

                order_summary()
                //location.reload();
            }
        });
    }
})


load_customer_profile = function load_customer_profile(id){
$.ajax({
		url:project_url +"includes/controller/ecommerceController.php",
		dataType: "json",
		type: "post",
		async:false,
		data: {
			q: "get_customer_details",
			customer_id: customer_id,
		},
		success: function(data){
			if(!jQuery.isEmptyObject(data.records)){
				$.each(data.records, function(i,data){
					$('#customer_id').html(data.customer_id);
					$('#customer_name').html(data.full_name);
					$('#contact_no').html(data.contact_no);
					$('#email').html(data.email);
					$('#address').html(data.address);
					$('#customer_status').html(data.status_text);
					$('#loyalty_points').html(data.loyalty_points);
					loyalty_points = data.loyalty_points;
					//
                    // alert(loyalty_points+'---')
					if(data.photo == ""){
						$('#customer_img').attr("src",'admin/images/no_image.png');
					}else{
						$('#customer_img').attr("src","admin/"+data.photo);
					}
					$('#customer_img').attr("width", "70%","height","70%");
				});

			}
			//alert(loyalty_points)
		}
	});
}
load_customer_profile()
display_div = function display_div(){
	$("#login_div").css("display", "none");
	$("#register_div").css("display", "none");
	$("#forget_pass_div").css("display", "none");
	$("#profile").css("display", "none");
	$("#take_out").css("display", "none");
	$("#payments").css("display", "none");
	document.getElementById("userDetails").classList.remove('active');
	document.getElementById("take_out_menu").classList.remove('active');
	document.getElementById("payments_menu").classList.remove('active');
}

user_details = function user_details(){
	if(customer_id && customer_id>0){
		//alert(customer_id)
		display_div()
		//document.getElementById("userDetails").classList.add('active');
		//$("#profile").css("display", "block");
	}
	else {
		display_div()
		login()
	}
}
login = function login() {
	display_div()
	document.getElementById("userDetails").classList.add('active');
	$("#login_div").css("display", "block");
}
registration = function registration() {
	display_div()
	document.getElementById("userDetails").classList.add('active');
	$("#register_div").css("display", "block");
}
forgetPass = function forgetPass() {
	display_div()
	document.getElementById("userDetails").classList.add('active');
	$("#forget_pass_div").css("display", "block");
}

take_out = function take_out(){
    load_customer_profile()
    display_div()
    document.getElementById("take_out_menu").classList.add('active');
    $("#take_out").css("display", "block");
}

payments = function payments(){
    display_div()
    document.getElementById("payments_menu").classList.add('active');
    $("#payments").css("display", "block");



    if(loyalty_points/loyalty_point_value<$('#total_paid_amount').val()){
        $('#loyalty_redio').attr('disabled',true);
    }
}

general_settings = function general_settings(){
    $.ajax({
        url:project_url +"includes/controller/ecommerceController.php",
        dataType: "json",
        type: "post",
        async:false,
        data: {
            q: "get_settings_details",
        },
        success: function(data){
            //alert($('#total_paid_amount').val())
            //alert(loyalty_points)
            //alert(loyalty_point_value)
            html=''
            if(!jQuery.isEmptyObject(data.records)){
                $.each(data.records, function(i,data){
                    loyalty_point_value=data.redeem_value;
                    loyalty_reserve_value=data.point_reserve_value;
                    $('#take_out_location_').html(data.store_address);
                    if(data.cash_payment==1){
                        html+='<div class="payment-mode">\n' +
                            '       <input type="radio" name="payment_method" value="1" onclick=""><label style="padding-left: 10px; padding-top: 10px;">Cash on Delivery</label>\n' +
                            '  </div>'
                    }
                    if(data.loyelty_payment==1 ){
                       // alert(loyalty_points+'-+-')

                        html+='<div class="payment-mode">\n' +
                            '      <input type="radio" name="payment_method" id="loyalty_redio" value="2"  onclick=""><label style="padding-left: 10px; padding-top: 10px;" id="use_loyalty_point">Use Loyalty Point <span id="loyalty_spend"></span></label>\n' +
                            '  </div>'
                    }
                    if(data.paypal==1){
                        html+='<div class="payment-mode">\n' +
                            '      <input type="radio" name="payment_method" value="3"  onclick=""><label style="padding-left: 10px; padding-top: 10px;">Paypal</label>'
                        if(data.payment_card_visa==1){
                            html+='<img src="./images/payments/visa.png" style="height: 30px">'
                        }
                        if(data.payment_card_master==1){
                            html+='<img src="./images/payments/mastercard.png" style="height: 30px">'
                        }
                        if(data.payment_card_amex==1){
                            html+='<img src="./images/payments/amex.png" style="height: 30px">'
                        }
                        if(data.payment_card_discover==1){
                            html+='<img src="./images/payments/discover.png" style="height: 30px">'
                        }
                        html+='</div>'
                    }
                });
            }
			alert(html);
            $('#payment_body').html(html);

        }
    });

}
general_settings()
$("input[type='checkbox']").on('ifChanged', function (e) {
    $(this).val(e.target.checked == true);
    //alert('sdf')
});

$("input[name='tips_percentage']").change(function(){
    var base_price =parseFloat($('#total_order_amt').val());

    if($(this).val()==100){
        $('#tips').css('display','block')
        $('#fieldName').attr("read", false)
        //$('#tips').attr()
    }
    else {
        //alert(base_price)
        $('#tips').val((base_price*parseInt($(this).val())/100).toFixed(2))
        $('#fieldName').attr("disabled", true)
    }
    $('#tips').trigger('change')


    // Do something interesting here
});

$('#tips').on('change',function () {
    //alert('ok')
    //var tips_am =
    $('#tips_').html(currency_symbol+''+parseFloat($('#tips').val()).toFixed(2))

    total_amt = parseFloat($('#total_order_amt').val())+parseFloat($('#tips').val())
    //alert(total_amt)
    $('#total_amount_').html(currency_symbol+''+total_amt.toFixed(2))

    $('#total_paid_amount').val(total_amt.toFixed(2))


    //set loyalty point expense for this order
    $('#loyalty_spend').html("(This order needs "+Math.ceil(total_amt*loyalty_point_value)+" point, you have "+loyalty_points+" points)" )
    if(Math.ceil(total_amt*loyalty_point_value)>loyalty_points){
        $('#use_loyalty_point').css('color','gray')
    }
    $('#loyalty_point_earn').html(Math.floor(total_amt/loyalty_reserve_value)+' points will earn')

})


order_summary = function order_summary(){
    $.ajax({
        url: project_url +"includes/controller/ecommerceController.php",
        dataType: "json",
        type: "post",
        async:false,
        data: {
            q: "viewCartSummery"
        },
        success: function(data) {
            //alert(data);
            if(!jQuery.isEmptyObject(data.records)){
                var html = '';
                var total = 0;
                var sub_total = 0;
                var count =0
                $.each(data.records, function(i,datas){
                    //alert(datas.quantity)

                    sub_total +=( parseFloat(datas.discounted_rate)*parseFloat(datas.quantity)).toFixed(2);

                    html+='<p><span class="text-capitalize">'+datas.item_name+'</span> x'+datas.quantity+' <small>'+ currency_symbol+''+(datas.discounted_rate * datas.quantity).toFixed(2)+'</small></p>\n'
                });
                $('#cart_summary').html(html);
            }
            $.ajax({
                url: project_url +"includes/controller/ecommerceController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "viewPriceSummery"
                },
                success: function(data) {
                    if(data){
                        //alert(loyalty_reserve_value)
                        //$('#items_price').html(data['total_price'])
                        $('#cart_total_').html(currency_symbol+''+data['total_price'].toFixed(2));
                        $('#discount_').html(currency_symbol+''+data['discount'].toFixed(2))
                        $('#tax_').html(currency_symbol+''+data['tax_amount'].toFixed(2))
                        $('#loyalty_point_earn').html(Math.floor(data['discounted_price']/loyalty_reserve_value)+' points will earn')
                        $('#total_order_amt').val(parseFloat(data['discounted_price']).toFixed(2))
                        $('#tax_amount').val(parseFloat(data['tax_amount']).toFixed(2))
                        if($('#tips').val()){
                            $('#total_paid_amount').val((parseFloat(data['discounted_price'])+parseFloat($('#tips').val())).toFixed(2))
                        }
                        else{
                            $('#total_paid_amount').val(parseFloat(data['discounted_price']).toFixed(2))
                        }
                        $('#total_amount_').html(currency_symbol+''+parseFloat($('#total_paid_amount').val()).toFixed(2))
                        $('#loyalty_spend').html("("+Math.ceil(data['discounted_price']*loyalty_point_value)+" point will spend; you have "+loyalty_points+")" )
                        $('#loyalty_spend').html("(This order needs "+Math.ceil(data['discounted_price']*loyalty_point_value)+" point, you have "+loyalty_points+" points)" )
                        if(Math.ceil(data['discounted_price']*loyalty_point_value)>loyalty_points){
                            $('#use_loyalty_point').css('color','gray')
                        }
                    }
                }
            });

        }
    });

}

order_summary()


//load_customer_profile()

$('#login').click(function(event){

        event.preventDefault();
        var formData = new FormData($('#login_form')[0]);
        formData.append("q","login_customer");
        if($.trim($('#username_').val()) == ""){
            success_or_error_msg('#loginerror_','danger',"Please type user name","#emp_name");
        }
        if($.trim($('#password_').val()) == ""){
            success_or_error_msg('#loginerror_','danger',"Please type password","#password");
        }
        else{
            $.ajax({
                url: project_url +"includes/controller/customerController.php",
                type:'POST',
                data:formData,
                async:false,
                cache:false,
                contentType:false,processData:false,
                success: function(data){
                    //alert(data)
                    if($.isNumeric(data)==true && data==3){
                        success_or_error_msg('#loginerror_',"danger","Invalid username","#user_name" );
                    }
                    else if($.isNumeric(data)==true && data==2){
                        success_or_error_msg('#loginerror_',"danger","Invalid password","#password" );
                    }
                    else if($.isNumeric(data)==true && data==1){

                        $('.language-menu').html('<a href="account.php" class="current-lang" id="my_acc"><i class="fa fa-user" aria-hidden="true" ></i> My Account</a>');
                        if($('#islogged_in').length > 0 ){
                            $('#islogged_in').val(1);
                            $('.logged_in_already').addClass('hide');
                        }
                        window.location.href = project_url+"index.php?page=checkout";

                    }
                }
            });
        }
    })


$('#foget_pass_submit_').click(function(event){
    event.preventDefault();
        var formData = new FormData($('#forget_pass_form')[0]);
        formData.append("q","forget_password");
        if($.trim($('#forget_email_').val()) == ""){
            success_or_error_msg('#foget_pass_submit_error','danger',"Please enter email address","#forget_email");
        }
        else{
            $.ajax({
                url: project_url +"includes/controller/customerController.php",
                type:'POST',
                data:formData,
                async:false,
                cache:false,
                contentType:false,processData:false,
                success: function(data){
                    //alert(data)
                    if($.isNumeric(data)==true && data==2){
                        success_or_error_msg('#foget_pass_submit_error_',"danger","Please provide a valid email address","#forget_email" );
                    }
                    else if($.isNumeric(data)==true && data==1){

                        $('.sent_password').addClass("hide");
                        $('.sent_password_msg').removeClass("hide");
                        setTimeout(function() { login() }, 3000);
                    }
                                        }
            });
        }
    })

// send mail if forget password
$('#register_submit_').click(function(event){
    event.preventDefault();
    var formData = new FormData($('#register_form')[0]);
    formData.append("q","registration");
    if($.trim($('#cust_name_').val()) == ""){
        success_or_error_msg('#registration_submit_error_','danger',"Please enter name","#cust_name");
    }
    else if($.trim($('#cust_username_').val()) == ""){
        success_or_error_msg('#registration_submit_error_','danger',"Please enter username","#cust_username");
    }
    else if($.trim($('#cust_email_').val()) == ""){
        success_or_error_msg('#registration_submit_error_','danger',"Please enter email address","#cust_email");
    }
    else if($.trim($('#cust_password_').val()) == ""){
        success_or_error_msg('#registration_submit_error_','danger',"Please enter pasword","#cust_password");
    }
    else if($.trim($('#cust_conf_password_').val()) == ""){
        success_or_error_msg('#registration_submit_error_','danger',"Please confirm password ","#cust_conf_password");
    }
    else if($.trim($('#cust_password_').val()) != $.trim($('#cust_conf_password_').val())){
        success_or_error_msg('#registration_submit_error_','danger',"Please enter same password","#cust_conf_password");
    }
    else if($.trim($('#cust_contact_').val()) == ""){
        success_or_error_msg('#registration_submit_error_','danger',"Please enter valid contact no","#cust_contact");
    }
    else{
		if($.trim($('#cust_email').val()) != ""){
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			if(!re.test($.trim($('#cust_email').val()))){
				success_or_error_msg('#registration_submit_error_','danger',"Please Insert a valid email address","#cust_email");
				return false;
			}
		}
		
        $.ajax({
            url: project_url +"includes/controller/customerController.php",
            type:'POST',
            data:formData,
            async:false,
            cache:false,
            contentType:false,processData:false,
            success: function(data){
                //
                // alert(data)
                if($.isNumeric(data)==true && data==2){
                    success_or_error_msg('#registration_submit_error_',"danger","Username is already exist, please try with another one","#cust_username" );
                }
                else if($.isNumeric(data)==true && data==3){
                    success_or_error_msg('#registration_submit_error_',"danger","Email is already exist, please try with another one","#cust_email" );
                }
                else if($.isNumeric(data)==true && data==1){
                    $('.done_registration').addClass("hide");
                    $('.done_registration_msg').removeClass("hide");
                    window.location.href = project_url+"index.php?page=checkout";
                }
                else{
                    success_or_error_msg('#registration_submit_error_',"danger","Registration is not completed. please check your information again.","#cust_email" );
                }
            }
        });
    }
})


function dateCheck() {
    //alert('came')
    var timeNow = new Date().toLocaleString("en-US", {timeZone: "America/New_York"});
    timeNow = new Date(Date.parse(timeNow));

   // setDate(timeNow)
    var usaTime = new Date(Date.parse($.trim($('#pickup_date_time').val())));

    if(timeNow.getDate()==usaTime.getDate() && timeNow.getMonth()==usaTime.getMonth() &&timeNow.getFullYear()==usaTime.getFullYear() ){

       //alert('in')
        hoursNow = timeNow.getHours();
        minutesNow = timeNow.getMinutes()
        timeNow = hoursNow*60 +minutesNow+30; // 30 is the minimum time for prepare food

        hourOrder = usaTime.getHours();
        minutesOrder = usaTime.getMinutes()
        timeorder = hourOrder*60 +minutesOrder;

        if(timeNow>timeorder){
            //alert(timeNow-timeorder)
            return 'Please Select a time that at least 30 Minutes from now';
        }

    }

    if(timeNow>usaTime){
        //alert('sadf')
        return 'Please Select an upcoming time';
    }

    date = $.trim($('#pickup_date_time').val());
    day = usaTime.getDay()
    time = usaTime.getHours() + ':' + usaTime.getMinutes() + ':' + usaTime.getSeconds()

    data = 0
    $.ajax({
        url: project_url + "includes/controller/ecommerceController.php",
        dataType: "json",
        type: "post",
        async: false,
        data: {
            q: "date_checker",
            date: date,
            day: day,
            time: time
        },
        success: function (datas) {

            data= parseInt(datas)

        }
    });

    if (data==1) return data;
    else if(data==2) return 'Please Select Another Date: The Shop will close That day';
    else if (data==3) return 'Please Choose Another Time: The Shop will close your selected time';
    else if(data==4) return 'We do not serve at Your selected time';
}

$('#checkout_submit').click(function(event) {

    //alert('call')
    timeValidation = dateCheck()
    //alert(timeValidation)

    event.preventDefault();

    var loyalty_value = Math.floor( $('#total_paid_amount').val()/loyalty_reserve_value);
    var loyalty_deduct = 0;
    if($('input[name=payment_method]:checked', '#checkout-form').val()==2){
        loyalty_deduct = Math.ceil($('#total_paid_amount').val()*loyalty_point_value);
    }

    delevery_type = $("[name='delevery_type']:checked").val();
    payment_type  = $("[name='payment_type']:checked").val();
    var formData = new FormData($('#checkout-form')[0]);
    formData.append("q","checkout");
    formData.append("loyalty_point",loyalty_value);
    formData.append("loyalty_deduct",loyalty_deduct);
    formData.append("order_from",1);
    formData.append("grand_total",$('#total_amount_').html());

    if($('input[name=payment_method]:checked', '#checkout-form').val()){
        formData.append("payment_method",$('input[name=payment_method]:checked', '#checkout-form').val());

    }
    if(timeValidation!=1){
        //alert(timeValidation)
        success_or_error_msg('#logn_reg_error','danger',timeValidation,"#pickup_date_time");
    }
    else if($.trim($('#islogged_in').val()) == "0"){
        success_or_error_msg('#logn_reg_error','danger',"You must have to login or register if you are new customer. ","#forget_email");
    }
    else if(!$('input[name=take_out_location]:checked', '#checkout-form').val()){
        success_or_error_msg('#logn_reg_error','danger',"You must confirm the takeout location. ","#pickup_date_time");
    }
    else if($.trim($('#pickup_date_time').val()) == ""){
        success_or_error_msg('#logn_reg_error','danger',"You must enter the delivery/pickup date time. ","#pickup_date_time");
    }
    else if( delevery_type== 2 && $('#delivery_address').val() == ""){
        success_or_error_msg('#logn_reg_error','danger',"You must enter the delivery address. ","#delivery_address");
    }
    else if(!$('input[name=payment_method]:checked', '#checkout-form').val()){
        success_or_error_msg('#logn_reg_error','danger',"You must select a payment method. ","#reference_no");
    }
    else if(!$('input[name=tarm_condition]:checked', '#checkout-form').val()){
        success_or_error_msg('#logn_reg_error','danger',"Please confirm the tarms and conditions ","#reference_no");
    }
    else{
        $.ajax({
            url: "includes/controller/ecommerceController.php",
            type:'POST',
            data:formData,
            async:false,
            cache:false,
            contentType:false,processData:false,
            success: function(data){
               // console.log(data);return;
                if(data==0){
                    success_or_error_msg('#logn_reg_error',"danger","Order failed. please check your information properly","#checkout_submit" );
                }
                else{
                    //alert('done')
                    if($('input[name=payment_method]:checked', '#checkout-form').val()==3){

                        $('#item_name').val(data)
                        $('#item_number').val(data)
                        $('#amount_total').val($('#total_paid_amount').val())

                        var new_formData = new FormData($('#paypal_form')[0]);
                        new_formData.append("next_url",project_url+"index.php?page=checkout_confirm");
						new_formData.append("project_url",project_url);

                        $.ajax({
                            url: project_url+"includes/controller/payments.php",
                            type:'POST',
                            type:'POST',
                            data:new_formData,
                            async:false,
                            cache:false,
                            contentType:false,processData:false,
                            success: function(data){
                                //alert('ok')
                                url ='https://'+data.split('https://')[1]
                                //alert(url)

                                window.location.href = url;
                                //console.log(data)
                                showCart()
                                // alert(data)
                            }
                        });
                    }
                    else {
                        showCart()
                        $("#content").load("views/checkout_confirm.php");
                    }

                }
            }
        });
    }
})

/*
function paypal(){
	$('#item_name').val('BB042004668')
	$('#item_number').val('BB042004668')
	$('#amount_total').val('22.33')

	var new_formData = new FormData($('#paypal_form')[0]);
	new_formData.append("next_url",project_url+"index.php?page=checkout_confirm");
	new_formData.append("project_url",project_url);

	$.ajax({
		url: "includes/controller/payments.php",
		type:'POST',
		type:'POST',
		data:new_formData,
		async:false,
		cache:false,
		contentType:false,processData:false,
		success: function(data){
			//alert('ok')
			url ='https://'+data.split('https://')[1]
			//alert(url)

			//window.location.href = url;
			//console.log(data)
			showCart()
			// alert(data)
		}
	});
}
*/



    <?php
    if($is_logged_in_customer != ""){
    ?>
    var customer_id = "<?php echo $customer_id; ?>";
    var order_id = "<?php echo $order_id; ?>";
    //user_details()
    take_out();
    $('#userDetails').css("display", "none");
    //alert('pro')

    <?php
    }
    else{
    ?>
    //alert('login')
    display_div()
    login()
    //$("#login_div").css("display", "block");
    //alert('ok3')

    <?php
    }?>
</script>







