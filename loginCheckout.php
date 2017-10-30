<?php session_start();
include("include/config.php");
include("include/functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: <?php echo siteName; ?> ::</title>
<link href="<?php echo siteUrl; ?>style@jhmshop.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<style> #hideItt{ display:none; } </style>
</head>
<body>
<?php include("include/header.php"); ?>
<?php include("navigation.php"); ?>
<div id="mainWrapper">
	<div id="innerWrapper" class="pagename">
    	<div id="address">
        	<h3> Quick Sign Up </h3>
            <div id="existing">
            	<ul>
                	<li>
                        
                        <div id="loginWrapper" style="box-shadow: none; left: auto; margin: 0; position: relative; top: 0; z-index: 0;">
                            <div id="loginBox">
                                 <div class="left">
                            		<div class="switch">
                                		<span class="signup">Login or Sign Up for <em> Checkout</em></span>
                            		</div>
                            		<h3 class="signin">Quick Sign Up </h3>
                            
                                    <div class="social">
                                        <div class="facebook">
                                            <div class="fb-icon-bg"></div>
                                            <div class="fb-bg"></div>
                                        </div>
                                        <div class="facepile">
                                            <iframe _src="https://www.facebook.com/plugins/facepile.php?app_id=182424375109898" allowtransparency="true" style="border:none;overflow:hidden; width:200px;" id="facepile-iframe" src="https://www.facebook.com/plugins/facepile.php?app_id=182424375109898"></iframe>
                                        </div>
                                        <div class="err"></div>
                                    </div>
                            
                                    <div class="separator">
                                        <div>OR</div>
                                    </div>
                                    <form class="signin" name="signin" action="<?php echo siteUrl; ?>action_model.php" method="post" style="display: inline-block ! important;">
                                        
                                        <input type="radio" name="type" value="new" checked="checked" onchange="switchTxt(this.value)" style="float:left;" />
                                        <p style="text-align: left;"> Enter Email for Quick Sign Up</p>
                                        <div>
                                        	<input type="text" name="email" placeholder="Enter email address" required id="emailId" />
                                        </div>
                                        <div class="clr"> </div>
                                        
                                        <input type="radio" name="type" value="old" onchange="switchTxt(this.value)" style="float:left;" />
                                        <p style="text-align: left;"> Already Have an Account? Sign In Now!</p>
                                        <div id="hideItt">
                                        	<input type="password" name="pass" placeholder="Password" id="passwdd" />
                                        </div>
                                        <div class="clr"> </div>
                                        
                                        <input type="submit" name="submit" value="SUBMIT" class="loginbutton">
                                        <input name="action" type="hidden" id="action" value="userQuickSignUp" />
                                    </form>
                             	</div>
                                <div class="right">
                                    <div class="signin">
                                    	<h4>Login to</h4>
                                        <ul>
                                        	<li>Apply coupons &amp; cashback</li>
                                            <li>Place orders easily</li>
                                            <li>Track past orders</li>
                                            <li>Manage WishList</li>
                                      	</ul>
                                 	</div>
                                </div>
                            </div>
                        </div>
                        
                    </li>
                </ul>
            </div>
            
            <div class="clr"> </div>
        </div>
        
        <div class="clr"></div>
    </div>
</div>
<script>
function switchTxt(type){
	if(type == 'old'){
		document.getElementById('hideItt').style.display = 'block';
		document.getElementById('action').value = 'userLogin';
		//$('#emailId').attr('type', 'text');
		$('#passwdd').attr('required', 'required');
	}
	else if(type == 'new'){
		document.getElementById('hideItt').style.display = 'none';
		document.getElementById('action').value = 'userQuickSignUp';
		//$('#emailId').attr('type', 'email');
		$('#passwdd').removeAttr('required');
	}
}
</script>
<?php include("include/footer.php"); ?>
</body>
</html>