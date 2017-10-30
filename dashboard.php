<?php session_start();
include("include/config.php");
include("include/functions.php");
if(!isset($_SESSION['user']) || $_SESSION['user'] == '' || empty($_SESSION['user'])){ redirect(siteUrl); } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: Jhm ::</title>
<link href="<?php echo siteUrl; ?>style@jhmshop.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
</head>
<body>
<?php include("include/header.php"); ?>
<?php include("navigation.php"); ?>
<div id="mainWrapper">
	<div id="innerWrapper" class="pagename">
    	<h1 class="mainHeading">User Dashboard</h1>
        <ul>
        	<li><a href="<?php echo siteUrl; ?>cart/">My Cart</a></li>
            <li><a href="<?php echo siteUrl; ?>mywish/">My Wishlist</a></li>
            <li><a href="<?php echo siteUrl; ?>userprofile/">Edit Profile</a></li>
            <li><a href="<?php echo siteUrl; ?>userchangepassword/">Change Password</a></li>
        </ul>
    </div>
</div>
<?php echo include("include/footer.php"); ?>
</body>
</html>
