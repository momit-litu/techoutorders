<?php
session_start();
include("../includes/dbConnect.php");
include("../includes/dbClass.php");
$dbClass = new dbClass;
$is_logged_in_customer = "";
$website_url  = $dbClass->getDescription('website_url');
$currency   = $dbClass->getDescription('currency_symbol');

$logo         =$website_url."admin/".$dbClass->getDescription('company_logo');


if(!isset($_SESSION['customer_id']) && $_SESSION['customer_id']!=""){ ob_start(); header("Location:index.php"); exit();}
else $is_logged_in_customer = 1;
$customer_id = $_SESSION['customer_id'];
$notification_info = $dbClass->getResultList("SELECT nt.id, nt.order_id, nt.status, nt.details, date_time
                                        FROM notification nt
                                        WHERE nt.notification_user_type = 0 and nt.notified_to = $customer_id
                                        ORDER BY nt.status, nt.id DESC
										");
//echo json_encode($notification_info);
if(empty($notification_info)){
    echo "<h6 class='text-center'>Your have no Notification </h6>";
}
else{


    ?>
	<div  class="col-md-12">
		<div  class="col-md-6">
			<h6>Your Notification List </h6>
		</div>
		<div  class="col-md-6 text-right">
			<div class="search-box" style="display:block" >
				<input type="text" name="txt" placeholder="Search" id="search_notification_field">
				<input type="submit" name="submit" value=" " id="search_notification_button">
			</div>
		</div>
	</div>
    
	<br>
    <hr>
    <section class="home-icon shop-cart bg-skeen" style="padding-top: 20px">
        <div class="container" style="max-width:100%" id="oredrs_div">

            <table class="table table-bordered table-hover" id="notification_Table" style="background-color: white">
                <thead>
					<tr style="alignment: center">
						<th>Notification</th>
						<th>date</th>
						<th></th>
					</tr>
                </thead>
                <tbody id="notification_table_body" >
                  <?php
                   /* foreach($notification_info as $notification){
                       // $order_no = '"'.$order['order_no'].'"';
                        if($notification['status']==0){
                            $color='#B66335';
                        }
                        else  $color='';

                        echo
                            "<tr style='color: $color'>
							  <td>".$notification['details']."</td>
							  <td>".$notification['date_time']."</td>
							  <td><button class='btn btn-block'><i class='fa fa-search-plus pointer' id='notification_no_".$notification['id']."' onclick='view_notification(".$notification['id'].")'></i></button></td>
						  </tr>
						";
                    }*/
                    ?>

                </tbody>
            </table>
			<div class="gallery-pagination">
				<div class="gallery-pagination-inner">
					<ul id="paginate">
					</ul>
				</div>
			</div>
        </div>
    </section>
    <?php
}
?>



<!-- Start Order details -->


<!-- End order -->

<script>
    var current_page_no=1;
	localStorage.setItem("currenturl", "notification");

    function view_notification(id) {
        $.ajax({
            url: project_url+"includes/controller/notificationController.php",
            type: "post",
            async:false,
            data:{
                q: "update_notification_status",
                notification_id:id
            },
            success: function(data){
                show_notifications_no();
                $('#notification_no_'+id).closest('tr').css("color","#8a6d3b");
				$('#notification_no_'+id).closest('tr').css("background-color","#fff");
            }
        });

    }

    load_page = function load_page(page_no){
        if(page_no != 0){
            // every time current_page_no need to change if the user change page
            current_page_no=page_no;
            var search_txt = $("#search_notification_field").val();
            load_notifications(search_txt)
        }
    }

	function load_notifications(search_txt) {
		$('#notification_Table tbody').html('');

		$.ajax({
			url: project_url+"includes/controller/notificationController.php",
			type: "post",
			async:false,
			data:{
				q: "get_all_notifications",
				limit:20,
				page_no:current_page_no,
				search_txt:search_txt
			},
			success: function(data){
				//show_notifications_no();
				data = JSON.parse(data)
				var records_array = data.records;
				var tr="";
				if(!jQuery.isEmptyObject(data['records'])){
					$.each(data['records'], function(i,data){
						var text_color= back_color =""
						if(data['read_status'] != 'Seen'){
							text_color = "cursor:pointer;  color:#B66335;";
							back_color = "background-color:#e0d7d7";
						}
						tr +='<tr class="even pointer" style="'+text_color+back_color+'" id="notification_no_'+data['id']+'"  onclick="view_notification('+data['id']+')"><td class="details">'+data['details']+'</td><td class="date_time">'+data['date_time']+'</td></tr>';
					});
					//alert(tr);
					$('#notification_Table tbody').append(tr);
					
					//paging
					total_pages = data['total_pages'];
					paging_html = "";
					if(data['total_pages'] == 1){						
						paging_html += '<li><a href="javascript:void(0)" class="pagination-prev paginate_button_disabled"><i class="fa fa-arrow-left" aria-hidden="true"></i><span>PREV page</span></a></li>'+
										'<li class="active"><a  href="javascript:void(0)" class="paginate_active" onclick="load_page(1)"><span>1</span></a></li>'+
										'<li><a href="javascript:void(0)" class="pagination-next paginate_button_disabled"><span>next page</span> <i class="fa fa-arrow-right" aria-hidden="true"></i></a></li>';
					}
					else if(total_pages > 1){
						if(current_page_no == 1) prev_page_no = 0;
						else 					 prev_page_no = (parseInt(current_page_no)-1);
						paging_html += '<li><a href="javascript:void(0)" class="pagination-prev paginate_button_disabled"  onclick="load_page('+prev_page_no+')"><i class="fa fa-arrow-left" aria-hidden="true"></i><span>PREV page</span></a></li>';
						page_i=1
						while(page_i < total_pages+1){
							if(current_page_no == page_i) paging_html  +=  '<li class="active"><a href="javascript:void(0)" class="paginate_active" onclick="load_page('+page_i+')"><span>'+page_i+'</span></a></li>';
							else 					      paging_html  += '<li class="active"><a href="javascript:void(0)" onclick="load_page('+page_i+')"><span>'+page_i+'</span></a></li>';
							page_i++;
						}

						if(current_page_no == total_pages)  next_page_no = 0;
						else 					 			next_page_no = (parseInt(current_page_no)+1);
						paging_html   += '<li><a href="javascript:void(0)" class="pagination-next" onclick="load_page('+next_page_no+')"><span>next page</span> <i class="fa fa-arrow-right" aria-hidden="true"></i></a></li>';
					}
					$('#paginate').html(paging_html);					
				}
				//if the table has no records / no matching records
				else{
					grid_has_no_result("notification_Table",10);
				}
			}
		});
	}


    // function after click search button
    $('#search_notification_button').click(function(){
        var search_txt = $("#search_notification_field").val();
        // every time current_page_no need to set to "1" if the user search from search bar
        current_page_no=1;
        load_notifications(search_txt);
    });

    //function after press "enter" to search
    $('#search_notification_field').keypress(function(event){
        var search_txt = $("#search_notification_field").val();
        if(event.keyCode == 13){
            // every time current_page_no need to set to "1" if the user search from search bar
            current_page_no=1;
			load_notifications(search_txt)
        }
    });
    load_notifications("")

</script>
