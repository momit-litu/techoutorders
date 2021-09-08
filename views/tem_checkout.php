<main>
    <div class="main-part">
        <!-- Start Breadcrumb Part -->
        <!-- End Breadcrumb Part -->
        <section class="home-icon shop-cart bg-skeen">
            <div class="icon-default icon-skeen">
                <img src="../images/scroll-arrow.png" alt="">
            </div>
            <div class="container" id="test">
            </div>
        </section>
    </div>
</main>


<script>
    function cartView(){
        //alert('ok')
        $.ajax({
            url: "includes/controller/ecommerceController.php",
            dataType: "json",
            type: "post",
            async:false,
            data: {
                q: "test_mail_group"
            },
            success: function(data) {
                //alert(data);
                $('#test').html(data)

            }
        });
    }
    cartView()
</script>

