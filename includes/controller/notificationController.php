<?php 
session_start();
include("../dbConnect.php");
include("../dbClass.php");

$dbClass = new dbClass;
$conn       = $dbClass->getDbConn();
$loggedCustomer = $dbClass->getCustomerId();

extract($_REQUEST);

switch ($q){
	
	case "insert_notification":		
		/* $stock_alert_product = $dbClass->getResultlist("SELECT r.id, r.stock_quantity, CONCAT(p.name,' >>',s.name) p_name 
														FROM product_rate r 
														LEFT JOIN products p ON p.product_id = r.product_id
														LEFT JOIN size s ON s.id = r.size_id
														WHERE p.sell_from_stock = 1 AND
														(
															SELECT ss.stock_alert_quantity 
															FROM settings ss 
															WHERE r.stock_quantity <= ss.stock_alert_quantity
														)");  
		
		foreach($stock_alert_product as $row){
			
			$unread_notification = $dbClass->getSingleRow("SELECT * FROM notification n WHERE n.`status` = 0 AND n.product_rate_id = '".$row['id']."'"); 
			//var_dump($unread_notification);die;
			if(empty($unread_notification)){
				$columns_value = array(
					'product_rate_id'=>$row['id'],
					'details'=>'Add <b>'.$row['p_name'].'</b> in Stock.'
				);			
				$return = $dbClass->insert("notification", $columns_value);		
			}
		} */
		
	break;
	
	case "load_notifications":
        if(!isset($_SESSION['customer_id'])) return 0;

        $customer_id =  isset($_SESSION['customer_id'])? $_SESSION['customer_id']:-1;
		//$start = ($page_no*$limit)-$limit;
		$start = 0;
        $end   = $limit*$page_no;
		//return $start.'='.$end;
		$total_unread_notification  = $dbClass->getSingleRow("SELECT count(id) unread FROM notification nt where nt.status=0 and nt.notified_to = $customer_id");  
		$data['total_unread']       = $total_unread_notification['unread'];					
		$sql = "SELECT nt.id, nt.order_id, nt.status, nt.details, date_time
				FROM notification nt
				WHERE nt.notification_user_type = 0 and nt.notified_to = $customer_id 
				ORDER BY nt.status, nt.id DESC
				limit $start, $end";	
		//echo $sql;die;
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
		foreach ($result as $row) {	
			$row['details']	 = $row['details']	;
			$data['records'][] = $row;
		}				
		echo json_encode($data);					
	break;
	
	case "load_notifications_no":
	    if(!isset($_SESSION['customer_id'])) return 0;

		$customer_id = $_SESSION['customer_id'];
		
		$total_un_notified_notification = $dbClass->getSingleRow("SELECT count(nt.id) un_notified from notification nt WHERE nt.status=0 and nt.notified_to = 1 and nt.notified_to = $customer_id AND TIME_TO_SEC(TIMEDIFF(current_timestamp, date_time)) < 3000");
		$data['total_un_notified']   	= $total_un_notified_notification['un_notified'];	
		
		$total_unread_notification  = $dbClass->getSingleRow("SELECT count(id) unread FROM notification nt where nt.status=0 and nt.notified_to = $customer_id");  
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

    case "get_all_notifications":
        $start = ($page_no*$limit)-$limit;
        $end   = $limit;
        $data = array();


        $condition =	" WHERE CONCAT(id, order_id, details, read_status) LIKE '%$search_txt%' ";



        $countsql = "SELECT count(id) FROM(SELECT id, order_id, read_status,details,date_time 
                        FROM (SELECT nt.id, nt.order_id, nt.status, nt.details, date_time,
                            CASE nt.status when 1 then 'Seen' else 'Unseen' end read_status
                            FROM notification nt
                            WHERE nt.notification_user_type = 0 and nt.notified_to = ".$_SESSION['customer_id']."
                            ORDER BY nt.status, nt.id DESC)A
                            $condition   
                        )B
					";
        //echo $countsql;die;

        $stmt = $conn->prepare($countsql);
        $stmt->execute();
        $total_records = $stmt->fetchColumn();
        $data['total_records'] = $total_records;
        $data['entry_status'] = 0;
        $total_pages = $total_records/$limit;
        $data['total_pages'] = ceil($total_pages);
        $sql = "SELECT id, order_id, read_status,details,date_time 
                FROM (SELECT nt.id, nt.order_id, nt.status, nt.details, date_time,
                    CASE nt.status when 1 then 'Seen' else 'Unseen' end read_status
                    FROM notification nt
                    WHERE nt.notification_user_type = 0 and nt.notified_to = ".$_SESSION['customer_id']."
                    ORDER BY nt.status, nt.id DESC)A
                $condition    
                LIMIT $start, $end";
        //echo $sql;die;
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