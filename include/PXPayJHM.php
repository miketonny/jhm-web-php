<?php
include "PxPay_Curl.inc.php";
$PxPay_Url    = "https://sec.paymentexpress.com/pxaccess/pxpay.aspx";
$PxPay_Userid = "JHMLimitedPxP"; #Important! Update with your UserId
$PxPay_Key    =  "93cb5bdaf2a96116aa7eeb27d6ccbe32ce6bfb7409eec52611dcd38ab718a688"; #Important! Update with your Key

$pxpay = new PxPay_Curl( $PxPay_Url, $PxPay_Userid, $PxPay_Key );

if (isset($_REQUEST["result"]))
{
    # this is a redirection from the payments page.
    print_result();
}
else
{
    # redirect to DPS payments page.
    process_request($pxpay);
}

//function process_request($amount, $orderId){
function process_request($pxpay){
	unset($_SESSION['order_id']);
    # this is a post back -- redirect to payments page.
    include 'config.php';
    $orderId = $_REQUEST['orderId'];
    $boId = $_REQUEST['backordId'];
    $orderNo = getOrderId($orderId, $con);
    $request = new PxPayRequest();

    # the following variables are read from the form
    $MerchantReference = $orderNo;
    $Address1 = $_REQUEST["billAddress"];
    $Address2 = $_REQUEST["bill_city"];
    $AmountInput = $_REQUEST["totalAmt"];

    #Generate a unique identifier for the transaction
    $TxnId = $orderId;  //use JHM order unique iD

    #Set PxPay properties
    $request->setMerchantReference($MerchantReference);
    $request->setAmountInput($AmountInput);
    $request->setTxnData1($Address1);
    $request->setTxnData2($Address2);
    $request->setTxnData3($boId);   //hacky
    $request->setTxnType("Purchase");
    $request->setCurrencyInput("NZD");
    $request->setEmailAddress($_REQUEST["bill_email"]);
    // $request->setUrlFail("https://www.jhm.co.nz/include/PXPayJHM.php");			# can be a dedicated failure page
    // $request->setUrlSuccess("https://www.jhm.co.nz/include/PXPayJHM.php");			# can be a dedicated success page
    $request->setUrlFail("http://localhost/jhm/include/PXPayJHM.php");           # debug
    $request->setUrlSuccess("http://localhost/jhm/include/PXPayJHM.php");          # debug
    $request->setTxnId($TxnId);

    #The following properties are not used in this case
    # $request->setEnableAddBillCard($EnableAddBillCard);
    # $request->setBillingId($BillingId);
    # $request->setOpt($Opt);



    #Call makeRequest function to obtain input XML
    $request_string = $pxpay->makeRequest($request);

    #Obtain output XML
    $response = new MifMessage($request_string);

    #Parse output XML
    $url = $response->get_element_text("URI");
    $valid = $response->get_attribute("valid");

    #Redirect to payment page
    header("Location: ".$url);
}

