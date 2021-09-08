<?php
class orderConfirm {
    public $dbClass;

    function __construct($dbClass){
        $this->dbClass = $dbClass;
    }

    function LoyaltyUpdate ($customer_id, $loyalty_point){
        try {
            $customer_loyalty_point=  $this->dbClass->getSingleRow("SELECT loyalty_points from customer_infos where customer_id=".$customer_id);
            //echo $customer_loyalty_point; die;
            $new_loyalty_point = intval($customer_loyalty_point['loyalty_points'])+intval($loyalty_point);
			$new_loyalty_point = ($new_loyalty_point<=0)?0:$new_loyalty_point;
            $value_ar= array(
                'loyalty_points'=>$new_loyalty_point
            );
            $condition_ar= array(
                'customer_id'=>$customer_id
            );
            $customer_loyalty_update=  $this->dbClass->update("customer_infos",$value_ar,$condition_ar);
            return 1;
        }catch (Exception $e){
            return 0;
        }
    }

    function orderConfirmationEmail($order_id){
        $web_url 		= $this->dbClass->getSingleRow("SELECT website_url FROM general_settings WHERE id=1");
        $customer_id 	= $this->dbClass->getSingleRow("SELECT customer_id, invoice_no FROM order_master WHERE invoice_no='$order_id'");
        $customer 		= $this->dbClass->getSingleRow("SELECT * FROM customer_infos WHERE customer_id=".$customer_id['customer_id']);


        $customer_email = $customer['email'];
        if($customer_email){
            $sql = "SELECT m.order_id, m.customer_id,
                    c.full_name customer_name, c.contact_no customer_contact_no, c.address customer_address, 
                    GROUP_CONCAT(ca.name,' >> ',ca.id,'#',ca.id,'#',p.name,' (',ca.name,' )','#',p.item_id,'#',d.item_rate,'#',d.quantity,'#',d.ingredient_name,'..') order_info,
					m.ASAP, DATE_FORMAT(m.order_date, '%Y-%m-%d %h:%i %p')  order_date, DATE_FORMAT(m.delivery_date, '%Y-%m-%d %h:%i %p')  delivery_date, m.delivery_type,m.delivery_charge, m.discount_amount, m.total_paid_amount,
                    m.total_order_amt, m.tax_amount,m.tips,m.remarks,m.payment_reference_no, m.invoice_no, m.total_order_amt,
                    case m.order_status when 1 then 'Ordered' when 2 then 'Rejected' when 3 then 'Received' when 4 then 'Ready'  else 'Gift delivered' end order_status,
                    case payment_status when 1 then 'Not Paid' when 3 then 'Refunded' else 'Paid' end paid_status, 
                    case payment_method when 1 then 'Cash' when 2 then 'loyalty point' when 3 then 'card'  else 'Mpesa'  end payment_method,
					payment_method as pay_methode
                    FROM order_master m
                    LEFT JOIN order_details d ON d.order_id = m.order_id
                    LEFT JOIN customer_infos c ON c.customer_id = m.customer_id
                    LEFT JOIN items p ON p.item_id = d.item_id
                    LEFT JOIN category ca ON ca.id = p.category_id
                    WHERE m.invoice_no= '$order_id'
                    GROUP BY m.order_id
                    ORDER BY m.order_id";
            //echo $sql; die;
           // $stmt = $conn->prepare($sql);
            //$stmt->execute();
            //$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result = $this->dbClass->getResultList($sql);
            foreach ($result as $row) {
                extract($row);
            }
            //echo 55; die;

            //return 1;
			$type= "";
            $to 	 = $customer_email;
            $from 	 = $this->dbClass->getDescription('web_admin_email');
			if($paid_status!='Paid' && ($pay_methode==3 || $pay_methode==4)){
				$subject = "#". $invoice_no." Order Confirmation from Techoutorders";
				$paid_css = "style='background-color: red; padding:5px 10px'";
				$type= "admin";
			}
			else if($pay_methode==1){
				$subject = "#". $invoice_no." Order Confirmation from Techoutorders";
				$paid_css = "";
			}
			else{
				$subject = "#". $invoice_no." Order Paid Confirmation from Techoutorders";
				$paid_css = "style='background-color: green; padding:5px 10px'";
			}
				
            
			$body 	 = '';
			$delivery_date = ($ASAP)?"<span style='background-color:lime'>Pickup: ASAP</span>":"<span style='background-color:orange'>Scheduled Pickup Time:". $delivery_date."</span>";
            $body .= "<link rel='stylesheet' type='text/css' href='".$web_url["website_url"]."plugin/bootstrap/bootstrap.css'>
                        <div id='order-div'>
                            <div class='title text-center' style='text-align: center'>
                                <img src='".$web_url["website_url"]."admin/images/banner/lyns.png' alt='' style='alignment: center'>
                                <h4 class='text-coffee'  style='alignment: center'>Order No # <span id='ord_title_vw'>$invoice_no</span></h4>
                            </div>
                            <div class='done_registration '>							    
                                <div class='doc_content'>
                                    <div width = '100%' >
                                    <table width='100%'><tbody><tr><td>
                                        <h4>Order Details:</h4>				
                                        <div style=' text-align: left'>
                                            <span id='ord_date'>Ordered Time: $order_date</span><br/> 
                                            <span id='dlv_date'>$delivery_date</span> <br/> 
                                            <span id='dlv_date'>Payment Status : <span  $paid_css>$paid_status</span></span> <br/> 
                                            <span id='dlv_date'>Payment Method : $payment_method</span>
                                        </div>
                                        </td><td style='text-align: right'>
                                        <div style=' text-align: right'>
                                            <h4>Customer Details:</h4> 								
                                            <address id='customer_detail_vw'>".$customer['full_name']."
                                            <br/><b>Mobile:</b>".$customer['contact_no']."
                                            <br/><b>Address:</b>".$customer['address']."
                                            </address>
                                        </div>
                                        </td></tr></tbody></table>
                                    </div>
                                    <div id='ord_detail_vw col-md-12'> 
                                        <table width='100%' style='background-color: grey'>
                                            <thead>
                                                <tr>
                                                    <th style='text-align: center; background-color: white; padding:10px'>Product</th>
                                                    <th width='10%' style='text-align: center; background-color: white; padding:10px'>Quantity</th>
                                                    <th width='18%' style='text-align: right; background-color: white; padding:10px'>Rate</th>                           
                                                    <th width='18%'  style='text-align: right; background-color: white; padding:10px'>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>";
            $order_info_arr = explode('..,', $order_info);
            $order_total = 0;
            foreach($order_info_arr as $key=>$item_details){
                $item_details_arr = explode('#', $item_details);
                $total = ($item_details_arr[5]*$item_details_arr[4]);
                $body .= "<tr><td style=' background-color: white; padding:10px'>".$item_details_arr[2]."<br>".$item_details_arr[6]."</td><td style='text-align: center; background-color: white; padding:10px'>".$item_details_arr[5]."</td><td style='text-align: right; background-color: white; padding:10px'>".$item_details_arr[4]."</td><td style='text-align: right; background-color: white; padding:10px'>".number_format($total,2)."</td></tr>";
                $order_total += $total;
            }


            $total_order_bill = $order_total-$discount_amount;
            $total_paid 	  = $total_paid_amount;
            $body .= '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px" ><p><b>Total </b></p></td><td style="text-align: right; background-color: white; padding:10px "><p><b>'.number_format($order_total ,2).'</b></p></td></tr>';
            $body .= '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px"><p><b>Discount </b></p></td><td style="text-align: right; background-color: white; padding:10px"><p><b>'.number_format($discount_amount,2).'</b></p></td></tr>';
            $body .= '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px" ><p><b>Tax </b></p></td><td style="text-align: right; background-color: white; padding:10px"><p><b>'.number_format($tax_amount,2).'</b></p></td></tr>';
            $body .= '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px" ><p><b>Tips</b></p></td><td style="text-align: right; background-color: white; padding:10px"><p><b>'.number_format($tips,2).'</b></p></td></tr>';
            $body .= '<tr><td colspan="3" style="text-align: right; background-color: white; padding:10px" ><p><b>Total Paid</b></p></td><td style="text-align: right; background-color: white; padding:10px"><p><b>'.number_format($total_paid_amount,2).'</b></p></td></tr>';
            $body .= "										
                                            </tbody>
                                        </table>
                                        <p>Note: <span id='note_vw'>$remarks</span></p>
                                        <p>Print Time :". date('Y-m-d h:m:s')."</p>
                                        <br />
                                        <p style='font-weight:bold; text-align:center'>Thank you. Hope we will see you soon </p>
                                    </div> 
                                </div>									
                            </div>							
                        </div>
                    ";
            //echo $body;die;
            try {
                $this->dbClass->orderMail ($to, $subject, $body, $type);
            }catch (Exception $e){
                return 0;
            }

            return 1;
            //mail($to, $subject, $body, $headers);
        }else{
            return 2;
        }
    }

