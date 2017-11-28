<?php setcookie('PHPSESSID', session_id(), 0, '/');
if (!isset($_SESSION)) {
      session_start();
}
include '../include/config.php';
include '../include/function.php';

$email = tres($_POST['email'],$con);
$password = tres(md5($_POST['password']),$con);



// $rs = mysqli_query($con,"SELECT recid, email, username FROM admin WHERE (username = '$email' AND password = '$password') OR (email = '$email' AND password = '$password')");
$stmt = $con->prepare("SELECT recid, email, username FROM admin WHERE (username = ? AND password = ?) OR  (email = ? AND password = ?) Limit 1");
$stmt->bind_param("ssss", $email, $password, $email, $password); //binds the param

$stmt->execute(); //execute the query
$stmt->bind_result($userID, $userEmail, $userName);

while ($stmt->fetch()){
	$_SESSION['email'] = $userEmail;
	$_SESSION['admin'] = $userID;
	$_SESSION['notifyShop'] = 0;
}

if (isset($userID)) {
	 	setMessage('Welcome ' . $userName, 'alert alert-success');
		header('Location: '.siteUrl.'JHMAdminPanel/home.php');
		die();
}else{
		setMessage('Login failed', 'alert alert-error');
		header('Location: '.siteUrl.'JHMAdminPanel');
		//redirect('index.php');
		die();
}

$stmt->free_result();

// if(mysqli_num_rows($rs)){
// 	$row = mysqli_fetch_object($rs);
// 	$_SESSION['email'] = $row->email;
// 	$_SESSION['admin'] = $row->recid;
// 	$_SESSION['notifyShop'] = 0;
// 	setMessage('Welcome ' . $row->username, 'alert alert-success');
// 	header('Location: https://www.jhm.co.nz/JHMAdminPanel/home.php');
// 	die();
// 	//redirect('home.php'); die();
// }
// else{
// 	setMessage('Login failed', 'alert alert-error');
// 	header('Location: https://www.jhm.co.nz/JHMAdminPanel/index.php');
// 	//redirect('index.php');
// 	 die();
// }
?>