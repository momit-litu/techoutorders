<?php
include("imageLibrary.php");

class dbClass {		
	private $dbCon;	
	private $userId;


	public function __construct() {
		include("dbConnect.php");			
		$this->dbCon  		= $conn;
		$this->userId 		= $_SESSION['user_id'];

		
	}
	
	function getDbConn(){
		return $this->dbCon;
	}
	
	function getUserId(){
		return $this->userId;
	}

	

	
	// insert function for all table
	// created ny moynul
	// parameter table name, inserted table column_name and value array
	function insert($table_name,$columns_values){		
		try {				
			$this->dbCon->beginTransaction();	
			//$this->print_arrays($columns_values);
			$bind = ':'.implode(',:', array_keys($columns_values));
			
			$columns =  implode(',', array_keys($columns_values));
				
			$master_sql = "Insert into $table_name ($columns)  VALUES ($bind)";	
			//echo $master_sql;die;	
			$stmt = $this->dbCon->prepare($master_sql);
    		$return = $stmt->execute(array_combine(explode(',',$bind), array_values($columns_values)));
			if($return == 1){
				$just_inserted_id = $this->dbCon->lastInsertId();
				if($just_inserted_id) $original_return = $just_inserted_id;
				else 				  $original_return = 1;
			}
			else 
				$original_return = 0;
			
			$this->dbCon->commit();
			return $original_return; 
			
		} catch(PDOException $e) {
			$this->dbCon->rollback();
			echo "Insert:Error: " . $e->getMessage();
		}		
	}
	
	
	function update($table_name, $columns_values,$condition_array){		
		try {				
			$this->dbCon->beginTransaction();
			$condition_bind = ':'.implode(',:', array_keys($condition_array));			
			$bind = ':'.implode(',:', array_keys($columns_values));
			
			$set_string = "";
			foreach($columns_values as $key=>$value) {
				$set_string .= "$key =:$key, ";
			}
			
			$con_string = "";
			$count_i = 1;
			foreach($condition_array as $key=>$value) {
				if(count($condition_array) != $count_i)
					$con_string .= "$key =:$key AND ";
				else 
					$con_string .= "$key =:$key";				
				$count_i++;
			}		
			
			$updatesql = "update $table_name set ".rtrim($set_string,", ")."  where $con_string";
			
		//	echo $updatesql;die;
			$stmt = $this->dbCon->prepare($updatesql);
		
			$condition_combined_array  = array_combine(explode(',',$condition_bind), array_values($condition_array));
			$columns_combined_array   = array_combine(explode(',',$bind), array_values($columns_values));
			$bind_array = array_merge($condition_combined_array, $columns_combined_array );  
			$return = $stmt->execute($bind_array); 
			$this->dbCon->commit();
			return $return;
			
		} catch(PDOException $e) {
			$this->dbCon->rollback();
			echo "Insert:Error: " . $e->getMessage();
		}		
	}	
	
	function delete($table_name ,$condition_array){		
		try {	
			$this->dbCon->beginTransaction();
			$condition_bind = ':'.implode(',:', array_keys($condition_array));
			$con_string = "";
			$count_i = 1;
			foreach($condition_array as $key=>$value) {
				if(count($condition_array) != $count_i)
					$con_string .= "$key =:$key AND ";
				else 
					$con_string .= "$key =:$key";				
				$count_i++;
			}
			$deletesql = "delete  from $table_name where $con_string";
			$stmt = $this->dbCon->prepare($deletesql);
			$condition_combined_array  = array_combine(explode(',',$condition_bind), array_values($condition_array));	
    		$return = $stmt->execute($condition_combined_array);
			$this->dbCon->commit();
			return $return; 
			
		} catch(PDOException $e) {
			$this->dbCon->rollback();
			echo "Insert:Error: " . $e->getMessage();
		}		
	}
	
	function getDescription($page_id){
	    $result = $this->getSingleRow("select $page_id from general_settings where id='1'");
		//$result = $this->getSingleRow("select description from web_menu where id = '$page_id'");
		//$result_info  =$result[$page_id];
		return $result[$page_id];
		//return $result[$page_id];
	}
	
	function getCompanyLogo(){
	    $result = $this->getSingleRow("select company_logo from general_settings where id='1'");
		return $result['company_logo'];
	}
	
	function getSingleRow($sql){
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return 	$result;	
	}
	
	function getSingleRowByKey($sql){
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch();
		return 	$result;	
	}
	
	function getResultList($sql){		
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return 	$result;	
	}

    function store_uploaded_image($file_name, $new_img_width, $new_img_height, $target_dir, $target_file) {
        /*$target_dir = "your-uploaded-images-folder/";
        $target_file = $target_dir . basename($_FILES[$html_element_name]["name"]);*/
        $image = new SimpleImage();
        $image->load($file_name);
        $image->resize($new_img_width, $new_img_height);
        $image->save($target_file);
        return $target_file; //return name of saved file in case you want to store it in you database or show confirmation message to user
    }
	
	function arrangeCondition($conditions){		
		$condition = "";
		$i=0;
		foreach ($conditions as $key=>$cond){				
			if($cond != "" ){
				if($i == 0)	{

					$condition .= " Where " .$cond;
				}
				else
					$condition .= " AND ".$cond;
				
				$i++;
			}
		}
		return 	$condition;	
	}
	
	

