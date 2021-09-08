<?php
session_start();
date_default_timezone_set("America/New_York");
include('../dbConnect.php');
include("../dbClass.php");

$dbClass = new dbClass;

include("orderConfirm.php");

$orderConfirm = new orderConfirm($dbClass);
//$loggedUser = $dbClass->getUserId();

extract($_REQUEST);

switch ($q){
    case "checkSideBreverage":
        if(isset($_SESSION['cart'])){
            $cart = $_SESSION['cart'];
            foreach ($cart as $key=>$value){
                if($value['category_id']==6 || $value['category_id']==45 || $value['is_combo']==1){
                    echo 1; die;
                }
            }
        }
        echo 0;
        break;

    case "addToCart":
        if(!isset($_SESSION['cart']) || empty($_SESSION['cart']))$cart = array();
        else $cart = $_SESSION['cart'];
        if(isset($ingredient)){
            foreach ($ingredient as $id=>$single_ingredient){
                $category_id =$dbClass->getSingleRow("SELECT category_id from items WHERE item_id=$id");
                $tem_cart[$id.'_'.$single_ingredient['price']]['item_name']=$single_ingredient['name'];
                $tem_cart[$id.'_'.$single_ingredient['price']]['cart_key']=$id.'_'.$single_ingredient['price'];
                $tem_cart[$id.'_'.$single_ingredient['price']]['discounted_rate']=$single_ingredient['price'];
                $tem_cart[$id.'_'.$single_ingredient['price']]['item_id']=$id;
                $tem_cart[$id.'_'.$single_ingredient['price']]['category_id']=$category_id['category_id'];
                $tem_cart[$id.'_'.$single_ingredient['price']]['quantity']=$quantity[$id];
                $tem_cart[$id.'_'.$single_ingredient['price']]['item_image']='';
                $tem_cart[$id.'_'.$single_ingredient['price']]['ingredient'] = $single_ingredient;
                $tem_cart[$id.'_'.$single_ingredient['price']]['is_combo'] = $is_combo;
                $tem_cart[$id.'_'.$single_ingredient['price']]['special_instruction'] = $special_instruction;

                $tem_cart[$id.'_'.$single_ingredient['price']]['item_total']=$single_ingredient['price']*$quantity[$id];
                foreach ($tem_cart as $item){
                    if (array_key_exists($item['cart_key'],$cart)){
                        $cart_key = $item['cart_key'];

                        $updatable_item = $cart[$cart_key];

                        if($quantity==0){
                            unset($cart[$item[$cart_key]]);
                        }
                        else{
                            $discounted_rate                      = $updatable_item['discounted_rate'];
                            $total_quantity                       = ($item['quantity']+$updatable_item['quantity']);
                            $cart[$cart_key]['quantity']          = $total_quantity;
                            $total_amount                         = $total_quantity*$updatable_item['discounted_rate'];
                            $cart[$cart_key]['item_total']        = $total_amount;
                            $_SESSION['cart']                     = $cart;
                        }
                    }
                    else{
                        $cart[$item['cart_key']]=$item;
                        $_SESSION['cart'] = $cart;
                    }
                }
                $tem_cart=[];
            }
        }
        echo 1;
        break;


    case "viewCartSummery":
        //echo 1; die;
        if(!isset($_SESSION['cart']) || empty($_SESSION['cart']))$cart = array();
        else 													 $cart = $_SESSION['cart'];
        $data['records'] = $cart;
        echo json_encode($data);
        break;

    case "viewPriceSummery":
        $tax = $dbClass->getSingleRow("Select tax_type, tax_amount, tax_enable from general_settings where id=1");
        if(!isset($_SESSION['cart']) || empty($_SESSION['cart']))$cart = array();
        else 													 $cart = $_SESSION['cart'];
        $total_price=0;
        $data = [];
        foreach ($cart as $items){
            $total_price+=($items['discounted_rate']* $items['quantity']);
        }
        $data['total_price']= $total_price;

        if(isset($_SESSION['total_discounted_amount'])){

            if(isset($_SESSION['min_order_amount']) && $total_price>=$_SESSION['min_order_amount']){
                $data['discount'] = $_SESSION['total_discounted_amount'];
                $data['discounted_price'] = $total_price-$data['discount'];

            }
            else{
                $data['discounted_price']=$total_price;
                $data['discount'] = 0;
            }
        }
        else{
            $data['discounted_price']=$total_price;
            $data['discount'] = 0;
        }
        //echo $data['discount']; die;

        if ($tax['tax_enable']==1){
            if($tax['tax_type']==1){
                $data['tax_amount']= $tax['tax_amount'];
            }
            else{
                $data['tax_amount']= $data['discounted_price']*$tax['tax_amount']/100;
            }
            $data['discounted_price']=$data['discounted_price']+$data['tax_amount'];
        }
        else{
            $data['tax_amount']= 0;
        }
        echo  json_encode($data);
        break;

    case "removeFromCart":
        if(!isset($_SESSION['cart']) || empty($_SESSION['cart']))$cart = array();
        else 													 $cart = $_SESSION['cart'];


        if (array_key_exists($cart_key,$cart)){
            unset($cart[$cart_key]);
        }
        $_SESSION['cart']= $cart;
        $data['records'] = $cart;
        echo json_encode($data);
        break;


    case "update_cart":
        if(!isset($_SESSION['cart']) || empty($_SESSION['cart']))$cart = array();
        else{
            $cart = $_SESSION['cart'];

            foreach($cart_key as $key=>$cart_key){
                //echo $cart_key;die;

                if (array_key_exists($cart_key,$cart)){
                    $updatable_item = $cart[$cart_key];

                    if($quantity==0){
                        unset($cart[$cart_key]);
                    }
                    else{
                        //echo $updatable_item['discounted_rate'];die;
                        $discounted_rate = $updatable_item['discounted_rate'];
                        $cart[$cart_key]['quantity']  = $quantity[$key];
                        $toal_amount = $quantity[$key]*$updatable_item['discounted_rate'];
                        $cart[$cart_key]['item_total'] = $toal_amount;

                        $_SESSION['cart'] = $cart;

                    }
                }
            }
            echo json_encode($_SESSION['cart']);die;
            echo 1;
        }
        break;


    case "apply_cupon":
        //var_dump($_SESSION);die;
        if(!isset($_SESSION['cart']) || empty($_SESSION['cart']))$cart = array();
        else{
            $cart = $_SESSION['cart'];
            // get the total cart amount
            $total_cart_amount = 0;
            foreach($cart as $key=>$item){
                $total_cart_amount += $item['item_total'];
            }
            $cupon_amount = 0;
            $date = date("Y-m-d");
            //echo $date; die;
            if(isset($_SESSION['customer_id']) && $_SESSION['customer_id'] != ""){
                //echo("select c_type,amount from cupons where status=1 and ((cupon_no='$cupon_code' and customer_id = ".$_SESSION['customer_id'].") or cupon_no='$cupon_code' and customer_id is null) and (DATE_FORMAT(start_date, '%Y-%m-%d') <= '$date' AND DATE_FORMAT(end_date, '%Y-%m-%d') >= '$date')");die;

                $cupon_info = $dbClass->getSingleRow("select c_type,amount,min_order_amount from cupons where status=1 and ((cupon_no='$cupon_code' and customer_id = ".$_SESSION['customer_id'].") or cupon_no='$cupon_code' and customer_id is null) and (DATE_FORMAT(start_date, '%Y-%m-%d') <= '$date' AND DATE_FORMAT(end_date, '%Y-%m-%d') >= '$date')");
            }
            else{
                // echo ("select c_type,amount from cupons where status=1 and cupon_no='$cupon_code' and customer_id is null and(DATE_FORMAT(start_date, '%Y-%m-%d') <= '$date' AND DATE_FORMAT(end_date, '%Y-%m-%d') >= '$date')"); die;
                $cupon_info = $dbClass->getSingleRow("select c_type,amount,min_order_amount from cupons where status=1 and cupon_no='$cupon_code' and customer_id is null and(DATE_FORMAT(start_date, '%Y-%m-%d') <= '$date' AND DATE_FORMAT(end_date, '%Y-%m-%d') >= '$date')");
            }
            //echo($cupon_info);die;
            if($cupon_info){
                //echo 1;die;
                if($cupon_info['c_type']==1 && $cupon_info['min_order_amount']<=$total_cart_amount) {// flat amount
                    $cupon_amount = $cupon_info['amount'];
                    $min_order_amount = $cupon_info['min_order_amount'];
                }
                else if($cupon_info['c_type']==2 && $cupon_info['min_order_amount']<=$total_cart_amount){ // % amount
                    $cupon_percent = $cupon_info['amount'];
                    $cupon_amount = ($total_cart_amount*$cupon_percent)/100;
                    $min_order_amount = $cupon_info['min_order_amount'];
                }
                else{
                    $cupon_amount = 0;
                    $min_order_amount = $cupon_info['min_order_amount'];
                }
                //echo $total_cart_amount; die;
                $_SESSION['total_discounted_amount'] = $cupon_amount;
                $_SESSION['cupon_code'] = $cupon_code;
                $_SESSION['min_order_amount'] = $min_order_amount;
                //$data['total_discounted_amount'] = $cupon_amount;
                //echo json_encode($data);
                echo 1;
            }
            else{
                unset($_SESSION['total_discounted_amount']);
                unset($_SESSION['cupon_code']);
                echo 2; // invalid cupon
            }
        }

        break;

    case "unpaid_order":
        $orderInfo = $dbClass->getSingleRow("SELECT payment_status,payment_method,order_id FROM order_master WHERE invoice_no='".$_SESSION['Unpaid_invoice_no']."'");

        //$orderInfo = $dbClass->getSingleRow("select payment_status, , order_id form order_master where invoice_no = '$order_id'");
        if(($orderInfo['payment_method']!=1 || $orderInfo['payment_method']!=2 ) && $orderInfo['payment_status']==2){
            $cart = array();

            //unblock flowing line after check
            $_SESSION['cart'] = $cart;
            unset($_SESSION['total_discounted_amount']);
            unset($_SESSION['cupon_code']);
            unset($_SESSION['min_order_amount']);
            unset($_SESSION['Unpaid_invoice_no']);
            echo 1; die();
        }
        else if(($orderInfo['payment_method']!=1 || $orderInfo['payment_method']!=2 ) && $orderInfo['payment_status']==1){
            $condition_array = array(
                'order_id'=>$orderInfo['order_id']
            );
            try {
                $dbClass->delete('order_details',$condition_array);
                $dbClass->delete('order_master',$condition_array);
                unset($_SESSION['Unpaid_invoice_no']);
                unset($_SESSION['Last_invoice_no']);


                echo 3; die();
            }catch (Exception $e){
                return $e;
            }
        }
        echo 2; die();
    break;


    case "checkout":
		//echo "BB102006220";die;
        //var_dump($_REQUEST);die;

		/*$data_return = array(
			'message' => '222'
		);
		echo json_encode($data_return); die;*/

        if(!isset($_SESSION['cart']) || empty($_SESSION['cart']) || (empty($_SESSION['customer_id']) && empty($_SESSION['group_master'])) || ($_SESSION['customer_id']=="" && empty($_SESSION['group_master'] ))){echo "01"; die;}
        else 	$cart = $_SESSION['cart'];

        //------------ generate invoice no  -------------------
        $c_y_m = date('my');
        $last_invoice_no = $dbClass->getSingleRow("SELECT max(RIGHT(invoice_no,5)) as invoice_no FROM order_master");
        $inv_no = ($last_invoice_no == null)?'00001':$last_invoice_no['invoice_no']+1;
        $str_length = 5;
        $str = substr("00000{$inv_no}", -$str_length);
        $invoice_no = "BB$c_y_m$str";
        //-----------------------------------------------------
		// need to work on order_form
        $order_from=0;
        if(isset($_SESSION['group_master'])){
            $price = 0;
            foreach($cart as $key=>$item){
                $price+=(float)$item['discounted_rate']*(int)$item['quantity'];
            }
            //echo json_encode($cart); die;
            $columns_value = array(
                'customer_id'=>0,
                'delivery_date'=>$_SESSION['delivery_date'],
                'delivery_type'=>1,
                'remarks'=>'',
                'order_status'=>0,
                'invoice_no'=>$invoice_no,
                'payment_method' =>0,
                'payment_status' =>1,
                'total_order_amt'=>$price,
                'tax_amount'=>0,
                'tips'=>0,
                'total_paid_amount'=>0,
                'group_order_details_id'=>$_SESSION['group_order_details_id'],
                'loyalty_point'=>0,
                //'order_from'=>$order_from
            );
        }
        else{
            $secial_notes	 = htmlspecialchars($secial_notes,ENT_QUOTES);
            $columns_value = array(
                'customer_id'=>$_SESSION['customer_id'],
                'delivery_date'=>$pickup_date_time,
                'order_date'=>$order_date_time,
                'delivery_type'=>1,
                'remarks'=>$secial_notes,
                'order_status'=>1,
                'invoice_no'=>$invoice_no,
                'payment_method' =>$payment_method,
                'total_order_amt'=>$total_order_amt,
                'tax_amount'=>$tax_amount,
                'total_paid_amount'=>$total_paid_amount,
                'tips'=>(float)(!$tips)?0:$tips,
                'payment_status' =>1,
                'loyalty_point'=>$loyalty_point,
                'loyalty_paid'=>$loyalty_deduct,
                'group_order_details_id'=>0,
                'ASAP'=>isset($asap) ? 1 : 0,

            );
        }
		//$dbClass->print_arrays($columns_value);die;
        if(isset($_SESSION['cupon_code']) || !empty($_SESSION['cupon_code'])){
            $columns_value['cupon_id'] 			= $_SESSION['cupon_code'];
            $columns_value['discount_amount']	= $_SESSION['total_discounted_amount'];
        }

        $paid = 0; // not paid

        if(!isset($_SESSION['group_master']) ) {
            if ($total_paid_amount) {
                $columns_value['payment_status'] = 1;
                if( $payment_method==2){
                    $columns_value['payment_status'] = 2;
                }
                $columns_value['payment_reference_no'] = 1;

            } else {
                $columns_value['payment_status'] = 1;
            }
        }
        $return_master = $dbClass->insert("order_master", $columns_value);

		/*
				try {
		$dbClass->getDbConn->beginTransaction();
		} catch(PDOException $e) {
			$dbClass->getDbConn->rollback();
			echo 0;//"Insert:Error: " . $e->getMessage();
		}

		*/
		
		if(is_numeric($return_master) && $return_master>0){
            foreach($cart as $key=>$item){
                if(isset($item['ingredient']['id_list'])){
                    $ing_list= $item['ingredient']['id_list'];
                    $ingredient_name= $item['ingredient']['ingredient_name'];
                }
                else{
                    $ing_list= '';
                    $ingredient_name='';
                }

                if(isset($item['special_instruction'])){
                    $special_instruction=$item['special_instruction'];
                }else{
                    $special_instruction='';
                }

                $cart_key_arr = explode('_',$key);
                $item_size_rate_id = $cart_key_arr[1];
                $columns_value = array(
                    'order_id'=>$return_master,
                    'item_id'=>$item['item_id'],
                    'quantity'=>$item['quantity'],
                    'ingredient_list'=>$ing_list,
                    'ingredient_name'=>$ingredient_name,
                    'item_rate_id'=>0,
                    'special_instruction'=>$item['special_instruction'],
                    'item_rate'=>$item['discounted_rate']
                );
                $return_details = $dbClass->insert("order_details", $columns_value);
            }

            if(is_numeric($return_details) && $return_details>0 ){
                if(!isset($_SESSION['group_master']) && ( $payment_method==1 || $payment_method==2)){
                    $customer_loyalty_point=  $dbClass->getSingleRow("SELECT loyalty_points from customer_infos where customer_id=".$_SESSION['customer_id']);
                    if($payment_method==2){						
                        $new_loyalty_point = $customer_loyalty_point['loyalty_points']-intval($loyalty_deduct);
						$new_loyalty_point = ($new_loyalty_point<=0)?0:$new_loyalty_point;
                        $value_arC= array(
                            'loyalty_points'=>$new_loyalty_point
                        );
                        $condition_arC= array(
                            'customer_id'=>$_SESSION['customer_id']
                        );
                        $customer_loyalty_update=  $dbClass->update("customer_infos",$value_arC,$condition_arC);

                    }
                    $orderConfirm->afterPayment($invoice_no);
                    $cart = array();
                    $_SESSION['latest_order_id'] = $return_master;

                    //unblock flowing line after check
                    $_SESSION['cart'] 	 = $cart;
                    $_SESSION['payment'] = $paid;

                    unset($_SESSION['total_discounted_amount']);
                    unset($_SESSION['cupon_code']);
                    unset($_SESSION['min_order_amount']);
                    $_SESSION['Last_invoice_no']=$invoice_no;
                    $orderConfirm->orderConfirmationEmail($invoice_no);
                    echo $invoice_no;

                }
                else if(isset($_SESSION['group_master'])){
                    $value_ar=array(
                        'order_master_id'=>$return_master,
                        'status'=>1
                    );
                    $condition_ar = array(
                        'id'=>$_SESSION['group_order_details_id']
                    );
                    $dbClass->update("group_order_details",$value_ar,$condition_ar);

                    $t_sql = "SELECT go.customer_id from  
                            group_order_details god
                            LEFT JOIN group_order go ON go.order_id = god.group_order_id
                            WHERE god.id = ".$_SESSION['group_order_details_id'];

                    $customer_id_group = $dbClass->getSingleRow($t_sql);
                    $c_sql ="SELECT full_name FROM customer_infos WHERE customer_id = ".$customer_id_group['customer_id'];
                    $group_customer_name = $dbClass->getSingleRow($c_sql);
                    $details = "".ucfirst($group_customer_name['full_name'])." selected the items for Group Order";
                    $notified_to = $customer_id_group['customer_id'];
                    $notification_user_type = 0;

                    $orderConfirm->orderNotification($return_master, $details, $notification_user_type, 0, $notified_to);
                    //**************************Session Clear********************//
                    $cart = array();
                    $_SESSION['cart'] = $cart;

                    $group_master =1;
                    unset($_SESSION['group_master']);
                    unset($_SESSION['delivery_date']);
                    unset($_SESSION['group_order_details_id']);
                    if(isset($_SESSION['groupOrderId'])){
                        $data_return = array(
                            'message' => '222'
                        );
                        echo json_encode($data_return); die;
                    }
                    else{
                        $data_return = array(
                            'message' => '111'
                        );
                        echo json_encode($data_return); die;
                    }
                }
                else{
                    $cart = array();

                    $_SESSION['latest_order_id'] = $return_master;

                    //unblock flowing line after check
                    $_SESSION['cart'] = $cart;
                    $_SESSION['payment'] 		 = $paid;

                    unset($_SESSION['total_discounted_amount']);
                    unset($_SESSION['cupon_code']);
                    unset($_SESSION['min_order_amount']);
                    $_SESSION['Last_invoice_no']=$invoice_no;
                    echo $invoice_no;
                }
            }
        }
        else echo "0";
        break;

    case "make_payment":

        if($payment_method==2){
            $sql = 'SELECT redeem_value from general_settings WHERE id = 1';
            $loyalty_value =$dbClass->getSingleRow($sql);
            $sql = 'SELECT loyalty_points from customer_infos WHERE customer_id ='.$_SESSION['customer_id'];
            $customer_loyalty =$dbClass->getSingleRow($sql);
            $new_loyalty =intval($customer_loyalty['loyalty_points']) -floor($paid_amount*$loyalty_value['redeem_value']);
            //$sql = 'UPDATE customer_infos SET loyalty_points = $new_loyalty WHERE customer_id ='.$_SESSION['customer_id'] ;
            $condition_array = array(
                'customer_id'=>$_SESSION['customer_id']
            );
            $columns_value = array(
                'loyalty_points'=>$new_loyalty
            );
            $return = $dbClass->update("customer_infos", $columns_value, $condition_array);




            $condition_array = array(
                'invoice_no'=>$order_no
            );
            $columns_value = array(
                'payment_status'=>2,
                'payment_method'=>$payment_method,
                'loyalty_paid' =>floor($paid_amount*$loyalty_value['redeem_value'])
            );
            $return = $dbClass->update("order_master", $columns_value, $condition_array);


            if (strpos($data['item_number'], 'BBG') !== false) {
                $return = $dbClass->update("group_order", $columns_value, $condition_array);
            }

        }

        break;

    case "payment_table_check":
        $result = $dbClass->getResultList("SELECT * FROM payments");
        echo json_encode($result);

    case "get_order_details_by_invoice":
        //echo 1; die;
        $sql = "SELECT m.order_id, m.customer_id, 
                c.full_name customer_name, d.item_id, c.contact_no customer_contact_no, c.address customer_address,  m.order_id,
                GROUP_CONCAT(ca.name,' >> ',ca.id,'#',ca.id,'#',p.name,' (',ca.name,' )','#',p.item_id,'#',d.item_rate,'#',d.quantity,'#',d.ingredient_name,'#',d.special_instruction,'..') order_info,
                DATE_FORMAT(m.order_date, '%Y-%m-%d %h:%i %p')  order_date, DATE_FORMAT(m.delivery_date, '%Y-%m-%d %h:%i %p')  delivery_date,
				m.delivery_type, m.discount_amount, m.total_paid_amount,
                m.address, m.delivery_charge_id, m.tax_amount, m.tips,
                m.remarks, m.order_status, m.payment_status, m.payment_method, 
                m.payment_reference_no, m.invoice_no, m.total_order_amt, m.ASAP, 
                case payment_status when payment_status=1 then 'Not Paid' else 'Paid' end paid_status, 
                case payment_method when 1 then 'Cash' when 3 then 'Paypal' when 2 then 'Loyalty'  else 'Square'  end payment_method,
                case order_status when 1 then 'Ordered' when 2 then 'Rejected'  when 3 then 'Received' when 4 then 'Ready'  else 'Delivered'  end order_status_text
                FROM order_master m
                LEFT JOIN order_details d ON d.order_id = m.order_id
                LEFT JOIN customer_infos c ON c.customer_id = m.customer_id
                LEFT JOIN items p ON p.item_id = d.item_id
                LEFT JOIN category ca ON ca.id = p.category_id
                WHERE m.invoice_no= '$order_id'
                GROUP BY d.order_id
                ORDER BY m.order_id";
        //echo $sql;die;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $data['records'][] = $row;
        }
        echo json_encode($data);

        break;

    case "repeat_order":
        //var_dump($_REQUEST);die;

        $sql1 = "SELECT * FROM order_master m WHERE m.invoice_no= '$order_id'";

        $order = $dbClass->getSingleRow($sql1);
        $sql2 = "SELECT * FROM order_details m WHERE m.order_id=".$order['order_id'];

        //echo $sql;die;
        $stmt = $conn->prepare($sql2);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //echo json_encode($result); die();

        foreach ($result as $row) {
            $item_name = $dbClass->getSingleRow("SELECT name FROM items where item_id =".$row['item_id']);

            $cart_key = $row['item_id'].'_'.$row['item_rate'];
            $tem_cart[$cart_key]['item_name']=$item_name['name'];
            $tem_cart[$cart_key]['cart_key']=$cart_key;
            $tem_cart[$cart_key]['discounted_rate']=$row['item_rate'];
            $tem_cart[$cart_key]['item_id']=$row['item_id'];
            $tem_cart[$cart_key]['quantity']=$row['quantity'];
            $tem_cart[$cart_key]['item_image']='';
            $tem_cart[$cart_key]['ingredient']['id_list'] =$row['ingredient_list'];
            $tem_cart[$cart_key]['ingredient']['ingredient_name'] = $row['ingredient_name'];
            $tem_cart[$cart_key]['special_instruction'] = $row['special_instruction'];
            $tem_cart[$cart_key]['item_total']=floatval($row['item_rate']) * intval($row['quantity']);

        }

        $_SESSION['cart'] = $tem_cart;

        $tem_cart=[];

        echo 1;
        break;


    case "get_customer_details":
        //echo '1'; die;
        $customer_details = $dbClass->getResultList("SELECT c.customer_id, c.full_name, c.loyalty_points, c.contact_no, c.age, c.address,c.city,c.state,c.zipcode,
                                                    c.`status`, c.photo, c.email, c.remarks,
                                                    (CASE c.`status` WHEN 1 THEN 'Active' WHEN  0 THEN 'Inactive' END) status_text
                                                    FROM customer_infos c
                                                    WHERE c.customer_id=".$_SESSION['customer_id']);
        //echo $customer_details; die;
        foreach ($customer_details as $row){
			if($row['loyalty_points']<=0)  $row['loyalty_points']=0;
            $data['records'][] = $row;
        }
        echo json_encode($data);

        break;

    case "insert_or_update":
        if(isset($customer_id) && $customer_id != ""){
            $is_active=0;
            if(isset($_POST['is_active'])){
                $is_active=1;
            }

            $columns_value = array(
                'full_name'=>$customer_name,
                'email'=>$email,
                'address'=>$address,
                'city'=>$city,
                'state'=>$state,
                'zipcode'=>$zipcode,
                'age'=>$age,
                'contact_no'=>$contact_no,
                'email'=>$email,
                'status'=>$is_active
            );
            if($age != "" && $age != "0000-00-00"){
                $dob = date("Y-m-d", strtotime($age));
                $columns_value['age'] = $dob;
            }

            if(isset($_POST['remarks']))
                $columns_value['remarks'] = $remarks;

            if(isset($_FILES['customer_image_upload']) && $_FILES['customer_image_upload']['name']!= ""){
                $desired_dir = "../../admin/images/customer";
                chmod( "../../admin/images/customer", 0777);
                $file_name = $_FILES['customer_image_upload']['name'];
                $file_size =$_FILES['customer_image_upload']['size'];
                $file_tmp =$_FILES['customer_image_upload']['tmp_name'];
                $file_type=$_FILES['customer_image_upload']['type'];
                if($file_size < 5297152){
                    if(file_exists("$desired_dir/".$file_name)==false){
                        if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
                            $photo = "$file_name";
                    }
                    else{//rename the file if another one exist
                        $new_dir="$desired_dir/".time().$file_name;
                        if(rename($file_tmp,$new_dir))
                            $photo =time()."$file_name";
                    }
                    $photo  = "/images/customer/".$photo;
                }
                else {
                    echo "Image size is too large!";die;
                }
                $columns_value['photo'] = $photo;
            }

            //echo '-1132-';


            //	var_dump($columns_value);
            //	die;
            $condition_array = array(
                'customer_id'=>$customer_id
            );
            $return = $dbClass->update("customer_infos", $columns_value, $condition_array);

            if($return) echo "2";
            else 	echo "0";
        }
        else
            echo "0";

        break;

    case "update_password":
        $customer_id = $_SESSION['customer_id'];
        //echo 111; die;
        if(($password != "" || $pass_reset==1) && $new_password != ""){
            //echo $password;
            $old_password =  $dbClass->getResultList("SELECT password FROM customer_infos WHERE customer_id=$customer_id");

            // echo json_encode($old_password[0]['password'])+'===='+ md5($password);
            if((md5($password) == $old_password[0]['password']) || $pass_reset==1){
                $columns_value['password'] = md5($new_password);

                $condition_array = array(
                    'customer_id'=>$customer_id
                );
                $return = $dbClass->update("customer_infos", $columns_value, $condition_array);
                if($return) echo "2"; die;

            }
            else{
                echo "3";die;
            }
        }


         	echo "0";
        break;

    case "get_settings_details":
        //echo '1'; die;
        $general_settings = $dbClass->getResultList("SELECT *
												FROM general_settings s
												WHERE s.id='1'");
        //echo $customer_details; die;
        foreach ($general_settings as $row){
            $data['records'][] = $row;
        }
        echo json_encode($data);
        break;


//this portion will remove while final submission......
    case "db_update_all_items":
        $sql = "SELECT id,NAME, description, catitems  FROM btr_sellercats   WHERE  id IN(32,33,34,36,37,38,39,40,41,42,43,44,45,6,46)";
        //echo $sql;die;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($categories as $category) {
            $items_id = explode(',',$category['catitems']);
            $columns_value['name'] = $category['NAME'];
            $columns_value['id'] = $category['id'];
            $columns_value['photo']='images/category/noFood.png';

            $category_id = $dbClass->insert("category", $columns_value);
            //var_dump($return_master);die;

            foreach ($items_id as $single_item){
                if ($single_item!=""){

                    //$sql = "SELECT id,1 as category_id, NAME, description, sideitems FROM btr_selleritems WHERE id = ".$single_item;

                    $sql = "SELECT p.price, s.id,".$category_id." as category_id, s.NAME, s.description, s.sideitems FROM btr_selleritems s 
                            LEFT JOIN( 
                            SELECT itemid,price from btr_selleritemprice  
                            )p 
                            on p.itemid= s.id
                            WHERE s.id = ".$single_item;

                    //echo $sql;die;
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $option_list =  explode(',',$items[0]['sideitems']);

                    $i_columns_value['name']=$items[0]['NAME'];
                    $i_columns_value['price']=round($items[0]['price'],2);
                    $i_columns_value['details']=$items[0]['description'];
                    $i_columns_value['category_id']=$items[0]['category_id'];
                    $i_columns_value['feature_image']='images/category/noFood.png';
                    $i_item_id = $dbClass->insert("items", $i_columns_value);


                    //var_dump($i_item_id); die;
                    //$items[0]['NAME'] is item name $category['id'] is category id
                    //var_dump($category['id']);
                    //var_dump($category['id'].'->'.$items[0]['id']);
                    foreach ($option_list as $single_option) {
                        if ($single_option != "") {
                            //var_dump($single_option);die;
                            $sql = "SELECT id, name, type, checked_limit, min_checked_limit, is_required FROM btr_sellersides WHERE id = ".$single_option;
                            //echo $sql;die;
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $ingredient_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            //var_dump($ingredient_list); die;
                            if($ingredient_list[0]['is_required']=="") $ingredient_list[0]['is_required']=0;

                            $o_columns_value['name'] = $ingredient_list[0]['name'];
                            $o_columns_value['item_id'] = $i_item_id;
                            $o_columns_value['is_required'] = $ingredient_list[0]['is_required'];
                            $o_columns_value['minimum_choice'] = $ingredient_list[0]['min_checked_limit'];
                            $o_columns_value['maximum_choice'] = $ingredient_list[0]['checked_limit'];
                            $o_columns_value['type'] = $ingredient_list[0]['type'];

                            $option_id = $dbClass->insert("item_options", $o_columns_value);

                            //var_dump($ingredient_list[0]['id']);die;

                            $sql = "SELECT id,name,price,listid FROM btr_sellersideitems WHERE sid=".$ingredient_list[0]['id'];
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $option_items_list = $stmt->fetchAll(PDO::FETCH_ASSOC);




                            //var_dump($option_item_id);
                            //$option_list =  explode(',',$items[0]['sideitems']);
                            foreach ($option_items_list as $single_option_items_list){
                                $ov_column_value['option_id']=$option_id;
                                $ov_column_value['ingredient_id']=$single_option_items_list['listid'];
                                $ov_column_value['name']=$single_option_items_list['name'];
                                $ov_column_value['price']=$single_option_items_list['price'];

                                $option_item_id = $dbClass->insert("options_items", $ov_column_value);
                                var_dump($option_item_id);
                            }
                            //var_dump($items[0]['id']);
                            //var_dump($ingredient_list);
                        }
                    }

                    //var_dump($items);
                }
            }
            //$data['records'][] = $row;




        }
        //echo json_encode($items_id); die;
        break;



    case "db_update_ingredient_add":
        echo 'done'; die;
        $sql = "INSERT INTO ingredient (id, name, price) SELECT id, side as name, cost as price FROM btr_sellersides_lists";
        //echo $sql;die;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //$return_master = $dbClass->insert("ingredient", $result);

        $dbClass->print_arrays($result[0]['id']);

        foreach ($result as $row) {
            $data['records'][] = $row;
        }
        echo json_encode($data['records'][0]);
        break;


    case "import_customer":
        $sql = "SELECT bul.id, bul.username, bul.email, bul.password, bul.fname as first_name, bul.lname as last_name,
             bul.country, bul.telephone, bul.last_update ,ifnull(lp.loyalty_positive,0) - ifnull(ln.loyalty_negative, 0) as loyalty,
             CASE status when 'active' then 1 else 0 end status
            FROM btr_userlogin bul
            LEFT JOIN(
            SELECT count(credits),SUM(credits) as loyalty_positive, type, dbid 
            FROM  btr_loyalties 
            WHERE type='in'
            GROUP BY dbid
            ) lp on lp.dbid=bul.id
            LEFT JOIN(
            SELECT count(credits),SUM(credits) as loyalty_negative, type, dbid 
            FROM  btr_loyalties 
            WHERE type='out'
            GROUP BY dbid
            ) ln on ln.dbid=bul.id
            WHERE bul.id>100";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($customers as $key=>$customer){
            $columns_value = array(
                'customer_id' =>$customer['id'],
                'full_name' =>$customer['first_name'].' '.$customer['last_name'],
                'username' =>$customer['username'],
                'password' =>$customer['password'],
                'address' =>$customer['country'],
                'photo' =>'',
                'contact_no' =>$customer['telephone'],
                'email' =>$customer['email'],
                'remarks' =>'',
                'status' =>$customer['status'],
                'loyalty_points' =>$customer['loyalty']
            );
            $dbClass->insert("customer_infos", $columns_value);

        }

        break;

    case "import_order":
        // $c_y_m = date('dmy');
        //echo $c_y_m; die;

        //$orderInfo = '140[+]1[+]veggie burrito with the works|||2.50[+][+]choose a size: (14" big daddy);  choose a tortilla: (spinach tortilla);  choose vegetables: (green peppers, onions, lettuce, black olives, tomatoes, jalapenos, mushrooms, cilantro, spinach, corn, pico de gallo,);  beans: (black beans,);  choose a rice style: (brown rice);  options: (hot sauce,);  would you like the burrito cut in 1/2?: (cut the burrito in 1/2,);  would you like toppings?: (cilantro, hot sauce, jalapenos, fresh jalopenos, lettuce, plain tomatoes,);  add: (sliced avocado);|||8.25[+]|||[+]|||[+]|||[+]Lisa{++}149[+]1[+]meat quesadilla|||7.85[+][+]choose a tortilla: (whole wheat tortilla);  choose a protein: (steak);  would you like toppings?: (cheddar cheese, sour cream, lettuce,);|||0.5[+]|||[+]|||[+]|||[+]{++}137[+]1[+]burrito|||0.00[+][+]choose a size: (12" regular);  choose a tortilla: (regular tortilla);  choose a protein: (carnitas);  beans: (black beans,);  choose a rice style: (mexican rice);  options: (hot sauce, salsa verde,);  would you like toppings?: (jalapenos, sour cream, monterey jack,);|||7[+]|||[+]|||[+]|||[+]{++}137[+]1[+]burrito|||0.00[+][+]choose a size: (12" regular);  choose a tortilla: (regular tortilla);  choose a protein: (ground beef);  beans: (no beans,);  choose a rice style: (white rice);  options: (hot sauce,);|||7[+]|||[+]|||[+]|||[+]{++}';


        //var_dump($order);die;

        $sql = "SELECT 
            orderid,dbid, orderinfo, notetobuyer, future_date, total, tax, addedon,
            CASE ordertype WHEN 'ordertype' THEN 1 WHEN 'dinein' THEN 2 else 3 end ordertype,
            CASE status WHEN 'placed' THEN 1 WHEN 'Picked Up' THEN 3 WHEN 'complete' THEN 5 WHEN 'cancelled' THEN 2 else 0 end status,
            CASE payment_used WHEN 'points|||' THEN 2 WHEN 'paypal|||' THEN 3 WHEN 'cash|||' THEN 1 else 3 end payment_used
            FROM btr_userorders";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //$day = $day


        $orderNo=0;
        $itemNo=0;
        $priceNo=0;

        foreach ($result as $key=>$data){

            //------------ generate invoice no  -------------------
            $datetime = new DateTime($data['addedon']);
            $c_y_m = $datetime->format('dmy');

            $last_invoice_no = $dbClass->getSingleRow("SELECT max(RIGHT(invoice_no,5)) as invoice_no FROM order_master");

            if($last_invoice_no == null){
                $inv_no = '00001';
            }
            else{
                $inv_no = $last_invoice_no['invoice_no']+1;
            }

            $str_length = 5;
            $str = substr("00000{$inv_no}", -$str_length);
            //echo 333;die;

            $invoice_no = "BBO$c_y_m$str";
            //-----------------------------------------------------

            $price=0;

            $itemList = explode('{++}',$data['orderinfo']); //split orderinfo into items
            $x=0;
            foreach ($itemList as$key=>$value){
                $x++;
                if(isset(explode('[+]|||[+]|||[+]|||[+]',$value)[1])){
                    $message = explode('[+]|||[+]|||[+]|||[+]',$value)[1];
                } //split item into item and special instruction
                $ingredient = explode('[+][+]',explode('[+]|||[+]|||[+]|||[+]',$value)[0]); // split item into item name and ingredient
                if(isset(explode('[+]',$ingredient[0])[2])){
                    $item = explode('[+]',$ingredient[0])[2];
                    $quantity = intval(explode('[+]',$ingredient[0])[1]);
                }
                if(isset(explode('|||',$item)[0])){
                    $itemName = explode('|||',$item)[0]; //Item name
                }
                if(isset(explode('|||',$item)[1])){
                    $itemPrice = floatval(explode('|||',$item)[1]);// Item Base price
                }
                if(isset($ingredient[1]) && isset(explode('|||',$ingredient[1])[0])){
                    $options = explode('|||',$ingredient[1])[0]; //get all option with ingredients
                }
                if(isset($ingredient[1]) && isset(explode('|||',$ingredient[1])[1])){
                    $optionPrice = floatval(explode('|||',$ingredient[1])[1]); //get all option with ingredients
                }
                $optionSingle = explode(');',$options);//split options
                $ingredientList = '';
                foreach ($optionSingle as $key_2=>$option){
                    $ingre=  explode('(',$option); //ingredients for single option
                    //var_dump($ingre);
                    if(isset($ingre[1]) && strlen($ingre[1])>1){
                        $ingredientList .=$ingre[1] . ', ';
                    }
                }

                if(!isset($itemPrice)) $itemPrice=0;
                if(!isset($optionPrice)) $optionPrice=0;
                if(!isset($ingredientList)) $ingredientList='';
                if(!isset($message)) $message='';


                $price+=($itemPrice+$optionPrice)*$quantity;
                $order[$x]=[];
                $order[$x]['price']=$itemPrice+$optionPrice;
                $order[$x]['ingredients']=$ingredientList;
                $order[$x]['quantity']=$quantity;
                $order[$x]['item']=$itemName;

                if (isset($message)){
                    $order[$x]['message']=$message;
                }
                $message='';
                $ingredientList='';
                $itemPrice = 0;
                $optionPrice = 0;
            }

            $future_date =new datetime($data['future_date']);
            $delivery_date = $future_date->format('d-M-y');
            $delivery_time = $future_date->format('H:i:s');
            $d_date = date('Y-m-d', strtotime($delivery_date)).' '.$delivery_time;

            $balance_amt = floatval($data['total'])-floatval($data['tax'])-$price;
            $discount = 0;
            $tips = 0;

            if($balance_amt<0) $discount = abs($balance_amt);
            elseif ($balance_amt>0) $tips = abs($balance_amt);
            else{
                $discount = 0;
                $tips = 0;
            }


            $columns_value = array(
                'customer_id' =>$data['dbid'],
                'order_date' =>$data['addedon'],
                'delivery_date' =>$d_date,
                'delivery_type' =>$data['ordertype'],
                'discount_amount' =>$discount,
                'delivery_charge' =>0,
                'total_order_amt' =>$price,
                'total_paid_amount' =>$data['total'],
                'tax_amount' =>$data['tax'],
                'tips' =>$tips,
                'remarks' =>$data['notetobuyer'],
                'order_status' =>$data['status'],
                'order_noticed' =>2,
                'payment_status' =>2,
                'payment_method' =>$data['payment_used'],
                'invoice_no' =>$invoice_no,
                'loyalty_point' =>0,
                'group_order_id' =>0,
            );
            $id = $dbClass->insert('order_master', $columns_value);

            //echo json_encode($order); die;

            foreach ($order as $item_name=>$item_details){
                //echo $item_name,'=', $item_details['price'],'>>' ;
                //echo json_encode($item_details[$ingredients]); die;
                $columns_value = array(
                    'order_id'=>$id,
                    'item_id'=>0,
                    'item_name'=>$item_details['item'],
                    'item_rate_id'=>0,
                    'quantity'=>intval($item_details['quantity']),
                    'item_rate'=>floatval($item_details['price']),
                    'status'=>1,
                    'ingredient_list'=>'',
                    'ingredient_name'=>$item_details['ingredients'],
                    'special_instruction'=>$item_details['message']
                );
                $details_id = $dbClass->insert('order_details', $columns_value);
            }
            $order=[];
            //echo $details_id;make_payment
        }

        break;
    case "closest_date":

        $day =  date("l");
        $time = date("H:i:s");

        $sql='SELECT * FROM serving_days WHERE 
            open<= "'.$time.'" AND 
            close>= "'.$time.'" and day="'.$day.'"';

        $sql2 = 'SELECT * FROM serving_days WHERE 
            open>= "'.$time.'" and day="'.$day.'"';

        $result = $dbClass->getSingleRow($sql);
        $result2 = $dbClass->getSingleRow($sql2);

        if($result ==null && $result2 == null){
            $day_id = $dbClass->getSingleRow("SELECT id FROM serving_days WHERE day='".$day."'");

            if($day_id['id']==7){
                $id = 1;
            }else{
                $id = 1+intval($day_id['id']);
            }
            $results = $dbClass->getSingleRow("SELECT open FROM serving_days WHERE id=$id");
            $data = array(
                'status'=>1,
                'time'=>$results['open']
            );
        }
        elseif ($result2 != null){
            $data = array(
                'status'=>2,
                'time'=>$result2['open']
            );
        }
        else{
            $data = array(
                'status'=>0
            );
        }

        echo json_encode($data); die;

        break;

    case "date_checker":

        //echo $time; die;
        $sql='SELECT * FROM special_day WHERE 
            DATE_FORMAT(date_from,"%Y %m %d")<=DATE_FORMAT("'.$date.'","%Y %m %d") AND 
            DATE_FORMAT(date_to,"%Y %m %d")>=DATE_FORMAT("'.$date.'","%Y %m %d")';

        //echo $sql; die;

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $key=>$data){
            if($data['all_day']==1 && $data['status']==0) {echo 2; die;}
            if(date ('H:i',strtotime($time))< date ('H:i',strtotime($data['open'])) || date ('H:i',strtotime($time))>date ('H:i',strtotime($data['close']))) {echo 3; die;}
        }

       // echo 8; die;

        $sql = "SELECT * FROM serving_days where id=".$day;

        $result = $dbClass->getSingleRow($sql);


        if(date ('H:i',strtotime($time))< date ('H:i',strtotime($result['open'])) || date ('H:i',strtotime($time))>date ('H:i',strtotime($result['close']))) {echo 4; die;}

        echo 1; 

    break;

    case "check_unpaid_order":
        $user_id = $_SESSION['customer_id'];
        $today = date('Y-m-d H:i:s');
        //echo $today; die();
        $condition = 'and  "'.$today.'" < delivery_date ';
        $condition .= 'and payment_status=1 and (payment_method=3 or payment_method=4)  AND order_status=1';

        //$user_id=22;

        $ssql = 'SELECT invoice_no, order_id from order_master where customer_id= '.$user_id .' '.$condition.' order by delivery_date ASC';
        //echo $ssql; die;

        $result = $dbClass->getResultList($ssql);
		
		//$dbClass->print_arrays($result);die;

        echo json_encode($result);
        break;
	
	case "cancel_order":
		$columns_value['order_status'] = 2;
		$condition_array = array(
			'invoice_no'=>$unpaid_order_id
		);
		$return = $dbClass->update("order_master", $columns_value, $condition_array);
		//echo $return;die;
		if($return) echo "1";
		else echo 0; 
		
	break;


    case "test_mail":
        $invoice_no = 'BB022000108';

        $sql = "SELECT m.order_id, m.customer_id, 
                    c.full_name customer_name, c.contact_no customer_contact_no, c.address customer_address, 
                    GROUP_CONCAT(ca.name,' >> ',ca.id,'#',ca.id,'#',p.name,' (',ca.name,' )','#',p.item_id,'#',d.item_rate,'#',d.quantity) order_info,
                    m.order_date, m.delivery_date, m.delivery_type,m.delivery_charge, m.discount_amount, m.total_paid_amount,
                    m.total_order_amt, m.tax_amount,m.tips,m.remarks,m.payment_reference_no, m.invoice_no, m.total_order_amt,
                    case m.order_status when 1 then 'ordered' when 2 then 'rejected' when 3 then 'Picked Up' when 4 then 'ready'  else 'gift delivered' end order_status,
                    case payment_status when 1 then 'Not Paid' when 3 then 'refunded' else 'Paid' end paid_status, 
                    case payment_method when 1 then 'cash' when 2 then 'loyalty_point' when 3 then 'Paypal'  else 'Square'  end payment_method
                    FROM order_master m
                    LEFT JOIN order_details d ON d.order_id = m.order_id
                    LEFT JOIN customer_infos c ON c.customer_id = m.customer_id
                    LEFT JOIN items p ON p.item_id = d.item_id
                    LEFT JOIN category ca ON ca.id = p.category_id
                    WHERE m.invoice_no= '$invoice_no'
                    GROUP BY m.order_id
                    ORDER BY m.order_id";
        //echo $sql; die;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            extract($row);
        }
        //echo 55; die;
        $body = '';

        $body .= "<link rel='stylesheet' type='text/css' href='".$dbClass->getDescription("website_url")."plugin/bootstrap/bootstrap.css'>
                        <div id='order-div'>
                            <div class='title text-center' style='text-align: center'>
                                <img src='".$dbClass->getDescription("website_url")."admin/images/banner/burritoLogo.png' alt='' style='alignment: center'>
                                <h4 class='text-coffee'  style='alignment: center'>Order No # <span id='ord_title_vw'>$invoice_no</span></h4>
                            </div>
                            <div class='done_registration '>							    
                                <div class='doc_content'>
                                    <div width = '100%' >
                                    <table width='100%'><tbody><tr><td>
                                        <h4>Order Details:</h4>				
                                        <div style='width: 48%; text-align: left'>
                                            <span id='ord_date'>Ordered Time: $order_date</span><br/> 
                                            <span id='dlv_date'>Delivery Time $delivery_date</span> <br/> 
                                            <span id='dlv_date'>Payment Status : $paid_status</span> <br/> 
                                            <span id='dlv_date'>Payment Method : $payment_method</span>
                                        </div>
                                        </td><td style='text-align: right'>
                                        <div style='width: 48%; text-align: right'>
                                            <h4>Customer Details:</h4> 								
                                            <address id='customer_detail_vw'>
                                            $customer_name
                                            <br/><b>Mobile:</b>$customer_contact_no
                                            <br/><b>Address:</b>$customer_address
                                            </address>
                                        </div>
                                        </td></tr></tbody></table>
                                    </div>
                                    <div id='ord_detail_vw col-md-12'> 
                                        <table width='100%' style='background-color: grey'>
                                            <thead>
                                                <tr>
                                                    <th style='text-align: center; background-color: white; padding:10px'>Product</th>
                                                    <th width='10%' style='text-align: center; background-color: white; padding:10px'>Quantity</th>
                                                    <th width='18%' style='text-align: right; background-color: white; padding:10px'>Rate</th>                           
                                                    <th width='18%'  style='text-align: right; background-color: white; padding:10px'>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>";
        $order_info_arr = explode(',', $order_info);
        $order_total = 0;
        foreach($order_info_arr as $key=>$item_details){
            $item_details_arr = explode('#', $item_details);
            $total = ($item_details_arr[5]*$item_details_arr[4]);
            $body .= "<tr><td style=' background-color: white; padding:10px'>".$item_details_arr[2]."</td><td style='text-align: center; background-color: white; padding:10px'>".$item_details_arr[5]."</td><td style='text-align: right; background-color: white; padding:10px'>".$item_details_arr[4]."</td><td style='text-align: right; background-color: white; padding:10px'>".number_format($total,2)."</td></tr>";
            $order_total += $total;
        }

        $total_order_bill = $order_total-$discount_amount;
        $total_paid 	  = $total_paid_amount;
        $body .= '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px" ><b>Total </b></td><td style="text-align: right; background-color: white; padding:10px "><b>'.number_format($order_total ,2).'</b></td></tr>';
        $body .= '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px"><b>Discount </b></td><td style="text-align: right; background-color: white; padding:10px"><b>'.number_format($discount_amount,2).'</b></td></tr>';
        $body .= '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px" ><b>Tax </b></td><td style="text-align: right; background-color: white; padding:10px"><b>'.number_format($tax_amount,2).'</b></td></tr>';
        $body .= '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px" ><b>Tips</b></td><td style="text-align: right; background-color: white; padding:10px"><b>'.number_format($tips,2).'</b></td></tr>';
        $body .= '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px" ><b>Total Amount</b></td><td style="text-align: right; background-color: white; padding:10px"><b>'.number_format($total_paid_amount,2).'</b></td></tr>';
        $body .= "										
                                            </tbody>
                                        </table>
                                        <p>Note: <span id='note_vw'>$remarks</span></p>
                                        <p>Print Time :". date('Y-m-d h:m:s')."</p>
                                        <br />
                                        <p style='font-weight:bold; text-align:center'>Thank you. Hope we will see you soon </p>
                                    </div> 
                                </div>									
                            </div>							
                        </div>
                    ";

        echo $body;
        break;

    case "test_mail_group":
        $order_id = 'BBGO022000034';

        $sql = "SELECT ci.full_name, ci.address as c_address, ci.contact_no as mobile, gi.name, go.order_id as group_order_id, go.order_date,go.remarks, go.tax_amount, go.discount_amount, go.delivery_date, go.total_order_amt, go.notification_time,  go.order_status as status, go.invoice_no,go.tips,go.total_paid_amount,
                    case go.order_status when 2 then 'Invitation Sent' when 3 then 'Menu Selected' when 4 then 'Order Panding' when 5 then 'Order Approved' when 6 then 'Order Ready' else 'Order Initiate' end order_status, 
                    case go. payment_status when 1 then 'Not Paid' else 'Paid' end payment_status, 
                    case go.payment_method when 1 then 'Cash On Delivery' when 2 then 'Loyalty Payment' when 3 then 'Card' when 4 then 'Square' else 'Not Defined' end payment_method
                    from group_order go
                    LEFT JOIN groups_info gi ON gi.id = go.group_id
                    LEFT JOIN(
                    SELECT full_name, address, contact_no,customer_id from customer_infos 
                    )ci ON ci.customer_id=go.customer_id              
                     WHERE go.order_id ='$order_id' OR go.invoice_no = '$order_id'";
        //echo $sql;die;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            extract($row);
        }

        $body_top = "<table width='100%'><tbody><tr><td>
                    <h4>Order Details:</h4>				
                    <div style='width: 48%; text-align: left'>
                        <span id='ord_date'>Ordered Time: $order_date</span><br/> 
                        <span id='dlv_date'>Delivery Time $delivery_date</span> <br/> 
                        <span id='dlv_date'>Payment Status : $payment_status</span> <br/> 
                        <span id='dlv_date'>Payment Method : $payment_method</span>
                    </div>
                    </td><td style='text-align: right'>
                    <div style='width: 48%; text-align: right'>
                        <h4>Customer Details:</h4> 								
                        <address id='customer_detail_vw'>
                        $full_name
                        <br/><b>Mobile:</b>$mobile
                        <br/><b>Address:</b>$c_address
                        </address>
                    </div>
                    </td></tr></tbody></table>";

        //echo $body_top;
        $order_summary = '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px" ><b>Total </b></td><td style="text-align: right; background-color: white; padding:10px "><b>'.number_format($total_order_amt ,2).'</b></td></tr>';
        $order_summary .= '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px"><b>Discount </b></td><td style="text-align: right; background-color: white; padding:10px"><b>'.number_format($discount_amount,2).'</b></td></tr>';
        $order_summary .= '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px" ><b>Tax </b></td><td style="text-align: right; background-color: white; padding:10px"><b>'.number_format($tax_amount,2).'</b></td></tr>';
        $order_summary .= '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px" ><b>Tips</b></td><td style="text-align: right; background-color: white; padding:10px"><b>'.number_format($tips,2).'</b></td></tr>';
        $order_summary .= '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px" ><b>Total Amount</b></td><td style="text-align: right; background-color: white; padding:10px"><b>'.number_format($total_paid_amount,2).'</b></td></tr>';


