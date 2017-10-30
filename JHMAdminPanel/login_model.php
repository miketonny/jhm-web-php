<?php setcookie('PHPSESSID', session_id(), 0, '/');
session_start();
include '../include/config.php';
include '../include/function.php';

$email = tres($_POST['email'],$con);
$password = tres(md5($_POST['password']),$con);

$rs = mysqli_query($con,"SELECT recid, email, username FROM admin WHERE (username = '$email' AND password = '$password') OR (email = '$email' AND password = '$password')");
if(mysqli_num_rows($rs)){
	$row = mysqli_fetch_object($rs);
	$_SESSION['email'] = $row->email;
	$_SESSION['admin'] = $row->recid;
	$_SESSION['notifyShop'] = 0;
	setMessage('Welcome ' . $row->username, 'alert alert-success');
	redirect('home.php'); die();
}
else{
	setMessage('Login failed', 'alert alert-error');
	redirect('index.php'); die();
}
?>