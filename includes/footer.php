  <!-- Start Footer -->
        <footer>
		
            <div class="footer-part wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                <div class="icon-default icon-dark">
                    <img src="/images/logo.png" alt="" style="background-color:#FFF" >
                </div>
				<div class="col-md-12 center">
					<div class="social-round">
						<ul>
							<li><a href="index.php">Home</a></li>
							<li><a href="news.php">News</a></li>																
							<li><a href="terms.php" style="width:130px" >Terms & Condition</a></li>
							<li><a href="privacy.php">Privacy</a></li>
							<li><a href="refund.php" style="width:100px" >Refund Policy</a></li>
							
							<!-- <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
							<li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
							<li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
							<li><a href="#"><i class="fa fa-google" aria-hidden="true"></i></a></li>
							-->
						</ul>
					</div>
				</div>
                <div class="container">	
					<div class="footer-inner">
                        <div class="footer-info">
                            <div class="social-round">
								<ul>										
									<li><a href="<?php echo $facebook; ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
									<li><a href="<?php echo $twitter; ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
									<li><a href="<?php echo $instagram; ?>"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
									<li><a href="<?php echo $googleplus; ?>"><i class="fa fa-google" aria-hidden="true"></i></a></li>
								</ul>
							</div>
							<br><br>
							<div class="col-md-12 center">
								<span style="color:#fcf8bb">Copyright &#169; 2016 Cakencookie. All rights reserved.</span>   
							</div>
							<div class="col-md-12 center"><span class="small" >Designed and developed by <a href="www.mbrotherssolution.com" style="color:#fcf8bb">Mbrotherssolution</a></span></div> 
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer -->
		
        <!-- Start custom order -->
        <div class="modal fade booktable" id="booktable" tabindex="-1" role="dialog" aria-labelledby="booktable">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
						<form class="form" method="post" name="custome-cake-form" id="custome-cake-form">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<div class="table-title">
								<h2>Custom Cake</h2>
								<h6 class="heade-xs">Here you can order your dream cake</h6>
							</div>
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="cc_cake_weight" id="cc_cake_weight" placeholder="Weight in KG" required class="form-control">
								</div>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<select class="select-dropbox" name="cc_cake_tyre" id="cc_cake_tyre">
										<option value ='0'>Tier</option>
										<option value ='1'>1</option>
										<option value ='2'>2</option>
										<option value ='3'>3</option>
										<option value ='4'>4</option>
										<option value ='5'>5</option>
									</select>
								</div>  
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="cc_delevery_date" id="cc_delevery_date" placeholder="Pickup/Delevery Date" class="date-picker">
								</div>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input name="cc_image_upload"  class="form-control col-md-6 col-xs-12 "  type="file" style="border-radius: 10px !important; height: 50px !important;" />
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<textarea name="cc_details" id="cc_details" placeholder="Details"  required class="form-control  col-md-6 col-xs-12 " style="border-radius: 10px !important;" ></textarea>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<input type="text" name="cc_name" id="cc_name" placeholder="Name" required class="form-control  col-md-6 col-xs-12 ">
								</div>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="cc_mobile" id="cc_mobile" placeholder="Phone Number" required class="form-control  col-md-6 col-xs-12 ">
								</div>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="email" name="cc_email" id="cc_email" placeholder="Email Address" required class="form-control  col-md-6 col-xs-12 ">
								</div>								
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div id="cc_submit_error" class="text-center" style="display:none"></div>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<input name="submit" id="cc_submit" value="ORDER CUSTOM CAKE" class="btn-black pull-right send_message" type="submit">
								</div>
							</div>
						</form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Book Table -->
		
		<!-- Start login modal -->
        <div class="modal fade booktable" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="booktable">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
						<div id="login-div" class="">
							<div id="done_login">
								<div class="title text-center">
									<h3 class="text-coffee">Login</h3>
								</div>
								<form class="login-form" method="post" name="login-form" id="login-form">
									<div class="row">
										<div >
											<div class="col-md-12 col-sm-12 col-xs-12">
												<input type="text" name="username" id="username" placeholder="Username or email address" class="input-fields" required >
											</div>
											<div class="col-md-12 col-sm-12 col-xs-12">
												<input type="password" name="password" id="password" placeholder="********" class="input-fields" required >
											</div>
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="row">
													<div class="col-md-6 col-sm-6 col-xs-12">
														<label>
															<input type="checkbox" name="chkbox">Remember me</label>
													</div>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<a href="javascript:void(0)" onclick="active_modal(2)"class="pull-right" data-toggle="modal" data-target="#forget_passModal" id="send_password"><i class="fa fa-user" aria-hidden="true"></i> Lost your password?</a>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div id="login_submit_error" class="text-center" style="display:none"></div>
											<input type="submit" name="submit" id="login_submit" value="LOGIN" class="button-default button-default-submit">
										</div>
									</div>
								</form>
								<div class="divider-login">
									<hr>
									<span>Or</span>
								</div>
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<a href="javascript:void(0)" onclick="active_modal(3)" class="facebook-btn btn-change button-default " id="log_reg"><i class="fa fa-user" aria-hidden="true"></i> Dont have an account? Register yourself</a>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12 center hide" 	id="done_login_msg" >
								<div class="alert alert-success alert-custom">
									<p>You have logged in successfully</p>
								</div>
								<a href="index.php?page=account" id="" class="facebook-btn btn-change button-default"><i class="fa fa-user"></i>Browse your account?</a>
							</div>							
						</div>
					</div>
                </div>
            </div>
        </div>		
        <!-- End login -->
		
		<!-- register modal -->
			<div class="modal fade booktable" id="registerModal" tabindex="-2" role="dialog" aria-labelledby="booktable">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
						<div id="register-div">
							<div class="title text-center">
								<h3 class="text-coffee">Register</h3>
							</div>
							<div class="done_registration">
								<form class="register-form" method="post" name="register-form" id="register-form"> 
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input type="text" name="cust_name" id="cust_name" placeholder="Name" class="input-fields" required>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input type="text" name="cust_username" id="cust_username" placeholder="User Name" class="input-fields" required>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input type="email" name="cust_email" id="cust_email" placeholder="Email address" class="input-fields" required>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input type="password" name="cust_password" id="cust_password" placeholder="Password" class="input-fields" required>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input type="password" name="cust_conf_password" id="cust_conf_password"  placeholder="Confirm Password" class="input-fields" required>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input type="number" name="cust_contact" id="cust_contact" pattern="[0-9]{11}" placeholder="Contact No" class="input-fields" required>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input type="text" name="cust_address" id="cust_address" placeholder="Address" class="input-fields" >
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
										
											<div id="registration_submit_error" class="text-center" style="display:none"></div>
											<input type="submit" name="submit" id="register_submit" class="button-default button-default-submit" value="Register now">
										</div>
									</div>
								</form>
								<p>By clicking on <b>Register Now</b> button you are accepting the <a href="terms.php">Terms &amp; Conditions</a></p>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12 done_registration_msg center hide" >
								<div class="alert alert-success">
									<p>Your registration is completed. Please login with provided credentials</p>
								</div>
								<a href="javascript:void(0)" onclick="active_modal(1)" class="facebook-btn btn-change button-default " data-toggle="modal" data-target="#loginModal" id="do_login"><i class="fa fa-user" aria-hidden="true"></i> Login</a>
							</div>							
						</div>
                    </div>
                </div>
            </div>
        </div>
		<!-- END REGISTER MODAL -->
		
		<!-- Start forgetr pass modal -->
        <div class="modal fade booktable" id="forget_passModal" tabindex="-1" role="dialog" aria-labelledby="booktable">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
						<div id="forget-pass-div">
							<div class="title text-center">
								<h3 class="text-coffee">Enter email address</h3>
							</div>
							<form class="register-form" method="post" name="forget-pass-form" id="forget-pass-form">
								<div class="row">
									<div class="sent_password">
										<div class="col-md-12 col-sm-12 col-xs-12 ">
											<input type="email" name="forget_email" id="forget_email" placeholder="Enter email address" class="input-fields">
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12 ">
											<div id="foget_pass_submit_error" class="text-center" style="display:none"></div>
											<input type="submit" name="submit" id="foget_pass_submit"  class="button-default button-default-submit" value="Send Password">
										</div>
									</div>
									<div class="col-md-12 col-sm-12 col-xs-12 sent_password_msg center hide" >
										<div class="alert alert-success">
											<p>A new password has been sent to your provided email address. please check and login</p>
										</div>
										<a href="javascript:void(0)" onclick="active_modal(1)" class="facebook-btn btn-change button-default " data-toggle="modal" data-target="#loginModal" id="do_login"><i class="fa fa-user" aria-hidden="true"></i> Login</a>
									</div>								
								</div>
							</form>
						</div>							
					</div>
                </div>
            </div>
        </div>		
        <!-- End login -->
		
		
	<!-- Start Order details -->
	<div class="modal fade booktable" id="order_modal" tabindex="-2" role="dialog" aria-labelledby="booktable">
            <div class="modal-dialog" role="document" style="width:80% !important">
                <div class="modal-content">
                    <div class="modal-body">
						<div id="order-div">
							<div class="title text-center">
								<h3 class="text-coffee left"> <a href="index.php"><img src="/images/logo.png" alt=""></a></h3>
								<h4 class="text-coffee left">Order No # <span id="ord_title_vw"></span></h4>
							</div>
							<div class="done_registration ">							    
								<div class="doc_content">
									<div class="col-md-12">
										<div class="col-md-6">
											<h4>Order Details:</h4>				
											<div class="byline">
												<span id="ord_date"></span><br/> 
												<span id="dlv_date"></span> <br/> 
												<span id="dlv_ps"></span> <br/> 
												<span id="dlv_pm"></span> 
											</div>	
										</div>
										<div class="col-md-6" style="text-align:right">
											<h4>Customer Details:</h4> 								
											<address id="customer_detail_vw">
											</address>
										</div>
									</div>
									<div id="ord_detail_vw"> 
										<table class="table table-bordered" >
											<thead>
												<tr>
													<th align="center">Product</th>
													<th width="18%" align="center">Size</th>
													<th width="10%" align="center">Quantity</th>
													<th width="18%" style="text-align:right">Rate</th>                           
													<th width="18%"  style="text-align:right">Total</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
										<p>Note: <span id="note_vw"></span></p>
										<p>Print Time : <?php echo date("Y-m-d h:m:s"); ?></p>
										<br />
										<p style="font-weight:bold; text-align:center">Thank you. Hope we will see you soon </p>
									</div> 
								</div>									
							</div>							
						</div>
						<div class="col-md-12 center"> <button type="button" class="btn btn-warning" id="order_print"><i class="fa fa-lg fa-print"></i></button></div>
                    </div>
                </div>
            </div>
        </div>		
        <!-- End order -->
    </div>
    <!-- Back To Top Arrow -->
    <a href="#" class="top-arrow"></a>
    <script src="/js/jquery.min.js"></script>
    <script src="/plugin/bootstrap/bootstrap.min.js"></script>
    <script src="/plugin/bootstrap/bootstrap-datepicker.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAf6My1Jfdi1Fmj-DUmX_CcNOZ6FLkQ4Os"></script>
    <script src="/plugin/form-field/jquery.formstyler.min.js"></script>
    <script src="/plugin/revolution-plugin/jquery.themepunch.plugins.min.js"></script>
    <script src="/plugin/revolution-plugin/jquery.themepunch.revolution.min.js"></script>
    <script src="/plugin/owl-carousel/owl.carousel.min.js"></script>
    <script src="/plugin/slick-slider/slick.min.js"></script>
    <script src="/plugin/isotop/isotop.js"></script>
    <script src="/plugin/isotop/packery-mode.pkgd.min.js"></script>
    <script src="/plugin/magnific/jquery.magnific-popup.min.js"></script>
    <script src="/plugin/scroll-bar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="/plugin/animation/wow.min.js"></script>
    <script src="/plugin/parallax/jquery.stellar.js"></script>
    <script src="/js/app.js"></script>
    <script src="/js/script.js"></script>
	
	
	<!-- daterangepicker     -->
