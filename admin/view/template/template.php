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
        <h2>Template List</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
				<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
    	<div id="template_notification_div" class="text-center" style="display:none"></div>
    	<div class="dataTables_length">
        	<label>Show 
                <select size="1" style="width:56px;padding:6px;" id="template_Table_length" name="template_Table_length" aria-controls="template_Table">
                    <option value="50" selected="selected">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                 </select> 
                 Post
             </label>
        </div>
         <div class="dataTables_filter" id="template_Table_filter">         
			<div class="input-group">
                <input class="form-control" id="search_template_field" style="" type="text">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-primary has-spinner" id="search_template_button">
                     <span class="spinner"><i class="fa fa-spinner fa-spin fa-fw "></i></span>
                     <i class="fa  fa-search "></i>
                    </button> 
                </span>
            </div>
       </div>
       <div style="height:250px; width:100%; overflow-y:scroll">
			<table id="template_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped table-scroll ">
				<thead>
					<tr class="headings">
						<th class="column-title" width="30%">Template Title</th>
						<th class="column-title" width="">Details</th>
						<th class="column-title" width="12%">Template Type</th>
						<th class="column-title no-link last" width="100"><span class="nobr"></span></th>
					</tr>
				</thead>
				<tbody id="template_table_body" class="scrollable">              
					
				</tbody>
			</table>
        </div>
        <div id="template_Table_div">
            <div class="dataTables_info" id="template_Table_info">Showing <span id="from_to_limit"></span> of  <span id="total_record"></span> entries</div>
            <div class="dataTables_paginate paging_full_numbers" id="template_Table_paginate">
            </div> 
        </div>  
    </div>
</div>

<div class="x_panel">
    <div class="x_title">
        <h2>Template Entry</h2>
        <ul class="nav navbar-right panel_toolbox">
			<li>
				<a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" id="iniial_collapse">
        <br />       
        <form method="post" id="template_form" name="template_form" enctype="multipart/form-data" class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Template Type<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="radio" class="flat_radio" name="type" value="1"/> Notice
					<input type="radio" class="flat_radio" name="type" value="2"/> Email
                </div>
            </div>
			<div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title">Template Name<span class="required">*</span></label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <input type="text" id="title" name="title" class="form-control col-lg-12" />
                </div>
            </div>
			<div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Dynamic Variables</label>
				<div class="col-md-10 col-sm-10 col-xs-12">
					<small style="color:red" >
						Dynamic Variables: avoid to edit 
					</small>
                    <input type="text" id="dynamic_variables" name="dynamic_variables" placeholder="[ORDER_NO] [CUSTOMER_NAME] [CUPON_NUMBER]" class="form-control col-lg-12" />
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
                     <button type="submit" id="save_template_btn" class="btn btn-success">Save</button>
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

	// initialize page no to "1" for paging
	var current_template_no=1;	
	load_data = function load_data(search_txt){
		$("#search_template_button").toggleClass('active');		 
		var template_Table_length =parseInt($('#template_Table_length').val());
		$.ajax({
			url: project_url+"controller/templateController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "grid_data",
				search_txt: search_txt,
				limit:template_Table_length,
				page_no:current_template_no
			},
			success: function(data) {
				// for  showing grid's no of records from total no of records 
				show_record_no(current_template_no, template_Table_length, data.total_records )
				
				var total_template = data.total_template;	
				var records_array = data.records;
				$('#template_Table tbody tr').remove();
				$("#search_template_button").toggleClass('active');
				if(!jQuery.isEmptyObject(records_array)){
					// create and set grid table row
					var colums_array=["id*identifier*hidden", "title", "details", "type_text"];
					// first element is for view , edit condition, delete condition
					// "all" will show /"no" will show nothing
					var condition_array=["","","all", "","all",""];
					// create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
					// cauton: not posssible to use multiple grid in same page					
					create_set_grid_table_row(records_array,colums_array,condition_array,"template","template_Table", 0);
					// show the showing no of records and paging for records 
					$('#template_Table_div').show();					
					// code for dynamic pagination 				
					paging(total_template, current_template_no, "template_Table" );					
				}
				// if the table has no records / no matching records 
				else{
					grid_has_no_result( "template_Table",5);
				}				
			}
		});	
	}
	
	// load desire page on clik specific page no
	load_page = function load_page(template_no){
		if(template_no != 0){
			// every time current_template_no need to change if the user change page
			current_template_no=template_no;
			var search_txt = $("#search_template_field").val();
			load_data(search_txt)
		}
	}	
	// function after click search button 
	$('#search_template_button').click(function(){
		var search_txt = $("#search_template_field").val();
		// every time current_template_no need to set to "1" if the user search from search bar
		current_template_no=1;
		load_data(search_txt)
		// if there is lot of data and it tooks lot of time please add the below condition
		/*
		if(search_txt.length>3){
			load_data(search_txt)	
		}
		*/
	});
	//function after press "enter" to search	
	$('#search_template_field').keypress(function(event){
		var search_txt = $("#search_template_field").val();	
		if(event.keyCode == 13){
			// every time current_template_no need to set to "1" if the user search from search bar
			current_template_no=1;
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
	$('#save_template_btn').click(function(event){	
		event.preventDefault();
		ckeditorUpdateElement();
		var formData = new FormData($('#template_form')[0]);
		formData.append("q","insert_or_update");
		//validation 
		if($.trim($('#title').val()) == ""){
			success_or_error_msg('#form_submit_error','danger','Please Insert Template Title',"#title"); 
		}
		else if($.trim($('#details').val()) == ""){
			success_or_error_msg('#form_submit_error','danger','Please Insert Details',"#details"); 
		}
		else{
			$('#save_template_btn').attr('disabled','disabled');
			var url = project_url+"controller/templateController.php";
			$.ajax({
				url: url,
				type:'POST',
				data:formData,
				async:false,
				cache:false,
				contentType:false,processData:false,
				success: function(data){
					$('#save_template_btn').removeAttr('disabled','disabled');
					if($.isNumeric(data)==true && data>0){
						success_or_error_msg('#form_submit_error',"success",save_success_ln); 
						clear_form();
						load_data("");
					}
					else{
						success_or_error_msg('#form_submit_error',"danger",'Not Saved...');													
					}
				 }	
			});
		}	
	})
	
	
	edit_template = function edit_template(template_id){	
		$.ajax({
			url: project_url+"controller/templateController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "get_template_details",
				template_id: template_id
			},
			success: function(data){
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,data){				
						$('#master_id').val(data.id);	
						$('input:radio[name=type][value='+data.type+']').prop('checked', true);	
						$('#title').val(data.title);						
						CKEDITOR.instances['details'].setData(data.details);						
						$('#save_template_btn').html('Update');						
						// to open submit post section
						if($.trim($('#toggle_form i').attr("class"))=="fa fa-chevron-down")
						$( "#toggle_form" ).trigger( "click" );	
					});				
				}
			}	
		});	
	}
	
	
	// clear function to clear all the form value
	clear_form = function clear_form(){			 
		$('#master_id').val('');
		// clear ckeditor field code
		CKEDITOR.instances['details'].setData("")
		$('#details').val('');
		$("#template_form").trigger('reset');
		$('#save_template_btn').html('Save');
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