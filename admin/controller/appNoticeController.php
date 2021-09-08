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
	
	case "insert_or_update":
		//var_dump($_REQUEST);die;
		if(isset($master_id) && $master_id == ""){

			$columns_value = array(
				'type'=>$type,
				'title'=>$title,
				'details'=>strip_tags($details),
				'posted_by'=>$loggedUser
			);
			$return = $dbClass->insert("app_notice", $columns_value);
			
			if($return){
				if($type == 1){
					//send notice to the customer/group member for app notice
					if(isset($customer_name) && $customer_name != ''){
						$return_noti = $dbClass->insert_notification(NULL, $title.'<br/>'.strip_tags($details), 0, $customer_id, $type);
						if($return_noti) echo "1";
						else             echo "0";	
					}
					if(isset($customer_group_select) && $customer_group_select != ''){
						foreach($customer_group_select as $group_id){
							$group_members  = $dbClass->getResultList("SELECT c.customer_id 
																	FROM customer_infos c 
																	LEFT JOIN customer_group_member m ON m.customer_id = c.customer_id
																	LEFT JOIN customer_group g ON g.id = m.group_id
																	WHERE m.`status` = 1 AND g.id = $group_id"); 
							foreach($group_members as $row){
								$return_noti = $dbClass->insert_notification(NULL, $details, 0, $row['customer_id'], $type);
								if($return_noti) echo "1";
								else             echo "0";	
							}														
						}
					}
				}
				else if($type == 2 || $type == 3){
					//var_dump($_REQUEST);die;
					//send notice & email to the customer/group member 
					if(isset($customer_name) && $customer_name != ''){
						$return_noti = $dbClass->insert_notification(NULL, $title, 0, $customer_id, $type);
						if($return_noti){
							//send email to the customer/group member
							//send_mail(mail_to,subject,message)
							$customer_mail = $dbClass->getResultList("SELECT email FROM customer_infos WHERE customer_id = '$customer_id'");
							foreach($customer_mail as $mail){
								$dbClass->sendMail($mail['email'],$title,$details);
							}
						} 
					}
					if(isset($customer_group_select) && $customer_group_select != ''){
						foreach($customer_group_select as $group_id){
							$group_members  = $dbClass->getResultList("SELECT c.customer_id, email 
																	FROM customer_infos c 
																	LEFT JOIN customer_group_member m ON m.customer_id = c.customer_id
																	LEFT JOIN customer_group g ON g.id = m.group_id
																	WHERE m.`status` = 1 AND g.id = $group_id"); 
							foreach($group_members as $row){
								$return_noti = $dbClass->insert_notification(NULL, $title, 0, $row['customer_id'], $type);	
								if($return_noti){
									//send email to the customer/group member
									$dbClass->sendMail($row['email'],$title,$details);
								} 
							}														
						}
					}
				}
			}
			
			if($return) echo "1";	
			else 		echo "0"; 
		}
		//update
		/* else if(isset($master_id) && $master_id>0){
			//var_dump($_POST);die;
			$columns_value = array(
				'type'=>$type,
				'title'=>$title,
				'details'=>$details
			);
			$condition_array = array(
				'id'=>$master_id
			);
			$return = $dbClass->update("app_notice",$columns_value, $condition_array);
			if($return) echo "2";
			else        echo "0";
			 
		} */
	
	break;
	
	
	case "grid_data":
		$start = ($page_no*$limit)-$limit;
        $end   = $limit;
        $data = array();

        $condition = "";
        //# advance search for grid        
        $condition =	" WHERE CONCAT(title, details) LIKE '%$search_txt%' ";
        
        $countsql = "SELECT COUNT(id) FROM app_notice $condition";
        $stmt = $conn->prepare($countsql);
        $stmt->execute();
        $total_records = $stmt->fetchColumn();
        $data['total_records'] = $total_records;
        $total_pages = $total_records/$limit;
        $data['total_pages'] = ceil($total_pages);
		$sql = "SELECT id, title, details, type,
				(CASE type WHEN 1 THEN 'Notice' WHEN 2 THEN 'Email' END) type_text	
				FROM app_notice
				$condition   
				ORDER BY id ASC limit $start, $end";
				//echo $sql;die;
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $row) {
			$data['records'][] = $row;
		}
		echo json_encode($data);
    break;	
	
	case "get_template_details":
		//var_dump($_POST);die;
		$sql = "select * from app_notice where id = $template_id";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		foreach ($result as $row) {
			$data['records'][] = $row;
		}			
		echo json_encode($data);
					
	break;

	
}
?>