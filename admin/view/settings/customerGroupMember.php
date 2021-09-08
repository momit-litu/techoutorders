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
        <h2>Customer Group Details</h2>
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
                <select size="1" style="width: 56px;padding: 6px;" id="customer_Table_length" name="customer_Table_length" aria-controls="search_Table">
                    <option value="50" selected="selected">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                 </select> 
                 Post
             </label>
         </div>
         <div class="dataTables_filter" id="emp_Table_filter">         
			<div class="input-group">
                <input class="form-control" id="search_customer_field" style="" type="text">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-primary has-spinner" id="search_customer_button">
                     <span class="spinner"><i class="fa fa-spinner fa-spin fa-fw "></i></span>
                     <i class="fa  fa-search "></i>
                    </button> 
                </span>
            </div>
		</div>
       <div style="height:250px; width:100%; overflow-y:scroll">
        <table id="search_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped table-scroll ">
            <thead >
                <tr class="headings">
					<th class="column-title" width="5%"></th>
					<th class="column-title" width="10%">Customer ID</th>
                    <th class="column-title" width="">Customer Name</th>
                    <th class="column-title" width="8%">Active Status</th>
                    <th class="column-title no-link last" width="100"><span class="nobr"></span></th>
                </tr>
            </thead>
            <tbody id="customer_table_body" class="scrollable">              
                
            </tbody>
        </table>
        </div>
        <div id="customer_Table_div">
            <div class="dataTables_info" id="customer_Table_info">Showing <span id="from_to_limit"></span> of  <span id="total_record"></span> entries</div>
            <div class="dataTables_paginate paging_full_numbers" id="customer_Table_paginate">
            </div> 
        </div>  
    </div>
