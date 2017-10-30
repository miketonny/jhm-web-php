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
    	<h1 class="mainHeading">User Dashboard </h1>
      
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
      
      
      <div id="profileinfo">
                        
	               	    <h2>Hi, <span><?php echo $_SESSION['user_name']; ?></span>	</h2>
                       
	</div>
    
    <div id="infobox">
                	<ul>
                    	<li class="products1">
                        	<h2>Cart</h2>
                            <ul>
                            <?php // cartttttttttttt
                            $cartQuery = "SELECT tbl_cart.*, tbl_product.product_name FROM tbl_cart
							LEFT JOIN tbl_product ON tbl_product.product_id = tbl_cart.product_id
							WHERE tbl_cart.user_id = '".$user_id."' ORDER BY tbl_cart.datetime DESC LIMIT 0,6";
							$cartRs = mysql_query($cartQuery);
                            if(mysql_num_rows($cartRs)){
								while($cartRow = mysql_fetch_object($cartRs)){ ?>
									<li>
										<h4><?php echo $cartRow->product_name.' ('.$cartRow->qty.' qty)'; ?></h4>
									</li>
								<?php } ?>
                                <li><a href="<?php echo siteUrl; ?>cart/">View Cart</a></li>
							<?php }else{ ?>
                            	<li> No Products </li>
                            <?php } ?>
                            </ul>
                        </li>
                        
                    	<li class="products1">
                        	<h2>Wishlist</h2>
                            <ul>
                            <?php // wishlisttttttttttt
                            $wlQuery = "SELECT tuw.*, tbl_product.product_name FROM tbl_user_wishlist tuw
							LEFT JOIN tbl_product ON tbl_product.product_id = tuw.product_id
							WHERE tuw.user_id = '".$user_id."' ORDER BY tuw.recid DESC LIMIT 0,6";
            				$wlRs = mysql_query($wlQuery, $con);
							if(mysql_num_rows($wlRs)){
								while($wlRow = mysql_fetch_object($wlRs)){ ?>
									<li>
										<h4><?php echo $wlRow->product_name; ?></h4>
									</li>
								<?php } ?>
                                <li><a href="<?php echo siteUrl; ?>mywish/">View Wishlist</a></li>
							<?php }else{ ?>
                            	<li> No Products </li>
                            <?php } ?>
                            </ul>
                        </li>
                        
                    	<li class="products1">
                        	<h2>Order</h2>
                            <ul id="wishlist">
								
                            </ul>
                        </li>
                        
                        <div id="clr">	</div>
                    </ul>
                </div>
      <div class="clr"> </div>
    </div>
    <div class="clr"> </div>
</div>
<?php echo include("include/footer.php"); ?>
</body>
</html>
