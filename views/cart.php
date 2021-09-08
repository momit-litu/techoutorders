<?php
session_start();
if(!isset($_SESSION['cart']) || !count($_SESSION['cart'])>0) {

    echo'<script> window.location=project_url+"index.php?page=categories"; </script> ';
}
?>
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
                <li class="active"><a href="index.php?page=cart">Shopping Cart</a></li>
                <li class=""><a href="checkout.php">Checkout</a></li>
                <li>Complete</li>
            </ul>
        </div>
        <form class="form" method="post" name="cart_detail" id="cart_detail">
            <div class="shop-cart-list wow fadeInDown hidden-xs" data-wow-duration="1000ms" data-wow-delay="300ms">
                <table class="shop-cart-table">
                    <thead>
                    <tr>
                        <th>PRODUCT</th>
                        <th>PRICE</th>
                        <th>QUANTITY</th>
                        <th>TOTAL</th>
                    </tr>
                    </thead>
                    <tbody id="cart_table">
                    </tbody>
                </table>
               <!-- <div class="product-cart-detail">
					<button type="button" class="btn-main btn-small btn-primary" style="border-radius: 4px"><a href="index.php?page=categories" style="color:#fff">Select More Items</a></button>
                    <input name="update_cart" id="update_cart"  value="UPDATE CART" class="btn-main btn-small btn-primary pull-right" style="border-radius: 4px;color:#fff" type="submit">
				</div> -->
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
                <!--<div class="product-cart-detail">
					<button type="button" class="btn-main btn-small btn-primary" style="border-radius: 4px"><a href="index.php?page=categories"  style="color:#fff">Select More Items</a></button>
                     <input name="update_cart" id="update_cart"  value="UPDATE CART" class="btn-main btn-small btn-primary pull-right" style="border-radius: 4px;color:#fff" type="submit">
                </div>-->
            </div>
			<div class="product-cart-detail">
				<button type="button" class="btn-main btn-small btn-primary" style="border-radius: 4px"><a href="index.php?page=categories" style="color:#fff">Select More Items</a></button>
				<input name="update_cart" id="update_cart"  value="UPDATE CART" class="btn-main btn-small btn-primary pull-right" style="border-radius: 4px;color:#fff" type="submit">
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
                                <button type="button" class="btn-main btn-small btn-primary" style="border-radius: 4px"><a href="index.php?page=categories" style="color: white">Select More Items</a></button>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <button type="button" class="btn-main btn-small btn-primary" style="border-radius: 4px" ><a href="index.php?page=checkout" style="color: white">Proceed to Checkout</a></button>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</section>


<script src="js/cart.js"></script>

<script>
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
                console.log(data)
                if(data=='111'){
                    $("#content").load("views/checkout_confirm.php");
                }
                else if(data=='222'){
                    window.location.href= project_url+"index.php?page=account";
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
                if(!jQuery.isEmptyObject(data.records)){
                    var html, html_xs = '';
                    var total = 0;
                    var sub_total = 0;
                    var count =0
                    $.each(data.records, function(i,datas){

                        sub_total += (parseFloat(datas.discounted_rate)*parseFloat(datas.quantity)).toFixed(2);

                        html+=' <tr>\n' +
                            '                            <td>\n' +
                            '                                <div class="product-cart" style="">\n' +
                            '                                    <img src="'+project_url+'/admin/images/item/'+datas.item_image+'" alt="" style="height: 80px; width: 80px; border-radius: 10px;'+ item_image_display +'">\n' +
                            '                                    <span class="text-capitalize">'+datas.item_name+'</span>\n' +
                            '                                </div>\n' +
                            '                            </td>\n' +
                            '                            <td>\n' +
                            '                                <strong>'+ currency_symbol+''+datas.discounted_rate+'</strong>\n' +
                            '                            </td>\n' +
                            '                            <td>\n' +
                            '                                <div class="price-textbox">\n' +
                            '                                    <span class="minus-text" onclick="minusProd('+datas.item_id+')"><i class="icon-minus"></i></span>' +
                            '                                    <input type="hidden" name="cart_key[]" value="'+i+'"/>\n' +
                            '                                    <input name="quantity[]" id="quantity_'+datas.item_id+'" placeholder="'+datas.quantity+'" type="text" value="'+datas.quantity+'">\n' +
                            '                                    <span class="plus-text" onclick="addProd('+datas.item_id+')"><i class="icon-plus"></i></span>\n' +
                            '                                </div>\n' +
                            '                            </td>\n' +
                            '                            <td>\n' +
                            '                                '+ currency_symbol+''+datas.discounted_rate+' * '+datas.quantity+'='+ currency_symbol+''+(datas.discounted_rate * datas.quantity).toFixed(2)+'\n' +
                            '                            </td>\n' +
                            '                            <td class="shop-cart-close"><i class="icon-cancel-5" onclick=deleteCartItem("'+i+'")></i></td>\n' +
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
                    if(sWidth<601){
                        $('#sm_cart_table').html(html_xs);
                    }
                    else {
                        $('#cart_table').html(html);
                    }

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

