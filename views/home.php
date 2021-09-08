<?php
session_start();
include("../includes/dbConnect.php");
include("../includes/dbClass.php");
$dbClass = new dbClass;

//$is_logged_in_customer = "";

//email, mobile, address, fearure, title, $subtitle, $facebook, $twitter, $instagram, $googleplus,

$email_info   = $dbClass->getDescription('web_admin_email');
$mobile_info  = $dbClass->getDescription('store_contact');
$about_us     = $dbClass->getDescriptionWithHtml(28);
$address      = $dbClass->getDescription('store_address');
$website_url  = $dbClass->getDescription('website_url');
$website_title=$dbClass->getDescription('website_title');
$store_address=$dbClass->getDescription('store_address');

?>


    <!-- Start Slider Part -->
<section class="home-slider">
        <div class="tp-banner-container">
            <div class="tp-banner">
                <ul>
                    <li data-transition="zoomout" data-slotamount="2" data-masterspeed="1000" data-thumb="" data-saveperformance="on" data-title="Slide">
                        <img src="./images/dummy.png" alt="slidebg1" data-lazyload="./images/slider1.jpg" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
                        <!-- LAYERS -->
                        <div class="tp-caption very_large_text" data-x="center" data-hoffset="0" data-y="250" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">WE’RE <span>Burrito Brothers</span> <i>Restaurant</i>
                        </div>
                        <!-- LAYERS -->
                        <div class="tp-caption medium_text" data-x="center" data-hoffset="0" data-y="340" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">Traditional Turkish Delicacies
                        </div>
                        <!-- LAYERS -->
                        <div class="tp-caption" data-x="center" data-hoffset="0" data-y="425" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300"><a href="#" class="button-white">Explore NOW</a>
                        </div>
                    </li>
                    <li data-transition="zoomout" data-slotamount="2" data-masterspeed="1000" data-thumb="" data-saveperformance="on" data-title="Slide">
                        <img src="./images/dummy.png" alt="slidebg1" data-lazyload="./images/slider1.jpg" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
                        <!-- LAYERS -->
                        <div class="tp-caption very_large_text" data-x="center" data-hoffset="0" data-y="250" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">WE’RE <span>Burrito Brothers</span> <i>Restaurant</i>
                        </div>
                        <!-- LAYERS -->
                        <div class="tp-caption medium_text" data-x="center" data-hoffset="0" data-y="340" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">Traditional Turkish Delicacies
                        </div>
                        <!-- LAYERS -->
                        <div class="tp-caption" data-x="center" data-hoffset="0" data-y="425" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300"><a href="#" class="button-white">Explore NOW</a>
                        </div>
                    </li>
                    <li data-transition="zoomout" data-slotamount="2" data-masterspeed="1000" data-thumb="" data-saveperformance="on" data-title="Slide">
                        <img src="./images/dummy.png" alt="slidebg1" data-lazyload="./images/slider1.jpg" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
                        <!-- LAYERS -->
                        <div class="tp-caption very_large_text" data-x="center" data-hoffset="0" data-y="250" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">WE’RE <span>Burrito Brothers</span> <i>Restaurant</i>
                        </div>
                        <!-- LAYERS -->
                        <div class="tp-caption medium_text" data-x="center" data-hoffset="0" data-y="340" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">Traditional Turkish Delicacies
                        </div>
                        <!-- LAYERS -->
                        <div class="tp-caption" data-x="center" data-hoffset="0" data-y="425" data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300"><a href="#" class="button-white">Explore NOW</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <!-- End Slider Part -->
    <!-- Start Welcome Part -->
<section id="reach-to" class="welcome-part home-icon">
        <div class="icon-default">
            <a href="#reach-to" class="scroll"><img src="./images/scroll-arrow.png" alt=""></a>
        </div>
        <div class="container">
            <div class="build-title">
                <h2>Welcome To The <?php echo $website_title; ?></h2>
                <h6>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h6>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                    <p>Welcome. This is La Boom. Elegant &amp; sophisticated restaurant template. Royal plate offers different home page layouts with smart and unique design, showcasing beautifully designed elements every restaurant website should have. Smooth animations, fast loading and engaging user experience are just some of , the features this template offers. So, give it a try and dive into a world of La Boom restaurant websites.</p>
                    <p><img src="./images/signature.png" alt=""></p>
                    <p><a href="#" class="btn-black">LEARN MORE</a></p>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
                    <img src="./images/img2.png" alt="">
                </div>
            </div>
        </div>
        <div class="float-main">
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
        </div>
    </section>
    <!-- End Welcome Part -->
    <!-- Start Hot Items -->
