<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
$user_type = $_SESSION['user_type'];

if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$website_url."../view/login.php");
else if($dbClass->getUserGroupPermission(69) != 1){
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
        <h2>Customer List</h2>
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
		<div class="x_panel hide">
			<div class="row">
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link-adv" id="toggle_form_ad"><b><small class="text-primary">Advance Search & Report</small></b><i class="fa fa-chevron-down"></i></a>
					</li>
				</ul>
			</div>
			<div class="x_content adv_cl" id="iniial_collapse_adv">
				<div class="row advance_search_div alert alert-warning">
					<div class="row">
						<label class="control-label col-md-1 col-sm-2 col-xs-4" style="text-align:right">Active</label>
						<div class="col-md-3 col-sm-4 col-xs-8">
							<input type="radio" class="flat_radio" name="is_active_status" id="is_active_status" value="1"/> Yes
							<input type="radio" class="flat_radio" name="is_active_status" id="is_active_status" value="0" /> No
							<input type="radio" class="flat_radio" name="is_active_status" id="is_active_status" value="2" checked="CHECKED"/> All
						</div>
						<div class="col-md-3" style="text-align:center">					
							<button type="button" class="btn btn-info" id="adv_search_button"><i class="fa fa-lg fa-search"></i></button>
							<button type="button" class="btn btn-warning" id="adv_search_print"><i class="fa fa-lg fa-print"></i></button>
						</div>
					</div>
				</div> 
			</div>
		</div>
		<!-- Adnach search end -->
		
		<div class="dataTables_length">
        	<label>Show 
                <select size="1" style="width: 56px;padding: 6px;" id="customer_Table_length" name="customer_Table_length" aria-controls="customer_Table">
                    <option value="50" >50</option>
                    <option value="100">100</option>
                    <option value="500" selected="selected">500</option>
                 </select> 
                 Post
             </label>
         </div>
         <div class="dataTables_filter" id="customer_Table_filter">         
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
        <table id="customer_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped  table-scroll ">
            <thead>
                <tr class="headings">
					<th class="column-title" width="5%"></th>
                    <th class="column-title" width="">Name</th>
                    <th class="column-title" width="10%">User Name</th>
                    <th class="column-title" width="15%">Email</th>
                    <th class="column-title" width="10%">Contact No</th>
					<th class="column-title" width="15%">Address</th>
					<th class="column-title" width="10%">Status</th>
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
<?php if($dbClass->getUserGroupPermission(66) == 1){ ?>
<div class="x_panel customer_entry_cl">
    <div class="x_title">
        <h2>Customer Entry</h2>
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
						<label class="control-label col-md-2 col-sm-2 col-xs-6">Name<span class="required">*</span></label>
						<div class="col-md-4 col-sm-4 col-xs-6">
							<input type="text" id="customer_name" name="customer_name" required class="form-control col-lg-12"/>
						</div>
						<label class="control-label col-md-2 col-sm-2 col-xs-6" for="name">Date of Birth</label>
						<div class="col-md-4 col-sm-4 col-xs-6">
							<input type="date" id="age" name="age" class="form-control col-lg-12" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-6">Contact No</label>
						<div class="col-md-4 col-sm-4 col-xs-6">
							<input type="text" id="contact_no" name="contact_no" required class="form-control col-lg-12"/>
						</div>
						<label class="control-label col-md-2 col-sm-2 col-xs-6">Email<span class="required">*</span></label>
						<div class="col-md-4 col-sm-4 col-xs-6">
							<input type="email" id="email" name="email" class="form-control col-lg-12"/>
						</div>
					</div>
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-6">User Name</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <input type="text" id="user_name" name="user_name" class="form-control col-lg-12"/>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-6">New Password</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <input type="text" id="password" name="password" class="form-control col-lg-12"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-6">Address</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <input type="text" id="address" name="address" class="form-control col-lg-12"/>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-6">City</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <input type="text" id="city" name="city" class="form-control col-lg-12"/>
                        </div>
                    </div><div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-6">State</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <input type="text" id="state" name="state" class="form-control col-lg-12"/>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-6">Zip Code</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <input type="text" id="zipcode" name="zipcode" class="form-control col-lg-12"/>
                        </div>
                    </div>

					<div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-6">Remarks</label>
						<div class="col-md-10 col-sm-10 col-xs-12">
							<textarea rows="2" cols="100" id="remarks" name="remarks" class="form-control col-lg-12"></textarea> 
						</div>
					</div>
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-6">Loyalty Points</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <input type="number" id="loyalty_points" name="loyalty_points" min="0" value="0" class="form-control col-lg-12"/>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-6" >Is Active</label>
                        <div class="col-md-4 col-sm-4 col-xs-6">
                            <input type="checkbox" id="is_active" name="is_active" checked="checked" class="form-control col-lg-12"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-6" >Customer Group</label>
                        <div id="group_select" class="col-md-10 col-sm-10 col-xs-12"></div>
                    </div>
					<div class="ln_solid"></div>
				</div>
				<div class="col-md-3">
					<img src="<?php echo $website_url ?>admin/images/no_image.png" width="70%" height="70%" class="img-thumbnail" id="customer_img">
					<input type="file" name="customer_image_upload" id="customer_image_upload"> 
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2 col-xs-6"></label>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<input type="hidden" id="customer_id" name="customer_id" />    
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
<script src="js/customTable.js"></script>
<script>
//------------------------------------- general & UI  --------------------------------------
/*
develped by @momit
=>load grid with paging
=>search records
*/
$(document).ready(function () {	
	var user_type = "<?php echo $user_type; ?>";
	// close form submit section onload page

	
	// icheck for the inputs
	$('#customer_form').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});	
	
	$('.flat_radio').iCheck({
		//checkboxClass: 'icheckbox_flat-green'
		radioClass: 'iradio_flat-green'
	});
	
	$('#is_active').iCheck('check'); 

});

