<?php
session_start();
include '../../includes/static_text.php';
if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header($activity_url."../view/login.php");
?>
<div class="x_title">
    <h2>Admin Notifications</h2>
    <div class="clearfix"></div>
</div>

<div class="x_content comment_div">
    <ul id="notification_ul_id" class="list-unstyled msg_list noti_cl">

    </ul>
</div>

<script>

    $(function () {
        var notification_current_page_no =1;
        load_notifications = function load_notifications(){
            $.ajax({
                url: project_url+"controller/notificationController.php",
                dataType: "json",
                type: "post",
                async:false,
                data:{
                    q: "load_notifications",
                    limit:30,
                    n_type: 'all',
                    page_no:notification_current_page_no
                },
                success: function(data) {
                    if(!jQuery.isEmptyObject(data.records)){
                        var notification_li = "";
                        $.each(data.records, function(i,notification){
                            if(notification.status == 0){
                                notification_li +='<li style="background-color: #CCD0D7; margin-top: 2px" ><a id="noti_a_'+notification.id+'" style="color:#b66335 !important" onclick="show_notification_details('+notification.id+','+notification.order_id+')" ><span class="image"></span><span class="message" style="font-size: 14px">'+notification.details+'</span></a></li>';
                            }
                            else{
                                notification_li +='<li style="background-color: #CCD0D7; margin-top: 2px" ><a  onclick="show_notification_details('+notification.id+','+notification.order_id+')" ><span class="message" style="font-size: 14px">'+notification.details+'</span></a></li>';
                            }
                        })
                        $('#notification_ul_id').append(notification_li);
                        $('#load_more_notification_not_button').removeClass("active");

                        if(notification_current_page_no==1){
                            notification_li ='<li><div class="text-left"><button class="btn btn-primary btn-xs has-spinner" id="load_more_notification_not_button"><span class="spinner"><i class="fa fa-spinner fa-spin fa-fw"></i></span>Load More Notificatons?</button> </div></li>';
                            $('#notification_ul_id').append(notification_li);
                        }
                        notification_current_page_no++;

                        $('#load_more_notification_not_button').click(function() {
                            $(this).toggleClass('active');
                            load_notifications();
                        });
                    }
                    else{
                        notification_li = '<li> <div style="width:100%" class="text-center alert alert-danger"> No More Notifications </div></li>';
                        $('#notification_ul_id>li:last').before(notification_li);
                        $('#load_more_notification_not_button').removeClass("active");
                        $('#load_more_notification_not_button').attr("disabled","disabled");
                    }
                }
            });
        }

        load_notifications();
    });
</script>
å