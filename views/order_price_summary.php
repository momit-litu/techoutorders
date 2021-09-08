<div class="product-cart-total">
    <small>Order Price</small>
    <span id="items_price"></span>
</div>
<div class="product-cart-total">
    <small>Discount</small>
    <span id="discount"></span>
</div>
<div class="product-cart-total">
    <small>Tax</small>
    <span id="tax"></span>
</div>
<div class="grand-total">
    <h5>TOTAL <span id="total_amount"></span></h5>
</div>

<script>
    price_view = function () {
        $.ajax({
            url: project_url +"includes/controller/ecommerceController.php",
            dataType: "json",
            type: "post",
            async:false,
            data: {
                q: "viewPriceSummery"
            },
            success: function(data) {
                if(data){
                    $('#items_price').html(currency_symbol+''+ data['total_price'].toFixed(2))
                    $('#discount').html(currency_symbol+'' +data['discount'].toFixed(2))
                    $('#tax').html(currency_symbol+''+ data['tax_amount'].toFixed(2))
                    $('#total_amount').html(currency_symbol+'' +data['discounted_price'].toFixed(2))
                }
            }
        });

    }
    price_view();
</script>