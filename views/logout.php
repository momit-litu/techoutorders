
<?php
session_start();
$_SESSION = array();
if(isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time()-42000, '/');
}
session_destroy();
//header("Location: /index.php");
?>
<script src="../js/static_text.js"></script>
<script>
	//alert(project_url)
	//window.location.replace(project_url+'index.php');
    localStorage.setItem("nexturl",'')
    localStorage.setItem("currenturl",'')
    window.location.replace(project_url+"/index.php");
</script>



