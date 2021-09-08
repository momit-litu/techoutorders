<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
$user_type = $_SESSION['user_type'];
$logo   = $dbClass->getCompanyLogo();
if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$website_url."../view/login.php");
else if($dbClass->getUserGroupPermission(84) != 1){
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
        <h2>Order Details Report</h2>
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
					<label class="control-label col-md-1 col-sm-1 col-xs-6"></label>
					<label class="control-label col-md-2 col-sm-2 col-xs-6" style="text-align:right">Order No<span class="required">*</span></label>
					<div class="col-md-3 col-sm-3 col-xs-6">
						<input class="form-control input-sm" type="text" name="order_no" id="order_no"/> 
						<input type="hidden" name="order_id" id="order_id"/> 
					</div>
					<label class="control-label col-md-1 col-sm-1 col-xs-6"></label>	
					<button type="button" class="btn btn-warning" id="reportBtn"><i class="fa fa-lg fa-print"></i> Report</button>
					<label class="control-label col-md-1 col-sm-1 col-xs-6"></label>
				</div><br/>
				<div style="text-align:center">				
					<div id="form_submit_error" class="text-center" style="display:none"></div>
				</div>
			</div>
		</div>
		<!-- Adnach search end -->
		 
    </div>
</div>


<!-- Start Order details -->
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
                                <div class="col-md-6" style="margin: 0px; padding: 0px; text-align: right">
                                    <h4>Customer Details:</h4>
                                    <address id="customer_detail_vw">
                                    </address
                                    <div class="">

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
<!-- End order -->
	
<?php

	} 
?>
<script src="js/customTable.js"></script> 