function print_result()
{
    session_start();    //resume session
    include 'config.php';
    include 'functions.php';

    global $pxpay;
    $enc_hex = $_REQUEST["result"];
    #getResponse method in PxPay object returns PxPayResponse object
    #which encapsulates all the response data
    $rsp = $pxpay->getResponse($enc_hex);
    # the following are the fields available in the PxPayResponse object
    $Success           = $rsp->getSuccess();   # =1 when request succeeds
    $AmountSettlement  = $rsp->getAmountSettlement();
    $AuthCode          = $rsp->getAuthCode();  # from bank
    $CardName          = $rsp->getCardName();  # e.g. "Visa"
    $CardNumber        = $rsp->getCardNumber(); # Truncated card number
    $DateExpiry        = $rsp->getDateExpiry(); # in mmyy format
    $DpsBillingId      = $rsp->getDpsBillingId();
    $BillingId    	 = $rsp->getBillingId();
    $CardHolderName    = $rsp->getCardHolderName();
    $DpsTxnRef	     = $rsp->getDpsTxnRef();
    $TxnType           = $rsp->getTxnType();
    $TxnData1          = $rsp->getTxnData1();
    $TxnData2          = $rsp->getTxnData2();
    $TxnData3          = $rsp->getTxnData3();
    $CurrencySettlement= $rsp->getCurrencySettlement();
    $ClientInfo        = $rsp->getClientInfo(); # The IP address of the user who submitted the transaction
    $TxnId             = $rsp->getTxnId();
    $CurrencyInput     = $rsp->getCurrencyInput();
    $EmailAddress      = $rsp->getEmailAddress();
    $MerchantReference = $rsp->getMerchantReference();
    $ResponseText		 = $rsp->getResponseText();
    $TxnMac            = $rsp->getTxnMac(); # An indication as to the uniqueness of a card used in relation to others

    // $debugSuccess = 1;
    if ($Success == "1")
    {
        //$result = "The transaction was approved.";
        //$user_id = $_SESSION['user'];
        $order = mysqli_fetch_object(mysqli_query($con, "select * from `tbl_order` WHERE order_id = '".$TxnId."'"));
        // update order table
        $successTransactionUpdate = mysqli_query($con, "UPDATE tbl_order SET payment_status = 'Paid', trnid = '".$DpsTxnRef."', trn_ref = '".$AuthCode."' WHERE order_id = '".$TxnId."'"); // save bank auth code into order in case needed

        if($successTransactionUpdate){
            //post payment order updates, i.e. update stock on hand to deduct stocks.
            sendOrderMail($order->user_id, $TxnId, $con);
            $orderRs = mysqli_query($con, "SELECT od_point_spend FROM tbl_order WHERE order_id = $TxnId");
            $orderRow1 = mysqli_fetch_object($orderRs);
            if(isset($orderRow1->od_point_spend) && $orderRow1->od_point_spend > 0){
                $pointQ = "INSERT INTO `tbl_user_point` (`order_id`, `user_id`, `point`, `datetime`) VALUES('$TxnId', '$user_id', '-$orderRow1->od_point_spend', '".date('c')."')";
                mysqli_query($con, $pointQ);
            }
            //successful payment, deduct stock from order/backorder,update qty in product price
            $ordUpdate = mysqli_query($con, "UPDATE tbl_product_price tpp INNER JOIN `tbl_order_item` toi ON toi.product_id = tpp.product_id AND toi.color_id = tpp.color_id SET qty = qty - toi.od_qty WHERE order_id = '$TxnId'");
            //set up back order
            if ($TxnData3 != null && $TxnData3 != '0') {
                $boUpdate = mysqli_query($con, "UPDATE tbl_product_price tpp INNER JOIN `tbl_order_item` toi ON toi.product_id = tpp.product_id AND toi.color_id = tpp.color_id SET backorder_qty = backorder_qty - toi.od_qty WHERE order_id = '$TxnData3'");
            }
            // delete cart product at last
            $delCart = mysqli_query($con, "DELETE FROM tbl_cart WHERE user_id = '".$order->user_id."'");
            $token = fetchRandomToken();
            redirect(siteUrl.'success/'.$token.$TxnId); die(); //now return to order success page and finish
        }
        else{
            setMessage("Payment Process Successfully Completed, But Some Error Occured While Processing Order.", 'alert alert-success');
        }
    }
    else
    {
        //$result = "The transaction was declined.";
        mysqli_query($con, "DELETE FROM tbl_order WHERE order_id = '$TxnId'");
        mysqli_query($con, "DELETE FROM tbl_order_item WHERE order_id = '$TxnId'");
        if ($TxnData3 != null && $TxnData3 != '0') {    //remove back order if there's any as well
            mysqli_query($con, "DELETE FROM tbl_order_item WHERE order_id = '$TxnData3'");
            mysqli_query($con, "DELETE FROM tbl_order_item WHERE order_id = '$TxnData3'");
        }
        setMessage("Payment failed, please try again.", 'alert alert-error');
        logMessage($ResponseText, $con);
        redirect(siteUrl.'paymentfailed'); die();
    }

}
?>