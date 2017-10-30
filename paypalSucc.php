<?php
session_start();
include("include/config.php");
include("include/functions.php");

// chk orderid
if(chkParam1($_GET['ordId']) && isset($_GET['ordId'])){	$orderId = $_GET['ordId']; }
elseif(chkParam1($_SESSION['order_id']) && isset($_SESSION['order_id'])){ $orderId = $_SESSION['order_id']; }
else{ redirect(siteUrl); }
unset($_SESSION['order_id']);

$orderItems=mysql_query("select product_id, od_qty from tbl_order_item where order_id = '".$orderId."'");
while($orderItem=mysql_fetch_object($orderItems)){
	mysql_query("update tbl_product set qty=qty-".$orderItem->od_qty." where product_id = '".$orderItem->product_id."'");
}

$user_id = $_SESSION['user'];

// update order table
$update = mysql_query("UPDATE tbl_order SET payment_status = 'Paid' WHERE order_id = '".$orderId."'");
			
sendOrderMail($user_id, $orderId, $con);
			
if($update){
	
	$orderRs = mysql_query("SELECT od_point_spend FROM tbl_order WHERE order_id = $orderId", $con);
	$orderRow = mysql_fetch_object($orderRs);
	if(isset($orderRow->od_point_spend) && $orderRow->od_point_spend > 0){
		mysql_query("INSERT INTO `tbl_user_point` (`order_id`, `user_id`, `point`, `datetime`) VALUES('$orderId', '$user_id', '-$orderRow->od_point_spend', '".date('c')."')");
	}
	
	setMessage("Payment Process Successfully Completed.", 'alert alert-success');
}
else{
	setMessage("Payment Process Successfully Completed, But Some Error Occured While Processing Order.", 'alert alert-success');
}
redirect(siteUrl.'success/orderId4xip'.$orderId); die();
?>