<script type="text/javascript" src="/admin/theme/js/moment/moment.min.js"></script>
<script type="text/javascript" src="/admin/theme/js/datepicker/daterangepicker.js"></script>
</body>

<script>

function active_modal(type){
	if(type==1){
		$('#forget_passModal').modal('hide');
		$('#registerModal').modal('hide');
	}
	else if(type==2){
		$('#loginModal').modal('hide');
		$('#registerModal').modal('hide');
	}
	else if(type==3){		
		$('#loginModal').modal('hide');
		setTimeout(function(){ 
			$('#registerModal').modal();
		}, 400);
		
		
	}		
}

//login validation and complete login
$('#login_submit').click(function(event){		
	event.preventDefault();
	var formData = new FormData($('#login-form')[0]);
	formData.append("q","login_customer");
	if($.trim($('#username').val()) == ""){
		success_or_error_msg('#login_submit_error','danger',"Please type user name","#emp_name");			
	}
	if($.trim($('#password').val()) == ""){
		success_or_error_msg('#login_submit_error','danger',"Please type password","#password");			
	}
	else{
		$.ajax({
			url: "includes/controller/customerController.php",
			type:'POST',
			data:formData,
			async:false,
			cache:false,
			contentType:false,processData:false,
			success: function(data){
				if($.isNumeric(data)==true && data==3){
					success_or_error_msg('#login_submit_error',"danger","Invalid username","#user_name" );			
				}
				else if($.isNumeric(data)==true && data==2){
					success_or_error_msg('#login_submit_error',"danger","Invalid password","#password" );			
				}
				else if($.isNumeric(data)==true && data==1){
					$('#done_login').addClass("hide");
					$('#done_login_msg').removeClass("hide");
					$('.language-menu').html('<a href="'+project_url+'index.php?page=account" class="current-lang" id="my_acc"><i class="fa fa-user" aria-hidden="true" ></i> My Account</a>');
					if($('#islogged_in').length > 0 ){
						$('#islogged_in').val(1);
						$('.logged_in_already').addClass('hide');
					}					
				}
			 }	
		});
	}	
})