<section class="special-menu bg-skeen home-icon wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
        <div class="icon-default icon-skeen">
            <img src="./images/icon6.png" alt="">
        </div>
        <div class="container">
            <div class="build-title">
                <h2>Our Hot Items</h2>
                <h6>The role of a good cook ware in the preparation of a sumptuous meal cannot be over emphasized then one consider white bread</h6>
            </div>
            <div class="menu-wrapper">
                <div class="portfolioContainer row">
                    <div class="col-md-6 col-sm-6 col-xs-12 isotope-item breakfast">
                        <div class="menu-list">
                                        <span class="menu-list-product">
                                            <img src="./images/img3.png" alt="">
                                        </span>
                            <h5>LASAL CHEESE <span>$ 15.00</span></h5>
                            <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 isotope-item lunch">
                        <div class="menu-list">
                                        <span class="menu-list-product">
                                            <img src="./images/img4.png" alt="">
                                        </span>
                            <h5>JUMBO CARB SHRIMP <span>$ 25.00</span></h5>
                            <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 isotope-item dessert">
                        <div class="menu-list">
                                        <span class="menu-list-product">
                                            <img src="./images/img5.png" alt="">
                                        </span>
                            <h5>SURMAI CHILLI <span>$ 15.00</span></h5>
                            <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 isotope-item dinner">
                        <div class="menu-list">
                                        <span class="menu-list-product">
                                            <img src="./images/img6.png" alt="">
                                        </span>
                            <h5>CAPO STEAK <span>$ 45.00</span></h5>
                            <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 isotope-item freshfood">
                        <div class="menu-list">
                                        <span class="menu-list-product">
                                            <img src="./images/img7.png" alt="">
                                        </span>
                            <h5>ORGANIC FRUIT SALAD <span>$ 15.00</span></h5>
                            <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 isotope-item freshfood">
                        <div class="menu-list">
                                        <span class="menu-list-product">
                                            <img src="./images/img8.png" alt="">
                                        </span>
                            <h5>PRAWNS BUTTER GARLIC <span>$ 15.00</span></h5>
                            <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames</p>
                        </div>
                    </div>
                </div>
                <div class="btn-outer">
                    <a href="#" class="btn-main btn-shadow">Explore Full Menu</a>
                </div>
            </div>
        </div>
        <div class="float-main">
            <div class="icon-top-left">
                <img src="./images/icon7.png" alt="">
            </div>
            <div class="icon-bottom-left">
                <img src="./images/icon8.png" alt="">
            </div>
            <div class="icon-top-right">
                <img src="./images/icon9.png" alt="">
            </div>
            <div class="icon-bottom-right">
                <img src="./images/icon10.png" alt="">
            </div>
        </div>
    </section>
    <!-- End Hot Items -->
    <!-- Start Services -->
<section class="bg-skeen home-icon wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms" style="background-color: rgba(244,242,237,1)">
        <div class="icon-default icon-skeen">
            <img src="./images/scroll-arrow.png" alt="">
        </div>
        <div class="container">
            <div class="service-track">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="service-track-inner btn-shadow" >
                            <div class="service-track-info">
                                <h3>Catering <span>Service</span></h3>
                            </div>
                            <div class="service-track-overlay banner-bg" data-background="images/hover-img1.png" onclick="catering()">
                                <img src="./images/img36.png" alt="">
                                <h3>Catering <span>Service</span></h3>
                                <p>Aptent taciti sociosqu ad litora euismod atras vulputate iltricies etri elit class.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="service-track-inner btn-shadow" id="groupOrder()">
                            <div class="service-track-info">
                                <h3>Group <span>Order</span></h3>
                            </div>
                            <div class="service-track-overlay banner-bg" data-background="images/hover-img1.png">
                                <img src="./images/img36.png" alt="">
                                <h3>Group <span>Order</span></h3>
                                <p>Aptent taciti sociosqu ad litora euismod atras vulputate iltricies etri elit class.</p>
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
        <div class="icon-default icon-skeen" style="background-color: white">
            <img src="./images/icon22.png" alt="" style="background-color: white">
        </div>
        <div class="container">
            <div class="build-title">
                <h2>Features</h2>
                <h6>The role of a good cook ware in the preparation of a sumptuous meal cannot be <br> over emphasized then one consider white bread</h6>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="feature-list-icon">
                        <div class="feature-icon-table">
                            <img src="./images/img9.png" alt="">
                        </div>
                    </div>
                    <h5>Fresh Dishes</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eius-</p>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="feature-list-icon">
                        <div class="feature-icon-table">
                            <img src="./images/img10.png" alt="">
                        </div>
                    </div>
                    <h5>Various Menu</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eius-</p>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="feature-list-icon">
                        <div class="feature-icon-table">
                            <img src="./images/img11.png" alt="">
                        </div>
                    </div>
                    <h5>Well Service</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eius-</p>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="feature-list-icon">
                        <div class="feature-icon-table">
                            <img src="./images/img12.png" alt="">
                        </div>
                    </div>
                    <h5>Fast Delivery</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eius-</p>
                </div>
            </div>
        </div>
    </section>
    <!-- End Feature list -->
    <!-- Start Captures -->
