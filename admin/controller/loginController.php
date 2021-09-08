<?php
session_start();
include '../includes/static_text.php';
include("../dbConnect.php");

$user_name	= htmlspecialchars($_POST['user_name'],ENT_QUOTES);
$pass	  	 = $_POST['password'];


$query="select user_id, user_password, user_name, full_name, designation_name, photo from appuser a left join user_infos e on e.emp_id=a.user_id WHERE a.user_name='".$user_name."' and a.is_active=1";
$stmt = $conn->prepare($query);
$stmt->execute();
$data = array();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $data['records'][] = $row;
}
//if username exists
if($stmt -> rowCount()>0){
    //compare the password
    if($row['user_password'] == md5($pass)){
       /* $update_activity_login_sql = "UPDATE web_login set is_login=:is_login where emp_id=:user_id";
        $stmt = $conn->prepare($update_activity_login_sql);
        $stmt->bindParam(':is_login', $is_login);
        $stmt->bindParam(':user_id', $row['user_id']);
        $is_login = 1;
        $stmt->execute();
        echo $stmt->execute(); die;*/
        $_SESSION['user_id']=$row['user_id'];
        $_SESSION['user_type']=1;// change
        // need to get these info dynamicly later
        $_SESSION['user_pic']	= $row['photo'];
        $_SESSION['user_name']	= $row['full_name'];
        $_SESSION['user_desg']	= $row['designation_name'];


        $sql = "select group_concat(group_id) my_groups from user_group_member where emp_id = '".$row['user_id']."' and status = 1";
        $stmt_group = $conn->prepare($sql);
        $stmt_group->execute();
        $result_group = $stmt_group->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_groups'] = $result_group['my_groups'];



        $settings_sql = "select * from general_settings";
        $stmt_settings = $conn->prepare($settings_sql);
        $stmt_settings->execute();
        $result_settings = $stmt_settings->fetch(PDO::FETCH_ASSOC);

        $_SESSION['company_name'] = $result_settings['company_name'];
        $_SESSION['website_title'] = $result_settings['website_title'];
        $_SESSION['website_url'] = $result_settings['website_url'];

        echo 1;
    }
    else
        echo 2;
}
else
    echo 3; //Invalid Login



?>