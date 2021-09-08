<!-- Start login modal -->
<div class="modal fade booktable" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="booktable" >
    <div class="modal-dialog auth_modal" role="document">
        <div class="modal-content" id="login_modal_responsive">
            <div class="modal-body" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <div id="login-div" class="">
                    <div id="done_login" class="">
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
                                        <input type="password" name="password" id="password" placeholder="Password" class="input-fields" required >
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label><input type="checkbox" name="chkbox">Remember me</label>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <a href="javascript:void(0)" onclick="active_modal(2)"class="pull-right" data-toggle="modal" data-target="#forget_passModal" id="send_password"><i class="fa fa-user" aria-hidden="true"></i> Forgot Password</a>
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
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                <a href="javascript:void(0)" onclick="active_modal(3)" class="facebook-btn btn-change button-default " id="log_reg"><i class="fa fa-user" aria-hidden="true"></i> Register New Account</a>
                            </div>
                        </div>
                    </div>
                    <div class=" hide" 	id="done_login_msg" >
                        <div class="alert alert-success alert-custom text-center">
                            <p>You have logged in successfully</p>
                        </div>
                        <div class="col-md-12 text-center"><a href="account.php" id="" class="facebook-btn btn-change button-default"><i class="fa fa-user"></i>Browse your account?</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End login -->
<div class="col-md-12 col-sm-12 col-xs-12 sent_password_msg center hide" >
    <a href="javascript:void(0)" class="facebook-btn btn-change button-default " data-toggle="modal" data-target="#unpaid_orderModal" id="unpaid_orderModal_click"></a>
