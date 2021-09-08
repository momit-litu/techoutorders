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


    <!-- End Header -->
    <!-- Start Main -->
    <div class="main-part" id="content" style="min-height: 600px;" >

        <?php
        include 'views/categories.php';
        ?>
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


</script>

<script>
    var url_info = location.search.split('?')[1];

    if(url_info && url_info.split('=')[0]=='groupmaster'){
        //alert('ok')
        var tem = url_info.split('=')[1].split('&')
        //alert(tem[0])
        //var data=[]
        var formdata = new FormData();
        formdata.append('group_order_details_id', tem[0]);
        formdata.append('order_key' , tem[1])
        formdata.append('q','group_member_order')
        //alert('safdds')
        $.ajax({
            url: project_url +"includes/controller/groupController.php",
            type:'POST',
            data:formdata,
            async:false,
            cache:false,
            contentType:false,processData:false,
            success: function(data){
                //alert(data)

                if(data==1){
                    window.location.replace(project_url+"categories.php")
                }
                else {
                    alert("This Link No More Valid, You are Redirected to Home Page")
                    window.location.replace(project_url)


                }
            }
        });
    }


    $('#delete_cart').on('click', function () {
        //alert('sdf')
        $('#cart_empty').modal('hide');

        $.ajax({
            url: "includes/controller/groupController.php",
            dataType: "json",
            type: "post",
            async: false,
            data: {
                q: "check_cart",
            },
            success: function (data) {
                showCart()
            }
        })
    })

    load_category = function load_category() {
        //alert(project_url)
        $.ajax({
            url:"includes/controller/itemsController.php",
            dataType: "json",
            type: "post",
            async: false,
            data: {
                q: "category_view",
            },
            success: function (data) {
                //alert(data);
                //alert('sdfs')
                //alert(data[0]['id'])
                var category_html=''
                for(var i=0 ; i<data.length; i++){
                    category_html= category_html+ categoryView(data[i])
                }
                $('#catagories').html(category_html)
                //for  showing grid's no of records from total no of records
            }
        });
    }

    load_category();
    function categoryView(data){
        //alert(data['id'])
        var name = data['name'].split(' ').join('__')

        var html='                <div class="col-md-3 col-sm-4 col-xs-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">\n' +
            '                    <div class="shop-main-list" style=" border-radius: 15px; border: 1px solid #fbc314; max-width: 220px;" >\n' +
            '                        <div class="shop-product" style="border-radius: 15px 15px 0px 0px; padding-bottom: 0px; margin-bottom: 0px">\n' +
            '                            <img src="admin/'+data['photo']+'" alt="" style="border-radius: 17px 17px 0px 0px">\n' +
            '                        <div style=" padding-top: 8px; padding-bottom: 8px; border-radius: 0px 0px 17px 17px">\n' +
            '                             <a href="'+project_url+'menu.php?category='+ name+'"><h5 style="text-transform: uppercase">'+ data['name']+'</h5></a>' +
            '                        </div>\n'+
            '                            <div class="cart-overlay-wrap" style="border-radius: 17px 17px 17px 17px">\n' +
            '                                <div class="cart-overlay" >\n' +
            '                                    <a href="'+project_url+'menu.php?category='+ name+'" class="shop-cart-btn">Choose Menu</a>\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>' +

            '                    </div>\n' +
            '                </div>\n'

        return html;

    }
</script>