//send mail to cakencookies from contact page
$('#contact_submit').click(function(event){		
	event.preventDefault();
	var formData = new FormData($('#contact-form')[0]);
	formData.append("q","contact_us_mail");
	if($.trim($('#first_name').val()) == ""){
		success_or_error_msg('#contact_submit_error','danger',"Please type name","#first_name");			
	}
	else if($.trim($('#email').val()) == ""){
		success_or_error_msg('#contact_submit_error','danger',"Please type email","#email");			
	}
	else if($.trim($('#mobile').val()) == ""){
		success_or_error_msg('#contact_submit_error','danger',"Please enter mobile no.","#mobile");			
	}
	else if($.trim($('#subject').val()) == ""){
		success_or_error_msg('#contact_submit_error','danger',"Please type subject.","#subject");			
	}
	else if($.trim($('#message').val()) == ""){
		success_or_error_msg('#contact_submit_error','danger',"Please type message.","#message");			
	}
	else{
		$.ajax({
			url: "includes/controller/customerController.php",
			type:'POST',
			data:formData,
			async:false,
			cache:false,
			contentType:false,processData:false,
			success: function(data){
				if($.isNumeric(data)==true && data==1){
					success_or_error_msg('#contact_submit_error',"success","Mail has sent","" );	
					$('#contact-form')[0].reset();				
				}
				else if($.isNumeric(data)==true && data==2){
					success_or_error_msg('#contact_submit_error',"danger","Mail has sent","" );			
				}
			 }	
		});
	}	
})



