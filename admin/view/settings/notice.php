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
	</div>f
	<?php 
}
else{
?>


<div class="x_panel">
    <div class="x_title">
        <h2>All Notice</h2>
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
                <select size="1" style="width: 56px;padding: 6px;" id="mynoticeTable_length" name="mynoticeTable_length" aria-controls="mynoticeTable">
                    <option value="5" selected="selected">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                 </select> 
                Page
             </label>
         </div>
         <div class="dataTables_filter" id="mynoticeTable_filter">         
			<div class="input-group">
                <input class="form-control" id="search_notice_field" style="" type="text">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-primary has-spinner" id="search_notice_button">
                     <span class="spinner"><i class="fa fa-spinner fa-spin fa-fw "></i></span>
                     <i class="fa  fa-search "></i>
                    </button> 
                </span>
            </div>
       </div>
        <table id="mynoticeTable" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped  table-scroll ">
            <thead>
				<tr class="headings">
                    <th class="column-title">TITLE</th>
                    <th class="column-title">DATE</th>
                    <th class="column-title">TYPE</th>
                    <th class="column-title">STATUS</th>
                    <th class="column-title no-link last" width="100"><span class="nobr"></span></th>                    
                </tr> 
            </thead>
            <tbody id="post_table_body">              
                
            </tbody>
        </table>
        <div id="mynoticeTable_div">
 			<div class="dataTables_info" id="mynoticeTable_info">
				Showing <span id="from_to_limit"></span> of <span id="total_record"></span> entries
            </div>
            <div class="dataTables_paginate paging_full_numbers" id="mynoticeTable_paginate">
            </div> 
        </div>  
    </div>
</div>

<div class="x_panel">
    <div class="x_title">
        <h2>Update Notice</h2>
        <ul class="nav navbar-right panel_toolbox">
			<li>
				<a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" id="iniial_collapse">
        <br />       
        <form method="post"  id="notice_post_form" name="notice_post_form" enctype="multipart/form-data" class="form-horizontal form-label-left">
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
           <div id="file_div" class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-6">Attachment</label>
                <div class="col-md-6 col-sm-6 col-xs-12" id="first_section">	              
                    <div class="input-group" >
                        <input name="attached_file[]" class="form-control input-sm col-md-6 col-xs-12"  type="file" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary btn-sm"  id="add_file_row"><span class="glyphicon glyphicon-plus"></span></button> 
                        </span>
                    </div>                                                                                  
                </div>
                <input type="text" class="tags form-control col-lg-12 hide" name="uploded_files" id="uploded_files" value="" />
            </div>
			<div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-6">Banner Image</label>
                <div class="col-md-6 col-sm-6 col-xs-12">	              
					<input name="attached_document" id="attached_document" class="form-control input-sm col-md-6 col-xs-12"  type="file" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-6">Notice Type<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control" name="notice_type" id="notice_type">
                        <option value="1">News</option>
                        <option value="2">Events</option>
                    </select>
                </div>
            </div>
			<div class="form-group" id="event_div">
                <label class="control-label col-md-2 col-sm-2 col-xs-6">Expire Event</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="expire_date" name="expire_date" readonly="readonly" placeholder="Click To Select Date" class="item form-control col-md-6 col-xs-12" type="text" />
                </div>
            </div>
            <div class="ln_solid"></div>
            <div class="form-group">
              	<label class="control-label col-md-2 col-sm-2 col-xs-6"></label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                	 <input type="hidden" id="master_id" name="master_id" />
                     <button type="submit" id="save_notice_post" class="btn btn-success">Save</button>
                     <button type="button" id="clear_button" class="btn btn-primary">Clear</button>
                </div>
                 <div class="col-md-7 col-sm-7 col-xs-12">
                 	<div id="form_submit_error" class="text-center" style="display:none"></div>
                 </div>
            </div>
        </form>  
    </div>
</div>
<?php } ?>
<script src="js/customTable.js"></script>
<script>
//------------------------------------- general & UI  --------------------------------------
/*
develped by @momit
=>load grid with paging
=>search records
*/
$(document).ready(function () {

	$('#post_tags').tagsInput({width: 'auto'});
	// add file row
	$('#add_file_row').click(function(){
		$('#first_section').children('div:last').after("<div class='input-group ' id='first_file'><input name='attached_file[]' class='form-control input-sm col-md-6 col-xs-12' type='file'><span class='input-group-btn'><button type='button' class='btn btn-danger btn-sm remove_me'  ><span class='glyphicon glyphicon-minus'></span></button></span></div> ");
		$('.remove_me').click(function(){
			$(this).parent().parent().remove();
		});
	});

	$('#expire_date').val("");
    //datepicker
    $('.item').daterangepicker({
        singleDatePicker: true,
        /*	autoUpdateInput: false,*/
        calender_style: "picker_3",
        locale: {
            format: 'YYYY-MM-DD',
            separator: " - ",
        }
    });


		
});

