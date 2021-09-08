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
$item_image = $dbClass->getDescription('item_image_display');

?>
<script>
    customer_id=<?php echo $customer_id;?>;
    image_item =<?php echo $item_image; ?>

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
                    <h2 id="category_name_head" class="text-uppercase">CATEGORY ITEMS</h2>
                    <a href="index.php">Home</a>
                    <span>Category Items</span>
                </div>
            </div>
        </section>
        <section class="special-menu home-icon">
            <div class="icon-default">
                <img src="./images/scroll-arrow.png" alt="">
            </div>
            <?php
            if(isset($_SESSION['group_master'])){?>
                <div class="container" id="group_order" style="display: block">
                    <p class="text-capitalize alert alert-warning" >
                        You are selecting Items for a Group Order, initiated by <b id="group_master_name"><?php echo $_SESSION['group_master']; ?></b> and TakeOut time is <b id="takeout_time"><?php echo $_SESSION['delivery_date']; ?></b>.
                        <button class='btn btn-danger btn-xs' id='clear_group_order' onclick="clear_group_order()">Clear</button>
                    </p>
                </div>
            <?php } ?>
            <div class="container">
                <div class="portfolioContainer row" id="menus">
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


    showCart()



</script>


<script>

    //alert(image_item)
    var width=''
    //var item_image_display="display: block"
    if(image_item==0){
        item_image_display="display: none"
        width='style="padding-left: 0px"'
    }
    else {
        item_image_display="display: block"

    }

    var menus = location.search.split('category=')[1];
    menus = menus.split('__').join(' ')
    $('#category_name_head').html(menus)


    html_generator = function html_generator(data) {
        price = parseFloat(data['price'])>0? currency_symbol+ data['price']:'';

        var html_op ='                <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">\n' +
            '                    <div class="shop-main-list" style="background-color: #e4b95b; border-radius: 15px">\n' +
            '                        <div class="shop-product" style="border-radius: 15px 15px 0px 0px">\n' +
            '                            <img src="'+ project_url+'admin/'+data['photo']+'" alt=""style="height: 220px; width:100%">\n' +
            '                            <div class="cart-overlay-wrap" style="border-radius: 0px">\n' +
            '                                <div class="cart-overlay" >\n' +
            '                                    <p onclick="cart('+data['item_id']+','+data['rate']+')" class="shop-cart-btn">Add to Cart</p>\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                                <a href="'+project_url+'item.php&id='+ data['item_id']+'"><h5 class="text-capitalize">'+ data['name']+' <span style="color: white">'+ currency_symbol+''+data['rate']+'</span></h5></a>\n' +
            '                    </div>\n' +
            '                </div>\n'


        var html='<div class="col-md-6 col-sm-6 col-xs-12 isotope-item breakfast">\n' +
            '         <div class="menu-list" '+width+'>\n' +
            '              <a href="'+project_url+'item.php?id='+ data['item_id']+'">' +
            '              <span class="menu-list-product" style="'+ item_image_display +'">\n' +
            '                <img  src="'+ project_url+'admin/'+data['photo']+'" onclick="cart('+data['item_id']+','+data['rate_id']+')" alt="" style="">\n' +
            '              </span>\n' +
            '                   <h5 class="text-capitalize margin-bottom-0 padding-bottom-4">'+ data['name']+' <span>'+ price +'</span></h5>' +
            '               </a>\n' +
            '              <p>'+ data['details']+'</p>\n' +
            '          </div>\n' +
            '        </div>\n'

        return html;

    }

    cart = function cart(id, rate_id){

        $.ajax({
            url: project_url +"includes/controller/ecommerceController.php",
            dataType: "json",
            type: "post",
            async:false,
            data: {
                q: "addToCart",
                item_id:id,
                quantity:1,
                rate_id:rate_id
            },
            success: function(data) {
                if(!jQuery.isEmptyObject(data.records)){
                    var html = '';
                    var total = 0;
                    var sub_total = 0;
                    var count =0
                    $.each(data.records, function(i,datas){
                        sub_total += parseFloat(datas.discounted_rate)*(datas.quantity);
                        html += '<div class="cart-item"><div class="cart-item-left"><img src="admin/images/item/'+datas.product_image+'" alt=""></div><div class="cart-item-right"><h6>'+datas.product_name+'</h6><span> '+datas.discounted_rate+' * '+datas.quantity+' = '+sub_total+'</span></div><span class="delete-icon" onclick="deleteProduct('+"'"+datas.cart_key+"'"+')"></span></div>';
                        count++;
                        total += sub_total ;
                    });
                    total = total.toFixed(2);
                    html += '<div class="subtotal"><div class="col-md-6 col-sm-6 col-xs-6"><h6>Subtotal :</h6></div><div class="col-md-6 col-sm-6 col-xs-6"><span>Tk '+total+'</span></div></div>';
                    html  += '<div class="cart-btn"><a href="cart.php" class="btn-main checkout">VIEW ALL</a><a href="checkout.php" class="btn-main checkout">CHECK OUT</a></div>';
                    $('#total_product_in_cart').html(count);
                    success_or_error_msg('#added_to_cart_message','info',"Added to cart" ,"#added_to_cart_message");

                }
                else{
                    $('#total_product_in_cart').html(0);
                    html = "<h6>You have no items in your cart</h6>";
                }
                $('#cart_div').html(html);

            }
        });
        showCart()

    }

    load_menu = function load_menu(menu) {
        $.ajax({
            url: project_url + "includes/controller/itemsController.php",
            dataType: "json",
            type: "post",
            async: false,
            data: {
                q: "menu_view",
                menu: menu,
            },
            success: function (data) {
                var menu_html=''
                for(var i=0 ; i<data.length; i++){
                    menu_html = menu_html+ html_generator(data[i])

                }
                $('#menus').html(menu_html)
                //for  showing grid's no of records from total no of records
            }
        });
    }
    load_menu(menus)


</script>



