<?php setcookie('PHPSESSID', session_id(), 0, '/');
session_start();
include '../include/config.php';
include '../include/function.php';
if(isset($_SESSION['admin']) && !empty($_SESSION['admin']) && $_SESSION['admin'] != ''){ redirect('home.php'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php echo siteName; ?> - Admin Login</title>

	<meta name="description" content="">
	<meta name="author" content="Akshay Kumar">

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="assets/css/bootstrap/bootstrap.css" /> 

    <!-- Fonts  -->
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,500,600,700,300' rel='stylesheet' type='text/css'>
    
    <!-- Base Styling  -->
    <link rel="stylesheet" href="assets/css/app/app.v1.css" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>	
    
	
    <div class="container">
    	<div class="row">
			<!-- login start -->
			<div class="col-lg-4 col-lg-offset-4" id="login_b"><?php echo getMessage(); ?>
				<h3 class="text-center"><?php echo siteName; ?></h3>
				<p class="text-center">Sign in to manage Site !</p>
				<hr class="clean">
				<form role="form" method="post" action="login_model.php" id="loginForm" >
				  <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					<input type="text" name="email" class="form-control" required placeholder="Username / Email Adress">
				  </div>
				  <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-key"></i></span>
					<input type="password" class="form-control" name="password" required placeholder="Password">
				  </div>
				  <button type="submit" class="btn btn-purple btn-block">Sign in</button>
				</form>
				<hr>
				<button type="submit" class="btn btn-default btn-block" onClick="show_forget();">Forget Password!</button>
			</div>
			<!-- login end -->
			<!-- forget start -->
			<div class="col-lg-4 col-lg-offset-4" id="forget_b" style="display:none;"><?php echo getMessage(); ?>
				<h3 class="text-center"><?php echo siteName; ?></h3>
				<p class="text-center">Forget Password !</p>
				<hr class="clean">
				<form role="form" method="post" action="forget_password_model.php" >
				  <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					<input type="text" name="email" class="form-control" required placeholder="Username / Email Adress">
				  </div>
				  <button type="submit" class="btn btn-purple btn-block">Submit</button>
				</form>
				<hr>
				<button type="submit" class="btn btn-default btn-block" onClick="show_login();">Login</button>
			</div>
			<!-- forget end -->
        </div>
    </div>
    <script>
    function show_forget(){
		document.getElementById('forget_b').style.display = "block";
		document.getElementById('login_b').style.display='none';
	}
	function show_login(){
		document.getElementById('login_b').style.display = "block";
		document.getElementById('forget_b').style.display='none';
	}
    </script>
    <!-- JQuery v1.9.1 -->
	<script src="assets/js/jquery/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="assets/js/plugins/underscore/underscore-min.js"></script>
    <!-- Bootstrap -->
    <script src="assets/js/bootstrap/bootstrap.min.js"></script>
    
    <!-- Globalize -->
    <script src="assets/js/globalize/globalize.min.js"></script>
    
    <!-- NanoScroll -->
    <script src="assets/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
    
    <!-- Custom JQuery -->
	<script src="assets/js/app/custom.js" type="text/javascript"></script>
</body>
</html>
