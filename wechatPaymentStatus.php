<?php 

function getStatus(){
    //request to server
    //payplus credentials
    $bizID = 29;
    $intToken = 'gpI3wXspE49xfYXUmmIoz9xRjEOxXzzVwPGDOsxDzuGFAa4xnM9X8dUceWCVQPTc'; 
    $time = time();
    $otn = $_COOKIE['otn'];
    $nonce = $rand = substr(md5(microtime()),rand(0,26),16);; //16 random chars
    $wechatAPI = 'https://api.payplus.co.nz/v3/rawWxcheck/';
    //https://api.payplus.co.nz/v3/rawWxcheck/biz_id/otn/time/nonce/sign
    $alipayAPI = 'https://api.payplus.co.nz/v3/rawAlipaycheck/';
    $cont=trim(utf8_encode($bizID.$otn.$time.$nonce.$intToken));
    $sign=urlencode(base64_encode(sha1($cont)));
    //send request to server fetching QR code string
    $s = curl_init(); 
    $uri = $wechatAPI . '/'. $bizID . '/' .$otn . '/'  . $time . '/' . $nonce . '/' . $sign;
    curl_setopt($s,CURLOPT_URL, $uri);
    curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
    $result = curl_exec($s);
    curl_close($s); 
    $res = json_decode($result, true)[0]; //get json respons from api
    //return $res;
    if($res['result_code'] == 'SUCCESS'){
        //check if status paid or not and return status
        return $res['state']; 
    }else{
        return 'error';
    }
}

echo getStatus();
 
?>