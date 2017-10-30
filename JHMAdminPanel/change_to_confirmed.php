<?php include '../include/config.php';
$succeeded = false;
if(isset($_GET['order_id'])) {
	$order_id = $_GET['order_id'];
	$query = "SELECT * from tbl_order_item WHERE order_id=".$order_id;
	echo $query."<br>";
	$itemRs = mysql_query($query, $con);
	
	while($item = mysql_fetch_object($itemRs)) {
		$query = "UPDATE tbl_product_price SET qty=qty-".$item->od_qty." WHERE product_id=".$item->product_id." AND color_id=".$item->color_id;
		echo $query."<br>";
		mysql_query($query);
	}
	$query = "UPDATE tbl_order_item SET isbackOrd=0 WHERE order_id=".$order_id;
	echo $query."<br>";
	if (mysql_query($query)) {
		$query = "UPDATE tbl_order SET isbackOrd=0 WHERE order_id=".$order_id;
		echo $query."<br>";
		if (mysql_query($query)) {
			$succeeded = true;
		}
	}
}

if ($succeeded) {
	echo "Successfully changed.";
} else {
	echo "change is failed. Please try, again.";
}
?>