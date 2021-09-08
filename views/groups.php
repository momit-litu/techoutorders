<?php
session_start();
include("../includes/dbConnect.php");
include("../includes/dbClass.php");
$dbClass = new dbClass;
$is_logged_in_customer = "";
$website_url  = $dbClass->getDescription('website_url');
$logo         =$website_url."admin/".$dbClass->getDescription('company_logo');


if(!isset($_SESSION['customer_id']) && $_SESSION['customer_id']!=""){ ob_start(); header("Location:index.php"); exit();}
else $is_logged_in_customer = 1;
$customer_id = $_SESSION['customer_id'];
?>



    <section class="home-icon shop-cart bg-skeen" style="padding: 0px; margin: 0px">
        <div class="container  col-md-5 col-sm-12 col-xs-12"  style="max-width:100%" >
            <h5 style="text-align: center">Your Groups </h5>
            <hr>
            <div id="groupInfo">

            </div>
        </div>
        <div class="x_panel category_entry_cl  col-md-7 col-sm-12 col-xs-12" style="padding-right: 0px; padding-left: 0px">
                <div class="x_title">
                    <h5 style="text-align: center">Create a New Group</h5>
                </div>
                <hr>
            <div style="background-color: white; padding-top: 20px; padding-bottom: 30px; border-radius: 15px">
                <form method="POST"  id="group_form" name="group_form"  enctype="multipart/form-data" class="form-horizontal form-label-left" style="margin: 0px; padding: 0px">
                    <div class="col-md-12" style="margin: 1px; padding: 0px">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12" style="padding: 0px; margin: 0px">
                            <label class="control-label col-md-3 col-sm-3 col-xs-4">Name<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-8">
                                <input type="text" id="group_name" name="group_name" class="form-control col-lg-12" style="border-radius: 10px; height: 40px; margin: 5px"/>
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12" style="padding: 0px; margin: 1px">
                            <label class="control-label col-md-3 col-sm-3 col-xs-4">Members</label>
                            <div class="col-md-9 col-sm-9 col-xs-8">
                                <input type="number" id="members" name="members" style="text-align: center; border-radius: 10px; height: 40px; margin: 5px" value="0" min="2"  class="form-control col-lg-12"/>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12" id="member_entry" ></div>
                    </div>
                    <div class="form-group">
                        <div class="ln_solid"></div>
                        <label class="control-label col-md-4 col-sm-4 col-xs-12"></label>
                        <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center">
                            <input type="hidden" id="group_id" name="group_id" />
                            <button  type="submit" id="save_group" class="btn btn-success">Save</button>
                            <button type="button" id="clear_button"  class="btn btn-primary">Clear</button>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="form_submit_error" class="text-center" style="display:none"></div>
                        </div>
                    </div>
                </form>

            </div>
            </div>

    </section>
    <div class="modal booktable" id="group_modal" tabindex="-2" role="dialog" aria-labelledby="booktable">
    <div class="modal-dialog" role="document" style="max-width: 600px;width:95% !important">
        <div class="modal-content">
            <div class="modal-body">
                <div id="order-div">
                    <div class="title text-center">
                        <h4 class="text-coffee left" id="modal_group_name">sdfhgjkh;</h4>
                        <input type="hidden" id="modal_group_id">
                    </div>
                    <div class="done_registration ">
                        <div class="doc_content">
                            <div id="ord_detail_vw">
                                <table class="table table-bordered" >
                                    <thead>
                                    <tr>
                                        <th align="center">Name</th>
                                        <th align="center">Email</th>
                                    </tr>
                                    </thead>
                                    <tbody id="members_info">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 center" style="text-align: center"> <button type="button" class="btn btn-warning" id="order_now" >Initiate a Group Order</button></div>
            </div>
        </div>
    </div>
    </div>

<!--Group Name Entry-->




