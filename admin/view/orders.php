<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
//$user_type = $_SESSION['user_type'];
//echo $user_type;
if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$site_url."../view/login.php");
else if($dbClass->getUserGroupPermission(77) != 1 ){
    ?>
    <div class="x_panel">
        <div class="alert alert-danger" align="center">You Don't Have permission of this Page.</div>
    </div>
    <?php
}
else{
    $user_name = $_SESSION['user_name'];
    $date = date("y-m-d");
    $logo = $dbClass->getDescription('website_url')."admin/".$dbClass->getDescription('company_logo');
    ?>

    <div class="x_panel">
        <div class="x_title">
            <h2>Order</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div id="page_notification_div" class="text-center" style="display:none"></div>

            <div class="dataTables_length">
                <label>Show
                    <select size="1" style="width: 56px;padding: 6px;" id="order_Table_length" name="order_Table_length" aria-controls="order_Table">
                        <option value="100">100</option>
                        <option value="200" selected="selected">200</option>
                        <option value="500">500</option>
                        <option value="500">1000</option>

                    </select>
                </label>
            </div>
            <div class="dataTables_filter" id="order_Table_filter">
                <div class="input-group">
                    <input class="form-control" id="search_order_field" style="" type="text">
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-primary has-spinner" id="search_order_button">
                     <span class="spinner"><i class="fa fa-spinner fa-spin fa-fw "></i></span>
                     <i class="fa  fa-search "></i>
                    </button>
                </span>
                </div>
            </div>
            <div style="height:450px; width:100%; overflow-y:scroll">
                <table id="order_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped  table-scroll ">
                    <thead >
                    <th class="column-title" width="">Invoice No</th>
                    <th class="column-title" width="">Customer</th>
                    <th class="column-title" width="12%">Delivery Date</th>
                    <!--<th class="column-title" width="12%">Delivery Date</th>-->
                    <th class="column-title" width="7%">Payment Method</th>
                    <th class="column-title" width="7%">Payment Status</th>
                    <th class="column-title" width="8%">Order Status</th>
                    <th class="column-title" width="10%">Order Amount</th>
                    <th class="column-title no-link last" width="5%"><span class="nobr"></span></th>
                    </tr>
                    </thead>
                    <tbody id="order_table_body" class="scrollable">

                    </tbody>
                </table>
            </div>
            <div id="order_Table_div">
                <div class="dataTables_info" id="order_Table_info">Showing <span id="from_to_limit"></span> of  <span id="total_record"></span> entries</div>
                <div class="dataTables_paginate paging_full_numbers" id="order_Table_paginate">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade booktable" id="order_modal" tabindex="-2" role="dialog" aria-labelledby="booktable">
        <div class="modal-dialog" role="document" style="width:80% !important">
            <div class="modal-content">
                <div class="modal-body" >
                    <button type="button" class="close btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="font-size: 50px; color: red">&times;</span></button>
                    <div id="order-div" style="margin-bottom: 30px">
                        <div class="title text-center">
                            <h3 class="text-coffee "> <a href="#"><img src="<?php echo ($logo); ?>" alt="" style="height: 100px; width: 100px"></a></h3>
                            <h4 class="text-coffee ">Order No # <span id="ord_title_vw"></span></h4>
                        </div>
                        <div class="done_registration ">
                            <div class="doc_content">
                                <div class="col-md-12" style="margin-left: 0px; padding: 0px; margin-bottom: 20px">
                                    <div class="col-md-6" style="margin: 0px; padding: 0px">
                                        <h4>Order Details:</h4>
                                        <div class="">
                                            <span>
                                                <button id="order_status_option" type="button" style="min-width:60px" class="btn btn-sm btn-success btn-lg disabled print_hide"></button></span><span>
                                                <button id="order_refund" onclick="orderRefund()" type="button" style="min-width:60px; display: none" class="btn btn-sm btn-success btn-lg print_hide">Refund</button>
                                            </span><br/>
                                            <span id="ord_date"></span><br/>
                                            <span id="dlv_date"></span> <br/>
                                            <span id="dlv_ps"></span> <br/>
                                            <span id="dlv_pm"></span><br/>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="text-align:right">

                                        <h4>Customer Details:</h4>
                                        <address id="customer_detail_vw">
                                        </address>

                                        <div class="col-md-10 col-sm-10 col-xs-6 print_hide" style="alignment: right">
                                            <input type="hidden" id="order_status_id" name="order_status_id" value="1" />
                                            <input type="hidden" id="order_id_edit">
                                            <input type="hidden" id="ordered_customer_id">
                                            <button id="order_received" onclick="update_order_status(3)" type="button" style="min-width:60px" class="order_status_btn btn btn-success btn-lg">Received</button>
                                            <button id="order_ready" onclick="update_order_status(4)" type="button" style="min-width:60px" class="order_status_btn btn btn-success btn-lg">Ready</button>
                                            <button id="order_delivered" onclick="update_order_status(5)" type="button" style="min-width:60px" class="order_status_btn btn btn-success btn-lg">Picked Up</button>
                                            <button id="order_rejected" onclick="update_order_status(2)" type="button" style="min-width:60px" class="btn btn-danger btn-lg">Reject</button>

                                        </div>

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
                                </div>
                            </div>
                            <div class="col-md-12 center print_hide"> <button type="button" class="btn btn-warning" id="order_print"><i class="fa fa-lg fa-print"></i></button></div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php
}
?>
<script src="js/customTable.js"></script>
<script src="js/autosuggest.js"></script>
<script>

    $('#refresh_btn').html('<button class="btn btn-success" style="margin: auto; position: center" onclick="load_order(``)"> Refresh</button>\n')

    $('#show_invoice').hide();

    //$('#delivery_date').val('');

    orderRefund = () =>{
        var order_id = $('#order_id_edit').val();
        var customer_id = $('#ordered_customer_id').val();
        var url = project_url+"controller/orderController.php";
        $.ajax({
            url: url,
            type:'POST',
            async:false,
            data:{
                q: "refund",
                order_id:order_id,
                customer_id:customer_id
            },
            success: function(data){
                if(data==1){
                    $('#order_status_option').html("Rejected & Refunded");
                    $('#order_status_option').css("background-color","red");
                    $('#order_refund').css('display', 'none');
                }

            }
        });
    }


    $(document).ready(function () {

        //status update action from edit modal
        update_order_status = function update_order_status(status_id){
            //alert(status_id)
            var order_id = $('#order_id_edit').val();
            var customer_id = $('#ordered_customer_id').val();
            var url = project_url+"controller/orderController.php";
            $.ajax({
                url: url,
                type:'POST',
                async:false,
                data:{
                    q: "update_order_status",
                    order_id:order_id,
                    status_id:status_id,
                    customer_id:customer_id
                },
                success: function(data){
                    //alert(data)
                    //console.log(data)
                    /*
                    if(data==1){
                        $('#order_refund').css('display', 'none');

                        if(status_id==2){
                            $('#order_status_option').html("Rejected");
                            $('#order_status_id').val(2);
                            $('#order_refund').css('display', 'block');

                            //next order status button show
                            $('#order_received').hide();
                            $('#order_ready').hide();
                            $('#order_delivered').hide();
                            $('#order_rejected').show();
                            $("#order_modal").click();

                        }
                        else if(status_id==3){
                            $('#order_status_option').html("Received");
                            $('#order_status_id').val(3);

                            //next order status button show
                            $('#order_received').hide();
                            $('#order_ready').show();
                            $('#order_delivered').hide();
                            $('#order_rejected').show();
                        }
                        else if(status_id==4){
                            $('#order_status_option').html("Ready");
                            $('#order_status_id').val(4);

                            //next order status button show
                            $('#order_received').hide();
                            $('#order_ready').hide();
                            $('#order_delivered').show();
                            $('#order_rejected').show();
                        }
                        else if(status_id==5){
                            $('#order_status_option').html("Delivered");
                            $('#order_status_id').val(5);

                            //next order status button show
                            $('#order_received').hide();
                            $('#order_ready').hide();
                            $('#order_delivered').hide();
                            $('#order_rejected').hide();
                        }

                    }

                     */
                }
            });
            $('#order_modal').modal('toggle');
            load_order("")
        }

        var current_page_no=1;
        load_order = function load_order(search_txt){
            //alert(search_txt)
            $("#search_order_button").toggleClass('active');
            var order_Table_length = parseInt($('#order_Table_length').val());
            var ad_product_name = $("#ad_product_name").val();
            var ad_order_date = $("#ad_order_date").val();
            //var ad_delivery_date = $("#ad_delivery_date").val();
            var ad_item_id = $("#ad_item_id").val();
            var ad_is_payment = $("input[name=ad_is_payment]:checked").val();
            var ad_is_order = $("input[name=ad_is_order]:checked").val();
            //alert('order2')

            $.ajax({
                url: project_url+"controller/orderController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "grid_data",
                    ad_order_date: ad_order_date,
                    //ad_delivery_date: ad_delivery_date,
                    ad_product_name: ad_product_name,
                    ad_item_id: ad_item_id,
                    ad_is_payment: ad_is_payment,
                    ad_is_order: ad_is_order,
                    search_txt: search_txt,
                    limit:order_Table_length,
                    page_no:current_page_no
                },
                success: function(data) {
                    //alert(data)
                    //console.log(data)
                    var todate = "<?php echo date("Y-m-d"); ?>";
                    var user_name =  "<?php echo $user_name; ?>";
                    var html = "";
                    if($.trim(search_txt) == "Print"){
                        var serach_areas= "";
                        if(ad_product_name != '')  	serach_areas += "Product Name: "+ad_product_name+" <br>";
                        if(ad_order_date != '')  	serach_areas += "Order Date: "+ad_order_date+" <br>";
                        //if(ad_delivery_date != '')  serach_areas += "Delivery Date: "+ad_delivery_date+" <br>";
                        if(ad_is_payment == 2)  	serach_areas += "Paid <br>";
                        if(ad_is_payment == 1)  	serach_areas += "Not Paid <br>";
                        if(ad_is_order == 1)    	serach_areas += "Ordered <br>";
                        if(ad_is_order == 2)  	    serach_areas += "Ready <br>";
                        if(ad_is_order == 3)  	    serach_areas += "Picked <br>";

                        /*<button class="no-print" onclick="window.print()">Print</button>*/

                        html +='<button class="no-print" onclick="window.print()">Print</button><div width="100%"  style="text-align:center"><img src="'+employee_import_url+'/images/logo.png" width="80"/></div><h2 style="text-align:center">Cakencookie</h2><h4 style="text-align:center">Order Information Report</h4><table width="100%"><tr><th width="60%" style="text-align:left"><small>'+serach_areas+'</small></th><th width="40%"  style="text-align:right"><small>Printed By: '+user_name+', Date:'+todate+'</small></th></tr></table>';

                        if(!jQuery.isEmptyObject(data.records)){

                            html +='<table width="100%" cellpadding="10" border="1px" style="margin-top:10px;border-collapse:collapse"><thead><tr><th style="text-align:center">Order No</th><th style="text-align:center">Customer</th><th style="text-align:center">Product</th><th style="text-align:center">Order Date</th><th style="text-align:center">Delivery Date</th><th style="text-align:center">Payment Status</th><th style="text-align:center">Order Status</th></tr></thead><tbody>';

                            $.each(data.records, function(i,data){
                                //alert(data)
                                html += "<tr>";
                                html +="<td style='text-align:left'>"+data.invoice_no+"</td>";
                                html +="<td style='text-align:left'>"+data.customer_name+"</td>";
                                var name = data.p_name;
                                var pname = name.replace(", ", "</br>");
                                html +="<td style='text-align:left'>"+pname+"</td>";
                                html +="<td style='text-align:left'>"+data.order_date+"</td>";
                                html +="<td style='text-align:left'>"+data.delivery_date+"</td>";
                                html +="<td style='text-align:center'>"+data.payment_status_text+"</td>";
                                html +="<td style='text-align:center'>"+data.order_status_text+"</td>";
                                html += '</tr>';

                            });
                            html +="</tbody></table>"
                        }
                        else{
                            html += "<table width='100%' border='1px' style='margin-top:10px;border-collapse:collapse'><tr><td><h4 style='text-align:center'>There is no data.</h4></td></tr></table>";
                        }
                        WinId = window.open("", "Order Report","width=1150,height=800,left=50,toolbar=no,menubar=YES,status=YES,resizable=YES,location=no,directories=no, scrollbars=YES");
                        WinId.document.open();
                        WinId.document.write(html);
                        WinId.document.close();
                    }
                    else{
                        if(data.entry_status==0){
                            $('.order_entry_cl').hide();
                        }
                        //for  showing grid's no of records from total no of records
                        show_record_no(current_page_no, order_Table_length, data.total_records )

                        var total_pages = data.total_pages;
                        var records_array = data.records;
                        $('#order_Table tbody tr').remove();
                        $("#search_order_button").toggleClass('active');
                        $.each(records_array, function (i, datas) {
                            //alert(i)
                            if(records_array[i]['order_status']==1){
                                records_array[i]['order_status']='Ordered'
                            }
                            else if(records_array[i]['order_status']==3){
                                records_array[i]['order_status']='Received'
                            }
                            else if(records_array[i]['order_status']==2){
                                records_array[i]['order_status']='Rejected'
                            }
                            else if(records_array[i]['order_status']==4){
                                records_array[i]['order_status']='Ready'
                            }
                            else if(records_array[i]['order_status']==5){
                                records_array[i]['order_status']='Picked Up'
                            }

                        })
                        if(!jQuery.isEmptyObject(records_array)){

                            //cnsole.log(total_order_amt)
                            //create and set grid table row
                            var colums_array=["order_id*identifier*hidden","invoice_no","customer_name","delivery_date"/*,"delivery_date"*/,"payment_method","paid_status","order_status","total_order_amt"];
                            //first element is for view , edit condition, delete condition
                            //"all" will show /"no" will show nothing
                            var condition_array=["","","update_status", "1","",""];
                            //create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
                            //cauton: not posssible to use multiple grid in same page
                            create_set_grid_table_row(records_array,colums_array,condition_array,"order","order_Table", 0);
                            //show the showing no of records and paging for records
                            $('#order_Table_div').show();
                            //code for dynamic pagination
                            paging(total_pages, current_page_no, "order_Table" );
                        }
                        //if the table has no records / no matching records
                        else{
                            grid_has_no_result("order_Table",10);
                        }
                    }

                }
            });
        }

        set_time_out_fn = function set_time_out_fn(){
            setTimeout(function(){
                load_order('')
                set_time_out_fn();
            }, 50000);
        }


        // load desire page on clik specific page no
        load_page = function load_page(page_no){
            if(page_no != 0){
                // every time current_page_no need to change if the user change page
                current_page_no=page_no;
                var search_txt = $("#search_order_field").val();
                load_order(search_txt)
            }
        }
        // function after click search button
        $('#search_order_button').click(function(){
            var search_txt = $("#search_order_field").val();
            // every time current_page_no need to set to "1" if the user search from search bar
            current_page_no=1;
            load_order(search_txt);
        });
        //function after press "enter" to search
        $('#search_order_field').keypress(function(event){
            var search_txt = $("#search_order_field").val();
            if(event.keyCode == 13){
                // every time current_page_no need to set to "1" if the user search from search bar
                current_page_no=1;
                load_order(search_txt)
            }
        })
        // load data initially on page load with paging
        load_order("");

        //advance search
        $('#adv_search_button').click(function(){
            load_order("Advance_search");
        });

        //print advance search data
        $('#adv_search_print').click(function(){
            load_order("Print");
        });


        $(document).on('click','#order_print', function(){
            $('.print_hide').css('display','none')
            var divContents = $("#order-div").html();
            var printWindow = window.open('', '', 'height=400,width=800');
            printWindow.document.write('<html><head><title>DIV Contents</title>');
            printWindow.document.write('</head><body style="padding:10px">');
            printWindow.document.write('<link href="../plugin/bootstrap/bootstrap.css" rel="stylesheet">');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
            $('.print_hide').css('display','block')

        });


        //order status update
        edit_order = function edit_order(order_id){
            //$('.print_hide').css('display','block')

            $('#ord_detail_vw>table>tbody').html('');
            $.ajax({
                url: project_url+"controller/orderController.php",
                type:'POST',
                async:false,
                dataType: "json",
                data:{
                    q: "get_order_details_by_invoice",
                    order_id: order_id
                },
                success: function(data){
                    //console.log(data)
                    $('#dlv_date').css("background","");
                    var order_summery=''
                    if(data.type=='group') {

                        $('#order_id_edit').val(data.order_details.invoice_no);
                        $('#ordered_customer_id').val(data.order_details.customer_id);
                        $('#ord_title_vw').html(data.order_details.invoice_no);
                        $('#ord_date').html("Ordered Time: "+data.order_details.order_date);
                        $('#dlv_date').html("Delivery Time: "+data.order_details.delivery_date);
                        $('#dlv_ps').html("Payment Status: "+data.order_details.paid_status);
                        $('#dlv_pm').html("Payment Method: "+data.order_details.payment_method);
                        $('#customer_detail_vw').html(" "+data.order_details.full_name+"<br/><b>Email:</b> "+data.order_details.email+"<br/><b>Mobile:</b> "+data.order_details.mobile+"<br/><b>Address:</b> "+data.order_details.c_address);
                        $('#note_vw').html(data.remarks);

                        order_summery += '<tr><td colspan="3" align="right" ><b>Discount Amount</b></td><td align="right"><b>'+data.order_details.discount_amount+'</b></td></tr>';
                        order_summery += '<tr><td colspan="3" align="right" ><b>Tax Amount</b></td><td align="right"><b>'+data.order_details.tax_amount+'</b></td></tr>';
                        order_summery += '<tr><td colspan="3" align="right" ><b>Tips Amount</b></td><td align="right"><b>'+data.order_details.tips+'</b></td></tr>';
                        order_summery += '<tr><td colspan="3" align="right" ><b>Total Amount</b></td><td align="right"><b>'+data.order_details.total_paid_amount+'</b></td></tr>';
                        //alert(order_summery)


                    }


                    if(!jQuery.isEmptyObject(data.records)){
                        var total_order=0;
                        $.each(data.records, function(i,datas){

                            console.log(datas)
                            if(data.type=='individual') {
                                //alert('indi')
                                if(datas.ASAP==1){
                                    $('#dlv_date').html("Delivery Time:"+datas.delivery_date+"<b> (ASAP)</b>");
                                    $('#dlv_date').css("background","yellow");
                                }
                                else {
                                    $('#dlv_date').html("Delivery Time: "+datas.delivery_date);
                                }
                                $('#order_id_edit').val(datas.invoice_no);
                                $('#ordered_customer_id').val(datas.customer_id);
                                $('#ord_title_vw').html(datas.invoice_no);
                                $('#ord_date').html("Ordered Time: "+datas.order_date);
                                $('#dlv_ps').html("Payment Status: "+datas.paid_status);
                                $('#dlv_pm').html("Payment Method: "+datas.payment_method);
                                $('#customer_detail_vw').html(" "+datas.customer_name+"<br/><b>Email:</b> "+datas.email+"<br/><b>Mobile:</b> "+datas.customer_contact_no+"<br/><b>Address:</b> "+datas.customer_address);
                                $('#note_vw').html(datas.remarks);

                                var order_summery = '<tr><td colspan="3" align="right" ><b>Discount Amount</b></td><td align="right"><b>'+(parseFloat(datas.discount_amount)).toFixed(2)+'</b></td></tr>';
                                order_summery += '<tr><td colspan="3" align="right" ><b>Tax Amount</b></td><td align="right"><b>'+(parseFloat(datas.tax_amount)).toFixed(2)+'</b></td></tr>';
                                order_summery += '<tr><td colspan="3" align="right" ><b>Tips Amount</b></td><td align="right"><b>'+(parseFloat(datas.tips)).toFixed(2)+'</b></td></tr>';
                                order_summery += '<tr><td colspan="3" align="right" ><b>Total Amount</b></td><td align="right"><b>'+(parseFloat(datas.total_paid_amount)).toFixed(2)+'</b></td></tr>';

                            }


                            var order_tr = "";
                            var order_total = 0;
                            order_infos	 = datas.order_info;
                            var order_arr = order_infos.split('..,');
                            //console.log(order_arr)
                            $.each(order_arr, function(i,orderInfo){
                                //console.log(orderInfo)
                                //alert(i)
                                if(orderInfo){
                                    var order_info_arr = orderInfo.split('#');
                                    var total = ((parseFloat(order_info_arr[4])*parseFloat(order_info_arr[5])));
                                    order_tr += '<tr><td class="text-capitalize">'+order_info_arr[2].split('..')[0]+' <br>'+order_info_arr[6].split('..')[0]+'</td><td align="center">'+order_info_arr[5]+'</td><td align="right">'+parseFloat(order_info_arr[4]).toFixed(2)+'</td><td align="right">'+parseFloat(total).toFixed(2)+'</td></tr>';
                                    order_total += total;
                                }

                            });

                            //var total_order_bill = (parseFloat(order_total);
                            total_order += order_total;
                            if(data.type=='individual'){
                                order_tr += '<tr><td colspan="3" align="right" ><b>Total Amount</b></td><td align="right"><b>'+(total_order).toFixed(2)+'</b></td></tr>'+order_summery;
                                var status = datas.order_status;
                                payment_status =datas.payment_status

                            }
                            else {
                                var status = data.order_details.status-2;
                                payment_status =data.order_details.payment_status

                            }

                            //alert(payment_status)

                            $('#ord_detail_vw>table>tbody').append(order_tr);


                            //alert(status)
                            $('#order_refund').css('display', 'none');

                            $('#order_status_option').css("background-color","green");

                            if(status==2){
                                $('#order_status_option').css("background-color","red");
                                $('#order_status_id').val(2);
                                if(payment_status==3){
                                    $('#order_status_option').html("Rejected & Refunded");
                                    $('#order_refund').css('display', 'none');
                                }else if(payment_status==2){
                                    $('#order_status_option').html("Rejected");
                                    $('#order_refund').css('display', 'block');
                                }else {
                                    $('#order_status_option').html("Rejected");
                                    $('#order_refund').css('display', 'none');
                                }


                                //next order status button show
                                $('#order_received').hide();
                                $('#order_ready').hide();
                                $('#order_delivered').hide();
                                $('#order_rejected').hide();
                            }
                            else if(status==3){
                                $('#order_status_option').html("Received");
                                $('#order_status_id').val(3);

                                //next order status button show
                                $('#order_received').hide();
                                $('#order_ready').show();
                                $('#order_delivered').hide();
                                $('#order_rejected').show();
                            }
                            else if(status==4){
                                $('#order_status_option').html("Ready");
                                $('#order_status_id').val(4);

                                //next order status button show
                                $('#order_received').hide();
                                $('#order_ready').hide();
                                $('#order_delivered').show();
                                $('#order_rejected').show();
                            }
                            else if(status==5){
                                $('#order_status_option').html("Picked Up");
                                $('#order_status_id').val(5);

                                //next order status button show
                                $('#order_received').hide();
                                $('#order_ready').hide();
                                $('#order_delivered').hide();
                                $('#order_rejected').hide();
                            }
                            else{
                                $('#order_status_option').html("Ordered");
                                $('#order_status_id').val(1);

                                //next order status button show
                                $('#order_received').show();
                                $('#order_ready').hide();
                                $('#order_delivered').hide();
                                $('#order_rejected').show();
                            }
                            //for small device
                        });
                    }

                    if(data.type!='individual'){
                        order_tr = '<tr><td colspan="3" align="right" ><b>Total Amount</b></td><td align="right"><b>'+total_order+'</b></td></tr>'+order_summery;
                        $('#ord_detail_vw>table>tbody').append(order_tr);
                    }
                }
            });

            $('#order_modal').modal();
        }

        $('#show_invoice').click(function(){
            order_id = $('#order_id').val();
            view_order(order_id);
        });

        delete_order = function delete_order(order_id){
            if (confirm("Do you want to delete the record? ") == true) {
                $.ajax({
                    url: project_url+"controller/orderController.php",
                    type:'POST',
                    async:false,
                    data: "q=delete_order&order_id="+order_id,
                    success: function(data){
                        if($.trim(data) == 1){
                            success_or_error_msg('#page_notification_div',"success","Deleted Successfully");
                            load_order("");
                            clear_form();
                        }
                        else{
                            success_or_error_msg('#page_notification_div',"danger","Not Deleted...");
                        }
                    }
                });
            }
        }


    });


</script>