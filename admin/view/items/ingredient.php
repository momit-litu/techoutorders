<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
$user_type = $_SESSION['user_type'];
if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$website_url."../view/login.php");
else if($dbClass->getUserGroupPermission(57) != 1 ){
?> 
	<div class="x_panel">
		<div class="alert alert-danger" align="center">You Don't Have permission of this Page.</div>
	</div>
	<?php 
}
else{
	//echo $website_url;die;
?>


<div class="x_panel">
    <div class="x_title">
        <h2>Ingredient List</h2>
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
                <select size="1" style="width: 56px;padding: 6px;" id="ingredient_Table_length" name="ingredient_Table_length" aria-controls="ingredient_Table">
                    <option value="50" selected="selected">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                 </select> 
                 Post
             </label>
         </div>
         <div class="dataTables_filter" id="ingredient_Table_filter">         
			<div class="input-group">
                <input class="form-control" id="search_ingredient_field" style="" type="text">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-primary has-spinner" id="search_ingredient_button">
                     <span class="spinner"><i class="fa fa-spinner fa-spin fa-fw "></i></span>
                     <i class="fa  fa-search "></i>
                    </button> 
                </span>
            </div>
       </div>
       <div style="height:250px; width:100%; overflow-y:scroll">
        <table id="ingredient_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped  table-scroll ">
            <thead >
                <tr class="headings">
                    <th class="column-title" width="">Name</th>
                    <th class="column-title" width="20%">Code</th>
                    <th class="column-title" width="20%">Price</th>
					<th class="column-title no-link last" width="10%"><span class="nobr"></span></th>	
                </tr>
            </thead>
            <tbody id="ingredient_table_body" class="scrollable">              
                
            </tbody>
        </table>
        </div>
        <div id="ingredient_Table_div">
            <div class="dataTables_info" id="ingredient_Table_info">Showing <span id="from_to_limit"></span> of  <span id="total_record"></span> entries</div>
            <div class="dataTables_paginate paging_full_numbers" id="ingredient_Table_paginate">
            </div> 
        </div>  
    </div>
</div>
<?php if($dbClass->getUserGroupPermission(54) == 1){ ?>
<div class="x_panel ingredient_entry_cl">
    <div class="x_title">
        <h2>Ingredient Entry</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
				<a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" id="iniial_collapse">		
		<form method="POST"  id="ingredient_form" name="ingredient_form" enctype="multipart/form-data" class="form-horizontal form-label-left">   
			<div class="row">
				<div class="col-md-9">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-6">Name<span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-6">
							<input type="text" id="ingredient_name" name="ingredient_name" class="form-control col-lg-12"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-6">Code</label>
						<div class="col-md-6 col-sm-6 col-xs-6">
							<input type="text" id="ingredient_code" name="ingredient_code" class="form-control col-lg-12"/>
						</div>
					</div>
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-6">Price<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <input type="text" id="ingredient_price" name="ingredient_price" class="form-control col-lg-12"/>
                        </div>
                    </div>					
				</div>
				<div class="col-md-3">
					<img src="<?php echo $website_url ?>admin/images/ingredient/no_image.png" width="70%" height="70%" class="img-thumbnail" id="ingredient_img">
					<input type="file" name="ingredient_image_upload" id="ingredient_image_upload"> 
				</div>
			</div>
			<div class="form-group">
				<div class="ln_solid"></div>
				<label class="control-label col-md-4 col-sm-4 col-xs-12"></label>
				<div class="col-md-3 col-sm-3 col-xs-12">
					 <input type="hidden" id="ingredient_id" name="ingredient_id" />    
					 <button  type="submit" id="save_ingredient" class="btn btn-success">Save</button>                    
					 <button type="button" id="clear_button"  class="btn btn-primary">Clear</button>                         
				</div>
				 <div class="col-md-5 col-sm-5 col-xs-12">
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
<script src="js/autosuggest.js"></script>

<script>
//------------------------------------- general & UI  --------------------------------------
/*
develped by @momit
=>load grid with paging
=>search records
*/

console.log(project_url)

$(document).ready(function () {
	var current_page_no=1;	
	load_ingredient = function load_ingredient(search_txt){
		$("#search_ingredient_button").toggleClass('active');
		var ingredient_Table_length = parseInt($('#ingredient_Table_length').val());		
		$.ajax({
			url: project_url+"controller/ingredientController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "grid_data",
				search_txt: search_txt,
				limit:ingredient_Table_length,
				page_no:current_page_no
			},
			success: function(data) {
				if(data.entry_status==0){
					$('.ingredient_entry_cl').hide();
				}
				//for  showing grid's no of records from total no of records 
				show_record_no(current_page_no, ingredient_Table_length, data.total_records )
				
				var total_pages = data.total_pages;	
				var records_array = data.records;
				$('#ingredient_Table tbody tr').remove();
				$("#search_ingredient_button").toggleClass('active');
				if(!jQuery.isEmptyObject(records_array)){
					//create and set grid table row
					var colums_array=["id*identifier*hidden","name","code","i_price"];
					//first element is for view , edit condition, delete condition
					//"all" will show /"no" will show nothing
					var condition_array=["","","update_status", "1","delete_status","1"];
					//create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
					//cauton: not posssible to use multiple grid in same page					
					create_set_grid_table_row(records_array,colums_array,condition_array,"ingredient","ingredient_Table", 0);
					//show the showing no of records and paging for records 
					$('#ingredient_Table_div').show();					
					//code for dynamic pagination 				
					paging(total_pages, current_page_no, "ingredient_Table" );					
				}
				//if the table has no records / no matching records 
				else{
					grid_has_no_result("ingredient_Table",4);
				}
			}
		});	
	}
	// load desire page on clik specific page no
	load_page = function load_page(page_no){
		if(page_no != 0){
			// every time current_page_no need to change if the user change page
			current_page_no=page_no;
			var search_txt = $("#search_ingredient_field").val();
			load_ingredient(search_txt)
		}
	}	
	// function after click search button 
	$('#search_ingredient_button').click(function(){
		var search_txt = $("#search_ingredient_field").val();
		// every time current_page_no need to set to "1" if the user search from search bar
		current_page_no=1;
		load_ingredient(search_txt);		
	});
	//function after press "enter" to search	
	$('#search_ingredient_field').keypress(function(event){
		var search_txt = $("#search_ingredient_field").val();	
		if(event.keyCode == 13){
			// every time current_page_no need to set to "1" if the user search from search bar
			current_page_no=1;
			load_ingredient(search_txt)
		}
	})
	// load data initially on page load with paging
	load_ingredient("");
	
	//insert ingredient
	$('#save_ingredient').click(function(event){
		event.preventDefault();
		var formData = new FormData($('#ingredient_form')[0]);
		formData.append("q","insert_or_update");
		//validation 
		if($.trim($('#ingredient_name').val()) == ""){
			success_or_error_msg('#form_submit_error','danger',not_input_insert_title_ln,"#ingredient_name");			
		}
		else{
			$.ajax({
				url: project_url+"controller/ingredientController.php",
				type:'POST',
				data:formData,
				async:false,
				cache:false,
				contentType:false,processData:false,
				success: function(data){
					$('#save_ingredient').removeAttr('disabled','disabled');					
					if($.isNumeric(data)==true && data>0){
						success_or_error_msg('#form_submit_error',"success","Save Successfully");
						load_ingredient("");
						clear_form();
					}
				 }	
			});

		}	
	})
	
	//edit ingredient
	edit_ingredient = function edit_ingredient(ingredient_id){
		$.ajax({
			url: project_url+"controller/ingredientController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "get_ingredient_details",
				ingredient_id: ingredient_id
			},
			success: function(data){
			   // alert(data)
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,data){
						$('#ingredient_id').val(data.id);	
						$('#ingredient_name').val(data.name);
						$('#ingredient_code').val(data.code);                   
                        $('#ingredient_price').val(data.price);
						
						if(data.photo == ""){
							$('#ingredient_img').attr("src",project_url+'images/ingredient/no_image.png');
						}else{
							$('#ingredient_img').attr("src",project_url+'images/ingredient/'+data.photo);
						}
						
						$('#ingredient_img').attr("width", "70%","height","70%");
						
						$('#save_ingredient').html('Update');
						// to open submit post section
						if($.trim($('#toggle_form i').attr("class"))=="fa fa-chevron-down")
						$( "#toggle_form" ).trigger( "click" );	
					});				
				}
			}
		});	
	}
	
	delete_ingredient = function delete_ingredient(ingredient_id){
		if (confirm("Do you want to delete the record? ") == true) {
			$.ajax({
				url: project_url+"controller/ingredientController.php",
				type:'POST',
				async:false,
				data: "q=delete_ingredient&ingredient_id="+ingredient_id,
				success: function(data){
					if($.trim(data) == 1){
						success_or_error_msg('#page_notification_div',"success","Deleted Successfully");
						load_ingredient("");
					}
					else{
						success_or_error_msg('#page_notification_div',"danger","Not Deleted...");						
					}
				 }	
			});
		} 	
	}
	
	clear_form = function clear_form(){			 
		$('#ingredient_id').val('');
		$("#ingredient_form").trigger('reset');
		$('#ingredient_img').attr("src",project_url+"images/no_image.png");
		$('#ingredient_img').attr("width", "70%","height","70%");
		$('#save_ingredient').html('Save');
	}
	
	$('#clear_button').click(function(){
		clear_form();
	});
	
});


</script>