<script>
    var screenSize = $(window).width();
    var group_id=''

    $('#order_now').click( function (){
        $('#group_modal').modal('toggle');
        show_my_accounts('group_order', group_id)
    })

    var customer_id = <?php echo $customer_id;?>
    //alert('ok')
    view_group = function view_group(id){
        var html='';
        group_id=id;

        $.ajax({
            url: project_url +"includes/controller/groupController.php",
            data:{
                q: "group_details",
                customer_id: customer_id,
                group_id: id
            },
            type:'POST',
            async:false,
            dataType: "json",
            success: function(data) {

                var html = ''
                if (!jQuery.isEmptyObject(data.records)) {
                    $('#modal_group_name').html(data['name'])
                    $.each(data.records, function (i, data) {
                        html += '<tr><td class="text-capitalize">' + data['name'] + '</td><td class="text-capitalize">' + data['email'] + '</td></tr>'
                    })
                    $('#members_info').html(html)
                }

            }



        });

        $('#group_modal').modal();

    }

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
                if(data == 0){
                    html= "<h5 class='center' style='color: red; text-align: center'>Your do not have any Group </h5>"
                }
                else{
                    html += '<table class="table table-bordered table-hover" id="table_big" style="display: block; background-color: white">\n' +
                        '                <thead>\n' +
                        '                <tr style="background-color: #e4b95b; alignment: center">\n' +
                        '                    <th width="60%">Group Name</th>\n' +
                        '                    <th width="20%">Members</th>\n' +
                        '                    <th></th>\n' +
                        '                </tr>\n' +
                        '                </thead>\n' +
                        '                <tbody>\n'

                    if(!jQuery.isEmptyObject(data.records)){
                        $.each(data.records, function(i,data){
                            html+='<tr>\n' +
                                ' <td>'+data['name']+'</td>\n' +
                                '  <td style="text-align: center">'+data['members']+'</td>\n' +
                                '  <td><button class="btn btn-block"><i class="fa fa-search-plus pointer" onclick="view_group('+data['id']+')"></i></button></td>\n' +
                                '  </tr>'
                            //alert(i)
                        })
                    }
                    html+= '  </tbody>\n' +
                        '  </table>'
                }
                $('#groupInfo').html(html)
            }

        });
    }
    loadGroups()

    $('#save_group').click(function(event){
        event.preventDefault();
        var formData = new FormData($('#group_form')[0]);
        formData.append("q","insert_or_update");
        formData.append("customer_id",customer_id);

        //validation
        console.log(formData.values())
        if($.trim($('#group_name').val()) == ""){
            success_or_error_msg('#form_submit_error','danger','Please insert group name',"#group_name");
        }
        else if($.trim($('#members').val()) <2){
            success_or_error_msg('#form_submit_error','danger','Please insert group members',"#group_member");
        }
        else{

            $.ajax({
                url: project_url +"includes/controller/groupController.php",
                type:'POST',
                data:formData,
                async:false,
                cache:false,
                contentType:false,processData:false,
                success: function(data){
                    $('#save_group').removeAttr('disabled','disabled');

                    if($.isNumeric(data)==true && data>0){
                        success_or_error_msg('#form_submit_error',"success","Save Successfully");


                    }
                    $("#group_form").trigger('reset');
                    $('#member_entry').html('')

                    loadGroups()
                }
            });

        }
    })

    $('#members').on('change',function () {
        var member= $('#members').val()

            var html="<br><h5 style='text-align: center'> Enter Member details</h5><hr>" +
                "<div class='col-md-6 col-sm-6 col-xs-6' style='text-align: left'><lebel><b>Name</b></lebel></div>" +
                "<div class='col-md-6 col-sm-6 col-xs-6'style='text-align: left'><lebel><b>Email</b></lebel></div>"
            while(member>1){
                html+="<div class='col-md-6 col-sm-6 col-xs-6' style='padding: 3px'><input type='text' name='name[]' style='border-radius: 5px;height: 40px; margin: 5px; padding: 5px'></div><div class='col-md-6 col-sm-6 col-xs-6' style='padding: 3px'><input type='text' name='email[]' style='border-radius: 5px;height: 40px; margin: 5px; padding: 5px'></div>"
                member-=1
            }


        $('#member_entry').html(html)
    })

</script>