//custome cake
$('#cc_submit').click(function(event){		
	event.preventDefault();
	var formData = new FormData($('#custome-cake-form')[0]);
	formData.append("q","insert_custom_cake");
	if($.trim($('#cc_details').val()) == ""){
		success_or_error_msg('#cc_submit_error','danger',"Please type details","#cc_details");			
	}
	else if($.trim($('#cc_name').val()) == ""){
		success_or_error_msg('#cc_submit_error','danger',"Please type name","#cc_name");			
	}
	else if($.trim($('#cc_mobile').val()) == ""){
		success_or_error_msg('#cc_submit_error','danger',"Please enter mobile no.","#cc_mobile");			
	}
	else if($.trim($('#cc_email').val()) == ""){
		success_or_error_msg('#cc_submit_error','danger',"Please type email.","#cc_email");			
	}
	else{
		$.ajax({
			url: "includes/controller/customerController.php",
			type:'POST',
			data:formData,
			async:false,
			cache:false,
			contentType:false,processData:false,
			success: function(data){
				if($.isNumeric(data)==true && data==1){
					success_or_error_msg('#cc_submit_error',"success","Request has benn accepted, please keep in touch. We will contact with you shortly","" );	
					$('#custome-cake-form')[0].reset();				
				}
				else if($.isNumeric(data)==true && data==2){
					success_or_error_msg('#cc_submit_error',"danger","Error! ","" );			
				}
			 }	
		});
	}	
})