<section class="instagram-main home-icon wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
        <div class="icon-default">
            <img src="./images/icon23.png" alt="">
        </div>
        <div class="container">
            <div class="build-title">
                <h2>Captures</h2>
                <h6>Enjoyed your stay at La Boom? Share your moments with us. Follow us on Instagram and use</h6>
            </div>
        </div>
        <div class="gallery-slider">
            <div class="owl-carousel owl-theme" data-items="6" data-laptop="5" data-tablet="4" data-mobile="1" data-nav="true" data-dots="false" data-autoplay="true" data-speed="2000" data-autotime="3000">
                <div class="item">
                    <a href="./images/gallery/gallery-big1.jpg" class="magnific-popup">
                        <img src="./images/gallery/gallery1.png" alt="" class="animated">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-inner">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="./images/gallery/gallery-big2.jpg" class="magnific-popup">
                        <img src="./images/gallery/gallery2.png" alt="" class="animated">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-inner">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="./images/gallery/gallery-big3.jpg" class="magnific-popup">
                        <img src="./images/gallery/gallery3.png" alt="" class="animated">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-inner">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="./images/gallery/gallery-big4.jpg" class="magnific-popup">
                        <img src="./images/gallery/gallery4.png" alt="" class="animated">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-inner">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="./images/gallery/gallery-big5.jpg" class="magnific-popup">
                        <img src="./images/gallery/gallery5.png" alt="" class="animated">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-inner">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="./images/gallery/gallery-big6.jpg" class="magnific-popup">
                        <img src="./images/gallery/gallery6.png" alt="" class="animated">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-inner">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="./images/gallery/gallery-big1.jpg" class="magnific-popup">
                        <img src="./images/gallery/gallery1.png" alt="" class="animated">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-inner">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="./images/gallery/gallery-big2.jpg" class="magnific-popup">
                        <img src="./images/gallery/gallery2.png" alt="" class="animated">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-inner">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="./images/gallery/gallery-big3.jpg" class="magnific-popup">
                        <img src="./images/gallery/gallery3.png" alt="" class="animated">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-inner">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="./images/gallery/gallery-big4.jpg" class="magnific-popup">
                        <img src="./images/gallery/gallery4.png" alt="" class="animated">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-inner">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="./images/gallery/gallery-big5.jpg" class="magnific-popup">
                        <img src="./images/gallery/gallery5.png" alt="" class="animated">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-inner">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="./images/gallery/gallery-big6.jpg" class="magnific-popup">
                        <img src="./images/gallery/gallery6.png" alt="" class="animated">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-inner">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- End Captures -->


<script src="js/app.js"></script>
<script src="js/script.js"></script>

<script>

    function catering() {
        //alert('sdf')
        window.location.href= 'index.php?page=catering'
    }

    function groupOrder(){

    }
	
	//Notification
	
	$(document).ready(function () {
		var customer_id = "<?php echo $_SESSION['customer_id']; ?>";
		
		$('body').on("click", ".dropdown-menu", function (e) {
			$(this).parent().is(".open") && e.stopPropagation();
		});
		
		$('#load_more_not_button').click(function() {
			 $(this).toggleClass('active');
			 show_notifications(customer_id);
		});
		
		
		set_time_out_fn = function set_time_out_fn(){
			setTimeout(function(){ 
				show_notifications_no(customer_id);
				set_time_out_fn();
			}, 30000); 		
		}
		
		set_time_out_fn();
		show_notifications_no(customer_id);	
		show_notifications(customer_id);
		

	});
	
</script>