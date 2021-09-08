<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
$user_type = $_SESSION['user_type'];
if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$website_url."../view/login.php");
else if($dbClass->getUserGroupPermission(53) != 1 ){
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
            <h2>Special Days </h2>
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
                    <select size="1" style="width: 56px;padding: 6px;" id="category_Table_length" name="category_Table_length" aria-controls="category_Table">
                        <option value="50" selected="selected">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                    </select>
                    Post
                </label>
            </div>
            <div class="dataTables_filter" id="category_Table_filter">
                <div class="input-group">
                    <input class="form-control" id="search_category_field" style="" type="text">
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-primary has-spinner" id="search_category_button">
                     <span class="spinner"><i class="fa fa-spinner fa-spin fa-fw "></i></span>
                     <i class="fa  fa-search "></i>
                    </button>
                </span>
                </div>
            </div>
            <div style="height:250px; width:100%; overflow-y:scroll">
                <table id="category_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped  table-scroll ">
                    <thead >
                    <tr class="headings">
                        <th class="column-title" width="20%">Start Date</th>
                        <th class="column-title" width="20%">End Date</th>
                        <th class="column-title" width="30%">Time</th>
                        <th class="column-title" width="20%">Status</th>
                        <th class="column-title no-link last" width="10%"><span class="nobr"></span></th>
                    </tr>
                    </thead>
                    <tbody id="category_table_body" class="scrollable">

                    </tbody>
                </table>
            </div>
            <div id="category_Table_div">
                <div class="dataTables_info" id="category_Table_info">Showing <span id="from_to_limit"></span> of  <span id="total_record"></span> entries</div>
                <div class="dataTables_paginate paging_full_numbers" id="category_Table_paginate">
                </div>
            </div>
        </div>
    </div>
    <?php if($dbClass->getUserGroupPermission(51) == 1){ ?>
        <div class="x_panel category_entry_cl">
            <div class="x_title">
                <h2>Special Days Entry</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" id="iniial_collapse">
                <form method="POST"  id="special_day_form" name="special_day_form" enctype="multipart/form-data" class="form-horizontal form-label-left">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label col-md-3 col-sm-3 col-xs-6">Date From<span class="required">*</span></label>
                                <div class="col-md-3 col-sm-3 col-xs-6">
                                    <input type="date" id="date_from" name="date_from" class="form-control col-lg-12"/>
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-6">Date To</label>
                                <div class="col-md-3 col-sm-3 col-xs-6">
                                    <input type="date" id="date_to" name="date_to" class="form-control col-lg-12"/>
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label col-md-3 col-sm-3 col-xs-6">Open</label>
                                <div class="col-md-3 col-sm-3 col-xs-6">
                                    <input type="time" id="open" name="open" class="form-control col-lg-12"/>
                                </div>
                                <label class="control-label col-md-3 col-sm-3 col-xs-6">Close</label>
                                <div class="col-md-3 col-sm-3 col-xs-6">
                                    <input type="time" id="close" name="close" class="form-control col-lg-12"/>
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label col-md-3 col-sm-3 col-xs-6">Status<span class="required">*</span></label>
                                <div class="col-md-3 col-sm-3 col-xs-6">
                                    <select class="form-control" name="status" id="status">
                                        <option value='1'>Open</option>
                                        <option value='0'>Close</option>
                                    </select>
                                </div>

                                <label class="control-label col-md-3 col-sm-3 col-xs-6">All Day</label>
                                <div class="col-md-3 col-sm-3 col-xs-6">
                                    <input type="checkbox" id="all_day" name="all_day" class="form-control col-lg-12 icheckbox_flat-yellow"/>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="ln_solid"></div>
                        <label class="control-label col-md-4 col-sm-4 col-xs-12"></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <input type="hidden" id="special_day_id" name="special_day_id" />
                            <button  type="submit" id="save_category" class="btn btn-success">Save</button>
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
    //------------------------------------- general & UI  --------------------------------------
    /*
    develped by @momit
    =>load grid with paging
    =>search records
    */

    console.log(project_url)

    $(document).ready(function () {
        var current_page_no=1;
        load_data = function load_data(search_txt){
            $("#search_category_button").toggleClass('active');
            var category_Table_length = parseInt($('#category_Table_length').val());
            $.ajax({
                url: project_url+"controller/calenderController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "grid_data_special_day",
                    search_txt: search_txt,
                    limit:category_Table_length,
                    page_no:current_page_no
                },
                success: function(data) {
                    if(data.entry_status==0){
                        $('.category_entry_cl').hide();
                    }
                    //for  showing grid's no of records from total no of records
                    show_record_no(current_page_no, category_Table_length, data.total_records )

                    var total_pages = data.total_pages;
                    var records_array = data.records;
                    $('#category_Table tbody tr').remove();
                    $("#search_category_button").toggleClass('active');
                    if(!jQuery.isEmptyObject(records_array)){
                        //create and set grid table row
                        //var colums_array=["date_from","id*identifier*hidden","name","code"];
                        var colums_array=["id*identifier*hidden","date_from","date_to","all_day","status"];

                        //first element is for view , edit condition, delete condition
                        //"all" will show /"no" will show nothing
                        var condition_array=["","","update_status", "1","delete_status","1"];
                        //create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
                        //cauton: not posssible to use multiple grid in same page
                        create_set_grid_table_row(records_array,colums_array,condition_array,"category","category_Table", 0);
                        //show the showing no of records and paging for records
                        $('#category_Table_div').show();
                        //code for dynamic pagination
                        paging(total_pages, current_page_no, "category_Table" );
                    }
                    //if the table has no records / no matching records
                    else{
                        grid_has_no_result("category_Table",4);
                    }
                }
            });
        }
        // load desire page on clik specific page no
        load_page = function load_page(page_no){
            if(page_no != 0){
                // every time current_page_no need to change if the user change page
                current_page_no=page_no;
                var search_txt = $("#search_category_field").val();
                load_data(search_txt)
            }
        }
        // function after click search button
        $('#search_category_button').click(function(){
            var search_txt = $("#search_category_field").val();
            // every time current_page_no need to set to "1" if the user search from search bar
            current_page_no=1;
            load_data(search_txt);
        });
        //function after press "enter" to search
        $('#search_category_field').keypress(function(event){
            var search_txt = $("#search_category_field").val();
            if(event.keyCode == 13){
                // every time current_page_no need to set to "1" if the user search from search bar
                current_page_no=1;
                load_data(search_txt)
            }
        })
        // load data initially on page load with paging
        load_data("");

        //insert category
        $('#save_category').click(function(event){
            event.preventDefault();
            //alert($('#special_day_id').val())
            var formData = new FormData($('#special_day_form')[0]);
            formData.append("q","insert_or_update_special_day");
            //validation

                $.ajax({
                    url: project_url+"controller/calenderController.php",
                    type:'POST',
                    data:formData,
                    async:false,
                    cache:false,
                    contentType:false,processData:false,
                    success: function(data){
                        //console.log(data)
                        $('#save_category').removeAttr('disabled','disabled');

                        if($.isNumeric(data)==true && data == 5){
                            success_or_error_msg('#form_submit_error',"danger","Please Insert Unique Code","#category_code");
                        }
                        else if($.isNumeric(data)==true && data>0){
                            success_or_error_msg('#form_submit_error',"success","Save Successfully");
                            load_data("");
                            clear_form();
                        }
                    }
                });


        })

        //edit category
        edit_category = function edit_category(id){
            $.ajax({
                url: project_url+"controller/calenderController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "get_special_day_details",
                    id: id
                },
                success: function(data){
                    if(!jQuery.isEmptyObject(data.records)){
                        $.each(data.records, function(i,data){

                            $('#date_from').val(data.date_from);
                            $('#date_to').val(data.date_to);
                            $('#open').val(data.open);
                            $('#close').val(data.close);
                            $('#status').val(data.status);
                            //$('#all_day').val(data.all_day);


                            if(data.all_day==1){
                                $('#all_day').iCheck('check');
                            }
                            else if(data.all_day==0){
                                $('#all_day').iCheck('uncheck');
                            }

                            $('#special_day_id').val(data.id);



                            $('#save_category').html('Update');
                            // to open submit post section
                            if($.trim($('#toggle_form i').attr("class"))=="fa fa-chevron-down")
                                $( "#toggle_form" ).trigger( "click" );
                        });
                    }
                }
            });
        }

        delete_category = function delete_category(id){
            if (confirm("Do you want to delete the record? ") == true) {
                $.ajax({
                    url: project_url+"controller/calenderController.php",
                    type:'POST',
                    async:false,
                    data: "q=delete_special_day&special_day_id="+id,
                    success: function(data){
                        //console.log(data)
                        if($.trim(data) == 1){
                            success_or_error_msg('#page_notification_div',"success","Deleted Successfully");
                            load_data("");
                        }
                        else{
                            success_or_error_msg('#page_notification_div',"danger","Not Deleted...");
                        }
                    }
                });
            }
        }

        clear_form = function clear_form(){
            $('#special_day_id').val('');
            $("#special_day_form").trigger('reset');
            $('#save_category').html('Save');
        }

        $('#clear_button').click(function(){
            clear_form();
        });

    });


</script>