<?php
session_start();
include '../includes/static_text.php';
include("../dbConnect.php");
include("../dbClass.php");

$dbClass = new dbClass;
$conn       = $dbClass->getDbConn();
$loggedUser = $dbClass->getUserId();

extract($_REQUEST);

switch ($q){



    case "general_setting_data":

        $update_permission = $dbClass->getUserGroupPermission(98);
        if($update_permission==1){
            $emp_details = $dbClass->getResultList("SELECT * FROM general_settings WHERE general_settings.id =1");
            foreach ($emp_details as $row){
                $data['records'][] = $row;
            }
            echo json_encode($data);
        }
        break;

    case "update_setting":
        //echo $square; die();
            if(isset($_FILES['company_logo']) && $_FILES['company_logo']['name']!= ""){
                $file_name = $_FILES['company_logo']['name'];
                $file_size =$_FILES['company_logo']['size'];
                $file_tmp =$_FILES['company_logo']['tmp_name'];
                $file_type=$_FILES['company_logo']['type'];
                if(($file_type =='image/png' || $file_type =='image/jpeg' || $file_type =='image/jpg') && $file_size < $file_max_length){
					$desired_dir = "../images/banner";
					chmod( "../images/banner", 0775);
					
                    if(file_exists("$desired_dir/".$file_name)==false){
                        if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
                            $photo = "$file_name";
                    }
                    else{//rename the file if another one exist
                        $new_dir="$desired_dir/".time().$file_name;
                        if(rename($file_tmp,$new_dir))
                            $photo =time()."$file_name";
                    }
                    $photo  = "images/banner/".$photo;
                }
                else {
                    echo $img_error_ln; die;
                }
            }
            else{
                $logo = $dbClass->getSingleRow("select general_settings.company_logo from general_settings where general_settings.id = 1");
                $photo= $logo['company_logo'];
                //echo  $photo['logo']; die;
                //$logo = $photo[0];
            }

			//var_dump($_REQUEST);die;
            $columns_value = array(
                'id'=>1,
                'company_name'=>$company_name,
                'website_title'=>$website_title,
                'website_url'=>$website_url,
                'web_admin_email'=>$web_admin_email,
                'order_email'=>$order_email,
                'web_admin_contact'=>$web_admin_contact,
                'store_name'=>$store_name,
                'store_address'=>$store_address,
                'store_longitude'=>$store_longitude,
                'store_latitude'=>$store_latitude,
                'store_contact'=>$store_contact,
                'store_incharge_name'=>$store_incharge_name,
                'company_logo'=>$photo,
                'yelp_url'=>$yelp_url,
                'fb_url'=>$fb_url,
                'tweeter_url'=>$tweeter_url,
                'instagram_url'=>$instagram_url,
                'item_image_display'=>$item_image_display,
                'ingredient_image_display'=>$ingredient_image_display,
                'meta_description'=>$meta_description,
                'meta_keywards'=>$meta_keywards,
                'currency'=>$currency,
                'currency_symbol'=>$currency_symbol,
                'decimal_placement'=>$decimal_placement,
                'tax_enable'=>$tax_enable,
                'minimum_order_amount'=>$minimum_order_amount,
                'takeout'=>$takeout,
                'delivery'=>$delivery,
                'dinein'=>$dinein,
                'card_payment'=>$card_payment,
                'cash_payment'=>$cash_payment,
                'loyelty_payment'=>$loyelty_payment,
                'payment_card_visa'=>$payment_card_visa,
                'payment_card_master'=>$payment_card_master,
                'payment_card_amex'=>$payment_card_amex,
                'payment_card_discover'=>$payment_card_discover,
                'point_reserve_value'=>$point_reserve_value,
                'redeem_value'=>$redeem_value,
                'tax_type'=>$tax_type,
                'tax_amount'=>$tax_amount,
                'paypal_email'=>$paypal_email,
                'meta_tag'=>$meta_tag,
                'meta_key'=>$meta_key,
                'paypal'=>$paypal,
                'square'=>$square,
                'square_email'=>$square_email,
				'mpesa'=>$mpesa,
				'mpesa_shortkey'=>$mpesa_shortkey,
				'mpesa_passkey'=>$mpesa_passkey,
				'mpesa_conkey'=>$mpesa_conkey,
				'mpesa_seckey'=>$mpesa_seckey,
				'home_message_title'=>$home_message_title,
				'home_message_details'=>$home_message_details,
				'is_show'	=>isset($is_show)?1:0,
            );
            $condition_array = array(
                'id'=>1
            );

            $return = $dbClass->update("general_settings", $columns_value, $condition_array);

            if($return) echo "1";
            else 	echo "0";
        break;

}
?>


