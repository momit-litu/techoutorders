<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
$user_type = $_SESSION['user_type'];
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
            <h2>All Notifications</h2>
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
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200" selected="selected">200</option>
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
                    <th class="column-title" width="10%">Notification Id</th>
                    <th class="column-title" width="10%">Order No</th>
                    <th class="column-title" width="">Details</th>
                    <th class="column-title" width="12%">Date</th>
                    <th class="column-title" width="12%">status</th>
                    <th class="column-title no-link last" width="10%"><span class="nobr"></span></th>
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

    <?php
}
?>
<script src="js/customTable.js"></script>
<script src="js/autosuggest.js"></script>
<script>


    $(document).ready(function () {

        //status update action from edit modal
        edit_order = function edit_order(status_id){
            //alert(status_id)
            show_notification_details(status_id,0)
            load_order()
        }

        var current_page_no=1;
        load_order = function load_order(search_txt){
            var order_Table_length = parseInt($('#order_Table_length').val());
            $.ajax({
                url: project_url+"controller/notificationController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "load_notifications",
                    limit:30,
                    page_no:notification_current_page_no,
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

                    //for  showing grid's no of records from total no of records
                    show_record_no(current_page_no, order_Table_length, data.total_records )

                    var total_pages = data.total_pages;
                    var records_array = data.records;
                    $('#order_Table tbody tr').remove();
                    $("#search_order_button").toggleClass('active');

                    //console.log(data)

                    if(!jQuery.isEmptyObject(data.records)){
                       // alert('ok')

                        //cnsole.log(total_order_amt)
                        //create and set grid table row  nt.id, nt.order_id, nt.status, nt.details, date_time
                        var colums_array=["id*identifier*hidden","id","order_id","details","date_time","status_text"];
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
            });
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



    });


</script>