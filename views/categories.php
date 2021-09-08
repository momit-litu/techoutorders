 <section class="breadcrumb-part" data-stellar-offset-parent="true" data-stellar-background-ratio="0.5" style="background-image: url('./images/breadbg1.jpg');max-height: 240px">
    <div class="container">
            <div class="breadcrumb-inner" style="top:45%">
				<div class="col-md-2 col-xs-12 small-center"></div>
				<div class="col-md-8 col-xs-12 small-center">
					<div class="col-md-12 col-xs-12  small-center">
						<a href="/admin/document/BurritoBrothersMenu.pdf" class="btn-small" title="Download PDF"><i class="fa fa-file-pdf-o" style="font-size:40px; font-weight:bold; color:red"></i>
						</a>
					</div>
					<h2 >ORDER FROM MENU BELOW FOR PICKUP</h2>
				</div>
				<div class="col-md-2 col-xs-12 small-center">
					<img src="images/qr-image.png" style="width:100px; border:2px solid white; border-radius:4px">
				</div>
                <!--<a href="index.php">Home</a>
                <span>Items Category</span>-->
                <div class="col-md-12 col-xs-12  small-center">
                    <a  href="http://www.yelp.com/biz/burrito-brothers-washington?osq=breakfast+burrito"  class="btn-main btn-small btn-primary">
                        <!--img src="images/delivery-service-logo.png" style="width:50px"/-->
                        Order for Delivery
                    </a>
                </div>
            </div>

    </div>


 </section>
    <!-- End Breadcrumb Part -->
 <div class="modal fade booktable" id="cart_empty" tabindex="-1" role="dialog" aria-labelledby="booktable">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-body">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <div class="table-title">
                     <h2></h2>
                     <h4 class="heade-xs">You have some items in your cart, would you like to empty your cart?</h4>
                 </div>
                 <div class="row">
                     <div class="col-md-6 col-sm-6 col-xs-6">
                         <button class="btn-main btn-small" id="delete_cart" style="border-radius: 8px">Delete Cart Items</button>
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-6">
                         <button class="btn-main btn-small" style="border-radius: 8px" class="close" data-dismiss="modal" >Keep Cart Items</button>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>


 <section class="home-icon blog-main-section shop-page">

     <div class="icon-default" >
            <img src="./images/scroll-arrow.png" alt="">

        </div>

     <?php
     if(isset($_SESSION['cart']))
        $cart_check = $_SESSION['cart'];

     if(isset($_SESSION['group_master'])){
     if(isset($cart_check)){
         if($cart_check!=[]){
             //echo 12;
             ?>
             <script>
                 $('#cart_empty').modal()
             </script>
         <?php
         }
     }
         ?>
			<div class="container" id="group_order" style="display: block">
				<p class="text-capitalize alert alert-warning" >
				You are selecting Items for a Group Order, initiated by <b id="group_master_name"><?php echo $_SESSION['group_master']; ?></b> and TakeOut time is <b id="takeout_time"><?php echo $_SESSION['delivery_date']; ?></b>.
				<button class='btn btn-danger btn-xs' id='clear_group_order' onclick="clear_group_order()">Clear</button>
				</p>
			</div>
     <?php } ?>
        <div class="container" id="catagories">
        </div>
    </section>



