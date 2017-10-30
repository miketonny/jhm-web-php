<?php
session_start();
include '../include/config.php';
include '../include/function.php';

$recid = $_SESSION['admin'];
$old_email = $_SESSION['email'];

$email = $_POST['email'];
$old_password = md5($_POST['old_password']);
$new_password = md5($_POST['new_password']);
$confirm_new_password = md5($_POST['confirm_new_password']);

$rs = mysql_query("SELECT password FROM admin WHERE recid = '$recid'", $con);
if(mysql_num_rows($rs)){
	$row = mysql_fetch_object($rs);
	$old_db_password = $row->password;
	
	if($old_db_password == $old_password){
		if($new_password == $confirm_new_password){
			
			$to = $email;
			$subject = "Profile Update (Admin) - Pink Pages";
			
			$txt = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<div bgcolor="#F5F5F4" style="background:#f5f5f4;margin:0;padding:0">



<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#f5f5f4" style="width:100%">
	<tbody><tr>
    	<td align="center">
        

<table width="600px" cellspacing="28" cellpadding="0" bgcolor="#FFFFFF">
	<tbody><tr>
    	<td>
        
        
        <table width="544" cellspacing="0" cellpadding="0">
        	
            
            <tbody><tr>
                <td style="padding:10px 20px 3px;text-align:center; font-family:Verdana, Geneva, sans-serif;font-size: 35px;">
                	PINK PAGES
                </td>
            </tr>
            <tr>
            	<td style="color:white;background-color:#F08396;font-family:Arial;font-size:24px;font-weight:bold;line-height:1.2em;text-align:center;padding:15px 30px"></td>
            </tr>
            
            
          	<tr>
            	<td height="20">
          			<table width="544" cellspacing="0" cellpadding="0">
            			<tbody><tr>
                			<td>
<h1 align="center" style="color:#676767;display:block;font-family:Inlove-Light,Arial,sans-serif;font-size:30px;font-style:normal;font-weight:normal;margin:20px 0 0;padding:0;text-align:center">Profile Update (Admin) Notification</h1>
<p style="color:#676767;display:block;float:left;font-family:ProximaNova-Regular,Arial,sans-serif;font-size:16px;font-style:normal;font-weight:normal;margin:20px 0 0;padding:0;">
   
   
Hi Administrator,<br><br>

Your Email id is changed from '.$old_email.' to '.$email.'.<br><br>

Your Password is changed from '.$old_db_password.' to '.$new_password.'.<br>

For any query contact us at info@pinkpages.com<br>
Thanks,<br>
Pink Pages<br>
The Pink Pages Team<br>

</p>
              				</td>
                      	</tr>
                  	</tbody></table>
              	</td>
          	</tr>
              
        </tbody></table>
        
        
        </td>
    </tr>
</tbody></table>



<table width="600" cellspacing="28" cellpadding="0" bgcolor="#404040">
	<tbody><tr>
    	<td>
        
        
        <table width="544" cellspacing="0" cellpadding="0">
        	<tbody><tr>
            	<td width="302" valign="top" align="left">
                	<p style="color:#808080;display:block;float:left;font-family:ProximaNova-Regular,Arial,sans-serif;font-size:12px;font-style:normal;font-weight:normal;margin:0;padding:0">Pink Pages<br>
<span>10 7th St #420<br>
Mexico, CA 10700</span></p>
                </td>
                <td width="20">&nbsp;</td>
                
            </tr>
            <tr>
              <td valign="bottom" height="40px" style="color:#808080;text-decoration:none" colspan="3">
                	<p style="color:#808080;display:block;float:left;font-family:ProximaNova-Regular,Arial,sans-serif;font-size:12px;font-style:normal;font-weight:normal;margin:0;padding:0">
                      &copy; 2014 Pink Pages, Inc. All rights reserved.<br></p>
                </td>
            </tr>
        </tbody></table>
        
        </td>
    </tr>
</tbody></table>


        </td>
    </tr>
</tbody></table>
</body>
</html>
';
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
			$headers .= "From: info@pinkpages.com" . "\r\n";
		
			$mail = mail($to, $subject, $txt, $headers);
			
			$rs_update = mysql_query("UPDATE admin set email = '$email', password = '$new_password' WHERE recid = '$recid'", $con);
			setMessage('Profile successfully edited.', 'alert alert-success');
			redirect('home.php');
			die();
		}
		else{
			setMessage('Error, New password not matching with confirm new password.', 'alert alert-error');
			redirect('profileEdit.php');
			die();
		}
	}
	else{
		setMessage('Error, Old password not matching with your current password.', 'alert alert-error');
		redirect('profileEdit.php');
		die();
	}
}
else{
	setMessage('Failed, Some error occured. Try again later.', 'alert alert-error');
	redirect('profileEdit.php');
}
?>