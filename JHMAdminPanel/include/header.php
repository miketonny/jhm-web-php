<?php
//setcookie('PHPSESSID', session_id(), 0, '/');
session_start();
include '../include/config.php';
include '../include/function.php';
if(!isset($_SESSION['admin']) || $_SESSION['admin'] == '' || empty($_SESSION['admin'])){ redirect('index.php'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo siteName; ?> : Admin Dashboard</title>
	<link rel="stylesheet" href="assets/css/bootstrap/bootstrap.css" /> 
	<!-- Typeahead Styling  -->
    <link rel="stylesheet" href="assets/css/plugins/typeahead/typeahead.css" />
    <!-- TagsInput Styling  -->
    <link rel="stylesheet" href="assets/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" />
    <!-- Chosen Select  -->
    <link rel="stylesheet" href="assets/css/plugins/bootstrap-chosen/chosen.css" />
    <!-- DateTime Picker  -->
    <link rel="stylesheet" href="assets/css/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.css" />
	<link rel="stylesheet" href="assets/css/plugins/bootstrap-validator/bootstrap-validator.css" />
    <!-- Switch Buttons  -->
    <link rel="stylesheet" href="assets/css/switch-buttons/switch-buttons.css" />
    <!-- Fonts  -->
    <link href='//fonts.googleapis.com/css?family=Raleway:400,500,600,700,300' rel='stylesheet' type='text/css'>
    <!-- Base Styling  -->
    <link rel="stylesheet" href="assets/css/app/app.v1.css" />
	<link rel="stylesheet" href="assets/css/custom.css" />
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	<!-- Preloader -->
    <div class="loading-container">
      <div class="loading">
        <div class="l1">
          <div></div>
        </div>
        <div class="l2">
          <div></div>
        </div>
        <div class="l3">
          <div></div>
        </div>
        <div class="l4">
          <div></div>
        </div>
      </div>
    </div>
    <!-- Preloader -->
    <?php include 'sidebar.php'; ?>
	    <section class="content">
    	<?php include 'topbar.php'; ?>