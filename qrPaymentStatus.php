<?php 
include 'include/config.php'; 
include 'include/functions.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}


echo getStatus($con);

function getStatus($con){
    //request to server
    //payplus credentials
    $bizID = 29;
    $intToken = 'gpI3wXspE49xfYXUmmIoz9xRjEOxXzzVwPGDOsxDzuGFAa4xnM9X8dUceWCVQPTc'; 
    $time = time();
    $otn = $_SESSION['otn'];
    $nonce = substr(md5(microtime()),rand(0,26),16);; //16 random chars
    if(!isset($_SESSION['type'])) return 'error'; 
    $type = $_SESSION['type'];
    $API; 
    //pattern /biz_id/fee_total/sale_id/title/time/nonce/sign'; 
    if($type == 'wechat'){
        $API = 'https://api.payplus.co.nz/v3/rawWxcheck';
    }else if($type == 'alipay'){
        $API = 'https://api.payplus.co.nz/v3/rawAlipaycheck';
    }
    $cont=trim(utf8_encode($bizID.$otn.$time.$nonce.$intToken));
    $sign=urlencode(base64_encode(sha1($cont)));
    //send request to server fetching QR code string
    $s = curl_init(); 
    $uri = $API . '/'. $bizID . '/' .$otn . '/'  . $time . '/' . $nonce . '/' . $sign;
    curl_setopt($s,CURLOPT_URL, $uri);
    curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
    $result = curl_exec($s);
    curl_close($s); 
    $res = json_decode($result, true)[0]; //get json respons from api
    //return $res;
    if($res['result_code'] == 'SUCCESS'){
        //check if status paid or not and return status
        if($res['state'] === 'SUCCESS'){
            return updateOrderStats($res['transaction_id'], $res['total_fee'], $con);
        }
        return $res['state']; 
    }else{
        return 'error';
    }
}

//TODO: update order status, clear cart etc.
function updateOrderStats($tranId, $total_fee, $con){
        if(!isset($_SESSION['orderId'])) return;
        $orderId = $_SESSION['orderId'];
        $boID = isset($_SESSION['backOrderId']) ? $_SESSION['backOrderId'] : 0; 
        $order = mysqli_fetch_object(mysqli_query($con, "select * from `tbl_order` WHERE order_id = '".$orderId."'"));
        // update order table
        $successTransactionUpdate = mysqli_query($con, "UPDATE tbl_order SET payment_status = 'Paid', trnid = '".$tranId."', trn_ref = '".$total_fee."' WHERE order_id = '".$orderId."'"); // save bank auth code into order in case needed

        if($successTransactionUpdate){
            //post payment order updates, i.e. update stock on hand to deduct stocks.
            sendOrderMail($order->user_id, $orderId, $con);
            $orderRs = mysqli_query($con, "SELECT od_point_spend FROM tbl_order WHERE order_id = $orderId");
            $orderRow1 = mysqli_fetch_object($orderRs);
            if(isset($orderRow1->od_point_spend) && $orderRow1->od_point_spend > 0){
                //TODO: bug here $user_id not exists
                $pointQ = "INSERT INTO `tbl_user_point` (`order_id`, `user_id`, `point`, `datetime`) VALUES('$orderId', '$user_id', '-$orderRow1->od_point_spend', '".date('c')."')";
                mysqli_query($con, $pointQ);
            }
            //successful payment, deduct stock from order/backorder,update qty in product price
            $ordUpdate = mysqli_query($con, "UPDATE tbl_product_price tpp INNER JOIN `tbl_order_item` toi ON toi.product_id = tpp.product_id AND toi.color_id = tpp.color_id SET qty = qty - toi.od_qty WHERE order_id = '$orderId'");
            //set up back order
            if ($boID != 0) {
                $boUpdate = mysqli_query($con, "UPDATE tbl_product_price tpp INNER JOIN `tbl_order_item` toi ON toi.product_id = tpp.product_id AND toi.color_id = tpp.color_id SET backorder_qty = backorder_qty - toi.od_qty WHERE order_id = '$TxnData3'");
            }
            // delete cart product at last
            $delCart = mysqli_query($con, "DELETE FROM tbl_cart WHERE user_id = '".$order->user_id."'"); 
            // $token = fetchRandomToken();
            // redirect(siteUrl.'success/'.$token.$orderId); die(); //now return to order success page and finish
            return "SUCCESS";
        }
        else{
            return "Error processing wechat/alipay payment.";
        }
}

 
?>