<?php
session_start();
include '../dbConnect.php';	
include("../dbClass.php");

$dbClass = new dbClass;	
extract($_POST);

if($q=="login_customer"){
    //echo 1; die;

	$username	= htmlspecialchars($_POST['username'],ENT_QUOTES);

    $pass	  	 = $_POST['password'];
	$query="select customer_id, password,full_name, token, email from customer_infos WHERE (username='".$username."' or email='".$username."') and status=1";
    //$query="select customer_id, password, token, email from customer_infos WHERE ( email='".$username."') and status=1";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $data = array();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //$result = $dbClass->getResultList($query);
    //echo json_encode($result); die;
    foreach ($result as $row) {
			$data['records'][] = $row;
		}	
		//if username exists
		if($stmt -> rowCount()>0){
			//compare the password
			if($row['password'] == md5($pass)){				
				$_SESSION['customer_id']=$row['customer_id'];
                $_SESSION['customer_name']=$row['full_name'];
                $_SESSION['customer_email']=$row['email'];
                if (isset($is_app) && $is_app==1 ){
                    if (isset($row['token']) && $row['token']!='' && $row['token']!=null){
                        $_SESSION["token"] = $row['token'];
                    }else{
                        $token = $username.$row['customer_id'];
                        $columns_value = array(
                            'token' => $token
                        );
                        $condition_array = array(
                            'customer_id' => $row['customer_id']
                        );

                        $tokenUpdate = $dbClass->update('customer_infos',$columns_value,$condition_array);
                        if($tokenUpdate)
                            $_SESSION["token"] = $token;
                    }
                }
				echo 1;
			}
			else
				echo 2; 
		}
		else
			echo 3; //Invalid Login
}


if($q=="forget_password"){
	$forget_email	 = htmlspecialchars($_POST['forget_email'],ENT_QUOTES);
	$query="select email, username, customer_id from customer_infos WHERE  email='".$forget_email."'  and status=1";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$data = array();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
		foreach ($result as $row) {
			$data['records'] = $row;
		}	
		//if username exists
		if($stmt -> rowCount()>0){
			// mail a new password to customer_id
			$customer_email = $data['records']['email'];
			$username 		= $data['records']['username'];
			$customer_id 	= $data['records']['customer_id'];			
			$new_password 	= mt_rand(100000,999999);

            $original_string = array_merge(range(0,9), range('a','z'), range('A', 'Z'));
            $original_string = implode("", $original_string);

            $web_url = $dbClass->getDescription('website_url');


            if(isset($is_app)){
                $pass_key= substr(str_shuffle($original_string), 0, 8);
                $body = 'Dear '.$username.',<br><p>We are processing your password reset request, Your new password is: "'. $pass_key.'" </p><p>Please reset your password right after login.</p><p><br> Thank,</p><br>Techoutorders';
                $columns_value = array(
                    'password' => md5($pass_key)
                );
                $condition_array = array(
                    'customer_id' =>$customer_id
                );
                $status = 0;
            }else{
                $pass_key= substr(str_shuffle($original_string), 0, 20);
                $pass_reset_url = $web_url.'index.php?passreset='.$pass_key;
                $body = 'Dear '.$username.',<br><p>We are processing your password reset request, Please <a href="'.$pass_reset_url.'">click here</a> to login your account and update password.<b></p><p>Keep Old password field empty while reset your password through this link.</b></p><p><br> Thank,</p><br>Techoutorders';
                $status = 1;
            }



            //echo $pass_reset_url;

			$to 	 = $forget_email;
			$from 	 = $dbClass->getDescription('web_admin_email');
			$subject = "Password Reset Request from Techoutorders";

			
			$headers = 'From: ' . $from . "\r\n" .
					'Reply-To: ' . $from . "\r\n" .
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
			
			//echo $to ."  ".$subject."   ".$body."  ". $headers;die;
			
			$sent_status = mail($to, $subject, $body, $headers);
			if($sent_status == 1 ){
			    if(isset($is_app)){
			        $result = $dbClass->update('customer_infos',$columns_value,$condition_array);
                }

			    $columns_value = array(
			        'customer_id'=> $data['records']['customer_id'],
			        'secret_key'=>$pass_key,
                    'status'=>$status
                );

			    $return = $dbClass->insert('password_reset',$columns_value);
				echo 1;
			}				
			else
				echo 2; 	
		}
		else
			echo 2; //Invalid email address
}

if($q =="password_reset_url_check"){
    //echo 1;
    $sql = "Select count(id) as id, customer_id FROM password_reset WHERE  secret_key ='".$key."' AND status = 1";
    //echo $sql; die;
    $result = $dbClass->getSingleRow($sql);



    if($result['id']==1) {

        $customer = $dbClass->getSingleRow("SELECT customer_id, email FROM customer_infos WHERE customer_id = ".$result['customer_id']);

        $_SESSION['customer_id']=$customer['customer_id'];
        //$_SESSION['customer_name']=$row['full_name'];
        $_SESSION['customer_email']=$customer['email'];

        echo 1;
    }
    else echo 0;
}


