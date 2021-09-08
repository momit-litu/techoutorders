
<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
$user_type = $_SESSION['user_type'];
if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$website_url."../view/login.php");
else if($dbClass->getUserGroupPermission(81) != 1 ){
    ?>
    <div class="x_panel">
        <div class="alert alert-danger" align="center">You Don't Have permission of this Page.</div>
    </div>
    <?php
}
else{
    ?>


    <div class="x_panel">
        <div class="x_title">
            <h2>Coupons</h2>
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
                    <select size="1" style="width: 56px;padding: 6px;" id="coupon_Table_length" name="coupon_Table_length" aria-controls="coupon_Table">
                        <option value="50" selected="selected">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                    </select>
                    Post
                </label>
            </div>
            <div class="dataTables_filter" id="coupon_Table_filter">
                <div class="input-group">
                    <input class="form-control" id="search_coupon_field" style="" type="text">
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-primary has-spinner" id="search_coupon_button">
                     <span class="spinner"><i class="fa fa-spinner fa-spin fa-fw "></i></span>
                     <i class="fa  fa-search "></i>
                    </button>
                </span>
                </div>
            </div>
            <div style="height:250px; width:100%; overflow-y:scroll">
                <table id="coupon_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped  table-scroll ">
                    <thead >
                    <tr class="headings">
                        <th class="column-title" width="10%">Coupon NO</th>
                        <th class="column-title" width="10%">Coupon Type</th>
                        <th class="column-title" width="">Customer Name</th>
                        <th class="column-title" width="15%">Amount</th>
                        <th class="column-title" width="15%">Start Date</th>
                        <th class="column-title" width="15%">End Date</th>
                        <th class="column-title" width="10%">Status</th>
                        <th class="column-title no-link last" width="8%"><span class="nobr"></span></th>
                    </tr>
                    </thead>
                    <tbody id="coupon_table_body" class="scrollable">

                    </tbody>
                </table>
            </div>
            <div id="coupon_Table_div">
                <div class="dataTables_info" id="coupon_Table_info">Showing <span id="from_to_limit"></span> of  <span id="total_record"></span> entries</div>
                <div class="dataTables_paginate paging_full_numbers" id="coupon_Table_paginate">
                </div>
            </div>
        </div>
    </div>
    <?php if($dbClass->getUserGroupPermission(78) == 1){ ?>
        <div class="x_panel coupon_entry_cl">
            <div class="x_title">
                <h2>Coupon Entry</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" id="iniial_collapse">
                <form method="POST" id="coupon_form" name="coupon_form" enctype="multipart/form-data" class="form-horizontal form-label-left">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Offer Title</label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" id="offer_title" name="offer_title" class="form-control col-lg-12"/>
                                </div>

                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Customer</label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" id="customer_name" name="customer_name" class="form-control col-lg-12"/>
                                    <input type="hidden" id="customer_id" name="customer_id"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Coupon No<span class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" id="coupon_no" name="coupon_no" class="form-control col-lg-12"/>
                                </div>
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Entry Date<span class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" id="entry_date" name="entry_date" class="item form-control col-lg-12"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Start Date<span class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" id="start_date" name="start_date" class="item form-control col-lg-12"/>
                                </div>
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">End Date<span class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" id="end_date" name="end_date" class="item form-control col-lg-12"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-6">Coupon Type<span class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <select class="form-control" name="coupon_type" id="coupon_type">
                                        <option value='1'>Flat Rate</option>
                                        <option value='2'>Percentage</option>
                                    </select>
                                </div>
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Amount<span class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" id="amount" name="amount" class="form-control col-lg-12"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Minimum Order Amount<span class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" id="min_order_amount" name="min_order_amount" class="form-control col-lg-12"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-6" for="name">Is Active</label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="checkbox" id="is_active" name="is_active" checked='checked' class="form-control col-lg-12"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-6" >Customer Group</label>
                                <div id="group_select" class="col-md-10 col-sm-10 col-xs-12"></div>
                            </div>
                            <div class="ln_solid"></div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>

                    <div class="form-group">
                        <div class="ln_solid"></div>
                        <div class="col-md-7 col-sm-7 col-xs-12" style="text-align:right">
                            <input type="hidden" id="coupon_id" name="coupon_id" />
                            <button type="submit" id="save_coupon" class="btn btn-success">Save</button>
                            <button type="button" id="clear_button"  class="btn btn-primary">Clear</button>
                        </div>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <div id="form_submit_error" class="text-center" style="display:none"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}
?>
<script src="js/customTable.js"></script>
<script>

    $(document).ready(function () {
        // close form submit section onload page
        $('#coupon_form input#check-all').on('ifChecked', function () {
            $("#coupon_form .tablecoupon").iCheck('check');
        });
        $('#coupon_form input#check-all').on('ifUnchecked', function () {
            $("#coupon_form .tablecoupon").iCheck('uncheck');
        });

        load_user_groups = function load_user_groups(){
            $.ajax({
                url: project_url+"controller/customerController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "get_customer_groups"
                },
                success: function(data) {
                    //alert(data)
                    //var option_html = '';
                    if(!jQuery.isEmptyObject(data.records)){
                        var html = '<table class="table table-bordered jambo_table"><thead><tr class="headings"><th class="column-title text-center" class="col-md-8 col-sm-8 col-xs-8" >User Groups</th><th class="col-md-2 col-sm-2 col-xs-12"><input type="checkbox" id="check-all" class="tablecoupon">Select All</th></tr></thead>';
                        $.each(data.records, function(i,datas){
                            html += '<tr><td colspan="2">';
                            $.each(datas.module_group, function(i,module_group){
                                module_group_arr = module_group.split("*");
                                html += '<div class="col-md-3" ><input type="checkbox" name="group[]"  id="'+module_group_arr[0]+'" class="tablecoupon"  value="'+module_group_arr[0]+'"/> '+module_group_arr[1]+'</div>';
                            });
                            html += '</td></tr>';

                        });
                        html +='</table>';
                    }
                    $('#group_select').html(html);
                    $('#coupon_form').iCheck({
                        checkboxClass: 'icheckbox_flat-green',
                        radioClass: 'iradio_flat-green'
                    });
                    //alert('ok')

                    $('#coupon_form input#check-all').on('ifChecked', function () {
                        alert('check');
                        $("#coupon_form .tablecoupon").iCheck('check');
                    });
                    $('#coupon_form input#check-all').on('ifUnchecked', function () {
                        alert('ucheck');
                        $("#coupon_form .tablecoupon").iCheck('uncheck');
                    });
                }
            });
        }

        load_user_groups();

        $('#coupon_form input#check-all').on('ifChecked', function () {
            $("#coupon_form .tablecoupon").iCheck('check');
        });
        $('#coupon_form input#check-all').on('ifUnchecked', function () {
            $("#coupon_form .tablecoupon").iCheck('uncheck');
        });

        $('#start_date').val('');
        $('#end_date').val('');

        $('#coupon_form').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });

        $('.flat_radio').iCheck({
            //checkboxClass: 'icheckbox_flat-green'
            radioClass: 'iradio_flat-green'
        });

        $("#customer_name").autocomplete({
            search: function() {
                //alert("i m in")
            },
            source: function(request, response) {
                $.ajax({
                    url: project_url+'controller/couponController.php',
                    dataType: "json",
                    type: "post",
                    async:false,
                    data: {
                        q: "customerInfo",
                        term: request.term
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
            }
        });
    });
    $(document).ready(function () {
        var current_page_no=1;
        load_coupon = function load_coupon(search_txt){
            $("#search_coupon_button").toggleClass('active');
            var coupon_Table_length = parseInt($('#coupon_Table_length').val());
            $.ajax({
                url: project_url+"controller/couponController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "grid_data",
                    search_txt: search_txt,
                    limit:coupon_Table_length,
                    page_no:current_page_no
                },
                success: function(data) {
                    if(data.entry_status==0){
                        $('.coupon_entry_cl').hide();
                    }
                    //for  showing grid's no of records from total no of records
                    show_record_no(current_page_no, coupon_Table_length, data.total_records )

                    var total_pages = data.total_pages;
                    var records_array = data.records;
                    $('#coupon_Table tbody tr').remove();
                    $("#search_coupon_button").toggleClass('active');
                    if(!jQuery.isEmptyObject(records_array)){
                        //create and set grid table row
                        var colums_array=["id*identifier*hidden","cupon_no","c_type_name","customer_name","amount","start_date","end_date","status_text"];
                        //first element is for view , edit condition, delete condition
                        //"all" will show /"no" will show nothing
                        var condition_array=["","","update_status", "1","delete_status","1"];
                        //create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
                        //cauton: not posssible to use multiple grid in same page
                        create_set_grid_table_row(records_array,colums_array,condition_array,"coupon","coupon_Table", 0);
                        //show the showing no of records and paging for records
                        $('#coupon_Table_div').show();
                        //code for dynamic pagination
                        paging(total_pages, current_page_no, "coupon_Table" );
                    }
                    //if the table has no records / no matching records
                    else{
                        grid_has_no_result("coupon_Table",8);
                    }
                }
            });
        }
        // load desire page on clik specific page no
        load_page = function load_page(page_no){
            if(page_no != 0){
                // every time current_page_no need to change if the user change page
                current_page_no=page_no;
                var search_txt = $("#search_coupon_field").val();
                load_coupon(search_txt)
            }
        }
        // function after click search button
        $('#search_coupon_button').click(function(){
            var search_txt = $("#search_coupon_field").val();
            // every time current_page_no need to set to "1" if the user search from search bar
            current_page_no=1;
            load_coupon(search_txt);
        });
        //function after press "enter" to search
        $('#search_coupon_field').keypress(function(event){
            var search_txt = $("#search_coupon_field").val();
            if(event.keyCode == 13){
                // every time current_page_no need to set to "1" if the user search from search bar
                current_page_no=1;
                load_coupon(search_txt)
            }
        })
        // load data initially on page load with paging
        load_coupon("");

        //insert coupon
        $('#save_coupon').click(function(event){
            event.preventDefault();
            var formData = new FormData($('#coupon_form')[0]);
            formData.append("q","insert_or_update");
            //validation

            if($.trim($('#offer_title').val()) == ""){
                success_or_error_msg('#form_submit_error','danger','Please Insert Offer Title',"#coupon_no");
            }
            else if($.trim($('#coupon_no').val()) == ""){
                success_or_error_msg('#form_submit_error','danger','Please Insert Coupon No',"#coupon_no");
            }
            else if($.trim($('#start_date').val()) == ""){
                success_or_error_msg('#form_submit_error','danger','Please Select Start Date',"#start_date");
            }
            else if($.trim($('#end_date').val()) == "0"){
                success_or_error_msg('#form_submit_error','danger',"Please Select End Date","#end_date");
            }
            else if($.trim($('#amount').val()) == ""){
                success_or_error_msg('#form_submit_error','danger',"Please Insert Amount","#amount");
            }
            else if($.trim($('#min_order_amount').val()) == ""){
                success_or_error_msg('#form_submit_error','danger',"Please Insert Minimum Order Amount","#min_order_amount");
            }
            else{
                $.ajax({
                    url: project_url+"controller/couponController.php",
                    type:'POST',
                    data:formData,
                    async:false,
                    cache:false,
                    contentType:false,
                    processData:false,
                    success: function(data){
                        //alert(data)
                        $('#save_coupon').removeAttr('disabled','disabled');

                        if($.isNumeric(data)==true && data==5){
                            success_or_error_msg('#form_submit_error',"danger","Please Insert Unique Coupon No","#coupon_no" );
                        }
                        else if($.isNumeric(data)==true && data>0){
                            success_or_error_msg('#form_submit_error',"success","Save Successfully");
                            load_coupon("");
                            clear_form();
                        }
                    }
                });

            }
        })

        //edit coupon
        edit_coupon = function edit_coupon(coupon_id){
            //$('#amount').attr("disabled", false);
            //$('#coupon_type').attr("disabled", false);
            load_user_groups()
            $.ajax({
                url: project_url+"controller/couponController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "get_coupon_details",
                    coupon_id: coupon_id
                },
                success: function(data){
                    if(!jQuery.isEmptyObject(data.records)){
                        $.each(data.records, function(i,data){
                            $('#coupon_id').val(coupon_id);
                            $('#offer_title').val(data.offer_title);
                            $('#customer_id').val(data.customer_id);
                            $('#customer_name').val(data.customer_name);
                            $('#coupon_no').val(data.cupon_no);
                            $('#start_date').val(data.start_date);
                            $('#end_date').val(data.end_date);
                            $('#entry_date').val(data.entry_date);
                            $('#amount').val(data.amount);
                            $('#coupon_type').val(data.c_type);
                            $('#min_order_amount').val(data.min_order_amount);

                            if(data.status==1){
                                $('#is_active').iCheck('check');
                            }
                            else if(data.status==0){
                                $('#is_active').iCheck('uncheck');
                            }
                        });
                    }
                    if(!jQuery.isEmptyObject(data.group)) {
                        console.log(data.group)
                        $.each(data.group, function (key, groups) {
                            $("#"+groups['customer_group_id']).iCheck('check');
                        })
                    }

                        if(data.coupon_details != null){
                        $('#amount').attr("disabled", true);
                        $('#coupon_type').attr("disabled", true);
                    }

                    $('#save_coupon').html('Update');

                    // to open submit post section
                    if($.trim($('#toggle_form i').attr("class"))=="fa fa-chevron-down")
                        $( "#toggle_form" ).trigger( "click" );
                }
            });
        }

        delete_coupon = function delete_coupon(coupon_id){
            if (confirm("Do you want to delete the record? ") == true) {
                $.ajax({
                    url: project_url+"controller/couponController.php",
                    type:'POST',
                    async:false,
                    data: "q=delete_coupon&coupon_id="+coupon_id,
                    success: function(data){
                        if($.trim(data) == 1){
                            success_or_error_msg('#page_notification_div',"success","Deleted Successfully");
                            load_coupon("");
                            clear_form();
                        }
                        else{
                            success_or_error_msg('#page_notification_div',"danger","Not Deleted...");
                        }
                    }
                });
            }
        }

        clear_form = function clear_form(){
            $('#coupon_id').val('');
            $('#customer_id').val('');

            $("#coupon_form").trigger('reset');

            $('#coupon_form').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });

            $('#amount').attr("disabled", false);
            $('#coupon_type').attr("disabled", false);

            $("#coupon_form .tableflat").iCheck('uncheck');
            $('#save_coupon').html('Save');
        }

        $('#clear_button').click(function(){
            clear_form();
        });

    });
</script>

