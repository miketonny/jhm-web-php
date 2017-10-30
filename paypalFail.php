<?php session_start();
include("include/config.php");
include("include/functions.php");

// chk orderid
if(chkParam1($_GET['ordId']) && isset($_GET['ordId'])){	$orderId = $_GET['ordId']; }
elseif(chkParam1($_SESSION['order_id']) && isset($_SESSION['order_id'])){ $orderId = $_SESSION['order_id']; }
else{ redirect(siteUrl); }
unset($_SESSION['order_id']);

$del_od = mysql_query("DELETE FROM tbl_order WHERE order_id = '".$orderId."'");
$del_od = mysql_query("DELETE FROM tbl_order_item WHERE od_id = '".$orderId."'");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: <?php echo siteName; ?> ::</title>
<link href="<?php echo siteUrl; ?>style@jhmshop.css" rel="stylesheet" type="text/css" />
<link href="<?php echo siteUrl; ?>css/styleCheckout.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
<?php include("include/header.php"); ?>
<?php include("navigation.php"); ?>
<div id="mainWrapper">
	<div id="innerWrapper" class="pagename">
        <h1>Transaction Cancelled</h1>
        <table width="550" border="0" align="center" cellpadding="5" cellspacing="1" class="entryTable">
            <tr class="entryTableHeader">
                <td>Payment Process not Completed.</td>
            </tr>
            <tr>
                <td width="150" class="label" align="center">Some Problem in Placing Order, </td>
            </tr>
            <tr>
                <td class="label" align="center">
                	<button type="button" onclick="window.location='<?php echo siteUrl; ?>product-search/'">Continue Shopping</button>
                    <button type="button" onclick="window.location='<?php echo siteUrl; ?>'">Go to Home</button>
                </td>
            </tr>
        </table>
	</div>
</div>
<?php include("include/footer.php"); ?>
<script> setTimeout(function(){ window.location='<?php echo siteUrl; ?>'; }, 7000); </script>
</body>
</html>