// send mail if forget password
$('#foget_pass_submit').click(function(event){		
	event.preventDefault();
	var formData = new FormData($('#forget-pass-form')[0]);
	formData.append("q","forget_password");
	if($.trim($('#forget_email').val()) == ""){
		success_or_error_msg('#foget_pass_submit_error','danger',"Please enter email address","#forget_email");			
	}
	else{
		$.ajax({
			url: "includes/controller/customerController.php",
			type:'POST',
			data:formData,
			async:false,
			cache:false,
			contentType:false,processData:false,
			success: function(data){
				if($.isNumeric(data)==true && data==2){
					success_or_error_msg('#foget_pass_submit_error',"danger","Please provide a valid email address","#forget_email" );			
				}
				else if($.isNumeric(data)==true && data==1){
					$('.sent_password').addClass("hide");
					$('.sent_password_msg').removeClass("hide");
				}
			 }	
		});
	}	
})


  
// send mail if forget password
$('#register_submit').click(function(event){		
	event.preventDefault();
	var formData = new FormData($('#register-form')[0]);
	formData.append("q","registration");
	if($.trim($('#cust_name').val()) == ""){ 
		success_or_error_msg('#registration_submit_error','danger',"Please enter name","#cust_name");			
	}
	else if($.trim($('#cust_username').val()) == ""){
		success_or_error_msg('#registration_submit_error','danger',"Please enter username","#cust_username");			
	}
	else if($.trim($('#cust_email').val()) == ""){
		success_or_error_msg('#registration_submit_error','danger',"Please enter email address","#cust_email");			
	}
	else if($.trim($('#cust_password').val()) == ""){
		success_or_error_msg('#registration_submit_error','danger',"Please enter pasword","#cust_password");			
	}
	else if($.trim($('#cust_conf_password').val()) == ""){
		success_or_error_msg('#registration_submit_error','danger',"Please confirm password ","#cust_conf_password");			
	}
	else if($.trim($('#cust_password').val()) != $.trim($('#cust_conf_password').val())){
		success_or_error_msg('#registration_submit_error','danger',"Please enter same password","#cust_conf_password");			
	}
	else if($.trim($('#cust_contact').val()) == ""){
		success_or_error_msg('#registration_submit_error','danger',"Please enter valid contact no","#cust_contact");			
	}
	else{
		$.ajax({
			url: "includes/controller/customerController.php",
			type:'POST',
			data:formData,
			async:false,
			cache:false,
			contentType:false,processData:false,
			success: function(data){
				//alert(data)
				if($.isNumeric(data)==true && data==2){
					success_or_error_msg('#registration_submit_error',"danger","Username is already exist, please try with another one","#cust_username" );			
				}
				else if($.isNumeric(data)==true && data==3){
					success_or_error_msg('#registration_submit_error',"danger","Email is already exist, please try with another one","#cust_email" );			
				}
				else if($.isNumeric(data)==true && data==1){
					$('.done_registration').addClass("hide");
					$('.done_registration_msg').removeClass("hide");
				}
				else{					
					success_or_error_msg('#registration_submit_error',"danger","Registration is not completed. please check your information again.","#cust_email" );
				}
			 }	
		});
	}	
})


