<?php
session_start();
include("../includes/dbConnect.php");
include("../includes/dbClass.php");
$dbClass = new dbClass;

if(!isset($_SESSION['customer_id']) && $_SESSION['customer_id']!=""){
    ob_start(); header("Location:error.php"); exit();
}
//echo $_SESSION['customer_id']; die;
//var_dump($_SESSION);

$customer_info = $dbClass->getSingleRow("select * from customer_infos where customer_id=".$_SESSION['customer_id']);
$customer_id = $_SESSION['customer_id'];
//var_dump($customer_info)


$order_id = '';
if(isset($_GET['order_id']) && $_GET['order_id']!="") $order_id =  $_GET['order_id'];

//var_dump($customer_info);
?>
<!-- Start Main -->
<!-- Start Breadcrumb Part -->
<section class="breadcrumb-part" id="welcome_heading_big" data-stellar-offset-parent="true" data-stellar-background-ratio="0.5" style="background-image: url('images/banner8.jpg'); height: 220px">
    <div class="container" id="welcome_container_big" style="padding-top: 0px">
        <div class="breadcrumb-inner" >
            <h3><span style='color:#8c5d2d'>Welcome</span> <?php echo $customer_info['full_name']; ?></h3>
            <a href="index.php">Home</a>
            <span>My Account</span>
        </div>
    </div>
</section>
<section class="breadcrumb-part" id="welcome_heading_small" data-stellar-offset-parent="true" data-stellar-background-ratio="0.5" style="background-image: url('images/banner8.jpg'); height: 180px; margin-bottom: 0px">
    <div class="container">
        <div class="breadcrumb-inner" >
            <h4 style="margin: 0px"><span style='color:#8c5d2d'>Welcome</span> <?php echo $customer_info['full_name']; ?></h4>
            <a href="index.php" style="margin-top: 2px">Home</a>
            <span>My Account</span>
        </div>
    </div>
</section>
<!-- End Breadcrumb Part -->
<!-- Start term condition -->
<section class="term-condition home-icon" style="padding-top: 40px">
    <div class="icon-default">
        <a href="#"><img src="images/scroll-arrow.png" alt=""></a>
    </div>
    <div class="container account_container">

        <div class="col-md-9 col-sm-8 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms" style="border-radius: 15px">
            <div class="team-single-info  alert-warning" style="margin: 10px 0 !important; padding: 10px; border-radius: 15px">
                <div class="row  alert-warning" id="account_contents" style="padding: 15px;border-radius: 10px">

                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
            <div class="terms-right account-menu">
                <ul >
                    <li class='sub-menu  profile'><a href='javascript:void(0)' onclick="show_my_accounts('profile' ,''); load_customer_profile();	">Profile</a></li>
                    <li class="sub-menu orders"><a  href='javascript:void(0)'  onclick="show_my_accounts('orders', '')">Orders</a></li>
                    <li class="sub-menu tracking"><a href='javascript:void(0)'  onclick="show_my_accounts('tracking', '')">Order Tracking</a></li>
                    <li class="sub-menu group_order"><a  href='javascript:void(0)'  onclick="show_my_accounts('group_order', '')">Group Order</a></li>
                    <li class="sub-menu groupOrderDetails"><a  href='javascript:void(0)'  onclick="show_my_accounts('groupOrderDetails', '')">Groups Order Details</a></li>
                    <li class="sub-menu notification"><a  href='javascript:void(0)'  onclick="show_my_accounts('notification', '')">Notification</a></li>
                    <li class="sub-menu logout"><a href='views/logout.php'>Logout</a></li>
                </ul>
            </div>
            <div>

            </div>
        </div>

    </div>
</section>
<!-- End Main -->