<script> 
$(document).ready(function () {	

	$("#order_no").autocomplete({
		search: function() {
		},
		source: function(request, response) {
			$.ajax({
				url: project_url+'controller/reportController.php',
				dataType: "json",
				type: "post",
				async:false,
				data: {
					q: "order_no_info",
					term: request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 3,
		select: function(event, ui) { 
			var order_id = ui.item.id;
			$(this).next().val(order_id);
		}
	});
	
});

$(document).ready(function () {
		
	load_data = function load_data(){
		
		var order_id = $("#order_id").val();
		var order_no = $("#order_no").val();		
		
		if($.trim($('#order_id').val()) == ""){
			success_or_error_msg('#form_submit_error','danger','Please Insert Order No',"#order_no");			
		}
		else{
			$.ajax({
				url: project_url+"controller/reportController.php",
				dataType: "json",
				type: "post",
				async:false,
				data: {
					q: "orderDetails",
					order_no: order_no,
					order_id: order_id
				},
				success: function(data) {
					$('#ord_detail_vw>table>tbody').html('');
					var order_summery=''
                    if(data.type=='group') {                        
                        $('#order_id_edit').val(data.order_details.invoice_no);
                        $('#ordered_customer_id').val(data.order_details.customer_id);
                        $('#ord_title_vw').html(data.order_details.invoice_no);
                        $('#ord_date').html("Ordered Time: "+data.order_details.order_date);
                        $('#dlv_date').html("Delivery Time: "+data.order_details.delivery_date);
                        $('#dlv_ps').html("Payment Status: "+data.order_details.paid_status);
                        $('#dlv_pm').html("Payment Method: "+data.order_details.payment_method);
                        $('#customer_detail_vw').html(" "+data.order_details.full_name+"<br/><b>Mobile:</b> "+data.order_details.mobile+"<br/><b>Address:</b> "+data.order_details.c_address);
                        $('#note_vw').html(data.remarks);

                        order_summery += '<tr><td colspan="3" align="right" ><b>Discount Amount</b></td><td align="right"><b>'+data.order_details.discount_amount+'</b></td></tr>';
                        order_summery += '<tr><td colspan="3" align="right" ><b>Tax Amount</b></td><td align="right"><b>'+data.order_details.tax_amount+'</b></td></tr>';
                        order_summery += '<tr><td colspan="3" align="right" ><b>Tips Amount</b></td><td align="right"><b>'+data.order_details.tips+'</b></td></tr>';
                        order_summery += '<tr><td colspan="3" align="right" ><b>Total Amount</b></td><td align="right"><b>'+data.order_details.total_paid_amount+'</b></td></tr>';
                    }


                    if(!jQuery.isEmptyObject(data.records)){
                        var total_order=0;
                        $.each(data.records, function(i,datas){
                            if(data.type=='individual') {
                                //alert('indi')
                                $('#order_id_edit').val(datas.invoice_no);
                                $('#ordered_customer_id').val(datas.customer_id);
                                $('#ord_title_vw').html(datas.invoice_no);
                                $('#ord_date').html("Ordered Time: "+datas.order_date);
                                $('#dlv_date').html("Delivery Time: "+datas.delivery_date);
                                $('#dlv_ps').html("Payment Status: "+datas.paid_status);
                                $('#dlv_pm').html("Payment Method: "+datas.payment_method);
                                $('#customer_detail_vw').html(" "+datas.customer_name+"<br/><b>Mobile:</b> "+data.customer_contact_no+"<br/><b>Address:</b> "+data.customer_address);
                                $('#note_vw').html(datas.remarks);

                                var order_summery = '<tr><td colspan="3" align="right" ><b>Discount Amount</b></td><td align="right"><b>'+datas.discount_amount+'</b></td></tr>';
                                order_summery += '<tr><td colspan="3" align="right" ><b>Tax Amount</b></td><td align="right"><b>'+datas.tax_amount+'</b></td></tr>';
                                order_summery += '<tr><td colspan="3" align="right" ><b>Tips Amount</b></td><td align="right"><b>'+datas.tips+'</b></td></tr>';
                                order_summery += '<tr><td colspan="3" align="right" ><b>Total Amount</b></td><td align="right"><b>'+datas.total_paid_amount+'</b></td></tr>';

                            }


                            var order_tr = "";
                            var order_total = 0;
                            order_infos	 = datas.order_info;
                            var order_arr = order_infos.split('..,');
                            //console.log(order_arr)
                            $.each(order_arr, function(i,orderInfo){
                                //console.log(orderInfo)
                                //alert(i)
                                var order_info_arr = orderInfo.split('#');
                                var total = ((parseFloat(order_info_arr[4])*parseFloat(order_info_arr[5])));
                                order_tr += '<tr><td class="text-capitalize">'+order_info_arr[2].split('..')[0]+' <br>'+order_info_arr[6].split('..')[0]+'</td><td align="center">'+order_info_arr[5]+'</td><td align="right">'+order_info_arr[4]+'</td><td align="right">'+total+'</td></tr>';
                                order_total += total;
                            });

                            //var total_order_bill = (parseFloat(order_total);
                            total_order += order_total;
                            if(data.type=='individual'){
                                order_tr += '<tr><td colspan="3" align="right" ><b>Total Amount</b></td><td align="right"><b>'+total_order+'</b></td></tr>'+order_summery;
                                var status = datas.order_status;
                            }
                            else {
                                var status = data.order_details.status-2;
                            }

                            $('#ord_detail_vw>table>tbody').append(order_tr);


                            //alert(status)

                            if(status==2){
                                $('#order_status_option').html("Rejected");
                                $('#order_status_option').css("background-color","red");
                                $('#order_status_id').val(2);

                                //next order status button show
                                $('#order_received').hide();
                                $('#order_ready').hide();
                                $('#order_delivered').hide();
                                $('#order_rejected').hide();
                            }
                            else if(status==3){
                                $('#order_status_option').html("Received");
                                $('#order_status_id').val(3);

                                //next order status button show
                                $('#order_received').hide();
                                $('#order_ready').show();
                                $('#order_delivered').hide();
                                $('#order_rejected').show();
                            }
                            else if(status==4){
                                $('#order_status_option').html("Ready");
                                $('#order_status_id').val(4);

                                //next order status button show
                                $('#order_received').hide();
                                $('#order_ready').hide();
                                $('#order_delivered').show();
                                $('#order_rejected').show();
                            }
                            else if(status==5){
                                $('#order_status_option').html("Delivered");
                                $('#order_status_id').val(5);

                                //next order status button show
                                $('#order_received').hide();
                                $('#order_ready').hide();
                                $('#order_delivered').hide();
                                $('#order_rejected').hide();
                            }
                            else{
                                $('#order_status_option').html("Ordered");
                                $('#order_status_id').val(1);

                                //next order status button show
                                $('#order_received').show();
                                $('#order_ready').hide();
                                $('#order_delivered').hide();
                                $('#order_rejected').show();
                            }
                            //for small device
                        });
                    }

                    if(data.type != 'individual'){
                        order_tr = '<tr><td colspan="3" align="right" ><b>Total Amount</b></td><td align="right"><b>'+total_order+'</b></td></tr>'+order_summery;
                        $('#ord_detail_vw>table>tbody').append(order_tr);
                    } 					
				}
			});
			$('#order_modal').modal();
		}
			
	}
	
	$(document).on('click','#order_print', function(){
		var divContents = $("#order-div").html();
		var printWindow = window.open('', '', 'height=400,width=800');
		printWindow.document.write('<html><head><title>DIV Contents</title>');
		printWindow.document.write('</head><body style="padding:10px">');
		printWindow.document.write('<link href="../plugin/bootstrap/bootstrap.css" rel="stylesheet">');
		printWindow.document.write(divContents);
		printWindow.document.write('</body></html>');
		printWindow.document.close();
		printWindow.print();
	});

	//print advance search data
	$('#reportBtn').click(function(){
		load_data();
	});
	
});




</script>