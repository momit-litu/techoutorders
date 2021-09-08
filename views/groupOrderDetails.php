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
$orders_info = $dbClass->getResultList("SELECT count(god.id) as members, gi.name, go.order_id, go.order_date, go.delivery_date, go.total_order_amt, go.notification_time,
                                        case go.order_status when 2 then 'Invitation Sent' when 3 then 'Menu Selected' when 4 then 'Order Panding' when 5 then 'Order Approved' when 6 then 'Order Ready' else 'Order Initiate' end order_status
                                        from group_order go 
                                        LEFT JOIN (
                                        SELECT name, id FROM groups_info
                                        ) gi ON gi.id=go.group_id
                                        INNER JOIN (
                                        SELECT id, group_order_id  from group_order_details 
                                        ) god on god.group_order_id = go.order_id 
                                        WHERE go.customer_id=$customer_id  GROUP BY god.group_order_id 
                                        ORDER BY god.group_order_id desc 
										");
if(empty($orders_info)){
    echo "<h6 class='center'>Your have no orders </h6>";
}
else{

    ?>
    <h6 class="center" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">Your Order List </h6>
    <hr>
    <section class="home-icon shop-cart bg-skeen" style="padding-top: 20px">
        <div class="container" style="max-width:100%" id="oredrs_div">
            <table class="table table-bordered table-hover" id="" style="background-color: white">
                <thead>
                <tr style="background-color: #e4b95b; alignment: center">

                    <th>Group Name</th>
                    <th class='hide-xs'>Delivery date</th>
                    <th class='hide-xs'>Notification Time</th>
                    <th>Members</th>
                    <th>Amount</th>
                    <th class='hide-xs'>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <?php
                    foreach($orders_info as $order){
                        $order_no = '"'.$order['order_id'].'"';
                        echo
                            "<tr>
							 <td style='font-weight: bold' ><button onclick='view_order(".$order_no.")' style='background: none; border: none'>".$order['name']."</button></td>
							  <td class='hide-xs'>".$order['delivery_date']."</td>
							  <td class='hide-xs'>".$order['notification_time']."</td>
							  <td class='text-center'>".$order['members']."</td>
							  <td class='text-right'>".$currency."".$order['total_order_amt']."</td>
							  <td class='hide-xs'>".$order['order_status']."</td>                                        
							  <td><button class='btn btn-primary btn-sm' onclick='view_order(".$order_no.")'><i class='fa fa-search-plus pointer' ></i></button></td>
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


<!-- Start Order details -->
<div class="modal fade booktable" id="order_modal" tabindex="-2" role="dialog" aria-labelledby="booktable">
    <div class="modal-dialog width_80_p" role="document" >
        <div class="modal-content">
            <div class="modal-body" style="margin-bottom: 50px">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <div id="order-div" >
                    <div class="title text-center">
                        <input type="hidden" id="order_id_modal">
                        <h3 class="text-coffee left"> <a href="index.php"><img src="<?php echo ($logo); ?>" alt="" style="height: 100px; width: 100px"></a></h3>
                        <h4 class="text-coffee left">Order For  <span class="text-capitalize" id="ord_title_vw"></span></h4>
                    </div>
                    <div class="done_registration ">
                        <div class="doc_content">
                            <div class="col-md-12" style="margin-left: 0px; padding: 0px; margin-bottom: 20px">
                                <div class="col-md-6" style="margin: 0px; padding: 0px">
                                    <h4>Order Details:</h4>
                                    <div class="byline">
                                        <span class="after_order_initiate" id="inv_no" style="display: none"></span>
                                        <span id="order_status"></span><br/>
                                        <span id="ord_date"></span><br/>
                                        <span id="ntf_date"></span> <br/>
                                        <span id="dlv_date"></span> <br/>

                                    </div>
                                </div>
                                <div class="col-md-6 text-right text-right-l left-padding-0 left-margin-0">
                                    <h4>Customer Details:</h4>
                                    <address id="customer_detail_vw">
                                    </address>
                                </div>

                            </div>
                            <p class="text-danger text-left before_order_initiate">*YOU CAN SELECT FOOD FOR THE MEMBERS</p>

                            <div id="ord_detail_vw">
                                <table width="100%" class="table table-bordered" id="ord_detail_vw_big" >
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
                                <table width="100%" class="table table-bordered" id="ord_detail_vw_small" style="display: none" >
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

                                <p class="hidden-print" style="font-weight:bold; text-align:center" id="thankingNoted">Thank you. Hope we will see you soon </p>
                            </div>

                        </div>


                    </div>
                </div>

                <div class="col-md-12 text-center before_order_initiate hidden-print"  style="margin-bottom: 10px" id="place_order" ><button type="button" class="btn btn-primary" onclick="checkout()">Proceed to Checkout</button></div>
                <div id="checkout_error" class="text-center" style="display:none" ></div>


                <div class="col-md-12 hidden-print" style="text-align: center"> <button type="button" class="btn btn-warning" id="order_print"><i class="fa fa-lg fa-print"></i></button></div>
            </div>
        </div>
    </div>
</div>
<!-- End order -->

<script>

    localStorage.setItem("currenturl", "groupOrderDetails");

    var print_module=''
    var group_id= ''


    function checkout(){
        group_id =    localStorage.getItem("currentGroupOrder");
        if($('#total_amt').html()!=currency_symbol+"0.00"){
            if(group_id == ''){
                group_id = $('#order_id_modal').val()
            }
            window.location.href = project_url+"index.php?page=groupCheckout&id="+group_id
        }
        else {
            success_or_error_msg('#checkout_error','danger',"Please Select items to checkout. ","#coupon");
        }
    }

    selectItems =function selectItems(grouporder_id, key) {

        $.ajax({
            url: project_url +"includes/controller/groupController.php",
            type: 'POST',
            async: false,
            dataType: "json",
            data: {
                q: "set_session_group_order",
                order_id: grouporder_id
            },
            success: function (data) {
                window.location.href = project_url+'index.php?groupmaster='+key;

            }
        })

    }

    var view_order = function view_order(order_id){
        localStorage.setItem("currentGroupOrder", order_id);

        group_id = order_id;
        $('#ord_detail_vw>table>tbody').html('');
        $.ajax({
            url:project_url +"includes/controller/groupController.php",
            type:'POST',
            async:false,
            dataType: "json",
            data:{
                q: "get_group_order_details",
                order_id:order_id
            },
            success: function(data){
                console.log(data)
                if(!jQuery.isEmptyObject(data.order_details)) {

                    if(parseInt(data.order_details.status)<3){
                        $('#place_order').css('display','block')
                    }else  $('#place_order').css('display','none')


                    $('#ord_title_vw').html(data.order_details.name);
                    $('#inv_no').html("Invoice Number: "+data.order_details.invoice_no);
                    $('#ord_date').html("Ordered time: "+data.order_details.order_date);
                    $('#dlv_date').html("<span style='background-color:orange'>Scheduled Pickup Time: "+data.order_details.delivery_date+"</span>");
                    $('#ntf_date').html("Notification time: "+data.order_details.notification_time);
                    $('#order_status').html("Order Status: "+data.order_details.order_status);
                    $('#customer_detail_vw').html(" "+data.order_details.full_name+"<br/><b>Mobile:</b> "+data.order_details.mobile+"<br/><b>Address:</b> "+data.order_details.c_address);
                }
                let order_status = parseInt(data['order_details']['status'])
                if(order_status>3){
                    $('.before_order_initiate').css('display','none')
                    $('.after_order_initiate').css('display','block')

                }

                    if(!jQuery.isEmptyObject(data.records)){
                        var sub_total=0;
                        $.each(data.records, function(i,data){

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
                                    var order_info_arr = orderInfo.split('#');
                                    var total = ((parseFloat(order_info_arr[4])*parseFloat(order_info_arr[5])));
                                    order_tr += '<tr><td class="text-capitalize">'+order_info_arr[2]+' <br>'+order_info_arr[6]+'</td><td align="center">'+order_info_arr[5]+'</td><td align="right">'+currency_symbol+''+order_info_arr[4]+'</td><td align="right">'+currency_symbol+''+total+'</td></tr>';
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

                        if(data['order_details']['cupon_amount']!=null){
                            if(data['order_details']['c_type']==2){
                                discount =sub_total*data['order_details']['cupon_amount']/100
                            }
                            else  discount =data['order_details']['cupon_amount']
                        }

                        if(data['tax']['tax_enable']!=0){
                            if(data['tax']['tax_type']==0){
                                tax=(sub_total-discount)*data['tax']['tax_amount']/100
                            }
                            else tax = data['tax']['tax_amount']
                        }


                            var order_tr='<tr align="right"><td colspan="3" ><b>Total Order Amount</b></td><td align="right"><b>'+currency_symbol+''+sub_total.toFixed(2)+'</b></td></tr>'
                        order_tr += '<trstyle="display: block><td colspan="3" align="right" ><b>Discount</b></td><td align="right"><b id="discount_amt">'+currency_symbol+''+discount.toFixed(2)+'</b></td></tr>';
                        order_tr += '<trstyle="display: block><td colspan="3" align="right" ><b>Tax</b></td><td align="right"><b id="tax_amt">'+currency_symbol+''+parseFloat(tax).toFixed(2)+'</b></td></tr>';
                        order_tr += '<trstyle="display: block><td colspan="3" align="right" ><b>Tips</b></td><td align="right"><b id="tax_amt">'+currency_symbol+''+parseFloat(data['order_details']['tips']).toFixed(2)+'</b></td></tr>';
                        order_tr += '<tr><td colspan="3" align="right" ><b>Grand Total Amount</b></td><td align="right"><b id="total_amt">'+currency_symbol+''+(parseFloat(sub_total-discount)+parseFloat(tax)+parseFloat(data['order_details']['tips'])).toFixed(2)+'</b></td></tr>';


                        $('#ord_detail_vw>table>tbody').append(order_tr);
                    }
                }

        });
        $('#order_modal').modal();
    }



</script>

<?php

if(isset($_SESSION['groupOrderId'])){
    ?>
    <script>

        view_order(<?php echo $_SESSION['groupOrderId']; ?>)
    </script>
    <?php
    unset($_SESSION['groupOrderId']);
    unset($_SESSION['groupOrderDetails']);
}
?>

