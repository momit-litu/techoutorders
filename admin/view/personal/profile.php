<?php
session_start();
include '../../includes/static_text.php';
include("../../dbConnect.php");
include("../../dbClass.php");
$dbClass = new dbClass;
?>


<script>
    var is_waiter = <?php if($dbClass->getUserGroupPermission(105) == 1 ){echo 1; }else echo 0;?>


    $(document).ready(function () {
    //alert(111)
        if(is_waiter==1) window.location.href=project_url+'index.php?module=dashboard&view=dashboard'


	var user_id 	= "<?php echo $_SESSION['user_id']; ?>";	
	var user_type  = "<?php echo $_SESSION['user_type']; ?>";


        $('.item').daterangepicker({
		singleDatePicker: true,
		calender_style: "picker_3",
		locale: {
			  format: 'YYYY-MM-DD',
			  separator: " - ",
		}
	});
});

</script>



<?php

if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] == "") header("Location:".$activity_url."../view/login.php");
else{
	$user_type_name = "HMS Employee";
	include("emp_profile.php");
} 
?>

<style type="text/css">
	@media print {    
		.no-print, .no-print * {
			display: none !important;
		}
	}
</style>