//echo $order_summary; die;



        $sql = " SELECT coalesce(oms.order_id, 'NAN') as order_id, god.id, coalesce(oms.order_info, '') as order_info, 
                coalesce(oms.order_date, '') as order_date, coalesce(oms.total_order_amt, '0') as total_order_amt, 
                coalesce(oms.order_status, '0') as order_status, gm.name, gm.email, god.id as group_order_details_id, god.order_key
               FROM group_order go
               LEFT JOIN group_order_details god ON god.group_order_id= go.order_id
               LEFT JOIN group_members gm ON gm.id=god.group_member_id
               LEFT JOIN(
               SELECT om.order_id, om.group_order_details_id,
                GROUP_CONCAT(ca.name,' >> ',ca.id,'#',ca.id,'#',p.name,' (',ca.name,' )','#',p.item_id,'#',d.item_rate,'#',d.quantity,'#',d.ingredient_name,'#',d.special_instruction,'..') order_info,
                om.order_date, om.total_order_amt, om.order_status 
                FROM order_master om
                LEFT JOIN order_details d ON d.order_id = om.order_id
                LEFT JOIN items p ON p.item_id = d.item_id
                LEFT JOIN category ca ON ca.id = p.category_id
                GROUP BY d.order_id
                )oms ON oms.group_order_details_id= god.id
                WHERE go.order_id ='$order_id' OR go.invoice_no = '$order_id'";
        //echo $sql;die;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $order_body = '';
        foreach ($result as $row) {
            extract( $row);
            $order_info_arr = explode('#..', $order_info);
            $order_total = 0;
            //echo json_encode($order_info_arr);
            foreach( $order_info_arr as $key=>$item_details){
                if($order_info_arr[$key] !=null){
                    $item_details_arr = explode('#', $item_details);
                    $total = (floatval($item_details_arr[5])*floatval($item_details_arr[4]));
                    if($item_details_arr[6]){

                        $order_body    .= '<tr>
                            <td class="text-capitalize" style="background-color: white; padding:10px">'.$item_details_arr[2].' 
                                <br>'.$item_details_arr[6].'<br>';
                        if(isset($item_details_arr[7])){
                            $order_body .='<i style="color: black">'.$item_details_arr[7].'</i>';
                        }

                        $order_body .= '</td>
                            <td align="center" style="background-color: white; padding:10px">'.$item_details_arr[5].'</td>
                            <td align="right" style="background-color: white; padding:10px">'.$item_details_arr[4].'</td>
                            <td align="right" style="background-color: white; padding:10px">'.$total.'</td></tr>';
                    }
                    //$order_body .= "<tr><td style=' background-color: white; padding:10px'>".$item_details_arr[2]."</td><td style='text-align: center; background-color: white; padding:10px'>".$item_details_arr[6]."</td><td style='text-align: right; background-color: white; padding:10px'>".$item_details_arr[4]."</td><td style='text-align: right; background-color: white; padding:10px'>".number_format($total,2)."</td></tr>";

                }
            }
            //echo  json_encode($order_body);
        }

        //die;

        //echo 55; die;
        $body = '';

        $body .= "<link rel='stylesheet' type='text/css' href='".$dbClass->getDescription("website_url")."plugin/bootstrap/bootstrap.css'>
                        <div id='order-div'>
                            <div class='title text-center' style='text-align: center'>
                                <img src='".$dbClass->getDescription("website_url")."admin/images/banner/burritoLogo.png' alt='' style='alignment: center'>
                                <h4 class='text-coffee'  style='alignment: center'>Order No # <span id='ord_title_vw'>$invoice_no</span></h4>
                            </div>
                            <div class='done_registration '>							    
                                <div class='doc_content'>
                                    <div width = '100%' >
                                    ".$body_top."
                                    </div>
                                    <div id='ord_detail_vw col-md-12'> 
                                        <table width='100%' style='background-color: grey'>
                                            <thead>
                                                <tr>
                                                    <th style='text-align: center; background-color: white; padding:10px'>Product</th>
                                                    <th width='10%' style='text-align: center; background-color: white; padding:10px'>Quantity</th>
                                                    <th width='18%' style='text-align: right; background-color: white; padding:10px'>Rate</th>                           
                                                    <th width='18%'  style='text-align: right; background-color: white; padding:10px'>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>";
        $body .= $order_body;
        $body .= $order_summary;

        $body .= "										
                                            </tbody>
                                        </table>
                                        <p>Note: <span id='note_vw'>$remarks</span></p>
                                        <p>Print Time :". date('Y-m-d h:m:s')."</p>
                                        <br />
                                        <p style='font-weight:bold; text-align:center'>Thank you. Hope we will see you soon </p>
                                    </div> 
                                </div>									
                            </div>							
                        </div>
                    ";

        echo $body;



        break;





}






?>