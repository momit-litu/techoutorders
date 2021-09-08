<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
$user_type = $_SESSION['user_type'];
if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$userurl."../view/login.php");
else if($dbClass->getUserGroupPermission(45) != 1){
    ?>
    <div class="x_panel">
        <div class="alert alert-danger" align="center">You Don't Have permission of this Page.</div>
    </div>
    <?php
}
else{
    $user_name = $_SESSION['user_name'];
    ?>

    <div class="x_panel">
        <div class="x_title">
            <h2>Banner Images</h2>
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
                    <select size="1" style="width: 56px;padding: 6px;" id="bannerImg_Table_length" name="bannerImg_Table_length" aria-controls="bannerImg_Table">
                        <option value="50" selected="selected">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                    </select>
                    Post
                </label>
            </div>
            <div class="dataTables_filter" id="bannerImg_Table_filter">
                <div class="input-group">
                    <input class="form-control" id="search_bannerImg_field" style="" type="text">
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-primary has-spinner" id="search_bannerImg_button">
                     <span class="spinner"><i class="fa fa-spinner fa-spin fa-fw "></i></span>
                     <i class="fa  fa-search "></i>
                    </button>
                </span>
                </div>
            </div>
            <div style="height:250px; width:100%; overflow-y:scroll">
                <table id="bannerImg_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped  table-scroll ">
                    <thead >
                    <tr class="headings">
                        <th class="column-title" width="5%"></th>
                        <th class="column-title" width="10%">ID</th>
                        <th class="column-title" width="">Title</th>
                        <th class="column-title" width="30%">Text</th>
                        <th class="column-title no-link last" width="100"><span class="nobr"></span></th>
                    </tr>
                    </thead>
                    <tbody id="bannerImg_table_body" class="scrollable">

                    </tbody>
                </table>
            </div>
            <div id="bannerImg_Table_div">
                <div class="dataTables_info" id="bannerImg_Table_info">Showing <span id="from_to_limit"></span> of  <span id="total_record"></span> entries</div>
                <div class="dataTables_paginate paging_full_numbers" id="bannerImg_Table_paginate">
                </div>
            </div>
        </div>
    </div>

    <div class="x_panel bannerImg_entry_cl">
        <div class="x_title">
            <h2>Banner Image Entry</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content" id="iniial_collapse">
            <br />
            <form id="bannerImg_form" name="bannerImg_form" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">Title<span class="required">*</span></label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="title" name="title" required class="form-control col-lg-12"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">Text</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <textarea rows="4" id="text" name="text" class="form-control col-xs-12" type="text"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">Photo<span class="required">*</span></label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="file" name="bannerImg_image_upload" id="bannerImg_image_upload">
                                <small style="color:blue">Image size should be (1920x1200)px and file size under 5mb. </small><br>
                                <small style="color:red">Please be aware that any space in image name will break the homepage banner animation. So please edit the image name before upload. </small>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-5 imgDiv">
                        <img src="<?php echo $website_url ?>admin/images/no_image.png" width="100%" height="196px" class="img-fluid" id="bannerImg_img">
                    </div>
                </div>
                <div class="form-group">
                    <div class="ln_solid"></div>
                    <div class="col-md-4 col-sm-4 col-xs-12" style="text-align:right">
                        <input type="hidden" id="bannerImg_id" name="bannerImg_id" />
                        <button type="submit" id="save_bannerImg_info" class="btn btn-success">Save</button>
                        <button type="button" id="clear_button" class="btn btn-primary">Clear</button>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div id="form_submit_error" class="text-center" style="display:none"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
}
?>
<script src="js/customTable.js"></script>
<script>

    $(document).ready(function () {
        var user_type = "<?php echo $user_type; ?>";


    });

    $(document).ready(function (){
        // initialize page no to "1" for paging
        var current_page_no=1;

        $('.imgDiv').hide();

        load_data = function load_data(search_txt){
            $("#search_bannerImg_button").toggleClass('active');
            var bannerImg_Table_length =parseInt($('#bannerImg_Table_length').val());

            $.ajax({
                url: project_url+"controller/webSiteSettingsController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "grid_data_banner",
                    search_txt: search_txt,
                    limit:bannerImg_Table_length,
                    page_no:current_page_no
                },
                success: function(data){
                    if(data.entry_status==0){
                        $('.bannerImg_entry_cl').hide();
                    }

                    // for  showing grid's no of records from total no of records
                    show_record_no(current_page_no, bannerImg_Table_length, data.total_records )

                    var total_pages = data.total_pages;
                    var records_array = data.records;
                    $('#bannerImg_Table tbody tr').remove();
                    //$("#search_bannerImg_button").toggleClass('active');
                    if(!jQuery.isEmptyObject(records_array)){
                        // create and set grid table row
                        var colums_array=["photo*image*"+project_url,"id*identifier", "title","text"];
                        // first element is for view , edit condition, delete condition
                        // "all" will show /"no" will show nothing
                        var condition_array=["","","update_status", "1","delete_status","1"];
                        // create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
                        // cauton: not posssible to use multiple grid in same page
                        create_set_grid_table_row(records_array,colums_array,condition_array,"bannerImg","bannerImg_Table", 0);
                        // show the showing no of records and paging for records
                        $('#bannerImg_Table_div').show();
                        // code for dynamic pagination
                        paging(total_pages, current_page_no, "bannerImg_Table" );
                    }
                    // if the table has no records / no matching records
                    else{
                        grid_has_no_result( "bannerImg_Table",5);
                    }
                    $("#search_bannerImg_button").toggleClass('active');
                }
            });
        }

        // load desire page on clik specific page no
        load_page = function load_page(page_no){
            if(page_no != 0){
                // every time current_page_no need to change if the bannerImg change page
                current_page_no=page_no;
                var search_txt = $("#search_bannerImg_field").val();
                load_data(search_txt)
            }
        }

        // function after click search button
        $('#search_bannerImg_button').click(function(){
            var search_txt = $("#search_bannerImg_field").val();
            // every time current_page_no need to set to "1" if the bannerImg search from search bar
            current_page_no=1;
            load_data(search_txt)
            // if there is lot of data and it tooks lot of time please add the below condition
        });

        //function after press "enter" to search
        $('#search_bannerImg_field').keypress(function(event){
            var search_txt = $("#search_bannerImg_field").val();
            if(event.keyCode == 13){
                // every time current_page_no need to set to "1" if the bannerImg search from search bar
                current_page_no=1;
                load_data(search_txt);
            }
        })

        // load data initially on page load with paging
        load_data("");
    });

    $(document).ready(function () {
        var url = project_url+"controller/webSiteSettingsController.php";
        // save and update for public post/notice
        $('#save_bannerImg_info').click(function(event){
            event.preventDefault();
            var formData = new FormData($('#bannerImg_form')[0]);
            formData.append("q","insert_or_update_banner");
            if($.trim($('#title').val()) == ""){
                success_or_error_msg('#form_submit_error','danger',"Please Insert Title","#title");
            }
            else if($.trim($('#bannerImg_image_upload').val()) == "" || $.trim($('#bannerImg_img').val()) == "images/no_image.png"){
                success_or_error_msg('#form_submit_error','danger',"Please Attach Image","#bannerImg_image_upload");
            }
            else{

                $.ajax({
                    url: url,
                    type:'POST',
                    data:formData,
                    async:false,
                    cache:false,
                    contentType:false,processData:false,
                    success: function(data){
                        $('#save_bannerImg_info').removeAttr('disabled','disabled');

                        if($.isNumeric(data)==true && data>0){
                            success_or_error_msg('#form_submit_error',"success","Save Successfully");
                            load_data("");
                            clear_form();
                        }
                        else{
                            if(data == "img_error")
                                success_or_error_msg('#form_submit_error',"danger",not_saved_msg_for_img_ln);
                            else
                                success_or_error_msg('#form_submit_error',"danger","Not Saved...");
                        }
                    }
                });
            }
        })

        // clear function to clear all the form value
        clear_form = function clear_form(){
            $('#bannerImg_id').val('');
            $("#bannerImg_form").trigger('reset');
            $('.imgDiv').show();
            $('#bannerImg_img').attr("src",project_url+"images/no_image.png");
            $('#bannerImg_img').attr("width", "100%","height","196px");

            $('#save_bannerImg_info').html('Save');
        }

        // on select clear button
        $('#clear_button').click(function(){
            clear_form();
        });


        delete_bannerImg = function delete_bannerImg(bannerImg_id){
            if (confirm("Do you want to delete the record? ") == true) {
                $.ajax({
                    url: url,
                    type:'POST',
                    async:false,
                    data: "q=delete_bannerImg&bannerImg_id="+bannerImg_id,
                    success: function(data){
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


        edit_bannerImg = function edit_bannerImg(bannerImg_id){
            $('.imgDiv').show();
            $.ajax({
                url: url,
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "get_bannerImg_details",
                    bannerImg_id: bannerImg_id
                },
                success: function(data){
                    if(!jQuery.isEmptyObject(data.records)){
                        $.each(data.records, function(i,data){
                            clear_form();
                            $('#bannerImg_id').val(data.id);
                            $('#title').val(data.title);
                            $('#text').val(data.text);

                            $('#bannerImg_img').attr("src",project_url+data.photo);
                            $('#bannerImg_img').attr("width", "100%","height","196px");

                            //change button value
                            $('#save_bannerImg_info').html('Update');

                            // to open submit post section
                            if($.trim($('#toggle_form i').attr("class"))=="fa fa-chevron-down")
                                $( "#toggle_form" ).trigger( "click" );

                        });

                    }
                }
            });
        }
    });

</script>