<div class="col-md-4 col-sm-5 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
	<div class="team-single-left">
		<div class="team-single-blog " style="margin: 0px 0 !important;" >
			<img id="customer_img_" alt="" class="img img-rounded">
		</div>
	</div>
</div>
<div class="col-md-8 col-sm-7 col-xs-12 wow fadeInDown main_content" data-wow-duration="1000ms" data-wow-delay="300ms">
	<div class="team-single-right">
		<h3 id='customer_name_' class="text-capitalize"></h3>
		<h6 >Customer Status : <span id='customer_status_' ></span> </h6>
		<p>Contact No: <a href="#" id="contact_no_"></a>
		<br> E-mail: <a href="#" id="email_"></a></p>
		<p > Address: <span id="address_"></span></p>
        <p > Loyalty Points: <span id="loyalty_points_"></span></p>

        <br /><br />
        <div class="col-md-6 col-sm-12 col-xs-12" style="padding: 2px; ">
            <button type="button" class="btn-main btn-small btn-primary" onclick="show_my_accounts('update-password')"  style="border-radius: 4px; padding: 2px"><a href="#" style="color: white">Update Password</a></button>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12" style="padding: 2px;" >
            <button type="button" class="btn-main btn-small btn-primary" onclick="show_my_accounts('update-profile')"  style="border-radius: 4px; padding: 2px"><a href="#" style="color: white">Update Information</a></button>
            <!-- <a href='javascript:void(0)'  onclick="show_my_accounts('update-profile');" class="btn-medium btn-skin pull-left">Update your information</a>-->

        </div>
	</div>




</div>

<script>

//-------------------------------------------------------------------------
if (localStorage.getItem("passkey") && localStorage.getItem("passkey")!=''){
    show_my_accounts('update-password', '')
}
localStorage.setItem("currenturl", "profile");


$(document).ready(function () {	
	load_customer_profile = function load_customer_profile(){
		$('#is_active_home_page_div').hide();
		$.ajax({
			url:"includes/controller/ecommerceController.php",
			dataType: "json",
			type: "post",
			async:false,
			data: {
				q: "get_customer_details",
				customer_id: customer_id,
			},
			success: function(data){
			    //alert(data.loyalty_points)
				if(!jQuery.isEmptyObject(data.records)){
					$.each(data.records, function(i,data){ 				
						$('#customer_name_').html(data.full_name);
						$('#contact_no_').html(data.contact_no);
						$('#email_').html(data.email);
						$('#address_').html(data.address);
						$('#customer_status_').html(data.status_text);
                        $('#loyalty_points_').html(data.loyalty_points);

                        if(data.photo == ""){
							$('#customer_img_').attr("src",'./admin/images/no_image.png');
						}else{
							$('#customer_img_').attr("src","./admin/"+data.photo);
						}
						$('#customer_img_').attr("width", "70%","height","70%");
					});
					
				}
			}
		});
	}	
	load_customer_profile();	

});
</script>
