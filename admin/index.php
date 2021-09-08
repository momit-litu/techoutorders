<?php 
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 3600);
// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(3600);
session_start();
//echo "MOMIT STOP HERE";die;
include 'includes/static_text.php';
if(!isset($_SESSION['user_id'])){ ob_start(); header("Location:".$activity_url."login.php"); exit();}
else if($_SESSION['user_id'] == ""){ ob_start(); header("Location:".$activity_url."login.php"); exit();}
else if(!isset($_REQUEST['view'])){ob_start(); header("Location:".$activity_url."index.php?module=personal&view=profile"); exit();}
else if($_REQUEST['view'] == "" ){ ob_start(); header("Location:".$activity_url."index.php?module=personal&view=profile"); exit();}
else{
	include("dbConnect.php");
	include("dbClass.php");
	$dbClass   = new dbClass;		
	$user_id   = $_SESSION['user_id'];
	$user_type = $_SESSION['user_type'];
    //$currency   = $dbClass->getDescription('currency_symbol');


	$logo      = $dbClass->getDescription('website_url')."admin/".$dbClass->getDescription('company_logo');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $website_title; ?></title>
    <!-- Bootstrap core CSS -->
    <link href="theme/css/bootstrap.min.css" rel="stylesheet">
    <link href="theme/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="theme/css/animate.min.css" rel="stylesheet">
    <!-- Custom styling plus plugins -->
    <link href="theme/css/custom.css" rel="stylesheet">    
    <!--calender-->
    <link href="theme/css/calendar/fullcalendar.css" rel="stylesheet">
    <link href="theme/css/calendar/fullcalendar.print.css" rel="stylesheet" media="print"> 
    <link href="theme/css/jquery-ui.css" rel="stylesheet">       
    <!--data table-->
    <link href="theme/css/datatables/tools/css/dataTables.tableTools.css" rel="stylesheet">     
    <!-- select2 -->
    <link href="theme/css/select/select2.min.css" rel="stylesheet">
    <!-- switchery -->
    <link href="theme/css/switchery/switchery.min.css"  rel="stylesheet" />  
         
    <link href="theme/css/icheck/flat/green.css" rel="stylesheet">
    
	<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
	<link rel="icon" href="images/favicon.png" type="image/x-icon">
	<script src="theme/js/jquery.min.js"></script>
    <script src="js/static_text.js"></script>
    <script src="js/common.js"></script>
</head>
<body class="nav-md">
<audio id="myAudio">
  <source src="../tone/Tinkle.mp3" type="audio/mpeg">
  <source src="../tone/Tinkle.m4r" type="audio/m4r">
  <source src="../tone/Tinkle.ogg" type="audio/ogg">
</audio>
<!--
<audio id="myAudio">
  <source src="../tone/Loud_Alarm.mp3" type="audio/mpeg">
  <source src="../tone/Loud_Alarm.m4r" type="audio/m4r">
  <source src="../tone/Loud_Alarm.ogg" type="audio/ogg">
</audio>
-->
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;"> 
                       <a href="" class="website_title" style="font-size:20px !important; font-weight:bold; color: white"><img style="height: 50px; width: auto" src="<?php echo $logo; ?>"> <?php echo $website_title; ?></a>
                    </div>
                    <div class="clearfix"></div>
                    <!-- menu prile quick info -->
                    <div class="profile">
                        <div class="profile_pic">
                            <img src="<?php echo $_SESSION['user_pic']; ?>" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span><h2><?php echo $_SESSION['user_name']; ?></h2> <h5><?php echo $_SESSION['user_desg']; ?></h5></span>
                        </div>
                    </div>
                    <!-- /menu prile quick info -->
                    <br />
                    <!-- sidebar menu -->
					<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
						<div class="menu_section">
							<ul class="nav side-menu">
							 <li><a href="index.php?module=dashboard&view=dashboard"><i class="fa fa-home"></i>DashBoard</a></li>
								<hr></hr>
								 <?php 
									if($dbClass->getUserGroupPermission(105) != 1 ){
										include("view/common_view/left_menu.php"); 
									}
								 ?>
					         </ul>
						</div>
					</div>
                    <!-- /sidebar menu -->
                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Logout" href="logout.php">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>
            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav class="" role="navigation">
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
                        <ul class="nav navbar-nav navbar-right">

                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <img src="<?php echo $_SESSION['user_pic']; ?>" alt="">
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                    <?php
                                    if($dbClass->getUserGroupPermission(105) != 1 ){ ?>
                                    <li><a href="index.php?module=personal&view=profile">  Profile</a></li>
                                    <?php
                                    }
                                    ?>
                                    <li><a href="logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                                </ul>
                            </li>
                            <li role="presentation" class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa  fa-bell-slash"></i>
                                    <span class="badge bg-red" id="unread_notifications"></span>
                                </a>
                                <ul id="notification_ul" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">
                                    <ul id="notification_ul_body">
                                        <li>
                                            <div class="text-left col-md-6">
                                                <button class="btn btn-primary btn-xs has-spinner" id="load_more_not_button"><span class="spinner"><i class="fa fa-spinner fa-spin fa-fw"></i></span>Load More Notificatons?</button>
                                            </div>

                                            <div class="text-right col-md-6">
                                                <a href="index.php?module=notification&view=adminNotification">
                                                    <strong>All Notifications</strong>
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                            </div>

                                        </li>
                                    </ul>
                                    <ul id="notification_ul_b"></ul>
                                </ul>
                            </li>
                            <li class="" id="refresh_btn">
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->
            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
						
						

							<div class="modal fade bs-example-modal-lg" id="main_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
								<div class="modal-dialog modal-lg " role="document">
									<div class="modal-content">
										<div class="modal-header">
											<div class="col-lg-10 col-sm-8">
												<h2 class="modal_title"></h2> 
											</div>
											<div class="col-lg-1 col-sm-2">
												<button type="button" class="btn btn-warning no-print" name="print" id="printBtn"><i class="fa fa-xs fa-print"></i></button>
											</div>
											<div class="col-lg-1 col-sm-2">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											</div>	
										</div>                                      
										<div class="modal-body">

											<div class="block">
												<div class="block_content"> 
													<div class="col-lg-12 col-sm-12">                     
														<div class="modal_content">
																							
														</div>
														<p class="text-right"><strong>Print Time : </strong> <?php echo date('Y-m-d h:m:s'); ?>							
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<input type="hidden" id="modal_doc_id" />												
												<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
							</div>						

                            <div id="main_container" class="" style="min-height:600px;">
      							<!--
                                
                                All the pages will load here 
                                
                                -->
                            </div>                             
                        </div>
                    </div>
                </div>
				
				
				<!------ order modal --->
				<div class="modal fade booktable" id="order_modal" tabindex="-2" role="dialog" aria-labelledby="booktable">
					<div class="modal-dialog" role="document" style="width:80% !important">
						<div class="modal-content">
							<div class="modal-body">
								<div id="order-div" style="margin-bottom: 30px">
									<div class="title text-center">
										<h3 class="text-coffee center"> <a href="#"><img src="<?php echo ($logo); ?>" alt="" style="height: 100px; width: 100px"></a></h3>
										<h4 class="text-coffee center">Order No # <span id="ord_title_vw"></span></h4>
									</div>
									<div class="done_registration ">
										<div class="doc_content">
											<div class="col-md-12" style="margin-left: 0px; padding: 0px; margin-bottom: 20px">
												<div class="col-md-6" style="margin: 0px; padding: 0px">
													<h4>Order Details:</h4>
													<div class="">
														<span><button id="order_status_option" type="button" style="min-width:60px" class="btn btn-sm btn-success btn-lg disabled"></button></span><br/>
														<span id="ord_date"></span><br/>
														<span id="dlv_date"></span> <br/>
														<span id="dlv_ps"></span> <br/>
														<span id="dlv_pm"></span><br/>
													</div>
												</div>
												<div class="col-md-6" style="text-align:right">
                                                    <h4>Customer Details:</h4>
                                                    <address id="customer_detail_vw">
                                                    </address
													<div class="col-md-10 col-sm-10 col-xs-6">
														<input type="hidden" id="order_status_id" name="order_status_id" value="1" />
														<input type="hidden" id="order_id_edit">
														<input type="hidden" id="ordered_customer_id">
														
														<button id="order_received" onclick="update_order_status(2)" type="button" style="min-width:60px" class="order_status_btn btn btn-success btn-lg">Received</button>
														<button id="order_preparing" onclick="update_order_status(3)" type="button" style="min-width:60px" class="order_status_btn btn btn-success btn-lg">Preparing</button>
														<button id="order_ready" onclick="update_order_status(4)" type="button" style="min-width:60px" class="order_status_btn btn btn-success btn-lg">Ready</button>
														<button id="order_delivered" onclick="update_order_status(5)" type="button" style="min-width:60px" class="order_status_btn btn btn-success btn-lg">Delivered</button>
														<button id="order_rejected" onclick="update_order_status(6)" type="button" style="min-width:60px" class="btn btn-danger btn-lg">Reject</button>
														
														<!--
														<input type="hidden" id="order_status_id" name="order_status_id" value="1" />
														<input type="hidden" id="order_id_edit">
														<button class="btn btn-primary btn-lg dropdown-toggle" style="width:190px" type="button" id="dropdown_status" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Change Status
															<span class="caret"></span>
														</button>
														<ul class="dropdown-menu" aria-labelledby="dropdown_status">
															<li id="order_received"><a href="javascript:void(0)" onclick="update_order_status(2)">Received</a></li>
															<li id="order_preparing"><a href="javascript:void(0)" onclick="update_order_status(3)">Preparing</a></li>
															<li id="order_ready"><a href="javascript:void(0)" onclick="update_order_status(4)">Ready</a></li>
															<li id="order_delivered"><a href="javascript:void(0)" onclick="update_order_status(5)">Delivered</a></li>
														</ul>
														<button id="order_status_option" type="button" style="min-width:60px" class="btn btn-success btn-lg disabled">Ordered</button>
														-->
													</div>

												</div>

											</div>
											<div id="ord_detail_vw">
												<table class="table table-bordered" >
													<thead>
													<tr>
														<th align="center">Items</th>
														<th width="10%" align="center">Quantity</th>
														<th width="12%" style="text-align:right">Rate</th>
														<th width="12%"  style="text-align:right">Price</th>
													</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
												<p>Note: <span id="note_vw"></span></p>
												<p>Print Time : <?php echo date("Y-m-d h:m:s"); ?></p>
												<br />
											</div>
										</div>
										<div class="col-md-12 center"> <button type="button" class="btn btn-warning" id="order_print"><i class="fa fa-lg fa-print"></i></button></div>

									</div>

								</div>

							</div>
						</div>
					</div>
				</div>
				
                <!-- footer content -->
                <!--footer>
                    <div class="">
                        <p class="pull-right">
                            <span class="lead"><span class="lead">&copy;  2018 MBrothers Solution</span></span>
                        </p>
                    </div>
                    <div class="clearfix"></div>
                </footer-->
                <!-- /footer content -->
            </div>
            <!-- /page content -->
        </div>

    </div>

    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
        </ul>
        <div class="clearfix"></div>
        <div id="notif-group" class="tabbed_notifications"></div>
    </div>


	<div id="wait" style="display:none;width:69px;height:89px;position:absolute;top:50%;left:50%;padding:2px;">	
		 <button class="btn btn-primary btn-lg has-spinner active" disabled >
			<span class="spinner"><i class="fa fa-spinner fa-spin fa-fw "></i></span> <?php echo 'Loading.............'; ?></span>
		</button>
	</div>


  	<!--  <script src="js/post.js"></script>-->
    <script src="theme/js/bootstrap.min.js"></script>
    <script src="theme/js/custom.js"></script> 
    <script src="theme/js/jquery-ui.js"></script> 

    
    <!-- chart js  -->
    <script src="theme/js/chartjs/chart.min.js"></script>      
    
    <!-- bootstrap progress js -->
	<script src="theme/js/progressbar/bootstrap-progressbar.min.js" type="text/javascript"></script>
    <script src="theme/js/nicescroll/jquery.nicescroll.min.js" type="text/javascript"></script>
    
    <!-- icheck -->
	<script src="theme/js/icheck/icheck.min.js" type="text/javascript"></script> 
 	
    <!-- Datatables -->
    <script src="theme/js/datatables/js/jquery.dataTables.js"></script>
    <script src="theme/js/datatables/tools/js/dataTables.tableTools.js"></script>
    
    <!-- daterangepicker     -->
    <script type="text/javascript" src="theme/js/moment/moment.min.js"></script>
    <script type="text/javascript" src="theme/js/datepicker/daterangepicker.js"></script>
   
     <!-- tags -->
    <script src="theme/js/tags/jquery.tagsinput.min.js"></script>
    
    <!-- switchery -->
    <script src="theme/js/switchery/switchery.min.js"></script>
    
    <!-- select2 -->
    <script src="theme/js/select/select2.full.js"></script>
    
    <!-- form validation -->
   <!-- <script type="text/javascript" src="theme/js/parsley/parsley.min.js"></script>-->
    
    <!-- textarea resize -->
    <script src="theme/js/textarea/autosize.min.js"></script>    
    <script>        autosize($('.resizable_textarea'));    </script>
 
    
    
    <!-- pace -->
    <script src="theme/js/pace/pace.min.js"></script>
    
    
    <!-- ckeditor -->
    <script src="theme/ckeditor-ckfinder-integration-master/ckeditor/ckeditor.js" type="text/javascript" ></script>
    <script src="theme/ckeditor-ckfinder-integration-master/ckfinder/ckfinder.js" type="text/javascript" ></script>
<?php
}
?>
</body>
</html>

