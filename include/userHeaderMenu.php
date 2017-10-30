<li class="downarrow userdropdown dropdown" >
        <?php $user = getUser($_SESSION['user'], $con);
        if($user->img == '' || empty($user->img) || !file_exists(siteUrl."site_image/profile_pic/".$user->img)){ $thumbnail = 'images/user.jpg'; }
        else{ $thumbnail = "site_image/profile_pic/".$user->img; }
        ?>
    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" >Welcome, (<strong><?php echo $_SESSION['user_name']; ?>)</strong></a>
    <ul class="dropdown-menu">
        <li><a href="<?php echo siteUrl; ?>userdashboard/" style="color:#333!important;">My Dashboard</a></li>
        <!--<li><a href="<?php echo siteUrl; ?>userchangepassword/" style="color:#333!important;">Change Password</a></li>-->
        <li><a href="<?php echo siteUrl; ?>usereditprofile/" style="color:#333!important;">Edit Profile</a></li>
        <li><a href="<?php echo siteUrl; ?>userlogout/"style="color:#333!important;">Logout</a></li>
    </ul>
</li>