<?php
session_start();
include '../includes/static_text.php';
include("../dbConnect.php");
include("../dbClass.php");

$dbClass = new dbClass;
$conn       = $dbClass->getDbConn();
$loggedUser = $dbClass->getUserId();
$permission = $dbClass->getUserGroupPermission(98);

extract($_REQUEST);

switch ($q){


    /************************************************ Unit CRUID ***************************************************/
    case "insert_or_update_unit":
        if(isset($unit_id) && $unit_id == ""){
            $check_unit_name_availability = $dbClass->getSingleRow("select count(id) as no_of_unit from units where unit_name='$unit_name'");
            if($check_unit_name_availability['no_of_unit']!=0) { echo 5; die;}

            $is_active =(isset($_POST['is_active']))?1:0;

            //echo $base_unit_id; die;

            //echo $base_unit_id; die;

            $columns_value = array(
                'unit_name'=>$unit_name,
                'short_name'=>$short_name,
                'status'=>$is_active,
                'note'=>($note?$note:NULL)
            );
            //$dbClass->print_arrays($columns_value);
            $return = $dbClass->insert("units", $columns_value);
            if($return) echo "1";
            else 	echo "0";
        }
        else{
            $check_unit_name_availability = $dbClass->getSingleRow("select count(id) as no_of_unit from units where unit_name='$unit_name' and id!=$unit_id");
            if($check_unit_name_availability['no_of_unit']!=0) { echo 5; die;}

            $is_active =(isset($_POST['is_active']))?1:0;

            $columns_value = array(
                'unit_name'=>$unit_name,
                'short_name'=>$short_name,
                'status'=>$is_active,
                'note'=>($note?$note:NULL)
            );
            $condition_array = array(
                'id'=>$unit_id
            );
            //$dbClass->print_arrays($columns_value);
            $return = $dbClass->update("units", $columns_value, $condition_array);

            if($return) echo "2";
            else 	echo "0";
        }
        break;

    case "grid_data_unit":
        $start = ($page_no*$limit)-$limit;
        $end   = $limit;
        $data = array();

        $countsql = "SELECT count(id)
				FROM(
					SELECT m.id, short_name, ifnull(note,'') note, unit_name, 
					CASE m.status WHEN 1 THEN 'Active'  WHEN 0 THEN 'Inactive' END status_text
					FROM units m
				)A
				WHERE CONCAT(id, unit_name, ifnull(note,''),short_name,status_text) LIKE '%$search_txt%'";
        //echo $countsql;die;
        $stmt = $conn->prepare($countsql);
        $stmt->execute();
        $total_records = $stmt->fetchColumn();
        $data['total_records'] = $total_records;
        $data['entry_status'] = $permission;
        $total_pages = $total_records/$limit;
        $data['total_pages'] = ceil($total_pages);
        if($permission==1){
            $sql = 	"SELECT id, unit_name, short_name, note, status_text status,
				    $permission as update_status,  $permission as delete_status
					FROM(
					SELECT m.id, short_name, ifnull(note,'') note, unit_name, 
						CASE m.status WHEN 1 THEN 'Active'  WHEN 0 THEN 'Inactive' END status_text
						FROM units m
					)A
					WHERE CONCAT(id, unit_name, ifnull(note,''),short_name,status_text) LIKE '%$search_txt%'
					ORDER BY id DESC
					LIMIT $start, $end";


            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $data['records'][] = $row;
            }
            echo json_encode($data);
        }
        break;


    case "get_unit_details":
        if($permission==1){
            $unit_details = $dbClass->getResultList("
				SELECT m.id, ifnull(m.note,'') note, m.short_name, m.unit_name,  m.status, b.unit_name base_unit_name
						FROM units m	
						left join units b on b.id=m.base_unit 						
				WHERE m.id='$unit_id'");

            foreach ($unit_details as $row){
                $data['records'][] = $row;
            }
            echo json_encode($data);
        }
        break;


    case "delete_unit":
        if($permission==1){
            $condition_array = array(
                'id'=>$unit_id
            );
            $columns_value = array(
                'status'=>0
            );
            $return = $dbClass->update("units", $columns_value, $condition_array);
        }
        if($return==1) echo "1";
        else 		   echo "0";
        break;

    /*----------------------------------------------- END Unit ---------------------------------------------------*/








}
?>