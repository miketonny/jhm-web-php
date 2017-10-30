<?php session_start();
include("include/config.php");
include("include/functions.php");
if(!isset($_SESSION['user']) || $_SESSION['user'] == '' || empty($_SESSION['user'])){ redirect(siteUrl); }
$user_id = $_SESSION['user'];
?>
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
      
      <div id="leftbar">
		<ul>
        	<li>
            	<strong> Profile</strong> 
            	<a href="<?php echo siteUrl; ?>userdashboard/"> Dashboard </a>
                <a href="<?php echo siteUrl; ?>usereditprofile/"> Profile </a>
                <a href="<?php echo siteUrl; ?>userchangepassword/"> Change Password </a> 
            </li>
            
            <li> <strong> Order </strong>
            	<a href="<?php echo siteUrl; ?>cart/"> Cart </a> 
                <a href="<?php echo siteUrl; ?>mywish/"> Wishlist </a> 
                <a href="<?php echo siteUrl; ?>userorder/"> Order </a>
            </li>
            
            <li> <strong> PREFERENCES </strong>
            	<a href="#"> Recommendations For You </a>
            </li>
            
            <li> <strong> Payments </strong>
            	<a href="#"> My SD Cash </a>
                <a href="#"> My E-Gift Voucher Balance </a>
            </li>
            
        </ul>
      </div>
      
  		<div id="wishlist">
        <h3> My Wishlist (2 items)</h3>
        	<ul>
            	<li>
                	<img src="<?php echo siteUrl; ?>images/product.JPG" />
                    <span class="wishlist_info">
                    <p> <img src="<?php echo siteUrl; ?>images/favo.png" /> Added on 27-03-2015 </p>
                    <h2> AVR Fashions White Embroidered Semi Chiffon Saree </h2>
                    <strong>Extra 1% cashback for Card/Netbanking orders above 500</strong>
                    <h4> $24.00 </h4>
                    <input type="button" value="Buy Now" class="buynow_wishlist" />
                    </span>
                    
                    <ul class="wishlist_similar">
                    	<li> <a href="#"> Similar to this item </a></li>
                        <li> <a href="#"> Also viewed with this item </a> </li>
                        <li> <a href="#"> Items bought together </a></li>
                    </ul>
                    <div class="clr"> </div>
                </li>
                
            </ul>
        </div>
    
      <div class="clr"> </div>
    </div>
    <div class="clr"> </div>
</div>
<?php echo include("include/footer.php"); ?>
</body>
</html>
