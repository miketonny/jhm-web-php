<?php session_start();
include("include/config.php");
include("include/functions.php");
if(isset($_SESSION['user'], $_SESSION['user_email'], $_SESSION['user_name'])){ redirect(siteUrl.'cart/'); } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: <?php echo siteName; ?> ::</title>
<link href="<?php echo siteUrl; ?>style@jhmshop.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
</head>
<body>
<?php include("include/header.php"); ?>

<?php include("navigation.php"); ?>
<div id="mainWrapper">
	<div id="innerWrapper" class="pagename">
    <h2 style="font-weight:normal; padding-bottom:10px; border-bottom:1px solid #999; margin-bottom:15px;"> SIGN UP </h2>
    	<div id="signupbox">
		<h3> SIGNUP </h3>
		<form action="<?php echo siteUrl; ?>action_model.php" method="post" enctype="multipart/form-data" style="font-size:13px;">
			<select required name="title" style="width:548px;">
				<option value="">- SELECT TITLE -</option>
				<option>Mr</option>
				<option>Mrs</option>
				<option>Ms</option>
			</select>
			<input type="text" name="fname" placeholder="First Name" required /> 		
			<input type="text" name="lname" placeholder="Last Name" required /> 		
			<input type="text" name="uname" placeholder="Username" required /> 			
			<input type="email" name="email" placeholder="Email Address" required /> 	
			<input type="password" name="pass" placeholder="Password" required /> 		
			<input type="password" name="cpass" placeholder="Confirm Password" required /> 
			<input type="text" name="phone" placeholder="Phone Number" required /> 		
			 		
			<input type="text" name="city" placeholder="City" required /> 				
			<input type="text" name="state" placeholder="State" required /> 			
			<select required name="country">
				<option value="">- SELECT COUNTRY -</option>
				<?php $country_rs = mysql_query("SELECT country_id, country_name FROM tbl_country ORDER BY country_name", $con);
				while($country_row = mysql_fetch_object($country_rs)){ ?>
					<option value="<?php echo $country_row->country_id; ?>"><?php echo $country_row->country_name; ?></option>
				<?php }	?>
			</select> 
            <textarea name="address" placeholder="Address" required="required" style="width:524px;"></textarea>
			<input type="text" name="zip" placeholder="Zip Code" required />
             	
			<input type="file" name="img" style="padding:6px;"/> 
			<input type="hidden" name="action" value="userRegistration" />
			<input type="submit" name="submit" value="SIGN UP"  class="loginbutton" style="width:539px !important; margin-left:30px; padding:13px !important; "/>
		</form>
        <div class="clr"> </div>
		</div>
		
		<div style="clear:both;"></div>
    </div>
</div>
<?php echo include("include/footer.php"); ?>
</body>
</html>
