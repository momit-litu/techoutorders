<?php
session_start();

if(!isset($_SESSION['customer_id']) && $_SESSION['customer_id']!="" && $_SESSION['latest_order_id']!=""){ ob_start(); header("Location:error.php"); exit();}
//var_dump($customer_info);

//var_dump( $_SESSION);die;
?>
<!-- Start Main -->

<section class="home-icon shop-cart bg-skeen wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
    <div class="icon-default icon-skeen">
                <img src="images/scroll-arrow.png" alt="">
            </div>
    <div class="container">
        <div class="checkout-wrap">
            <ul class="checkout-bar">
                <li class="done-proceed">Shopping Cart</li>
                <li class="done-proceed">Checkout</li>
                <li class="active done-proceed">Order Complete</li>
            </ul>
        </div>

            <div class="order-complete-box">
                <img src="images/complete-sign.png" alt="">
                <p >Thank you for ordering our food. You will receive a confirmation email shortly. your order referenced id #<b><?php echo $_SESSION['Last_invoice_no']; ?></b>
                    <br> Now check a Food Tracker progress with your order.</p>
                <a href="#" class="btn-medium btn-primary-gold btn-large" id="tracker">Go To Food Tracker</a>
                <br /><br />
                <button type="button" class="btn btn-warning"  onclick="view_order()" id=""><i class="fa fa-lg fa-print"> &nbsp; Print order #</i></button>
            </div>

    </div>
</section>
<div class="modal fade booktable" id="order_modal" tabindex="-2" role="dialog" aria-labelledby="booktable">

    <div class="modal-dialog width_80_p" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <div id="order-div">
                    <div class="title text-center">
                        <h3 class="text-coffee left"> <a href="index.php"><img id="modal_logo" src="" alt="" style="height: 100px; width: 100px"></a></h3>
                        <h4 class="text-coffee left">Order No # <span id="ord_title_vw"></span></h4>
                    </div>
                    <div class="done_registration ">
                        <div class="doc_content">
                            <div class="col-md-12" style="margin-left: 0px; padding: 0px; margin-bottom: 20px">
                                <div class="col-md-6" style="margin: 0px; padding: 0px">
                                    <h4>Order Details:</h4>
                                    <div class="byline">
                                        <span id="ord_date"></span><br/>
                                        <span id="dlv_date"></span> <br/>
                                        <span id="dlv_ps"></span> <br/>
                                        <span id="dlv_pm"></span><br/>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right text-right-l padding-left-0">
                                    <h4>Customer Details:</h4>
                                    <address id="customer_detail_vw">
                                    </address>
                                </div>

                            </div>
                            <div id="ord_detail_vw">
                                <table class="table table-bordered" >
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
                                <p>Note: <span id="note_vw"></span></p>
                                <p>Print Time : <?php echo date("Y-m-d h:m:s"); ?></p>
                                <br />
                                <p style="font-weight:bold; text-align:center">Thank you. Hope we will see you soon </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 text-center"> <button type="button" class="btn btn-warning" id="order_print"><i class="fa fa-lg fa-print"></i></button></div>
            </div>
        </div>
    </div>
</div>

<script>
    //alert('sdf')
    order_id = "<?php echo $_SESSION['Last_invoice_no']; ?>";

    var view_order = function view_order(){
        //alert('ok')
        $('#ord_detail_vw>table>tbody').html('');
        $.ajax({
            url:project_url +"includes/controller/ecommerceController.php",
            type:'POST',
            async:false,
            dataType: "json",
            data:{
                q: "get_order_details_by_invoice",
                order_id:order_id
            },
            success: function(data){
                //alert(data.item_id)
                //console.log(data)
                if(!jQuery.isEmptyObject(data.records)){
                    $.each(data.records, function(i,data){
                        $('#ord_title_vw').html(data.invoice_no);
                        $('#ord_date').html("Ordered time: "+data.order_date);
						delivery_date = (data.ASAP==1)?"<span style='background-color:Lime'>Pickup: ASAP</span>":"<span style='background-color:orange'>Scheduled Pickup Time:"+data.delivery_date+"</span>";
                        $('#dlv_date').html("Delivery time: "+delivery_date);
                        $('#dlv_ps').html("Payment Status: "+data.paid_status);
                        $('#dlv_pm').html("Payment Method: "+data.payment_method);
                        $('#customer_detail_vw').html(" "+data.customer_name+"<br/><b>Mobile:</b> "+data.customer_contact_no+"<br/><b>Address:</b> "+data.customer_address);
                        $('#note_vw').html(data.remarks);

                        var order_tr = "";
                        var order_total = 0;
                        order_infos	 = data.order_info;
                        var order_arr = order_infos.split('..,');
                        $.each(order_arr, function(i,orderInfo){
                            //alert(orderInfo)
                            var order_info_arr = orderInfo.split('#');
                            var total = ((parseFloat(order_info_arr[4])*parseFloat(order_info_arr[5])));
                            order_tr += '<tr><td class="text-capitalize">'+order_info_arr[2]+' <br>'+order_info_arr[6].split('..')[0]+'</td><td align="center">'+order_info_arr[5]+'</td><td align="right">'+currency_symbol+''+order_info_arr[4]+'</td><td align="right">'+currency_symbol+''+total+'</td></tr>';
                            order_total += total;
                        });
                        var total_order_bill = ((parseFloat(order_total)+parseFloat(data.delivery_charge))-parseFloat(data.discount_amount));
                        var total_paid = data.total_paid+_amount;
                        order_tr += '<tr><td colspan="3" align="right" ><b>Discount</b></td><td align="right"><b>'+currency_symbol+''+parseFloat(data.discount_amount).toFixed(2)+'</b></td></tr>';
                        order_tr += '<tr><td colspan="3" align="right" ><b>Tax</b></td><td align="right"><b>'+currency_symbol+''+parseFloat(data.tax_amount).toFixed(2)+'</b></td></tr>';
                        order_tr += '<tr><td colspan="3" align="right" ><b>Tips</b></td><td align="right"><b>'+currency_symbol+''+parseFloat(data.tips).toFixed(2)+'</b></td></tr>';
                        order_tr += '<tr><td colspan="3" align="right" ><b>Total Amount</b></td><td align="right"><b>'+currency_symbol+''+parseFloat(total_paid).toFixed(2)+'</b></td></tr>';
                        $('#ord_detail_vw>table>tbody').append(order_tr);



                        //for small device

                    });
                }
            }
        });
		$('#modal_logo').attr('src',$('.logo>a>img').attr('src'));
        $('#order_modal').modal();
    }

    $('#tracker').on('click', function () {
        localStorage.setItem("currenturl", "tracking");

        window.location.href=project_url+'index.php?page=account'
    })

</script>





