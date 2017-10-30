<?php if (!isset($orderId)) { redirect(siteUrl); exit; } ?>
<center style="margin-top: 82px;">
    <p>&nbsp;</p>
    <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="333333"><br/>You will be redirect in few seconds...</font></p>
</center>
<?php
chkParam($_SESSION['user'], siteUrl);
if(isset($_SESSION['trnid'])){ unset($_SESSION['trnid']); }
$_SESSION['trnid'] = rand(10000000, 99999999);
$trnid = $_SESSION['trnid'];

if(isset($orderId) && chkParam1($orderId)){
	$orderDetail = mysql_fetch_object(mysql_query("SELECT * FROM tbl_order WHERE order_id = '$orderId'"));
	// update trn id in order table
	$update = "UPDATE tbl_order SET trnid = '".$_SESSION['trnid']."' WHERE order_id = '$orderId'";
	if(exec_query($update, $con) && chkParam1($orderDetail->amount)){
		$paypal = paypalId;
		?>
		<form id="payp" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" style="text-align:center;">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="<?php echo $paypal; ?>">
			<input type="hidden" name="lc" value="IN">
			<input type="hidden" name="item_name" value="JHM Shop Order Payment">
			<input type="hidden" name="item_number" value="Item Id">
			<input type="hidden" name="amount" value="<?php echo $orderDetail->amount; ?>">
			<input type="hidden" name="currency_code" value="USD">
			<input type="hidden" name="button_subtype" value="services">
			<input type="hidden" name="no_note" value="0">
			<input type="hidden" name="return" value="<?php echo siteUrl; ?>success/<?php echo $user_id; ?>/<?php echo $trnid; ?>">
			<input type="hidden" name="cancel_return" value="<?php echo siteUrl; ?>failure/<?php echo $user_id; ?>/<?php echo $trnid; ?>">
			<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
			<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
		</form>
		<script> document.getElementById('payp').submit(); </script>
		<?php
	}
	else{
		setMessage('Failed, Some error occured. Try again later.', 'alert alert-error');
	}
}
else{
	setMessage('Failed, Some error occured. Try again later.', 'alert alert-error');
}
redirect(siteUrl); ?>