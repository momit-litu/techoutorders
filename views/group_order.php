<?php
session_start();
include("../includes/dbConnect.php");
include("../includes/dbClass.php");
$dbClass = new dbClass;

//unset($_SESSION['nexturl']);


if(isset($_SESSION['customer_id']) && $_SESSION['customer_id']!=""){
    $is_logged_in_customer = 1; // here will be the customer id that will come from session when the customer will login
    $customer_info = $dbClass->getSingleRow("select * from customer_infos where customer_id=".$_SESSION['customer_id']);
    $customer_id = $_SESSION['customer_id'];
}
else $is_logged_in_customer = "";


//var_dump($customer_info)

$order_id = '';
if(isset($_GET['order_id']) && $_GET['order_id']!="") $order_id =  $_GET['order_id'];
$store_address=$dbClass->getDescription('store_address');
//var_dump($customer_info);
?>


<section class="home-icon shop-cart row  alert-warning" style="padding-top: 0px; margin-top: 0px">
    <form method="POST"  id="group_order" onsubmit=" return false;" name="group_order"  enctype="multipart/form-data">
        <div class="container  col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-12 col-xs-12"  style="max-width:100%" >
            <h5 style="text-align: center"> Group Information </h5>
            <hr>
            <input type="text" placeholder="Enter A Name for your Group" list="group_names" name="group_name" id="group_name" />
            <input type="hidden" name="id_group" id="id_group" value="">

            <datalist id="group_names"></datalist>
            <table class="table table-bordered" style="padding: 0px" id="member_table">
                <thead>
                <tr>
                    <th width="40%" align="center">Name</th>
                    <th width="55%" align="center">Email</th>
                    <th width="" ><button class="btn btn-primary btn-xs" onclick="addMember()" >+</button></th>
                </tr>
                </thead>
                <tbody id="members_info">

                </tbody>
            </table>

        </div>
        <div class="col-md-6 col-sm-12 col-xs-12"  style="max-width:100%">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <label style=" font-size: 14px"> Confirm TakeOut Location </label>
                <div class="payment-mode">
                    <input type="checkbox" checked name="take_out_location" id="take_out_location">
                    <label for="take_out_location" id="take_out_location_" style=" font-size: 12px"><?php echo $store_address; ?></label>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <label style=" font-size: 14px"> Please select Take-Out date and time </label>
                <input type="text" name="pickup_date_time" id="pickup_date_time" placeholder="Date and Time" class="input-fields date-picker" required value="2020-01-07 12:00:00" style="background-color: white">
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <label style=" font-size: 14px"> Please select date and time for notification </label>
                <input type="text" name="notification_date_time" id="notification_date_time" placeholder="Date and Time" class="input-fields date-picker_noti" required value="2020-01-07 12:00:00" style="background-color: white">
            </div>



        </div>
            <div>
                <div class="col-md-12 center" style="text-align: center; height: 40px"> <button type="button" class="btn btn-warning" id="save_group_order"  style="height: 40px">Initiate a Group Order</button></div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="form_submit_error" class="text-center" style="display:none"></div>
            </div>
        </div>

    </form>

</section>

<div class="modal fade booktable" id="order_modal_group" tabindex="-2" role="dialog" aria-labelledby="booktable">
    <div class="modal-dialog" role="document" style="width:80% !important">
        <div class="modal-content">
            <div class="modal-body" style="margin-bottom: 50px">
                <div id="order-div" >
                    <div class="title text-center">
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
                                <div class="col-md-6" style="text-align:right">
                                    <h4>Customer Details:</h4>
                                    <address id="customer_detail_vw">
                                    </address>
                                </div>

                            </div>
                            <p class="text-danger text-left before_order_initiate">*YOU CAN SELECT FOOD FOR THE MEMBERS</p>

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

                <div class="col-md-12 text-center before_order_initiate" style="margin-bottom: 10px" ><button type="button" class="btn btn-primary" onclick="checkout()">Proceed to Checkout</button></div>
                <div id="checkout_error" class="text-center" style="display:none" ></div>


                <div class="col-md-12" style="text-align: center"> <button type="button" class="btn btn-warning" id="order_print"><i class="fa fa-lg fa-print"></i></button></div>
            </div>
        </div>
    </div>
</div>


<script>

localStorage.setItem("currenturl", "group_order");

