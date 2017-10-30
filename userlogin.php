<?php include("include/new_top.php"); ?>

<?php include("include/new_header.php"); ?>
<section class="block-pt5">
    <div class="container">
        
        <div class="col-md-8 col-md-push-2">
         <div id="loginWrapper" style="position:inherit; background:none; box-shadow:none; margin:0px auto; left:auto;">
            <div id="loginBox">
                 <div class="left">
            <div class="switch">
                <span class="signin">New to JHM Stores? <em><a href="javascript:void(0)" onclick="activebox('signupscreen')">Create a new account</a></em></span>
                <span class="signup">Already have an Account? <em><a href="javascript:void(0)" onclick="activebox('loginscreen')">Sign in</a></em></span>
            </div>
            <h3 class="signin">Connect to your account</h3>
            
            <div class="social">
                <div class="facebook">
                    <a href="<?php echo siteUrl; ?>fbint/fbconfig.php">
					<div class="fb-icon-bg"></div>
                    <div class="fb-bg"></div></a>
                </div>
                <div class="facepile">
                    <iframe _src="https://www.facebook.com/plugins/facepile.php?app_id=182424375109898" allowtransparency="true" style="border:none;overflow:hidden; width:200px;" id="facepile-iframe" src="https://www.facebook.com/plugins/facepile.php?app_id=182424375109898"></iframe>
                </div>
                <div class="err"></div>
            </div>
            
            <div class="separator">
                <div>OR</div>
                <div class="orbtn">OR</div>
            </div>
            <form class="signin" name="signin" action="<?php echo siteUrl; ?>action_model.php" method="post">
                <input type="text" name="email" placeholder="Enter email address" required />
                <input type="password" name="pass" placeholder="Password" required />
                <input type="submit" name="submit" value="LOGIN" class="loginbutton">
                <input name="action" type="hidden" value="userLogin" />
            </form>
            <form class="signup" name="signin" action="<?php echo siteUrl; ?>action_model.php" method="post">
                <input type="email" name="email" placeholder="Enter email address" required />
                <input type="password" name="pass" placeholder="Password" required />
                <input type="password" name="cpass" placeholder="Confirm Password" required />
                <input type="submit" name="submit" value="Sign Up" class="loginbutton">
                <input name="action" type="hidden" value="userQuickSignUp" />
                <input name="data3" type="hidden" value="data3" />
            </form>
            	<!-- closebox('loginWrapper'); -->
                <a href="javascript:void(0)" onclick="forgotPassword()" class="forgotbutton">Forget Password?</a>
				<div id="forgetWrapper">
						<div style="text-align:center;">
							<form class="formForget" action="<?php echo siteUrl; ?>action_model.php" method="post" >
								<input type="email" name="email" placeholder="Enter Username / Email Address" required class="txtMargin" />
								<input type="submit" name="button" value="Reset Now" class="loginbutton txtMargin">
								<input name="action" type="hidden" value="userForgetPassword" />
							</form>
						</div>
				</div>
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
        </div>
    </div>
</section>

    	
        
       
<script><?php
if(isset($_GET['type'])){
	if($_GET['type'] == 'login'){ ?> activebox('loginscreen'); <?php }
	elseif($_GET['type'] == 'register'){ ?> activelogin('signupscreen'); <?php }
	else{ ?> activebox('loginscreen'); <?php }
}
else{ ?> activebox('loginscreen'); <?php } ?>


function forgotPassword(){
	document.getElementById('forgetWrapper').className="activated";
}
</script>
        



<?php include("include/new_footer.php"); ?>
<?php include("include/new_bottom.php"); ?>