/*
develped by @momit
=>load grid with paging
=>search records
*/
$(document).ready(function (){	
	// initialize page no to "1" for paging

    load_user_groups = function load_user_groups(){
        $.ajax({
            url: project_url+"controller/customerController.php",
            dataType: "json",
            type: "post",
            async:false,
            data: {
                q: "get_customer_groups"
            },
            success: function(data) {
                //var option_html = '';
                if(!jQuery.isEmptyObject(data.records)){
                    var html = '<table class="table table-bordered jambo_table"><thead><tr class="headings"><th class="column-title text-center" class="col-md-8 col-sm-8 col-xs-8" >User Groups</th><th class="col-md-2 col-sm-2 col-xs-12"><input type="checkbox" id="check-all" class="tablecoupon">Select All</th></tr></thead>';
                    $.each(data.records, function(i,datas){
                        html += '<tr><td colspan="2">';
                        $.each(datas.module_group, function(i,module_group){
                            module_group_arr = module_group.split("*");
                            html += '<div class="col-md-3" ><input type="checkbox" name="group[]"  id="'+module_group_arr[0]+'" class="tablecoupon"  value="'+module_group_arr[0]+'"/> '+module_group_arr[1]+'</div>';
                        });
                        html += '</td></tr>';

                    });
                    html +='</table>';
                }
                $('#group_select').html(html);

            }
        });
    }

    load_user_groups();


	var current_page_no=1;	
	
	$('.adv_cl').hide();
	
	load_data = function load_data(search_txt){
		$("#search_customer_button").toggleClass('active');
		var customer_Table_length =parseInt($('#customer_Table_length').val());
		
		var customer_active_status = $("input[name=is_active_status]:checked").val();

        $.ajax({
			url: project_url+"controller/customerController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "grid_data",
				search_txt: search_txt,
				customer_active_status: customer_active_status,
				limit:customer_Table_length,
				page_no:current_page_no
			},
			success: function(data){
				var todate = "<?php echo date("Y-m-d"); ?>";
				var user_name =  "<?php echo $user_name; ?>";
				var html = "";
				if($.trim(search_txt) == "Print"){
					var serach_areas= "";
					if(customer_active_status == 1)  serach_areas += "Active <br>";
					if(customer_active_status == 0)  serach_areas += "In-Active <br>";
					/*<input name="print" type="button" value="Print" id="printBTN" onClick="printpage();" />*/
					
					html +='<div width="100%"  style="text-align:center"><img src="'+employee_import_url+'/images/logo.png" width="80"/></div><h2 style="text-align:center">Hotel Management System</h2><h4 style="text-align:center">Customer Information Report</h4><table width="100%"><tr><th width="60%" style="text-align:left"><small>'+serach_areas+'</small></th><th width="40%"  style="text-align:right"><small>Printed By: '+user_name+', Date:'+todate+'</small></th></tr></table>';
					
					if(!jQuery.isEmptyObject(data.records)){
						html +='<table width="100%" border="1px" style="margin-top:10px;border-collapse:collapse"><thead><tr><th style="text-align:center">Name</th><th style="text-align:center">Date of Birth</th><th style="text-align:center">Email</th><th style="text-align:center">Contact No</th><th style="text-align:center">Address</th><th style="text-align:center">Status</th></tr></thead><tbody>';
						$.each(data.records, function(i,data){  
							html += "<tr>";				
							html +="<td style='text-align:left'>"+data.full_name+"</td>";
							html +="<td style='text-align:left'>"+data.age+"</td>";
							html +="<td style='text-align:left'>"+data.email+"</td>";
							html +="<td style='text-align:center'>"+data.contact_no+"</td>";
							html +="<td style='text-align:left'>"+data.address+"</td>";
							html +="<td style='text-align:left'>"+data.status_text+"</td>";
							html += '</tr>';
						});
						html +="</tbody></table>"
					}
					else{
						html += "<table width='100%' border='1px' style='margin-top:10px;border-collapse:collapse'><tr><td><h4 style='text-align:center'>There is no data.</h4></td></tr></table>";		
					}
					WinId = window.open("", "Employee Report","width=950,height=800,left=50,toolbar=no,menubar=YES,status=YES,resizable=YES,location=no,directories=no, scrollbars=YES"); 
					WinId.document.open();
					WinId.document.write(html);
					WinId.document.close();
				}
				else{
					if(data.entry_status==0){
						$('.customer_entry_cl').hide();
					}
					// for  showing grid's no of records from total no of records 
					show_record_no(current_page_no, customer_Table_length, data.total_records )
					
					var total_pages = data.total_pages;	
					var records_array = data.records;
					$('#customer_Table tbody tr').remove();
					//$("#search_customer_button").toggleClass('active');
					if(!jQuery.isEmptyObject(records_array)){
						// create and set grid table row
						var colums_array=["photo*image*"+project_url,"customer_id*identifier*hidden","full_name","username","email","contact_no*center","address","status_text"];
						// first element is for view , edit condition, delete condition
						// "all" will show /"no" will show nothing
						var condition_array=["","","update_status", "1","delete_status","1"];
						// create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
						// cauton: not posssible to use multiple grid in same page					
						create_set_grid_table_row(records_array,colums_array,condition_array,"customer","customer_Table", 0);
						// show the showing no of records and paging for records 
						$('#customer_Table_div').show();					
						// code for dynamic pagination 				
						paging(total_pages, current_page_no, "customer_Table" );					
					}
					// if the table has no records / no matching records 
					else{
						grid_has_no_result( "customer_Table",8);
					}
					$("#search_customer_button").toggleClass('active');					
				}			
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
	var url = project_url+"controller/customerController.php";


	
	// save and update for public post/notice
	$('#save_customer_info').click(function(event){		
		event.preventDefault();
		var formData = new FormData($('#customer_form')[0]);
		formData.append("q","insert_or_update");
		if($.trim($('#customer_name').val()) == ""){
			success_or_error_msg('#form_submit_error','danger',"Please Insert Name","#customer_name");			
		}
        if($.trim($('#email').val()) == ""){
            success_or_error_msg('#form_submit_error','danger',"Please Insert Email","#email");
        }
		else{
		//	$('#save_customer_info').attr('disabled','disabled');
			
			$.ajax({
				url: url,
				type:'POST',
				data:formData,
				async:false,
				cache:false,
				contentType:false,processData:false,
				success: function(data){
					$('#save_customer_info').removeAttr('disabled','disabled');
					
					if($.isNumeric(data)==true && data==5){
						success_or_error_msg('#form_submit_error',"danger","Please Insert Unique Identy No","#nid_no" );			
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
	
	//advance search
	$('#adv_search_button').click(function(){
		load_data("Advance_search");
	});
	
	//print advance search data
	$('#adv_search_print').click(function(){
		load_data("Print");
	});
	
	// clear function to clear all the form value
	clear_form = function clear_form(){			 
		$('#customer_id').val('');
		$("#customer_form").trigger('reset');		
		$('#customer_img').attr("src",project_url+"images/no_image.png");
		$('#customer_img').attr("width", "70%","height","70%");
		$('#img_url_to_copy').val("");
		$('#customer_form').iCheck({
			checkboxClass: 'icheckbox_flat-green',
			radioClass: 'iradio_flat-green'
		});	
		$("#customer_form .tableflat").iCheck('uncheck');
		$('#save_customer_info').html('Save');	
		$('#is_active').iCheck('check'); 	
	}
	
	// on select clear button 
	$('#clear_button').click(function(){
		clear_form();
	});
	
	
	delete_customer = function delete_customer(customer_id){
		if (confirm("Do you want to delete the record? ") == true) {
			$.ajax({
				url: url,
				type:'POST',
				async:false,
				data: "q=delete_customer&customer_id="+customer_id,
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
	
	
	edit_customer = function edit_customer(customer_id){
        load_user_groups();

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
                    if(!jQuery.isEmptyObject(data.group)){
                        var html = '<table class="table table-bordered jambo_table"><thead><tr class="headings"><th class="column-title text-center" class="col-md-8 col-sm-8 col-xs-8" >User Groups</th><th class="col-md-2 col-sm-2 col-xs-12"><input type="checkbox" id="check-all" class="tablecoupon">Select All</th></tr></thead>';
                        html += '<tr><td colspan="2">';
                        $.each(data.group, function(i,groups){
                            html += '<div class="col-md-3" ><input type="checkbox" '+groups['status']+' name="group[]"  id="'+groups['id']+'" class="tablecoupon"  value="'+groups['id']+'"/> '+groups['group_name']+'</div>';
                        });
                        html += '</td></tr>';

                        html +='</table>';
                    }
                    $('#group_select').html(html)

                    $.each(data.records, function(i,data){
						clear_form();					
						$('#customer_id').val(data.customer_id);
						$('#customer_name').val(data.full_name);
						$('#contact_no').val(data.contact_no);
						$('#email').val(data.email);
						$('#age').val(data.age);
						$('#address').val(data.address);
                        $('#city').val(data.city);
                        $('#state').val(data.state);
                        $('#zipcode').val(data.zipcode);

                        $('#remarks').val(data.remarks);
                        $('#loyalty_points').val(data.loyalty_points);
                        $('#user_name').val(data.username);


                        if(data.photo == ""){
							$('#customer_img').attr("src",project_url+'images/no_image.png');
						}else{
							$('#customer_img').attr("src",project_url+data.photo);
						}
						$('#customer_img').attr("width", "70%","height","70%");
						
						if(data.status==0)
							$('#is_active').iCheck('uncheck');

                        //change button value
						$('#save_customer_info').html('Update');
						
						// to open submit post section
						if($.trim($('#toggle_form i').attr("class"))=="fa fa-chevron-down")
							$( "#toggle_form" ).trigger( "click" );						
					});
					
				}
			}	
		});			
	}				
	
});


</script>