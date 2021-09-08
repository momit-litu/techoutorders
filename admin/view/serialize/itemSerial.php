<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
$user_type = $_SESSION['user_type'];
if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$website_url."../view/login.php");

else{
?>
	<div class="x_panel">
		<div class="x_title">
			<h2>Item Serialize</h2>
			<div class="clearfix"></div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-2 col-sm-2 col-xs-6">Category<span class="required">*</span></label>
			<div class="col-md-4 col-sm-4 col-xs-6">
				<select class="form-control" name="category_option" id="category_option">
					
				</select>
			</div>
		</div>
		<div class="x_content hide_class">
			<form method="POST" id="item_form" name="item_form" enctype="multipart/form-data" class="form-horizontal form-label-left"> 
				<table id="item_table" name="item_table" class="table table-bordered responsive-utilities jambo_table table-striped table-scroll ">
					<thead>
						<tr class="headings">
							<th class="column-title" width="">Item Name</th>
							<th class="column-title" width="8%">Serial No</th> 
						</tr>
					</thead>
					<tbody id="item_table_body" class="scrollable">              
							
					</tbody>
				</table>
			</form>
		</div>
		<div class="x_content hide_class">
			<div id="form_submit_error" class="text-center" style="display:none"></div>
			<div class="row" style="text-align:center">
				<button type="submit" id="save" class="btn btn-success">Save</button>
			</div>		
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
	
	//insert category
	$('#save').click(function(event){ 
		var category_id = $('#category_option').val();
		event.preventDefault();
		var formData = new FormData($('#item_form')[0]);
		formData.append("q","insert_update_item_serial");
		
		//submit form data
		$.ajax({
			url: project_url+"controller/serializeController.php",
			type:'POST',
			data:formData,
			async:false,
			cache:false,
			contentType:false,processData:false,
			success: function(data){
				//console.log(data)
				$('#save').removeAttr('disabled','disabled');				
				if($.isNumeric(data)==true && data>0){
					success_or_error_msg('#form_submit_error',"success","Save Successfully");
					load_item(category_id);
				} 
			 }	
		});
	})
	
	
	//load category
	load_item = function load_item(category_id){
		$('#item_table_body').html('');
		$.ajax({
			url: project_url+"controller/serializeController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "item_grid_data",
				category_id:category_id
			},
			success: function(data){
				var html = '';
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,data){
						html += "<tr><td>"+data.item_name+"</td><td><input type='text' name='serial_no[]' value='"+data.serial+"' class='form-control'/><input type='hidden' name='item_id[]' value='"+data.item_id+"'/></td><tr/>";	
					});				
				}
				//alert(html);
				$('#item_table_body').append(html);
			}
		});	
	}
	
	//load category function
	load_category = function load_category(){
		$.ajax({
			url: project_url+"controller/itemController.php",
			dataType: "json",
			type: "post",
			async:false,
			data:{
				q: "view_category",
			},
			success: function(data){
				var option_html = "";
				$('#category_option').after().html("");
				option_html += '<option value="0">Select Option</option>';
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,data){
						option_html += '<option value="'+data.id+'">'+data.category_name+'</option>';
					});
				}
				$('#category_option').after().html(option_html);
			}
		});
	}
	
	//load category on load
	load_category();
	
	//initial block item table
	if($('#category_option').val()==0){
		$('#item_table_body').html('');
		$('.hide_class').hide();
	}
	
	//change category option and load item table
	$( "#category_option" ).change(function() {
		var category_id = $(this).val();
		if(category_id == 0){
			$('#item_table_body').html('');
			$('.hide_class').hide();
		}
		else{
			$('.hide_class').show();
			load_item(category_id);
		}
	});
	
});


</script>