<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . "/vendor/autoload.php";
///generates QR code for wechat/alipay then redirect to let client to pay for the order
function getQR(){ 
    //payplus credentials etc ====================================================
    $bizID = 29;
    $intToken = 'gpI3wXspE49xfYXUmmIoz9xRjEOxXzzVwPGDOsxDzuGFAa4xnM9X8dUceWCVQPTc';
    // $feeTotal = $_SESSION['totalAmt'];
    $feeTotal = 0.01; //total charged, dev mode use 0.01
    $saleId = $_GET['ordId'];
    $title = 'JHM Ltd Payment';
    $type = $_GET['type'];
    $time = time();
    $nonce = substr(md5(microtime()),rand(0,26),16);; //16 random chars
    $API; 
    //pattern /biz_id/fee_total/sale_id/title/time/nonce/sign'; 
    if($type == 'wechat'){
        $API = 'https://api.payplus.co.nz/v3/rawWxpay';
    }else if($type == 'alipay'){
        $API = 'https://api.payplus.co.nz/v3/rawAlipay';
    }
    if($saleId == null){  header('Location: ../paymentfailed'); die(); }
    $cont=trim(utf8_encode($bizID.$feeTotal.$saleId.$title.$time.$nonce.$intToken));
    $sign=urlencode(base64_encode(sha1($cont)));
    //send request to server fetching QR code string
    $s = curl_init(); 
    $url =  $API . '/' . $bizID . '/' . $feeTotal . '/' . $saleId . '/' . $title . '/' . $time . '/' . $nonce . '/' . $sign;
    curl_setopt($s,CURLOPT_URL, $url); 
    curl_setopt($s,CURLOPT_RETURNTRANSFER,true); 
    $result = curl_exec($s);
    curl_close($s); 
    $res = json_decode($result, true)[0]; //get json respons from api 
    if($res['result_code'] == 'SUCCESS'){
        //generate QR code, QR is good, redirect to display QR page?
        $qrTempDir = '/temp';
        $filePath = $qrTempDir.'/'.uniqid();
        //save the generated png as a encoded bas64 string, and store in cookie for retrive on next page
        QRcode::png(urldecode($res['code_url']), $filePath, QR_ECLEVEL_H, 6, 0); //write to temp file
        $qrImage = file_get_contents($filePath);
        unlink($filePath); //remove temp file
        //set the session variables of otn and qrcode 
        $_SESSION['otn'] = $res['out_trade_no'];
        $_SESSION['QRString'] = base64_encode($qrImage); 
        $_SESSION['type'] = $type;
        // setcookie("otn", $res['out_trade_no']);
        // setcookie("qrstring", base64_encode($qrImage)); 
        //redirect to payment page for user to scan on .. 
        header('Location: ../qrpayment/'. $saleId); 
        die();
    }else{
        header('Location: ../paymentfailed'); 
        die();
    }


}

getQR();

?>