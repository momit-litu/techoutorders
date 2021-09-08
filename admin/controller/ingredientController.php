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
		if(isset($ingredient_id) && $ingredient_id == ""){			
			if(isset($_FILES['ingredient_image_upload']) && $_FILES['ingredient_image_upload']['name']!= ""){
				$file_name = $_FILES['ingredient_image_upload']['name'];
				$file_size =$_FILES['ingredient_image_upload']['size'];
				$file_tmp =$_FILES['ingredient_image_upload']['tmp_name'];
				$file_type=$_FILES['ingredient_image_upload']['type'];	
				if(($file_type =='image/png' || $file_type =='image/png' ) && $file_size < $file_max_length){
					$desired_dir = "../images/ingredient";
					chmod( "../images/ingredient", 0777);
					if(file_exists("$desired_dir/".$file_name)==false){
						if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
							$photo = "$file_name";
					}
					else{//rename the file if another one exist
						$new_dir="$desired_dir/".time().$file_name;
						if(rename($file_tmp,$new_dir))
							$photo =time()."$file_name";				
					}
				}
				else {
					echo $img_error_ln;die;
				}			
			}
			else{
				$photo  = "images/no_image.png";	
			}
			
			$columns_value = array(
				'name'=>$ingredient_name,
				'code'=>$ingredient_code,
				'photo'=>$photo,
                'price'=>$ingredient_price
			);
			
			$return = $dbClass->insert("ingredient", $columns_value);
			
			if($return){ 
				echo "1";
			}else "0";
		}
		else if(isset($ingredient_id) && $ingredient_id>0){
            if(isset($_FILES['ingredient_image_upload']) && $_FILES['ingredient_image_upload']['name']!= ""){
				$file_name = $_FILES['ingredient_image_upload']['name'];
				$file_size =$_FILES['ingredient_image_upload']['size'];
				$file_tmp =$_FILES['ingredient_image_upload']['tmp_name'];
				$file_type=$_FILES['ingredient_image_upload']['type'];	
				if(($file_type =='image/png' || $file_type =='image/png' ) && $file_size < $file_max_length){
					$desired_dir = "../images/ingredient";
					chmod( "../images/ingredient", 0777);
					if(file_exists("$desired_dir/".$file_name)==false){
						if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
							$photo = "$file_name";
					}
					else{//rename the file if another one exist
						$new_dir="$desired_dir/".time().$file_name;
						if(rename($file_tmp,$new_dir))
							$photo =time()."$file_name";				
					}
				}
				else {
					echo $img_error_ln;die;
				}				
			}
			else{
				$photo  = "";	
			}
			
			$prev_attachment = $dbClass->getSingleRow("select photo from ingredient where id=$ingredient_id");
			
			if($photo != ""){	
				if($prev_attachment['photo'] != "" && $prev_attachment['photo'] != "images/no_image.png"){
					//unlink("../".$prev_attachment['photo']);
				}
				$columns_value = array(
					'photo' => $photo
				);	 
				$condition_array = array(
					'id'=>$ingredient_id
				);
				$return_photo = $dbClass->update("ingredient",$columns_value, $condition_array);				
			}
			
			$columns_value = array(
				'name'=>$ingredient_name,
				'code'=>$ingredient_code,
                'price'=>$ingredient_price
			);
			
			$condition_array = array(
				'id'=>$ingredient_id
			);	
			
			$return = $dbClass->update("ingredient", $columns_value, $condition_array);
							
			if($return) echo "2";
			else 	    echo "0";		 
		}
	break;
	
	case "grid_data":
		$start = ($page_no*$limit)-$limit;
		$end   = $limit;
		$data = array();
		
		$entry_permission   	    = $dbClass->getUserGroupPermission(54);
		$delete_permission          = $dbClass->getUserGroupPermission(55);
		$update_permission          = $dbClass->getUserGroupPermission(56);
		
		$ingredient_grid_permission   = $dbClass->getUserGroupPermission(57);

		$countsql = "SELECT count(id)
					FROM(
						SELECT c.id, c.code, c.name, c.price, c.photo
						FROM ingredient c
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
		if($ingredient_grid_permission==1){
			$sql = 	"SELECT id, name, code, price as i_price, photo,
					$update_permission as update_status, $delete_permission as delete_status
					FROM(
						SELECT c.id, ifnull(c.code,'') code, c.name, c.price, c.photo
						FROM ingredient c
						ORDER BY c.id
					)A
					WHERE CONCAT(id, code, name) LIKE '%$search_txt%';
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
	
	case "get_ingredient_details":
		$update_permission = $dbClass->getUserGroupPermission(56);
		if($update_permission==1){
			$sql = "SELECT c.id, ifnull(c.code,'') code, c.name, ifnull(photo,'') photo, c.price
					FROM ingredient c
					WHERE c.id=$ingredient_id";

			//echo $sql; die;
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);	
			foreach ($result as $row) {
				$data['records'][] = $row;
			}			
			echo json_encode($data);	
		}			
	break;
	
	case "delete_ingredient":		
		$delete_permission = $dbClass->getUserGroupPermission(55);
		if($delete_permission==1){
			$prev_attachment = $dbClass->getSingleRow("select photo from ingredient where id=$ingredient_id");
			if($prev_attachment['photo'] != "" || $prev_attachment['photo'] != "no_image.png"){
				unlink("../".$prev_attachment['photo']);
			}
			$condition_array = array(
				'id'=>$ingredient_id
			);
			$return = $dbClass->delete("ingredient", $condition_array);
		}
		if($return) echo "1";
		else 		echo "0";
	break;
	
}
?>