if($q=="registration"){
    $web_url = $dbClass->getSingleRow("select website_url from general_settings where id=1");
	$username	 = htmlspecialchars($_POST['cust_username'],ENT_QUOTES);
	$email	 = htmlspecialchars($_POST['cust_email'],ENT_QUOTES);
	
	$check_username = $dbClass->getSingleRow("select username from customer_infos WHERE  username='".$username."'");
	if(isset($check_username['username']) && $check_username['username'] != "") { echo 2; die;} //username is found, same username cant be taken
	
	$check_email = $dbClass->getSingleRow("select email from customer_infos WHERE  email='".$email."'");
	if(isset($check_email['email']) && $check_email['email'] != "") { echo 3; die;} //email is found, same email cant be taken
	
	$columns_value = array(
		'full_name'=>$cust_name,
		'username'=>$cust_username,
		'email'=>$cust_email,
		'address'=>$cust_address,
		'contact_no'=>$cust_contact,
        'state'=>$state,
        'city'=>$city,
        'zipcode'=>$zipcode,
		'status'=>1,
		'password'=>md5($cust_password),
        'token'=>'',

    );
	//var_dump($columns_value);die;
	$return = $dbClass->insert("customer_infos", $columns_value);
    //echo $return; die;



    if($return) {
        //Email
        $to 	 = $cust_email;
        $from 	 = $dbClass->getDescription('web_admin_email');
        $subject = "Registration Confirmation";
        $body 	 = 'Dear '.$cust_name.'<br><p>You have been successfully registered to Techoutorders. 
            To Select Menus please visit <a href="'.$web_url["website_url"].'"> Techoutorders</a>.</p><p><br><br>
            Best Regards</p><p><b>Techoutorders</b></p><br>Note: Please Do not reply this email.';

        try {
            $dbClass->sendMail ($to, $subject, $body);
        }catch (Exception $e){

        }

        /* need to add chaki start */
        $common_group_id = $dbClass->getSingleRow("SELECT id FROM customer_group WHERE group_name = 'All'");
        $columns_value = array(
            'group_id'=>$common_group_id['id'],
            'customer_id'=>$return,
            'status'=>1
        );
        $dbClass->insert("customer_group_member", $columns_value);
        /* need to add chaki end */

        $query="select customer_id, full_name, password, email from customer_infos WHERE (customer_id= '".$return."')";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $data = array();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $data['records'][] = $row;
        }
        //if username exists
        if($stmt -> rowCount()>0){
            $_SESSION['customer_id']=$row['customer_id'];
            $_SESSION['customer_name']=$row['full_name'];
            $_SESSION['customer_email']=$row['email'];
        }
        echo "1";
    }
	else 	echo "0";	
}

if($q=="contact_us_mail"){
	$to 	 = $dbClass->getDescription('web_admin_email');
	$from 	 = $email;
	$subject = "Contact us mail from $name. '$subject'";

    $headers = "From:" . $from;

    $body 	 = '<p>'.$message.'</p><p>Send By: '.$name.'</p><p>Mobile: '. $mobile;
    $return = $dbClass->contactMail($from,$to,$subject,$body);

    //echo $to ." @@@ ".$subject." @@@@  ".$body." @@@ ". $headers;die;
	if($return = 1) echo 1;
	else		 echo 2;
}


if($q=="insert_custom_cake"){
	
	$cc_image = "";
	if(isset($_FILES['cc_image_upload']) && $_FILES['cc_image_upload']['name'] != ""){
		$desired_dir = "../../admin/images/custom";
		chmod( "../../admin/images/custom", 0777);				
		$file_name = $_FILES['cc_image_upload']['name'];
		$file_size =$_FILES['cc_image_upload']['size'];
		$file_tmp =$_FILES['cc_image_upload']['tmp_name'];
		$file_type=$_FILES['cc_image_upload']['type'];	
		if($file_size < 5297152 ){
			if(file_exists("$desired_dir/".$file_name)==false){
				if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
					$cc_image = "$file_name";						
			}
			else{//rename the file if another one exist
				$new_dir="$desired_dir/".time().$file_name;
				if(rename($file_tmp,$new_dir))
					$cc_image =time()."$file_name";				
			}
			chmod( "../../admin/images/custom/".$cc_image, 0775);
			$cc_image  = "/images/custom/".$cc_image;
		}
		else{
			echo "3";die;
		}
	}
	else{
		$cc_image = "/images/no_image.png";
	}	

	$columns_value = array(
		'cc_cake_weight'=>$cc_cake_weight,
		'cc_cake_tyre'=>$cc_cake_tyre,
		'cc_delevery_date'=>$cc_delevery_date,
		'cc_details'=>$cc_details,
		'cc_name'=>$cc_name,
		'cc_email'=>$cc_email,
		'cc_image'=>$cc_image,
		'cc_mobile'=>$cc_mobile
	);	
	//var_dump($columns_value);die;
	
	$return = $dbClass->insert("custom_cake", $columns_value);	
	if($return) echo "1";
	else 	echo "0";
}

if($q=="duplicate_id_check"){
    //echo 1;
    if($type=='username'){
        $sql = "select username from customer_infos WHERE  username='$userInfo'";
    }
    else{
        $sql = "select username from customer_infos WHERE  email='$userInfo'";
    }
    $check_userInfo = $dbClass->getSingleRow($sql);
    if($check_userInfo){
        echo 0;
    }
    else
        echo 1;
}
 //     cc_attached_file 

?>