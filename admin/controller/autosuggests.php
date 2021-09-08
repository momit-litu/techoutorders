<?php
session_start();
include '../includes/static_text.php';
include("../dbConnect.php");
include("../dbClass.php");

$dbClass = new dbClass;
$conn       = $dbClass->getDbConn();
$loggedUser = $dbClass->getUserId();
$user_type = $_SESSION['user_type'];
$user_id	 = $_SESSION['user_id'];

extract($_REQUEST);
switch ($q){

    case "expense_parent_cat_info":
        $sql_query ="SELECT e1.id, 
					Concat(IFNULL(e2.expense_cat_name,''), Case when e2.expense_cat_name IS NOT NULL then ' => ' ELSE '' END,  e1.expense_cat_name) name
					from expense_categories e1 
					LEFT JOIN  expense_categories e2 ON e2.id= e1.parent_id
					WHERE e2.parent_id IS null AND CONCAT(IFNULL(e1.expense_cat_name,''), IFNULL(e2.expense_cat_name,'')) LIKE '%$term%'
					ORDER BY id asc";
        //echo $sql_query;die;
        $stmt = $conn->prepare($sql_query);
        $stmt->execute();
        $json = array();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();
        if($count>0){
            foreach ($result as $row) {
                $json[] = array('id' => $row["id"],'label' => $row["name"]);
            }
        } else {
            $json[] = array('id' => "0",'label' => "Not Found !!!");
        }
        echo json_encode($json);
        break;


    case "expense_cat_info":
        $sql_query ="SELECT id, case when parent_cat_name=''  then expense_cat_name ELSE CONCAT(parent_cat_name,' => ', expense_cat_name ) END name
					FROM (
						SELECT e1.id, e1.expense_cat_name, IFNULL(e1.parent_id,'') parent_id, e1.STATUS, 
						CONCAT(IFNULL(e3.expense_cat_name,''),Case when e3.expense_cat_name IS NOT NULL then ' => ' ELSE '' END,IFNULL(e2.expense_cat_name,'')) parent_cat_name
						FROM expense_categories e1 
						LEFT JOIN  expense_categories e2 ON e1.parent_id= e2.id
						LEFT JOIN  expense_categories e3 ON e2.parent_id= e3.id	
					)A
					WHERE CONCAT(IFNULL(parent_cat_name,''), expense_cat_name ) LIKE '%$term%'
					ORDER BY id asc";
        //echo $sql_query;die;
        $stmt = $conn->prepare($sql_query);
        $stmt->execute();
        $json = array();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();
        if($count>0){
            foreach ($result as $row) {
                $json[] = array('id' => $row["id"],'label' => $row["name"]);
            }
        } else {
            $json[] = array('id' => "0",'label' => "Not Found !!!");
        }
        echo json_encode($json);
        break;


    // return with row
    case "expense_heads":
        $sql_query ="SELECT eh.id, concat(ec.expense_cat_name,'=>',eh.expense_head_name) as head_name, 0 amount, '' exp_details
					FROM expense_heads eh 
					JOIN expense_categories ec ON eh.expense_category_id=ec.id
					WHERE eh.status = 1  AND
					concat(eh.expense_category_id,eh. ) LIKE '%$term%'
					ORDER BY id asc";
        //echo $sql_query;die;
        $stmt = $conn->prepare($sql_query);
        $stmt->execute();
        $json = array();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();
        if($count>0){
            foreach ($result as $row) {
                $json[] = array('id' => $row["id"],'label' => $row["head_name"], 'row'=>$row);
            }
        } else {
            $json[] = array('id' => "0",'label' => "Not Found !!!");
        }
        echo json_encode($json);
        break;

    case "unit_infos":
        $sql ="SELECT id, case when unit_name IS NOT NULL then concat(unit_name ,' (',short_name,')') ELSE short_name END unit_name FROM  units
						WHERE CONCAT( ifnull(unit_name,''), short_name ) LIKE '%$term%'
						ORDER BY id asc	";
        //echo $sql;die;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $json = array();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();


        if($count>0){
            foreach ($result as $row) {
                $json[] = array('id' => $row["id"],'label' => $row["unit_name"]);
            }
        } else {
            $json[] = array('id' => "0",'label' => "Not Found !!!");
        }
        echo json_encode($json);
        break;



    case "size_infos":

        $sql ="SELECT s.id , name as  size_name	FROM  size s WHERE CONCAT( ifnull(code,''), name ) LIKE '%$term%'  ORDER BY s.id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $json = array();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //echo $result; die;

        $count = $stmt->rowCount();


        if($count>0){
            foreach ($result as $row) {
                $json[] = array('id' => $row["id"],'label' => $row["size_name"]);
            }
        } else {
            $json[] = array('id' => "0",'label' => "Not Found !!!");
        }
        echo json_encode($json);
        break;



    case "customer_infos":
        $sql ="SELECT customer_id, full_name FROM customer_infos  WHERE CONCAT( customer_id, full_name, ifnull(username,''), ifnull(contact_no,''), ifnull(email,'')) LIKE '%$term%'  ORDER BY full_name";
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