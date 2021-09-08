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
        if(isset($emp_id) && $emp_id == ""){
            $check_user_name_availability = $dbClass->getSingleRow("select count(user_name) as no_of_user from appuser where user_name='$user_name'");
            if($check_user_name_availability['no_of_user']!=0) { echo 5; die;}

            $last_user_id = $dbClass->getSingleRow("select max(user_id) user_id from appuser");
            if(empty($last_user_id)) $last_user_id['user_id'] = "1000000";
            $emp_id = $last_user_id['user_id'] + 1;

            $is_active_home_page=0;
            if(isset($_POST['is_active_home_page'])){
                $is_active_home_page=1;
            }

            if(isset($_FILES['emp_image_upload']) && $_FILES['emp_image_upload']['name']!= ""){
                $file_name = $_FILES['emp_image_upload']['name'];
                $file_size =$_FILES['emp_image_upload']['size'];
                $file_tmp =$_FILES['emp_image_upload']['tmp_name'];
                $file_type=$_FILES['emp_image_upload']['type'];
                if(($file_type =='image/png' || $file_type =='image/png' ) && $file_size < $file_max_length){
					$desired_dir = "../images/employee";
					chmod( "../images/employee", 0777);
                    if(file_exists("$desired_dir/".$file_name)==false){
                        if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
                            $photo = "$file_name";
                    }
                    else{//rename the file if another one exist
                        $new_dir="$desired_dir/".time().$file_name;
                        if(rename($file_tmp,$new_dir))
                            $photo =time()."$file_name";
                    }
                    $photo  = "images/employee/".$photo;
                }
                else {
                    echo $img_error_ln;die;
                }
            }
            else{
                $photo  = "";
            }

            $columns_value = array(
                'emp_id'=>$emp_id,
                'full_name'=>$emp_name,
                'designation_name'=>$desg_name,
                'email'=>$email,
                'address'=>$address,
                'nid_no'=>$nid_no,
                'contact_no'=>$contact_no,
                'is_active_home_page'=>$is_active_home_page,
                'photo'=>$photo,
                'remarks'=>$remarks
            );

            $return = $dbClass->insert("user_infos", $columns_value);
            if(is_numeric($return) && $return>0) {
                // insert data in appuser
                $is_active=0;
                /* if(isset($_POST['is_active'])){
                    $is_active=1;
                } */
                if(empty($_POST['password'])){
                    $password="123456";
                }
                $columns_value = array(
                    'user_id'=>$emp_id,
                    'user_name'=>$user_name,
                    'user_password'=>md5($password),
                    'is_active'=>$is_active,
                    'created_by'=>$loggedUser
                );
                $return_app = $dbClass->insert("appuser", $columns_value);
                if($return_app){
                    if(isset($_POST['group'])){
                        $group_result = $dbClass->getResultList("select id from user_group where status=0");
                        foreach($group_result as $row){
                            $columns_value = array(
                                'group_id'=>$row['id'],
                                'emp_id'=>$emp_id,
                                'status'=>0
                            );
                            $return_group = $dbClass->insert("user_group_member",$columns_value);
                        }
                        if($return_group){
                            foreach($group as $key=>$module_group_id){
                                $columns_value = array('status'=>1);
                                $condition_array = array(
                                    'group_id'=>$module_group_id,
                                    'emp_id'=>$emp_id,
                                );
                                $return_succes = $dbClass->update("user_group_member", $columns_value, $condition_array);
                                if(!$return_succes) break;
                            }
                        }
                    }
                    else{
                        $group_result = $dbClass->getResultList("select id from user_group where status=0");
                        foreach($group_result as $row){
                            $columns_value = array(
                                'group_id'=>$row['id'],
                                'emp_id'=>$emp_id,
                                'status'=>0
                            );
                            $return_succes = $dbClass->insert("user_group_member",$columns_value);
                            //echo $return_succes."--";
                        }
                    }
                }
            }
            if($return_succes) echo "1";
            else 	echo "0";
        }
        else{
            $check_user_name_availability = $dbClass->getSingleRow("select count(user_name) as no_of_user from appuser where user_name='$user_name' and user_id!=$emp_id");
            if($check_user_name_availability['no_of_user']!=0) { echo 5; die;}

            $is_active_home_page=0;
            if(isset($_POST['is_active_home_page'])){
                $is_active_home_page=1;
            }
            if(isset($_FILES['emp_image_upload']) && $_FILES['emp_image_upload']['name']!= ""){
                $file_name = $_FILES['emp_image_upload']['name'];
                $file_size =$_FILES['emp_image_upload']['size'];
                $file_tmp =$_FILES['emp_image_upload']['tmp_name'];
                $file_type=$_FILES['emp_image_upload']['type'];
                if(($file_type =='image/png' || $file_type =='image/png' ) && $file_size < $file_max_length){
					$desired_dir = "../images/employee";
					chmod( "../images/employee", 0777);
                    if(file_exists("$desired_dir/".$file_name)==false){
                        if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
                            $photo = "$file_name";
                    }
                    else{//rename the file if another one exist
                        $new_dir="$desired_dir/".time().$file_name;
                        if(rename($file_tmp,$new_dir))
                            $photo =time()."$file_name";
                    }
                    $photo  = "images/employee/".$photo;
                }
                else {
                    echo $img_error_ln;die;
                }
                $columns_value = array(
                    'full_name'=>$emp_name,
                    'designation_name'=>$desg_name,
                    'email'=>$email,
                    'contact_no'=>$contact_no,
                    'address'=>$address,
                    'nid_no'=>$nid_no,
                    'is_active_home_page'=>$is_active_home_page,
                    'photo'=>$photo,
                    'remarks'=>$remarks
                );
            }
            else{
                $columns_value = array(
                    'full_name'=>$emp_name,
                    'designation_name'=>$desg_name,
                    'email'=>$email,
                    'address'=>$address,
                    'nid_no'=>$nid_no,
                    'contact_no'=>$contact_no,
                    'is_active_home_page'=>$is_active_home_page,
                    'remarks'=>$remarks
                );
            }
            $condition_array = array(
                'emp_id'=>$emp_id
            );
            $return = $dbClass->update("user_infos", $columns_value, $condition_array);

            //Update data in appuser
            $is_active=0;
            if(isset($_POST['is_active'])){
                $is_active=1;
            }
            if(empty($_POST['password'])){
                $columns_value = array(
                    'is_active'=>$is_active,
                    'created_by'=>$loggedUser
                );
            }
            else{
                $columns_value = array(
                    'user_password'=>md5($password),
                    'is_active'=>$is_active,
                    'created_by'=>$loggedUser
                );
            }
            $condition_array = array(
                'user_id'=>$emp_id
            );
            $return_app = $dbClass->update("appuser", $columns_value, $condition_array);
            if($is_active == 1){
                //$dbClass->sendSMS($contact_no,"Your Shastho Shikkha Foundation Account has been activated.");
            }
            if($return_app){
                $columns_value = array('status'=>0);
                $condition_array = array('emp_id'=>$emp_id);
                $return_group = $dbClass->update("user_group_member",$columns_value, $condition_array);
                if($return_group){
                    foreach($group as $key=>$module_group_id){
                        $columns_value = array('status'=>1);
                        $condition_array = array(
                            'group_id'=>$module_group_id,
                            'emp_id'=>$emp_id,
                        );
                        //var_dump($condition_array);die;
                        $return_succes = $dbClass->update("user_group_member", $columns_value, $condition_array);
                        if(!$return_succes) break;
                    }
                }
            }
            if($return_succes) echo "2";
            else 	echo "0";
        }
        break;

    case "update_information":
        $password = $dbClass->getSingleRow("select user_password from appuser where user_id=$loggedUser");
        if(isset($new_password) && $new_password != ""){
            if($password['user_password'] == md5($old_password)){
                $columns_value = array(
                    'user_password'=>md5($new_password),
                    'user_name'=>$user_name
                );
                $condition_array = array(
                    'user_id'=>$loggedUser
                );
                $return_pass = $dbClass->update("appuser", $columns_value, $condition_array);
                if($return_pass){
                    $columns_value = array(
                        'full_name'=>$emp_name,
                        'designation_name'=>$desg_name,
                        'email'=>$email,
                        'contact_no'=>$contact_no,
                    );
                    $condition_array = array(
                        'emp_id'=>$loggedUser
                    );
                    $return = $dbClass->update("user_infos", $columns_value, $condition_array);
                    if($return==1) echo "1";
                }
            }
            else echo "0";
        }
        else{
            if($password['user_password'] == md5($old_password)){
                $columns_value = array(
                    'user_name'=>$user_name
                );
                $condition_array = array(
                    'user_id'=>$loggedUser
                );
                $return_pass = $dbClass->update("appuser", $columns_value, $condition_array);
                if($return_pass){
                    $columns_value = array(
                        'full_name'=>$emp_name,
                        'designation_name'=>$desg_name,
                        'email'=>$email,
                        'contact_no'=>$contact_no,
                    );
                    $condition_array = array(
                        'emp_id'=>$loggedUser
                    );
                    $return = $dbClass->update("user_infos", $columns_value, $condition_array);
                    if($return==1) echo "1";
                }
            }
            else echo "0";
        }
        break;

    case "grid_data":
        $start = ($page_no*$limit)-$limit;
        $end   = $limit;
        $data = array();
        $employee_grid_permission   = $dbClass->getUserGroupPermission(15);
        $entry_permission   	   	= $dbClass->getUserGroupPermission(10);
        if($emp_active_status == '') $emp_active_status = 1;

        $condition = "";
        //# advance search for grid
        if($search_txt == "Print" || $search_txt == "Advance_search"){
            // for status condition
            if($emp_active_status != 2) $condition  .=" WHERE a.is_active = $emp_active_status ";
        }
        // textfield search for grid
        else{
            $condition .=	" where CONCAT(e.emp_id, e.full_name, e.designation_name) LIKE '%$search_txt%' ";
        }
        $countsql = "SELECT count(e.emp_id) 
					FROM user_infos e
					join appuser a on a.user_id=e.emp_id 
					$condition";
        $stmt = $conn->prepare($countsql);
        $stmt->execute();
        $total_records = $stmt->fetchColumn();
        $data['total_records'] = $total_records;
        $data['entry_status'] = $entry_permission;
        $total_pages = $total_records/$limit;
        $data['total_pages'] = ceil($total_pages);
        if($employee_grid_permission==1 || $permission_grid_permission==1){
            $sql = "SELECT e.emp_id, e.full_name, e.designation_name, e.contact_no, e.photo, a.is_active, 
					 a.is_active, a.user_name,
					(case a.is_active when 1 then 'Active' when 0 then 'Blocked' end) active_status,
					$employee_grid_permission as permission_status, $entry_permission as update_status,	$entry_permission as delete_status 
					FROM user_infos e
					join appuser a on a.user_id=e.emp_id			
					$condition  
					order by is_active, emp_id DESC limit $start, $end";
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

    case "get_emp_details":
        $update_permission = $dbClass->getUserGroupPermission(10);
        if($update_permission==1){
            $emp_details = $dbClass->getResultList("select inf.emp_id, inf.full_name, inf.designation_name, 
													inf.photo, inf.contact_no, app.user_name, inf.nid_no,
													inf.email, inf.is_active_home_page, inf.remarks, app.is_active, photo
													from user_infos inf 
													join appuser app on inf.emp_id=app.user_id 
													where emp_id='$emp_id'");
            foreach ($emp_details as $row){
                $data['records'][] = $row;
            }
            echo json_encode($data);
        }
        break;

    case "get_user_groups":
        $user_groups = $dbClass->getResultList("select group_concat(ug.id,'*', ug.group_name) module_group_ids
												from 
												user_group ug where ug.status=0");
        foreach ($user_groups as $row) {
            $module_group_ids_arr = explode(',',$row['module_group_ids']);
            $arr['module_group']=$module_group_ids_arr;
            $data['records'][] = $arr;
        }
        echo json_encode($data);
        break;

    case "get_user_groups_emp":
        $data = array();
        // if an user is already in another group, then need to avoid the user
        // will be done by momit
        $user_group_emps = $dbClass->getResultList("select distinct(um.emp_id),CONCAT_WS(' >> ',e.emp_id,e.full_name,e.designation_name) empName from user_group_member um 
												left join user_infos e on e.emp_id=um.emp_id
												where um.group_id in ($group) and `status`= 1");
        foreach ($user_group_emps as $row){
            $data['records'][] = $row;
        }
        echo json_encode($data);

        break;

    case "get_emp_user_groups":
        $user_groups = $dbClass->getResultList("select group_concat(ug.id,'*', ug.group_name,'*',ugm.`status`) module_group_ids
												from 
												user_group_member ugm 
												left join user_group ug on ug.id=ugm.group_id
												where ugm.emp_id=$emp_id and ug.status=0");

        foreach ($user_groups as $row) {
            $module_group_ids_arr = explode(',',$row['module_group_ids']);
            $arr['module_group']=$module_group_ids_arr;
            $data['records'][] = $arr;
        }
        echo json_encode($data);
        break;

    case "get_post_user_groups":
        $user_groups = $dbClass->getResultList("select group_concat(ug.id,'*', ug.group_name,'*',ap.`status`) module_group_ids
												from 
												activity_public_post_group ap 
												left join user_group ug on ug.id=ap.group_id
												where ap.post_id=$post_id and ug.status=0");
        foreach ($user_groups as $row) {
            $module_group_ids_arr = explode(',',$row['module_group_ids']);
            $arr['module_group']=$module_group_ids_arr;
            $data['records'][] = $arr;
        }
        echo json_encode($data);
        break;

    case "get_permission_details":
        $permission_details = $dbClass->getResultList("select group_concat(aa.id,'*', aa.activity_name ,'*',ep.`status`) module_activity_ids_actions
									from 
									user_web_permission ep
									left join web_actions aa on aa.id=ep.activity_action_id
									where ep.emp_id= $emp_id and aa.status=0");
        foreach ($permission_details as $row) {
            $module_activity_ids_actions_arr = explode(',',$row['module_activity_ids_actions']);
            $arr['module_activity']=$module_activity_ids_actions_arr;
            $data['records'][] = $arr;
        }
        echo json_encode($data);
        break;

    case "delete_user":
        $delete_permission = $dbClass->getUserGroupPermission(10);
        if($delete_permission==1){
            $condition_array = array(
                'user_id'=>$emp_id
            );
            $columns_value = array(
                'is_active'=>0
            );
            $return = $dbClass->update("appuser", $columns_value, $condition_array);
        }
        if($return==1) echo "1";
        else 		   echo "0";
        break;

    case "get_user_info":
        $emp_info = "select inf.emp_id, inf.full_name, inf.designation_name, inf.photo, app.user_name, 
					inf.contact_no, inf.email, inf.is_active_home_page, app.is_active
					from user_infos inf 
					join appuser app on inf.emp_id=app.user_id where emp_id= $loggedUser";

        //echo $emp_info;die;
        $stmt = $conn->prepare($emp_info);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row){
            $data['records'] = $row;
        }
        echo json_encode($data);
        break;

    case "get_department_members":
        $loggedUser_dept_id_result = $dbClass->getSingleRow("select dept_id from user_infos e where e.emp_id=$loggedUser");
        $loggedUser_dept_id        = $loggedUser_dept_id_result['dept_id'];
        $department_members_list   = $dbClass->getResultList("select concat(e.full_name,'(',e.designation_name,')') full_name, e.photo 
									 from user_infos e where e.dept_id = '$loggedUser_dept_id' and e.emp_id != '$loggedUser'");
        foreach ($department_members_list as $row){
            $data['records'][] = $row;
        }
        echo json_encode($data);
        break;

    case "get_current_date":
        $current_date = date('d');
        if($current_date <= 25){
            $start_date  = mktime(0, 0, 0, date('n') - 1, 26);
            $start_date  = date('Y-m-d', $start_date);
            $end_date	= date('Y-m-d', strtotime(date('Y-m-d') .' -1 day'));
        }
        else{
            $start_date  = mktime(0, 0, 0, date('n'), 26);
            $start_date  = date('Y-m-d', $start_date);
            $end_date	= date('Y-m-d', strtotime(date('Y-m-d') .' -1 day'));
        }
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        echo json_encode($data);
        break;
}
?>