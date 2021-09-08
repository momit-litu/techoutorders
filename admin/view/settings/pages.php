<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
$user_type = $_SESSION['user_type'];
if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$activity_url."../view/login.php");
else if($dbClass->getUserGroupPermission(45) != 1 ){
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
        <h2>Page List</h2>
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
                <select size="1" style="width:56px;padding:6px;" id="page_Table_length" name="page_Table_length" aria-controls="page_Table">
                    <option value="50" selected="selected">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                 </select> 
                 Post
             </label>
        </div>
         <div class="dataTables_filter" id="page_Table_filter">         
			<div class="input-group">
                <input class="form-control" id="search_page_field" style="" type="text">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-primary has-spinner" id="search_page_button">
                     <span class="spinner"><i class="fa fa-spinner fa-spin fa-fw "></i></span>
                     <i class="fa  fa-search "></i>
                    </button> 
                </span>
            </div>
       </div>
       <div style="height:250px; width:100%; overflow-y:scroll">
			<table id="page_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped table-scroll ">
				<thead>
					<tr class="headings">
						<th class="column-title" width="10%">Id</th>
						<th class="column-title" width="">Menu Name</th>
						<th class="column-title" width="">Parent Menu</th>
						<th class="column-title no-link last" width="100"><span class="nobr"></span></th>
					</tr>
				</thead>
				<tbody id="page_table_body" class="scrollable">              
					
				</tbody>
			</table>
        </div>
        <div id="page_Table_div">
            <div class="dataTables_info" id="page_Table_info">Showing <span id="from_to_limit"></span> of  <span id="total_record"></span> entries</div>
            <div class="dataTables_paginate paging_full_numbers" id="page_Table_paginate">
            </div> 
        </div>  
    </div>
</div>

<div class="x_panel">
    <div class="x_title">
        <h2>Page</h2>
        <ul class="nav navbar-right panel_toolbox">
			<li>
				<a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" id="iniial_collapse">
        <br />       
        <form method="post" id="page_form" name="page_form" enctype="multipart/form-data" class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title">Menu<span class="required">*</span></label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <input type="text" id="menu" name="menu" required class="form-control col-lg-12" />
                </div>
            </div>
			<div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title">Parent Menu</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <div class="input-group">		
						<select style="width:840px;" class="select2_multiple form-control" name="parent_menu_select" id="parent_menu_select" multiple="multiple"></select>	
					</div>		
                </div>
            </div>
			<div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title">Title<span class="required">*</span></label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <input type="text" id="title" name="title" required class="form-control col-lg-12" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="details">Details<span class="required">*</span></label>
                <div class="col-md-10 col-sm-10 col-xs-12">                 
                    <textarea type="text" id="details" name="details" required class="form-control  col-lg-12"></textarea>
                </div>
            </div>          
            <div class="ln_solid"></div>
            <div class="form-group">
              	<label class="control-label col-md-2 col-sm-2 col-xs-6"></label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                	 <input type="hidden" id="master_id" name="master_id" />
                     <button type="submit" id="save_page_btn" class="btn btn-success">Save</button>
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
	?>

<script src="js/customTable.js"></script>
<script>

$(document).ready(function () {
	

	
 	load_menu = function load_menu(){	
		$.ajax({
			url: project_url+"controller/webSiteSettingsController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "get_menus_page"
			},
			success: function(data){
				var option_html = '';	
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,datas){ 			
						 $.each(datas.module_menu, function(i,module_menu){ 
							module_menu_arr = module_menu.split("*");	
							option_html += '<option value="'+module_menu_arr[0]+'">'+module_menu_arr[1]+'</option>';								
						});
					});
					$('#parent_menu_select').after().html(option_html);
				}
				$('.select2_multiple').select2({
					maximumSelectionLength: 1,
					placeholder: "Select Menu Here",
					allowClear: true
				});
			}	
		});
	}
	
	load_menu();
	
});
	

