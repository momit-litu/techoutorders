<div>
    <button onclick="importOrder()">click me to add order</button>
    <button onclick="customerAdd()">click me to add customer</button>

</div>

<script>

    //-------------------------------------------------------------------------

    ingredientadd= function ingredientadd(){
        //alert('ok')
        $.ajax({
            url: project_url +"includes/controller/ecommerceController.php",
            dataType: "json",
            type: "post",
            async: false,
            data: {
                q: "db_update_all_items",
            },
            success: function (data) {
                alert(data)

            }
        })
    }

    customerAdd= function customerAdd(){
        //alert('ok')
        $.ajax({
            url: project_url +"includes/controller/ecommerceController.php",
            dataType: "json",
            type: "post",
            async: false,
            data: {
                q: "import_customer",
            },
            success: function (data) {
                alert(data)

            }
        })
    }

    importOrder= function importOrder(){
        //alert('ok')
        $.ajax({
            url: project_url +"includes/controller/ecommerceController.php",
            dataType: "json",
            type: "post",
            async: false,
            data: {
                q: "import_order",
            },
            success: function (data) {

                $.each(data,function (key, value) {
                    console.log(value['total'])
                    console.log(value['tax'])
                    console.log(value['orderinfo'])
                })

            }
        })
    }

    /*
    aa ='107[+]1[+]egg and cheese breakfast burrito|||3.25[+][+]\n' +
        'choose a cheese: (cheddar cheese);
          add: (onions, black beans,);
          flavored tortillas: (regular flour);|||0[+]|||[+]|||[+]|||[+]\n' +
        'Could i please have only one egg and also add potato.  Thank you!{++}\n' +
        '107[+]3[+]egg and cheese breakfast burrito|||3.25[+][+]\n' +
        'choose a cheese: (cheddar cheese);
          flavored tortillas: (regular flour);|||0[+]|||[+]|||[+]|||[+]\n' +
        'Pico de gallo on the side please.{++}\n' +
        '114[+]1[+]super breakfast burrito|||6.85[+][+]\n' +
        'choose a cheese: (cheddar cheese);
         flavored tortillas: (whole wheat);
         options: (home fries);  extra: (sour cream,);|||1.3[+]|||[+]|||[+]|||[+]\n' +
        'could we please have the potato in the burrito and sour cream on the side!  thank you!{++}'

    rules[1]= 'item start with "number"[+]"number"[+]'
    rules[2]='item end with {++}'*
    rules[3]='option start with "number"[+][+]'
    rules[4]='options separated by ";"'
    rules[5]='ingredients are in "()" and separated by ","'
    rules[6]='special instruction are in "[+]|||[+]|||[+]|||[+]" and {++}'
    rules[7]='item price is in "|||" and "[+][+]" after item name'
    rules[8]='ingredients price is in "|||" and "[+]|||" after ingredient list'

    totalPrice ='all item price + ingredient price + tax -discount'
    st = '137[+]1[+]burrito|||0.00[+][+]' +
        'choose a size: (12" tortilla);  ' +
        'choose a tortilla: (regular tortilla);  ' +
        'choose a protein: (grilled chicken);  ' +
        'beans: (black beans,);  ' +
        'choose a rice style: (mexican rice);  ' +
        'options: (lettuce, sour cream, monterey jack, cheddar cheese, fresh tomatoes,);' +
        '|||8[+]|||[+]|||[+]|||[+]{++}' +


        '188[+]1[+]chips and queso dip|||3.00[+][+]|||0[+]|||[+]|||[+]|||[+]{++}' +

        '138[+]1[+]burrito with the works|||0.00[+][+]' +
        'choose a size: (14" tortilla);  ' +
        'choose a tortilla: (regular tortilla);  ' +
        'choose a protein: (grilled steak);  beans: (black beans,);  ' +
        'choose a rice style: (mexican rice);  ' +
        'would you like toppings?: (cilantro, monterey jack,);  ' +
        'extras: (extra nacho cheese sauce, fresh avocado,);' +
        '|||17.1[+]|||[+]|||[+]|||[+]{++}' +
        '188[+]1[+]chips and queso dip|||3.00[+][+]|||0[+]|||[+]|||[+]|||[+]{++}' +
        '137[+]1[+]burrito|||0.00[+][+]' +
        'choose a size: (12" tortilla);  ' +
        'choose a tortilla: (regular tortilla);  ' +
        'choose a protein: (grilled chicken);  ' +
        'beans: (no beans,);  choose a rice style: (mexican rice);  ' +
        'options: (salsa verde, lettuce, sour cream, monterey jack, cheddar cheese,);' +
        '|||8[+]|||[+]|||[+]|||[+]{++}137[+]1[+]burrito|||0.00[+][+]' +
        'choose a size: (little brother);  ' +
        'choose a tortilla: (regular tortilla);  ' +
        'choose a protein: (shredded chicken);  ' +
        'beans: (black beans,);  ' +
        'choose a rice style: (white rice);  ' +
        'options: (salsa verde, lettuce, sour cream, cheddar cheese, onions, green peppers, fresh tomatoes, fresh jalopenos,);' +
        '|||6[+]|||[+]|||[+]|||[+]{++}' +
        '137[+]1[+]burrito|||0.00[+][+]' +
        'choose a size: (little brother);  ' +
        'choose a tortilla: (regular tortilla);  ' +
        'choose a protein: (grilled chicken);  ' +
        'beans: (refried beans,);  choose a rice style: (brown rice);  ' +
        'options: (hot sauce, lettuce, sour cream, monterey jack, fresh jalopenos,);  ' +
        'extras: (mushrooms,);|||7[+]|||[+]|||[+]|||[+]{++}' +
        '168[+]1[+]chips|||2.25[+][+]' +
        'would you like to add a dip?: (queso dip);' +
        '|||1.5[+]|||[+]|||[+]|||[+]{++}' +
        '137[+]1[+]burrito|||0.00[+][+]' +
        'choose a size: (12" tortilla);  ' +
        'choose a tortilla: (regular tortilla);  ' +
        'choose a protein: (carnitas);  beans: (black beans,);  ' +
        'choose a rice style: (white rice); ' +
        ' options: (lettuce, onions, green peppers, fresh tomatoes,);  ' +
        'extras: (guacamole,);|||10.4[+]|||[+]|||[+]|||[+]{++}'
*/
    stringSplit = function () {
        var string = '137[+]1[+]burrito|||0.00[+][+]choose a size: (12" tortilla);  choose a tortilla: (regular tortilla);  choose a protein: (grilled chicken);  beans: (black beans,);  choose a rice style: (mexican rice);  options: (lettuce, sour cream, monterey jack, cheddar cheese, fresh tomatoes,);|||8[+]|||[+]|||[+]|||[+]{++}188[+]1[+]chips and queso dip|||3.00[+][+]|||0[+]|||[+]|||[+]|||[+]{++}138[+]1[+]burrito with the works|||0.00[+][+]choose a size: (14" tortilla);  choose a tortilla: (regular tortilla);  choose a protein: (grilled steak);  beans: (black beans,);  choose a rice style: (mexican rice);  would you like toppings?: (cilantro, monterey jack,);  extras: (extra nacho cheese sauce, fresh avocado,);|||17.1[+]|||[+]|||[+]|||[+]{++}188[+]1[+]chips and queso dip|||3.00[+][+]|||0[+]|||[+]|||[+]|||[+]{++}137[+]1[+]burrito|||0.00[+][+]choose a size: (12" tortilla);  choose a tortilla: (regular tortilla);  choose a protein: (grilled chicken);  beans: (no beans,);  choose a rice style: (mexican rice);  options: (salsa verde, lettuce, sour cream, monterey jack, cheddar cheese,);|||8[+]|||[+]|||[+]|||[+]{++}137[+]1[+]burrito|||0.00[+][+]choose a size: (little brother);  choose a tortilla: (regular tortilla);  choose a protein: (shredded chicken);  beans: (black beans,);  choose a rice style: (white rice);  options: (salsa verde, lettuce, sour cream, cheddar cheese, onions, green peppers, fresh tomatoes, fresh jalopenos,);|||6[+]|||[+]|||[+]|||[+]{++}137[+]1[+]burrito|||0.00[+][+]choose a size: (little brother);  choose a tortilla: (regular tortilla);  choose a protein: (grilled chicken);  beans: (refried beans,);  choose a rice style: (brown rice);  options: (hot sauce, lettuce, sour cream, monterey jack, fresh jalopenos,);  extras: (mushrooms,);|||7[+]|||[+]|||[+]|||[+]{++}168[+]1[+]chips|||2.25[+][+]would you like to add a dip?: (queso dip);|||1.5[+]|||[+]|||[+]|||[+]{++}137[+]1[+]burrito|||0.00[+][+]choose a size: (12" tortilla);  choose a tortilla: (regular tortilla);  choose a protein: (carnitas);  beans: (black beans,);  choose a rice style: (white rice);  options: (lettuce, onions, green peppers, fresh tomatoes,);  extras: (guacamole,);|||10.4[+]|||[+]|||[+]|||[+]{++}'
    }

</script>
