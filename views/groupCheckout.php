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
else {
    ?>
    <script>
        window.location.href = project_url+"index.php";
    </script>
    <?php
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
                <li>Order Complete</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 wow fadeInDown  tab-content" data-wow-duration="1000ms" data-wow-delay="300ms" >
                <ul class="nav nav-tabs" role="tablist" style="margin-right: 43%; margin-top: 20px; font-size: 20px; border-radius: 15px 15px 0px 0px">
                    <li role="presentation" onclick="take_out()" id="take_out_menu" style="background-color: #EAEAEA">
                        <a href="#reviews" aria-controls="pickUp" role="tab" data-toggle="tab">TakeOut Confirmation and Payment</a>
                    </li>
                    <li role="presentation" onclick="payments()" id="payments_menu" style="background-color: #EAEAEA">
                    </li>
                </ul>
                <div class="col-md-7 col-sm-7 col-xs-12" style="background-color: white; border-radius: 0px 12px 12px 12px; padding-top: 25px; padding-bottom: 20px">
                    <div role="tabpanel" class="tab-pane" id="description">
                        <form method="post" name="checkout-form" id="checkout-form">

                            <div id="take_out" style="display: none">



                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h5>Takeout Details</h5>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <br />
                                    <label > Confirm TakeOut Location </label>
                                    <div class="payment-mode">
                                        <span>
                                            <label>
                                                <input type="checkbox" name="take_out_location" id="take_out_location" > I’ve read and accept the <a href="termsCondition.php"> terms & conditions </a> and confirm this is a TakeOut order from our address below:
                                            </label>
                                            <b id="take_out_location_"></b>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <br />
                                    <label> Date and Time</label>
                                    <p class="asap" style="margin-right: 5px; margin-bottom: 0px"> <input class="asap asap_disable" type="checkbox" name="asap" id="asap" >I want my food ASAP (5-30 minutes, during  <a href="#" onclick="openCalender()">operating hours</a>)</p>
                                    <p style="margin-top: 0px" ><b class="asap" style="color: #e4b95b">OR</b> <br>Schedule date and time, The soonest time is already selected by default <small style="color: #4FB5D3"></small> </p>
                                    <input type="text" name="pickup_date_time" id="pickup_date_time" placeholder="Date and Time" class="input-fields date-picker" required value="2020-01-07 12:00:00">
                                    <input type="hidden" name="order_date_time" id="order_date_time" placeholder="Date and Time" class="input-fields order_date-picker" required style="border-radius: 10px">

                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label> Order Notes </label>
                                    <textarea placeholder="Order Notes" name="secial_notes" id="secial_notes"></textarea>
                                </div>



                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label> Use Coupon/Vouchers </label>
                                    <input type="text" name="coupon" id="coupon" placeholder="Enter The Coupon Code" class="input-fields" style="border-radius: 10px">
                                    <div id="coupon_error" class="text-center" style="display:none"></div>

                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12" style="margin: auto" id="tips_entry">
                                    <label>Tips </label><p>Always Appreciated</p><br>
                                    <label style="margin-right: 5px; margin-left: 10px"> <input type="radio" value="0" class="icheckbox_flat" name="tips_percentage" id="0percente" >Not today</label>
                                    <label style="margin-right: 5px; margin-left: 10px"> <input type="radio" value="18" name="tips_percentage" id="18percente" >18% </label>
                                    <label style="margin-right: 5px; margin-left: 10px"> <input type="radio" value="25" name="tips_percentage" id="25percente" >25% </label>
                                    <label style="margin-right: 5px; margin-left: 10px"> <input type="radio" value="30" name="tips_percentage" id="30percente" >30% </label>
                                    <label style="margin-right: 5px; margin-left: 10px">  <input type="radio" value="100" name="tips_percentage" id="100percente" > Custom</label>
                                    <span><input type="number" step="0.01" name="tips" id="tips" placeholder="Tips amount" class="input-fields" min="0" value="0" style="border-radius: 10px; display: block;margin-top: 10px"></span>
                                    <div id="tips_error" class="text-center" style="display:none"></div>
                                </div>

                                <label>Payment Methods</label>
                                <input type="hidden"  id="grand_total">
                                <div class="payment_body" id="payment_body"></div>
                                <div id="payment_alert" class="text-center" style="display:none"></div>


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
                                <h6>GROUP MEMBERS <span>ORDER</span></h6>
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
            <input type="hidden" name="lc" value="USA" />
            <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
            <input type="hidden" name="first_name" value="<?php echo $_SESSION['customer_name'] ?>" />
            <input type="hidden" name="last_name" value="Customer's Last Name" />
            <input type="hidden" name="payer_email" value="<?php echo $_SESSION['customer_email'] ?>" />
            <input type="hidden" name="item_number" id="item_number" value="123456" />
            <input type="hidden" name="item_name" id="item_name" value="123456" />
            <input type="hidden" name="amount_total" id="amount_total" value="123456" />



        </form>

    </div>
</section>

<script>
    var group_order_id = location.search.split('&')[1].split('=')[1];
    var customer_id = <?php echo $customer_id; ?>

    var loyalty_points=0;
    var loyalty_point_value=0;
    var loyalty_reserve_value=0;
    var total = 0;


    $('select.select-dropbox, input[type="radio"], input[type="checkbox"]').styler({selectSearch:true,});

    datetime = () =>{
        type = 0
        time =''

        $.ajax({
            url: project_url + "includes/controller/ecommerceController.php",
            dataType: "json",
            type: "post",
            async: false,
            data: {
                q: "closest_date",
            },
            success: function (datas) {
                type = datas['status'];
                time = datas['time']?datas['time']:''
            }
        });
        //alert(1)
        if(type==0){
            newdates= new Date(Date.parse(new Date().toLocaleString("en-US", {timeZone: "America/New_York"})));
            newdates.setMinutes(newdates.getMinutes() + 30);
            return newdates
        }else if(type==1){
            var data = time.split(':');
            newdates= new Date(Date.parse(new Date().toLocaleString("en-US", {timeZone: "America/New_York"})));
            newdates.setDate(newdates.getDate() + 1);
            newdates.setMinutes(data[1]);
            newdates.setHours(data[0]);

            return newdates
        }
        else if(type==2){
            var data = time.split(':');
            newdates= new Date(Date.parse(new Date().toLocaleString("en-US", {timeZone: "America/New_York"})));
            newdates.setMinutes(data[1]);
            newdates.setHours(data[0]);

            //alert(new Date(newdates))
            return newdates
        }
    }


    $('.date-picker').daterangepicker({
        singleDatePicker: true,
        /*autoUpdateInput: false,*/
        calender_style: "picker_2",
        timePicker:true,
        locale: {
            format: 'YYYY-MM-DD H:mm',
            separator: " - ",
        },
        minDate:datetime()
    });


    $('.order_date-picker').daterangepicker({
        singleDatePicker: true,
        /*autoUpdateInput: false,*/
        calender_style: "picker_2",
        timePicker:true,
        locale: {
            format: 'YYYY-MM-DD H:mm',
            separator: " - ",
        },
        minDate:new Date(Date.parse(new Date().toLocaleString("en-US", {timeZone: "America/New_York"})))
    });





    function tips_add_to_db() {
        var tips = $('#tips').val()
        $.ajax({
            url: project_url +"includes/controller/groupController.php",
            type:'POST',
            async:true,
            data: {
                q:'add_tips',
                tips:tips,
                group_order_id: group_order_id
            },
            success: function(data){
                if(data==1){
                    success_or_error_msg('#tips_error','success',"Thank you for the tip &#128512  ","#coupon");
                }else if(data==2){
                    success_or_error_msg('#tips_error','danger',"Tips is not added. ","#coupon");
                }
                order_summary()

            }
        });

    }

    $('#tips').change(function () {
        $('#tips').val(Math.abs($('#tips').val()))
        tips_add_to_db()
    })

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
        tips_add_to_db()
        //$('#tips').trigger('change')

    });


    $('#coupon').on('change',function () {
        var coupon_code = $('#coupon').val();
        if(coupon_code !=""){
            $.ajax({
                url: project_url +"includes/controller/groupController.php",
                type:'POST',
                async:true,
                data: {
                    q:'apply_coupon',
                    coupon_code:coupon_code,
                    group_order_id: group_order_id
                },
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
            async:true,
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
                //alert(loyalty_points)
            }
        });
    }
    load_customer_profile();

    display_div = function display_div(){
        $("#take_out").css("display", "none");
        $("#payments").css("display", "none");
        document.getElementById("take_out_menu").classList.remove('active');
        document.getElementById("payments_menu").classList.remove('active');
    }

    take_out = function take_out(){
        display_div()
        document.getElementById("take_out_menu").classList.add('active');
        $("#take_out").css("display", "block");
    }

    take_out()

    payments = function payments(){
        display_div()
        document.getElementById("payments_menu").classList.add('active');
        $("#payments").css("display", "block");

        if(loyalty_points/loyalty_point_value<parseFloat($('#total_amount_').html().split('$')[1])){
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
                html=''
                if(!jQuery.isEmptyObject(data.records)){
                    $.each(data.records, function(i,data){
                        loyalty_point_value=data.redeem_value;
                        loyalty_reserve_value=data.point_reserve_value;
                        $('#take_out_location_').html(data.store_address);
                        if(data.cash_payment==1){
                            html+='<div class="payment-mode">\n' +
                                '       <label><input type="radio" name="payment_method" value="1" onclick="" style="margin-right: 10px">Cash on Delivery</label>\n' +
                                '  </div>'
                        }
                        if(data.loyelty_payment==1 ){
                            // alert(loyalty_points+'-+-')

                            html+='<div class="payment-mode">\n' +
                                '      <label id="use_loyalty_point"><input type="radio" name="payment_method" id="loyalty_redio" value="2"  onclick="" style="margin-right: 10px">Use Loyalty Point <span id="loyalty_spend"></span></label>\n' +
                                '  </div>'
                        }
                        if(data.paypal==1){
                            html+='<div class="payment-mode">\n' +
                                '      <label><input type="radio" name="payment_method" value="3"  onclick="" style="margin-right: 10px">Paypal</label>'

                            html+='</div>'
                        }
                        if(data.square==1) {
                            html += '<div class="payment-mode">\n' +
                                '      <label><input type="radio" name="payment_method" value="4"  onclick="" style="margin-right: 10px">Pay By Card (Square)</label>'
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
                        html+='</div>'

                    });

                }
                $('#payment_body').html(html);

            }
        });

    }
    general_settings()


    order_summary = function order_summary(){
        //alert('sdf')
        $.ajax({
            url: project_url +"includes/controller/groupController.php",
            dataType: "json",
            type: "post",
            async:true,
            data: {
                q: "viewCartSummery",
                group_order_id: group_order_id
            },
            success: function(data) {
                //alert(data)
                //return false;
                //console.log(data)
                //alert(data);
                if(!jQuery.isEmptyObject(data.records)){
                    var html = '';
                    var total = 0;
                    var sub_total = 0;
                    var count =0
                    $.each(data.records, function(i,datas){
                        sub_total += parseFloat(datas.total_order_amt);
                        html+='<p class="text-capitalize"><span>'+datas.name+'</span><small>'+ currency_symbol+''+datas.total_order_amt+'</small></p>\n'
                    });
                    $('#cart_summary').html(html);
                    //alert(typeof data['order_details']['tips']))
                    total= parseFloat(data['order_details']['total_order_amt'])+ parseFloat(data['order_details']['tax_amount'])+parseFloat(data['order_details']['tips'])-parseFloat(data['order_details']['discount_amount'])
                    $('#cart_total_').html(currency_symbol+''+parseFloat(data['order_details']['total_order_amt']).toFixed(2));
                    $('#discount_').html(currency_symbol+''+parseFloat(data['order_details']['discount_amount']).toFixed(2));
                    $('#tax_').html(currency_symbol+''+parseFloat(data['order_details']['tax_amount']).toFixed(2));
                    $('#tips_').html(currency_symbol+''+parseFloat(data['order_details']['tips']).toFixed(2));
                    $('#total_amount_').html(currency_symbol+''+total.toFixed(2));


                    $('#total_order_amt').val(parseFloat(data['order_details']['total_order_amt']).toFixed(2))
                    $('#tax_amount').val(parseFloat(data['order_details']['tax_amount']).toFixed(2))
                    $('#total_paid_amount').val(parseFloat(data['order_details']['total_order_amt']).toFixed(2))
                    $('#loyalty_point_earn').html('This Order Will Earn You '+ Math.floor(parseFloat(data['order_details']['total_order_amt'])/loyalty_reserve_value) +' Points')

                    //$('#loyalty_spend').html("(point will spend you haveThis order needs "+Math.ceil(parseFloat(data['order_details']['total_order_amt'])*loyalty_reserve_value)+" point, you have "+loyalty_points+" points)" )
                    $('#loyalty_spend').html("(You have "+loyalty_points+", you will spend "+Math.ceil(parseFloat(data['order_details']['total_order_amt'])*loyalty_reserve_value)+" on this order)" )

                    $('#loyalty_redio').attr('disabled',false);
                    $('#use_loyalty_point').css('color','black')
                    //alert(Math.ceil(parseFloat(data['order_details']['total_order_amt'])*loyalty_reserve_value))
                    //alert(loyalty_points)

                    if(parseInt(Math.ceil(parseFloat(data['order_details']['total_order_amt'])*loyalty_reserve_value))> parseInt(loyalty_points)){
                        //alert('ok')
                        $('#use_loyalty_point').css('color','gray')
                        $('#loyalty_redio').attr('disabled',true);
                    }

                }


            }
        });
        //tips_entry_form()
    }

    order_summary()
    load_customer_profile()

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
        day = day ? day:1

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

    $("#asap-styler").click(function () {
        if(($("#asap").prop('checked'))){
            //alert(timeValidation)
            //$('#pickup_date_time').attr('readonly', true);
            $('#pickup_date_time').css('background-color', 'antiquewhite');
            //$('#pickup_date_time')

        }
        else {
            //$('#pickup_date_time').attr('readonly', false);
            $('#pickup_date_time').css('background-color', '');

        }
    })
    asapCheck = () =>{
        timeValidation = dateCheck()
        if(timeValidation !=1){
            //alert(1)
            $("input.asap").prop("disabled", true);
        }

    }

    openCalender = () =>{
        $('#booktable').modal()
    }

    $('#checkout_submit').click(function(event){

        event.preventDefault();
        timeValidation = dateCheck()

        //$('#grand_total').val($('#total_amount_').html());
        //  alert($('#grand_total').val())
        var loyalty_value =Math.floor( parseFloat($('#total_amount_').html().split('$')[1])/loyalty_reserve_value);
        var loyalty_deduct = 0;
        if($('input[name=payment_method]:checked', '#checkout-form').val()==2){
            loyalty_deduct = Math.ceil(parseFloat($('#total_amount_').html().split('$')[1])*loyalty_point_value);
        }

        delevery_type = $("[name='delevery_type']:checked").val();
        payment_type  = $("[name='payment_type']:checked").val();
        var formData = new FormData($('#checkout-form')[0]);
        formData.append("q","checkout");
        formData.append("loyalty_point",loyalty_value);
        formData.append("loyalty_deduct",loyalty_deduct);
        formData.append("order_from",1);
        formData.append("grand_total",$('#total_amount_').html());
        formData.append("total_paid_amount",parseFloat($('#total_amount_').html().split('$')[1]));
        formData.append("group_order_id",group_order_id);


        if($('input[name=payment_method]:checked', '#checkout-form').val()){
            formData.append("payment_method",$('input[name=payment_method]:checked', '#checkout-form').val());

        }


        //console.log(formData)

        if(timeValidation!=1){
            //alert(timeValidation)
            success_or_error_msg('#logn_reg_error','danger',timeValidation,"#pickup_date_time");
        }
        else if($.trim($('#islogged_in').val()) == "0"){
            success_or_error_msg('#logn_reg_error','danger',"You must have to login or register if you are new customer. ","#forget_email");
        }
        else if(!$('input[name=take_out_location]:checked', '#checkout-form').val()){
            success_or_error_msg('#logn_reg_error','danger',"You must confirm Terms and Conditions and Take-Out Order. ","#pickup_date_time");
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
        else{
            //alert('sdf')

            $.ajax({
                url: project_url+"includes/controller/groupController.php",
                type:'POST',
                data:formData,
                async:true,
                cache:false,
                contentType:false,processData:false,
                success: function(data){
                   // alert('checkout done')
                    //alert(data)
                    //console.log(data)
                    if(data==0){
                        success_or_error_msg('#logn_reg_error',"danger","Order Failed. please check your information properly","#checkout_submit" );
                    }
                    else{

                        if($('input[name=payment_method]:checked', '#checkout-form').val()==3){

                            $('#item_name').val(data+'TakeOut Time:'+$.trim($('#pickup_date_time').val()))
                            $('#item_number').val(data)
                            $('#amount_total').val(parseFloat($('#total_amount_').html().split('$')[1]))

                            var new_formData = new FormData($('#paypal_form')[0]);
                            new_formData.append("next_url",project_url+"checkout_confirm.php");
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
                                    url ='https://'+data.split('https://')[1]

                                    window.location.href = url;
                                    showCart()
                                }
                            });
                        }
                        else  if($('input[name=payment_method]:checked', '#checkout-form').val()==4){
                            localStorage.setItem('bill_id',data.replace(/(^[ \t]*\n)/gm, ""))
                            localStorage.setItem('amount',parseFloat($('#total_amount_').html().split('$')[1]))
                            localStorage.setItem('sq_success','checkout_confirm.php')

                            window.location.href = project_url+'square'

                            //$('#item_number').val(data)
                            //$('#amount_total').val($('#total_paid_amount').val())
                        }
                        else {
                            showCart()
                            //alert('Your orde')
                            window.location.href = project_url+"checkout_confirm.php"
                        }

                    }
                }
            });

        }
    })



</script>







