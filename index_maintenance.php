<?php
include 'views/layout/common_php.php';
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <?php
         include 'views/layout/header_files.php';
   ?>
</head>

<body>
<?php
	include 'views/layout/pre_load.php';
?>
<div class="wrapper">
    <!-- Start Header -->
    <?php
    include 'views/layout/header.php';
    ?>
    <!-- End Header -->
    <!-- Start Main -->
    <?php
    include 'views/layout/auth_modal.php';
    //echo $website_url;
    ?>
    <script>
        customer_id=<?php echo $customer_id;?>
    </script>

    <!-- End Header -->
    <!-- Start Main -->
    <div class="main-part" id="content" style="min-height: 600px;" >

        <div id="close_store_message_div" class="close-alert" style="">
            <!--<img src="images/closed.jpg" class="closed-img" style="width:300px" />-->
			 <img src="images/u_m.png" class="closed-img" style="width:300px" />
        </div>

        <!-- Start Slider Part -->
        <section class="home-slider" style="min-height: 500px;">
            <div class="tp-banner-container">
                <div class="tp-banner">
                    <ul>
						<!--
						<li data-transition="zoomout" data-slotamount="2" data-masterspeed="1000" data-thumb="" data-saveperformance="on" data-title="Slide">
							<img src="images/dummy.png" alt="" data-lazyload="admin/images/banner/slider1.jpg" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
							<div class="tp-caption very_large_text" data-x="center" data-hoffset="0" data-y="250" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">Hello oooooooooooo
							</div>
							<div class="tp-caption medium_text" data-x="center" data-hoffset="0" data-y="340" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">Some text  sdf sdsdf sdf sdfsd fsdf
							</div>
							<div class="tp-caption" data-x="center" data-hoffset="0" data-y="425" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">
								<a  href="categories.php"  class="button-white">Explore Our Menu</a>
							</div>
						</li>
						<li data-transition="zoomout" data-slotamount="2" data-masterspeed="1000" data-thumb="" data-saveperformance="on" data-title="Slide">
							<img src="images/dummy.png" alt="" data-lazyload="admin/images/banner/platter.jpg" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
							<div class="tp-caption very_large_text" data-x="center" data-hoffset="0" data-y="250" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">Hello oooooooooooo
							</div>
							<div class="tp-caption medium_text" data-x="center" data-hoffset="0" data-y="340" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">Some text  sdf sdsdf sdf sdfsd fsdf
							</div>
							<div class="tp-caption" data-x="center" data-hoffset="0" data-y="425" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">
								<a  href="categories.php"  class="button-white">Explore Our Menu</a>
							</div>
						</li>

						<li data-transition="zoomout" data-slotamount="2" data-masterspeed="1000" data-thumb="" data-saveperformance="on" data-title="Slide">
							<img src="images/dummy.png" alt="" data-lazyload="admin/images/banner/burrito_cat.jpg" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
							<div class="tp-caption very_large_text" data-x="center" data-hoffset="0" data-y="250" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">Hello oooooooooooo
							</div>
							<div class="tp-caption medium_text" data-x="center" data-hoffset="0" data-y="340" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">Some text  sdf sdsdf sdf sdfsd fsdf
							</div>
							<div class="tp-caption" data-x="center" data-hoffset="0" data-y="425" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">
								<a  href="categories.php"  class="button-white">Explore Our Menu</a>
							</div>
						</li>-->

                        <?php
                       $banner_info =  $dbClass->getResultList("select id,title,text,photo,status from banner_image where status=1  order by id desc limit 3");
                        foreach ($banner_info as $banner){
							extract($banner);
							echo '<li data-transition="zoomout" data-slotamount="2" data-masterspeed="1000" data-thumb="" data-saveperformance="on" data-title="Slide">
									<img src="images/dummy.png" alt="" data-lazyload="admin/'.$photo.'" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
									<!-- LAYERS -->
									<div class="tp-caption very_large_text" data-x="center" data-hoffset="0" data-y="250" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">'.$title.'
									</div>
									<!-- LAYERS -->
									<div class="tp-caption medium_text" data-x="center" data-hoffset="0" data-y="340" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">'.$text.'
									</div>
									<!-- LAYERS -->
									<div class="tp-caption" data-x="center" data-hoffset="0" data-y="425" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">
										<a  href="categories.php"  class="button-white">Explore Our Menu</a>
									</div>
								</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </section>
        <!-- End Slider Part -->
		
		        <!-- End Welcome Part -->
	
		
        <!-- Start Welcome Part-->
        <section id="reach-to" class="welcome-part home-icon">
			<div class="row" style="
					position: absolute;
					/*left: 50%;*/
					top: -130px;
					width: 100%;
					z-index: 111;
					text-align: center;
					padding: 20px 0 0 5px;">
			  <div class="col-xs-6 "><a href="https://postmates.com/merchant/burrito-brothers-washington-dc" > <img class="thumbnail btn responsive" src="images/yelp.png" style="max-width:100px; padding-left:5px"></a></div>
			  <div class="col-xs-6 "style="text-align: right !important;"><a  href="https://www.tripadvisor.com/Restaurant_Review-g28970-d1073695-Reviews-Burrito_Brothers-Washington_DC_District_of_Columbia.html"  class="button-white" style="display: inline-block !important;border: 1px solid #ffffff !important;padding: 4px 5px !important;border-radius: 4px !important; background-color: #fff;" ><img src="images/tripadvisor.png" /></a></div>
			</div>
		
			
			<div class="build-title">
				<h2 style="font-size:25px;"> Website is Currently under Maintenance<br/>You May However still order from:<br/>
					<div style="padding:20px 5px 20px 5px;">
						<a href="https://postmates.com/merchant/burrito-brothers-washington-dc" target="_blank">
							<img alt="Postmates" style="width: 70px;height: 70px;" src="images/postmates.png"/>
						</a>
						<a href="https://www.ubereats.com/washington-dc/food-delivery/burrito-brothers/BHBA8w74Tjy-zeFupL_Zvw" target="_blank">
							<img alt="UberEats" style="width: 70px;height: 70px;" src="images/uber.png"/>
						</a>
						<a href="https://www.grubhub.com/restaurant/burrito-brothers-205-pennsylvania-ave-se-washington/160787" target="_blank">
							<img alt="Doordash" style="width: 70px;height: 70px;" src="images/grubhub.png"/>
						</a>
					</div>
					or Call us Directly on (202)543-6835<br/>
					Open Mon-Sat 8AM-8:45PM, Sun 9AM-7:45PMPM<br/>
					Thank You For Your Patronage.
				</h2>

				<!--<h6 style="font-family: 'Schoolbell', arial, serif; ">Experience, DC'S freshest Mexican Taqueria for Less. First in Town and Still the Best.</h6>-->
			</div>
			
			
			
			
			
            <div class="icon-default">
                <a href="#reach-to" class="scroll"><img src="./images/scroll-arrow.png" alt=""></a>
            </div>
            <div class="container">
                <div class="build-title">
                    <h2>Welcome To <?php echo $website_title; ?></h2>

                    <h2 style="font-family:&quot;Pacifico&quot; !important ; color:#319C00 !important; margin-top: 0px  !important;">DC's Tastiest Mexican for  take-out or quick eat in!</h2>

                    <!--<h6 style="font-family: 'Schoolbell', arial, serif; ">Experience, DC'S freshest Mexican Taqueria for Less. First in Town and Still the Best.</h6>-->
                </div>
                <!--<div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                        <p class="text-justify" >Welcome. This is <?php echo $website_title; ?>. Elegant &amp; sophisticated restaurant service. </p>
                        <p><img src="./images/signature.png" alt=""></p>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
                        <img src="./images/img2.png" alt="">
                    </div>
                </div>-->
            </div>
            <!--
            <div class="float-main hidden-xs">
                <div class="icon-top-left">
                    <img src="./images/icon1.png" alt="">
                </div>
                <div class="icon-bottom-left">
                    <img src="./images/icon2.png" alt="">
                </div>
                <div class="icon-top-right">
                    <img src="./images/icon3.png" alt="">
                </div>
                <div class="icon-bottom-right">
                    <img src="./images/icon4.png" alt="">
                </div>
            </div>-->
        </section>

        
        <!-- End Hot Items -->
        <!-- Start Services -->
        <section class="bg-skeen home-icon wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms" style="background-color: rgba(244,242,237,1)">
            <div class="icon-default icon-skeen">
                <img src="./images/icon6.png" alt="">
            </div>
            <div class="container">
                <div class="service-track">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-6" style="cursor:pointer">
                            <div class="service-track-inner btn-shadow" >
                                <div class="service-track-info">
                                    <img src="./images/img36.png" alt="">
                                    <h3>PICK UP<span>ORDER</span></h3>
                                    <p>&nbsp;</p>
                                </div>
                                <div class="service-track-overlay banner-bg" style="background-color: #e4b95b" onclick="pickup_order()">
                                    <img src="./images/img36.png" alt="" style="max-height: 50px !important;">
                                    <h3>PICK UP <span>ORDER</span></h3>
                                    <p>Order ahead & have your order waiting for you at the store.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-6" style="cursor:pointer">
                            <div class="service-track-inner btn-shadow" id="groupOrder">
                                <div class="service-track-info">
                                    <img src="./images/img36.png" alt="">
                                    <h3>DELIVER <span>Order</span></h3>
                                    <p>&nbsp;</p>
                                </div>
                                <div class="service-track-overlay banner-bg" onclick="delivery_order()" style="background-color: #e4b95b">
                                    <img src="./images/img36.png" alt="" style="max-height: 50px !important;">
                                    <h3>DELIVER <span>Order</span></h3>
                                    <p>Stay home stay safe, your food will be delivered by our partners.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-6" style="cursor:pointer">
                            <div class="service-track-inner btn-shadow" >
                                <div class="service-track-info">
                                    <img src="./images/img36.png" alt="">
                                    <h3>Group <span>Order</span></h3>
                                    <p>For Pick Up Only</p>
                                </div>
                                <div class="service-track-overlay banner-bg" style="background-color: #e4b95b" onclick="group_order()">
                                    <img src="./images/img36.png" alt="" style="max-height: 50px !important;">
                                    <h3>Group <span>Order</span></h3>
                                    <p><small>Invite your friends to order for pickup from our store. You will need your friend's email addresses to send the the invitates.</small></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-6" style="cursor:pointer">
                            <div class="service-track-inner btn-shadow" id="groupOrder">
                                <div class="service-track-info">
                                    <img src="./images/img36.png" alt="">
                                    <h3>Catering <span>Service</span></h3>
                                    <p>&nbsp;</p>
                                </div>
                                <div class="service-track-overlay banner-bg" onclick="catering()" style="background-color: #e4b95b">
                                    <img src="./images/img36.png" alt="" style="max-height: 50px !important;">
                                    <h3>Catering <span>Service</span></h3>
                                    <p>Enjoy your party. We will serve your guests our delicious selections. Call us at <b><?php echo $mobile_info; ?></b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Services -->
        <!-- Start Feature list -->
        <section class="bg-skeen feature-list text-center home-icon wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms" style="background-color: white;">
            <div class="icon-default" >
                <img src="./images/icon22.png" alt="" style="background-color: white">
            </div>
            <div class="container">
                <!--<div class="build-title">
                    <h2>Features</h2>
                    <h6>The role of a good cook ware in the preparation of a sumptuous meal cannot be <br> over emphasized then one consider white bread</h6>
                </div>-->
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="feature-list-icon">
                            <div class="feature-icon-table">
                                <img src="./images/img9.png" alt="">
                            </div>
                        </div>
                        <h5>Fresh Dishes</h5>
                        <p>Every attempt is made to prepare our dishes with the maximum locally sourced fresh ingredients.</p>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="feature-list-icon">
                            <div class="feature-icon-table">
                                <img src="./images/img10.png" alt="">
                            </div>
                        </div>
                        <h5>Wide Menu</h5>
                        <p>Yes! Something Yummy for Every Tummy.</p>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="feature-list-icon">
                            <div class="feature-icon-table">
                                <img src="./images/img11.png" alt="">
                            </div>
                        </div>
                        <h5>Efficient Service</h5>
                        <p>Dishes promptly and accurately prepared on order with our streamlined processes.</p>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="feature-list-icon">
                            <div class="feature-icon-table">
                                <img src="./images/img12.png" alt="">
                            </div>
                        </div>
                        <h5>Fast Delivery</h5>
                        <p>Partneships with all major delivery platforms for efficient fast and safe service.</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Feature list -->
        <!-- Start Captures -->
        <section class="instagram-main home-icon wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms" style="background-color: rgba(244,242,237,1)">
            <div class="icon-default icon-skeen">
                <img src="./images/icon23.png" alt="">
            </div>
            <div class="container">
                <div class="build-title">
                    <h2>Our Favorite Social Media Captures</h2>
                    <!--<h6>Our Favourite Social Media.</h6>-->
                </div>
            </div>
            <div class="gallery-slider">
                <div class="owl-carousel owl-theme" data-items="6" data-laptop="5" data-tablet="4" data-mobile="1" data-nav="true" data-dots="false" data-autoplay="true" data-speed="2000" data-autotime="3000">
                    <?php foreach($gallary as $key=>$item){
                        if($item["attachment"]!=null){
                            echo('  <div class="item">
                                  <a href="'.$website_url.'admin/document/gallary_attachment/'.$item["attachment"].'" class="magnific-popup">
                                        <img  src="'.$website_url.'admin/document/gallary_attachment/thumb/'.$item["attachment"].'" alt="" class="animated captureImage">
                                        <div class="gallery-overlay captureImage">
                                            <div class="gallery-overlay-inner">
                                                <i class="fa fa-instagram" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </a>
                                </div>');
                        }
                    }?>
                </div>
            </div>
        </section>

    </div>
    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>
    <!-- End Main -->
    <!-- Start Footer -->
    <?php
    include 'views/layout/footer.php';
    ?>
    <!-- End Footer -->
    <!-- Start Book Table -->
    <!-- End Book Table -->
    <?php
    include 'views/layout/open_time_modal.php';
    ?>
</div>
<!-- Back To Top Arrow -->
<a href="#" class="top-arrow"></a>
</body>


<!-- Mirrored from laboom.sk-web-solutions.com/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 08 Sep 2018 06:20:20 GMT -->
</html>


<?php
include 'views/layout/footer_files.php';
?>



<script>


    //alert(pass[1])


    $('#pre-loader').delay(1000).fadeOut();

    // view images for category or not
    var item_image_d="<?php echo $item_image_display; ?>";
    if(item_image_d==1){
        item_image_display="display: block"
    }else{
        item_image_display="display: none"
    }


    var customer_id = "<?php echo $customer_id; ?>";


    galary = function galary() {
        $.ajax({
            url:"includes/controller/webController.php",
            dataType: "json",
            type: "post",
            async: false,
            data: {
                q: "gallary_image",
            },
            success: function (data) {

                var html='';
                $.each(data, function(i,datas){
                    html+='<div class="item">\n' +
                        '   <a href="'+project_url+'admin/document/gallary_attachment/thumb/'+datas.attachment+'" class="magnific-popup">\n' +
                        '     <img src="'+project_url+'admin/document/gallary_attachment/thumb/'+datas.attachment+'" alt="" class="animated">\n' +
                        '     <div class="gallery-overlay">\n' +
                        '      <div class="gallery-overlay-inner">\n' +
                        '       <i class="fa fa-instagram" aria-hidden="true"></i>\n' +
                        '      </div>\n' +
                        '     </div>\n' +
                        '    </a>\n' +
                        '  </div>\n'
                })

                $('#hot_items').html(html)
            }
        });
    }

    galary()

    hot_item = function hot_item() {
        /*
        $.ajax({
            url:"includes/controller/itemsController.php",
            dataType: "json",
            type: "post",
            async: false,
            data: {
                q: "hot_items",
            },
            success: function (data) {

                var width=''
                if(item_image_display=="display: none"){
                    width='style="padding-left: 10px"'
                }

                var html='';
                $.each(data, function(i,datas){
                    html+='<div class="col-md-6 col-sm-6 col-xs-12 isotope-item breakfast" >\n' +
                        '    <a href="item.php?id='+datas['item_id']+'">\n' +
                        '    <div class="menu-list" '+width+'>\n' +
                        '      <span class="menu-list-product" style="'+item_image_display+'">\n' +
                        '        <img src="'+project_url+'admin/'+datas['feature_image']+'" alt="">\n' +
                        '      </span>\n' +
                        '      <h5 class="margin-bottom-0 padding-bottom-4 text-uppercase"><a href="item.php?id='+datas['item_id']+'">'+datas['name']+'</a> <span>$'+datas['price']+'</span></h5>\n' +
                        '      <p>'+datas['details']+'</p>\n' +
                        '    </div>\n' +
                        '   </a>\n' +
                        ' </div>\n'
                })


                $('#hot_items').html(html)
            }
        });
        */
    }

    hot_item()

</script>
