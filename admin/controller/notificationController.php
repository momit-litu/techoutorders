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
	/*
	case "insert_notification":		
		//var_dump($_REQUEST);die;
		
		if(isset($customer_name) && $customer_name != ''){
			$return_noti = $dbClass->insert_notification(NULL, $details, 0, $customer_id, $notification_type);
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
				foreach($group_members as $id){
					$return_noti = $dbClass->insert_notification(NULL, $details, 0, $id['customer_id'], $notification_type);	
				}														
			}
			if($return_noti) echo "1";
			else             echo "0"; 
		}
		
	break;
	*/
	case "load_notifications":

	    if(!isset($_SESSION['user_id']) || $_SESSION['user_id']==null || $_SESSION['user_id']==0){
	        echo 0; die();
        }
	    //echo json_encode($_SESSION);
        //echo $_SESSION['timeout'];

        $entry_permission   	    = $dbClass->getUserGroupPermission(74);
        $delete_permission          = $dbClass->getUserGroupPermission(75);
        $update_permission          = $dbClass->getUserGroupPermission(76);

        $category_grid_permission   = $dbClass->getUserGroupPermission(77);

		if(isset($n_type) && $n_type == 'all'){
			$con = '';
		}else{
			$con = ' WHERE notification_user_type = 1 ';
		}

		if(isset($search_text) && isset($con) && $con==''){
            $con = ' WHERE CONCAT(order_id, details, status_text) LIKE "%'.$search_txt.'%"';
        }
		else if(isset($search_text) && isset($con) && $con!=''){
		    $con.='AND CONCAT(order_id, details, status_text) LIKE "%'.$search_txt.'%"';
        }
		else if(isset($search_text)){
            $con = ' WHERE CONCAT(order_id, details, status_text) LIKE "%'.$search_txt.'%"';
        }
		$start = ($page_no*$limit)-$limit;
		$end   = $limit;		
		$total_unread_notification  = $dbClass->getSingleRow("SELECT count(id) unread FROM notification nt where nt.status=0 and nt.notification_user_type = 1");  
		$data['total_unread']       = $total_unread_notification['unread'];					
		$sql = "SELECT * FROM(SELECT nt.id, ifnull(nt.order_id, '') as order_id, nt.status, nt.details, date_time,notification_user_type,
                CASE  nt.status WHEN 0 THEN 'Not Seen' else 'Seen' end status_text,
                $update_permission as update_status, $delete_permission as delete_status
				FROM notification nt)A
				$con
				ORDER BY status, id DESC
				limit $start, $end";	
		//echo $sql;die;
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		//echo json_encode($result);
		foreach ($result as $row) {
		    //echo json_encode($row);
			$data['records'][] = $row;
		}				
		echo json_encode($data);					
	break;
	
	case "load_notifications_no":
	
		$total_un_notified_notification = $dbClass->getSingleRow("SELECT count(nt.id) un_notified from notification nt WHERE nt.status=0 and nt.notification_user_type = 1 AND TIME_TO_SEC(TIMEDIFF(current_timestamp, date_time)) < 3000");  
		$data['total_un_notified']   	= $total_un_notified_notification['un_notified'];	
	
		$total_unread_notification  = $dbClass->getSingleRow("SELECT count(id) unread FROM notification nt where nt.status=0 and nt.notification_user_type = 1");  
		$data['total_unread']       = $total_unread_notification['unread'];	
		
		$data['nofication_details_message'] = "";
		if($data['total_unread']>0){
			$sql = "SELECT nt.id, nt.order_id, nt.status, nt.details, date_time
					FROM notification nt
					ORDER BY nt.status,nt.id DESC limit 1";	
			//echo $sql;die;
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
			foreach ($result as $row) {	
				$search_for = array("<b>", "</b>");
				$details_msg = str_replace($search_for, '', $row['details']); 					
				$data['nofication_details_message'] = $details_msg;
			}				
		}								
		echo json_encode($data);					
	break;
	
	case "update_notification_status":	
		$columns_value = array(
			'status'=>1
			// need to add view notification time here later @momit 26-07-2017
		);		 
		$condition_array = array(
			'id'=>$notification_id
		);
		$return = $dbClass->update("notification",$columns_value, $condition_array);		
		if($return==1)  echo "1";
		else 			echo "0";		
	break;

    case "get_order_id":
        $sql = 'SELECT order_id FROM notification WHERE id='.$notification_id;
        $result=$dbClass->getSingleRow($sql);
        echo $result['order_id'];
}



	



?>