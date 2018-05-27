<?php
	$bz_id = '';
	$fee_total = $_REQUEST['totalAmt'];
	$sale_id = $_REQUEST['orderId'];
	$title = 'JHM Ltd'; 
	$time = time();	//utc timestamp in long
	$nonce = substr(md5(microtime()),rand(0,26),16); //random 16 chars
	$integration_key = ''; ??
	$cont =trim(utf8_encode($bz_id.$fee_total.$sale_id.$title.$time.$nonce.$integration_key));
	$sign = urlencode(base64_encode(sha1($cont)));

	$api = 'https://api.payplus.co.nz/v3/rawWxpay/' .$sign;
	$uri = siteUrl.'wechatpayment.php';
	$result = getQR();

	if (isset($result)) {
		
		//QR is good, redirect to display QR page?
		#Redirect to payment page
    	header("Location: ".$uri."?qr=".$result->QR);
	}

	function getQR(){
		
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, this->$api);
		
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$inputXml);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		 

		$outputXml = curl_exec ($ch); 		
			
		curl_close ($ch);
  
		return $outputXml;
	}
?>