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
    case "insert_or_update_item":
        if(isset($_FILES['attached_file']) && $_FILES['attached_file']['name']!= ""){
            $file_name = $_FILES['attached_file']['name'];
            $file_size =$_FILES['attached_file']['size'];
            $file_tmp =$_FILES['attached_file']['tmp_name'];
            $file_type=$_FILES['attached_file']['type'];

            if(($file_type =='image/png' || $file_type =='image/jpg' || $file_type =='image/jpeg') && $file_size < $file_max_length){
				$desired_dir = "../images/category";
				chmod( "../images/category", 0777);
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
                echo $img_error_ln;die;
            }
        }
        else{
            $photo  = "images/no_image.png";
        }

        $is_combo_item=0;
        if(isset($_POST['is_combo'])){
            $is_combo_item=1;
        }
        $availability_item=0;
        if(isset($_POST['is_active'])){
            $availability_item=1;
        }

        $columns_value = array(
            'name'=>$item_name,
            'details'=>$details,
            'category_id'=>$category_option,
            'sell_from_stock'=>1,
            'price'=>$rate,
            'is_combo'=> $is_combo_item,
            'availability'=> $availability_item,
            'tags'=>''
        );

        if(isset($item_id) && $item_id!='' && $item_id!=0){
            if($photo!="images/no_image.png"){
                $columns_value['feature_image']=$photo;
            }
            $condition_array=array(
                'item_id'=>$item_id
            );
            $return_item = $dbClass->update("items", $columns_value,$condition_array);
        }
        else{
            $columns_value['feature_image']=$photo;
            $return_item = $dbClass->insert("items", $columns_value);
        }
        echo $return_item;
    break;

    case "insert_or_update_option":
        $is_required_int=0;
        if(isset($is_required)){
            $is_required_int=1;
        }

        $columns_value = array(
            'item_id'=>intval($item_id_option),
            'name'=>$option_name,
            'is_required'=>$is_required_int,
            'maximum_choice'=>$maximum,
            'minimum_choice'=>$minimum
        );

        if($option_id!=0){

            $condition_array = array(
                'id' => intval($option_id)
            );
            $dbClass->update("item_options", $columns_value,$condition_array);
            $return_option = intval($option_id);
            $condition_array = array(
                'option_id'=>$option_id
            );
            $return = $dbClass->delete("options_items", $condition_array);
        }
        else{
            $return_option = $dbClass->insert("item_options", $columns_value);
        }

        foreach ($ingredient_id as $i => $value){
            if($value!='' ){
                $I_id=intval($value);
                $sql = "select name, price, id FROM ingredient WHERE id = $I_id";
                $ingredient = $dbClass->getSingleRow($sql);

                $columns_value = array(
                    'option_id'=>$return_option,
                    'ingredient_id'=>$I_id,
                    'name'=>$ingredient['name'],
                    'price'=>floatval($ingredient['price'])
                );
                $dbClass->insert("options_items", $columns_value);
            }
        }

        echo 1;
        break;

    case "delete_options":
        $delete_permission = $dbClass->getUserGroupPermission(63);
        if($delete_permission==1){
            $condition_array = array(
                'id'=>$option_id
            );
            $dbClass->delete("item_options", $condition_array);
            $condition_array = array(
                'option_id'=>$option_id
            );
            $return = $dbClass->delete("options_items", $condition_array);

        }
        $sql ="SELECT id, name, is_required, minimum_choice, maximum_choice
                FROM item_options 
                WHERE item_id ='$item_id'";
        $options = $dbClass->getResultList($sql);
        foreach ($options as $row) {
            $data['option'][] = $row;
        }
        echo json_encode($data);
        break;


    case "grid_data":
        $start = ($page_no*$limit)-$limit;
        $end   = $limit;
        $data = array();

        $entry_permission   	    = $dbClass->getUserGroupPermission(62);
        $delete_permission          = $dbClass->getUserGroupPermission(63);
        $update_permission          = $dbClass->getUserGroupPermission(64);

        $category_grid_permission   = $dbClass->getUserGroupPermission(65);

        $condition = "";
        //# advance search for grid
        if($search_txt == "Print" || $search_txt == "Advance_search"){
            // for advance condition
            if($is_active_status !=2) $condition  .=" WHERE availability = $is_active_status ";
            if($ad_category_id != '') $condition  .=" and category_id = $ad_category_id ";
            if($ad_item_id != '')  $condition  .=" and item_id = $ad_item_id ";
            //echo '4';die;

        }
        // textfield search for grid
        else{
            $condition .=	" WHERE CONCAT(item_id, name, category_head_name) LIKE '%$search_txt%' ";
        }
        $countsql = "SELECT count(item_id)
					FROM(
					SELECT i.category_id, i.item_id, i.name, i.details, i.price as i_rate,i.availability,
						CASE WHEN c.parent_id IS NULL THEN c.name WHEN c.parent_id IS NOT NULL THEN CONCAT(ec.name,' >> ',c.name) END category_head_name,
						(CASE i.availability WHEN 1 THEN 'Available' WHEN 0 THEN 'Not-Available' END) active_status	
						FROM items i
						LEFT JOIN item_rate r on r.item_id = i.item_id
						LEFT JOIN category c on c.id = i.category_id
						LEFT JOIN category ec ON c.parent_id = ec.id
						LEFT JOIN size s on s.id = r.size_id
						group by i.item_id
						ORDER BY i.item_id DESC  
					)A
					$condition";
        //echo $countsql;die;
        $stmt = $conn->prepare($countsql);
        $stmt->execute();
        $total_records = $stmt->fetchColumn();
        $data['total_records'] = $total_records;
        $data['entry_status'] = $entry_permission;
        $total_pages = $total_records/$limit;
        $data['total_pages'] = ceil($total_pages);
        if($category_grid_permission==1){
            $sql = 	"SELECT category_id, item_id, name, category_head_name, i_rate, details,active_status,availability,
					$update_permission as update_status, $delete_permission as delete_status
					FROM(
						SELECT i.category_id, i.item_id, i.name, i.details, i.price as i_rate,i.availability,
						CASE WHEN c.parent_id IS NULL THEN c.name WHEN c.parent_id IS NOT NULL THEN CONCAT(ec.name,' >> ',c.name) END category_head_name,
						(CASE i.availability WHEN 1 THEN 'Available' WHEN 0 THEN 'Not-Available' END) active_status	
						FROM items i
						LEFT JOIN item_rate r on r.item_id = i.item_id
						LEFT JOIN category c on c.id = i.category_id
						LEFT JOIN category ec ON c.parent_id = ec.id
						LEFT JOIN size s on s.id = r.size_id
						group by i.item_id
						ORDER BY i.item_id DESC
					)A
					$condition
					ORDER BY item_id DESC
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

    case "get_item_details":
        $update_permission = $dbClass->getUserGroupPermission(64);
        if($update_permission==1){
            $sql = "SELECT p.item_id, p.name, p.details, p.category_id, p.is_combo, p.availability,  
                    p.feature_image,p.price, c.name as category_name
					FROM items p
					LEFT JOIN category c ON c.id = p.category_id
					WHERE p.item_id= $item_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //foreach ($result as $row) {
                $data['item'] = $result[0];
            //}
            $sql ="SELECT id, name, is_required, minimum_choice, maximum_choice
                FROM item_options 
                WHERE item_id ='$item_id'";

            $options = $dbClass->getResultList($sql);
            //echo $rate_details; die;
            foreach ($options as $row) {
                $data['option'][] = $row;
            }

            echo json_encode($data);
        }
        break;

    case "load_options_items":
        $update_permission = $dbClass->getUserGroupPermission(64);
        if($update_permission==1) {
            $sql = "SELECT id,item_id, name, minimum_choice, maximum_choice, is_required 
                FROM item_options
                WHERE id = $option_id";
            $data['records'] = $dbClass->getSingleRow($sql);


            $sql = "SELECT ingredient_id as id, name , price 
                FROM options_items
                WHERE option_id= $option_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $data['ingredients'][] = $row;
            }
            echo json_encode($data);
            die;
        }

        break;

    case "category_wise_item_code":
        $category_id 	= $_POST['category_id'];
        $category_code  = $_POST['category_code'];

        $last_item_code = $dbClass->getSingleRow("SELECT MAX(SUBSTR(p.code,LENGTH(c.code)+1)) item_code	
													FROM items p 
													LEFT JOIN category c on c.id = p.category_id
													WHERE c.id = '$category_id' AND LEFT(p.code,LENGTH(c.code)) = c.code");

        if($last_item_code['item_code'] == 'NULL' || $last_item_code['item_code'] == ''){
            $new_item_code = $category_code.'0001';
        }
        else{
            $new_item_code = $category_code.$last_item_code['item_code']+1;
        }
        echo $new_item_code;
        break;

    case "delete_attached_file":
        $attachment_name = $dbClass->getSingleRow("select item_image from item_image where id = $img_id");
        $condition_array = array(
            'id'=>$img_id
        );

        if($dbClass->delete("item_image", $condition_array)){
            unlink("../images/item/".$attachment_name['item_image']);
            unlink("../images/item/thumb/".$attachment_name['item_image']);
            echo 1;
        }
        else
            echo 0;
        break;

    case "delete_item":
        $delete_permission = $dbClass->getUserGroupPermission(63);
        //echo $delete_permission; die;
        if($delete_permission==1){
            $condition_array = array(
                'item_id'=>$item_id
            );
            $columns_value = array(
                'availability'=>0
            );
            $return = $dbClass->update("items", $columns_value, $condition_array);
        }
        if($return) echo "1";
        else 		echo "0";
        break;

    case "view_category":
        $data = array();
        $details = $dbClass->getResultList("SELECT id, CONCAT(code,' >> ',head_name)category_name 
											FROM (
												SELECT c.id, c.code, 
												CASE WHEN c.parent_id IS NULL THEN c.name WHEN c.parent_id IS NOT NULL THEN CONCAT(ec.name,' >> ',c.name) END head_name
												FROM category c
												LEFT JOIN category ec ON c.parent_id = ec.id
												ORDER BY c.id
											) A");
        foreach ($details as $row) {
            $data['records'][] = $row;
        }
        echo json_encode($data);
        break;

    case "get_ingredient":
        $con = "WHERE CONCAT(name, id) LIKE '%$term%'";

        $sql_query = "SELECT name, id, price FROM ingredient
					$con
					ORDER BY id";
        //echo $sql_query;die;
        $stmt = $conn->prepare($sql_query);
        $stmt->execute();
        $json = array();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();
        if($count>0){
            foreach ($result as $row) {
                $json[] = array('id' => $row["id"],'label' => $row["name"].'('.$row["price"].')', 'price' => $row["price"]);
            }
        } else {
            $json[] = array('id' => "0",'label' => "No Product Found !!!");
        }
        echo json_encode($json);
        break;

}

function resizeImage($filename, $newwidth, $newheight){
    list($width, $height) = getimagesize($filename);
    if($width > $height && $newheight < $height){
        $newheight = $height / ($width / $newwidth);
    } else if ($width < $height && $newwidth < $width) {
        $newwidth = $width / ($height / $newheight);
    } else {
        $newwidth = $width;
        $newheight = $height;
    }
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $source = imagecreatefromjpeg($filename);
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return imagejpeg($thumb);
}


?>