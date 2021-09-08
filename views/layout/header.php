<header>
    <audio id="myAudio">
        <source src="tone/inflicted.mp3" type="audio/mpeg">
        <source src="tone/inflicted.m4r" type="audio/m4r">
        <source src="tone/inflicted.ogg" type="audio/ogg">
    </audio>
    <div class="header-part header-reduce sticky">
        <div class="header-top">
            <div class="container">
                <div class="header-top-inner">
                    <div class="header-top-left">
                        <a href="Tel:<?php echo $mobile_info; ?>" class="top-cell"><img src="images/fon.png" alt=""> <span><?php echo $mobile_info; ?></span></a>
                        <a href="mailto:<?php echo $email_info; ?>" class="top-email"><span><?php echo $email_info; ?></span></a>
                    </div>
                    <div class="header-top-right" id="us_account">
                        <div class="social-top" >

                            <ul id="login_registration">
                                <li><a href="<?php echo $facebook; ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                <li><a href="<?php echo $twitter; ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                <li><a href="<?php echo $instagram; ?>"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                <li><a href="<?php echo $yelp; ?>"><i class="fa fa-yelp" style="color:white"></i></a></li>


                                <?php
                                if($is_logged_in_customer == ""){
                                    echo '<li class="language-menu"><a href="#" onclick="active_modal(1)" data-toggle="modal" data-target="#loginModal" class="current-lang" id="log_reg"><i class="fa fa-user" aria-hidden="true"></i> Login</a></li>';
                                    echo '<li class="language-menu language-menu_hide"><a href="#" onclick="active_modal(3)" data-toggle="modal" data-target="#registerModal" class="current-lang" id="log_reg_modal"><i class="fa fa-user-plus" aria-hidden="true"></i> SignUp</a></li>';

                                }
                                ?>


                                <?php
                                if($is_logged_in_customer != ""){
                                    echo '	<li class="dropdown">
                                                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-bell-slash"></i>
                                                    <span class="badge bg-red" id="unread_notifications"></span>
                                                </a>
                                            
                                                <ul id="notification_ul" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">														
                                                    
                                                </ul>
                                                
                                            </li>';
                                }
                                ?>

                            </ul>
                        </div>

                        <?php
                        if($is_logged_in_customer != ""){
                            echo '<div class="language-menu">
										<a href="#" class="current-lang">
											<img src="images/user.png" alt=""> <i class="fa fa-caret-down" aria-hidden="true"></i>
										</a>
										<ul>
											<li><a href="account.php"> My Account</a></li>
											<li><a href="logout.php"> Log Out <i class="fa fa-sign-out"></i></a></li>
										</ul>
									</div>';
                        }
                        ?>


                    </div>
                </div>
            </div>
        </div>

        <div class="header-bottom">
            <div class="container">
                <div class="header-info">
                    <div class="header-info-inner">
                        <div class="book-table header-collect book-md">
                            <a href="#" data-toggle="modal" data-target="#booktable"><img src="images/icon-table.png" alt=""><span class="book-table-p">Store Hours</span></a>
                        </div>
                        <div class="shop-cart header-collect">
                            <a href="<?php if(isset($_SESSION['cart'])){echo 'cart.php';} else {echo '#';}?>" ><img src="images/icon-basket.png" alt=""><span id="total_item_in_cart"></span> <span class="">items</span></a>
                            <div class="cart-wrap " >
                                <div class="cart-blog " id="cart_div" style="overflow-y: scroll; max-height:400px; max-width: 270px" >
                                </div>
                            </div>
                        </div>
                        <div class="search-part" style="display: none">
                            <a href="#"></a>
                            <div class="search-box">
                                <input type="text" name="txt" placeholder="Search">
                                <input type="submit" name="submit" value=" ">
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
                <div class="book-table header-collect book-sm">
                    <a href="#" data-toggle="modal" data-target="#booktable"><img src="images/icon-table.png" alt=""><span class="book-table-p">Book a Table</span></a>
                </div>
                <?php include("top_menu.php"); ?>
                <div class="logo">
				<?php
				 $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);  
				 $style = ($curPageName!='index.php')?"style='max-width:150px'":"";  
				?>
                    <a href="index.php" <?php echo $style; ?>><img src="<?php echo $logo; ?>" alt=""></a>
                </div>
            </div>
        </div>
    </div>
</header>