$(document).ready(function () {	
	// initialize page no to "1" for paging
	var current_page_no=1;	
	load_data = function load_data(search_txt){
		$("#search_notice_button").toggleClass('active');		 
		var mynoticeTable_length =parseInt($('#mynoticeTable_length').val());
		$.ajax({
			url: project_url+"controller/webSiteSettingsController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "grid_data_notice",
				search_txt: search_txt,
				limit:mynoticeTable_length,
				page_no:current_page_no
			},
			success: function(data) {
				// for  showing grid's no of records from total no of records 
				show_record_no(current_page_no, mynoticeTable_length, data.total_records )
				
				var total_pages = data.total_pages;	
				var records_array = data.records;
				$('#mynoticeTable tbody tr').remove();
				$("#search_notice_button").toggleClass('active');
				if(!jQuery.isEmptyObject(records_array)){
					// create and set grid table row
					// id,title,posted_by,post_status, post_date, tags, notice_type, status_id
					// always set the identifier column at first element of the colums_array 
					var colums_array=["id*identifier*hidden", "title","post_date*center","notice_type*center","notice_status*center"];
					// first element is for view , edit condition, delete condition
					// "all" will show /"no" will show nothing
					var condition_array=["","","all", "","all",""];

					// create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_array,module_name,table/grid id, is_checkbox to select tr );
					// cauton: not posssible to use multiple grid in same page
					
					create_set_grid_table_row(records_array,colums_array,condition_array,"notice","mynoticeTable", 0);
					// show the showing no of records and paging for records 
					$('#mynoticeTable_div').show();
					
					// code for dynamic pagination 				
					paging(total_pages, current_page_no, "mynoticeTable" );					
				}
				// if the table has no records / no matching records 
				// param @table/grid id, no of column for colspan 
				else{
					grid_has_no_result( "mynoticeTable",8);
				}				
			}
		});	
	}
	
	// load desire page on clik specific page no
	load_page = function load_page(page_no){
		if(page_no != 0){
			// every time current_page_no need to change if the user change page
			current_page_no=page_no;
			var search_txt = $("#search_notice_field").val();
			load_data(search_txt)
		}
	}	
	// function after click search button 
	$('#search_notice_button').click(function(){
		var search_txt = $("#search_notice_field").val();
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
	$('#search_notice_field').keypress(function(event){
		var search_txt = $("#search_notice_field").val();	
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
	//load_user_groups("");
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
	var user_id = "<?php echo $_SESSION['user_id']; ?>";
		
	// save and update for public post/notice
	$('#save_notice_post').click(function(event){		
		event.preventDefault();
		ckeditorUpdateElement();
		var formData = new FormData($('#notice_post_form')[0]);
		formData.append("q","insert_or_update_notice");
		//validation 
		if($.trim($('#title').val()) == ""){
			success_or_error_msg('#form_submit_error','danger',not_input_insert_title_ln,"#title");			
		}
		else if($.trim($('#details').val()) == ""){
			success_or_error_msg('#form_submit_error','danger',not_input_insert_details_ln,"#details"); 
		}
		else{
			$('#save_notice_post').attr('disabled','disabled');
			var url = project_url+"controller/webSiteSettingsController.php";
			$.ajax({
				url: url,
				type:'POST',
				data:formData,
				async:false,
				cache:false,
				contentType:false,processData:false,
				success: function(data){
					$('#save_notice_post').removeAttr('disabled','disabled');
					if($.isNumeric(data)==true && data>0){
					    //alert('ok1')
						success_or_error_msg('#form_submit_error',"success",save_success_ln); 
						clear_form();
						current_page_no=1;
						load_data("");
						//$( "#toggle_form" ).trigger( "click" );
					}
					else{
					    //alert(data)
						if(data == "img_error")
							success_or_error_msg('#form_submit_error',"danger",not_saved_msg_for_attachment_ln);
						else	
							success_or_error_msg('#form_submit_error',"danger",not_saved_msg_for_input_ln);												
					}
				 }	
			});
		}	
	})
	
	// clear function to clear all the form value
	clear_form = function clear_form(){			 
		$('#master_id').val('');
		// clear ckeditor field code
		CKEDITOR.instances['details'].setData("")
		$('#details').val('');
		$("#notice_post_form").trigger('reset');
		$('#event_div').hide();
		$("input[name='attached_file[]']:gt(0)").parent().remove();
		$('#save_notice_post').html('Save');
	}
	
	// on select clear button 
	$('#clear_button').click(function(){
		clear_form();
	});
	
	$('#event_div').hide(); 
    $('#notice_type').change(function(){
        if($('#notice_type').val() == 2) {
			$('#event_div').show();
        }else {
            $('#event_div').hide(); 
        } 
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
	
	edit_notice = function edit_notice(notice_id){
		$.ajax({
			url: project_url+"controller/webSiteSettingsController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "get_notice_details_notice",
				notice_id: notice_id
			},
			success: function(data){
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,data){
						$('#uploded_files_tagsinput').remove();	
						var master_id = data.id;						
						CKEDITOR.instances['details'].setData(data.details)					
						$('#expire_date').val(data.expire_date);						
						$('#master_id').val(data.id);
						$('#title').val(data.title);
						$('#notice_type').val(data.type);
						
						$('#save_notice_post').html('Update');
						
						// to open submit post section
						if($.trim($('#toggle_form i').attr("class"))=="fa fa-chevron-down")
						$( "#toggle_form" ).trigger( "click" );	
						
						//alert(data.attachment);
						if($.trim(data.attachment) != ""){	
							$('#uploded_files').val(data.attachment);
							$('#uploded_files').tagsInput({
								width: 'auto', 
								onRemoveTag:function(fie_name){
									if (confirm("Do you want to delete the attached file"+ fie_name +"? ") == true) {
										$.ajax({
											url: project_url+"controller/webSiteSettingsController.php",
											type:'POST',
											async:false,
											data: "q=delete_attached_file_notice&master_id="+master_id+"&file_name="+fie_name,
											success: function(data){
												if($.trim(data) == 1){
													success_or_error_msg('#form_submit_error',"success",delete_msg_ln);
												}
												else{
													success_or_error_msg('#form_submit_error',"danger",not_delete_msg_ln);	
													return false;					
												}
											 }	
										});
									} 		
								}
							});
							$('#uploded_files_tag').css("display","none");
						}
					});				
				}
			}	
		});
	}
	
	delete_notice = function delete_notice(notice_id){
		if (confirm("Do you want to delete the record? ") == true) {
			$.ajax({
				url: project_url+"controller/webSiteSettingsController.php",
				type:'POST',
				async:false,
				data: "q=delete_notice&notice_id="+notice_id,
				success: function(data){
					if($.trim(data) == 1){
						success_or_error_msg('#page_notification_div',"success",delete_msg_ln); 
						load_data("");
					}
					else{
						success_or_error_msg('#page_notification_div',"danger",not_delete_msg_ln);			 			
					}
				 }	
			});
		} 	
	}
	
	
});

</script>