function showCart(){
	$.ajax({
		url: "includes/controller/ecommerceController.php",
		dataType: "json",
		type: "post",
		async:false,
		data: {
			q: "viewCartSummery"
		},
		success: function(data) {
			if(!jQuery.isEmptyObject(data.records)){
				var html = '';
				var total = 0;
				var sub_total = 0;
				var count =0
				$.each(data.records, function(i,datas){ 
					sub_total += parseFloat(datas.discounted_rate)*(datas.quantity);
					html += '<div class="cart-item"><div class="cart-item-left"><img src="/admin/images/product/'+datas.product_image+'" alt=""></div><div class="cart-item-right"><h6>'+datas.product_name+'</h6><span> '+datas.discounted_rate+' * '+datas.quantity+' = '+sub_total+'</span></div><span class="delete-icon" onclick="deleteProduct('+"'"+datas.cart_key+"'"+')"></span></div>';
					count++;
					total += sub_total ; 
				});
				total = total.toFixed(2);
				html += '<div class="subtotal"><div class="col-md-6 col-sm-6 col-xs-6"><h6>Subtotal :</h6></div><div class="col-md-6 col-sm-6 col-xs-6"><span>Tk '+total+'</span></div></div>';
				html  += '<div class="cart-btn"><div class="col-sm-6"><a href="cart.php" class="btn-main checkout">VIEW ALL</a></div><div class="col-sm-6"><a href="checkout.php" class="btn-main checkout">CHECK OUT</a></div></div>';  
				$('#total_product_in_cart').html(count);
			}
			else{
				$('#total_product_in_cart').html(0);
				html = "<h6>You have no items in your cart</h6>";
			}
			$('#cart_div').html(html);
			
		}
	});	
}


function deleteProduct(cart_key){
	//alert(cart_key)
	$.ajax({
		url: "includes/controller/ecommerceController.php",
		dataType: "json",
		type: "post",
		async:false,
		data: {
			q: "removeFromCart",
			cart_key:cart_key
		},
		success: function(data) {
			if(!jQuery.isEmptyObject(data.records)){
				var html = '';
				var total = 0;
				var sub_total = 0;
				var count =0
				$.each(data.records, function(i,datas){ 
					sub_total += parseFloat(datas.discounted_rate)*(datas.quantity);
					html += '<div class="cart-item"><div class="cart-item-left"><img src="/admin/images/product/'+datas.product_image+'" alt=""></div><div class="cart-item-right"><h6>'+datas.product_name+'</h6><span> '+datas.discounted_rate+' * '+datas.quantity+' = '+sub_total+'</span></div><span class="delete-icon" onclick="deleteProduct('+"'"+datas.cart_key+"'"+')"></span></div>';
					count++;
					total += sub_total ; 
				});
				total = total.toFixed(2);
				html += '<div class="subtotal"><div class="col-md-6 col-sm-6 col-xs-6"><h6>Subtotal :</h6></div><div class="col-md-6 col-sm-6 col-xs-6"><span>Tk '+total+'</span></div></div>';
				html  += '<div class="cart-btn"><a href="cart.php" class="btn-main checkout">VIEW ALL</a><a href="checkout.php" class="btn-main checkout">CHECK OUT</a></div>';  
				$('#total_product_in_cart').html(count);
			}
			else{
				$('#total_product_in_cart').html(0);
				html = "<h6>You have no items in your cart</h6>";
			}
			$('#cart_div').html(html);
			
		}
	});	
}

// className: danger, success, info, primary, default, warning
function success_or_error_msg(div_to_show, class_name, message, field_id){
	$(div_to_show).addClass('alert alert-custom alert-'+class_name).html(message).show("slow");
	//$(window).scrollTop(200);
	var set_interval = setInterval(function(){
		$(div_to_show).removeClass('alert alert-custom alert-'+class_name).html("").hide( "slow" );
		if(field_id!=""){ $(field_id).focus();}
		clearInterval(set_interval);
	}, 4000);
}

showCart();




