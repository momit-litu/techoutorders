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
        <h2>Gallary Images</h2>
        <ul class="nav navbar-right panel_toolbox">
			<li>
				<a class="collapse-link" id="toggle_form"><i class="fa fa-chevron-down"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" >
        <br />       
        <form method="post"  id="gallary_form" name="gallary_form" enctype="multipart/form-data" class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Search By Album Name</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <!--input type="text" id="album_search" name="album_search" required class="form-control col-lg-12" />
					<input type="hidden" id="album_id" name="album_id"/-->
                    <div class="input-group" >

                        <select class="form-control input-sm col-md-6 col-xs-12" name="album_search" id="album_search">
                            <option value="0">Select Album</option>
                        </select>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary btn-sm" id="add_album_row"><span class="glyphicon glyphicon-plus"></span></button>
                        </span>
                    </div>
                </div>
            </div>
			<div class="form-group" id="album_name_div" style="display: none">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Album Name<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="album_name" name="album_name" required class="form-control col-lg-12" />
                </div>
            </div>
			<div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Image Title<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="title" name="title" required class="form-control col-lg-12" />
                </div>
            </div>      
			<div id="file_div" class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-6">Attachment</label>
                <div class="col-md-6 col-sm-6 col-xs-12" id="first_section">	              
                    <div class="input-group" >
                        <input name="attached_file[]" class="form-control input-sm col-md-6 col-xs-12"  type="file" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary btn-sm" id="add_file_row"><span class="glyphicon glyphicon-plus"></span></button> 
                        </span>
                    </div>
					<small style="color:red">Image size should be (4:3 ratio) and size under 3mb. </small><br>
				
                </div>
                <input type="text" class="tags form-control col-lg-12 hide" name="uploded_files" id="uploded_files" value=""/>
            </div>
			<div class="form-group" id="gallary_div">
				
			</div>
            <div class="form-group">
				<div class="ln_solid"></div>
              	<label class="control-label col-md-3 col-sm-3 col-xs-6"></label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                	 <input type="hidden" id="master_id" name="master_id" />
                     <button type="submit" id="save_gallary_btn" class="btn btn-success">Save</button>
                     <button type="button" id="clear_button" class="btn btn-primary">Clear</button>
                </div>
                 <div class="col-md-6 col-sm-6 col-xs-12">
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
=>auto suggest
*/
$(document).ready(function () {
	
	// add file row
	$('#add_file_row').click(function(){
		$('#first_section').children('div:last').after("<div class='input-group' id='first_file'><input name='attached_file[]' class='form-control input-sm col-md-6 col-xs-12' type='file'><span class='input-group-btn'><button type='button' class='btn btn-danger btn-sm remove_me'  ><span class='glyphicon glyphicon-minus'></span></button></span></div> ");
		$('.remove_me').click(function(){
			$(this).parent().parent().remove();
		});		
	});

    $.ajax({
        url: project_url+'controller/webSiteSettingsController.php',
        dataType: "json",
        type: "post",
        async:false,
        data: {
            q: "album_name",
        },
        success: function(data) {
            html = '';
            $.each(data, function (key, name) {
                html+='<option value="'+name["id"]+'">'+name["album_name"]+'</option>';
            })
            $('#album_search').append(html)
        }
    });
    $('#add_album_row').click(function () {
        $('#album_name_div').css('display','block')
    })
    $('#album_search').on('change', function () {
        //alert($('#album_search').val())
        load_gallary_data($('#album_search').val());

    })


    /*Auto Suggest For Title*/
    /*
	$("#album_search").autocomplete({
        search: function() {
        },
        source: function(request, response) {
            $.ajax({
                url: project_url+'controller/webSiteSettingsController.php',
                dataType: "json",
                type: "post",
				async:false,
                data: {
                    q: "album_name",
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 3,
        select: function(event, ui){ 
			var id = ui.item.id;
			$('#album_id').val(id);
			load_gallary_data(id);
		}
	});

     */
	
});
	

