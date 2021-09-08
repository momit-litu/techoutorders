<?php
session_start();
include("../includes/dbConnect.php");
include("../includes/dbClass.php");
$dbClass = new dbClass;

if(isset($_SESSION['customer_id']) && $_SESSION['customer_id']!=""){
    $is_logged_in_customer = 1; // here will be the customer id that will come from session when the customer will login
    $customer_info = $dbClass->getSingleRow("select * from customer_infos where customer_id=".$_SESSION['customer_id']);
    $customer_id = $_SESSION['customer_id'];
}
else $is_logged_in_customer = "";
$order_id = '';
if(isset($_GET['order_id']) && $_GET['order_id']!="") $order_id =  $_GET['order_id'];
?>

<section class="breadcrumb-part" data-stellar-offset-parent="true" data-stellar-background-ratio="0.5"
         style="background-image: url('./images/breadbg1.jpg');max-height: 220px" xmlns="http://www.w3.org/1999/html"
         xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <div class="breadcrumb-inner">
            <h2 class="text-uppercase" id="page_title_item_name">ITEM NAME</h2>
            <a href="index.php">Home</a>
            <span>Item name</span>
        </div>
    </div>
</section>

<section class="home-icon shop-cart bg-skeen" style="background-color: rgba(244,242,237,1)">
    <div class="icon-default icon-skeen">
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
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 wow fadeInDown  tab-content" id="item_body" data-wow-duration="1000ms" data-wow-delay="300ms" >
                <div class="col-md-8 col-sm-8 col-xs-12" id="option_body" style="background-color: white; border-radius: 12px 12px 0px 0px; padding-top: 25px; padding-bottom: 20px; margin-bottom: 10px">
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms" style="position: sticky; alignment: right; float: right">
                    <div class="shop-checkout-right" style="margin-bottom: 10px">
                        <label>Special Instruction</label>
                        <textarea class="form-control" rows="5" style="padding-bottom: 0px; margin-bottom: 0px" id="special_instruction"></textarea>
                    </div>
                    <div class="shop-checkout-right" >
                        <!--<label id="title_right" style="text-transform: capitalize;"></label>-->
                        <div id="ingredient_summary"></div>
                        <div class="col-md-12 col-sm-12 col-xs-12 checkout-total" style="align-content: baseline"></div>
                        <div class="col-md-12 col-sm-12 col-xs-12 " >
                            <div class="col-md-9 col-sm-9 col-xs-9"><b>Sub Total</b></div>
                            <div class="col-md-3 col-sm-3 col-xs-3 text-right" id="total_price"><b>$00.00</b></div>
                        </div>

                        <button class="button-default button-default-submit" style="width: 100%; background-color: #e4b95b; color: white; margin-top: 15px" onclick="addToCart()"><b>Add to Cart</b></button>
                        <div id="select_ingredinet" class="text-center" style="display:none"></div>
                        <div id="select_side" class="text-center" style="display:none"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>

    <div class="modal fade " id="cart_confirmation" tabindex="-2" role="dialog" aria-labelledby="booktable">
        <div class="modal-dialog modal-sm" role="document" style="max-width: 90% ">
            <div class="modal-content">
                <div class="modal-body" style="padding-left: 0px; padding-right: 0px;  min-height:200px">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <div id="order-div">
                        <div class="title text-center">
                            <h4 class="text-coffee left">Your Items Has Been Added To Cart<span id="ord_title_vw"></span></h4>
                        </div>
                        <div class="buttons_wrapper" style="padding-bottom: 15px">
                            <div class="col-md-6 col-sm-6 col-xs-6 text-right" style="">
                                <button type="button" class="btn-main btn-small btn-primary" style="border-radius: 4px"><a href="index.php?page=categories" style="color: white">Select More Items</a></button>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <button type="button" class="btn-main btn-small btn-primary" style="border-radius: 4px" ><a href="index.php?page=cart" style="color: white">Proceed to Cart</a></button>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade " id="side_selection" tabindex="-2" role="dialog" aria-labelledby="booktable">
        <div class="modal-dialog modal-sm" role="document" style="max-width: 90% ">
            <div class="modal-content">
                <div class="modal-body" style="padding-left: 0px; padding-right: 0px; height: auto !important; min-height:400px">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <div id="order-div">
                        <div class="title text-center">
                            <h4 class="text-coffee left text-capitalize" id="side_item_title"><span id="ord_title_vw"></span></h4>
                        </div>
                        <div class="buttons_wrapper" style="padding-bottom: 15px" id="side_selection_body">
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <button type="button" class="btn-main btn-small btn-primary" style="border-radius: 4px" onclick="confirm()">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    var selected_ingredient={};
    var base_price=0;
    var total_price = 0;
    var selected_item_list={};
    var quantity={};
    var item_id = location.search.split('?')[1].split('&')[1].split('=')[1];
    var item_choice_limit = {} //limit for ingredient selection in a option group like
    var choosed_ingredient_number = {}
    var cart_side_check=0
    var select_ingredinet_check = 1;
    var is_combo = 0;
    var category_id = 0;


    chose_summary= function  chose_summary() {
        //console.log(quantity)
        cart_side_check=0;
        select_ingredinet_check = 1;
        var html=''
        var sub_total= 0 ;

        $.each(selected_ingredient, function (m, selected_ingredient_item){
            var ingredient_ids=''
            var ingredient_name=''
            var ingredient_list_by_option ={};
            total_price=parseFloat(selected_ingredient_item["base_price"]);
            //console.log(selected_ingredient_item)
            html+='<div class="col-md-12 col-sm-12 col-xs-12 padding-left-0"><div class="col-md-9 col-sm-9 col-xs-9 padding-left-0"><label style="text-transform: capitalize;"><b>'+selected_ingredient_item["name"]+'</b></label></div><div class="col-md-3 col-sm-3 col-xs-3  text-right">'+currency_symbol+''+selected_ingredient_item["base_price"]+'</div></div>\n'
            $.each(selected_ingredient_item, function (i, data) {
                ingredient_list_by_option[i]=''
                if(i!='id_list' && i!='ingredient_list_by_option' && i!='ingredient_name' && i!='name' && i!='price' && i!='base_price'){
                    html+='<div class="col-md-12 col-sm-12 col-xs-12 checkout-total" style="align-content: baseline; margin-top: 0px; padding: 0px">\n' +
                        '       <b>'+data['option_name']+'</b>\n' +
                        '   </div>'
                    $.each(data, function (j, ingredient) {
                        if(j!='option_name' && j!='name'){
                            html+='<div class="col-md-12 col-sm-12 col-xs-12">\n' +
                                '    <div class="col-md-9 col-sm-9 col-xs-9 text-capitalize">'+ingredient['name']+'</div>\n' +
                                '    <div class="col-md-3 col-sm-3 col-xs-3  text-right">'+currency_symbol+''+ingredient['price']+'</div>\n' +
                                '  </div>\n';
                            total_price+=parseFloat(ingredient['price'])
                            ingredient_ids+=','+j
                            ingredient_name+=ingredient['name']+', '
                            ingredient_list_by_option[i]+=ingredient['name']+', '
                        }
                    })
                }
            })

            if(!selected_ingredient[m]){
                selected_ingredient[m]={}
            }

            total_price=total_price.toFixed(2)
            selected_ingredient[m]['id_list']=ingredient_ids
            selected_ingredient[m]['ingredient_name']= ingredient_name
            selected_ingredient[m]['ingredient_list_by_option']= ingredient_list_by_option;

            html+=' <div class="col-md-12 col-sm-12 col-xs-12 checkout-total" style="align-content: baseline; margin-top: 0px; padding: 0px"></div>\n' +
                '<div class="col-md-12 col-sm-12 col-xs-12 " >\n' +
                '       <div class="col-md-9 col-sm-9 col-xs-9"><b></b></div>\n' +
                '       <div class="col-md-3 col-sm-3 col-xs-3 text-right"><b>'+currency_symbol+''+total_price+'</b></div>\n' +
                '  </div>\n'+
                '    <div class="col-md-12 col-sm-12 col-xs-12 " >\n' +
                '       <div class="col-md-5 col-sm-5 col-xs-9"></div>\n' +
                '       <div class="col-md-4 col-sm-4 col-xs-9">X</div>\n' +
                '       <div class="col-md-3 col-sm-3 col-xs-3 text-right"><b>'+quantity[m]+'</b></div>\n' +
                '  </div>\n'+
                ' <div class="col-md-12 col-sm-12 col-xs-12 checkout-total" style="align-content: baseline;margin-top: 0px; padding: 0px"></div>\n' +
                '<div class="col-md-12 col-sm-12 col-xs-12 margin-bottom-20">\n' +
                '       <div class="col-md-9 col-sm-9 col-xs-9 text-capitalize" style="color: #8a6d3b">'+selected_ingredient_item["name"]+'</div>\n' +
                '       <div class="col-md-3 col-sm-3 col-xs-3 text-right"><b>'+currency_symbol+''+total_price*quantity[m]+'</b></div>\n' +
                '  </div>'
            sub_total+=total_price*quantity[m];
            selected_ingredient[m]['price']=total_price
        })
        $('#ingredient_summary').html(html)
        $('#total_price').html(currency_symbol+''+sub_total)

        return false;
    }

    var add_to_meal=function add_to_meal(option_id,option_name,ing_id,ing_rate,ing_name, cat_id, this_item_id, this_item_name, this_item_price){
        //console.log(choosed_ingredient_number)

        if(!selected_ingredient[this_item_id]){
            selected_ingredient[this_item_id]={'name':this_item_name, 'price':this_item_price, 'base_price':this_item_price}
        }
        if(selected_ingredient[this_item_id][option_id]){

            if(selected_ingredient[this_item_id][option_id][ing_id]){
                choosed_ingredient_number[this_item_id][option_id] = choosed_ingredient_number[this_item_id][option_id]-1;
                delete selected_ingredient[this_item_id][option_id][ing_id];
            }
            else{
                if(item_choice_limit[this_item_id][option_id]['maximum']>choosed_ingredient_number[this_item_id][option_id] || item_choice_limit[this_item_id][option_id]['maximum']==0){
                    choosed_ingredient_number[this_item_id][option_id] = choosed_ingredient_number[this_item_id][option_id]+1;
                    selected_ingredient[this_item_id][option_id][ing_id]={'name':ing_name, 'price':ing_rate}
                }
                else if(item_choice_limit[this_item_id][option_id]['required']==1 && item_choice_limit[this_item_id][option_id]['maximum']==1){
                    selected_ingredient[this_item_id][option_id] = {}
                    selected_ingredient[this_item_id][option_id]={'option_name':option_name}
                    selected_ingredient[this_item_id][option_id][ing_id]={'name':ing_name, 'price':ing_rate}
                    chose_summary()

                    return 2;
                }
                else{
                    return 0;
                }
            }
        }
        else {
            if(!choosed_ingredient_number[this_item_id]){
                choosed_ingredient_number[this_item_id]={}
                selected_ingredient[this_item_id]={}
            }
            choosed_ingredient_number[this_item_id][option_id] = 1;
            selected_ingredient[this_item_id][option_id]={'option_name':option_name}
            selected_ingredient[this_item_id][option_id][ing_id]={'name':ing_name, 'price':ing_rate}
        }
        chose_summary()
        return 1;

    }

    // Reduce the number of main menu
    minusProd = function minusProd(id){
        if(!quantity[id]){
            quantity[id]=1
        }
        id_= 'item_'+id;
        quantity[id] = parseInt($('#'+id_).val())-1;
        $('#'+id_).val(quantity[id])
        chose_summary()
    }

    // add the number of main menu
    addProd = function addProd(id){
        if(!quantity[id]){
            quantity[id]=1
        }
        id_= 'item_'+id;
        quantity[id] = parseInt($('#'+id_).val()) +1;
        $('#'+id_).val(quantity[id])

        chose_summary()
    }

    //Set the header for the main menu like name, id, quantity  minimum price etc
    set_item_header = function set_item_header(data) {
        base_price=parseFloat(data['price']);
        quantity[data['item_id']]=1;
        if(data['is_combo']==1){
            cart_side_check = 1;
        }
        $('#total_price').html(currency_symbol+''+base_price)


        selected_ingredient[data['item_id']]={'name':data['name'], 'price':data['price'], 'base_price':data['price']}



        $('#title_right').html(data['name'])
        $('#page_title_item_name').html(data['name'])
        var html='<div class="row"><div  class="col-md-8 col-sm-6 col-xs-7"><h4 style="text-transform: uppercase;" id="item_name">'+data['name']+' ('+currency_symbol+'<b>'+data['price']+')</b></h4></div>' +
            '     <div class="price-textbox price-textbox-item col-md-2 col-sm-6 col-xs-5" style="text-align: right">\n' +
            '           <span class="minus-text" onclick="minusProd('+data['item_id']+')" style="padding-left: 20px;font-size: 30px;margin-top: -15px">-</span>\n' +
            '           <input name="quantity[]" id="item_'+data['item_id']+'" placeholder="1" type="text" value="1">\n' +
            '           <span class="plus-text" onclick="addProd('+data['item_id']+')" style="padding-right: 10px;font-size: 30px;margin-top: -15px">+</span>\n' +
            '     </div></div>\n'+

            '        <input type="hidden" name="item_id[]" id="item_id" value="'+data['item_id']+'" />\n' +
            '        <input type="hidden" name="item_rate[]" value="15" />\n' +
            '     <div class="col-md-12 col-sm-12 col-xs-12"><label>'+data['details']+'</label></div>\n'

        return html;
    }

    // Set all ingredient list for the main menu on basis of a category like 'choose a cheese'
    set_ingredient = function set_ingredient(data, category_id, item_id, item_name, item_price,option_id,class_name){
        var html = '';
        $.each(data, function(i,datas){
            try{
                if(selected_ingredient[item_id]['ingredient_list_by_option'][option_id].includes(datas['name']+',')){
                    class_add = 'selected_ingredient';
                }
                else{
                    class_add = ''
                 }
            }catch (e) {
                class_add = ''
            }

            var price=' '
            if(datas['price']>0){
                price+= '+'+currency_symbol+''+datas['price']
            }
            if(ingredient_image_display=="display: none"){
                var img = ' <div class="shop-main-list" style=" border-radius: 15px">\n' +
                    '                        <div class="shop-product" style="border-radius: 15px 15px 0px 0px; color:  ">\n' +
                    '                            <div class="text-capitalize bold" style="background-color: #D2B48C ;border-radius: 17px 17px 17px 17px; height: 90px; max-width: 120px; display: flex; justify-content: center; align-items: center; color: white">'+datas['name']+'</br>'+price+'</div>\n' +
                    '                            <div class="cart-overlay-wrap'+class_add+'" style="background-color: #372727; opacity: .5; max-width: 120px" >\n' +
                    '                                <div class="cart-overlay" >\n' +
                    '                                    <i class="fa fa-check" style="font-size:48px;color: white; opacity: 1"></i>\n\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                    </div>'
            }else {
                if(datas['photo']){
                    var photo = 'ingredient/'+datas['photo']
                }
                else {
                    var photo = 'ingredient/not_available.png'
                }
                var img ='           <div class="shop-main-list" style=" border-radius: 15px">\n' +
                    '                        <div class="shop-product" style="border-radius: 15px 15px 0px 0px; margin-bottom: 0px; padding-bottom: 0px">\n' +
                    '                            <img src="'+project_url+'admin/images/'+photo+'" alt="" style="border-radius: 17px 17px 17px 17px; max-height: 70px; max-width: 70px">\n' +
                    '                            <div class="cart-overlay-wrap '+class_add+'" style="background-color: #372727; opacity: .5" >\n' +
                    '                                <div class="cart-overlay" >\n' +
                    '                                    <i class="fa fa-check" style="font-size:48px;color: white; opacity: 1"></i>\n\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                       <br><span  style="text-transform: capitalize; padding-top: 0px; margin-top: 0px">'+datas['name']+' '+price+'</span>\n' +
                    '                    </div>\n'
            }

            html+='    <div class="'+class_name+' col-md-3 col-sm-4 col-xs-6" style="text-align: center; margin-top: 10px; height: 120px; overflow: hidden; border-width: 1px;">\n' +
                '       <input type="hidden" class="ingredient_id"    value="'+datas['id']+'" />\n' +
                '       <input type="hidden" class="ingredient_rate"  value="'+datas['price']+'" />\n' +
                '       <input type="hidden" class="category_id"  value="'+category_id+'" />\n' +
                '       <input type="hidden" class="ingredient_name"  value="'+datas['name']+'" />\n'+
                '       <input type="hidden" class="item_id"  value="'+item_id+'" />\n' +
                '       <input type="hidden" class="item_name"  value="'+item_name+'" />\n' +
                '       <input type="hidden" class="item_price"  value="'+item_price+'" />\n' + img +
                '      </div>'

        });
        return html;
    }

    //set ingredient for side order


    // set the ingredient category header for the main menu
    load_item_option = function load_item_option(){
        $.ajax({
            url:project_url +"includes/controller/itemsController.php",
            dataType: "json",
            type: "post",
            async:false,
            data: {
                q: "menu_options_view",
                item_id:item_id,
                side_item: 1
            },
            success: function(data){

                //choosed_ingredient_number[item_id]={}
                //selected_ingredient[item_id]={}
                //choosed_ingredient_number[item_id][option_id] = 1;


                var html = '';
                html+=set_item_header(data['item'])
                is_combo = data['item']['is_combo']
                category_id = data['item']['category_id']
                choosed_ingredient_number[data['item']['item_id']]={}
                item_choice_limit[data['item']['item_id']]={}
                //selected_ingredient[this_item_id]={'name':this_item_name, 'price':this_item_price, 'base_price':this_item_price}
                $.each(data['option'], function(i,datas){
                    var hints=''
                    if(parseInt(datas['is_required'])==1 || parseInt(datas['minimum_choice'])>=1){
                        if(parseInt(datas['maximum_choice'])>0){
                            hints+='  (Required: Max-'+parseInt(datas['maximum_choice'])+')'
                        }else {
                            hints+='  (Required)'
                        }
                    }else {
                        if(parseInt(datas['maximum_choice'])>0){
                            hints+='  (Max-'+parseInt(datas['maximum_choice'])+')'
                        }else {
                            hints+='  (Max- Unlimited)'
                        }
                    }

                    choosed_ingredient_number[data['item']['item_id']][datas['option_id']]=0
                    item_choice_limit[data['item']['item_id']][datas['option_id']]={}
                    item_choice_limit[data['item']['item_id']][datas['option_id']]['maximum']=datas['maximum_choice'];
                    item_choice_limit[data['item']['item_id']][datas['option_id']]['minimum']=datas['minimum_choice'];
                    item_choice_limit[data['item']['item_id']][datas['option_id']]['required']=datas['is_required'];
                    item_choice_limit[data['item']['item_id']][datas['option_id']]['name']=datas['option_name'];

                    html3='<div class="col-md-12 col-sm-12 col-xs-12 option-div" name="option_div" style="background-color: rgba(244,242,237,1); padding-top: 25px; padding-bottom: 20px; margin-top: 5px; margin-bottom: 10px">\n' +
                        '       <!-- option id will come from DB  -->\n' +
                        '       <div class="col-md-12 col-sm-12 col-xs-12 left-padding-0 ">\n' +
                        '             <span style="font-size: 20px; text-transform: capitalize"><b>'+datas['option_name']+'</b> </span><span>'+hints+'</span>\n' +
                        '       </div>\n' +
                        '       <input type="hidden" name="options[]" value="'+datas['option_id']+'" />\n' +
                        '       <input type="hidden" name="options_name[]" value="'+datas['option_name']+'" />\n' +
                        '       <input type="hidden" name="option_selected[]" value="0" />\n' +
                        '       <input type="hidden" id="option_required_1" value="'+datas['is_required']+'" />\n' +
                        '       <input type="hidden" id="option_maximum_1" value="'+datas['maximum_choice']+'" />\n' +
                        '       <input type="hidden" id="option_minimum_1" value="'+datas['minimum_choice']+'" />\n' +
                        '       <input type="hidden" id="select_no_1" value="'+datas['option_id']+'" />'

                    html3 +=set_ingredient(datas['ingredient'][0],data['item']['category_id'],data['item']['item_id'],data['item']['name'],parseFloat(data['item']['price']),datas['option_id'],'options-ingredient')
                    html3 +='</div>\n' +
                        '     </div>'
                    html+=html3;
                });
                $('#option_body').html(html);

                var html_side=''

                //this portion will load the side items like brevarage
                $.each(data['side_item'], function (i,datas) {
                    html_side+='<div class="col-md-8 col-sm-8 col-xs-12" id="additional_items_'+i+'" style="background-color: white; padding-top: 25px; padding-bottom: 20px; margin-bottom: 15px">\n' +
                        '           <div  class="col-md-12 col-sm-12 col-xs-12"><h4 style="text-transform: uppercase;" id="item_name">'+i+'</h4></div>\n'+
                        '           <div class="col-md-12 col-sm-12 col-xs-12 option-div"  style="background-color: rgba(244,242,237,1); padding-top: 25px; padding-bottom: 20px; margin-top: 5px; margin-bottom: 10px">'

                    $.each(datas, function (j, items) {

                        var html='<div class="col-md-12 col-sm-12 col-xs-12 isotope-item breakfast">\n' +
                            '         <div class="menu-list" style="padding-left: 0px">\n' +
                            '             <h6 class="text-capitalize margin-bottom-0 -align-left" style="margin: 0px !important;">'+ items['name']+' <span>'+ currency_symbol+''+items['price']+'</span></h6>' +
                            '          </div>\n' +
                            '        </div>\n'


                        html_side+= '<div class="additional_items col-md-6 col-sm-6 col-xs-12 margin-bottom-10" style=" overflow: hidden; border-width: 1px; cursor: pointer">\n' +
                            '       <input type="hidden" class="item_id"    value="'+items['item_id']+'" />\n' +
                            '       <input type="hidden" class="item_rate"  value="'+items['price']+'" />\n' +
                            '       <input type="hidden" class="item_name"  value="'+items['name']+'" />\n' +
                            '       <input type="hidden" class="category_name"  value="'+i+'" />\n' + html+
                            '       </div>\n'

                        /* //item image as grid view
                        if(item_image_display=="display: none"){
                            var img = ' <div class="shop-main-list" style=" border-radius: 15px">\n' +
                                '                        <div class="shop-product" style="border-radius: 15px 15px 0px 0px; color:  ">\n' +
                                '                            <div class="text-capitalize bold" style="background-color: #D2B48C ;border-radius: 17px 17px 17px 17px; height: 90px; width: 110px; display: flex; justify-content: center; align-items: center; color: white">'+items['name']+' '+currency_symbol+' '+items['price']+'</div>\n' +
                                '                            <div class="cart-overlay-wrap" style="background-color: #372727; opacity: .5; width: 110px" >\n' +
                                '                                <div class="cart-overlay" >\n' +
                                '                                    <i class="fa fa-check" style="font-size:48px;color: white; opacity: 1"></i>\n\n' +
                                '                                </div>\n' +
                                '                            </div>\n' +
                                '                        </div>\n' +
                                '                    </div>'
                        }else {
                            var img ='           <div class="shop-main-list" style=" border-radius: 15px">\n' +
                                '                        <div class="shop-product" style="border-radius: 15px 15px 0px 0px; margin-bottom: 0px; padding-bottom: 0px">\n' +
                                '                            <img src="'+project_url+'admin/images/category/noFood.png" alt="" style="border-radius: 17px 17px 17px 17px; height: 90px; width: 110px">\n' +
                                '                            <div class="cart-overlay-wrap" style="background-color: #372727; opacity: .5" >\n' +
                                '                                <div class="cart-overlay" >\n' +
                                '                                    <i class="fa fa-check" style="font-size:48px;color: white; opacity: 1"></i>\n\n' +
                                '                                </div>\n' +
                                '                            </div>\n' +
                                '                        </div>\n' +
                                '                       <br><span  style="text-transform: capitalize; padding-top: 0px; margin-top: 0px">'+items['name']+' '+currency_symbol+' '+items['price']+'</span>\n' +
                                '                    </div>\n'
                        }


                        html_side+= '<div class="additional_items col-md-3 col-sm-4 col-xs-6" style="text-align: center; margin-top: 10px; height: 140px; overflow: hidden; border-width: 1px;">\n' +
                            '       <input type="hidden" class="item_id"    value="'+items['item_id']+'" />\n' +
                            '       <input type="hidden" class="item_rate"  value="'+items['price']+'" />\n' +
                            '       <input type="hidden" class="item_name"  value="'+items['name']+'" />\n' +
                            '       <input type="hidden" class="category_name"  value="'+i+'" />\n' + img+
                            '                </div>\n'

                        */
                    })
                    html_side+='</div></div>'
                })

                $('#item_body').html($('#item_body').html()+html_side)
            }
        });
    }
    load_item_option()

    // Ingredient add or remove

    //additional items like breverage add or remove
    $('.additional_items').on('click', function(){
        var this_item_id = $(this).children('input:eq(0)').val();
        quantity[this_item_id] = 1

        $.ajax({
            url:project_url +"includes/controller/itemsController.php",
            dataType: "json",
            type: "post",
            async:false,
            data: {
                q: "menu_options_view",
                item_id:this_item_id,
                side_item: 0
            },
            success: function(data){

                html = ''

                html22 = '<div class="row"><div  class="col-md-8 col-sm-6 col-xs-7"><h4 style="text-transform: uppercase;" id="item_name">'+data['item']['name']+' ('+currency_symbol+'<b>'+data['item']['price']+')</b></h4></div>' +
                '     <div class="price-textbox price-textbox-item col-md-2 col-sm-6 col-xs-5" style="text-align: right">\n' +
                '           <span class="minus-text" onclick="minusProd('+data['item']['item_id']+')" style="padding-left: 20px;font-size: 30px;margin-top: -5px">-</span>\n' +
                '           <input name="quantity[]" id="item_'+data['item']['item_id']+'" placeholder="1" type="text" value="1">\n' +
                '           <span class="plus-text" onclick="addProd('+data['item']['item_id']+')" style="padding-right: 10px;font-size: 30px;margin-top: -5px">+</span>\n' +
                '     </div></div>\n'

                $('#side_item_title').html(html22)

                if(!choosed_ingredient_number[this_item_id]){
                    choosed_ingredient_number[this_item_id]={}
                }

                item_choice_limit[this_item_id]={}

                $.each(data['option'], function(i,datas){
                    var hints=''
                    if(parseInt(datas['is_required'])==1 || parseInt(datas['minimum_choice'])>=1){
                        if(parseInt(datas['maximum_choice'])>0){
                            hints+='  (Required: Max-'+parseInt(datas['maximum_choice'])+')'
                        }else {
                            hints+='  (Required)'
                        }
                    }else {
                        if(parseInt(datas['maximum_choice'])>0){
                            hints+='  (Max-'+parseInt(datas['maximum_choice'])+')'
                        }else {
                            hints+='  (Max- Unlimited)'
                        }
                    }

                    if(!choosed_ingredient_number[this_item_id][datas['option_id']]){
                        choosed_ingredient_number[this_item_id][datas['option_id']]=0
                    }

                    item_choice_limit[this_item_id][datas['option_id']]={}
                    item_choice_limit[this_item_id][datas['option_id']]['maximum']=datas['maximum_choice'];
                    item_choice_limit[this_item_id][datas['option_id']]['minimum']=datas['minimum_choice'];
                    item_choice_limit[this_item_id][datas['option_id']]['required']=datas['is_required'];
                    item_choice_limit[this_item_id][datas['option_id']]['name']=datas['option_name'];


                    html3='<div class="col-md-12 col-sm-12 col-xs-12 option-div" name="option_div" style="background-color: rgba(244,242,237,1); padding-top: 25px; padding-bottom: 20px; margin-top: 5px; margin-bottom: 10px">\n' +
                        '       <!-- option id will come from DB  -->\n' +
                        '       <div class="col-md-12 col-sm-12 col-xs-12 left-padding-0 ">\n' +
                        '             <span style="font-size: 20px; text-transform: capitalize"><b>'+datas['option_name']+'</b> </span><span>'+hints+'</span>\n' +
                        '       </div>\n' +
                        '       <input type="hidden" name="options[]" value="'+datas['option_id']+'" />\n' +
                        '       <input type="hidden" name="options_name[]" value="'+datas['option_name']+'" />\n' +
                        '       <input type="hidden" name="option_selected[]" value="0" />\n' +
                        '       <input type="hidden" id="option_required_1" value="'+datas['is_required']+'" />\n' +
                        '       <input type="hidden" id="option_maximum_1" value="'+datas['maximum_choice']+'" />\n' +
                        '       <input type="hidden" id="option_minimum_1" value="'+datas['minimum_choice']+'" />\n' +
                        '       <input type="hidden" id="select_no_1" value="'+datas['option_id']+'" />'
                    html3 +=set_ingredient(datas['ingredient'][0],data['item']['category_id'],data['item']['item_id'],data['item']['name'],parseFloat(data['item']['price']),datas['option_id'],'side_option_ingredient')

                    html3 +='</div>\n' +
                        '     </div>'
                    html+=html3;
                });
                html+='<input type="hidden" id="side_item_name_hidden" value="'+data['item']['name']+'">' +
                    '<input type="hidden" id="side_item_id_hidden" value="'+data['item']['item_id']+'">' +
                    '<input type="hidden" id="side_item_price_hidden" value="'+data['item']['price']+'">'

                $('#side_selection_body').html(html);
                $('#side_selection').modal()
            }
        })

        $('.side_option_ingredient').on('click', function(){

            //alert('okksdfj')

            //console.log(item_choice_limit)
           // console.log(choosed_ingredient_number)

            if($(this).find('.cart-overlay-wrap').hasClass('selected_ingredient')){
                $(this).find('.cart-overlay-wrap').removeClass('selected_ingredient')
            }
            else{
                $(this).find('.cart-overlay-wrap').addClass('selected_ingredient')
            }
            var option_id = $(this).siblings('input:eq(0)').val();
            var option_name = $(this).siblings('input:eq(1)').val()
            var option_selected = $(this).siblings('input:eq(2)').val()
            var ing_id   = $(this).find('input:eq(0)').val();
            var ing_rate   = $(this).find('input:eq(1)').val();
            var cat_id   = $(this).find('input:eq(2)').val();
            var ing_name   = $(this).find('input:eq(3)').val();
            var this_item_id   = $(this).find('input:eq(4)').val();
            var this_item_name = $(this).find('input:eq(5)').val();
            var this_item_price = $(this).find('input:eq(6)').val();


            add_to_meal_return = add_to_meal(option_id,option_name,ing_id,ing_rate,ing_name,cat_id, this_item_id, this_item_name,this_item_price)
            if(add_to_meal_return==0){
                $(this).find('.cart-overlay-wrap').removeClass('selected_ingredient')
            }
            else if(add_to_meal_return==2){

                //write to remove class
                $(this).parent().find('.side_option_ingredient').each(function(){
                    if($(this).find('.cart-overlay-wrap').hasClass('selected_ingredient')){
                        $(this).find('.cart-overlay-wrap').removeClass('selected_ingredient')
                    }
                })

                $(this).find('.cart-overlay-wrap').addClass('selected_ingredient')
            }
        })
    })

    $('.options-ingredient').on('click', function(){

        if($(this).find('.cart-overlay-wrap').hasClass('selected_ingredient')){
            $(this).find('.cart-overlay-wrap').removeClass('selected_ingredient')
        }
        else{
            $(this).find('.cart-overlay-wrap').addClass('selected_ingredient')
        }
        var option_id = $(this).siblings('input:eq(0)').val();
        var option_name = $(this).siblings('input:eq(1)').val()
        var option_selected = $(this).siblings('input:eq(2)').val()
        var ing_id   = $(this).find('input:eq(0)').val();
        var ing_rate   = $(this).find('input:eq(1)').val();
        var cat_id   = $(this).find('input:eq(2)').val();
        var ing_name   = $(this).find('input:eq(3)').val();
        var this_item_id   = $(this).find('input:eq(4)').val();
        var this_item_name = $(this).find('input:eq(5)').val();
        var this_item_price = $(this).find('input:eq(6)').val();
        add_to_meal_return = add_to_meal(option_id,option_name,ing_id,ing_rate,ing_name,cat_id, this_item_id, this_item_name,this_item_price)
        //alert(add_to_meal_return)
        if(add_to_meal_return==0){
            $(this).find('.cart-overlay-wrap').removeClass('selected_ingredient')
        }
        else if(add_to_meal_return==2){

            //write to remove class
            $(this).parent().find('.options-ingredient').each(function(){
                if($(this).find('.cart-overlay-wrap').hasClass('selected_ingredient')){
                    $(this).find('.cart-overlay-wrap').removeClass('selected_ingredient')
                }
            })

            $(this).find('.cart-overlay-wrap').addClass('selected_ingredient')
        }
    })

    /*
     html+='<input type="hidden" id="side_item_name_hidden" value="'+data['item']['name']+'">' +
                    '<input type="hidden" id="side_item_id_hidden" value="'+data['item']['item_id']+'">' +
                    '<input type="hidden" id="side_item_price_hidden" value="'+data['item']['price']+'">' +
                    '<input type="hidden" id="side_item__hidden" value="">'
     */

    function confirm(){
        if(!selected_ingredient[$('#side_item_id_hidden').val()]){
            quantity[$('#side_item_id_hidden').val()] = parseInt($('#'+'item_'+$('#side_item_id_hidden').val()).val());
            selected_ingredient[$('#side_item_id_hidden').val()]={'name':$('#side_item_name_hidden').val(), 'price':$('#side_item_price_hidden').val(), 'base_price':$('#side_item_price_hidden').val()}
        }
        $('#side_selection').modal('hide');
        chose_summary()
    }

    addToCart = function addToCart(){
        $.each(choosed_ingredient_number, function (i, choosed_ingredient_number_single_item) {
            $.each(choosed_ingredient_number_single_item, function (j, ingredient) {
                if (parseInt(item_choice_limit[i][j]['minimum']) > parseInt(ingredient) || parseInt(item_choice_limit[i][j]['required']) > parseInt(ingredient)) {
                    success_or_error_msg('#select_ingredinet', 'danger', "Please Select " + item_choice_limit[i][j]['name'] + " for <b>"+selected_ingredient[i]['name'] +" </b>", "#side_order");
                    select_ingredinet_check = 0;
                    return false;
                }
            });
        });

        sideInCart = 0;
        $.ajax({
            url:project_url +"includes/controller/ecommerceController.php",
            dataType: "json",
            type: "post",
            async:false,
            data: {
                q: "checkSideBreverage"
            },
            success: function(data){
                sideInCart = data;
            }
        })



        if(Object.keys(choosed_ingredient_number).length<=1 && is_combo==0 && category_id != 6 && category_id != 45 && sideInCart==0){
            $('#additional_items_beverages	').focus();
            success_or_error_msg('#select_side','warning',"You did not select any BEVERAGE ","#side_order");
            cart_side_check=1
        }
        else if(select_ingredinet_check!=0){
            $.ajax({
                url: project_url +"includes/controller/ecommerceController.php",
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "addToCart",
                    item_image: 'noFood.png',
                    quantity : quantity,
                    ingredient: selected_ingredient,
                    special_instruction: $('#special_instruction').val()
                },
                success: function(data) {
                    $('#cart_confirmation').modal()
                }
            });

        }
        showCart()
    }

    //reset the page after added to cart
    $('#cart_confirmation').on('hidden.bs.modal', function () {
        //if you want to clear choose option please unblock flowing line

       // window.location.reload(true);
    })



</script>






