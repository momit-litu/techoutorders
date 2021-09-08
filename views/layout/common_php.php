<?php
session_start();
include("includes/dbConnect.php");
include("includes/dbClass.php");
$dbClass = new dbClass;

if(isset($_SESSION['customer_id']) && $_SESSION['customer_id']){
    $customer_id =$_SESSION['customer_id'];
    $is_logged_in_customer = 1;
}  // here will be the customer id that will come from session when the customer will login
else {
    $is_logged_in_customer = "";
    $customer_id = 0;

}
$conn       = $dbClass->getDbConn();


$sql ="SELECT gi.title, gi.attachment
         FROM image_album ia
         LEFT JOIN gallary_images gi ON gi.album_id=ia.id ";

$stmt = $conn->prepare($sql);
$stmt->execute();
$gallary = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql2 ="SELECT * FROM serving_days ";

$stmt = $conn->prepare($sql2);
$stmt->execute();
$calender = $stmt->fetchAll(PDO::FETCH_ASSOC);

//echo $gallary[0]['attachment']; die;

//echo $customer_id; die;

//$is_logged_in_customer = "";

//email, mobile, address, fearure, title, $subtitle, $facebook, $twitter, $instagram, $googleplus,

$email_info   = $dbClass->getDescription('web_admin_email');
$mobile_info  = $dbClass->getDescription('store_contact');
$about_us     = $dbClass->getDescriptionWithHtml(28);
$address      = $dbClass->getDescription('store_address');
$website_url  = $dbClass->getDescription('website_url');
//echo $website_url;die;
//$website_url  = 'http://64.187.224.149/';

$website_title=$dbClass->getDescription('website_title');
$store_address=$dbClass->getDescription('store_address');
$currency   = $dbClass->getDescription('currency_symbol');
$item_image_display=$dbClass->getDescription('item_image_display');
$ingredient_image_display=$dbClass->getDescription('ingredient_image_display');
$meta_keywards  = $dbClass->getDescription('meta_keywards');
$meta_description  = $dbClass->getDescription('meta_description');

$message_is_show  = $dbClass->getDescription('is_show');
$home_message_title = ($message_is_show)?$dbClass->getDescription('home_message_title'):"";
$home_message_details = ($message_is_show)?$dbClass->getDescription('home_message_details'):"";
//echo $website_url; die;

$logo         = $website_url."admin/".$dbClass->getDescription('company_logo');

$facebook  = $dbClass->getDescription('fb_url');
$twitter  = $dbClass->getDescription('tweeter_url');
$instagram  = $dbClass->getDescription('instagram_url');
$yelp  = $dbClass->getDescription('yelp_url');

$search_text = "";
if(isset($_GET['search'])) $search_text = "";

?>


