<?php session_start();
include('include/config.php');
include('include/functions.php');

$min = 15;
//echo date('Y-m-d H:i:s');
$mins = mysqli_fetch_object(exec_query("SELECT no FROM tbl_config WHERE type = 'shoppingTimeoutCart'", $con));
if(isset($mins->no) && $mins->no != '' && $mins->no > 0){ $min = $mins->no; }
$curr = date('Y-m-d H:i:s', strtotime("-$min minute", strtotime(date('c'))));

$query = "SELECT cart_id FROM tbl_cart WHERE datetime < '$curr'";
$rs = mysqli_query($con, $query);
while($row = mysqli_fetch_object($rs)){
	$cartId = $row->cart_id;
	$del = exec_query("DELETE FROM tbl_cart WHERE cart_id = '$cartId'", $con);
}
echo "Done";
//exec_query("INSERT INTO tbl_cart(datetime) VALUES ('".date('c')."')", $con);
//schtasks /create /tn "cronCleanCart" /tr "C:\xampp\php\php.exe C:\xampp\htdocs\jhm\cronCleanCart.php" /sc minute /mo 2
?>