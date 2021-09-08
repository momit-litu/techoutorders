<?php 
	session_start();
	include("includes/dbConnect.php");
	include("includes/dbClass.php");
	$dbClass = new dbClass;	
	
	if(isset($_SESSION['customer_id']) && $_SESSION['customer_id']) $is_logged_in_customer = 1; // here will be the customer id that will come from session when the customer will login
	else $is_logged_in_customer = "";

	//$is_logged_in_customer = ""; 

	$email_info   = $dbClass->getDescription(41);
	$mobile_info  = $dbClass->getDescription(40);
	$about_us     = $dbClass->getDescriptionWithHtml(28);
	$address      = $dbClass->getDescription(39);
	$feature      = $dbClass->getDescription(47);
	$special_menu = $dbClass->getDescription(48);
	$subtitle     = $dbClass->getDescription(49);
	$why_we_best  = $dbClass->getDescription(50);
	
	
	$facebook  = $dbClass->getDescription(57);
	$twitter  = $dbClass->getDescription(58);
	$instagram  = $dbClass->getDescription(59);
	$googleplus  = $dbClass->getDescription(60);
  
	
	
	$search_text = "";
	if(isset($_GET['search'])) $search_text = "";
	
?>

<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from Cakencookie.sk-web-solutions.com/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 08 Sep 2018 06:19:23 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cakencookie</title>
    <link href="/plugin/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="/plugin/bootstrap/datepicker.css" rel="stylesheet">
    <link href="/plugin/font-awesome/font-awesome.css" rel="stylesheet">
    <link href="/plugin/form-field/jquery.formstyler.css" rel="stylesheet">
    <link href="/plugin/revolution-plugin/extralayers.css" rel="stylesheet">
    <link href="/plugin/revolution-plugin/settings.css" rel="stylesheet">
    <link href="/plugin/owl-carousel/owl.carousel.css" rel="stylesheet">
    <link href="/plugin/owl-carousel/owl.theme.default.css" rel="stylesheet">
    <link href="/plugin/slick-slider/slick-theme.css" rel="stylesheet">
    <link href="/plugin/magnific/magnific-popup.css" rel="stylesheet">
    <link href="/plugin/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet">
    <link href="/plugin/animation/animate.min.css" rel="stylesheet">
    <link href="/css/theme.css" rel="stylesheet">
    <link href="/css/responsive.css" rel="stylesheet">
	<link href="/css/colordefault.css" rel="stylesheet">
	<link href="/css/elements.css" rel="stylesheet">
    <link rel="icon" href="/images/favicon.png" type="image/x-icon">
</head>

<body>
    <!-- Page pre loader -->
    <div id="pre-loader">
        <div class="loader-holder">
            <div class="frame">
                <img src="/images/Preloader.gif" alt="Cakencookie" />
            </div>
        </div>
    </div>
    <div class="wrapper">
        <!-- Start Header -->
        <header>
            <div class="header-part header-reduce sticky">
                <div class="header-top">
                    <div class="container">
                        <div class="header-top-inner">
                            <div class="header-top-left">
                                <a href="tel:<?php echo $mobile_info; ?>" class="top-cell"><img src="/images/fon.png" alt=""> <span>	<?php echo $mobile_info; ?></span></a>
                                <a href="mailto:<?php echo $email_info; ?>" class="top-email"><span><?php echo $email_info; ?></span></a>
                            </div>				
                            <div class="header-top-right">
                                <div class="social-top">
                                    <ul>
                                        <li><a href="<?php echo $facebook; ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                        <li><a href="<?php echo $twitter; ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                        <li><a href="<?php echo $instagram; ?>"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                        <li><a href="<?php echo $googleplus; ?>"><i class="fa fa-google" aria-hidden="true"></i></a></li>
                                    </ul>
                                </div>
							
                                <div class="language-menu">
								<?php 
									if($is_logged_in_customer != "") 
										echo '<a href="index.php?page=account" class="current-lang" id="my_acc"><i class="fa fa-user" aria-hidden="true" ></i> My Account</a>';
									else 
										echo '<a href="#" onclick="active_modal(1)" data-toggle="modal" data-target="#loginModal" class="current-lang" id="log_reg"><i class="fa fa-user" aria-hidden="true"></i> Login / Register</a>';
								?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-bottom">
                    <div class="container">
                        <div class="header-info">
                            <div class="header-info-inner">
                                <div class="book-table header-collect book-md">
                                    <a href="#" data-toggle="modal" data-target="#booktable" id="c_cake_modal_open_btn"><img src="/images/custom_cake.png" width="30" alt="">Custom Cake</a>
                                </div>
                                <div class="shop-cart header-collect">
                                    <a href="cart.php"><img src="/images/icon-basket.png" alt=""><span id="total_item_in_cart"></span> items</a>
                                    <div class="cart-wrap">
                                        <div class="cart-blog" id="cart_div" >
                                        </div>
                                    </div>
                                </div>
                                <div class="search-part">
                                    <a href="#"></a>
                                    <div class="search-box">
                                        <input type="text" name="searchbox" id="searchbox"  placeholder="Search">
                                        <input type="submit" name="searchSubmit" id="searchSubmit" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="menu-icon">
                            <a href="#" class="hambarger">
                                <span class="bar-1"></span>
                                <span class="bar-2"></span>
                                <span class="bar-3"></span>
                            </a>
                        </div>
                        <div class="menu-main">
                            <ul>
                                <li><a href="index.php">Home</a></li>
								<li><a href="about.php">About Us</a></li>
                                <li class="has-child">
                                    <a href="shop.php">Shop</a>
                                    <ul class="drop-nav">
									<?php								
										$category_result = $dbClass->getResultList("select * from category where status=1 order by id Desc");
										foreach ($category_result as $row){
											extract($row);								
											echo "<li><a href='shop.php?category=".$row['id']."'>".$row['name']."</a></li>";								
										}
									?>	
                                    </ul>
                                </li>
								<li><a href="gallery.php">Gallery</a></li>								
								<li><a href="contact.php">Contact</a></li>
								<!-- 
								<li><a href="news.php">News</a></li>																
                                <li><a href="terms_condition.php">Terms & Condition</a></li>
                                <li><a href="faq.php">FAQ</a></li>-->
                            </ul>
                        </div>
                        <div class="logo">
                            <a href="index.php"><img src="/images/logo.png" alt="" ></a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- End Header -->