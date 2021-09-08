<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
$user_type = $_SESSION['user_type'];
$logo   = $dbClass->getCompanyLogo();

//echo $logo;die;

if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$website_url."../view/login.php");
else if($dbClass->getUserGroupPermission(82) != 1){
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
            <h2>Category Report</h2>
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
            <div class="x_panel">
                <div class="row advance_search_div alert alert-warning">
                    <div class="row">
                        <label class="control-label col-md-4 col-sm-4 col-xs-4" style="text-align:right">Active</label>
                        <div class="col-md-3 col-sm-3 col-xs-8">
                            <input type="radio" class="flat_radio" name="is_active_status" id="is_active_status" value="1"/> Yes
                            <input type="radio" class="flat_radio" name="is_active_status" id="is_active_status" value="0" /> No
                            <input type="radio" class="flat_radio" name="is_active_status" id="is_active_status" value="2" checked="CHECKED"/> All
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-8" style="text-align:center">
                            <button type="button" class="btn btn-warning" id="adv_search_print"><i class="fa fa-lg fa-print"></i> Report</button>
                        </div>
                    </div>
                    <div style="text-align:center">
                        <div id="ad_form_submit_error" class="text-center" style="display:none"></div>
                    </div>
                </div>
            </div>
            <!-- Adnach search end -->

        </div>
    </div>

    <?php

}
?>
<script src="js/customTable.js"></script>

<script>
    $(document).ready(function () {

        // close form submit section onload page
        var x_panel = $('#iniial_collapse').closest('div.x_panel');
        var button = $('#iniial_collapse').find('i');
        var content = x_panel.find('div.x_content');
        content.slideToggle(200);
        (x_panel.hasClass('fixed_height_390') ? x_panel.toggleClass('').toggleClass('fixed_height_390') : '');
        (x_panel.hasClass('fixed_height_320') ? x_panel.toggleClass('').toggleClass('fixed_height_320') : '');
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        setTimeout(function () {
            x_panel.resize();
        }, 50);

        // collaps button function
        $('.collapse-link').click(function () {
            var x_panel = $(this).closest('div.x_panel');
            var button = $(this).find('i');
            var content = x_panel.find('div.x_content');
            content.slideToggle(200);
            (x_panel.hasClass('fixed_height_390') ? x_panel.toggleClass('').toggleClass('fixed_height_390') : '');
            (x_panel.hasClass('fixed_height_320') ? x_panel.toggleClass('').toggleClass('fixed_height_320') : '');
            button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
            setTimeout(function () {
                x_panel.resize();
            }, 50);
        })

        $('.flat_radio').iCheck({
            //checkboxClass: 'icheckbox_flat-green'
            radioClass: 'iradio_flat-green'
        });

    });

    $(document).ready(function () {

        load_category_grid = function load_category_grid(search_txt){

            var is_active_status = $("input[name=is_active_status]:checked").val();

            $.ajax({
                url: project_url+"controller/reportController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "categoryReport",
                    is_active_status: is_active_status
                },
                success: function(data) {
                    var todate = "<?php echo date("Y-m-d"); ?>";
                    var user_name =  "<?php echo $user_name; ?>";
                    var html = "";
                    if($.trim(search_txt) == "Print"){
                        var serach_areas= "";

                        if(is_active_status == 1)  serach_areas += "Available <br>";
                        if(is_active_status == 0)  serach_areas += "Not-Available <br>";


                        html +='<button class="no-print" onclick="window.print()">Print</button><div width="100%"  style="text-align:center"><img src="'+project_url+'<?php echo $logo; ?>" width="80"/></div><h2 style="text-align:center">Burrito Brothers</h2><h4 style="text-align:center">Category Report</h4><table width="100%"><tr><th width="60%" style="text-align:left"><small>'+serach_areas+'</small></th><th width="40%"  style="text-align:right"><small>Printed By: '+user_name+', Date:'+todate+'</small></th></tr></table>';

                        if(!jQuery.isEmptyObject(data.records)){

                            html +='<table width="100%" cellpadding="10" border="1px" style="margin-top:10px;border-collapse:collapse"><thead><tr><th style="text-align:left">Id</th><th style="text-align:left">Name</th><th style="text-align:right">Item Number</th></tr></thead><tbody>';
                            $.each(data.records, function(i,data){
                                //alert(data)
                                html += "<tr>";
                                html +="<td style='text-align:left'>"+data.id+"</td>";
                                html +="<td style='text-align:left; text-transform:capitalize'>"+data.name+"</td>";
                                html +="<td style='text-align:right'>"+data.item+"</td>";
                                html += '</tr>';
                            });
                            html +="</tbody></table>"
                        }
                        else{
                            html += "<table width='100%' border='1px' style='margin-top:10px;border-collapse:collapse'><tr><td><h4 style='text-align:center'>There is no data.</h4></td></tr></table>";
                        }
                        WinId = window.open("", "Category Report","width=850,height=800,left=50,toolbar=no,menubar=YES,status=YES,resizable=YES,location=no,directories=no, scrollbars=YES");
                        WinId.document.open();
                        WinId.document.write(html);
                        WinId.document.close();
                    }

                }
            });

        }

        //print advance search data
        $('#adv_search_print').click(function(){
            load_category_grid("Print");
        });

    });




</script>