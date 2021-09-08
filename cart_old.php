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

if(!isset($_SESSION['cart']) || $_SESSION['cart']==null)
    $cart_empty = 0;
else $cart_empty = 1;

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
                    <h2>CART ITEMS</h2>
                    <a href="index.php">Home</a>
                    <span>Cart Items</span>
                </div>
            </div>
        </section>

        <section class="home-icon shop-cart bg-skeen">
            <div class="icon-default icon-skeen">
                <img src="./images/scroll-arrow.png" alt="">
            </div>
            <div class="container" style="margin: auto">
                <div class="checkout-wrap wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                    <ul class="checkout-bar">
                        <li class="active"><a href="cart.php">Shopping Cart</a></li>
                        <li class=""><a href="checkout.php">Checkout</a></li>
                        <li>Complete</li>
                    </ul>
                </div>
                <form class="form" method="post" name="cart_detail" id="cart_detail">
                    <div class="shop-cart-list wow fadeInDown hidden-xs" data-wow-duration="1000ms" data-wow-delay="300ms">
                        <table class="shop-cart-table">
                            <thead>
                            <tr>
								<th>CATEGORY</th>
                                <th>ITEM</th>
                                <th>PRICE</th>
                                <th>QUANTITY</th>
                                <th>TOTAL</th>
                            </tr>
                            </thead>
                            <tbody id="cart_table">
                            </tbody>
                        </table>
                        <div class="product-cart-detail">
                            <button type="button" class="btn-main btn-small btn-primary" style="border-radius: 4px"><a href="categories.php" style="color:#fff">Select More Items</a></button>
                            <input name="update_cart" id="update_cart"  value="UPDATE CART" class="btn-main btn-small btn-primary pull-right" style="border-radius: 4px;color:#fff" type="submit">

                        </div>
                    </div>
                    <div class="shop-cart-list wow fadeInDown hidden-sm hidden-md hidden-lg" data-wow-duration="1000ms" data-wow-delay="300ms">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>PRODUCT</th>
                                <th>QUANTITY</th>
                            </tr>
                            </thead>
                            <tbody id="sm_cart_table">
                            </tbody>
                        </table>
                        <div class="product-cart-detail">
                            <button type="button" class="btn-main btn-small btn-primary" style="border-radius: 4px"><a href="categories.php"  style="color:#fff">Select More Items</a></button>
                            <input name="update_cart" id="update_cart"  value="UPDATE CART" class="btn-main btn-small btn-primary pull-right" style="border-radius: 4px;color:#fff" type="submit">
                        </div>
                    </div>


                </form>
                <div class="cart-total wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms" style="text-align: center">
                    <div class="cart-total-title">
                        <h5>CART TOTALS</h5>
                    </div>
                    <div id="price_summary">
                    </div>
                    <div class="proceed-check">
                        <?php
                        if(isset($_SESSION['group_master'])){?>
                            <a href="#" class="btn-main btn-small btn-primary" style="text-align: center; border-radius: 4px; color:#fff" onclick="submitItem()">SUBMIT YOUR ITEMS</a>
                        <?php }
                        else{ ?>
                            <button type="button" class="btn-main btn-small btn-primary" style="border-radius: 4px"><a href="javascript:void(0)"  onclick="proceedToPayment()" style="color:#fff">PROCEED TO CHECKOUT</a></button>


                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="modal fade " id="cart_confirmation" tabindex="-2" role="dialog" aria-labelledby="booktable">
                <div class="modal-dialog modal-sm" role="document" style="max-width: 90% ">
                    <div class="modal-content">
                        <div class="modal-body" style="padding-left: 0px; padding-right: 0px">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                            <div id="order-div">
                                <div class="title text-center">
                                    <h4 class="text-coffee left">Would you like to select more items<span id="ord_title_vw"></span></h4>
                                </div>
                                <div class="buttons_wrapper" style="padding-bottom: 15px">
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-right" style="">
                                        <button type="button" class="btn-main btn-small btn-primary" style="border-radius: 4px"><a href="categories.php" style="color: white">Select More Items</a></button>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <button type="button" class="btn-main btn-small btn-primary" style="border-radius: 4px" ><a href="checkout.php" style="color: white">Proceed to Checkout</a></button>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
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
    cart_empty = <?php echo $cart_empty; ?>

    if(cart_empty==0) window.location.href = project_url+'categories.php'

    showCart()

    $(document).ready(function () {
        var customer_id = "<?php echo $customer_id; ?>";
        $('body').on("click", ".dropdown-menu", function (e) {
            $(this).parent().is(".open") && e.stopPropagation();
        });

        $('#load_more_not_button').click(function() {
            $(this).toggleClass('active');
            show_notifications(customer_id);
        });

    });


    $("#price_summary").load("views/order_price_summary.php");

    submitItem= function submitItem() {
        //alert('checkout')
        $.ajax({
            url: project_url +"includes/controller/ecommerceController.php",
            dataType: "json",
            type: "post",
            async:false,
            data: {
                q: "checkout"
            },
            success: function(data) {
                //alert('ok')
                //data = JSON.parse(data)
                //console.log(data.message)
                //return false;
                if(data.message =='111'){
                    window.location.href = project_url;
                }
                else if(data.message =='222'){
                    //alert('group')
                    window.location.href= project_url+"account.php";
                }
            }

        })
    }

    function cart_View(){
        $.ajax({
            url: project_url +"includes/controller/ecommerceController.php",
            dataType: "json",
            type: "post",
            async:false,
            data: {
                q: "viewCartSummery"
            },
            success: function(data) {
                var html, html_xs = '';

                if(!jQuery.isEmptyObject(data.records)){
                    var total = 0;
                    var sub_total = 0;
                    var count =0
                    $.each(data.records, function(i,datas){

                        sub_total += (parseFloat(datas.discounted_rate)*parseFloat(datas.quantity)).toFixed(2);

                        html+=' <tr>\n' +
                            '                            <td>\n' +
                            '                                <div class="product-cart" style="">\n' +
                            '                                    <img src="'+project_url+'/admin/'+datas.item_image+'" alt="" style="height: 80px; width: 80px; border-radius: 10px;'+ item_image_display +'">\n' +
                            '                                    <span class="text-capitalize">'+datas.category_name+'</span>\n' +
                            '                                </div>\n' +
                            '                            </td>\n' +
                                                        '<td>\n' +
                            '                                <div class="product-cart" style="">\n' +
                            '                                    <span class="text-capitalize">'+datas.item_name+'</span>\n' +
                            '                                </div>\n' +
                            '                            </td>\n' +
							'                            <td>\n' +
                            '                                <strong>'+ currency_symbol+''+parseFloat(datas.discounted_rate).toFixed(2)+'</strong>\n' +
                            '                            </td>\n' +
                            '                            <td>\n' +
                            '                                <div class="price-textbox">\n' +
                            '                                    <span class="minus-text" onclick="minusProd('+datas.item_id+')" style="padding-left: 20px;font-size: 30px;top: 10px !important;">-</span>' +
                            '                                    <input type="hidden" name="cart_key[]" value="'+i+'"/>\n' +
                            '                                    <input name="quantity[]" id="quantity_'+datas.item_id+'" placeholder="'+datas.quantity+'" type="text" value="'+datas.quantity+'">\n' +
                            '                                    <span class="plus-text" onclick="addProd('+datas.item_id+')"style="padding-left: 20px;font-size: 30px;top: 10px !important;">+</span>\n' +
                            '                                </div>\n' +
                            '                            </td>\n' +
                            '                            <td>\n' +
                            '                                '+ currency_symbol+''+parseFloat(datas.discounted_rate).toFixed(2)+' * '+datas.quantity+'='+ currency_symbol+''+(datas.discounted_rate * datas.quantity).toFixed(2)+'\n' +
                            '                            </td>\n' +
                            '                            <td class="shop-cart-close"><button type="button" class="btn-danger icon-cancel-5" style="border-radius: 4px" onclick=deleteCartItem("'+i+'")></button></td>\n' +
                            '                        </tr>';

                        html_xs+=' <tr>\n' +
                            '                            <td>\n' +
                            '                                <div class="product-cart" style="">\n' +
                            '                                    <span class="text-capitalize">'+datas.item_name+' ('+ currency_symbol+''+parseFloat(datas.discounted_rate).toFixed(2)+')</span>\n' +
                            '                                </div>\n' +
                            '                            </td>\n' +
                            '                            <td>\n' +
                            '                                <div class="price-textbox">\n' +
                            '                                    <span class="minus-text" onclick="minusProd('+datas.item_id+')"><i class="icon-minus"></i></span>' +
                            '                                    <input type="hidden" name="cart_key[]" value="'+i+'"/>\n' +
                            '                                    <input name="quantity[]" id="quantity_'+datas.item_id+'" placeholder="'+datas.quantity+'" type="text" value="'+datas.quantity+'">\n' +
                            '                                    <span class="plus-text" onclick="addProd('+datas.item_id+')"><i class="icon-plus"></i></span>\n' +
                            '                                </div>\n' +
                            '                            </td>\n' +
                            '                            <td class="shop-cart-close"><i class="icon-cancel-5" onclick=deleteCartItem("'+i+'")></i></td>\n' +
                            '                        </tr>';



                        count++;
                        total += sub_total ;
                    });


                    var sWidth = window.screen.width;

                    //alert("sWidth is: " + sWidth);

                    // $('#quantity_15').val(15)

                    total = parseFloat(total).toFixed(2);
                    html += '<div class="subtotal"><div class="col-md-6 col-sm-6 col-xs-6"><h6>Subtotal :</h6></div><div class="col-md-6 col-sm-6 col-xs-6"><span>Tk '+total+'</span></div></div>';
                    html  += '<div class="cart-btn"><div class="col-sm-6"><a href="cart.php" class="btn-main checkout">VIEW ALL</a></div><div class="col-sm-6"><a href="checkout.php" class="btn-main checkout">CHECK OUT</a></div></div>';
                    $('#total_item_in_cart').html(count);
                }
                else{
                    $('#total_item_in_cart').html(0);
                    html = "<h6>You have no items in your cart</h6>";
                }
                if(sWidth<601){
                    $('#sm_cart_table').html(html_xs);
                }
                else {
                    $('#cart_table').html(html);
                }


            }
        });
        showCart()
    }
    cart_View()



    // send mail if forget password
    $('#update_cart, #update_cart_xs').click(function(event){
        event.preventDefault();
        var formData = new FormData($('#cart_detail')[0]);
        formData.append("q","update_cart");
        $.ajax({
            url: project_url +"includes/controller/ecommerceController.php",
            type:'POST',
            data:formData,
            async:false,
            cache:false,
            contentType:false,processData:false,
            success: function(data){
                cart_View()
                price_view()
            }
        });
        showCart()
    })

    deleteCartItem = function (cart_key) {
        deleteItem(cart_key);
        cart_View()
        price_view()
        showCart()

    }

    function addProd(cart_key){
        var qty = parseFloat($('#quantity_'+cart_key).val());
        $('#quantity_'+cart_key).val(qty+1);
    }

    function minusProd(cart_key){
        var qty = parseFloat($('#quantity_'+cart_key).val());
        if(qty>1)  $('#quantity_'+cart_key).val(qty-1);
    }

    function proceedToPayment() {
        $('#cart_confirmation').modal()
    }

</script>


