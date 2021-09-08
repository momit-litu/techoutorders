<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
$user_type = $_SESSION['user_type'];

if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$website_url."../view/login.php");
else if($dbClass->getUserGroupPermission(15) != 1){
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
        <h2>Users List</h2>
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
		<!-- Adnach search end -->
		
		<div class="dataTables_length">
        	<label>Show 
                <select size="1" style="width: 56px;padding: 6px;" id="emp_Table_length" name="emp_Table_length" aria-controls="emp_Table">
                    <option value="50" selected="selected">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                 </select> 
                 Post
             </label>
         </div>
         <div class="dataTables_filter" id="emp_Table_filter">         
			<div class="input-group">
                <input class="form-control" id="search_emp_field" style="" type="text">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-primary has-spinner" id="search_emp_button">
                     <span class="spinner"><i class="fa fa-spinner fa-spin fa-fw "></i></span>
                     <i class="fa  fa-search "></i>
                    </button> 
                </span>
            </div>
		</div>
       <div style="height:250px; width:100%; overflow-y:scroll">
        <table id="emp_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped  table-scroll ">
            <thead >
                <tr class="headings">
					<th class="column-title" width="5%"></th>
					<th class="column-title" width="10%">ID</th>
                    <th class="column-title" width="">Name</th>
                    <th class="column-title" width="20%">Designation</th>
                    <th class="column-title" width="15%">Contact No</th>
                    <th class="column-title" width="8%">Active Status</th>
                    <th class="column-title no-link last"  width="100"><span class="nobr"></span></th>
                </tr>
            </thead>
            <tbody id="emp_table_body" class="scrollable">              
                
            </tbody>
        </table>
        </div>
        <div id="emp_Table_div">
            <div class="dataTables_info" id="emp_Table_info">Showing <span id="from_to_limit"></span> of  <span id="total_record"></span> entries</div>
            <div class="dataTables_paginate paging_full_numbers" id="emp_Table_paginate">
            </div> 
        </div>  
    </div>
</div>
<?php if($dbClass->getUserGroupPermission(10) == 1){ ?>
<div class="x_panel user_entry_cl">
    <div class="x_title">
        <h2>User Entry</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
				<a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" id="iniial_collapse">
        <br />             
		<form id="emp_form" name="emp_form" enctype="multipart/form-data" class="form-horizontal form-label-left">   
			<div class="row">
				<div class="col-md-9">
					<div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-6">Full Name</label>
						<div class="col-md-4 col-sm-4 col-xs-6">
							<input type="text" id="emp_name" name="emp_name" required class="form-control col-lg-12"/>
						</div>
						<label class="control-label col-md-2 col-sm-2 col-xs-6">Designation</label>
						<div class="col-md-4 col-sm-4 col-xs-6">
							<input type="text" id="desg_name" name="desg_name" required class="form-control col-lg-12" />
						</div>
					</div>

                    <div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-6" >NID No</label>
						<div class="col-md-10 col-sm-10  col-xs-6">
							<input type="text" id="nid_no" name="nid_no" class="form-control col-lg-12" />
						</div>
					</div>
                    <div class="form-group"> 
						<label class="control-label col-md-2 col-sm-2 col-xs-6" >Address</label>
						<div class="col-md-10 col-sm-10  col-xs-6">
							<input type="text" id="address" name="address" class="form-control col-lg-12" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-6">Contact No</label>
						<div class="col-md-4 col-sm-4 col-xs-6">
							<input type="text" id="contact_no" name="contact_no" required class="form-control col-lg-12"/>
						</div>
						<label class="control-label col-md-2 col-sm-2 col-xs-6">Email</label>
						<div class="col-md-4 col-sm-4 col-xs-6">
							<input type="email" id="email" name="email" required class="form-control col-lg-12"/>
						</div>
					</div>				
					<div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-6">User Name</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<input type="text" id="user_name" name="user_name" required class="form-control col-lg-12"/>
						</div>
						<label class="control-label col-md-2 col-sm-2 col-xs-6">Password</label>
						<div class="col-md-4 col-sm-4 col-xs-6">
							<input type="password" id="password" name="password" required class="form-control col-lg-12"/>
						</div>
					</div>				

					<div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-6">Remarks</label>
						<div class="col-md-10 col-sm-10 col-xs-12">
							<textarea rows="2" cols="100" id="remarks" name="remarks" class="form-control col-lg-12"></textarea> 
						</div>
					</div>
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-6" >Is Active</label>
                        <div class="col-md-4 col-sm-4 col-xs-6">
                            <input type="checkbox" id="is_active" name="is_active" class="form-control col-lg-12"/>
                        </div>
                        <input type="hidden" id="is_active_home_page" name="is_active_home_page" required class="form-control col-lg-12"/>

                    </div>
					<div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-6" >User Group</label>
						<div id="group_select" class="col-md-10 col-sm-10 col-xs-12"></div>
					</div>
					<div class="ln_solid"></div>
				</div>
				<div class="col-md-3">
					<img src="<?php echo $website_url ?>images/no_image.png" width="70%" height="70%" class="img-thumbnail" id="emp_img">
					<input type="file" name="emp_image_upload" id="emp_image_upload">
                    <br />
                    <br />
                    <div id="opening_balance_div" style="display:none">
                    	<label class="control-label ">Helth Account Openning Balance</label>
						<div class="">
							<input type="text" id="openning_balace" name="openning_balace" required class="form-control col-md-4 col-sm-4 col-xs-4" style="max-width:180px !important" disabled="disabled"/ value="10"><b>Taka</b> 
						</div>                    
                   </div> 
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2 col-xs-6"></label>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<input type="hidden" id="emp_id" name="emp_id" />    
					<button type="submit" id="save_emp_info" class="btn btn-success">Save</button>                    
					<button type="button" id="clear_button" class="btn btn-primary">Clear</button>                         
				</div>
				 <div class="col-md-7 col-sm-7 col-xs-12">
					<div id="form_submit_error" class="text-center" style="display:none"></div>
				 </div>
			</div>
		</form>  
    </div>
</div>
	
<?php
		}
	} 