    function orderNotification($orderId, $details, $U_type, $N_type, $N_to){
        $customer = $this->dbClass->getSingleRow("SELECT order_id FROM order_master WHERE invoice_no='$orderId'");

        try {
            $column_array = array(
                'order_id'=>$customer['order_id'],
                'details'=>$details,
                'notification_user_type'=>$U_type,
                'notification_type'=>$N_type,
                'notified_to'=>$N_to
            );
            $this->dbClass->insert("notification", $column_array);
        }catch (Exception $e){
            return 0;
        }
        return 1;


    }

    function afterPayment($order_id){
        $customer = $this->dbClass->getSingleRow("SELECT customer_id,loyalty_point FROM order_master WHERE invoice_no='$order_id'");
        $loyalty_point = $customer['loyalty_point'];
        $customer_id = $customer['customer_id'];
        $customer = $this->dbClass->getSingleRow("SELECT full_name FROM customer_infos WHERE customer_id=$customer_id");

        $details_to_admin = "New Order" . $order_id ." placed by ". ucfirst($customer['full_name']) . " .";
        $this->orderNotification($order_id, $details_to_admin, 1, 0, null);
        $details_to_user = "You placed an order. The Invoice number is: " . $order_id ." .";
        $this->orderNotification($order_id, $details_to_user, 0, 0, $customer_id);
        $this->LoyaltyUpdate($customer_id , $loyalty_point);
        $this->orderConfirmationEmail($order_id);
    }

    function paymentAdd($txnid, $payment_amount, $payment_status, $method, $itemid){
        $item_number = trim(preg_replace('/\s+/', '', $itemid));
        //$item_number = 'LB072004722';
        $createdtime = date('Y-m-d H:i:s');
        try {
            $column_value = array(
                'payment_status'=>2,
                'payment_time'=>$createdtime,
                'payment_method'=> $method==0 ? 3 : 4,
            );
            $condition_array = array(
                'invoice_no' =>$item_number
            );

            if (strpos($item_number, 'LBG') !== false){
                $this->dbClass->update("group_order", $column_value , $condition_array);
            }
            $this->dbClass->update("order_master", $column_value , $condition_array);
        }catch (Exception $e){}

        try {
            $column_array = array(
                'txnid'=>$txnid,
                'payment_amount'=>$payment_amount,
                'payment_status'=>$payment_status,
                'method'=>$method,
                'itemid'=>$itemid,
                'createdtime'=>$createdtime
            );
            //var_dump($column_array);
            $this->dbClass->insert("payments", $column_array);
        }catch (Exception $e){
            return 0;
        }
        return 1;
    }
}

?>