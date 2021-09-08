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
				'details'=>$details,
				'dynamic_variables'=>$dynamic_variables,
			);
			$return = $dbClass->insert("template", $columns_value);

			if($return) echo "1";	
			else 		echo "0";
		}
		//update
		else if(isset($master_id) && $master_id>0){
			//var_dump($_POST);die;
			$columns_value = array(
				'type'=>$type,
				'title'=>$title,
				'details'=>$details,
				'dynamic_variables'=>$dynamic_variables,
			);
			$condition_array = array(
				'id'=>$master_id
			);
			$return = $dbClass->update("template",$columns_value, $condition_array);
			if($return) echo "2";
			else        echo "0";
			 
		}
	
	break;
	
	
	case "grid_data":
		$start = ($page_no*$limit)-$limit;
        $end   = $limit;
        $data = array();

        $condition = "";
        //# advance search for grid        
        $condition =	" WHERE CONCAT(title, details) LIKE '%$search_txt%' ";
        
        $countsql = "SELECT COUNT(id) FROM template $condition";
        $stmt = $conn->prepare($countsql);
        $stmt->execute();
        $total_records = $stmt->fetchColumn();
        $data['total_records'] = $total_records;
        $total_pages = $total_records/$limit;
        $data['total_pages'] = ceil($total_pages);
		$sql = "SELECT id, title, details, type,
				(CASE type WHEN 1 THEN 'Notice' WHEN 2 THEN 'Email' END) type_text	
				FROM template
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
		$sql = "select * from template where id = $template_id";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		foreach ($result as $row) {
			$data['records'][] = $row;
		}			
		echo json_encode($data);
					
	break;
	
	case "get_customer_groups":
        $user_groups = $dbClass->getResultList("select group_concat(cg.id,'*', cg.group_name) module_group_ids
												from 
												customer_group cg where cg.status=1");
        foreach ($user_groups as $row) {
            $module_group_ids_arr = explode(',',$row['module_group_ids']);
            $arr['module_group']=$module_group_ids_arr;
            $data['records'][] = $arr;
        }
        echo json_encode($data);
    break;
	
	
	case "templateInfo":
        $sql ="SELECT id, title, details FROM template WHERE title LIKE '%$term%' ORDER BY id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $json = array();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();

        if($count>0){
            foreach ($result as $row) {
                $json[] = array('id' => $row["id"],'label' => $row["title"],'details' => $row['details']);
            }
        } else {
            $json[] = array('id' => "0",'label' => "No name available !!!");
        }
        echo json_encode($json);
     break;
	
}
?>