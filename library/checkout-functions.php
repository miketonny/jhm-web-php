<?php
/*********************************************************
*                 CHECKOUT FUNCTIONS 
*********************************************************/
function saveOrder($user_id, $con){
	$orderId       = 0;
	$shippingCost  = 5;
	$requiredField = array('hidShippingFirstName' , 'hidShippingLastName'	, 'hidShippingAddress1'	, 'hidShippingCity'	, 'hidShippingPostalCode',
						   'hidPaymentFirstName'  , 'hidPaymentLastName'	, 'hidPaymentAddress1'	, 'hidPaymentCity'	, 'hidPaymentPostalCode');
						   
	if (checkRequiredPost($requiredField)) {
	    extract($_POST);
		
		// make sure the first character in the 
		// customer and city name are properly upper cased
		$hidShippingFirstName = ucwords($hidShippingFirstName);
		$hidShippingLastName  = ucwords($hidShippingLastName);
		$hidPaymentFirstName  = ucwords($hidPaymentFirstName);
		$hidPaymentLastName   = ucwords($hidPaymentLastName);
		$hidShippingCity      = ucwords($hidShippingCity);
		$hidPaymentCity       = ucwords($hidPaymentCity);
		
		// OLD CODE // $sid = session_id();
		//$sql_cart = "SELECT ct_id, ct.pd_id, ct.days, ct.fromdate, ct.todate, ct_session_id, pd.*, ct_qty, media.src as pd_thumbnail, day_price as pd_price FROM tbl_cart ct left join tbl_product pd on ct.pd_id= pd.pd_id left join tbl_media media on pd.pd_id = media.pid WHERE ct_session_id = '$sid' AND ct.pd_id = pd.pd_id order by ct_id desc limit 0, 1";
		
		$sql_cart = "SELECT tbl_cart.cart_id, tbl_cart.promo_id, tbl_cart.product_id, tbl_cart.color_id, tbl_cart.qty, tbl_cart.product_price, tbl_cart.product_promo_price, tbl_product.product_name, tbl_product_color.color_code
FROM tbl_cart
LEFT JOIN tbl_product ON tbl_product.product_id = tbl_cart.product_id
LEFT JOIN tbl_product_color ON tbl_product_color.color_id = tbl_cart.color_id
WHERE tbl_cart.user_id = $user_id ORDER BY tbl_cart.datetime DESC";
		
		/*$sql_cart = "SELECT ct.cart_id, ct.visitor_id, ct.user_id, ct.pd_id, ct.store_id, ct.qty AS mainQty, ct.amount AS totalAmount, ct.datetime AS cartDatetime, pd.*, media.src as pd_thumbnail FROM tbl_cart ct left join tbl_product pd on ct.pd_id= pd.pdid left join tbl_productmedia media on pd.pdid = media.pdid WHERE ct.visitor_id = '$visitor_id' AND media.ismain = 1 order by ct.datetime desc";*/
				
		$rs_cart = exec_query($sql_cart, $con);
		$numItem = mysql_num_rows($rs_cart);
		
		// save order & get order id
		/*$sql = "INSERT INTO tbl_order(od_date, od_last_update, od_shipping_first_name, od_shipping_last_name, od_shipping_address1, 
		                              od_shipping_address2, od_shipping_phone, od_shipping_state, od_shipping_city, od_shipping_postal_code, od_shipping_cost,
                                      od_payment_first_name, od_payment_last_name, od_payment_address1, od_payment_address2, 
									  od_payment_phone, od_payment_state, od_payment_city, od_payment_postal_code, uid, shippinemail)
                VALUES (NOW(), NOW(), '$hidShippingFirstName', '$hidShippingLastName', '$hidShippingAddress1', 
				        '$hidShippingAddress2', '$hidShippingPhone', '$hidShippingState', '$hidShippingCity', '$hidShippingPostalCode', '$shippingCost',
						'$hidPaymentFirstName', '$hidPaymentLastName', '$hidPaymentAddress1', 
						'$hidPaymentAddress2', '$hidPaymentPhone', '$hidPaymentState', '$hidPaymentCity', '$hidPaymentPostalCode', '".$_SESSION['userid']."', '$txtemail')";*/
		$sql = "INSERT INTO `tbl_order` (`od_date`, `od_last_update`, `od_status`, `od_memo`, `od_shipping_first_name`, `od_shipping_last_name`, `od_shipping_address1`, `od_shipping_address2`, `od_shipping_phone`, `od_shipping_city`, `od_shipping_state`, `od_shipping_postal_code`, `od_shipping_cost`, `od_payment_first_name`, `od_payment_last_name`, `od_payment_address1`, `od_payment_address2`, `od_payment_phone`, `od_payment_city`, `od_payment_state`, `od_payment_postal_code`, `user_id`)
		VALUES ('".date('c')."', '".date('c')."', 'Unpaid', '', '$hidShippingFirstName', '$hidShippingLastName', '$hidShippingAddress1', '$hidShippingAddress2', '$hidShippingPhone', '$hidShippingCity', '$hidShippingState', '$hidShippingPostalCode', '0.00', '$hidPaymentFirstName', '$hidPaymentLastName', '$hidPaymentAddress1', '$hidPaymentAddress2', '$hidPaymentPhone', '$hidPaymentCity', '$hidPaymentState', '$hidPaymentPostalCode', '".$user_id."')";
		$result = exec_query($sql, $con);
		
		// get the order id
		$orderId = mysql_insert_id();
		if (isset($orderId) && $orderId != '') {
			// save order items
			$grandTotal = 0;
			while($cartContent = mysql_fetch_object($rs_cart)) {
				$price = checkPromoValidity($cartContent, $con);
				$totalAmount = $cartContent->qty * $price;
				$grandTotal += $totalAmount;
				$sql = "INSERT INTO tbl_order_item(order_id, product_id, od_qty, amount)
						VALUES ($orderId, '".$cartContent->product_id."', '".$cartContent->qty."', '".$totalAmount."')";
				$result = exec_query($sql, $con);
				
				$_SESSION['paypalemail'] = paypalId;
				$_SESSION['transaction_orderid'] = $orderId;
				$_SESSION['transaction_uid'] = $user_id;
				$_SESSION['transaction_date'] = date('c');
				$_SESSION['transaction_paymenttype'] = '1';
			}
			
			if(exec_query("UPDATE tbl_order SET amount = '$grandTotal' WHERE order_id = $orderId", $con)){
				
			}
			else{
				return false;
			}
			/* update product stock - NO NEED
			for ($i = 0; $i < $numItem; $i++) {
				$sql = "UPDATE tbl_product 
				        SET pd_qty = pd_qty - {$cartContent['ct_qty']}
						WHERE pd_id = {$cartContent['pd_id']}";
				$result = dbQuery($sql);					
			}*/
			
			
			/* then remove the ordered items from cart - NO NEED
			for ($i = 0; $i < $numItem; $i++) {
				$sql = "DELETE FROM tbl_cart
				        WHERE ct_id = {$cartContent['ct_id']}";
				$result = dbQuery($sql);					
			}*/							
		}					
	}
	return $orderId;
}

/* Get order total amount ( total purchase + shipping cost ) */
function getOrderAmount($orderId){
	$orderAmount = 0;
	$sql = "SELECT SUM(amount) AS amount FROM tbl_order_item WHERE order_id = '$orderId'";
	$result = mysql_query($sql);
	$amount = mysql_fetch_object($result)->amount;
	$amount = (isset($amount) && $amount != '' && !empty($amount))?$amount:0;
	$_SESSION['transaction_amount'] = $amount;
	return $amount;
}?>