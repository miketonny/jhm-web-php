<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>

<?php if(!isset($_SESSION['user']) || $_SESSION['user'] == '' || empty($_SESSION['user'])){ redirect(siteUrl); } ?>






<section class="block-pt5">
    <div class="container">

	<div  class="dashboard-holder">

     <div class="col-sm-12">
    	<h1 class="mainHeading">Change Password </h1>
            </div>
           <div class="col-md-3 col-sm-4"> 
      	<?php include 'include/userNavigation.php'; ?>
           </div>


<div class="col-md-9 col-sm-8">  
                <div class="clr"> </div>
<div id="profile">
               		
            		<div id="clr"></div>
                    <div id="userpages">
            			<h2>Change Password</h2>
                        <div id="loginbox" style="background:none; border:none; width:274px; text-align:left;">
                            <form action="<?php echo siteUrl; ?>action_model.php" method="post">
                                <input type="password" name="old_password" placeholder="Current Password" required />
                                <input type="password" name="new_password" placeholder="New Password" required />
                                <input type="password" name="confirm_new_password" placeholder="Confirm New Password" required />
                                
                                <input type="hidden" name="action" value="userchangepassword" />
                                <input type="submit" name="submit" value="Save Changes" class="loginbutton" style="width:130px !important;" />
                            </form>
                        </div>
                   	</div>
             	</div>
</div>

<div class="clr"> </div>
	</div>
  
        
  </div>
</section>



<?php include("include/new_footer.php"); ?>
<?php include("include/new_bottom.php"); ?>