<script>

$(document).ready(function () {

	var user_id = "<?php echo $_SESSION['user_id']; ?>";
	
	$('body').on("click", ".dropdown-menu", function (e) {
		$(this).parent().is(".open") && e.stopPropagation();
	});

	$('#load_more_not_button').click(function() {
		 $(this).toggleClass('active');
		 show_notifications('more');
    });
	
	
	set_time_out_fn_noti = function set_time_out_fn_noti(){
		setTimeout(function(){
            show_notifications('')
            set_time_out_fn_noti();
		}, 30000);
	}
    
    set_time_out_fn_noti();
	show_notifications('');

	view_notification_details = function view_notification_details(order_id){
		$('#ord_detail_vw>table>tbody').html('');
		$.ajax({
			url: project_url+"controller/orderController.php",
			type:'POST',
			async:false,
			dataType: "json",
			data:{
				q: "get_order_details_by_invoice",
				order_id: order_id
			},
			success: function(data){
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,data){
						$('#order_id_edit').val(data.order_id);
						$('#ordered_customer_id').val(data.customer_id);
						$('#ord_title_vw').html(data.invoice_no);
						$('#ord_date').html("Ordered Time: "+data.order_date);
						$('#dlv_date').html("Delivery Time: "+data.delivery_date);
						$('#dlv_ps').html("Payment Status: "+data.paid_status);
						$('#dlv_pm').html("Payment Method: "+data.payment_method);
						$('#customer_detail_vw').html(" "+data.customer_name+"<br/><b>Mobile:</b> "+data.customer_contact_no+"<br/><b>Address:</b> "+data.customer_address);
						$('#note_vw').html(data.remarks);

						var order_tr = "";
						var order_total = 0;
						order_infos	 = data.order_info;
						var order_arr = order_infos.split('..,');
						//console.log(order_arr)
						$.each(order_arr, function(i,orderInfo){

							var order_info_arr = orderInfo.split('#');
							var total = ((parseFloat(order_info_arr[4])*parseFloat(order_info_arr[5])));
							order_tr += '<tr><td class="text-capitalize">'+order_info_arr[2].split('..')[0]+' <br>'+order_info_arr[6].split('..')[0]+'</td><td align="center">'+order_info_arr[5]+'</td><td align="right">'+order_info_arr[4]+'</td><td align="right">'+total+'</td></tr>';
							order_total += total;
						});
						
						var total_order_bill = ((parseFloat(order_total)+parseFloat(data.delivery_charge))-parseFloat(data.discount_amount));
						var total_paid = data.total_paid_amount;
						
						order_tr += '<tr><td colspan="3" align="right" ><b>Total Amount</b></td><td align="right"><b>'+total_paid+'</b></td></tr>';
						
						$('#ord_detail_vw>table>tbody').append(order_tr);
						
						if(data.order_status==2){
							$('#order_status_option').html("Received");
							$('#order_status_id').val(2);
							
							//next order status button show
							$('#order_received').hide();
							$('#order_preparing').show();
							$('#order_ready').hide();
							$('#order_delivered').hide();
							$('#order_rejected').show();
						}
						else if(data.order_status==3){
							$('#order_status_option').html("Preparing");
							$('#order_status_id').val(3);
							
							//next order status button show
							$('#order_received').hide();
							$('#order_preparing').hide();
							$('#order_ready').show();
							$('#order_delivered').hide();
							$('#order_rejected').show();
						}
						else if(data.order_status==4){
							$('#order_status_option').html("Ready");
							$('#order_status_id').val(4);
							
							//next order status button show
							$('#order_received').hide();
							$('#order_preparing').hide();
							$('#order_ready').hide();
							$('#order_delivered').show();
							$('#order_rejected').show();
						}
						else if(data.order_status==5){
							$('#order_status_option').html("Delivered");
							$('#order_status_id').val(5);
							
							//next order status button show
							$('#order_received').hide();
							$('#order_preparing').hide();
							$('#order_ready').hide();
							$('#order_delivered').hide();
							$('#order_rejected').hide();
						}
						else{
							$('#order_status_option').html("Ordered");
							$('#order_status_id').val(1);
							
							//next order status button show
							$('#order_received').show();
							$('#order_preparing').hide();
							$('#order_ready').hide();
							$('#order_delivered').hide();
							$('#order_rejected').show();
						}
						//for small device
					});
				}
			}
		});
		
		$('#order_modal').modal();
	}
	
	update_order_status = function update_order_status(status_id){
		var order_id = $('#order_id_edit').val();			
		var customer_id = $('#ordered_customer_id').val();				
		var url = project_url+"controller/orderController.php";
		$.ajax({
			url: url,
			type:'POST',
			async:false,
			data:{
				q: "update_order_status",
				order_id:order_id,
				status_id:status_id,
				customer_id:customer_id
			},
			success: function(data){
				if(data==1){
					if(status_id==2){
						$('#order_status_option').html("Received");
						$('#order_status_id').val(2);
						
						//next order status button show
						$('#order_received').hide();
						$('#order_preparing').show();
						$('#order_ready').hide();
						$('#order_delivered').hide();
						$('#order_rejected').show();
					}
					else if(status_id==3){
						$('#order_status_option').html("Preparing");
						$('#order_status_id').val(3);
						
						//next order status button show
						$('#order_received').hide();
						$('#order_preparing').hide();
						$('#order_ready').show();
						$('#order_delivered').hide();
						$('#order_rejected').show();
					}
					else if(status_id==4){
						$('#order_status_option').html("Ready");
						$('#order_status_id').val(4);
						
						//next order status button show
						$('#order_received').hide();
						$('#order_preparing').hide();
						$('#order_ready').hide();
						$('#order_delivered').show();
						$('#order_rejected').show();
					}
					else if(status_id==5){
						$('#order_status_option').html("Delivered");
						$('#order_status_id').val(5);
						
						//next order status button show
						$('#order_received').hide();
						$('#order_preparing').hide();
						$('#order_ready').hide();
						$('#order_delivered').hide();
						$('#order_rejected').hide();
					}
					else if(status_id==6){
						$("#order_modal").click();
					}
				}
			}
		});
	}
	
	
});


</script>