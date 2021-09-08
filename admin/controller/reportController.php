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

    case "ad_product_info":
        $sql_query = "SELECT p.item_id, p.name FROM items p WHERE CONCAT(name) LIKE '%$term%' ORDER BY p.item_id";
        $stmt = $conn->prepare($sql_query);
        $stmt->execute();
        $json = array();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();
        if($count>0){
            foreach ($result as $row) {
                $json[] = array('id' => $row["item_id"],'label' => $row["name"]);
            }
        } else {
            $json[] = array('id' => "0",'label' => "No Item Found !!!");
        }
        echo json_encode($json);
        break;

    case "orderReport":
        //var_dump($_REQUEST);die;
        $condition = "";
        if($ad_is_order == 0)  															  $condition  .=" m.order_status != '$ad_is_order'";
        if($ad_is_order != 0 && $ad_is_order != 1)  									  $condition  .=" m.order_status = '$ad_is_order' ";
        if($ad_is_order == 1)  															  $condition  .=" m.order_status = '1 || 2 || 3' ";
        if(isset($ad_customer_id) && ($ad_customer_id != '' && $ad_customer_name != ''))  $condition  .=" and m.customer_id = '$ad_customer_id' ";
        if(isset($start_date) && $start_date != '')  							      	  $condition  .=" and ((date(m.order_date) between '$start_date' and '$end_date') AND (date(m.delivery_date) between '$start_date' and '$end_date'))";

        $data = array();

        $sql = "SELECT m.order_id, m.customer_id, ifnull(c.full_name,'') as customer_name, m.invoice_no,
				m.order_date, m.delivery_date,m.total_order_amt,
				m.delivery_type, order_noticed,
				m.address, m.remarks, m.order_status, m.payment_status, m.payment_reference_no,
				case m.payment_method when 1 then 'Cash' when 2 then 'Loyalty Point' when 3 then 'Paypal' else 'Square'  end payment_method,
				case m.order_status when 1 then 'Ordered' when 2 then 'Received' when 3 then 'Preparing' WHEN 4 then 'Ready' When 5 then 'Delivered' else 'Rejected' end order_status_text
				FROM order_master m
				LEFT JOIN customer_infos c ON c.customer_id = m.customer_id
				WHERE $condition
				GROUP BY m.order_id
				ORDER BY m.order_id DESC";
        //echo $sql;die;
        $details = $dbClass->getResultList($sql);

        foreach ($details as $row) {
            $data['records'][] = $row;
        }
        echo json_encode($data);
        break;

    case "customerReport":
        $con = "";
        $condition = "";
        if($is_active_status != 2)  $condition = ' WHERE c.status = "'.$is_active_status.'" ';
        if(isset($report_type)){
            $con = "limit 10";
        }
        $data = array();
        $sql="SELECT c.customer_id, c.full_name, c.age, c.email, c.contact_no, COUNT(m.order_id) no_of_order,c.loyalty_points,
			CASE c.status WHEN 1 THEN 'Active' WHEN 0 THEN 'In-active' END status_text, ifnull(cc.cupon_no,'') coupon_no
			FROM customer_infos c 
			LEFT JOIN order_master m on m.customer_id = c.customer_id
			LEFT JOIN cupons cc on cc.customer_id = c.customer_id
			$condition
			GROUP BY c.customer_id
			ORDER BY no_of_order DESC $con";
        //echo $sql;die;
        $details = $dbClass->getResultList($sql);
        foreach ($details as $row) {
            $data['records'][] = $row;
        }
        echo json_encode($data);
        break;

    case "order_no_info":
        $sql_query = "SELECT order_id, invoice_no FROM order_master m WHERE invoice_no LIKE '%$term%'";
        $stmt = $conn->prepare($sql_query);
        $stmt->execute();
        $json = array();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();
        if($count>0){
            foreach ($result as $row) {
                $json[] = array('id' => $row["order_id"],'label' => $row["invoice_no"]);
            }
        } else {
            $json[] = array('id' => "0",'label' => "No Order No Found !!!");
        }
        echo json_encode($json);
        break;

    case "category_name_autocomplete":
        $sql_query = "SELECT name, code, id FROM category m WHERE name LIKE '%$term%'";
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
            $json[] = array('id' => "0",'label' => "No Order No Found !!!");
        }
        echo json_encode($json);
        break;

    case "orderDetails":
        $group_order_id = $dbClass->getSingleRow("SELECT group_order_id FROM order_master WHERE order_id=$order_id");

        if($group_order_id['group_order_id']==0){
            $sql = "SELECT m.order_id, m.customer_id, ifnull(c.full_name,'Instance Order') customer_name, d.item_id, ifnull(c.contact_no,'') customer_contact_no, ifnull(c.address,'') customer_address, m.order_id,
                GROUP_CONCAT(ifnull(ca.name,' '),' >> ',ifnull(ca.id,' '),'#',ifnull(ca.id, ' '),'#',ifnull(p.name, d.item_name),' (',ifnull(ca.name,' '),' )','#',ifnull(p.item_id,' '),'#',d.item_rate,'#',d.quantity,'#',d.ingredient_name,'..') order_info,
                m.order_date, m.delivery_date, m.delivery_type, m.discount_amount, m.total_paid_amount,
                m.address, m.delivery_charge_id, m.tax_amount,m.tips,
                m.remarks, m.order_status, m.payment_status, m.payment_method, 
                m.payment_reference_no, m.invoice_no, m.total_order_amt,
                case payment_status when 1 then 'Not Paid' when 3 then 'Refunded' else 'Paid' end paid_status, 
                case payment_method when 1 then 'Cash' when 3 then 'Paypal' when 2 then 'Loyalty'  else 'Square'  end payment_method
                FROM order_master m
                LEFT JOIN order_details d ON d.order_id = m.order_id
                LEFT JOIN customer_infos c ON c.customer_id = m.customer_id
                LEFT JOIN items p ON p.item_id = d.item_id
                LEFT JOIN category ca ON ca.id = p.category_id
                WHERE m.order_id= '$order_id'
                GROUP BY d.order_id
                ORDER BY m.order_id";
            //echo $sql;die;
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $data['records'][] = $row;
            }
            $data['type']='individual';

            echo json_encode($data);
        }
        else{
            $sql = " SELECT coalesce(oms.order_id, 'NAN') as order_id, god.id, coalesce(oms.order_info, '') as order_info,
               coalesce(oms.order_date, '') as order_date, coalesce(oms.total_order_amt, '0') as total_order_amt, coalesce(oms.order_status, '0') as order_status, gm.name, gm.email, god.id as group_order_details_id, god.order_key
               FROM group_order go
               LEFT JOIN group_order_details god ON god.group_order_id= go.order_id
               LEFT JOIN group_members gm ON gm.id=god.group_member_id
               LEFT JOIN(
               SELECT om.order_id, om.group_order_details_id,
                GROUP_CONCAT(ca.name,' >> ',ca.id,'#',ca.id,'#',p.name,' (',ca.name,' )','#',p.item_id,'#',d.item_rate,'#',d.quantity,'#',d.ingredient_name,'..') order_info,
                om.order_date, om.total_order_amt, om.order_status 
                FROM order_master om
                LEFT JOIN order_details d ON d.order_id = om.order_id
                LEFT JOIN items p ON p.item_id = d.item_id
                LEFT JOIN category ca ON ca.id = p.category_id
                GROUP BY d.order_id
                )oms ON oms.group_order_details_id= god.id
                WHERE go.order_id =".$group_order_id['group_order_id'];
            //echo $sql;die;
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


            foreach ($result as $row) {
                $data['records'][] = $row;
            }

            $sql = "SELECT ci.full_name,ci.customer_id, ci.address as c_address,go.discount_amount, ci.contact_no as mobile, gi.name,
                    go.order_id as group_order_id,go.tips, go.order_date, go.delivery_date, go.total_order_amt,go.tax_amount, 
                    go.order_status as status, go.invoice_no,go.total_paid_amount,
                    case go.payment_method when 1 then 'Cash' when 2 then 'Paypal' when 3 then 'Loyalty'  else 'Square'  end payment_method,
                    case go.payment_status when 1 then 'Not Paid' when 3 then 'Refunded' else 'Paid' end paid_status 
                    from group_order go
                    LEFT JOIN groups_info gi ON gi.id = go.group_id
                    LEFT JOIN(
                    SELECT full_name, address, contact_no,customer_id from customer_infos 
                    )ci ON ci.customer_id=go.customer_id              
                     WHERE go.order_id=".$group_order_id['group_order_id'];
            //echo $sql;die;
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $tax = $dbClass->getSingleRow("Select tax_type, tax_amount, tax_enable from general_settings where id=1");

            $data['order_details']=$result[0];
            $data['type']='group';
            echo json_encode($data);
        }
        break;

    case "categoryReport":
        $con = "";
        $condition = "";
        if($is_active_status != 2)  $condition = ' WHERE c.status = "'.$is_active_status.'" ';
        if(isset($report_type)){
            $con = "limit 10";
        }
        $data = array();
        $sql="SELECT c.name, i.item, c.id, c.photo from category c
            LEFT JOIN( SELECT count(item_id) as item, category_id from items group by category_id) i on i.category_id = c.id
			$condition
			ORDER BY item DESC $con";
        //echo $sql;die;
        $details = $dbClass->getResultList($sql);
        foreach ($details as $row) {
            $data['records'][] = $row;
        }
        echo json_encode($data);
        break;

    case "itemReport":
        $con = "";
        $condition = "";
        if($is_active_status != 2)  $condition = ' WHERE i.availability = "'.$is_active_status.'" ';
        if(isset($report_type)){
            $con = "limit 10";
        }
        if($category_id !=0){
            if($condition==""){
                $condition = ' WHERE i.category_id = "'.$category_id.'" ';
            }
            else{
                $condition .= 'and i.category_id = "'.$category_id.'" ';
            }
        }
        $data = array();
        $sql="SELECT i.name as item_name, i.price, i.item_id, i.details, c.name as category_name,
        CASE i.is_combo when 1 then 'Yes' else 'No' end is_combo,
        CASE i.hot_item when 1 then 'Yes' else 'No' end hot_item
         from items i
        LEFT JOIN category c on c.id=i.category_id
        $condition
			ORDER BY item_id DESC $con";
        //echo $sql;die;
        $details = $dbClass->getResultList($sql);
        foreach ($details as $row) {
            $data['records'][] = $row;
        }
        echo json_encode($data);
        break;


        case "ingredientReport":
            $con = "";
            if(isset($report_type)){
                $con = "limit 10";
            }
            $data = array();
            $sql="SELECT *
                from ingredient
               ORDER BY id ASC $con";
            //echo $sql;die;
            $details = $dbClass->getResultList($sql);
            foreach ($details as $row) {
                $data['records'][] = $row;
            }
            echo json_encode($data);
            break;

}
?>