?>
<script src="../admin/js/customTable.js"></script>
<script>
//------------------------------------- general & UI  --------------------------------------
/*
develped by @momit
=>load grid with paging
=>search records
*/
$(document).ready(function () {	
	var user_type = "<?php echo $user_type; ?>";


	// icheck for the inputs
	$('#emp_form').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});	
	
	$('.flat_radio').iCheck({
		//checkboxClass: 'icheckbox_flat-green'
		radioClass: 'iradio_flat-green'
	});

});

/*
develped by @momit
=>load grid with paging
=>search records
*/
$(document).ready(function (){	
	// initialize page no to "1" for paging
	var current_page_no=1;	
	$('.adv_cl').hide();
	load_data = function load_data(search_txt){
		$("#search_emp_button").toggleClass('active');		 
		var emp_Table_length =parseInt($('#emp_Table_length').val());

		
		$.ajax({
			url: project_url+"controller/userController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "grid_data",
				search_txt: search_txt,
				emp_active_status: 2,
				limit:emp_Table_length,
				page_no:current_page_no
			},
			success: function(data){
				var todate = "<?php echo date("Y-m-d"); ?>";
				var user_name =  "<?php echo $user_name; ?>";
				var html = "";
				if($.trim(search_txt) == "Print"){
					var serach_areas= "";
					if(emp_active_status == 1)  serach_areas += "Active <br>";
					if(emp_active_status == 0)  serach_areas += "In-Active <br>";
					/*<input name="print" type="button" value="Print" id="printBTN" onClick="printpage();" />*/
					
					html +='<div width="100%"  style="text-align:center"><img src="'+user_import_url+'/images/logo.png" width="80"/></div><h2 style="text-align:center">Cakencookie</h2><h4 style="text-align:center">User Information Report</h4><table width="100%"><tr><th width="60%" style="text-align:left"><small>'+serach_areas+'</small></th><th width="40%"  style="text-align:right"><small>Printed By: '+user_name+', Date:'+todate+'</small></th></tr></table>';
					
					if(!jQuery.isEmptyObject(data.records)){
						html +='<table width="100%" border="1px" style="margin-top:10px;border-collapse:collapse"><thead><tr><th style="text-align:left">ID</th><th style="text-align:left">Name</th><th style="text-align:left">Designation</th><th style="text-align:center">Contact No</th><th style="text-align:left">Status</th></tr></thead><tbody>';
						$.each(data.records, function(i,data){  
							html += "<tr>";				
							html +="<td style='text-align:left'>"+data.emp_id+"</td>";
							html +="<td style='text-align:left'>"+data.full_name+"</td>";
							html +="<td style='text-align:left'>"+data.designation_name+"</td>";
							html +="<td style='text-align:center'>"+data.contact_no+"</td>";
							html +="<td style='text-align:left'>"+data.active_status+"</td>";
							html += '</tr>';
						});
						html +="</tbody></table>"
					}
					else{
						html += "<table width='100%' border='1px' style='margin-top:10px;border-collapse:collapse'><tr><td><h4 style='text-align:center'>There is no data.</h4></td></tr></table>";		
					}
					WinId = window.open("", "User Information Report","width=950,height=800,left=50,toolbar=no,menubar=YES,status=YES,resizable=YES,location=no,directories=no, scrollbars=YES"); 
					WinId.document.open();
					WinId.document.write(html);
					WinId.document.close();
				}
				else{
					if(data.entry_status==0){
						$('.user_entry_cl').hide();
					}
					// for  showing grid's no of records from total no of records 
					show_record_no(current_page_no, emp_Table_length, data.total_records )
					
					var total_pages = data.total_pages;	
					var records_array = data.records;
					$('#emp_Table tbody tr').remove();
					//$("#search_emp_button").toggleClass('active');
					if(!jQuery.isEmptyObject(records_array)){
						// create and set grid table row
						var colums_array=["photo*image*"+project_url,"emp_id*identifier", "full_name","designation_name","contact_no*center","active_status"];
						// first element is for view , edit condition, delete condition
						// "all" will show /"no" will show nothing
						var condition_array=["","","update_status", "1","delete_status","1"];
						// create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
						// cauton: not posssible to use multiple grid in same page					
						create_set_grid_table_row(records_array,colums_array,condition_array,"user","emp_Table", 0);
						// show the showing no of records and paging for records 
						$('#emp_Table_div').show();					
						// code for dynamic pagination 				
						paging(total_pages, current_page_no, "emp_Table" );					
					}
					// if the table has no records / no matching records 
					else{
						grid_has_no_result( "emp_Table",9);
					}
					$("#search_emp_button").toggleClass('active');					
				}			
			}
		});	
	}
	
	load_user_groups = function load_user_groups(){
		$.ajax({
			url: project_url+"controller/userController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "get_user_groups"
			},
			success: function(data) {
			    //alert(data)
				//var option_html = '';	
				if(!jQuery.isEmptyObject(data.records)){
					var html = '<table class="table table-bordered jambo_table"><thead><tr class="headings"><th class="column-title text-center" class="col-md-8 col-sm-8 col-xs-8" >User Groups</th><th class="col-md-2 col-sm-2 col-xs-12"><input type="checkbox" id="check-all" class="tableflat">Select All</th></tr></thead>';
						$.each(data.records, function(i,datas){ 			
							 html += '<tr><td colspan="2">';
							 $.each(datas.module_group, function(i,module_group){ 
								module_group_arr = module_group.split("*");	
								html += '<div class="col-md-3" ><input type="checkbox" name="group[]"  class="tableflat"  value="'+module_group_arr[0]+'"/> '+module_group_arr[1]+'</div>';								
							 });
							html += '</td></tr>';
							
						});
					html +='</table>';	
				}
				$('#group_select').html(html);
				$('#emp_form').iCheck({
						checkboxClass: 'icheckbox_flat-green',
						radioClass: 'iradio_flat-green'
				});									
				
				$('#emp_form input#check-all').on('ifChecked', function () {
					//alert('check');
					$("#emp_form .tableflat").iCheck('check');
				});
				$('#emp_form input#check-all').on('ifUnchecked', function () {
					//alert('ucheck');
					$("#emp_form .tableflat").iCheck('uncheck');
				});
			}
		});
	}
	// load desire page on clik specific page no
	load_page = function load_page(page_no){
		if(page_no != 0){
			// every time current_page_no need to change if the user change page
			current_page_no=page_no;
			var search_txt = $("#search_emp_field").val();
			load_data(search_txt)
		}
	}	
	// function after click search button 
	$('#search_emp_button').click(function(){
		var search_txt = $("#search_emp_field").val();
		// every time current_page_no need to set to "1" if the user search from search bar
		current_page_no=1;
		load_data(search_txt)
		// if there is lot of data and it tooks lot of time please add the below condition
		/*
		if(search_txt.length>3){
			load_data(search_txt)	
		}
		*/
	});
	//function after press "enter" to search	
	$('#search_emp_field').keypress(function(event){
		var search_txt = $("#search_emp_field").val();	
		if(event.keyCode == 13){
			// every time current_page_no need to set to "1" if the user search from search bar
			current_page_no=1;
			load_data(search_txt)
			// if there is lot of data and it tooks lot of time please add the below condition
			/*
			if(search_txt.length>3){
				load_data(search_txt,1)	
			}*/
		}
	})
	
	
	// load data initially on page load with paging
	load_data("");
	load_user_groups("");
});