$(document).ready(function () {

$('select.select-dropbox, input[type="radio"], input[type="checkbox"]').styler({selectSearch:true,});

    datetime = () =>{
        newdates= new Date(Date.parse(new Date().toLocaleString("en-US", {timeZone: "America/New_York"})));
        //alert(newdates)
        //$('#order_date_time').val(newdates)

        newdates.setMinutes(newdates.getMinutes() + 30);
        return newdates
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


    $('.date-picker_noti').daterangepicker({

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


    $("#group_name").autocomplete({
			search: function() {
        },
        source: function(request, response) {
            $.ajax({
                url: project_url +"includes/controller/groupController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "group_details_by_name",
                    term: $('#group_name').val()

                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 3,
        select: function(event, ui) {
            var id = ui.item.id;
            $(this).next().val(id);
            loadmembers(id)

        }
    });


	loadGroups = function loadGroup(){
		var html=''

		$.ajax({
			url: project_url +"includes/controller/groupController.php",
			data:{
				q: "groups",
				customer_id: customer_id
			},
			type:'POST',
			async:false,
			dataType: "json",
			success: function(data){
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,data){
						html+='<option>'+data['name']+'</option>'
					})
				}
				$('#group_names').html(html)
			}
		});
	}
	
	loadGroups();

    addMember = function addMember(){
        var html =''
        //alert(html)
        html+='<tr class="user_information">\n' +
            '    <td><input type="text" required name="memberName[]" value="" style="margin: 0px; padding: 3px; border-radius: 5px; height: 40px"></td>\n' +
            '    <td><input type="email" required name="memberEmail[]" value="" style="margin: 0px; padding: 3px; border-radius: 5px; height: 40px"></td>\n' +
            '    <td style="margin-top: 10px"><button class="btn btn-xs btn-danger deletes" >x</button></td>\n' +
            '  </tr>'
        $('#members_info').append(html)

        $('.deletes').on('click', function () {
            $(this).parent().parent().remove()
        })
    }
	
	var customer_id = "<?php echo $_SESSION['customer_id']; ?>";

    $('#save_group_order').click(function(event){
        event.preventDefault();
        var formData = new FormData($('#group_order')[0]);
        formData.append("q","initiate_group_order");
        formData.append("customer_id",customer_id);

        //validation
        if(!$('input[name=take_out_location]:checked', '#group_order').val()){
            success_or_error_msg('#form_submit_error','danger','Please Confirm Take-Out Location',"#take_out_location");
        }
        else if(!$.trim($('#group_name').val())){
            success_or_error_msg('#form_submit_error','danger','Please ENTER  a name for this group',"#pickup_date_time");
        }
        else if(!$.trim($('#pickup_date_time').val())){
            success_or_error_msg('#form_submit_error','danger','Please Select Take-Out Date and Time',"#pickup_date_time");
        }
        else if(!$.trim($('#notification_date_time').val())){
            success_or_error_msg('#form_submit_error','danger','Please Enter the Final Remainder Time to Confirm Order',"#notification_date_time");
        }
        else{
            //alert('ksjfdlk;')
            $.ajax({
                url: project_url +"includes/controller/groupController.php",
                type:'POST',
                data:formData,
                async:false,
                cache:false,
                contentType:false,processData:false,
                success: function(data){
                   // alert(data)
                    if (data==1){
                        success_or_error_msg('#form_submit_error','primary','Group Order Successfully Initiated',"#notification_date_time");
                        newForm()
                        show_my_accounts('groupOrderDetails', '')

                    }

                }
            });
            //alert('done')

        }
    })


    loadmembers = function loadmembers(id){
        var html=''
       // alert(id)
        $('#id_group').val(id);


        if(typeof id != 'undefined'){
            //$('#id_group').val(group_id)

            $.ajax({
                url: project_url +"includes/controller/groupController.php",
                data:{
                    q: "group_details",
                    group_id: id

                },
                type:'POST',
                async:false,
                dataType: "json",
                success: function(data){
                    //console.log(data)
                    $('#group_name').val(data['name'])

                    if(!jQuery.isEmptyObject(data.records)){
                        $.each(data.records, function(i,data){
                            html+='<tr class="user_information">\n' +
                                '    <td><input type="text" required  name="memberName[]" value="'+data['name']+'" style="margin: 0px; padding: 3px; border-radius: 5px; height: 40px"></td>\n' +
                                '    <td><input type="email" required name="memberEmail[]" value="'+data['email']+'" style="margin: 0px; padding: 3px; border-radius: 5px; height: 40px"></td>\n' +
                                '    <td style="margin-top: 10px"><button class="btn btn-xs btn-danger deletes" >x</button></td>\n' +
                                '  </tr>'
                           // alert(i)
                        })
                    }

                    $('#members_info').html(html);

                    $('.deletes').on('click', function () {
                        $(this).parent().parent().remove()
                    })
                }

            });
        }
        else {
            newForm()
        }

    }

    function newForm(){
        $.ajax({
            url: project_url +"includes/controller/ecommerceController.php",
            data: {
                q: "get_customer_details"
            },
            type: 'POST',
            async: false,
            dataType: "json",
            success: function (data) {
                //console.log(data['records'])
                html='<tr class="user_information">\n' +
                    '    <td><input type="text" required name="memberName[]" value="'+data['records']['0']['full_name']+'" style="margin: 0px; padding: 3px; border-radius: 5px; height: 40px"></td>\n' +
                    '    <td><input type="email" required name="memberEmail[]" value="'+data['records']['0']['email']+'" style="margin: 0px; padding: 3px; border-radius: 5px; height: 40px"></td>\n' +
                    '    <td style="margin-top: 10px"><button class="btn btn-xs btn-danger deletes" >x</button></td>\n' +
                    '  </tr>'
                $('#members_info').html(html);

                addMember();

            }
        });
    }

    loadmembers();
	
});
	
</script>








