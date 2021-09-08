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
        <h2>Notification Send</h2>
        <ul class="nav navbar-right panel_toolbox">
			<li>
				<a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" id="">
        <br />       
        <form method="post" id="notification_form" name="notification_form" enctype="multipart/form-data" class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title">Customer<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="customer_name" name="customer_name" class="form-control col-lg-12"/>
					<input type="hidden" id="customer_id" name="customer_id"/>
                </div>
            </div>
			<div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title">Customer Group</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="input-group">		
						<select style="width:498px;" class="select2_multiple form-control" name="customer_group_select[]" id="customer_group_select" multiple="multiple"></select>	
					</div>		
                </div>
            </div>

			<div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title">Template Type<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="notification_type" name="notification_type" class="form-control">
						<option value="">Select Type</option>
						<option value="0">Order</option>
						<option value="1">Coupon</option>
						<option value="2">Promotional</option>
						<option value="3">Award</option>
						<option value="4">Upcoming Item</option>
					</select>
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
                     <button type="submit" id="save_notification_btn" class="btn btn-success">Save</button>
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
	
 	
	load_groups = function load_groups(){	
		$.ajax({
			url: project_url+"controller/customerGroupController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "get_customer_groups"
			},
			success: function(data){
				var option_html = '';	
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,datas){ 			
						 $.each(datas.module_group, function(i,module_group){ 
							module_group_arr = module_group.split("*");	
							option_html += '<option value="'+module_group_arr[0]+'">'+module_group_arr[1]+'</option>';								
						});
					});
					$('#customer_group_select').after().html(option_html);
				}
				$('.select2_multiple').select2({
					//maximumSelectionLength: 1,
					placeholder: "Select Groups Here",
					allowClear: true
				});
			}	
		});
	}
	
	load_groups();
	
});


$(document).ready(function () {

	var user_id = "<?php echo $_SESSION['user_id']; ?>";
		
	// save and update for public post/notice
	$('#save_notification_btn').click(function(event){	
		event.preventDefault();
		ckeditorUpdateElement();
		var formData = new FormData($('#notification_form')[0]);
		formData.append("q","insert_notification");
		//validation 
		if($.trim($('#customer_name').val()) == "" && $.trim($('#customer_group_select').val()) == ""){
			success_or_error_msg('#form_submit_error','danger','Please Select Customer or Groups'); 
		}
		else if($.trim($('#details').val()) == ""){
			success_or_error_msg('#form_submit_error','danger','Please Insert Details',"#details"); 
		}
		else if($.trim($('#notification_type').val()) == ""){
			success_or_error_msg('#form_submit_error','danger','Please Select Notification Template Type',"#notification_type"); 
		}
		else{
			$('#save_notification_btn').attr('disabled','disabled');
			var url = project_url+"controller/notificationController.php";
			$.ajax({
				url: url,
				type:'POST',
				data:formData,
				async:false,
				cache:false,
				contentType:false,processData:false,
				success: function(data){
					$('#save_notification_btn').removeAttr('disabled','disabled');
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
	
	
	
	// clear function to clear all the form value
	clear_form = function clear_form(){			 
		$('#master_id').val('');
		// clear ckeditor field code
		CKEDITOR.instances['details'].setData("")
		$('#details').val('');
		$("#notification_form").trigger('reset');
		$('#customer_group_select').select2("val", "");
		$('#save_notification_btn').html('Save');
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