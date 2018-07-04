<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JHM Shop</title>
<link href="<?php echo siteUrl; ?>style@jhmshop.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
</head>
<body>
<script type="text/javascript">
$(document).ready(function() {
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
    let orderID = getParameterByName('ordId');
    
    console.log(orderID);
    if(orderID === null || orderID === 'undefined'){
        return;
    }
    setInterval(() => {
        console.log('fetching payment status ------------');
        $.ajax({
            method: "GET",
            url: "wechatPaymentStatus.php",  
        }).done((msg) => { 
            if(msg === 'NOTPAY'){
                //stay on current page..
            }else if(msg === 'SUCCESS'){
                //return to order page..
                window.location.replace(`../success/${orderID}`);
            }
        });
  
    }, 10000); 
});
</script>

<div style="text-align:center;">
    <h2>Please scan below QR code for payment</h2>  
    <img 
    src="data:image/png;base64,<?php echo (isset($_COOKIE['qrstring']) ? $_COOKIE['qrstring']: '') ?>" />
</div> 
</body>
</html> 