$(document).ready(function () {	
	// initialize page no to "1" for paging
	var current_page_no=1;	
	load_data = function load_data(search_txt){
		$("#search_page_button").toggleClass('active');		 
		var page_Table_length =parseInt($('#page_Table_length').val());
		$.ajax({
			url: project_url+"controller/webSiteSettingsController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "grid_data_page",
				search_txt: search_txt,
				limit:page_Table_length,
				page_no:current_page_no
			},
			success: function(data) {
				if(data.entry_status==0){
					$('.employee_entry_cl').hide();
				}
				// for  showing grid's no of records from total no of records 
				show_record_no(current_page_no, page_Table_length, data.total_records )
				
				var total_pages = data.total_pages;	
				var records_array = data.records;
				$('#page_Table tbody tr').remove();
				$("#search_page_button").toggleClass('active');
				if(!jQuery.isEmptyObject(records_array)){
					// create and set grid table row
					var colums_array=["id*identifier", "menu", "parent_menu"];
					// first element is for view , edit condition, delete condition
					// "all" will show /"no" will show nothing
					var condition_array=["","","all", "","all",""];
					// create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
					// cauton: not posssible to use multiple grid in same page					
					create_set_grid_table_row(records_array,colums_array,condition_array,"pages","page_Table", 0);
					// show the showing no of records and paging for records 
					$('#page_Table_div').show();					
					// code for dynamic pagination 				
					paging(total_pages, current_page_no, "page_Table" );					
				}
				// if the table has no records / no matching records 
				else{
					grid_has_no_result( "page_Table",8);
				}				
			}
		});	
	}
	
	// load desire page on clik specific page no
	load_page = function load_page(page_no){
		if(page_no != 0){
			// every time current_page_no need to change if the user change page
			current_page_no=page_no;
			var search_txt = $("#search_page_field").val();
			load_data(search_txt)
		}
	}	
	// function after click search button 
	$('#search_page_button').click(function(){
		var search_txt = $("#search_page_field").val();
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
	$('#search_page_field').keypress(function(event){
		var search_txt = $("#search_page_field").val();	
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



$(document).ready(function () {

	var user_id = "<?php echo $_SESSION['user_id']; ?>";
		
	// save and update for public post/notice
	$('#save_page_btn').click(function(event){	
		event.preventDefault();
		ckeditorUpdateElement();
		var formData = new FormData($('#page_form')[0]);
		formData.append("q","insert_or_update_page");
		//validation 
		if($.trim($('#details').val()) == ""){
			success_or_error_msg('#form_submit_error','danger','Please Insert Details',"#details"); 
		}
		else if($.trim($('#menu').val()) == ""){
			success_or_error_msg('#form_submit_error','danger','Please Insert Menu',"#menu"); 
		}
		else{
			$('#save_page_btn').attr('disabled','disabled');
			var url = project_url+"controller/webSiteSettingsController.php";
			$.ajax({
				url: url,
				type:'POST',
				data:formData,
				async:false,
				cache:false,
				contentType:false,processData:false,
				success: function(data){
					$('#save_page_btn').removeAttr('disabled','disabled');
					if($.isNumeric(data)==true && data>0){
						success_or_error_msg('#form_submit_error',"success",save_success_ln); 
						clear_form();
						load_data("");
					}
					else{
						if(data == "img_error")
							success_or_error_msg('#form_submit_error',"danger",'Not Saved.. Please check attachment');
						else	
							success_or_error_msg('#form_submit_error',"danger",'Not Saved...');												
					}
				 }	
			});
		}	
	})
	
	edit_pages = function edit_pages(menu_id){	
		$.ajax({
			url: project_url+"controller/webSiteSettingsController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "get_page_details",
				menu_id: menu_id
			},
			success: function(data){
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,data){				
						$('#master_id').val(data.id);
						$('#title').val(data.title);
						$('#menu').val(data.menu);
						if(data.parent_menu_id != null){
							$('#parent_menu_select').select2().select2('val',data.parent_menu_id);
						}
						CKEDITOR.instances['details'].setData(data.description);
						
						$('#save_page_btn').html('Update');
						
						// to open submit post section
						if($.trim($('#toggle_form i').attr("class"))=="fa fa-chevron-down")
						$( "#toggle_form" ).trigger( "click" );	
					});				
				}
			}	
		});	
	}
	
	delete_pages = function delete_pages(menu_id){
	   // alert(menu_id)
		//if (confirm("Do you want to delete the record? ") == true) {
			$.ajax({
				url: project_url+"controller/webSiteSettingsController.php",
				type:'POST',
				async:false,
				data: "q=delete_menu_page&menu_id="+menu_id,
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
		//}
	}
	
	// clear function to clear all the form value
	clear_form = function clear_form(){			 
		$('#master_id').val('');
		// clear ckeditor field code
		CKEDITOR.instances['details'].setData("")
		$('#details').val('');
		$("#page_form").trigger('reset');
		$('#parent_menu_select').select2("val", "");
		$('#save_page_btn').html('Save');
	}

	// on select clear button 
	$('#clear_button').click(function(){
		clear_form();
	});
	
	// ckeditor
	var editor = CKEDITOR.replace( 'details', {
		filebrowserBrowseUrl : 'theme/ckeditor-ckfinder-integration-master/ckfinder/ckfinder.html',
		filebrowserImageBrowseUrl : 'theme/ckeditor-ckfinder-integration-master/ckfinder/ckfinder.html?type=Images',
		filebrowserFlashBrowseUrl : 'theme/ckeditor-ckfinder-integration-master/ckfinder/ckfinder.html?type=Flash',
		filebrowserUploadUrl : 'theme/ckeditor-ckfinder-integration-master/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
		filebrowserImageUploadUrl : 'theme/ckeditor-ckfinder-integration-master/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
		filebrowserFlashUploadUrl : 'theme/ckeditor-ckfinder-integration-master/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
	});
	CKFinder.setupCKEditor( editor, '../' );	

});

</script>