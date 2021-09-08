<?php
session_start();
include 'includes/static_text.php';
include("dbConnect.php");
if(isset($_SESSION['user_id']) && $_SESSION['user_id'])header("Location:".$activity_url."index.php");


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $website_title; ?></title>
        <!-- Bootstrap core CSS -->
        <link href="theme/css/bootstrap.min.css" rel="stylesheet">
        <link href="theme/fonts/css/font-awesome.min.css" rel="stylesheet">
        <link href="theme/css/animate.min.css" rel="stylesheet">
    
        <!-- Custom styling plus plugins -->
        <link href="theme/css/custom.css" rel="stylesheet">
        <link href="theme/css/icheck/flat/blue.css" rel="stylesheet">
		<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
		<link rel="icon" href="images/favicon.png" type="image/x-icon">
        <script src="theme/js/jquery.min.js"></script>
        <script src="js/static_text.js"></script>
        <script src="js/login.js"></script>
    </head>
    
    <body style="background:#FFF;">        
        <div class=""> 
            <div id="wrapper">
                <div id="login" class="animate form" align="center" >
                    <h1 align="center"><a href="#" ><?php echo $company_name; ?></a></h1>
                    <section class="login_content">
                       <form name="loginform" id="loginform" action="" method="post">
                            <h1><?php echo $login; ?></h1>
                            
                            <div id="login_error"></div>
                            
                            <div>
                                <input type="text" class="form-control" placeholder="Username"  required name="user_login" id="user_login" />
                            </div>
                            <div>
                                <input type="password" class="form-control" placeholder="Password" required name="user_pass" id="user_pass"  />
                            </div>

                            <br>
                            <div>
                                <input name="submit" id="submit" class="btn btn-primary active submit" value="<?php echo $login; ?>" type="submit">
                                <a class="reset_pass" href="#"><?php echo $forget_pass; ?></a>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                        <!-- form -->
                    </section>
                    <div>
                        <br /> <br />
                        <p> <?php echo $copyright." ". $company_name; ?></p>
                    </div>
                    <!-- content -->
                </div>
            </div>
        </div>
    </body>
</html>

	<script>
	$(document).ready(function () {
		$('#company_id').trigger('change');
	});
	</script>