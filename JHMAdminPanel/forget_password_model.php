<?php
session_start();
include '../include/config.php';
include '../include/function.php';

$email = tres($_POST['email']);

$rs = mysqli_query($con, "SELECT recid, email, password, username FROM admin WHERE (username = '$email') OR (email = '$email')");
if(mysqli_num_rows($rs)){
	$row = mysqli_fetch_object($rs);
	//$pass = $row->password;
	
	/* send reset mail */
	$to = $row->email;
	$subject = 'Forget Password (Admin) - '.siteName;
	$txt = get_mail_content('header', 'Forget Password Admin - '.siteName).'<p style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;margin-bottom:10px;font-weight:normal;font-size:15px;line-height:1.6;color:#666"><br style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
Hi Administrator ('.$row->username.'),<br>
Click on following link to reset your Password,
<a href="'.siteUrl.'/resetPassword.php?info=true&OAUTHAPIPath=_SELF_FETCH&accessToken55='.rand(000000000000, 999999999999).'|'.$row->recid.'&APIDATA=pjqly66lxmalf8j9bax9szxvz9xldjdslajvjwpkdvkisdgsd99sd&datatarget52=admin" target="_blank" style="color:#333"> Click here to reset Pasword </a>
</p><br/>'.get_mail_content('footer', '');
	
	$mail = mail($to, $subject, $txt, get_mail_content('mail_header', ''));
	/* ----------------- */
	
	setMessage('Password reset link sent to registered Email Address.', 'alert alert-success');
}
else{
	setMessage('Error, Incorrect Email Address.', 'alert alert-error');
}
redirect('index.php'); die();
?>