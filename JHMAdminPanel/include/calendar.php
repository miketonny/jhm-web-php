<?php include '../../include/config.php';
include '../../include/function.php';
$arrayOrder = array();
$arrayJSON = array();
$orderQ = "SELECT order_id, od_date, amount, status FROM tbl_order WHERE payment_status = 'Paid'";
$orderRs = exec_query($orderQ, $con);
if(mysqli_num_rows($orderRs)){
	while($order = mysqli_fetch_object($orderRs)){ $oStatus = ''; $class = '';
		$date = strtotime($order->od_date).'000';
		$no = getOrderId($order->order_id);
		$status = $order->status;
		if($status == 0){ $oStatus = 'In Process'; $class = 'event-warning'; }
		elseif($status == 1){ $oStatus = 'Dispatched'; $class = 'event-info'; }
		elseif($status == -1){ $oStatus = 'Rejected'; $class = 'event-important'; }
		elseif($status == 2){ $oStatus = 'Delivered'; $class = 'event-success'; }
		
		$arrayOrder[] = array(
			"id" => "$order->order_id",
			"title" => "Order $no ($oStatus)",
			"url" => "#",
			"class" => "$class",
			"start" => "$date"
		);
	}
}
$arrayJSON['success'] = 1;
$arrayJSON['result'] = $arrayOrder;
echo json_encode($arrayJSON); ?>