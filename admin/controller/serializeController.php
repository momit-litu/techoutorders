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
	case "insert_update_category_serial":		
		//var_dump($_REQUEST);die;
		foreach ($category_id as $key=>$value) {
			$columns_value = array(
                'serial'=>$serial_no[$key]
            );
			$condition_array = array(
                'id'=>$value
            );
            $return = $dbClass->update("category", $columns_value, $condition_array);
		}
		if($return) echo '1';
		else        echo '0';  
	break;
	
	case "category_grid_data":
		$sql = 	"SELECT c.id, c.name, ifnull(c.serial,'') serial FROM category c";
			//	echo $sql;die;
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
		foreach ($result as $row) {
			$data['records'][] = $row;
		}			
		echo json_encode($data);
	break;
	
	case "insert_update_item_serial":		
		//var_dump($_REQUEST);die;
		foreach ($item_id as $key=>$value) {
			$columns_value = array(
                'serial'=>$serial_no[$key]
            );
			$condition_array = array(
                'item_id'=>$value
            );
            $return = $dbClass->update("items", $columns_value, $condition_array);
		}
		if($return) echo '1';
		else        echo '0';  
	break;
	
	case "item_grid_data":
		$sql = 	"SELECT i.item_id, i.name item_name, ifnull(i.serial,'') serial FROM items i WHERE i.category_id = $category_id";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
		foreach ($result as $row) {
			$data['records'][] = $row;
		}			
		echo json_encode($data);
	break;
	
	case "load_item_category_wise":
		$sql = 	"SELECT i.item_id, i.name item_name, ifnull(i.serial,'') serial FROM items i WHERE i.category_id = $category_id";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
		foreach ($result as $row) {
			$data['records'][] = $row;
		}			
		echo json_encode($data);
	break;
	
	case "insert_update_option_serial":		
		//var_dump($_REQUEST);die;
		foreach ($option_id as $key=>$value) {
			$columns_value = array(
                'serial'=>$serial_no[$key]
            );
			$condition_array = array(
                'id'=>$value
            );
            $return = $dbClass->update("item_options", $columns_value, $condition_array);
		}
		if($return) echo '1';
		else        echo '0';  
	break;
	
	case "option_grid_data":
		$sql = 	"SELECT id, NAME AS option_name, ifnull(serial,'') serial FROM item_options WHERE item_id='$item_id'";
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