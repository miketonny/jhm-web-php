<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>


<section class="block-pt5">
    <div class="container">

	<div  class="dashboard-holder">

      <div class="col-sm-12">
    	<h1 class="mainHeading">Profile </h1>
            </div>
           <div class="col-md-3 col-sm-4"> 
      	<?php include 'include/userNavigation.php'; ?>
           </div>

<div class="col-md-9 col-sm-8">  
                <div class="clr"> </div>

<div id="profile">
                   
		           <div id="clr"></div>
                    <div id="userpages">
                    	<h2>Edit Profile</h2>
                        <div id="signupbox" class="col-md-6" style="margin:0px;">
                            <form action="<?php echo siteUrl; ?>action_model.php" method="post" enctype="multipart/form-data" style="font-size:13px;">
                                <select required name="title" >
                                    <option value="">- SELECT TITLE -</option>
                                    <option <?php getSelected($user->title, 'Mr'); ?>>Mr</option>
                                    <option <?php getSelected($user->title, 'Mrs'); ?>>Mrs</option>
                                    <option <?php getSelected($user->title, 'Ms'); ?>>Ms</option>
                                </select>
                                <input type="text" name="fname" placeholder="First Name" required value="<?php echo $user->first_name; ?>" />
                                <input type="text" name="lname" placeholder="Last Name" required value="<?php echo $user->last_name; ?>" />
                                <input type="text" name="uname" placeholder="Username" style="display:none" required value="<?php echo $user->username; ?>" readonly="readonly" />
                                <input type="email" name="email" placeholder="Email Address" required value="<?php echo $user->email; ?>" readonly="readonly" />
                                <input type="text" name="phone" placeholder="Phone Number" required value="<?php echo $user->phone_1; ?>" />
                                <input type="text" name="city" placeholder="City" required value="<?php echo $user->city; ?>" />
                                <input type="text" name="state" placeholder="State"  style="display:none" required value="<?php echo $user->state; ?>" />
                                <select required name="country">
                                    <option value="">- SELECT COUNTRY -</option>
                                    <?php $country_rs = mysql_query("SELECT country_id, country_name FROM tbl_country ORDER BY country_name", $con);
                                    while($country_row = mysql_fetch_object($country_rs)){ ?>
                                        <option <?php getSelected($user->country_id, $country_row->country_id); ?> value="<?php echo $country_row->country_id; ?>"><?php echo $country_row->country_name; ?></option>
                                    <?php }	?>
                                </select>
                                <textarea name="address" placeholder="Address" required="required" ><?php echo $user->address_1; ?></textarea>
                                <input type="text" name="zip" placeholder="Zip Code"  style="display:none" required value="<?php echo $user->zip; ?>" />
                                <?php /*<input type="file" name="img" style="padding:6px;"/>
                                if($user->img != ''){ ?>
                                    <img src="<?php echo siteUrl; ?>site_image/profile_pic/<?php echo $user->img; ?>" style="margin-top:9px;" >
                                <?php }*/ ?>
                                <input type="hidden" name="action" value="userEditProfile" />
                                <input type="submit" name="submit" value="Save Changes"  class="loginbutton" />
                            </form>
                            <div class="clr"> </div>
                       	</div>
                    </div>
                    <?php /*<br/>
                    <div id="userpages">
                    	<h2>Change Password</h2>
                        <div id="loginbox">
                            <form action="<?php echo siteUrl; ?>action_model.php" method="post">
                                <input type="password" name="old_password" placeholder="Current Password" required />
                                <input type="password" name="new_password" placeholder="New Password" required />
                                <input type="password" name="confirm_new_password" placeholder="Confirm New Password" required />
                                
                                <input type="hidden" name="action" value="userchangepassword" />
                                <input type="submit" name="submit" value="Save Changes" class="loginbutton" style="width:130px !important;" />
                            </form>
                        </div>
                        <div class="clr"> </div>
                    </div>
                    */ ?>
                </div>


</div>


<div class="clr"> </div>
	</div>
  
        
  </div>
</section>






<?php include("include/new_footer.php"); ?>
<?php include("include/new_bottom.php"); ?>