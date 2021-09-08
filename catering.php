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
<script>
    customer_id=<?php echo $customer_id;?>
</script>
<div class="wrapper">
    <!-- Start Header -->
    <?php
    include 'views/layout/header.php';
    ?>
    <!-- End Header -->
    <!-- Start Main -->
    <?php
    include 'views/layout/auth_modal.php';
    ?>

    <!-- Start Main -->
    <div class="main-part" id="content" style="min-height: 600px;" >

        <section class="breadcrumb-part" data-stellar-offset-parent="true" data-stellar-background-ratio="0.5" style="background-image: url('./images/breadbg1.jpg');max-height: 220px">
            <div class="container">
                <div class="breadcrumb-inner">
                    <h2>CATERING SERVICE</h2>
                    <a href="index.php">Home</a>
                    <span>Catering Service</span>
                </div>
            </div>
        </section>
        <section class="term-condition home-icon">
            <div class="icon-default">
                <img src="./images/scroll-arrow.png" alt="">
            </div>
            <div class="container">
                <h3>Techoutorders Offers</h3>
                <ul>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b >Beef Samosas (MOST ORDERED)<br>
						Ksh 4700<br>
						serves 25<br>
						</b>
						Savory turnovers stuffed with beef. Be sure to purchase utensils if you'll need them (in the Miscellaneous category below).
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b >Fried Plantains <br>
						Ksh 7000<br>
						serves 40<br>
						</b>
						Be sure to purchase utensils if you'll need them (in the Miscellaneous category below).Veggie Samosas
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b >Veggie Samosas <br>
						Ksh 4700<br>
						serves 25<br>
						</b>
						Savory turnovers stuffed with vegetables. Be sure to purchase utensils if you'll need them (in the Miscellaneous category below).
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b>Fruit Tray <br>
						Ksh 6500<br>
						serves 35<br>
						</b>
						Fresh-cut seasonal fruit. Be sure to purchase utensils if you'll need them (in the Miscellaneous category below).
					<li>
					<br>

					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b>
						Pita Bread w/ Hummus<br>
						Ksh 6500<br>
						serves 40
						</b>
						Be sure to purchase utensils if you'll need them (in the Miscellaneous category below).
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b>
						HOT ENTREES<br>
						Seasoned Oven-Roasted Chicken<br>
						Ksh 7000<br>
						MOST ORDEREDserves 40<br>
						</b>
						Be sure to purchase utensils if you'll need them (in the Miscellaneous category below).
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b>
						Beef Curry<br>
						Ksh 13000<br>
						serves 35<br>
						</b>
						Be sure to purchase utensils if you'll need them (in the Miscellaneous category below).
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b>
						Bone-In Chicken Curry<br>
						Ksh 8000<br>
						serves 35<br> </b>
						Be sure to purchase utensils if you'll need them (in the Miscellaneous category below).
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b>Goat Curry <b>
						Ksh 15000<br>
						serves 35<br/b>
						Be sure to purchase utensils if you'll need them (in the Miscellaneous category below).
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b>
						RICE
						Pilau Rice<br>
						Ksh 6000<br>
						MOST ORDEREDserves 35<br>
						</b>
						Rice pilaf. You can usually order a side of rice for just half your headcount. Be sure to purchase utensils if you'll need them (in the Miscellaneous category below).
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b>
						Jollof Rice<br>
						Ksh 6000<br>
						serves 35<br> </b>
						Rice with tomatoes, onions, salt, and spices. You can usually order a side of rice for just half your headcount. Be sure to purchase utensils if you'll need them (in the Miscellaneous category below).
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b>White Rice<br>
						Ksh 3000<br>
						serves 35<br></b>
						You can usually order a side of rice for just half your headcount. Be sure to purchase utensils if you'll need them (in the Miscellaneous category below).
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b>SALADS & SIDES<br>
						Coconut Curry Red Kidney Beans<br>
						Ksh 6500<br>
						MOST ORDERED serves 35<br></b>
						You can usually order a side of beans for just half your headcount.
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b>Mixed Vegetables<br>
						Ksh 6000<br>
						serves 35<br></b>
						Be sure to purchase utensils if you'll need them (in the Miscellaneous category below).
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b>Caesar Salad<br>
						Ksh 6000<br>
						serves 35<br></b>
						With your choice of dressing.
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b>String Beans<br>
						Ksh 6000<br>
						serves 35<br></b>
					</li>
					<br>
					<li style="font-size: 16px">
						<i class="material-icons" style="font-size:20px; color: #FBAD50">restaurant</i>    
						<b>MISCELLANEOUS<br>
						Utensils<br>
						Ksh 150<br>
						MOST ORDERED</b>
					</li>
                </ul>
            </div>
        </section>

    </div>

    <!-- End Main -->
    <!-- Start Footer -->
    <?php
    include 'views/layout/footer.php';
    ?>
    <!-- End Footer -->

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

    showCart()

</script>




