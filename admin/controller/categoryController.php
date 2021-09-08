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
		if(isset($category_id) && $category_id == ""){
			if(isset($_FILES['category_image_upload']) && $_FILES['category_image_upload']['name']!= ""){
				$file_name = $_FILES['category_image_upload']['name'];
				$file_size =$_FILES['category_image_upload']['size'];
				$file_tmp =$_FILES['category_image_upload']['tmp_name'];
				$file_type=$_FILES['category_image_upload']['type'];
				if(($file_type =='image/png' || $file_type =='image/jpeg' || $file_type =='image/jpg' ) && $file_size < $file_max_length){
					$desired_dir = "../images/category";
					chmod( "../images/category", 0775);
					if(file_exists("$desired_dir/".$file_name)==false){
						if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
							$photo = "$file_name";
					}
					else{//rename the file if another one exist
						$new_dir="$desired_dir/".time().$file_name;
						if(rename($file_tmp,$new_dir))
							$photo =time()."$file_name";				
					}
					$photo  = "images/category/".$photo;
				}
				else {
					echo "0";die;
				}			
			}
			else{
                $photo  = "images/no_image.png";
			}
            $active = 0;

            if(isset($is_active)){
                $active = 1;
            }
			$columns_value = array(
				'name'=>$category_name,
				'code'=>$category_code,
				'photo'=>$photo,
                'status'=>$active
			);
			
			$return = $dbClass->insert("category", $columns_value);
			
			if($return){ 
				echo "1";
			}else "0";
		}
		else if(isset($category_id) && $category_id>0){
            if(isset($_FILES['category_image_upload']) && $_FILES['category_image_upload']['name']!= ""){
                $file_name = $_FILES['category_image_upload']['name'];
                $file_size =$_FILES['category_image_upload']['size'];
                $file_tmp =$_FILES['category_image_upload']['tmp_name'];
                $file_type=$_FILES['category_image_upload']['type'];
                if(($file_type =='image/png' || $file_type =='image/jpg' || $file_type =='image/jpeg'  ) && $file_size < $file_max_length){
					$desired_dir = "../images/category";
					chmod( "../images/category", 0775);
                    if(file_exists("$desired_dir/".$file_name)==false){
                        if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
                            $photo = "$file_name";
                    }
                    else{//rename the file if another one exist
                        $new_dir="$desired_dir/".time().$file_name;
                        if(rename($file_tmp,$new_dir))
                            $photo =time()."$file_name";
                    }
                    $photo  = "images/category/".$photo;
                }
                else {
                    echo "0";die;
                }
            }
			else{
				$photo  = "";
			}
			
			$prev_attachment = $dbClass->getSingleRow("select photo from category where id = $category_id");

			//echo json_encode($prev_attachment);
			
			if($photo != ""){
			    //echo 11;
				if($prev_attachment['photo'] != "" && $prev_attachment['photo'] != "images/no_image.png"){
				    //echo 22;

                    try {
                        //echo 33;
                        unlink("../".$prev_attachment['photo']);
                    }catch (Exception $e){}
				}
				//echo $photo;
				$columns_value = array(
					'photo' => $photo
				);	 
				$condition_array = array(
					'id'=>$category_id
				);
				//echo 44;
				$return_photo = $dbClass->update("category",$columns_value, $condition_array);
				//echo $return_photo;
			}

            $active = 0;


            if(isset($is_active)){
                $active = 1;
            }

            $columns_value = array(
				'name'=>$category_name,
				'code'=>$category_code,
                'status'=>$active
			);
			
			$condition_array = array(
				'id'=>$category_id
			);	
			//echo $category_id; die;
			$return = $dbClass->update("category", $columns_value, $condition_array);

			//echo ($return);
							
			if($return) echo "2";
			else 	    echo "0";		 
		}
	break;
	
	case "grid_data":
		$start = ($page_no*$limit)-$limit;
		$end   = $limit;
		$data = array();
		
		$entry_permission   	    = $dbClass->getUserGroupPermission(50);
		$delete_permission          = $dbClass->getUserGroupPermission(51);
		$update_permission          = $dbClass->getUserGroupPermission(52);
		
		$category_grid_permission   = $dbClass->getUserGroupPermission(53);
		
		$countsql = "SELECT count(id)
					FROM(
						SELECT c.id, c.code, c.name, ifnull(c.photo,'') photo
						FROM category c
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
		if($category_grid_permission==1){
			$sql = 	"SELECT id, name, code, photo, status,
					$update_permission as update_status, $delete_permission as delete_status
					FROM(
						SELECT c.id, c.code, c.name, ifnull(c.photo,'') photo, 
						CASE status when 1 then 'Active' else 'Inactive' end status
						FROM category c
					)A
					WHERE CONCAT(id, name, code) LIKE '%$search_txt%'
					ORDER BY id desc
					LIMIT $start, $end";
				//	echo $sql;die;
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
			$sql = "SELECT c.id, c.code, c.name, ifnull(c.photo,'') photo, ec.id parent_id, ifnull(ec.name,'') parent_name, c.status
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
	
	case "delete_category":		
		$delete_permission = $dbClass->getUserGroupPermission(51);
		if($delete_permission==1){
			$prev_attachment = $dbClass->getSingleRow("select photo from category where id=$category_id");
			if($prev_attachment['photo'] != "" || $prev_attachment['photo'] != "no_image.png"){
				unlink("../".$prev_attachment['photo']);
			}
			$columns_value = array(
			    'status' => 0
            );
			$condition_array = array(
				'id'=>$category_id
			);
			$return = $dbClass->update("category", $columns_value, $condition_array);
		}
		if($return) echo "1";
		else 		echo "0";
	break;
	
	case "parent_head_info":
		$sql_query = "SELECT id, CONCAT_WS(' >> ',name,code) parentName
					FROM category
					WHERE CONCAT_WS('-> ',code,name) LIKE '%" . $term . "%' AND parent_id is NULL 
					ORDER BY name";
		//echo $sql_query;die;
		$stmt = $conn->prepare($sql_query);
		$stmt->execute();
		$json = array();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);			
		$count = $stmt->rowCount();
		if($count>0){
			foreach ($result as $row) {
				$json[] = array('id' => $row["id"],'label' => $row["parentName"]);
			}
		} else {
			$json[] = array('id' => "0",'label' => "No Parent Found !!!");
		}						
		echo json_encode($json);
	break;
	
}
?>