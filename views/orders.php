<?php
session_start();
include("../includes/dbConnect.php");
include("../includes/dbClass.php");
$dbClass = new dbClass;
$is_logged_in_customer = "";
$website_url  = $dbClass->getDescription('website_url');
$currency   = $dbClass->getDescription('currency_symbol');

$logo         =$website_url."admin/".$dbClass->getDescription('company_logo');


if(!isset($_SESSION['customer_id']) && $_SESSION['customer_id']!=""){ ob_start(); header("Location:index.php"); exit();}
else $is_logged_in_customer = 1;
$customer_id = $_SESSION['customer_id'];
$orders_info = $dbClass->getResultList("SELECT invoice_no order_no, order_id,  payment_status, 
				ASAP, TIME_FORMAT(order_date, '%h:%i %p') as order_date, TIME_FORMAT(delivery_date, '%h:%i %p') as delivery_date,
				CASE order_status when 1 then 'Ordered' when 3 then 'Received' when 2 then 'Rejected' when 4 then 'Ready' else 'Picked Up' end order_status,
				total_order_amt,total_paid_amount
				FROM order_master
				WHERE customer_id=$customer_id 
				order by order_id desc
				");
if(empty($orders_info)){
    echo "<div class='col-md-12 text-center'><h6 class='center'>Your have no orders </h6><div>";
}
else{


    ?>
    <h6 class="center">Your Order List </h6>
    <hr>
    <section class="home-icon shop-cart bg-skeen" style="padding-top: 20px">
        <div class="container" style="max-width:100%" id="oredrs_div">
            <table class="table table-bordered table-hover" id="table_big" style=" background-color: white">
                <thead>
                <tr style="background-color: #fbc314; alignment: center">
                    <th>Order No</th>
                    <th class='hide-xs'>Order Date</th>
                    <th class='hide-xs'>Delivery date</th>
                    <th>Amount</th>
                    <th>Paid</th>
                    <th class='hide-xs'>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <?php
                    foreach($orders_info as $order){
                        $order_no = '"'.$order['order_no'].'"';
                        $bbg = '';

                        if(substr( $order['order_no'], 0, 3 ) === "LBG"){
                            $bbg = '';
                        }else{
                            $bbg =  "<button class='btn btn-primary-gold btn-xs'><i class='fa fa-repeat pointer' onclick='repeat_order(".$order_no.")' title='Repeat Order'></i></button>" ;
                        }


                        if($order['payment_status']==1){
                            $paid="<button class='btn btn-primary btn-sm pay_".$order['order_no']."' onclick='makePayment(".$order_no.",". $order['total_order_amt'].")'>Pay</button>";
                        }else{
                            $paid = $currency."".$order['total_paid_amount'];
                        }
						$delivery_date = ($order['ASAP'])?"<span style='background-color:lime'>Pickup: ASAP</span>":"<span style='background-color:orange'>Scheduled Pickup Time:". $order['delivery_date']."</span>";
                        echo
                            "<tr>
							  <td style='font-weight: bold' ><button onclick='order_tracking(".$order_no.")' style='background: none; border: none'>".$order['order_no']."</button></td>
							  <td class='hide-xs'>".$order['order_date']."</td>
							  <td class='hide-xs'>".$delivery_date."</td>
							  <td class='text-right'>".$currency."".$order['total_order_amt']."</td>
							  <td>".$paid."</td>
							  <td class='hide-xs'>".$order['order_status']."</td>							  
							  <td><button class='btn btn-primary btn-xs' onclick='view_order(".$order_no.")' ><i class='fa fa-search-plus pointer'  title='View Order'/></i></button>
							  ".$bbg."
							  </td>
						  </tr>
						";
                    }
                    ?>
                </tr>
                </tbody>
            </table>




        </div>
    </section>
    <?php
}
?>
<div class="modal fade booktable" id="order_modal" tabindex="-2" role="dialog" aria-labelledby="booktable">
    <div class="modal-dialog width_80_p" role="document" >
        <div class="modal-content">
            <div class="modal-body" style="margin-bottom: 50px">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div id="order-div" >
                    <div class="title text-center">
                        <h3 class="text-coffee left"> <a href="index.php"><img src="<?php echo ($logo); ?>" alt="" style="height: 100px; width: 100px"></a></h3>
                    </div>
                    <div class="done_registration ">
                        <div class="doc_content">
                            <div class="col-md-12 left-margin-0" style=" padding: 0px; margin-bottom: 20px">
                                <div class="col-md-6" style="margin: 0px; padding: 0px">
                                    <h4>Order Details:</h4>
                                    <div class="byline">
                                        <span class="after_order_initiate" id="inv_no"></span><br/>
                                        <span id="order_status"></span><br/>
                                        <span id="ord_date"></span><br/>
                                        <span id="dlv_date"></span> <br/>

                                    </div>
                                </div>
                                <div class="col-md-6 text-right text-right-l left-margin-0  left-padding-0">
                                    <h4>Customer Details:</h4>
                                    <address id="customer_detail_vw">
                                    </address>
                                </div>

                            </div>

                            <div id="ord_detail_vw">
                                <table class="table table-bordered" id="ord_detail_vw_big" >
                                    <thead>
                                    <tr>
                                        <th align="center">Items</th>
                                        <th width="10%" align="center">Quantity</th>
                                        <th width="12%" style="text-align:right">Rate</th>
                                        <th width="12%"  style="text-align:right">Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <table class="table table-bordered" id="ord_detail_vw_small" style="display: none" >
                                    <thead>
                                    <tr>
                                        <th align="center">Items</th>
                                        <th width="12%"  style="text-align:right">Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                                <p>Note: <span id="note_vw"></span></p>
                                <p>Print Time : <?php echo date("Y-m-d h:m:s"); ?></p>
                                <br />

                                <p style="font-weight:bold; text-align:center" id="thankingNoted">Thank you. Hope we will see you soon </p>
                            </div>

                        </div>


                    </div>
                </div>


                <div class="col-md-12 hidden-print" style="text-align: center" id="print_repeat_order"> </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade booktable" id="payment" tabindex="-2" role="dialog" aria-labelledby="booktable">
    <div class="modal-dialog width_80_p" role="document" >
        <div class="modal-content">
            <div class="modal-body" style="margin-bottom: 50px">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <div id="order-div" >
                    <div class="title text-center">
                        <h3 class="text-coffee left" id="payment_head"> Make Payment</h3>
                    </div>
                    <div class="done_registration ">
                        <div class="doc_content">
                            <h4>Payment Methods</h4>
                            <input type="hidden"  id="grand_total">
                            <form class="" method="post" id="non_paypal_form">

                                <div class="payment_body" id="payment_body"></div>
                                <div id="payment_alert" class="text-center" style="display:none"></div>
                                <input type="hidden" name="order_no" id="order_no" value="" />
                                <input type="hidden" name="paid_amount" id="paid_amount" value="" />
                            </form>

                            <form class="paypal" method="post" id="paypal_form_order">
                                <input type="hidden" name="cmd" value="_xclick" />
                                <input type="hidden" name="no_note" value="1" />
                                <input type="hidden" name="lc" value="" />
                                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                                <input type="hidden" name="first_name" value="Customer's First Name" />
                                <input type="hidden" name="last_name" value="Customer's Last Name" />
                                <input type="hidden" name="payer_email" value="customer@example.com" />
                                <input type="hidden" name="item_number" id="item_number" value="" />
                                <input type="hidden" name="item_name" id="item_name" value="" />
                                <input type="hidden" name="amount_total" id="amount_total" value="" />
                            </form>
                            <div class="col-md-12" style="text-align: center"> <button type="button" class="btn btn-warning" id="make_payment">Make Payment</button></div>
                            <div id="logn_reg_error" class="text-center" style="display:none"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<!-- Start Order details -->


<!-- End order -->

<script>
    localStorage.setItem("currenturl", "orders");
    loyalty_point_value=1;
    loyalty_reserve_value=1;
    loyalty_points=0;
    customer_id=<?php echo $customer_id; ?>
	

    var print_module=''
    //alert("sWidth is: " + sWidth);

    var view_order = function view_order(order_id){
        //alert('ok')
        $('#ord_detail_vw>table>tbody').html('');
        $('#print_repeat_order').html('<button type="button" class="btn btn-warning" id="order_print"><i class="fa fa-lg fa-print"></i></button>')

        if(!order_id.includes("BBG")){
            $.ajax({
                url:project_url +"includes/controller/ecommerceController.php",
                type:'GET',
                async:false,
                dataType: "json",
                data:{
                    q: "get_order_details_by_invoice",
                    order_id:order_id
                },
                success: function(data){
                    repeat =  "<button class='btn btn-primary'><i class='fa fa-repeat pointer' onclick='repeat_order(`"+order_id+"`)'></i></button>"
                    //alert(data.item_id)
                    $('#print_repeat_order').append(repeat)
                    console.log(data)
                    if(!jQuery.isEmptyObject(data.records)){
                        $.each(data.records, function(i,data){
                            //$('#ord_title_vw').html(data.invoice_no);
                            $('#inv_no').html("Invoice Number: "+data.invoice_no);
                            $('#order_status').html("Order Status: "+data.order_status_text);
                            $('#ord_date').html("Ordered time: "+data.order_date);
                            //$('#dlv_date').html("Delivery time: "+data.delivery_date);
                            $('#customer_detail_vw').html(" "+data.customer_name+"<br/><b>Mobile:</b> "+data.customer_contact_no+"<br/><b>Address:</b> "+data.customer_address);
                            $('#note_vw').html(data.remarks);

                            if(data.ASAP==1){
                                $('#dlv_date').html("<span style='background-color:Lime'>Pickup:<b> (ASAP)</b><span>");
                            }
                            else {
                                $('#dlv_date').html("<span style='background-color:orange'>Scheduled Pickup Time: "+data.delivery_date+"</span>");
                            }


                            var order_tr = "";
                            var order_total = 0;
                            order_infos	 = data.order_info;
                            var order_arr = order_infos.split('..,');
                            $.each(order_arr, function(i,orderInfo){
                                //alert(orderInfo)
                                var order_info_arr = orderInfo.split('#');
                                var total = ((parseFloat(order_info_arr[4])*parseFloat(order_info_arr[5])));
                                order_tr += '<tr><td class="text-capitalize">'+order_info_arr[2]+' <br>'+order_info_arr[6]+'<br><i style="color: black">'+order_info_arr[7].split('..')[0]+'</i></td><td align="center">'+order_info_arr[5]+'</td><td align="right">'+currency_symbol+''+order_info_arr[4]+'</td><td align="right">'+currency_symbol+''+total+'</td></tr>';
                                order_total += total;
                            });
                            var total_order_bill = ((parseFloat(order_total)+parseFloat(data.delivery_charge))-parseFloat(data.discount_amount));
                            var total_paid = data.total_paid_amount;
                            order_tr += '<tr><td colspan="3" align="right" ><b>Discount</b></td><td align="right"><b>'+currency_symbol+''+data.discount_amount+'</b></td></tr>';
                            order_tr += '<tr><td colspan="3" align="right" ><b>Tax</b></td><td align="right"><b>'+currency_symbol+''+data.tax_amount+'</b></td></tr>';
                            order_tr += '<tr><td colspan="3" align="right" ><b>Tips</b></td><td align="right"><b>'+currency_symbol+''+data.tips+'</b></td></tr>';
                            order_tr += '<tr><td colspan="3" align="right" ><b>Total Amount</b></td><td align="right"><b>'+currency_symbol+''+total_paid+'</b></td></tr>';
                            $('#ord_detail_vw>table>tbody').append(order_tr);



                            //for small device

                        });
                    }
                }
            });

        }
        else {
            $.ajax({
                url:project_url +"includes/controller/groupController.php",
                type:'GET',
                async:false,
                dataType: "json",
                data:{
                    q: "get_group_order_details_by_invoice",
                    order_id:order_id
                },
                success: function(data){
                    //alert(data)
                    //console.log(data)
                    if(!jQuery.isEmptyObject(data.order_details)) {

                        //$('#ord_title_vw').html(data.order_details.name);
                        $('#inv_no').html("Invoice Number: "+data.order_details.invoice_no);						
                        $('#ord_date').html("Ordered time: "+data.order_details.order_date);
                        $('#dlv_date').html("<span style='background-color:orange'>Scheduled Pickup Time: "+data.order_details.delivery_date+"</span>");
                        $('#ntf_date').html("Notification time: "+data.order_details.notification_time);
                        $('#order_status').html("Order Status: "+data.order_details.order_status);
                        $('#customer_detail_vw').html(" "+data.order_details.full_name+"<br/><b>Mobile:</b> "+data.order_details.mobile+"<br/><b>Address:</b> "+data.order_details.c_address);
                        //$('#note_vw').html(data.remarks);
                    }
                    let order_status = parseInt(data['order_details']['status'])
                    if(order_status>3){
                        $('.before_order_initiate').css('display','none')
                        $('.after_order_initiate').css('display','block')

                    }

                    if(!jQuery.isEmptyObject(data.records)){
                        var sub_total=0;
                        $.each(data.records, function(i,data){
                            //console.log(data)
                            //alert(data['id'])

                            var order_tr = '';//for big screen
                            var order_total = 0;
                            var order_tr_small = ''; //for small screen

                            order_infos	 = data['order_info'];
                            var order_arr = order_infos.split('..,');
                            if(!order_arr[0] && order_status<4){
                                order_tr+='<tr><td colspan="4" align="left"  ><b>'+data['name']+' </b> ('+data['email']+')</td>'
                                order_tr_small+='<tr><td colspan="2" align="left"  ><b>'+data['name']+' </b> ('+data['email']+')</td>'
                                var tem = data['group_order_details_id']+'&'+data['order_key']
                                order_tr += '<tr><td class="text-capitalize">Not Selected<br><a href="#" onclick="selectItems('+order_id+','+"'"+data['group_order_details_id']+'&'+data['order_key']+"'"+')">Click here to Select item for <b>'+data['name']+'<b></a></td><td align="center"></td><td align="right"></td><td align="right">'+currency_symbol+''+'00'+'</td></tr>';
                                order_tr_small+='<tr><td class="text-capitalize">Not Selected<br><a href="#" onclick="selectItems('+order_id+','+"'"+data['group_order_details_id']+'&'+data['order_key']+"'"+')">Click here to Select item for <b>'+data['name']+'<b></a></td><td align="right">'+currency_symbol+''+'00'+'</td></tr>';

                            }
                            else if(order_arr[0]){
                                order_tr+='<tr><td colspan="4" align="left"  ><b>'+data['name']+' </b> ('+data['email']+')</td>'
                                order_tr_small+='<tr><td colspan="2" align="left"  ><b>'+data['name']+' </b> ('+data['email']+')</td>'
                                $.each(order_arr, function(i,orderInfo){
                                    //alert(orderInfo)
                                    var order_info_arr = orderInfo.split('#');
                                    var total = ((parseFloat(order_info_arr[4])*parseFloat(order_info_arr[5])));
                                    order_tr += '<tr><td class="text-capitalize">'+order_info_arr[2]+' <br>'+order_info_arr[6]+'<br><i style="color: black">'+order_info_arr[7].split('..')[0]+'</i></td><td align="center">'+order_info_arr[5]+'</td><td align="right">'+currency_symbol+''+order_info_arr[4]+'</td><td align="right">'+currency_symbol+''+total+'</td></tr>';
                                    order_tr_small+='<tr><td class="text-capitalize">'+order_info_arr[2]+':'+order_info_arr[5]+'X'+currency_symbol+''+order_info_arr[4]+'<br>'+order_info_arr[6]+'</td><td align="right">'+currency_symbol+''+total+'</td></tr>';

                                    order_total += total;
                                });
                                sub_total += order_total;
                                order_tr += '<tr><td colspan="3" align="right" ><b>Total Amount</b></td><td align="right"><b>'+currency_symbol+''+order_total.toFixed(2)+'</b></td></tr>';
                                order_tr_small += '<tr><td align="right" ><b>Total Amount</b></td><td align="right"><b>'+currency_symbol+''+order_total.toFixed(2)+'</b></td></tr>';

                            }

                            $('#ord_detail_vw_big>tbody').append(order_tr);
                            $('#ord_detail_vw_small>tbody').append(order_tr);


                            //for small device

                        });

                        var discount = 0;
                        var tax = 0;

                        discount = parseFloat(data['order_details']['discount_amount'])
                        tax = parseFloat(data['order_details']['tax_amount'])
                        total_paid_amount =  parseFloat(data['order_details']['total_paid_amount'])



                        /*if(data['order_details']['cupon_amount']!=null){
                            if(data['order_details']['c_type']==2){
                                discount =sub_total*data['order_details']['cupon_amount']/100
                            }
                            else  discount =data['order_details']['cupon_amount']
                        }

                        if(data['tax']['tax_enable']!=0){
                            if(data['tax']['tax_enable']==0){
                                tax=(sub_total-discount)*data['tax']['tax_amount']/100
                            }
                            else tax = data['tax']['tax_amount']
                        }*/


                        var order_tr='<tr align="right"><td colspan="3" ><b>Total Order Amount</b></td><td align="right"><b>'+currency_symbol+''+sub_total.toFixed(2)+'</b></td></tr>'
                        order_tr += '<trstyle="display: block><td colspan="3" align="right" ><b>Discount</b></td><td align="right"><b id="discount_amt">'+currency_symbol+''+discount.toFixed(2)+'</b></td></tr>';
                        order_tr += '<trstyle="display: block><td colspan="3" align="right" ><b>Tax</b></td><td align="right"><b id="tax_amt">'+currency_symbol+''+tax.toFixed(2)+'</b></td></tr>';
                        order_tr += '<trstyle="display: block><td colspan="3" align="right" ><b>Tips</b></td><td align="right"><b id="tax_amt">'+currency_symbol+''+data['order_details']['tips']+'</b></td></tr>';
                        order_tr += '<tr><td colspan="3" align="right" ><b>Grand Total Amount</b></td><td align="right"><b id="total_amt">'+currency_symbol+''+(total_paid_amount).toFixed(2)+'</b></td></tr>';


                        $('#ord_detail_vw>table>tbody').append(order_tr);
                    }
                }

            });

        }
        $('#order_modal').modal();
    }

    repeat_order = (order_id) =>{
        $.ajax({
            url: project_url + "includes/controller/ecommerceController.php",
            type: 'POST',
            async: false,
            dataType: "json",
            data: {
                q: "repeat_order",
                order_id: order_id
            },
            success: function (data) {

                data = JSON.parse(data)
                if(data==1){
                    window.location.href = project_url+'cart.php'
                }

            }
        })
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

                            html+='</div>'
                        }
                        if(data.square==1) {
                            html += '<div class="payment-mode">\n' +
                                '      <input type="radio" name="payment_method" value="4"  onclick=""><label style="padding-left: 10px; padding-top: 10px;">Pay By Card (Square)</label>'
                            if (data.payment_card_visa == 1) {
                                html += '<img src="./images/payments/visa.png" style="height: 30px">'
                            }
                            if (data.payment_card_master == 1) {
                                html += '<img src="./images/payments/mastercard.png" style="height: 30px">'
                            }
                            if (data.payment_card_amex == 1) {
                                html += '<img src="./images/payments/amex.png" style="height: 30px">'
                            }
                            if (data.payment_card_discover == 1) {
                                html += '<img src="./images/payments/discover.png" style="height: 30px">'
                            }
                        }
						if(data.mpesa==1){
							let amount = $('#amount_total').val();
							let order_no = $('#order_no').val();
							if(order_no != "" && order_no !== undefined){
								let order_type 	= (order_no.substring(3, 0)=='BBG')?2:1;
								let order_id 	= order_no.substring(order_no.length - 5, order_no.length); 
								 html+='<div class="payment-mode">\n' +
									'<label><input type="radio" id="mpesa_payment" name="payment_method" value="5"  onclick="">Mpesa</label>'
								html+='	<div class="row mpesa_payment_div" style="padding:20px;"> <div class="col-md-8"  style="border-right:1px solid black">'+
									'<h5> Option One:  MPESA EXPRESS</h5>'+
									'<ul style="list-style-type:none;"><li>1. Unlock your phone and stay on your home screen.</li>'+ 
									'<li>2. Enter your M-PESA registered phone number below (No Leading Zero) and submit your order</li>'+														
									'+254 <input  type="text" id="mpesa_mobile" name="mpesa_mobile" maxlength="9" style="display: inline-block;width: 160px; margin-bottom: 5px; height:30px;" value="" class="input-fields" autocomplete="off">'+
									'<li>3.You should receive a pop-up on your mobile handset for an instant payment request for this order. Note, pop-up may time out within 10 seconds</li>'+
									'<li>4.Enter your M-PESA PIN to confirm the payment on</li></ul>'+
									'<div class="divider-login hide"><hr><span>Or</span></div></div>'+
									'<div class="col-md-4"><h5> Option Two: LIPA NA MPESA - Paybill</h5>'+
									'<ul style="list-style-type:none;"><li>1.Send payment to</li>'+						
									'<img style="margin-top:5px" src="./images/payments/mpesa.png" >'+
									'<li>Your account no:'+order_type+order_id+'</li>'+	
									'<li>Amount:'+amount+' </li>'+								
									'</div></div>';
							}
							else
								html+='<div class="alert alert-danger">ERROR!!! Contact with adin</div>';
                        }
                        html+='</div>';
                    });
                }
                $('#payment_body').html(html);
				$( 'input[type="radio"]' ).styler();

				/*$("#mpesa_payment").change(function(){
					alert(1)
					$('.mpesa_payment_div').show()
				});*/	
            }
        });
    }
	
	
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
                        loyalty_points = data.loyalty_points;
                    });

                }
            }
        });
    }


    function order_tracking(order_no) {
        localStorage.setItem("order_no_tracking", order_no)
        show_my_accounts('tracking', order_no)
    }

    function makePayment(id, amount){
        //alert(id, amount)
		$('input[type="radio"]' ).styler();
        $('#payment_head').html('Make Payment for Order ID# '+id)
        load_customer_profile();
		
        if(loyalty_points/loyalty_point_value<amount){
            $('#loyalty_redio').attr('disabled',true);
        }
        $('#item_name').val(id)
        $('#item_number').val(id)
        $('#amount_total').val(amount)
        $('#order_no').val(id)
        $('#paid_amount').val(amount)

        $('#loyalty_spend').html("(You have "+loyalty_points+", you will spend "+Math.ceil(amount*loyalty_point_value)+" on this order)" )
		general_settings();
		
        $('#payment').modal();
    }

    $('#make_payment').on('click', function () {
        //alert('mk pay')
        if($('input[name=payment_method]:checked', '#non_paypal_form').val()=== undefined || $('input[name=payment_method]:checked', '#non_paypal_form').val()== ""){
			success_or_error_msg('#logn_reg_error','danger',"You must select a payment method. ","#non_paypal_form");
			return;
		}

        //alert($('input[name=payment_method]:checked', '#non_paypal_form').val())

        if($('input[name=payment_method]:checked', '#non_paypal_form').val()==3){
            //alert($('#item_name').val())

            var new_formData = new FormData($('#paypal_form_order')[0]);
            new_formData.append("next_url",project_url+"account.php");
            new_formData.append("project_url",project_url);

            $.ajax({
                url: project_url+"includes/controller/payments.php",
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
                    //window.location.href = project_url+'checkout_confirm.php'

                    //console.log(data)
                    showCart()
                    // alert(data)
                }
            });

        }
        else  if($('input[name=payment_method]:checked', '#non_paypal_form').val()==4){
            localStorage.setItem('bill_id',$('#item_number').val())
            localStorage.setItem('amount',$('#amount_total').val())
            localStorage.setItem('sq_success','account.php')

            window.location.href = project_url+'square'

            //$('#item_number').val(data)
            //$('#amount_total').val($('#total_paid_amount').val())
        }
        else {

            var formData = new FormData($('#non_paypal_form')[0]);
            formData.append("next_url",project_url+"index.php?page=account");
            formData.append("q","make_payment");

			if($('input[name=payment_method]:checked', '#non_paypal_form').val()==5){
				if($("#mpesa_mobile").val() == "" ){
					success_or_error_msg('#logn_reg_error','danger',"You must enter mpesa mobile number","#mpesa_mobile");
					return;
				}			
				else if($("#mpesa_mobile").val() != "" && $.isNumeric($("#mpesa_mobile").val())==0){
					success_or_error_msg('#logn_reg_error','danger',"Please enter proper mpesa mobile no","#mpesa_mobile");
					return;
				}
				formData.append("mpesa_mobile",$("#mpesa_mobile").val());	    
			}
			$('#make_payment').attr('disabled','disabled');		
            $.ajax({
                url: project_url+"includes/controller/ecommerceController.php",
                type:'POST',
                type:'POST',
                data:formData,
                async:false,
                cache:false,
                contentType:false,processData:false,
                success: function(data){
					if(data==1){
						$('#payment').modal('hide');
						$('.pay_'+$('#order_no').val()).hide();
					}
					else 
						success_or_error_msg('#logn_reg_error','danger',"Error!!!! Payment faild to process","#mpesa_mobile");
						$('#make_payment').removeAttr('disabled','disabled');
				}
            });
        }
    })

</script>