$(document).ready(function () {
	var user_id = "<?php echo $_SESSION['user_id']; ?>";
		
	// save and update for public post/notice
	$('#save_gallary_btn').click(function(event){		
		event.preventDefault();
		ckeditorUpdateElement();
		var formData = new FormData($('#gallary_form')[0]);
		formData.append("q","insert_or_update_image");
		//validation 
		if($.trim($('#title').val()) == ""){
			success_or_error_msg('#form_submit_error','danger',not_input_insert_title_ln,"#title");			
		}
		else if($.trim($('#album_name').val()) == "" && $.trim($('#album_search').val()) == 0){
			success_or_error_msg('#form_submit_error','danger',not_input_insert_album_ln,"#album_name");			
		}
		else{
			$('#save_gallary_btn').attr('disabled','disabled');
			var url = project_url+"controller/webSiteSettingsController.php";
			$.ajax({
				url: url,
				type:'POST',
				data:formData,
				async:false,
				cache:false,
				contentType:false,processData:false,
				success: function(data){
					$('#save_gallary_btn').removeAttr('disabled','disabled');
					if($.isNumeric(data)==true && data>0){
						success_or_error_msg('#form_submit_error',"success",save_success_ln); 
						clear_form();			
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
		$('#album_id').val('');		
		$("#gallary_form").trigger('reset');
		$('#uploded_files_tagsinput').remove();
		$("input[name='attached_file[]']:gt(0)").parent().remove();
		$('#uploded_files_tagsinput').remove();
		$('#save_gallary_btn').html('Save');
		$('#gallary_div').html('');
	}
	

	// on select clear button 
	$('#clear_button').click(function(){
		clear_form();
	});

	load_gallary_data = function load_gallary_data(master_id){
		$('.img_label').after('');
		var attachement_html = "";
		var url = project_url+"controller/webSiteSettingsController.php";
		$.ajax({
			url: url,
			dataType: "json",
			type: "post",
			async:false,
			data:{
				q: "get_album_details",
				master_id: master_id
			},
			success: function(data){ 
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,data){  	
						$('#master_id').val(data.album_id);					
						$('#album_name').val(data.album_name);					
						$('#title').val(data.title);							
					});
				}
				
				$('#gallary_div').html('<label class="control-label col-md-3 col-sm-3 col-xs-6 img_label"></label>');	
				if(!jQuery.isEmptyObject(data.attachment)){
					attachement_html   = '<div class="col-md-12">';
					$.each(data.attachment, function(i,data){				
						if($.trim(data) != ""){
							attachement_html   += '<div class="col-md-2 text-center" id="gallary_image_id_'+data.img_id+'"><img src="'+project_url+"document/gallary_attachment/"+$.trim(data.attachment)+'" class="img-thumbnail"><button type="button" class="btn btn-danger btn-xs remove_img" onclick="delete_gallary_image('+data.img_id+','+master_id+')"><span class="glyphicon glyphicon-remove"></span></button></div>';
							$('.remove_img').click(function(){
								$(this).parent().parent().remove();
							});	
						}
					});
					attachement_html   += '</div>';					
					$('.img_label').after(attachement_html);						
				}
				
				//change button value 
				$('#save_gallary_btn').html('Update');
			}	
		});			
	} 
	
	delete_gallary_image = function delete_gallary_image(img_id, master_id){
		var url = project_url+"controller/webSiteSettingsController.php";
		$.ajax({
			url: url,
			type:'POST',
			async:false,
			data: "q=delete_attached_image_file&img_id="+img_id,
			success: function(data){
				if($.isNumeric(data)==true && data>0){
					success_or_error_msg('#form_submit_error',"success","Deleted Successfully");
					//clear_form();
					$('#gallary_image_id_'+img_id).hide();
				}
				else{
					success_or_error_msg('#form_submit_error',"danger","Not Deleted...");						
				}
			 }	
		});
	}
	
});

</script>