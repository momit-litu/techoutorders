<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;

if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$activity_url."../view/login.php");
else if($dbClass->getUserGroupPermission(14) != 1){
?> 
	<div class="x_panel">
		<div class="alert alert-danger" align="center">You Don't Have permission of this Page.</div>
	</div>
	<?php 
} 
else{
	?>
	
<div class="x_panel">
    <div class="x_title">
        <h2>Group List</h2>
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
                <select size="1" style="width: 56px;padding: 6px;" id="group_Table_length" name="group_Table_length" aria-controls="group_Table">
                    <option value="50" selected="selected">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                 </select> 
                 Group
             </label>
         </div>
		 <div class="dataTables_filter" id="action_Table_filter">         
			<div class="input-group">
                <input class="form-control" id="search_group_field" style="" type="text">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-primary has-spinner" id="search_group_button">
                     <span class="spinner"><i class="fa fa-spinner fa-spin fa-fw "></i></span>
                     <i class="fa  fa-search "></i>
                    </button> 
                </span>
            </div>
       </div>
       <div style="height:250px; width:100%; overflow-y:scroll">
        <table id="group_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped  table-scroll">
            <thead >
                <tr class="headings">
					<th class="column-title" width="50%">Group Name</th>
                    <th class="column-title" width="20%">Status</th> 
					<th class="column-title no-link last"  width="10%"><span class="nobr"></span></th>	
                </tr>
            </thead>
            <tbody id="group_Table_body" class="scrollable">              
                
            </tbody>
        </table>
        </div>
    </div>
</div>
<div class="x_panel activity_entry_cl">
    <div class="x_title">
        <h2 id="group_entry_title">Group Entry</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
				<a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" id="iniial_collapse">		
		<form method="POST"  id="group_form" name="group_form" enctype="multipart/form-data" class="form-horizontal form-label-left">   
			<div class="row">
				<div class="col-md-12">	
					<div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Name</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" id="group_name" name="group_name" class="form-control col-lg-12"/>
						</div>
					</div>				
					<div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-6" for="name">Is Active</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="checkbox" id="is_active" name="is_active" class="form-control col-lg-12" checked='checked'/>
						</div>
					</div>				
					
					<div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-6"></label>
						<div class="col-md-3 col-sm-3 col-xs-12">
							 <input type="hidden" id="group_id" name="group_id" />    
							 <button  type="submit" id="save_group" class="btn btn-success">Save</button>                    
							 <button type="button" id="clear_button"  class="btn btn-primary">Clear</button>                         
						</div>
						 <div class="col-md-7 col-sm-7 col-xs-12">
							<div id="form_submit_error" class="text-center" style="display:none"></div>
						 </div>
					</div>
				</div>
			</div>
		</form>  
    </div>
</div>


<?php
	} 
?>
<script src="js/customTable.js"></script> 
<script>
//------------------------------------- general & UI  --------------------------------------
/*
develped by 
=>load grid with paging
=>search records
*/
$(document).ready(function () {	
	
	// icheck for the inputs
	$('#group_form').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});	
	
	//datepicker
	$('.date-picker').daterangepicker({
		singleDatePicker: true,
		calender_style: "picker_3",
		locale: {
			  format: 'YYYY-MM-DD',
			  separator: " - ",
		}
	});



});
//<!-- ------------------------------------------end --------------------------------------->


//------------------------------------- grid table codes --------------------------------------
/*
develped by @niloy
=>load grid with paging
=>search records
*/
$(document).ready(function () {	
	load_groups = function load_groups(search_txt){
		$("#search_group_button").toggleClass('active');
		var group_Table_length = parseInt($('#group_Table_length').val());		
		$.ajax({
			url: project_url+"controller/customerGroupController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "grid_data",
				search_txt: search_txt
			},
			success: function(data) {
				// for  showing grid's no of records from total no of records 
				//show_record_no(data.total_records )
				var records_array = data.records;
				$('#group_Table tbody tr').remove();
				$("#search_group_button").toggleClass('active');
				if(!jQuery.isEmptyObject(records_array)){
					// create and set grid table row
					var colums_array=["id*identifier*hidden","group_name","status_text"];
					// first element is for view , edit condition, delete condition
					// "all" will show /"no" will show nothing
					var condition_array=["","","all", "","",""];
					// create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
					// cauton: not posssible to use multiple grid in same page					
					create_set_grid_table_row(records_array,colums_array,condition_array,"group_permission","group_Table", 0);
					// show the showing no of records and paging for records 
					$('#group_Table_div').show();					
					// code for dynamic pagination 				
					//paging(total_pages, current_page_no, "group_Table" );					
				}
				// if the table has no records / no matching records 
				else{
					grid_has_no_result( "group_Table",3);
				}
			}
		});	
	}
	// load desire page on clik specific page no
	
	// function after click search button 
	$('#search_group_button').click(function(){
		var search_txt = $("#search_group_field").val();
		// every time current_page_no need to set to "1" if the user search from search bar
		load_groups(search_txt);		
	});
	//function after press "enter" to search	
	$('#search_group_field').keypress(function(event){
		var search_txt = $("#search_group_field").val();	
		if(event.keyCode == 13){
			load_groups(search_txt)
		}
	})
	// load data initially on page load with paging
	load_groups("");
	
	//insert action
	$('#save_group').click(function(event){  
		event.preventDefault();
		var formData = new FormData($('#group_form')[0]);
		formData.append("q","insert_or_update");
		//validation 
		if($.trim($('#group_name').val()) == ""){
			success_or_error_msg('#form_submit_error','danger',not_input_insert_title_ln,"#group_name");			
		}
		else{
			$.ajax({
				url: project_url+"controller/customerGroupController.php",
				type:'POST',
				data:formData,
				async:false,
				cache:false,
				contentType:false,processData:false,
				success: function(data){
					$('#save_group').removeAttr('disabled','disabled');
					if($.isNumeric(data)==true && data>0){
						success_or_error_msg('#form_submit_error',"success","Save Successfully");
						load_groups("");
						clear_form();
					}
				 }	
			});
			
		}	
	})
	
	edit_group_permission = function edit_group_permission(group_id){			
		$.ajax({
			url: project_url+"controller/customerGroupController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "get_group_details",
				group_id: group_id
			},
			success: function(data){
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,data){
						//alert(data.status)
						var master_id = data.id;
						$('#group_name').val(data.group_name);
						$('#group_id').val(data.id);
						
						if(data.status==1){
							$('#is_active').iCheck('check');
						}
						
						$('#save_group').html('Update');

                        if($.trim($('#toggle_form i').attr("class"))=="fa fa-chevron-down")
                            $( "#toggle_form" ).trigger( "click" );
						
					});				
				}
			}
		});	
	}

	
	clear_form = function clear_form(){			 
		$('#group_id').val('');
		$("#group_form").trigger('reset');

		$('#group_form').iCheck({
			checkboxClass: 'icheckbox_flat-green',
			radioClass: 'iradio_flat-green'
		});	
		$('#save_group').html('Save');		
	}
	
	
	$('#clear_button').click(function(){
		clear_form();
	});
	
});


//<!-- ------------------------------------------end --------------------------------------->
</script>