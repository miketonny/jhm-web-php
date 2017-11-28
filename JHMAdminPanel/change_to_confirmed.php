<?php include '../include/config.php';
$succeeded = false;
if(isset($_GET['order_id'])) {
	$order_id = $_GET['order_id'];
	$query = "SELECT * from tbl_order_item WHERE order_id=".$order_id;
	echo $query."<br>";
	$itemRs = mysqli_query($con, $query);
	
	while($item = mysqli_fetch_object($itemRs)) {
		$query = "UPDATE tbl_product_price SET qty=qty-".$item->od_qty." WHERE product_id=".$item->product_id." AND color_id=".$item->color_id;
		echo $query."<br>";
		mysqli_query($con, $query);
	}
	$query = "UPDATE tbl_order_item SET isbackOrd=0 WHERE order_id=".$order_id;
	echo $query."<br>";
	if (mysqli_query($con, $query)) {
		$query = "UPDATE tbl_order SET isbackOrd=0 WHERE order_id=".$order_id;
		echo $query."<br>";
		if (mysqli_query($con, $query)) {
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