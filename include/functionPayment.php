<?php
//function process_request($amount, $orderId){
function process_request($name, $cardNum, $ex, $cvv, $orderId, $amount){
	unset($_SESSION['order_id']);
	$orderNo = getOrderId($orderId);
	$cmdDoTxnTransaction = "
	<Txn>
		<PostUsername>JHMLimited223_Dev</PostUsername>
		<PostPassword>password12</PostPassword>
		<CardHolderName>$name</CardHolderName>
		<CardNumber>$cardNum</CardNumber>
		<Amount>$amount</Amount>
		<DateExpiry>$ex</DateExpiry>
		<Cvc2>$cvv</Cvc2>
		<Cvc2Presence>1</Cvc2Presence>
		<InputCurrency>NZD</InputCurrency>
		<TxnType>Purchase</TxnType>
		<TxnId>$orderNo</TxnId>
		<MerchantReference>$orderNo</MerchantReference>
	</Txn>";

	$URL = "https://sec.paymentexpress.com/pxpost.aspx";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $URL);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$cmdDoTxnTransaction);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //Needs to be included if no *.crt is available to verify SSL certificates
	$result = curl_exec ($ch);
	curl_close($ch);
    return parse_xml($result, $orderId);    //boolean true or false;

}

function parse_xml($data, $orderId){
	$xml_parser = xml_parser_create();
	xml_parse_into_struct($xml_parser, $data, $vals, $index);
	xml_parser_free($xml_parser);
	$params = array();
	$level = array();
	foreach ($vals as $xml_elem) {
		if ($xml_elem['type'] == 'open') {
			if (array_key_exists('attributes',$xml_elem)) {
				list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);
			}
			else {
				$level[$xml_elem['level']] = $xml_elem['tag'];
			}
		}
		if ($xml_elem['type'] == 'complete') {
			$start_level = 1;
			$php_stmt = '$params';

			while($start_level < $xml_elem['level']) {
				$php_stmt .= '[$level['.$start_level.']]';
				$start_level++;
			}

			$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
			@eval($php_stmt);
		}
	}
	echo "<pre>";
	//print_r($params);
	echo "</pre>";

	if(!empty($params) && !empty($params['TXN']) && !empty($params['TXN'][1])){
		$myArr = $params['TXN'][1];
		if(isset($myArr['AUTHORIZED']) && $myArr['AUTHORIZED'] == 1){
			$user_id = $_SESSION['user'];

			// update order table
			$update = mysql_query("UPDATE tbl_order SET payment_status = 'Paid', trnid = '".$myArr['TRANSACTIONID']."', trn_ref = '".$params['TXN']['TXNREF']."' WHERE order_id = '".$orderId."'");

			sendOrderMail($user_id, $orderId);

			if($update){
				//post payment order updates, i.e. update stock on hand to deduct stocks.

				$orderRs = mysql_query("SELECT od_point_spend FROM tbl_order WHERE order_id = $orderId");
				$orderRow1 = mysql_fetch_object($orderRs);
				if(isset($orderRow1->od_point_spend) && $orderRow1->od_point_spend > 0){
					$pointQ = "INSERT INTO `tbl_user_point` (`order_id`, `user_id`, `point`, `datetime`) VALUES('$orderId', '$user_id', '-$orderRow1->od_point_spend', '".date('c')."')";
					mysql_query($pointQ);
				}
				//setMessage("Payment Process Successfully Completed.", 'alert alert-success');
			}
			else{
				setMessage("Payment Process Successfully Completed, But Some Error Occured While Processing Order.", 'alert alert-success');
			}
            return true;

		}
		else{
			//echo 'failure';
			mysql_query("DELETE FROM tbl_order WHERE order_id = '$orderId'");
			mysql_query("DELETE FROM tbl_order_item WHERE order_id = '$orderId'");
//			setMessage("Payment failed, please try again.", 'alert alert-error');
            return false;
//			redirect(siteUrl.'failure/'); die();
		}
	}else{
        return false;
//		setMessage("Payment failed, please try again.", 'alert alert-error');
//		redirect(siteUrl.'failure/'); die();
	}
    return false;
//	redirect(siteUrl); die();
}
?>