var view_order = function view_order(order_id){
	$('#ord_detail_vw>table>tbody').html('');
	$.ajax({
		url:"includes/controller/ecommerceController.php",
		type:'POST',
		async:false,
		dataType: "json",
		data:{
			q: "get_order_details_by_invoice",
			order_id:order_id
		},
		success: function(data){
			if(!jQuery.isEmptyObject(data.records)){
				$.each(data.records, function(i,data){
					$('#ord_title_vw').html(data.invoice_no);
					$('#ord_date').html("Ordered time: "+data.order_date);
					$('#dlv_date').html("Delivery time: "+data.delivery_date);
					$('#dlv_ps').html("Payment Status: "+data.paid_status);
					$('#dlv_pm').html("Payment Method: "+data.payment_method);
					$('#customercustomer_detail_vw').html(" "+data.customer_name+"<br/><b>Mobile:</b> "+data.customer_contact_no+"<br/><b>Address:</b> "+data.customer_address);
					$('#note_vw').html(data.remarks);
					
					var order_tr = "";
					var order_total = 0;
					order_infos	 = data.order_info;
					var order_arr = order_infos.split(',');
					$.each(order_arr, function(i,orderInfo){
						var order_info_arr = orderInfo.split('#');
						var total = ((parseFloat(order_info_arr[6])*parseFloat(order_info_arr[7])));
						order_tr += '<tr><td>'+order_info_arr[2]+'</td><td align="left">'+order_info_arr[4]+'</td><td align="center">'+order_info_arr[7]+'</td><td align="right">'+order_info_arr[6]+'</td><td align="right">'+total.toFixed(2)+'</td></tr>';
						order_total += total;
					});	
					var total_order_bill = ((parseFloat(order_total)+parseFloat(data.delivery_charge))-parseFloat(data.discount_amount));
					var total_paid = data.total_paid_amount;
					order_tr += '<tr><td colspan="4" align="right" ><b>Total Product Bill</b></td><td align="right"><b>'+order_total.toFixed(2)+'</b></td></tr>';
					order_tr += '<tr><td colspan="4" align="right" ><b>Discount Amount</b></td><td align="right"><b>'+data.discount_amount+'</b></td></tr>';
					order_tr += '<tr><td colspan="4" align="right" ><b>Delivery Charge</b></td><td align="right"><b>'+data.delivery_charge+'</b></td></tr>';
					order_tr += '<tr><td colspan="4" align="right" ><b>Total Order Bill</b></td><td align="right"><b>'+total_order_bill.toFixed(2)+'</b></td></tr>';	
				    order_tr += '<tr><td colspan="4" align="right" ><b>Total Paid</b></td><td align="right"><b>'+total_paid+'</b></td></tr>';		
					order_tr += '<tr><td colspan="4" align="right" ><b>Balance</b></td><td align="right"><b>'+(total_order_bill-total_paid).toFixed(2)+'</b></td></tr>';							
					$('#ord_detail_vw>table>tbody').append(order_tr);

				});								
			}
		 }	
	});
	$('#order_modal').modal();	
}

$(document).on('click','#order_print', function(){
	var divContents = $("#order-div").html();
	var printWindow = window.open('', '', 'height=400,width=800');
	printWindow.document.write('<html><head><title>DIV Contents</title>');
	printWindow.document.write('</head><body style="padding:10px">');
	printWindow.document.write('<link href="plugin/bootstrap/bootstrap.css" rel="stylesheet">');
	printWindow.document.write(divContents);
	printWindow.document.write('</body></html>');
	printWindow.document.close();
	printWindow.print();
});


$('.category a').on('click',function(){
	window.location = $(this).attr('href');
});

$('#searchSubmit').on('click',function(){	
	window.location = 'search.php?search='+$('#searchbox'). val();
});




$('.modal').on('shown.bs.modal', function (e) {
	//alert(1111)
     $('.item').daterangepicker({
		singleDatePicker: true,
	/*	autoUpdateInput: false,*/
		calender_style: "picker_3",
		timePicker:true,
		locale: {
			  format: 'YYYY-MM-DD h:mm',
			  separator: " - ",
		}
	});
});



/*

$(document).on('click','#c_cake_modal_open_btn',function(){
	$('.item').daterangepicker({
		singleDatePicker: true,
		calender_style: "picker_3",
		timePicker:true,
		locale: {
			  format: 'YYYY-MM-DD h:mm',
			  separator: " - ",
		}
	});
})
*/
</script>


<!-- Mirrored from Cakencookie.sk-web-solutions.com/about.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 08 Sep 2018 06:22:15 GMT -->
</html>