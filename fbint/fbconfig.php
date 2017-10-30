<?php
require 'src/facebook.php';  // Include facebook SDK file
//require 'functions.php';  // Include functions
$facebook = new Facebook(array(
  'appId'  => '1602769846674933',   // Facebook App ID 
  'secret' => '1a34c404197f4b3191ac01aa393a11c8',  // Facebook App Secret
  'cookie' => true,	
));
$user = $facebook->getUser();

if ($user) {
  try {
    $user_profile = $facebook->api('/me');
  	//echo $user;
  	 //print_r($user_profile);
	 //exit;
  	// echo '<img src="https://graph.facebook.com/'.$user.'/picture">';
	
	    $fbid = $user_profile['id'];                 // To Get Facebook ID
 	    $fbuname = $user_profile['username'];  // To Get Facebook Username
 	   // $fbfullname = $user_profile['name']; // To Get Facebook full name
	   
	   $fname=$user_profile['first_name'];
	   $lname=$user_profile['last_name'];
	   $gender=$user_profile['gender'];
	    $femail = $user_profile['email'];    // To Get Facebook email ID
	    $img=$user_profile['link'];
	/* ---- Session Variables -----*/
	    $_SESSION['FBID'] = $fbid;           
	    $_SESSION['USERNAME'] = $fbuname;
        $_SESSION['FULLNAME'] = $fbfullname;
	    $_SESSION['EMAIL'] =  $femail;
		
		$data = explode('@', $femail);
		
		include("../include/config.php");
		$qcheck=mysql_query("select * from tbl_user where email='$femail' or fb_id='$fbid'")or die(mysql_error());
		$numcheck=mysql_num_rows($qcheck);
		$fetch=mysql_fetch_array($qcheck);
		
		if($numcheck>0)
		{
		
			if($femail==''){
			$data[0] = $fname.$lname;
			}
			$_SESSION['user_email']=$fetch['email'];
			$_SESSION['user']=$fetch['user_id'];
			$_SESSION['user_name']=$data[0];
			header("location:".siteUrl); 
		}
		else
		{
			$query=mysql_query("insert into tbl_user (fb_id,email,first_name,last_name,username) values('$fbid','$femail','$fname','$lname','".$data[0]."')")or die(mysql_error());
			if($femail==''){
			$data[0] = $fname.$lname;
			}
			$_SESSION['user']=mysql_insert_id();
			$_SESSION['user_email']=$femail;
			$_SESSION['user_name']=$data[0];
			header("location:".siteUrl); 
		}
		
	
	
	
	//       checkuser($fbid,$fbuname,$fbfullname,$femail);    // To update local DB
  } catch (FacebookApiException $e) {
    error_log($e);
   $user = null;
  }
}
if ($user) {
//	header("Location: index.php");
} else {
 $loginUrl = $facebook->getLoginUrl(array(
		'scope'		=> 'email', // Permissions to request from the user
		));
 
 header("Location: ".$loginUrl);
}
?>
 