<script>



    var customer_id = "<?php echo $customer_id; ?>";
    var order_id = "<?php echo $order_id; ?>";

    var sWidth = window.screen.width;
    if(sWidth<801){
        $('#welcome_heading_big').css('display','none')
        $('#welcome_heading_small').css('display','block')
        $('#table_big').css('display', 'none')
        $('#table_small').css('display', 'block')
    }
    else {
        $('#welcome_heading_big').css('display','block')
        $('#welcome_heading_small').css('display','none')
        $('#table_big').css('display', 'block')
        $('#table_small').css('display', 'none')
    }



    show_my_accounts = function show_my_accounts(page_name, order_id){
        localStorage.setItem("currenturl")
        $(".sub-menu").removeClass('active');
        if(page_name == 'tracking'){
            $('#account_contents').load('views/'+page_name+'.php?order_id='+order_id);
        }
        else if(page_name == 'group_order'){
            if(!order_id>0) order_id=0

            $('#account_contents').load('views/'+page_name+'.php',{'group_id':order_id});
        }
        else{
            $('#account_contents').load('views/'+page_name+'.php');
        }
        if(page_name !== 'profile'){
            $("."+page_name).addClass('active');
            $('html, body').animate()
        }
    }



    $(document).on('click','#track_btn', function(){
        if($.trim($('#order_tracking_number').val()) == ""){
            return false;
        }
        else{
            order_no = $.trim($('#order_tracking_number').val());
            $.ajax({
                url: project_url +"includes/controller/itemsController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "getOrder_status",
                    order_tracking_number:order_no
                },
                success: function(data){

                    if(!jQuery.isEmptyObject(data.records)){
                        $.each(data.records, function(i,datas){

                            var li=""; var message = "";

                            if(datas.payment_method == 1 ){
                                if(datas.order_status == 3){
                                    li = "<li class='done-proceed'>Order Placed</li><li class='done-proceed'>Order Received</li><li class='done-proceed'>Ready</li><li class='done-proceed'>Delivered</li>";
                                    message = "You have received your order! Have fun";
                                }
                                else if(datas.order_status == 4){
                                    li = "<li class='done-proceed '>Order Placed</li><li class='done-proceed '>Order Received</li><li class='done-proceed '>Ready</li><li>Delivered</li>";
                                    message = "Your order is ready for pickup";
                                }
                                else if(datas.order_status == 1){
                                    li = "<li class='done-proceed'>Order Placed</li><li >Order Received</li><li >Ready</li><li >Delivered</li>";
                                    message = "Your placed an order";
                                }
                                else if(datas.order_status == 5){
                                    li = "<li class='done-proceed'>Order Placed</li><li  class='done-proceed col-sm-3 col-xs-4'>Order Received</li><li  class='done-proceed col-sm-3 col-xs-4'>Ready</li><li  class='done-proceed col-sm-3 col-xs-4'>Delivered</li>";
                                    message = "Your Order has been collected from outlet";
                                }

                            }else if(datas.order_status == 2){

                                if(datas.payment_status == 2){
                                    li = "<li class='done-proceed'>Order Placed</li><li class='done-proceed'>Order Rejected</li><li>Refund</li>";
                                    message = "Your order has been rejected you will get refund soon";
                                }
                                else if(datas.payment_status ==3){
                                    li = "<li class='done-proceed'>Order Placed</li><li class='done-proceed'>Order Rejected</li>";
                                    message = "Your Received your refund amount";
                                }
                            }
                            else{
                                if(datas.order_status == 3){
                                    li = "<li class='done-proceed'>Order Placed</li><li class='done-proceed'>Order Received</li><li>Ready</li><li>Delivered</li>";
                                    message = "We have received your order! Have fun";
                                }
                                else if(datas.order_status == 4){
                                    li = "<li class='done-proceed col-sm-3'>Order Placed</li><li class='done-proceed col-sm-3'>Order Received</li><li class='done-proceed col-sm-3'>Ready</li><li class='col-sm-3'  >Delivered</li>";
                                    message = "Your order is ready for pickup";
                                }
                                else if(datas.order_status == 5){
                                    li = "<li class='done-proceed'>Order Placed</li><li  class='done-proceed'>Order Received</li><li  class='done-proceed '>Ready</li><li  class='done-proceed'>Delivered</li>";
                                    message = "Your Order has been collected from outlet";
                                }
                                else if(datas.order_status == 1){
                                    li = "<li class='done-proceed'>Order Placed</li><li >Order Received</li><li >Ready</li><li >Delivered</li>";
                                    message = "Your placed an order";
                                }
                            }



                            $('#order_status_li').html(li);
                            $('#order_status_message').html(message);
                        })
                    }
                }
            });
        }
    })

    if (localStorage.getItem("passkey")){
        show_my_accounts('update-profile', '')
    } else if(order_id != ''){
        show_my_accounts('tracking',order_id);
    } else if (localStorage.getItem("key")){
        show_my_accounts('group_order', '')
    }



    if(localStorage.getItem("nexturl")=='grouporder') {
        show_my_accounts('grouporder', '')
        localStorage.setItem("nexturl", "")
    }


    if(localStorage.getItem("currenturl")) {
        show_my_accounts(localStorage.getItem("currenturl"), '')
    }
    else {
        show_my_accounts('profile',order_id)
    }



</script>


<?php

if(isset($_SESSION['groupOrderId'])){
    ?>
    <script>
        show_my_accounts('groupOrderDetails', '')
    </script>
    <?php



}
?>
