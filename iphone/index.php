<?php
require_once('Apple.php');
$apple = new Lib_Apple();
if(isset($_REQUEST['deviceToken']) && $_REQUEST['deviceToken'] != ""){
	$dataArr = array();
	$dataArr['deviceToken'] = $_REQUEST['deviceToken'];
	$dataArr['emer_id'] = $_REQUEST['emer_id'];
	$dataArr['message'] = urldecode($_REQUEST['message']);
	
	$apple->sendAppleNotification($dataArr);
}else{
	echo "Invalid Device ID";
}
?>