</div>
<?php if($dbClass->getUserGroupPermission(10) == 1){ ?>
<div class="x_panel user_entry_cl">
    <div class="x_title">
        <h2>Group Entry</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
				<a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" id="iniial_collapse">
        <br />             
		<form id="customer_form" name="customer_form" enctype="multipart/form-data" class="form-horizontal form-label-left">   
			<div class="row">
				<div class="col-md-9">
					<div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Customer Name</label>
						<div class="col-md-10 col-sm-10  col-xs-12">
							<input type="text" id="customer_name" name="customer_name" class="form-control col-lg-12"/>
							<input type="hidden" id="customer_id" name="customer_id"/>
						</div>
					</div>
					<div class="ln_solid"></div>
					<div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-6" >Customer Group</label>
						<div id="group_select" class="col-md-10 col-sm-10 col-xs-12"></div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2 col-xs-6"></label>
				<div class="col-md-3 col-sm-3 col-xs-12"> 
					<input type="hidden" id="temp_id" name="temp_id"/>
					<button type="submit" id="save_customer_info" class="btn btn-success">Save</button>                    
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
	
	//autosuggest
	$("#customer_name").autocomplete({
		search: function() {
		},
		source: function(request, response) {
			$.ajax({
				url: project_url+'controller/customerGroupController.php',
				dataType: "json",
				type: "post",
				async:false,
				data: {
					q: "customerInfo",
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


	// icheck for the inputs
	$('#customer_form').iCheck({
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
		$("#search_customer_button").toggleClass('active');		 
		var customer_Table_length =parseInt($('#customer_Table_length').val());

		
		$.ajax({
			url: project_url+"controller/customerGroupController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "group_grid_data",
				search_txt: search_txt,
				emp_active_status: 2,
				limit:customer_Table_length,
				page_no:current_page_no
			},
			success: function(data){
				if(data.entry_status==0){
					$('.user_entry_cl').hide();
				}
				// for  showing grid's no of records from total no of records 
				show_record_no(current_page_no, customer_Table_length, data.total_records )
				
				var total_pages = data.total_pages;	
				var records_array = data.records;
				$('#search_Table tbody tr').remove();
				//$("#search_customer_button").toggleClass('active');
				if(!jQuery.isEmptyObject(records_array)){
					// create and set grid table row
					var colums_array=["photo*image*"+project_url,"customer_id*identifier", "full_name","active_status"];
					// first element is for view , edit condition, delete condition
					// "all" will show /"no" will show nothing
					var condition_array=["","","update_status", "1","delete_status","1"];
					// create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
					// cauton: not posssible to use multiple grid in same page					
					create_set_grid_table_row(records_array,colums_array,condition_array,"customer","search_Table", 0);
					// show the showing no of records and paging for records 
					$('#customer_Table_div').show();					
					// code for dynamic pagination 				
					paging(total_pages, current_page_no, "search_Table" );					
				}
				// if the table has no records / no matching records 
				else{
					grid_has_no_result( "search_Table",5);
				}
				$("#search_customer_button").toggleClass('active');			
			}
		});	
	}
	
	
	load_customer_groups = function load_customer_groups(){
		$.ajax({
			url: project_url+"controller/customerGroupController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "get_customer_groups"
			},
			success: function(data) {
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
				$('#customer_form').iCheck({
						checkboxClass: 'icheckbox_flat-green',
						radioClass: 'iradio_flat-green'
				});									
				
				$('#customer_form input#check-all').on('ifChecked', function () {
					//alert('check');
					$("#customer_form .tableflat").iCheck('check');
				});
				$('#customer_form input#check-all').on('ifUnchecked', function () {
					//alert('ucheck');
					$("#customer_form .tableflat").iCheck('uncheck');
				});
			}
		});
	}
	// load desire page on clik specific page no
	load_page = function load_page(page_no){
		if(page_no != 0){
			// every time current_page_no need to change if the user change page
			current_page_no=page_no;
			var search_txt = $("#search_customer_field").val();
			load_data(search_txt)
		}
	}	
	// function after click search button 
	$('#search_customer_button').click(function(){
		var search_txt = $("#search_customer_field").val();
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
	$('#search_customer_field').keypress(function(event){
		var search_txt = $("#search_customer_field").val();	
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
	load_customer_groups("");
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
	var url = project_url+"controller/customerGroupController.php";

	// save and update for public post/notice
	$('#save_customer_info').click(function(event){		
		event.preventDefault();
		var formData = new FormData($('#customer_form')[0]);
		formData.append("q","insert_or_update_groups");
		if($.trim($('#customer_name').val()) == ""){
			success_or_error_msg('#form_submit_error','danger',"Please Select Customer","#customer_name");			
		}
		else{			
			$.ajax({
				url: url,
				type:'POST',
				data:formData,
				async:false,
				cache:false,
				contentType:false,processData:false,
				success: function(data){
					$('#save_customer_info').removeAttr('disabled','disabled');					
					if($.isNumeric(data)==true && data>0){
						success_or_error_msg('#form_submit_error',"success","Save Successfully");
						load_data("");
						clear_form();
					}
					else{	
						success_or_error_msg('#form_submit_error',"danger","Not Saved...");												
					}
				 }	
			});
		}	
	})
	

	
	// clear function to clear all the form value
	clear_form = function clear_form(){			 
		$('#customer_id').val('');
		$('#temp_id').val('');
		$("#customer_form").trigger('reset');		
		
		$('#customer_form').iCheck({
			checkboxClass: 'icheckbox_flat-green',
			radioClass: 'iradio_flat-green'
		});	
		$("#customer_form .tableflat").iCheck('uncheck');
		$('#save_customer_info').html('Save');
		
	}
	
	// on select clear button 
	$('#clear_button').click(function(){
		clear_form();
	});
	
	
	
	edit_customer = function edit_customer(customer_id){
		//alert(customer_id);
		$.ajax({
			url: url,
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "get_customer_details",
				customer_id: customer_id
			},
			success: function(data){
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,data){ 
						clear_form();					
						$('#customer_id').val(data.customer_id);
						$('#temp_id').val(data.customer_id);
						$('#customer_name').val(data.full_name);

						//change button value 
						$('#save_customer_info').html('Update');
						
						// to open submit post section
						if($.trim($('#toggle_form i').attr("class"))=="fa fa-chevron-down")
							$( "#toggle_form" ).trigger( "click" );
						
						$.ajax({
							url: project_url+"controller/customerGroupController.php",
							dataType: "json",
							type: "post",
							async:false,
							data: {
								q: "get_customer_group_details",
								customer_id: customer_id
							},
							success: function(data) {

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
								$('#customer_form').iCheck({
										checkboxClass: 'icheckbox_flat-green',
										radioClass: 'iradio_flat-green'
								});									
								
								$('#customer_form input#check-all').on('ifChecked', function () {
									//alert('check');
									$("#customer_form .tableflat").iCheck('check');
								});
								$('#customer_form input#check-all').on('ifUnchecked', function () {
									//alert('ucheck');
									$("#customer_form .tableflat").iCheck('uncheck');
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