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
			<h2>Category Serialize</h2>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<form method="POST" id="category_form" name="category_form" enctype="multipart/form-data" class="form-horizontal form-label-left"> 
				<table id="category_table" name="category_table" class="table table-bordered responsive-utilities jambo_table table-striped table-scroll ">
					<thead>
						<tr class="headings">
							<th class="column-title" width="">Category Name</th>
							<th class="column-title" width="8%">Serial No</th> 
						</tr>
					</thead>
					<tbody id="category_table_body" class="scrollable">              
							
					</tbody>
				</table>
			</form>
		</div>
		<div class="x_content">
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
		event.preventDefault();
		var formData = new FormData($('#category_form')[0]);
		formData.append("q","insert_update_category_serial");
		
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
					load_category();
				} 
			 }	
		});
	})
	
	//load category
	load_category = function load_category(){
		$('#category_table_body').html('');
		$.ajax({
			url: project_url+"controller/serializeController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "category_grid_data"
			},
			success: function(data){
				var html = '';
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,data){
						html += "<tr><td>"+data.name+"</td><td><input type='text' name='serial_no[]' value='"+data.serial+"' class='form-control'/><input type='hidden' name='category_id[]' value='"+data.id+"'/></td><tr/>";	
					});				
				}
				//alert(html);
				$('#category_table_body').append(html);
			}
		});	
	}
	
	load_category();
	
});


</script>