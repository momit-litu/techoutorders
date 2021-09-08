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
		if(isset($size_id) && $size_id == ""){

			$columns_value = array(
				'name'=>$size_name,
				'code'=>$size_code
			);
			$return = $dbClass->insert("size", $columns_value);
			
			if($return){ 
				echo "1";
			}else "0";
		}
		else if(isset($size_id) && $size_id>0){
	
			$columns_value = array(
				'name'=>$size_name,
				'code'=>$size_code
			);
			
			$condition_array = array(
				'id'=>$size_id
			);	
			
			$return = $dbClass->update("size", $columns_value, $condition_array);
							
			if($return) echo "2";
			else 	    echo "0";		 
		}
	break;
	
	case "grid_data":
		$start = ($page_no*$limit)-$limit;
		$end   = $limit;
		$data = array();
		
		$entry_permission   	    = $dbClass->getUserGroupPermission(58);
		$delete_permission          = $dbClass->getUserGroupPermission(59);
		$update_permission          = $dbClass->getUserGroupPermission(60);
		
		$size_grid_permission   = $dbClass->getUserGroupPermission(61);
		
		$countsql = "SELECT count(id)
					FROM(
						SELECT c.id, c.code, c.name
						FROM size c
						ORDER BY c.id
					)A
					WHERE CONCAT(id, code, name) LIKE '%$search_txt%'";
		//echo $countsql;die;
		$stmt = $conn->prepare($countsql);
		$stmt->execute();
		$total_records = $stmt->fetchColumn();
		$data['total_records'] = $total_records;
		$data['entry_status'] = $entry_permission;	
		$total_pages = $total_records/$limit;		
		$data['total_pages'] = ceil($total_pages); 
		if($size_grid_permission==1){
			$sql = 	"SELECT id, name, code, 
					$update_permission as update_status, $delete_permission as delete_status
					FROM(
						SELECT c.id, c.code, c.name
						FROM size c
						ORDER BY c.id
					)A
					WHERE CONCAT(id, name, code) LIKE '%$search_txt%'
					ORDER BY id desc
					LIMIT $start, $end";
					//echo $sql;die;
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
			foreach ($result as $row) {
				$data['records'][] = $row;
			}			
			echo json_encode($data);	
		}
	break;
	
	case "get_size_details":
		$update_permission = $dbClass->getUserGroupPermission(60);
		if($update_permission==1){
			$sql = "SELECT c.id, c.code, c.name
					FROM size c
					WHERE c.id=$size_id";
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);	
			foreach ($result as $row) {
				$data['records'][] = $row;
			}			
			echo json_encode($data);	
		}			
	break;
	
	case "delete_size":		
		$delete_permission = $dbClass->getUserGroupPermission(59);
		if($delete_permission==1){
			$condition_array = array(
				'id'=>$size_id
			);
			$return = $dbClass->delete("size", $condition_array);
		}
		if($return) echo "1";
		else 		echo "0";
	break;
	
}
?>