</div>
<!-- register modal -->
<div class="modal fade booktable" id="registerModal" tabindex="-2" role="dialog" aria-labelledby="booktable">
    <div class="modal-dialog auth_modal" role="document">
        <div class="modal-content" id="registration_modal_responsive">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <div id="register-div">
                    <div class="title text-center">
                        <h3 class="text-coffee">Register</h3>
                    </div>
                    <div class="done_registration">

                        <form class="register-form" method="post" name="register-form" id="register-form">
                            <div class="form-group row" style="margin: -5px !important; margin-top: ">
                                <label for="staticEmail" class="col-sm-3 col-form-label" >Name</label>
                                <div class="col-sm-9" style="margin: -5px !important;">
                                    <input type="text" name="cust_name" id="cust_name" placeholder="Name" class="form-control" required >
                                </div>
                            </div>
                            <div class="form-group row" style="margin: -5px !important;">
                                <label for="inputPassword" class="col-sm-3 col-form-label" >User Name</label>
                                <div class="col-sm-9" style="margin: -5px !important;">
                                    <input type="text" name="cust_username" id="cust_username" placeholder="User Name" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row" style="margin: -5px !important;">
                                <label for="staticEmail" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9" style="margin: -5px !important;">
                                    <input type="email" name="cust_email" id="cust_email" placeholder="Email address" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row" style="margin: -5px !important;">
                                <label for="inputPassword" class="col-sm-3 col-form-label" >Contact No</label>
                                <div class="col-sm-9" style="margin: -5px !important;">
                                    <input type="number" name="cust_contact" id="cust_contact" pattern="[0-9]{11}" placeholder="Contact No" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row" style="margin: -5px !important;">
                                <label for="staticEmail" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9" style="margin: -5px !important;">
                                    <input type="password" name="cust_password" id="cust_password" placeholder="Password" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row" style="margin: -5px !important;">
                                <label for="inputPassword" class="col-sm-3 col-form-label" >Confirm Password</label>
                                <div class="col-sm-9" style="margin: -5px !important;">
                                    <input type="password" name="cust_conf_password" id="cust_conf_password"  placeholder="Confirm Password" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row" style="margin: -5px !important;">
                                <label for="staticEmail" class="col-sm-3 col-form-label" >Address</label>
                                <div class="col-sm-9" style="margin: -5px !important;">
                                    <input type="text" name="cust_address" id="cust_address" placeholder="Address" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group row" style="margin: -5px !important;">
                                <label for="staticEmail" class="col-sm-3 col-form-label">City</label>
                                <div class="col-sm-9" style="margin: -5px !important;">
                                    <input type="text" name="city" id="city" placeholder="City" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group row" style="margin: -5px !important;">
                                <label for="inputPassword" class="col-sm-3 col-form-label">State</label>
                                <div class="col-sm-9" style="margin: -5px !important;">
                                    <input type="text" name="state" id="state" placeholder="State" class="form-control" >
                                </div>
                            </div>

                            <div class="form-group row" style="margin: -5px !important;">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Zipcode</label>
                                <div class="col-sm-9" style="margin: -5px !important;">
                                    <input type="text" name="zipcode" id="zipcode" placeholder="Zipcode" class="form-control" >
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <p>By clicking on <b>Register Now</b> button you are accepting the <a href="index.php?termsCondition.php">Terms &amp; Conditions</a></p>
                                <div id="registration_submit_error" class="text-center" style="display:none"></div>
                                <input type="submit" name="submit" id="register_submit" class="button-default button-default-submit" value="Register now">
                            </div>
                        </form>

                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 hide done_registration_msg text-center " >
                        <div class="alert alert-success " id="registration_success_message">
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
    <div class="modal-dialog auth_modal" role="document">
        <div class="modal-content" id="passwore_recovary_modal_responsive">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <div id="forget-pass-div">
                    <div class="title text-center">
                        <h3 class="text-coffee">Recover Password</h3>
                    </div>
                    <form class="register-form" method="post" name="forget-pass-form" id="forget-pass-form">
                        <div class="row">
                            <div class="sent_password">
                                <div class="col-md-12 col-sm-12 col-xs-12 ">
                                    <input type="email" name="forget_email" id="forget_email" placeholder="Enter email address" class="input-fields">
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 ">
                                    <div id="foget_pass_submit_error" class="text-center" style="display:none"></div>
                                    <input type="submit" name="submit" id="foget_pass_submit"  class="button-default button-default-submit" value="Send Email">
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
<div class="modal fade booktable" id="unpaid_orderModal" tabindex="-1" role="dialog" aria-labelledby="booktable">
    <div class="modal-dialog auth_modal" role="document">
        <div class="modal-content" id="unpaid_order">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div id="forget-pass-div">
                    <div class="title text-center">
                        <h3 class="text-coffee">You have an unpaid order #<span id="unpaid_order_id"></span></h3>
                        <p>To pay it now please click the button below</p>
                        <input type="button" onclick="paynow()" name="pay_button" id="pay_button"  class="button-default button-default-submit" style="background-color: #e4b95b; color: white;" value="Pay Now">
						 <input type="button" onclick="cancel_order()" name="pay_button" id="pay_cancel"  class="button-default button-default-submit" style="background-color:red; color: white;" value="Cancel Order">
				   </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- New Password set

<div class="modal fade booktable" id="password_set" tabindex="-1" role="dialog" aria-labelledby="booktable">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="passwore_set_modal_responsive">
            <div class="modal-body">
                <div id="forget-pass-div">
                    <div class="title text-center">
                        <h3 class="text-coffee">Enter New Password</h3>
                    </div>
                    <form class="register-form" method="post" name="new-pass-form" id="new-pass-form">
                        <div class="row">
                            <div class="sent_password">
                                <div class="col-md-12 col-sm-12 col-xs-12 ">
                                    <input type="hidden" name="password_set_key" id="password_set_key">
                                    <input type="password" name="new_password" id="new_password" placeholder="Enter New Password" class="input-fields">
                                    <input type="password" name="new_password_retype" id="new_password_retype" placeholder="Enter New Password Again" class="input-fields">

                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 ">
                                    <div id="new_password_submit_error" class="text-center" style="display:none"></div>
                                    <input type="submit" name="submit" id="new_password_submit"  class="button-default button-default-submit" value="Set Password">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
-->
<!-- End login -->

