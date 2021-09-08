<?php
session_start();
include("../../includes/static_text.php");
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
$user_type = $_SESSION['user_type'];
if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$website_url."../view/login.php");
else if($dbClass->getUserGroupPermission(65) != 1 ){
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
            <h2>Items Grid</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div id="page_notification_div" class="text-center" style="display:none"></div>

            <!-- Advance Search Div-->
           <!-- <div class="x_panel">
                <div class="row">
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <a class="collapse-link-adv" id="toggle_form_ad"><b><small class="text-primary">Advance Search & Report</small></b><i class="fa fa-chevron-down"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="x_content adv_cl" id="iniial_collapse_adv">
                    <div class="row advance_search_div alert alert-warning">
                        <div class="row">
                            <label class="control-label col-md-2 col-sm-2 col-xs-6" style="text-align:right">Category</label>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <input class="form-control input-sm" type="text" name="ad_category_name" id="ad_category_name"/>
                                <input type="hidden" name="ad_category_id" id="ad_category_id"/>
                            </div>
                            <label class="control-label col-md-2 col-sm-2 col-xs-6" style="text-align:right">Item</label>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <input class="form-control input-sm" type="text" name="ad_item_name" id="ad_item_name"/>
                                <input type="hidden" name="ad_item_id" id="ad_item_id"/>
                            </div>
                        </div><br/>
                        <div class="row">
                            <label class="control-label col-md-2 col-sm-2 col-xs-6" style="text-align:right">Availability</label>
                            <div class="form-group col-md-3 col-sm-3 col-xs-6">
                                <input type="radio" class="flat_radio" name="is_active_status" id="is_active_status" value="1"/> Yes
                                <input type="radio" class="flat_radio" name="is_active_status" id="is_active_status" value="0" /> No
                                <input type="radio" class="flat_radio" name="is_active_status" id="is_active_status" value="2" checked="CHECKED"/> All
                            </div>
                            <label class="control-label col-md-2 col-sm-2 col-xs-6" style="text-align:right">Rate</label>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <input type="radio" class="flat_radio" name="is_rate" id="is_rate" value="1"/> Yes
                                <input type="radio" class="flat_radio" name="is_rate" id="is_rate" value="0" checked="CHECKED"/> No
                            </div>
                        </div><br/>
                        <div style="text-align:center">
                            <div class="col-md-6 col-sm-6 col-xs-12" style="text-align:right">
                                <button type="button" class="btn btn-info" id="adv_search_button"><i class="fa fa-lg fa-search"></i></button>
                                <button type="button" class="btn btn-warning" id="adv_search_print"><i class="fa fa-lg fa-print"></i></button>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div id="ad_form_submit_error" class="text-center" style="display:none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            -->
            <!-- Advance search end -->

            <div class="dataTables_length">
                <label>Show
                    <select size="1" style="width: 56px;padding: 6px;" id="item_Table_length" name="item_Table_length" aria-controls="item_Table">
                        <option value="50" selected="selected">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                    </select>
                    Post
                </label>
            </div>
            <div class="dataTables_filter" id="item_Table_filter">
                <div class="input-group">
                    <input class="form-control" id="search_item_field" style="" type="text">
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-primary has-spinner" id="search_item_button">
                     <span class="spinner"><i class="fa fa-spinner fa-spin fa-fw "></i></span>
                     <i class="fa  fa-search "></i>
                    </button>
                </span>
                </div>
            </div>
            <div style="height:250px; width:100%; overflow-y:scroll">
                <table id="item_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped  table-scroll ">
                    <thead >
                    <tr class="headings">
                        <th class="column-title" width="15%">Item Name</th>
                        <th class="column-title" width="15%">Category Name</th>
                        <th class="column-title" width="">Details</th>
                        <th class="column-title" width="10%">Rate</th>
                        <th class="column-title" width="10%">Availability</th>
                        <th class="column-title no-link last" width="10%"><span class="nobr"></span></th>
                    </tr>
                    </thead>
                    <tbody id="item_table_body" class="scrollable">

                    </tbody>
                </table>
            </div>
            <div id="item_Table_div">
                <div class="dataTables_info" id="item_Table_info">Showing <span id="from_to_limit"></span> of  <span id="total_record"></span> entries</div>
                <div class="dataTables_paginate paging_full_numbers" id="item_Table_paginate">
                </div>
            </div>
        </div>
    </div>
    <?php if($dbClass->getUserGroupPermission(62) == 1){ ?>
        <div class="x_panel item_entry_cl">
            <div class="x_title">
                <h2>Item Entry</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" id="iniial_collapse">
                    <div class="row">
                        <div class="col-md-12">

                            <!-- item details for insert and update-->
                            <form method="POST" id="item_form" name="item_form" enctype="multipart/form-data" class="form-horizontal form-label-left">

                            <div class="col-md-12 col-xs-12 col-sm-12" style="margin-bottom:20px; padding: 10px; padding-top: 20px; border-style: solid; border-width: 1px; border-color: #8a6d3b">

                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Item Name<span class="required">*</span></label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" id="item_name" name="item_name" class="form-control col-lg-12"/>
                                        <input type="hidden" id="item_id" name="item_id" class="form-control col-lg-12" value=""/>

                                    </div>

                                    <label class="control-label col-md-2 col-sm-2 col-xs-6">Category<span class="required">*</span></label>
                                    <div class="col-md-4 col-sm-4 col-xs-6">
                                        <select class="form-control" name="category_option" id="category_option">
											
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Details</label>
                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                        <textarea rows="2" cols="100" id="details" name="details" class="form-control col-lg-12"></textarea>
                                    </div>
                                </div>
                                <div id="file_div" class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Base Price<span class="required">*</span></label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" id="rate" name="rate" class="form-control col-lg-12"/>
                                    </div>
                                    <label class="control-label col-md-2 col-sm-2 col-xs-6" >Is Combo</label>
                                    <div class="col-md-4 col-sm-4 col-xs-6">
                                        <input type="checkbox" id="is_combo" name="is_combo" class="form-control col-lg-12"/>
                                    </div>
                                </div>

                                <div class="form-group" id="image_div"></div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-6" >Availability</label>
                                    <div class="col-md-4 col-sm-4 col-xs-6">
                                        <input type="checkbox" id="is_active" name="is_active" checked="checked" class="form-control col-lg-12"/>
                                    </div>
                                </div>
                                <div class="form-group">


                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Image</label>
                                    <div class="col-md-4 col-sm-4 col-xs-12" id="first_section">
                                        <div class="input-group">
                                            <input name="attached_file" class="form-control input-sm col-md-6 col-xs-12 attached_file" type="file"/>
                                            <!--<span class="input-group-btn">
									            <button type="button" class="btn btn-primary btn-sm" id="add_file_row"><span class="glyphicon glyphicon-plus"></span></button>
								            </span>-->
                                            <img src="" id="item_image" style="height: 70px; width: 70px; display: none">

                                        </div>
                                        <small style="color:red">Image size should be (1:1 ratio) and size under 3mb. </small><br>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div class="ln_solid"></div>
                                    <div class="col-md-7 col-sm-7 col-xs-12" style="text-align:right">
                                        <button type="submit" id="save_item" class="btn btn-success">Save</button>
                                        <button type="button" id="clear_button"  class="btn btn-primary">Clear</button>
                                    </div>
                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                        <div id="form_submit_error" class="text-center" style="display:none"></div>
                                    </div>
                                </div>

                            </div>

                            </form>

                            <!--display item Options-->
                            <div id="option_list" style="max-height:250px; width:100%; overflow-y:scroll; display: block; margin-bottom: 15px; border: solid 1px gray">
                                <table id="options_table" name="options_table" class="table table-bordered  responsive-utilities jambo_table table-striped  table-scroll ">
                                    <thead >
                                    <tr class="headings">
                                        <th class="column-title" width="" style="text-align: left;">Option Name</th>
                                        <th class="column-title" width="15%">Required</th>
                                        <th class="column-title" width="10%">Minimum Choice</th>
                                        <th class="column-title" width="10%">Maximum Choice</th>
                                        <th class="column-title no-link last" width="10%"><button id='addNewOption' type='button' class='btn btn-info btn-xs'><span class='glyphicon glyphicon-plus'></span></button></th>
                                    </tr>
                                    </thead>
                                    <tbody id="options_table_body" class="scrollable">

                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="option_entry" style="display:none; margin-bottom:20px; padding: 10px; padding-top: 20px; border-style: solid; border-width: 1px; border-color: #8a6d3b">
                                <label class="control-label col-md-12 col-sm-12 col-xs-12 x_title" style="text-align: left; margin-bottom: 10px">Create Option</label>
                                <hr>
                                <div class="table-responsive col-md-12 col-sm-12 col-xs-12" id="" >
                                    <form id="option_add_form">
                                        <div class="form-group">
                                            <input type="hidden" name="item_id_option" id="item_id_option">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-6" >Option Name</label>
                                            <div class="col-md-4 col-sm-4 col-xs-6" style="margin-bottom: 8px">
                                                <input class="form-control" type="text" name="option_name" id="option_name"value="" >
                                                <input class="form-control" type="hidden" name="option_id" id="option_id"value="0" >

                                            </div>
                                            <label class="control-label col-md-2 col-sm-2 col-xs-6" >Minimum Choice</label>
                                            <div class="col-md-4 col-sm-4 col-xs-6" style="margin-bottom: 8px">
                                                <input class="form-control" type="number" name="minimum" id="minimum" value="0" >
                                            </div>
                                            <label class="control-label col-md-2 col-sm-2 col-xs-6" >Maximum Choice</label>
                                            <div class="col-md-4 col-sm-4 col-xs-6" style="margin-bottom: 8px">
                                                <input class="form-control" type="number" name="maximum" id="maximum" value="0" >
                                            </div>
                                            <label class="control-label col-md-2 col-sm-2 col-xs-6" >Is Required</label>
                                            <div class="col-md-4 col-sm-4 col-xs-6" style="margin-bottom: 8px">
                                                <input type="checkbox" id="is_required" name="is_required" checked="checked" class="form-control" style="padding-top: 4px"/>
                                            </div>

                                        </div>


                                    <table class="table table-bordered" id="ingredient_input" >
                                        <thead>
                                        <tr>
                                            <th class="text-center">Ingredient Name</th>
                                            <th class="text-center" >Price</th>
                                            <th width="10%"><button onclick="option_ingredient_entry()" type='button' class='btn btn-info btn-xs'><span class='glyphicon glyphicon-plus'></span></button></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                    <button type="button" id="add_option" class="btn btn-success" style="margin-top: 15px; alignment: center">Add Option</button>
                                    </form>

                                </div>
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" style="margin-left: 0px">
                            </div>

                        </div>
                    </div>


            </div>
        </div>
        <?php
    }
}
?>
<script src="js/customTable.js"></script>
<script>

    console.log(project_url)
    //alert(project_url)
    //------------------------------------- general & UI  --------------------------------------

    function  deleteoption(id){
        $.ajax({
            url: project_url+"controller/itemController.php",
            dataType: "json",
            type: "post",
            async:false,
            data:{
                q: "delete_options",
                option_id: id,
                item_id: $('#item_id').val()
            },
            success: function(data){
                if(data.option){
                   // alert('option')
                    html=''

                    $.each(data.option, function(i,data){
                        html+= '<tr><td class="text-capitalize" style="text-align: left">'+data['name']+'</td><td style="text-align: center">'+data['is_required']+'</td><td style="text-align: right">'+data['minimum_choice']+'</td><td style="text-align: right">'+data['maximum_choice']+'</td><td><button class="btn btn-primary btn-xs" onclick="editoption('+parseInt(data['id'])+')" type="button"><i class="fa fa-pencil"></i></button><button class="btn btn-danger btn-xs" onclick="deleteoption('+parseInt(data['id'])+')" type="button"><i class="fa fa-trash"></i></button></td></tr>'
                    });
                    $('#options_table > tbody').html(html)
                }
            }
        });
    }

    //load all the ingredients for a single option for edit
    function  editoption(option_id){

        $.ajax({
            url: project_url+"controller/itemController.php",
            dataType: "json",
            type: "post",
            async:false,
            data:{
                q: "load_options_items",
                option_id: option_id
            },
            success: function(data){
                console.log(data);
                //alert(data)
                html=''
                $('#option_name').val(data.records.name)
                $('#option_id').val(data.records.id)
                $('#item_id_option').val(data.records.item_id);
                $('#minimum').val(data.records.minimum_choice)
                $('#maximum').val(data.records.maximum_choice)

                if(data.records.is_required==1){
                    $('#is_required').iCheck('check');
                }else{
                    $('#is_required').iCheck('uncheck');
                }


                $.each(data.ingredients, function(i,datas){

                    html +="<tr><td class='text-capitalize'><input type='text' name='ingredient_name[]' value='"+datas['name']+"' required class='form-control col-lg-12 size ad_ingredient_name'/><input type='hidden' name='ingredient_id[]' value='"+datas['id']+"'></td>" +
                        "<td><input type='text' name='ingredient_price[]' value='"+datas['price']+"' required class='form-control col-lg-12 text-right rate' disabled/></td>" +
                        "<td style='text-align: center'><span class='input-group-btn'><button type='button' class='btn btn-danger btn-xs remove_row'><span class='glyphicon glyphicon-minus'></span></button></span></td></tr>";

                });

                //alert(html)
                $('#ingredient_input > tbody').html(html)
                $('#option_entry').css('display', 'block')

                $('#option_entry').css('display','block')

                $('.remove_row').on('click',function () {
                    $(this).closest('tr').remove()
                })



            }
        });

        $(".ad_ingredient_name").autocomplete({
            search: function() {
            },
            source: function(request, response) {
                //alert('ok')
                $.ajax({
                    url: project_url+'controller/itemController.php',
                    dataType: "json",
                    type: "post",
                    async:false,
                    data: {
                        q: "get_ingredient",
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                var id = ui.item.id;
                $(this).next().val(id);
                $(this).closest('td').find("input").val(ui.item.price)
                $(this).parent().next('td').find('input').val(ui.item.price)

                //$(this).parent().closest('.rate').val(ui.item.price)
            }
        });

    }

    option_ingredient_entry = function option_ingredient_entry(){
        html ="<tr><td class='text-capitalize'><input type='text' name='ingredient_name[]' value='' required class='form-control col-lg-12 ad_ingredient_name'/><input type='hidden' name='ingredient_id[]' value=''></td>" +
            "<td><input type='text' name='ingredient_price[]' value='' required class='form-control col-lg-12 text-right rate' disabled/></td>" +
            "<td style='text-align: center'><span class='input-group-btn'><button type='button' class='btn btn-danger btn-xs remove_row'><span class='glyphicon glyphicon-minus'></span></button></span></td></tr>";
        //alert(html)
        $('#ingredient_input > tbody').append(html)
        //$('#option_entry').css('display', 'block')

        $('.remove_row').on('click',function () {
            $(this).closest('tr').remove()
        })


        $(".ad_ingredient_name").autocomplete({
            search: function() {
            },
            source: function(request, response) {
                //alert('ok')
                $.ajax({
                    url: project_url+'controller/itemController.php',
                    dataType: "json",
                    type: "post",
                    async:false,
                    data: {
                        q: "get_ingredient",
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {

                var id = ui.item.id;

                $(this).next().val(id);
                $(this).parent().next('td').find('input').val(ui.item.price)

            }
        });

    }

    $('#addNewOption').click(function () {
        $('#option_id').val('')
        $('#option_add_form')[0].reset();
        $('#ingredient_input > tbody').html('');
        $('#item_id_option').val($('#item_id').val())
        $('#option_entry').css('display','block');
        option_ingredient_entry()
    });


    $(document).ready(function () {
        // close form submit section onload page


        $('#item_form').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });

        $('.flat_radio').iCheck({
            //checkboxClass: 'icheckbox_flat-green'
            radioClass: 'iradio_flat-green'
        });

        $("#ad_category_name").autocomplete({
            search: function() {
            },
            source: function(request, response) {
                $.ajax({
                    url: project_url+'controller/orderController.php',
                    dataType: "json",
                    type: "post",
                    async:false,
                    data: {
                        q: "category_info",
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

        $("#ad_item_name").autocomplete({
            search: function() {
                category_id  = $('#ad_category_id').val();
                if(category_id == ""){
                    success_or_error_msg('#ad_form_submit_error','danger','Please Select Category',"#ad_category_name");
                }
            },
            source: function(request, response) {
                $.ajax({
                    url: project_url+'controller/orderController.php',
                    dataType: "json",
                    type: "post",
                    async:false,
                    data: {
                        q: "product_info",
                        term: request.term,
                        category_id: category_id
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

        load_category = function load_category(){
            $.ajax({
                url: project_url+"controller/itemController.php",
                dataType: "json",
                type: "post",
                async:false,
                data:{
                    q: "view_category",
                },
                success: function(data){
                    var option_html = "";
                    $('#category_option').after().html("");
                    option_html += '<option value="0">Select Option ..</option>';
                    if(!jQuery.isEmptyObject(data.records)){
                        $.each(data.records, function(i,data){
                            option_html += '<option value="'+data.id+'">'+data.category_name+'</option>';
                        });
                    }
                    $('#category_option').after().html(option_html);
                }
            });
        }

        load_ingredient = function load_ingredient(){
            $.ajax({
                url: project_url+"controller/itemController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "get_ingredient"
                },
                success: function(data) {
                    if(!jQuery.isEmptyObject(data.records)){
                        var html = '<table class="table table-bordered jambo_table"><thead><tr class="headings"><th class="column-title text-center" class="col-md-8 col-sm-8 col-xs-8">Ingredients</th><th class="col-md-2 col-sm-2 col-xs-12"><input type="checkbox" id="check-all" class="tableflat">Select All</th></tr></thead>';
                        $.each(data.records, function(i,datas){
                            html += '<tr><td colspan="2">';
                            $.each(datas.module_group, function(i,module_group){
                                module_group_arr = module_group.split("*");
                                html += '<div class="col-md-3" ><input type="checkbox" name="ingredient[]"  class="tableflat"  value="'+module_group_arr[0]+'"/> '+module_group_arr[1]+'</div>';
                            });
                            html += '</td></tr>';

                        });
                        html +='</table>';
                    }
                    $('#ingredient_select').html(html);
                    $('#item_form').iCheck({
                        checkboxClass: 'icheckbox_flat-green',
                        radioClass: 'iradio_flat-green'
                    });

                    $('#item_form input#check-all').on('ifChecked', function () {
                        //alert('check');
                        $("#item_form .tableflat").iCheck('check');
                    });

                    $('#item_form input#check-all').on('ifUnchecked', function () {
                        //alert('ucheck');
                        $("#item_form .tableflat").iCheck('uncheck');
                    });
                }
            });
        }

        load_category();
        load_ingredient();

    });

    $(document).ready(function () {

        var current_page_no=1;
        $('.adv_cl').hide();

        load_item = function load_item(search_txt){

            $("#search_item_button").toggleClass('active');
            var item_Table_length = parseInt($('#item_Table_length').val());

            var ad_category_name = $("#ad_category_name").val();
            var ad_category_id = $("#ad_category_id").val();
            var ad_item_name = $("#ad_item_name").val();
            var ad_item_id = $("#ad_item_id").val();
            var is_active_status = $("input[name=is_active_status]:checked").val();
            var is_rate = $("input[name=is_rate]:checked").val();

            $.ajax({
                url: project_url+"controller/itemController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "grid_data",
                    ad_category_name: ad_category_name,
                    ad_category_id: ad_category_id,
                    ad_item_name:ad_item_name,
                    ad_item_id:ad_item_id,
                    is_active_status:is_active_status,
                    is_rate:is_rate,
                    search_txt: search_txt,
                    limit:item_Table_length,
                    page_no:current_page_no
                },
                success: function(data) {
                    //alert(data)
                    var todate = "<?php echo date("Y-m-d"); ?>";
                    var user_name =  "<?php echo $user_name; ?>";
                    var html = "";
                    if($.trim(search_txt) == "Print"){
                        var serach_areas= "";
                        if(is_active_status == 1)  	serach_areas += "Available <br>";
                        if(is_active_status == 0)  	serach_areas += "Not-Available <br>";
                        if(ad_category_id != '')  	serach_areas += "Category Name: "+ad_category_name+" <br>";
                        if(ad_item_id != '')  	serach_areas += "Item Name: "+ad_item_name+" <br>";
                        /*<input name="print" type="button" value="Print" id="printBTN" onClick="printpage();" />*/

                        html +='<div width="100%"  style="text-align:center"><img src="'+employee_import_url+'/images/logo.png" width="80"/></div><h2 style="text-align:center">Cakencookie</h2><h4 style="text-align:center">Item Information Report</h4><table width="100%"><tr><th width="60%" style="text-align:left"><small>'+serach_areas+'</small></th><th width="40%"  style="text-align:right"><small>Printed By: '+user_name+', Date:'+todate+'</small></th></tr></table>';

                        if(!jQuery.isEmptyObject(data.records)){
                            if(is_rate == 1){
                                html +='<table width="100%" border="1px" style="margin-top:10px;border-collapse:collapse"><thead><tr><th style="text-align:center">Item</th><th style="text-align:center">Category</th><th style="text-align:center">Details</th><th style="text-align:center">Rate</th></tr></thead><tbody>';
                            }
                            else{
                                html +='<table width="100%" border="1px" style="margin-top:10px;border-collapse:collapse"><thead><tr><th style="text-align:center">Item</th><th style="text-align:center">Category</th><th style="text-align:center">Details</th></tr></thead><tbody>';
                            }
                            $.each(data.records, function(i,data){
                                html += "<tr>";
                                html +="<td style='text-align:left'>"+data.name+"</td>";
                                html +="<td style='text-align:left'>"+data.category_head_name+"</td>";
                                html +="<td style='text-align:left'>"+data.details+"</td>";
                                if(is_rate == 1){
                                    var s_rate = data.p_rate;
                                    var p_rate = s_rate.replace(",", "</br>");
                                    html +="<td style='text-align:center'>"+p_rate+"</td>";
                                }
                                html += '</tr>';
                            });
                            html +="</tbody></table>"
                        }
                        else{
                            html += "<table width='100%' border='1px' style='margin-top:10px;border-collapse:collapse'><tr><td><h4 style='text-align:center'>There is no data.</h4></td></tr></table>";
                        }
                        WinId = window.open("", "Item Report","width=950,height=800,left=50,toolbar=no,menubar=YES,status=YES,resizable=YES,location=no,directories=no, scrollbars=YES");
                        WinId.document.open();
                        WinId.document.write(html);
                        WinId.document.close();
                    }
                    else{
                        if(data.entry_status==0){
                            $('.item_entry_cl').hide();
                        }
                        //for  showing grid's no of records from total no of records
                        show_record_no(current_page_no, item_Table_length, data.total_records )

                        var total_pages = data.total_pages;
                        var records_array = data.records;
                        $('#item_Table tbody tr').remove();
                        $("#search_item_button").toggleClass('active');
                        if(!jQuery.isEmptyObject(records_array)){
                            //create and set grid table row
                            var colums_array=["item_id*identifier*hidden","name","category_head_name","details","i_rate","active_status"];
                            //first element is for view , edit condition, delete condition
                            //"all" will show /"no" will show nothing
                            var condition_array=["","","update_status", "1","delete_status","1"];
                            //create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
                            //cauton: not posssible to use multiple grid in same page
                            create_set_grid_table_row(records_array,colums_array,condition_array,"item","item_Table", 0);
                            //show the showing no of records and paging for records
                            $('#item_Table_div').show();
                            //code for dynamic pagination
                            paging(total_pages, current_page_no, "item_Table" );
                        }
                        //if the table has no records / no matching records
                        else{
                            grid_has_no_result("item_Table",6);
                        }
                    }
                }
            });
        }

        // load desire page on clik specific page no
        load_page = function load_page(page_no){
            if(page_no != 0){
                // every time current_page_no need to change if the user change page
                current_page_no=page_no;
                var search_txt = $("#search_item_field").val();
                load_item(search_txt)
            }
        }

        // function after click search button
        $('#search_item_button').click(function(){
            var search_txt = $("#search_item_field").val();
            // every time current_page_no need to set to "1" if the user search from search bar
            current_page_no=1;
            load_item(search_txt);
        });

        //function after press "enter" to search
        $('#search_item_field').keypress(function(event){
            var search_txt = $("#search_item_field").val();
            if(event.keyCode == 13){
                // every time current_page_no need to set to "1" if the user search from search bar
                current_page_no=1;
                load_item(search_txt)
            }
        });

        // load data initially on page load with paging
        load_item("");

        //advance search
        $('#adv_search_button').click(function(){
            load_item("Advance_search");
        });

        //print advance search data
        $('#adv_search_print').click(function(){
            load_item("Print");
        });


    });

    $(document).ready(function () {

        $('#save_item').click(function(event){
            event.preventDefault();
            var formData = new FormData($('#item_form')[0]);

            formData.append("q","insert_or_update_item");
            if($.trim($('#item_name').val()) == ""){
                success_or_error_msg('#form_submit_error','danger','Please Insert Item Name',"#item_name");
            }
            else if($.trim($('#category_option').val()) == "0"){
                success_or_error_msg('#form_submit_error','danger',"Please Select Category","#category_option");
            }
            else if($.trim($('#rate').val()) == ""){
                success_or_error_msg('#form_submit_error','danger',"Please select Rate",".rate");
            }
            else{
                $.ajax({
                    url: project_url+"controller/itemController.php",
                    type:'POST',
                    data:formData,
                    async:false,
                    cache:false,
                    contentType:false,
                    processData:false,
                    success: function(data){
                        $('#item_id').val(data);
                        $('#addNewOption').trigger('click')

                        //console.log(data)

                        $('#save_item').removeAttr('disabled','disabled');

                        if($.isNumeric(data)==true && data == 3){
                            alert("Item Already Ordered. Only update those category which Item is not Ordered.")
                        }
                        else if($.isNumeric(data)==true && data>0){
                            success_or_error_msg('#form_submit_error',"success","Save Successfully");
                            load_item("");
                            //clear_form();
                        }
                    }
                });

            }
        })

        //edit item
        edit_item = function edit_item(item_id){
            $('#option_add_form')[0].reset();
            $('#options_table > tbody').html('')
            $('#ingredient_input > tbody').html('')
            $('#option_entry').css('display','none')

            $('#item_id').val(item_id);
            $('.img_label').after('');
            $('#optionTable > tbody').html('');
            $('#ingredient_select').html('');

            $.ajax({
                url: project_url+"controller/itemController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "get_item_details",
                    item_id: item_id
                },
                success: function(data){

                    if(!jQuery.isEmptyObject(data.item)){
                        $('#item_name').val(data.item.name);
                        $('#item_id_option').val(item_id);
                        $('#rate').val(data.item.price);
                        $('#details').val(data.item.details);
                        $('#category_option').val(data.item.category_id);
                        $('#tag').val(data.item.tags);
                        $("#item_image").attr("src","");
                        $("#item_image").css("display","none");


                        if(data.item.availability==1){
                            $('#is_active').iCheck('check');
                        }else{
                            $('#is_active').iCheck('uncheck');
                        }

                        if(data.item.is_combo==1){
                            $('#is_combo').iCheck('check');
                        }else{
                            $('#is_combo').iCheck('uncheck');
                        }

                        if(data.item.feature_image!=""){
                            $('#is_feature').iCheck('check');
                        }else{
                            $('#is_feature').iCheck('uncheck');
                        }
                    }

                    if(data.option){
                        html=''

                        $.each(data.option, function(i,data){
                            html+= '<tr><td class="text-capitalize" style="text-align: left">'+data['name']+'</td><td style="text-align: center">'+data['is_required']+'</td><td style="text-align: right">'+data['minimum_choice']+'</td><td style="text-align: right">'+data['maximum_choice']+'</td><td><button class="btn btn-primary btn-xs" onclick="editoption('+parseInt(data['id'])+')" type="button"><i class="fa fa-pencil"></i></button><button class="btn btn-danger btn-xs" onclick="deleteoption('+parseInt(data['id'])+')" type="button"><i class="fa fa-trash"></i></button></td></tr>'
                        });
                        $('#options_table > tbody').html(html)
                    }

                    $('#save_item').html('Update');
                    // to open submit post section
                    if($.trim($('#toggle_form i').attr("class"))=="fa fa-chevron-down")
                        $( "#toggle_form" ).trigger( "click" );
                }
            });
        }

        delete_gallary_image = function delete_gallary_image(img_id, item_id){
            var url = project_url+"controller/itemController.php";
            $.ajax({
                url: url,
                type:'POST',
                async:false,
                data: "q=delete_attached_file&img_id="+img_id,
                success: function(data){
                    if($.isNumeric(data)==true && data>0){
                        success_or_error_msg('#form_submit_error',"success","Deleted Successfully");
                        $('#item_image_'+img_id).remove();
                        //clear_form();
                    }
                    else{
                        success_or_error_msg('#form_submit_error',"danger","Not Deleted...");
                    }
                }
            });
        }

        delete_item = function delete_item(item_id){
            if (confirm("Do you want to delete the record? ") == true) {
                $.ajax({
                    url: project_url+"controller/itemController.php",
                    type:'POST',
                    async:false,
                    data: "q=delete_item&item_id="+item_id,
                    success: function(data){
                        if($.trim(data) == 1){
                            success_or_error_msg('#page_notification_div',"success","Deleted Successfully");
                            load_item("");
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
            $('#item_id').val('');
            $("#item_form").trigger('reset');
            $("input[name='attached_file[]']:gt(0)").parent().remove();
            $('#image_div').html('');
            load_category();
            load_ingredient();

            $('#optionTable > tbody').html('');

            $( "#addNewOption" ).trigger( "click" );

            $('#item_form').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });

            $("#item_form .tableflat").iCheck('uncheck');
            $('#save_item').html('Save');
        }

        $('#clear_button').click(function(){
            clear_form();
        });

        <!-- delivery option on change -->
        $("#category_option").change(function(){
            var category_id = $(this).val();
            if($(this).val() != 0){
                var category_name = $("#category_option option:selected").text();
                var category_arr = category_name.split(" >> ");
                var category_code = category_arr[0];

                $.ajax({
                    url: project_url+"controller/itemController.php",
                    dataType: "json",
                    type: "post",
                    async:false,
                    data:{
                        q: "category_wise_item_code",
                        category_id: category_id,
                        category_code: category_code
                    },
                    success: function(data){
                        $('#item_code').val(data);
                    }
                });
            }else{
                $('#item_code').val('');
            }
        });

        $('#add_option').click(function(){
            event.preventDefault();
            var formData = new FormData($('#option_add_form')[0]);
            formData.append("q","insert_or_update_option");
            if($.trim($('#option_name').val()) == ""){
                success_or_error_msg('#form_submit_error','danger','Please Insert Option Name',"#option_name");
            }
            else{
                $.ajax({
                    url: project_url+"controller/itemController.php",
                    type:'POST',
                    data:formData,
                    async:false,
                    cache:false,
                    contentType:false,
                    processData:false,
                    success: function(data){

                        $('#save_item').removeAttr('disabled','disabled');

                        if($.isNumeric(data)==true && data>0){
                            success_or_error_msg('#form_submit_error',"success","Save Successfully");

                            edit_item($('#item_id').val())
                            $('#ingredient_input > tbody').html('')
                            $('#addNewOption').trigger( "click" )
                            $('#option_entry').css('display','none');
                        }
                    }
                });

            }
        });


    });


</script>