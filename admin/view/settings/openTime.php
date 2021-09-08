<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
$user_type = $_SESSION['user_type'];

if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$website_url."../view/login.php");
else if($dbClass->getUserGroupPermission(98) != 1){
    ?>
    <div class="x_panel">
        <div class="alert alert-danger" align="center">You Don't Have permission of this Page.</div>
    </div>
    <?php
}
else{
    $user_name = $_SESSION['user_name'];
    ?>
    <?php if($dbClass->getUserGroupPermission(98) == 1){ ?>
        <div class="x_panel employee_entry_cl">

            <div class="x_content" >
                <form id="setting_form" name="setting_form" enctype="multipart/form-data" class="form-horizontal form-label-left" style="width: 100%; margin: auto">

                    <div class="form-group col-md-12" id="serving_day">

                    </div>

                    <div class="col-md-8 col-sm-8 col-xs-12" style="text-align: center">
                        <button type="submit" id="save_general_settings" class="btn btn-success">Update Settings</button>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="form_submit_error" class="text-center" style="display:none"></div>
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
    $('#point').text('point = 1$');
    $('#point_currency').text('$= 1 point');


    $(document).ready(function () {

        var url = project_url+"controller/calenderController.php";


        load_data = function load_data() {
            $.ajax({
                url: url,
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "get_serving_days_data",
                },
                success: function(data){

                    if(!jQuery.isEmptyObject(data.records)){
                        var html = '<div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px; size: 18px">\n' +
                            '                            <label class="control-label col-md-2 col-sm-2 col-xs-4">Day</label>\n' +
                            '                            <label class="control-label col-md-3 col-sm-3 col-xs-4" style="text-align: left">Opening Time</label>\n' +
                            '                            <label class="control-label col-md-3 col-sm-3 col-xs-4" style="text-align: left">Close Time</label>\n' +
                            '                        </div>';
                        $.each(data.records, function(i,data){
                            html +='<div class="col-md-12 col-sm-12 col-xs-12">\n' +
                                '         <label class="control-label col-md-2 col-sm-2 col-xs-4">'+data.day+'<span class="required">*</span></label>\n' +
                                '           <div class="col-md-3 col-sm-3 col-xs-4" style="margin-bottom: 10px">' +
                                '               <input type="hidden" name="id[]" value="'+data.id+'">\n' +
                                '               <input type="time" id="open[]" name="open[]" value="'+data.open+'" required class="form-control col-lg-12"/>\n' +
                                '            </div>\n' +
                                '            <div class="col-md-3 col-sm-3 col-xs-4" style="margin-bottom: 10px">\n' +
                                '                <input type="time" id="close[]" name="close[]" value="'+data.close+'" required class="form-control col-lg-12"/>\n' +
                                '            </div>\n' +
                                '     </div>'
                        });
                        $('#serving_day').html(html)
                    }
                }
            });
        }
        load_data();

        $('#tax_enable').on('change', function() {
            if(this.value==1){
                $('#tax_set').css({display: "block"});
            }
            else {
                $('#tax_set').css({display: "none"});
            }
        });

        $('#save_general_settings').click(function(event){
            event.preventDefault();
            var formData = new FormData($('#setting_form')[0]);
            formData.append("q","update_serving_day");
            $.ajax({
                url: url,
                type:'POST',
                data:formData,
                async:false,
                cache:false,
                contentType:false,processData:false,
                success: function(data){
                    load_data();
                }
            });

        });

    });
</script>


