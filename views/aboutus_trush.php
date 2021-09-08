<?php
session_start();
include("../includes/dbConnect.php");
include("../includes/dbClass.php");
$dbClass = new dbClass;
?>
<section class="term-condition home-icon">
    <div class="icon-default">
        <a href="#"><img src="images/scroll-arrow.png" alt=""></a>
    </div>
    <div class="container">
        <?php
        $li_str  = "";
        $div_str = "";
        $i 		 = 0;

        $about_us_result = $dbClass->getResultList("select * from web_menu where parent_menu_id=28");
        foreach ($about_us_result as $row){
            extract($row);
            if($i == 0) $class = "class='active'";
            else 		    $class = "";

            $li_str  .= "<li $class><a href='#$menu'>$title</a></li>";
            $div_str .= "<div class='terms-left padding-top-160' id='$menu'>							
							   <h5>$title</h5>
								$description
								</div>";
            $i++;
        }
        ?>
        <div class="col-md-9 col-sm-8 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
            <?php echo $div_str; ?>
        </div>
        <div class="col-md-3 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
            <div class="terms-right">
                <ul>
                    <?php echo $li_str; ?>
                </ul>
            </div>
        </div>
    </div>
</section>
                <!-- end term condition -->
<section class="chef-part home-icon home-small-pad wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
    <div class="icon-default">
        <img src="images/icon11.png" alt="">
    </div>
    <div class="container">
        <div class="build-title">
            <h2>Our Awesome Team</h2>
            <h6>Our Production & Service Team</h6>
        </div>
        <div class="service-port odd wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <img src="./images/team1.jpg" alt="" class="round-color border-1px">
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <img src="./images/team2.jpg" alt="" class="round-color border-1px">
                </div>
            </div>
        </div>
    </div>
</section>


<script>
	
</script>
