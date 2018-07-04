<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}?>
<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>
<style>
    /*wechat/alipay*/
    .vertical-align {
        display: flex;
        flex-flow: column nowrap;
        justify-content: center;
        align-content: center;
        align-items: stretch;
        height: 40rem;
        text-align: center;
    }
    #qr-payment>div:first-child{
        margin-bottom: 40px;
    }
    #qr-payment>div:first-child>h1{
        margin-bottom: 20px;
    }
    .qr-img-wrapper{
        position: relative;
        width: 235px;
        height: auto;
        min-height: 275px;
        margin: 0 auto;
        padding: 6px;
        border: 1px solid #d3d3d3;
        -webkit-box-shadow: 1px 1px 1px #ccc;
        box-shadow: 1px 1px 1px #ccc;
    }
    .qr-img-wrapper>div:first-child{
        position:relative;
    }
    .qr-img-wrapper>div:nth-child(2){
        padding-top:10px;
        padding-left:0;
        padding-right:0;
    }
    .qr-img-wrapper>div>img{
        width:222px;
        height:222px;
    }
    .qr-img-wrapper>div>img:nth-child(2){
        position:absolute;
        top:50%; 
        width: 40px;
        height: 40px;
        margin-left: -20px;
    }
    .qr-img-wrapper div div{
        padding:0;
    }
</style>
<script type="text/javascript">
$(document).ready(function() {
    const orderID = window.location.href.split('/').pop(); //order id is the last section in url 
    if(orderID === null || orderID === 'undefined'){
        return;
    }
    //runs a 10sec timer to check the payment status, once the payment has been made, it'll redirect to new success page
    setInterval(() => {
        console.log('fetching payment status ------------');
        $.get("../qrPaymentStatus.php", function(msg) {
                if(msg === 'NOTPAY'){
                    //stay on current page..
                    console.log('pending payment');
                }else if(msg === 'SUCCESS'){
                    //return to order page..
                    let rnd = Math.round(Math.random() * 10000000, 0);
                    window.location.replace(`../success/${rnd}${orderID}`);
                }else if(msg === 'error'){
                    console.log('error occured in fetch status');
                }   
        });  
    }, 10000); 
});
</script>

<div id="qr-payment" class="block-pt5 text-center">
    <div>
        <h1>Please scan below QR code for payment</h1>
        <h3>Scan to Pay: <?php echo '$' .(isset($_SESSION['totalAmt']) ? $_SESSION['totalAmt']: '0.00') ?></h3>
    </div>
    <div class="qr-img-wrapper">
        <div><img 
        src="data:image/png;base64,<?php echo (isset($_SESSION['QRString']) ? $_SESSION['QRString']: '') ?>" />
        <img style="background-color:white;" src="<?php echo ($_SESSION['type'] == 'wechat' ? '../images/wechatlogo.png' : 'https://t.alipayobjects.com/tfscom/T1Z5XfXdxmXXXXXXXX.png') ?>"/>
        </div>
        <div class="col-sm-12">  
            <div class="col-xs-3"><img src="https://t.alipayobjects.com/images/T1bdtfXfdiXXXXXXXX.png"/></div>
            <div class="col-xs-9">Scan with QR code scanner</div>
        </div>
    </div>
   
</div> 



<?php include("include/new_footer.php"); ?>
<?php include("include/new_bottom.php"); ?>