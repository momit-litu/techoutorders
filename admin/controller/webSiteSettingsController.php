<?php
session_start();
include '../includes/static_text.php';
include("../dbConnect.php");
include("../dbClass.php");

$dbClass = new dbClass;
$conn       = $dbClass->getDbConn();
$loggedUser = $dbClass->getUserId();

extract($_REQUEST);
switch($q){
    case "insert_or_update_page":
        if(isset($master_id) && $master_id == ""){
            if(isset($parent_menu_select) && $parent_menu_select != ""){
                $columns_value = array(
                    'menu'=>$menu,
                    'title'=>$title,
                    'description'=>$details,
                    'parent_menu_id'=>$parent_menu_select
                );
            }else{
                $columns_value = array(
                    'menu'=>$menu,
                    'title'=>$title,
                    'description'=>$details
                );
            }
            $return_menu = $dbClass->insert("web_menu", $columns_value);
            if($return_menu) echo "1";
            else			 echo "0";
        }
        else if(isset($master_id) && $master_id>0){
            if(isset($parent_menu_select) && $parent_menu_select != ""){
                $columns_value = array(
                    'menu'=>$menu,
                    'title'=>$title,
                    'description'=>$details,
                    'parent_menu_id'=>$parent_menu_select
                );
            }else{
                $columns_value = array(
                    'menu'=>$menu,
                    'title'=>$title,
                    'description'=>$details
                );
            }
            $condition_array = array(
                'id'=>$master_id
            );
            $return_menu = $dbClass->update("web_menu", $columns_value, $condition_array);
            if($return_menu) echo "1";
            else			 echo "0";
        }
        break;

    case "get_menus_page":
        $users = $dbClass->getResultList("select CONCAT(m.id,'*', m.menu) module_menu_ids from web_menu m");
        foreach ($users as $row) {
            $module_menu_ids_arr = explode(',',$row['module_menu_ids']);
            $arr['module_menu']=$module_menu_ids_arr;
            $data['records'][] = $arr;
        }
        echo json_encode($data);
        break;

    case "grid_data_page":
        $start = ($page_no*$limit)-$limit;
        $end   = $limit;
        $data = array();

        $countsql = "select count(m.id) from web_menu m WHERE CONCAT(title,menu) LIKE '%$search_txt%'";
        $stmt = $conn->prepare($countsql);
        $stmt->execute();
        $total_records = $stmt->fetchColumn();
        $data['total_records'] = $total_records;
        $total_pages = $total_records/$limit;
        $data['total_pages'] = ceil($total_pages);

        $sql = "SELECT m.id, m.parent_menu_id, m.title, m.menu, m.description, ifnull(wm.menu,'') parent_menu
				FROM web_menu m
				LEFT JOIN web_menu wm ON wm.id = m.parent_menu_id WHERE CONCAT(m.title, m.menu) LIKE '%$search_txt%'
				ORDER BY m.id desc limit $start, $end";
        //echo $sql;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $data['records'][] = $row;
        }
        echo json_encode($data);
        break;

    case "get_page_details":
        $sql = "select m.id, m.parent_menu_id, m.title, m.menu, 
				m.description, ifnull(web.menu,'') parent_menu 
				from web_menu m 
				left join web_menu web on web.id = m.parent_menu_id
				where m.id = '$menu_id'";
        //echo $sql;die;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $data['records'][] = $row;
        }
        echo json_encode($data);
        break;

    case "delete_menu_page":
        //echo 1; die;
        $condition_array = array(
            'id'=>$menu_id
        );
        $return = $dbClass->delete("web_menu", $condition_array);
        if($return) echo "1";
        else 		echo "0";
        break;

    case "insert_or_update_notice":
        //var_dump($_POST);die;
        if(isset($master_id)  && $master_id == ""){
            $attachments = "";
            if(isset($_FILES['attached_file']) && $_FILES['attached_file']['name'][0] != ""){
                $desired_dir = "../document/notice_attachment";
                chmod( "../document/notice_attachment", 0777);
                foreach($_FILES['attached_file']['tmp_name'] as $key => $tmp_name ){
                    $file_name = $_FILES['attached_file']['name'][$key];
                    $file_size =$_FILES['attached_file']['size'][$key];
                    $file_tmp =$_FILES['attached_file']['tmp_name'][$key];
                    $file_type=$_FILES['attached_file']['type'][$key];
                    if($file_size < $file_max_length){
                        if(file_exists("$desired_dir/".$file_name)==false){
                            if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
                                $attachments .= "$file_name ,";
                        }
                        else{//rename the file if another one exist
                            $new_dir="$desired_dir/".time().$file_name;
                            if(rename($file_tmp,$new_dir))
                                $attachments .=time()."$file_name ,";
                        }
                    }
                    else {
                        echo $img_error_ln;die;
                    }
                }
                $attachments  = rtrim($attachments,",");
            }
            $banner_img = "";
            if(isset($_FILES['attached_document']) && $_FILES['attached_document']['name']!= ""){
                $desired_dir = "../document/banner_attachment";
                chmod( "../document/banner_attachment", 0777);
                $file_name = $_FILES['attached_document']['name'];
                $file_size = $_FILES['attached_document']['size'];
                $file_tmp  = $_FILES['attached_document']['tmp_name'];
                $file_type = $_FILES['attached_document']['type'];
                if($file_size < $file_max_length){
                    if(file_exists("$desired_dir/".$file_name)==false){
                        if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
                            $banner_img .= "$file_name";
                    }
                    else{//rename the file if another one exist
                        $new_dir="$desired_dir/".time().$file_name;
                        if(rename($file_tmp,$new_dir))
                            $banner_img .=time()."$file_name";
                    }
                }
                else {
                    echo $img_error_ln;die;
                }
            }
            if(trim($expire_date) == "") $expire_date="0000-00-00";
            $columns_value = array(
                'title'=>$title,
                'details'=>$details,
                'attachment'=>$attachments,
                'banner_img'=>$banner_img,
                'expire_date'=>$expire_date,
                'posted_by'=>$loggedUser,
                'type'=>$notice_type
            );
            $return = $dbClass->insert("web_notice", $columns_value);
            if($return) echo "1";
            else 		echo "0";
        }
        else if(isset($master_id) && $master_id>0){
            $attachments= "";
            if(isset($_FILES['attached_file']) && $_FILES['attached_file']['name'][0] != ""){
                $attachments = "";
                $desired_dir = "../document/notice_attachment";
                chmod( "../document/notice_attachment", 0777);
                foreach($_FILES['attached_file']['tmp_name'] as $key => $tmp_name ){
                    $file_name = $_FILES['attached_file']['name'][$key];
                    $file_size =$_FILES['attached_file']['size'][$key];
                    $file_tmp =$_FILES['attached_file']['tmp_name'][$key];
                    $file_type=$_FILES['attached_file']['type'][$key];
                    if($file_size < $file_max_length){
                        if(file_exists("$desired_dir/".$file_name)==false){
                            if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
                                $attachments .= "$file_name,";
                        }
                        else{//rename the file if another one exist
                            $new_dir="$desired_dir/".time().$file_name;
                            if(rename($file_tmp,$new_dir))
                                $attachments .=time()."$file_name ,";
                        }
                    }
                    else {
                        echo "img_error";die;
                    }
                }
                $attachments  = rtrim($attachments,",");
            }
            $banner_img = "";
            if(isset($_FILES['attached_document']) && $_FILES['attached_document']['name']!= ""){
                $prev_banner = $dbClass->getSingleRow("select banner_img from web_notice where id = $master_id");
                if($prev_banner != ""){
                    unlink("../document/banner_attachment/".$prev_banner['banner_img']);
                }
                $desired_dir = "../document/banner_attachment";
                chmod( "../document/banner_attachment", 0777);
                $file_name = $_FILES['attached_document']['name'];
                $file_size = $_FILES['attached_document']['size'];
                $file_tmp  = $_FILES['attached_document']['tmp_name'];
                $file_type = $_FILES['attached_document']['type'];
                if($file_size < $file_max_length){
                    if(file_exists("$desired_dir/".$file_name)==false){
                        if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
                            $banner_img .= "$file_name";
                    }
                }
                else {
                    echo $img_error_ln;die;
                }
            }
            $prev_attachment = $dbClass->getSingleRow("select attachment from web_notice where id = $master_id");
            if($prev_attachment['attachment'] != "")  $attachments = $attachments.",".$prev_attachment['attachment'];
            $attachments  = ltrim($attachments,",");
            $columns_value = array(
                'title'=>$title,
                'details'=>$details,
                'attachment'=>$attachments,
                'banner_img'=>$banner_img,
                'expire_date'=>$expire_date,
                'posted_by'=>$loggedUser,
                'type'=>$notice_type
            );
            $condition_array = array(
                'id'=>$master_id
            );
            $return = $dbClass->update("web_notice",$columns_value, $condition_array);
            if($return) echo "2";
            else 		echo "0";
        }
        break;

    case "grid_data_notice":
        $start = ($page_no*$limit)-$limit;
        $end   = $limit;
        $data = array();

        $countsql = "select count(id) from web_notice WHERE CONCAT(title,details) LIKE '%$search_txt%'";
        //echo $countsql;die;
        $stmt = $conn->prepare($countsql);
        $stmt->execute();
        $total_records = $stmt->fetchColumn();
        $data['total_records'] = $total_records;
        $total_pages = $total_records/$limit;
        $data['total_pages'] = ceil($total_pages);
        $sql = "SELECT id, title, details, banner_img, attachment, expire_date, banner_img,`type` type, post_date,
				case type when 1 then 'News' when 2 then 'Event' end notice_type, status as status_id,
				case status when 1 then 'Pending' when 2 then 'Approved' when 3 then 'Deleted' end notice_status
				from web_notice WHERE CONCAT(title,details) LIKE '%$search_txt%' order by id desc limit $start, $end";
        //echo $sql;die;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $data['records'][] = $row;
        }
        echo json_encode($data);
        break;

    case "get_notice_details_notice":
        $notice_details = $dbClass->getResultList("select * from web_notice where id=$notice_id");
        foreach ($notice_details as $row) {
            $data['records'][] = $row;
        }
        echo json_encode($data);
        break;

    case "delete_attached_file_notice":
        $prev_attachment = $dbClass->getSingleRow("select attachment from web_notice where id=$master_id");
        $prev_attachment_array = explode(",",$prev_attachment['attachment']);
        if(($key = array_search($file_name, $prev_attachment_array)) !== false) {
            unset($prev_attachment_array[$key]);
        }
        $attachment = implode(",",$prev_attachment_array);
        $columns_value = array(
            'attachment'=>$attachment
        );
        $condition_array = array(
            'id'=>$master_id
        );
        if($dbClass->update("web_notice",$columns_value, $condition_array)){
            unlink("../document/notice_attachment/".$file_name);
            echo 1;
        }
        else
            echo 0;
        break;

    case "delete_notice":
        $attachment = $dbClass->getSingleRow("select attachment from web_notice where id = $notice_id");
        $attachment_array = explode(",",$attachment['attachment']);
        for($i=0;$i<count($attachment_array);$i++){
            unlink("../document/notice_attachment/".$attachment_array[$i]);
        }
        $condition_array = array(
            'id'=>$notice_id
        );
        $return = $dbClass->delete("web_notice", $condition_array);

        if($return) echo "1";
        else 		echo "0";

        break;

    case "insert_or_update_image":
        //var_dump($_POST);die;
        if(isset($album_search)  && $album_search == 0){
            $columns_value = array(
                'album_name'=>$album_name
            );
            $return = $dbClass->insert("image_album", $columns_value);
            $last_album_id = $dbClass->getSingleRow("select id from image_album where album_name = '$album_name'");

            $attachments = "";
            if(isset($_FILES['attached_file']) && $_FILES['attached_file']['name'][0] != ""){
                $desired_dir = "../document/gallary_attachment";
                $desired_dir_thumb = "../document/gallary_attachment/thumb";
                //chmod( "../document/gallary_attachment", 0775);
                //chmod( "../document/gallary_attachment/thumb", 0775);

                foreach($_FILES['attached_file']['tmp_name'] as $key => $tmp_name ){
                    $file_name = $_FILES['attached_file']['name'][$key];
                    $file_size =$_FILES['attached_file']['size'][$key];
                    $file_tmp =$_FILES['attached_file']['tmp_name'][$key];
                    $file_type=$_FILES['attached_file']['type'][$key];
                    if($file_size < $file_max_length){
                        if(file_exists("$desired_dir/".$file_name)==false){
                            //$dbClass->store_uploaded_image($file_tmp, 200, 200, $desired_dir_thumb, "$desired_dir/thumb/".$file_name);
                            if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
                                $attachments = "$file_name";
                        }
                        else{//rename the file if another one exist
                            $new_dir="$desired_dir/".time().$file_name;
                            //$dbClass->store_uploaded_image($file_tmp, 200, 200, $desired_dir_thumb, "$desired_dir/thumb/".time().$file_name);
                            if(rename($file_tmp,$new_dir))
                                $attachments =time()."$file_name";
                        }
                        chmod( "../document/gallary_attachment/".$attachments, 0775);
                        $columns_value = array(
                            'title'=>$title,
                            'album_id'=>$last_album_id['id'],
                            'attachment'=>$attachments
                        );
                        $return_details = $dbClass->insert("gallary_images",$columns_value);
                    }
                    else{
                        echo $img_error_ln;die;
                    }
                }
            }
            else{
                $columns_value = array(
                    'title'=>$title,
                    'album_id'=>$last_album_id['id']
                );
                $return_details = $dbClass->insert("gallary_images",$columns_value);
            }
            if($return_details) echo "1";
            else 				echo "0";
        }
        else if(isset($album_search) && $album_search>0){
            //var_dump($_REQUEST);die;
            $attachments = "";
            if(isset($_FILES['attached_file']) && $_FILES['attached_file']['name'][0] != ""){
                $desired_dir = "../document/gallary_attachment";
                $desired_dir_thumb = "../document/gallary_attachment/thumb";
               // chmod( "../document/gallary_attachment", 0775);
                //chmod( "../document/gallary_attachment/thumb", 0775);

                foreach($_FILES['attached_file']['tmp_name'] as $key => $tmp_name ){
                    $file_name = $_FILES['attached_file']['name'][$key];
                    $file_size =$_FILES['attached_file']['size'][$key];
                    $file_tmp =$_FILES['attached_file']['tmp_name'][$key];
                    $file_type=$_FILES['attached_file']['type'][$key];
                    if($file_size < $file_max_length){
                        //here is an error ----------------------chaki
                        if(file_exists("$desired_dir/".$file_name)==false){
                            //$dbClass->store_uploaded_image($file_tmp, 200, 200, $desired_dir_thumb, "$desired_dir/thumb/".$file_name);
                            if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
                                $attachments = "$file_name";
                        }
                        else{//rename the file if another one exist
                            $new_dir="$desired_dir/".time().$file_name;
                            //$dbClass->store_uploaded_image($file_tmp, 200, 200, $desired_dir_thumb, "$desired_dir/thumb/".time().$file_name);
                            if(rename($file_tmp,$new_dir))
                                $attachments = time()."$file_name";
                        }
                        chmod("../document/gallary_attachment/".$attachments, 0775);
                        $columns_value = array(
                            'title'=>$title,
                            'album_id'=>$album_search,
                            'attachment'=>$attachments
                        );
                        $return_details = $dbClass->insert("gallary_images",$columns_value);
                        if($return_details) echo "2";
                    }
                    else{
                        echo $img_error_ln; die;
                    }
                }
            }
            else echo "1";
        }
        break;

    case "album_name":
        $sql_query = "SELECT id,album_name FROM image_album ORDER BY album_name";
        $stmt = $conn->prepare($sql_query);
        $stmt->execute();
        $json = array();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();
        if($count>0){
            foreach ($result as $row) {
                $json[] = array('id' => $row["id"],'label' => $row["album_name"]);
            }
        } else {
            $json[] = array('id' => '0','label' => 'No Name Found');
        }
        echo json_encode($result);
        break;

    case "get_album_details":
        $data = array();
        $album_details = $dbClass->getResultList("select alb.id album_id, alb.album_name, img.title
												from image_album alb
												left join gallary_images img on alb.id = img.album_id
												where alb.id = '$master_id' group by alb.id");
        foreach ($album_details as $row){
            $data['records'][] = $row;
        }
        $attachment_details = $dbClass->getResultList("select img.id img_id, img.attachment  
													from image_album mas 
													join gallary_images img on img.album_id = mas.id 
													where mas.id = '$master_id'");
        foreach ($attachment_details as $row){
            $data['attachment'][] = $row;
        }
        echo json_encode($data);

        break;

    case "delete_attached_image_file":
        $attachment_name = $dbClass->getSingleRow("select attachment from gallary_images where id = $img_id");
        $condition_array = array(
            'id'=>$img_id
        );
        if($dbClass->delete("gallary_images", $condition_array)){
            chmod( "../document/gallary_attachment/".$attachment_name['attachment'], 0775);
            chmod( "../document/gallary_attachment/thumb/".$attachment_name['attachment'], 0775);
            unlink("../document/gallary_attachment/".$attachment_name['attachment']);
            unlink("../document/gallary_attachment/thumb/".$attachment_name['attachment']);
            echo 1;
        }
        else
            echo 0;
        break;

    case "insert_or_update_banner":

        if(isset($bannerImg_id) && $bannerImg_id == ""){
            if(isset($_FILES['bannerImg_image_upload']) && $_FILES['bannerImg_image_upload']['name']!= ""){
                $desired_dir = "../images/banner";
                $desired_dir_thumb = "../images/banner/thumb";
                //chmod( "../images/banner", 0777);
                //chmod( "../images/item/thumb", 0777);

                $file_name = $_FILES['bannerImg_image_upload']['name'];
                $file_size =$_FILES['bannerImg_image_upload']['size'];
                $file_tmp =$_FILES['bannerImg_image_upload']['tmp_name'];
                $file_type=$_FILES['bannerImg_image_upload']['type'];
                if($file_size < $file_max_length){
                    try {
                        if(file_exists("$desired_dir/".$file_name)==false){

                            //$dbClass->store_uploaded_image($file_tmp, 200, 150, $desired_dir_thumb, "$desired_dir/thumb/".$file_name);

                            if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name)){

                                $photo = "$file_name";
                            }
                            else
                            {echo "Not uploaded because of error #".$_FILES["bannerImg_image_upload"]["error"]; die;}
                        }
                        else{//rename the file if another one exist

                            $new_dir="$desired_dir/".time().$file_name;
                            //$dbClass->store_uploaded_image($file_tmp, 200, 150, $desired_dir_thumb, "$desired_dir/thumb/".time().$file_name);

                            if(rename($file_tmp,$new_dir)){
                                $photo =time()."$file_name";
                            }
                        }
                    }catch (Exception $e){
                        return $e;
                    }

                    $photo  = "/images/banner/".$photo;
                }
                else{
                    echo $img_error_ln;die;
                }
            }
            else{

                $photo  = "";
            }
            $columns_value = array(
                'title'=>$title,
                'text'=>$text,
                'photo'=>$photo
            );
            echo 4;

            $return = $dbClass->insert("banner_image", $columns_value);

            if($return) echo "1";
            else 	echo "0";
        }
        else{
            if(isset($_FILES['bannerImg_image_upload']) && $_FILES['bannerImg_image_upload']['name']!= ""){
                $desired_dir = "../images/banner";
                $desired_dir_thumb = "../images/banner/thumb";
                //chmod( "../images/banner", 0777);
                //chmod( "../images/item/thumb", 0777);

                $file_name = $_FILES['bannerImg_image_upload']['name'];
                $file_size =$_FILES['bannerImg_image_upload']['size'];
                $file_tmp =$_FILES['bannerImg_image_upload']['tmp_name'];
                $file_type=$_FILES['bannerImg_image_upload']['type'];
                if($file_size < $file_max_length){
                    if(file_exists("$desired_dir/".$file_name)==false){
                        $dbClass->store_uploaded_image($file_tmp, 200, 150, $desired_dir_thumb, "$desired_dir/thumb/".$file_name);
                        if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name)){
                            $photo = "$file_name";
                        }
                    }
                    else{//rename the file if another one exist
                        $new_dir="$desired_dir/".time().$file_name;
                        $dbClass->store_uploaded_image($file_tmp, 200, 150, $desired_dir_thumb, "$desired_dir/thumb/".time().$file_name);
                        if(rename($file_tmp,$new_dir)){
                            $photo =time()."$file_name";
                        }
                    }
                    $photo  = "/images/banner/".$photo;
                }
                else {
                    echo $img_error_ln;die;
                }
                $columns_value = array(
                    'text'=>$text,
                    'title'=>$title,
                    'photo'=>$photo
                );
            }
            else{
                $columns_value = array(
                    'text'=>$text,
                    'title'=>$title,
                );
            }
            $condition_array = array(
                'id'=>$bannerImg_id
            );
            $return = $dbClass->update("banner_image", $columns_value, $condition_array);

            if($return) echo "2";
            else 	echo "0";
        }
        break;


    case "grid_data_banner":
        $start = ($page_no*$limit)-$limit;
        $end   = $limit;
        $data = array();
        $employee_grid_permission   = $dbClass->getUserGroupPermission(15);
        $entry_permission   	   	= $dbClass->getUserGroupPermission(10);

        $countsql = "SELECT count(id) FROM banner_image";

        $stmt = $conn->prepare($countsql);
        $stmt->execute();
        $total_records = $stmt->fetchColumn();
        $data['total_records'] = $total_records;
        $data['entry_status'] = $entry_permission;
        $total_pages = $total_records/$limit;
        $data['total_pages'] = ceil($total_pages);

        if($employee_grid_permission==1 || $permission_grid_permission==1){
            $sql = "SELECT id, title, text, photo,	
					$employee_grid_permission as permission_status, $entry_permission as update_status,	
					$entry_permission as delete_status 
					FROM banner_image order by id desc limit $start, $end";
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

    case "get_bannerImg_details":
        $update_permission = $dbClass->getUserGroupPermission(10);
        if($update_permission==1){
            $details = $dbClass->getResultList("SELECT id, title, text, photo	
												from banner_image
												where id='$bannerImg_id'");
            foreach ($details as $row){
                $data['records'][] = $row;
            }
            echo json_encode($data);
        }
        break;

    case "delete_bannerImg":
        $attachment_name = $dbClass->getSingleRow("select photo from banner_image where id = $bannerImg_id");
        $condition_array = array(
            'id'=>$bannerImg_id
        );
        if($dbClass->delete("banner_image", $condition_array)){
            unlink("../images/banner/".$attachment_name['attachment']);
            unlink("../images/banner/thumb".$attachment_name['attachment']);
            echo 1;
        }
        else
            echo 0;
        break;
}

?>