<?php
session_start();
include '../dbConnect.php';
include("../dbClass.php");


$dbClass = new dbClass;
$conn       = $dbClass->getDbConn();

extract($_REQUEST);

switch($q){
    case "gallary_image":
        //echo 1;
        $sql ="SELECT gi.title, gi.attachment
         FROM image_album ia
         LEFT JOIN gallary_images gi ON gi.album_id=ia.id ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
        break;



}

?>