	/*	
	function getUserPermission($action_id){	
		$logged_user = $this->userId;		
		$status_return = $this->getSingleRow("select status from emp_activity_permission where emp_id='$logged_user' and activity_action_id=$action_id");
		return 	$status_return['status'];
	}
	*/
	function getUserGroupPermission($action_id){	
		$logged_user = $this->userId;
		$logged_user_groups = $this->getResultList("select group_id from user_group_member where emp_id = '$logged_user' and status = 1");
		if(empty($logged_user_groups)){
			return 0;
			die;
		}
		$groups = "";
		foreach($logged_user_groups as $row){
			$groups .="'".$row['group_id']."',";
			
		}

		$groups = substr($groups,0,-1);	
		$status_return = $this->getSingleRow("select status from user_group_permission where group_id in(".$groups.") and action_id = $action_id order by status desc limit 1");
		
		return 	$status_return['status'];		
	}
	
	
	function getUserDetails($user_id){
		$sql = "select emp_id,full_name,,designation_name, concat(e.full_name, ' (',e.designation_name,')') employee_name,
				photo,contact_no,email,blood_group,team_leader from user_infos e where emp_id='$user_id'";
		$stmt = $this->dbCon->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return 	$result;		
	}
	

	function sendSMS($mobile_nos,$message){		
		try{
			$soapClient = new SoapClient("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl");
			$paramArray = array(
				'userName'  => "01980340482",
				'userPassword'  => "34178",
				'messageText'  => "$message",
				'numberList'  => "$mobile_nos",
				'smsType' => "TEXT",
				'maskName'   => '',
				'campaignName'  => '',
			);
			//var_dump($paramArray);die;
			$value= $soapClient->__call("OneToMany", array($paramArray));
			echo 1;
		} 
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}	
    public function print_arrays()
    {
        $args = func_get_args();
        echo "<pre>";
        foreach ($args as $arg) {
            print_r($arg);
        }
        echo "</pre>";
        die();
    }

	public function print_arrays_no_die()
    {
        $args = func_get_args();
        echo "<pre>";
        foreach ($args as $arg) {
            print_r($arg);
        }
        echo "</pre>";
    }

	
	//notification function
	function insert_notification($order_id, $details, $notification_user_type, $notified_to, $notification_type){		
		
		$columns_values = array(
			'order_id'=>$order_id,
			'details'=>$details,
			'notification_user_type'=>$notification_user_type,
			'notified_to'=>$notified_to,
			'notification_type'=>$notification_type
		);	
		//var_dump($columns_value);die;
		try {				
			$this->dbCon->beginTransaction();	
			//$this->print_arrays($columns_values);
			$bind = ':'.implode(',:', array_keys($columns_values));
			
			$columns =  implode(',', array_keys($columns_values));
				
			$master_sql = "Insert into notification ($columns)  VALUES ($bind)";	
			//echo $master_sql;die;	
			$stmt = $this->dbCon->prepare($master_sql);
    		$return = $stmt->execute(array_combine(explode(',',$bind), array_values($columns_values)));
			if($return == 1){
				$just_inserted_id = $this->dbCon->lastInsertId();
				if($just_inserted_id) $original_return = $just_inserted_id;
				else 				  $original_return = 1;
			}
			else 
				$original_return = 0;
			
			$this->dbCon->commit();
			return $original_return; 
			
		} catch(PDOException $e) {
			$this->dbCon->rollback();
			echo "Insert:Error: " . $e->getMessage();
		}		
	}
    //email function
    /*function sendMail ($to, $subject, $body){
        try {
            ini_set( 'display_errors', 1 );
            error_reporting( E_ALL );
            $from = "info@Techoutorders.com";
            $to =  $to;
            $subject = $subject;
            $message = $body;
            $headers = "From:" . $from;
            mail($to,$subject,$message, $headers);
            //echo "Test email sent52120";
            return 1;
        }catch (Exception $e){
            return 0;
        }

    }*/

    function sendMail ($to, $subject, $body){

        ini_set( 'display_errors', 1 );
        error_reporting( E_ALL );

        $from = "info@Techoutorders.com";
        $to =  $to;
        $subject = $subject;
        $message = $body;
        $headers = "From:" . $from . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' ;
        mail($to,$subject,$message, $headers);
        //echo "Test email sent52120";
        return 1;
    }
    function send_mail($customer_id, $subject, $order_id) {
        //echo "mail function";die;


        $email = $this->getSingleRow("SELECT email, full_name FROM customer_infos WHERE customer_id = $customer_id");
        $webEmail = $this->getDescription('web_admin_email');
        
        ini_set( 'display_errors', 1 );
        error_reporting( E_ALL );

        $to 	 = $email['email'];
        $from 	 = $webEmail;
        $subject = "Your order no #$order_id has been $subject by Techoutorders";
        $body 	 = "Dear ".$email['full_name']."<br>Your order". $order_id." has been". $subject." by Techoutorders. <br>Thanks<br>Techoutorders";

        $headers = "From:" . $from . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' ;

        mail($to, $subject, $body, $headers);
    }
	
}

?>