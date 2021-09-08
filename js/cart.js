var currency_symbol=''

$.ajax({
    url:project_url +"includes/controller/ecommerceController.php",
    dataType: "json",
    type: "post",
    async:false,
    data: {
        q: "get_settings_details",
    },
    success: function(data){
        currency_symbol = data['records'][0]['currency_symbol']
    }
});


function showCart(){
    var width='style="width: 0px"'
    if(item_image_display=="display: none"){
        width='style="width: 0px"'
    }

    //alert('ok')
    $.ajax({
        url: project_url +"includes/controller/ecommerceController.php",
        dataType: "json",
        type: "post",
        async:false,
        data: {
            q: "viewCartSummery"
        },
        success: function(data) {
            //alert('cart index')
            if(!jQuery.isEmptyObject(data.records)){
                var html = '';
                var total = 0;
                var sub_total = 0;
                var count =0
                $.each(data.records, function(i,datas){
                    //alert(item_image_display)
                    sub_total = (parseFloat(datas.discounted_rate)*(datas.quantity)).toFixed(2);
                    discounted_rate = parseFloat(datas.discounted_rate).toFixed(2)
                    //alert(sub_total)
                    html += '<div class="cart-item"><div class="cart-item-left" '+width+'><img src="'+project_url+'/admin/images/item/'+datas.item_image+'" alt="" style="'+ item_image_display +';border-radius: 10px"></div><div class="cart-item-right"  ><h6>'+datas.item_name+'</h6><span> '+ currency_symbol+''+discounted_rate+' * '+datas.quantity+' = '+ currency_symbol+''+sub_total+'</span></div><span class="delete-icon" onclick=deleteItem("'+i+'")></span></div>';
                    count++;
                    total =parseFloat(total)+parseFloat(sub_total) ;
                });
                total = total;
                html += '<div class="subtotal"><div class="col-md-6 col-sm-6 col-xs-6"><h6>Subtotal :</h6></div><div class="col-md-6 col-sm-6 col-xs-6"><span>'+ currency_symbol+' '+ parseFloat(total).toFixed(2)+'</span></div></div>';
                html  += '<div class="cart-btn"><div class="col-sm-6 col-xs-6"><a href="'+project_url+'cart.php" class="btn-main checkout" style="padding-left: 15px !important;padding-right: 15px !important;">VIEW ALL</a></div><div class="col-sm-6 col-xs-6"><a href="checkout.php" class="btn-main checkout" style="padding-left: 15px !important;padding-right: 15px !important; font-size: small">CHECKOUT</a></div></div>';
                $('#total_item_in_cart').html(count);
            }
            else{
                $('#total_item_in_cart').html(0);
                html = "<h6>You have no items in your cart</h6>";
            }
            $('#cart_div').html(html);

        }
    });
}

function deleteItem(cart_key){
    //alert('ok')
    //alert(cart_key)
    $.ajax({
        url: project_url +"includes/controller/ecommerceController.php",
        dataType: "json",
        type: "post",
        async:false,
        data: {
            q: "removeFromCart",
            cart_key:cart_key
        },
        success: function(data) {
            //alert(data)
            $.each(data.records, function(i,datas){
                //alert(datas.cart_key)

            });
            showCart()
        }
    });
}

showCart()




