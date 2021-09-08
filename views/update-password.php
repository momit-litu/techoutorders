<h6 class="center">Update your Password</h6>
<hr>
<form id="customer_form" name="customer_form" enctype="multipart/form-data" class="form-horizontal form-label-left register-form">   
	<div class="row">
		<div class="col-md-8">

			<div class="form-group">
				<label class="control-label col-md-4 col-sm-4 col-xs-4 right-padding-responsive2">Old Password</label>
				<div class="col-md-8 col-sm-8  col-xs-8">
					<input type="password" id="old_password" name="old_password" class="form-control col-lg-12">
					<small id="note_pass">First you need to provide your old password</small>
				</div>
				
			</div>
			<div class="form-group">
				<label class="control-label col-md-4 col-sm-4 col-xs-4 right-padding-responsive2" >New Password</label>
				<div class="col-md-8 col-sm-8  col-xs-8">
					<input type="password" id="new_password" name="new_password" class="form-control col-lg-12"/>
				</div>
			</div>
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-4 right-padding-responsive2" >Confirm Password</label>
                <div class="col-md-8 col-sm-8  col-xs-8">
                    <input type="password" id="retype_new_password" name="retype_new_password" class="form-control col-lg-12"/>
                </div>
            </div>
			<div class="ln_solid"></div>
			
			<div id="form_submit_error" class="text-center" style="display:none"></div>
			<div class="form-group">
			&nbsp;
				<label class="control-label col-md-4 col-sm-4 col-xs-4 right-padding-responsive2">&nbsp;</label>
				<div class="col-md-8 col-sm-8  col-xs-12">		
					<input type="hidden" id="is_active" name="is_active" />
					<input type="hidden" id="customer_id" name="customer_id" />    
					<button type="submit" id="save_password_info" class="btn-medium btn-skin pull-left">Submit new Password</button>
				</div>
			</div>
		</div>

	</div>
</form>

<script>
    if (localStorage.getItem("passkey")){
        $('#note_pass').html('')
        $('#old_password').attr('disabled','disabled');
    }
   
    pass_reset = 0;

    if (localStorage.getItem("passkey")){
        pass_reset = 1;
    }


    $('#save_password_info').click(function(event){
        event.preventDefault();
        var formData = new FormData($('#customer_form')[0]);
        formData.append("q","update_password");
        formData.append("pass_reset",pass_reset);
        formData.append("password",$('#old_password').val());

        if($.trim($('#old_password').val()) == "" && pass_reset == 0){
            success_or_error_msg('#form_submit_error','danger',"Please Insert old password","#old_password");
        }
        else if($.trim($('#new_password').val()) == "" ){
            success_or_error_msg('#form_submit_error','danger',"Please Insert new password","#new_password");
        }
        else if( $.trim($('#new_password').val()) != $.trim($('#retype_new_password').val()) ){
            success_or_error_msg('#form_submit_error','danger',"New Password do not matched","#retype_new_password");
        }
        else{
            //	$('#save_password_info').attr('disabled','disabled');
            $.ajax({
                url: 'includes/controller/ecommerceController.php',
                type:'POST',
                data:formData,
                async:false,
                cache:false,
                contentType:false,processData:false,
                success: function(data){
                    $('#save_password_info').removeAttr('disabled','disabled');


                    if($.isNumeric(data)==true && data>0){
                        if (localStorage.getItem("passkey")){
                            localStorage.removeItem("passkey")
                        }
                        if(data==3){
                            success_or_error_msg('#form_submit_error',"danger","Old password did not match");
                        }
                        else{
                            success_or_error_msg('#form_submit_error',"success","Save Successfully");
                            setTimeout(function() { show_my_accounts('profile' ,'') }, 4000);
                        }
                        //set_customer_data();
                    }

                }
            });
        }
    })

</script>

