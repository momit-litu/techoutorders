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
		
		if(isset($group_id) && $group_id == ""){
			$is_active = 0;
			if(isset($_POST['is_active'])){
				$is_active = 1;
			}
			$columns_value = array(
				'group_name'=>$group_name,
				'status'=>$is_active
			);
			$return = $dbClass->insert("customer_group", $columns_value);

			if($return){
				$customer_result = $dbClass->getResultList("select customer_id from customer_infos");
				foreach($customer_result as $row){
					$columns_value = array(
						'group_id'=>$return,
						'customer_id'=>$row['customer_id'],
						'status'=>0
					);
					$return_group_member = $dbClass->insert("customer_group_member",$columns_value);
				}
			}
			if($return_group_member) echo "1";	
			else 					 echo "0";
		}
		//update
		else if(isset($group_id) && $group_id>0){
			//var_dump($_POST);die;
			$is_active = 0;
			if(isset($_POST['is_active'])){
				$is_active = 1;
			}
			$columns_value = array(
				'group_name'=>$group_name,
				'status'=>$is_active
			);
			$condition_array = array(
				'id'=>$group_id
			);
			$return = $dbClass->update("customer_group",$columns_value, $condition_array);
			if($return) echo "2";
			else        echo "0";
			 
		}
	
	break;
	
	case "insert_or_update_groups":
        if(isset($temp_id) && $temp_id == ""){
            if(isset($_POST['group'])){
				$group_result = $dbClass->getResultList("select id from customer_group where status=1");
				foreach($group_result as $row){
					$columns_value = array(
						'group_id'=>$row['id'],
						'customer_id'=>$customer_id,
						'status'=>0
					);
					$return_group = $dbClass->insert("customer_group_member",$columns_value);
				}
				if($return_group){
					foreach($group as $key=>$module_group_id){
						$columns_value = array('status'=>1);
						$condition_array = array(
							'group_id'=>$module_group_id,
							'customer_id'=>$customer_id,
						);
						$return_succes = $dbClass->update("customer_group_member", $columns_value, $condition_array);
						if(!$return_succes) break;
					}
				}
			}
			else{
				$group_result = $dbClass->getResultList("select id from customer_group where status=1");
				foreach($group_result as $row){
					$columns_value = array(
						'group_id'=>$row['id'],
						'customer_id'=>$customer_id,
						'status'=>0
					);
					$return_succes = $dbClass->insert("customer_group_member",$columns_value);
					//echo $return_succes."--";
				}
			}
            if($return_succes) echo "1";
            else 	           echo "0";
        }
        else{
            $columns_value = array('status'=>0);
			$condition_array = array('customer_id'=>$customer_id);
			$return_group = $dbClass->update("customer_group_member",$columns_value, $condition_array);
			if($return_group){
				if(isset($_POST['group'])){
					foreach($group as $key=>$module_group_id){
						$columns_value = array('status'=>1);
						$condition_array = array(
							'group_id'=>$module_group_id,
							'customer_id'=>$customer_id,
						);
						//var_dump($condition_array);die;
						$return_succes = $dbClass->update("customer_group_member", $columns_value, $condition_array);
					}	
				}
			}
            if($return_group) echo "2";
            else 			  echo "0";
        }
    break;
	
	case "grid_data":
		//var_dump($_POST);die;	
		$sql = 	"SELECT id, group_name, status_text
				from(
					select u.id, u.group_name,
					u.`status`,case status when 0 then 'Inactive' when 1 then 'Active' end status_text
					from customer_group u
				)U
				WHERE CONCAT(id, group_name, status_text) LIKE '%$search_txt%'
				ORDER BY id ASC";	
		//echo $sql; die;		
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
		foreach ($result as $row) {
			$data['records'][] = $row;
		}			
		echo json_encode($data);		
	break;
	
	case "group_grid_data":
		$start = ($page_no*$limit)-$limit;
        $end   = $limit;
        $data = array();
        $employee_grid_permission   = $dbClass->getUserGroupPermission(15);
        $entry_permission   	   	= $dbClass->getUserGroupPermission(10);

        $condition = "";
        //# advance search for grid        
        $condition .=	" WHERE CONCAT(c.customer_id, c.full_name) LIKE '%$search_txt%' ";
        
        $countsql = "SELECT COUNT(c.customer_id) FROM customer_infos c $condition";
        $stmt = $conn->prepare($countsql);
        $stmt->execute();
        $total_records = $stmt->fetchColumn();
        $data['total_records'] = $total_records;
        $data['entry_status'] = $entry_permission;
        $total_pages = $total_records/$limit;
        $data['total_pages'] = ceil($total_pages);
        if($employee_grid_permission==1 || $permission_grid_permission==1){
            $sql = "SELECT c.customer_id, c.full_name, c.photo,
					(CASE c.status when 1 then 'Active' when 0 then 'Blocked' end) active_status,
					$employee_grid_permission as permission_status, $entry_permission as update_status,	$entry_permission as delete_status
					FROM customer_infos c
					LEFT JOIN customer_group_member gm on c.customer_id = gm.customer_id
					LEFT JOIN customer_group g ON g.id = gm.group_id
					$condition  
					GROUP BY c.customer_id 
					ORDER BY c.status DESC, customer_id ASC limit $start, $end";
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
	
	case "get_group_details":
		//var_dump($_POST);die;
		$sql = "select * from customer_group where id=$group_id";
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
	
	case "get_customer_details":
        $update_permission = $dbClass->getUserGroupPermission(10);
        if($update_permission==1){
            $details = $dbClass->getResultList("select c.customer_id, c.full_name
													from customer_infos c 
													where c.customer_id='$customer_id'");
            foreach ($details as $row){
                $data['records'][] = $row;
            }
            echo json_encode($data);
        }
    break;
	
	case "get_customer_group_details":
        $customer_groups = $dbClass->getResultList("select group_concat(cg.id,'*', cg.group_name,'*',cgm.`status`) module_group_ids
												from 
												customer_group_member cgm 
												left join customer_group cg on cg.id=cgm.group_id
												where cgm.customer_id=$customer_id and cg.status=1");

        foreach ($customer_groups as $row) {
            $module_group_ids_arr = explode(',',$row['module_group_ids']);
            $arr['module_group']=$module_group_ids_arr;
            $data['records'][] = $arr;
        }
        echo json_encode($data);
    break;
	
	case "customerInfo":
        $sql ="SELECT customer_id, CONCAT(full_name,' (',customer_id,')') full_name FROM customer_infos WHERE CONCAT(full_name) LIKE '%$term%' ORDER BY full_name";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $json = array();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();

        if($count>0){
            foreach ($result as $row) {
                $json[] = array('id' => $row["customer_id"],'label' => $row["full_name"]);
            }
        } else {
            $json[] = array('id' => "0",'label' => "Not Found !!!");
        }
        echo json_encode($json);
     break;

}
?>