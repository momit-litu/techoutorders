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
    case "insert_or_update_special_day":
        //echo json_encode($_REQUEST); die;
        if(isset($special_day_id) && $special_day_id == ""){

            if(isset($all_day)){
                $all_day_status = 1;
            }else{
                $all_day_status = 0;
            }

            $columns_value = array(
                'date_from'=>$date_from,
                'date_to'=>$date_to,
                'open'=>$open,
                'close'=>$close,
                'status'=>$status,
                'all_day'=>$all_day_status

            );

            $return = $dbClass->insert("special_day", $columns_value);

            if($return){
                echo "1";
            }else "0";
        }
        else if(isset($special_day_id) && $special_day_id>0){

            if(isset($all_day)){
                $all_day_status = 1;
            }else{
                $all_day_status = 0;
            }

            $columns_value = array(
                'date_from'=>$date_from,
                'date_to'=>$date_to,
                'open'=>$open,
                'close'=>$close,
                'status'=>$status,
                'all_day'=>$all_day_status

            );

            $condition_array = array(
                'id'=>$special_day_id
            );

            $return = $dbClass->update("special_day", $columns_value, $condition_array);

            if($return) echo "2";
            else 	    echo "0";
        }
        break;

    case "grid_data_special_day":
        $start = ($page_no*$limit)-$limit;
        $end   = $limit;
        $data = array();

        $entry_permission   	    = $dbClass->getUserGroupPermission(50);
        $delete_permission          = $dbClass->getUserGroupPermission(51);
        $update_permission          = $dbClass->getUserGroupPermission(52);

        $category_grid_permission   = $dbClass->getUserGroupPermission(53);

        $countsql = "SELECT count(id)
					FROM(
						SELECT *
						FROM special_day c
					)A
					WHERE CONCAT(date_from, date_to) LIKE '%$search_txt%'";
        //echo $countsql;die;
        $stmt = $conn->prepare($countsql);
        $stmt->execute();
        $total_records = $stmt->fetchColumn();
        $data['total_records'] = $total_records;
        $data['entry_status'] = $entry_permission;
        $total_pages = $total_records/$limit;
        $data['total_pages'] = ceil($total_pages);
        if($category_grid_permission==1){
            $sql = 	"SELECT date_from, date_to, id,all_day,status,
					$update_permission as update_status, $delete_permission as delete_status
					FROM(
						SELECT date_from, date_to, id,
						CASE all_day WHEN 1 THEN 'All Day' ELSE CONCAT(open,'-',close) end all_day,
						CASE status WHEN 1 THEN 'Open' ELSE 'Close' end status
						FROM special_day c
					)A
					WHERE CONCAT(date_from, date_to) LIKE '%$search_txt%'
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

    case "get_special_day_details":
        $update_permission = $dbClass->getUserGroupPermission(52);
        if($update_permission==1){
            $sql = "SELECT *
						FROM special_day c
					WHERE c.id=$id";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $data['records'][] = $row;
            }
            echo json_encode($data);
        }
        break;

    case "get_category_details":
        $update_permission = $dbClass->getUserGroupPermission(52);
        if($update_permission==1){
            $sql = "SELECT c.id, c.code, c.name, ifnull(c.photo,'') photo, ec.id parent_id, ifnull(ec.name,'') parent_name
					FROM category c
					LEFT JOIN category ec on c.parent_id = ec.id
					WHERE c.id=$category_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $data['records'][] = $row;
            }
            echo json_encode($data);
        }
        break;

    case "delete_special_day":
        $delete_permission = $dbClass->getUserGroupPermission(51);
        if($delete_permission==1){

            $condition_array = array(
                'id'=>$special_day_id
            );
            $return = $dbClass->delete("special_day", $condition_array);
        }
        if($return) echo "1";
        else 		echo "0";
        break;

    case "get_serving_days_data":
        $sql_query = "SELECT *
					FROM serving_days
					ORDER BY id";
        //echo $sql_query;die;
        $stmt = $conn->prepare($sql_query);
        $stmt->execute();
        $json = array();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $data['records'][] = $row;
        }
        echo json_encode($data);
        break;

    case "update_serving_day":
        foreach ($id as $key=>$value){
            $columns_value= array(
                'open'=>$open[$key],
                'close'=>$close[$key]
            );
            $condition_array = array(
              'id'=>$value
            );

            $dbClass->update('serving_days',$columns_value,$condition_array);
        }

}
?>