/*
develped by @momit
=>form submition for add/edit
=>clear form
=>load data to edit
=>delete record
=>view 
*/
$(document).ready(function () {		
	var url = project_url+"controller/userController.php";

	// save and update for public post/notice
	$('#save_emp_info').click(function(event){		
		event.preventDefault();
		var formData = new FormData($('#emp_form')[0]);
		formData.append("q","insert_or_update");
		if($.trim($('#emp_name').val()) == ""){
			success_or_error_msg('#form_submit_error','danger',"Please Import Name","#emp_name");			
		}
		else{
		//	$('#save_emp_info').attr('disabled','disabled');
			
			$.ajax({
				url: url,
				type:'POST',
				data:formData,
				async:false,
				cache:false,
				contentType:false,processData:false,
				success: function(data){
				    //alert('ok')
					$('#save_emp_info').removeAttr('disabled','disabled');
					
					if($.isNumeric(data)==true && data==5){
						success_or_error_msg('#form_submit_error',"danger","Please Insert Unique Username","#user_name" );			
					}
					else if($.isNumeric(data)==true && data>0){
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
		$('#emp_id').val('');
		$("#emp_form").trigger('reset');		
		$('#emp_img').attr("width", "0%");
		$('#emp_img').attr("src",project_url+"images/no_image.png");
		$('#emp_img').attr("width", "70%","height","70%");
		$('#img_url_to_copy').val("");
		$('#emp_form').iCheck({
			checkboxClass: 'icheckbox_flat-green',
			radioClass: 'iradio_flat-green'
		});	
		$("#emp_form .tableflat").iCheck('uncheck');
		$('#save_emp_info').html('Save');
		
	}
	
	// on select clear button 
	$('#clear_button').click(function(){
		clear_form();
	});
	
	
	delete_user = function delete_user(emp_id){
		if (confirm("Do you want to delete the record? ") == true) {
			$.ajax({
				url: url,
				type:'POST',
				async:false,
				data: "q=delete_user&emp_id="+emp_id,
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
	
	
	edit_user = function edit_user(emp_id){
		//alert(emp_id);
		$.ajax({
			url: url,
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "get_emp_details",
				emp_id: emp_id
			},
			success: function(data){
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,data){ 
						clear_form();					
						$('#emp_id').val(data.emp_id);
						$('#emp_name').val(data.full_name);
						$('#user_name').val(data.user_name);
						$('#nid_no').val(data.nid_no);
						$('#dept_id').val(data.dept_id);
						$('#dept_name').val(data.department_name);
						$('#desg_id').val(data.desig_id);
						$('#desg_name').val(data.designation_name);
						

						$('#contact_no').val(data.contact_no);
						$('#office_phone_no').val(data.office_phone_no);
						$('#bank_acc').val(data.bank_acc_no);
						$('#project_name').val(data.project_name);	
						$('#email').val(data.email);
						$('#remarks').val(data.remarks);
						$('#emp_img').attr("src",project_url+data.photo);
						$('#emp_img').attr("width", "70%","height","70%");
						
						if(data.is_active_home_page==1)
							$('#is_active_home_page').iCheck('check'); 
						if(data.is_active==1)
							$('#is_active').iCheck('check'); 
						
						//change button value 
						$('#save_emp_info').html('Update User');
						
						// to open submit post section
						if($.trim($('#toggle_form i').attr("class"))=="fa fa-chevron-down")
							$( "#toggle_form" ).trigger( "click" );
						
						$.ajax({
							url: project_url+"controller/userController.php",
							dataType: "json",
							type: "post",
							async:false,
							data: {
								q: "get_emp_user_groups",
								emp_id: emp_id
							},
							success: function(data) {
							    //alert('ok')
								//alert(data);
								//var option_html = '';	
								if(!jQuery.isEmptyObject(data.records)){
									var html = '<table class="table table-bordered jambo_table"><thead><tr class="headings"><th class="column-title text-center" class="col-md-8 col-sm-8 col-xs-8" >User Groups</th><th class="col-md-2 col-sm-2 col-xs-12"><input type="checkbox" id="check-all" class="tableflat">Select All</th></tr></thead>';
										$.each(data.records, function(i,datas){ 			
											 html += '<tr><td colspan="2">';
											 $.each(datas.module_group, function(i,module_group){
												module_group_arr = module_group.split("*");
                                                 if(module_group_arr[2]!=1)
													html += '<div class="col-md-3" ><input type="checkbox" name="group[]"  class="tableflat"   value="'+module_group_arr[0]+'"/> '+module_group_arr[1]+'</div>';
												else
													html += '<div class="col-md-3" ><input type="checkbox" name="group[]"  class="tableflat" checked="checked" value="'+module_group_arr[0]+'"/> '+module_group_arr[1]+'</div>';
											 });
											html += '</td></tr>';
											
										});
									html +='</table>';	
								}
								$('#group_select').html(html);
								$('#emp_form').iCheck({
										checkboxClass: 'icheckbox_flat-green',
										radioClass: 'iradio_flat-green'
								});									
								
								$('#emp_form input#check-all').on('ifChecked', function () {
									//alert('check');
									$("#emp_form .tableflat").iCheck('check');
								});
								$('#emp_form input#check-all').on('ifUnchecked', function () {
									//alert('ucheck');
									$("#emp_form .tableflat").iCheck('uncheck');
								});
							}
						});
						
					});
					
				}
			}	
		});			
	}				
});

</script>