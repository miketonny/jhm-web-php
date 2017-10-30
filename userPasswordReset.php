<?php session_start();
include "include/new_top.php";
include "include/new_header.php";
if (!isset($_GET['userId']) || $_GET['userId'] == '' || empty($_GET['userId'])) {redirect(siteUrl);die();}
$user_id = str_replace('CurrentSecurityOnLevelNo', '', $_GET['userId']);

$userRs = exec_query("SELECT * FROM tbl_user WHERE user_id = '$user_id'", $con);
if (!mysqli_num_rows($userRs)) {redirect(siteUrl);die();}
?>
<div class="block-pt5">
	<div id="innerWrapper" class="pagename">
        <div id="textWrapper">
        	<div id="leftWrapper" class="width100">
                <div id="profile">
                    <div id="userpages">
                    	<h2>Reset Password</h2>
                        <div id="signupbox" style="margin:0px;">
                            <form class="col-md-4" action="<?php echo siteUrl; ?>action_model.php" method="post" enctype="multipart/form-data" style="font-size:13px;">

                                <input type="password" name="new_password" placeholder="New Password" required /><br/>
                                <input type="password" name="confirm_new_password" placeholder="Confirm New Password" required /><br/>

                                <input type="hidden" name="action" value="userResetPassword" />
                                <input type="hidden" name="user" value="<?php echo $user_id; ?>" />
                                <input type="submit" name="submit" value="Submit"  class="loginbutton" />
                            </form>
                            <div class="clr"> </div>
                       	</div>
                    </div>

                </div>
                <div id="clr"></div>
            </div>
	        <div id="clr"></div>
        </div>
		<div style="clear:both;"></div>
    </div>
</div>

<?php include "include/new_footer.php";?>
<?php include "include/new_bottom.php";?>