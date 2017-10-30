<?php session_start();
include("include/config.php");
include("include/functions.php");
chkParam($_SESSION['user'], siteUrl);
chkParam($_SESSION['order_id'], 'cart/');
$user_id = $_SESSION['user'];
$odId  = $_SESSION['order_id'];
/* get cart sum */
$sumOfCart = getCartValue($user_id, $con);
/* remember that if there are some changes in this code, than also change, on cart page , cart.php shipping */
$i = 1;
$grandTotal = 0;
$grandTotalOriginalPrice = 0;
$promotionDiscount = 0;
$promoCodeDiscount = 0;
$promoCodeChk = 'no';
$amGst = 0;
$cdate = date('Y-m-d H:i:s');
$cartQuery = "SELECT tbl_cart.*, tbl_product.product_name, tbl_product.product_description, tbl_product_color.color_code FROM tbl_cart
LEFT JOIN tbl_product ON tbl_product.product_id = tbl_cart.product_id
LEFT JOIN tbl_product_color ON tbl_product_color.color_id = tbl_cart.color_id
WHERE tbl_cart.user_id = '".$user_id."' ORDER BY tbl_cart.datetime DESC";
$pro_rs = mysql_query($cartQuery, $con);
$numCart = mysql_num_rows($pro_rs);
if($numCart > 0){
	while($pro_row = mysql_fetch_object($pro_rs)){
		include 'include/promotionCalc.php';
	}
}else{ /*echo 'Cart is Empty...';*/ }
/* remember that if there are some changes in this code, than also change, on cart page , cart.php shipping */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: <?php echo siteName; ?> ::</title>
<link href="<?php echo siteUrl; ?>style@jhmshop.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
</head>
<body>
<?php include("include/header.php"); ?>
<?php include("navigation.php"); ?>

<script src="<?php echo siteUrl; ?>credit/ccvalidate.js" type="text/javascript"></script>
<link href="<?php echo siteUrl; ?>credit/ccvalidate.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(document).ready(function() {
	$('.cc-container').ccvalidate({ onvalidate: function(isValid) {
		if (!isValid) {
			alert('Incorrect Credit Card format');
			return false;
		}
	}
	});
	$('.cc-ddl-contents').css('display', 'none');
	$('.cc-ddl-header').toggle(function() {
		toggleContents($(this).parent().find('.cc-ddl-contents'));
	}, function() { toggleContents($(this).parent().find('.cc-ddl-contents')); });

	function toggleContents(el) {
		$('.cc-ddl-contents').css('display', 'none');
		if (el.css('display') == 'none') el.fadeIn("slow");
		else el.fadeOut("slow");
	}
	$('.cc-ddl-contents a').click(function() {
		$(this).parent().parent().find('.cc-ddl-o select').attr('selectedIndex', $('.cc-ddl-contents a').index(this));
		$(this).parent().parent().find('.cc-ddl-title').html($(this).html());
		$(this).parent().parent().find('.cc-ddl-contents').fadeOut("slow");
	});
	$(document).click(function() {
		$('.cc-ddl-contents').fadeOut("slow");
	});

	$('#check').click(function() {
		var cnumber = $('#cnumber').val();
		var type = $('#ctype').val();
		alert(isValidCreditCard(cnumber, type) ? 'Valid' : 'Invalid');
	});
});
</script>

<div id="mainWrapper" class=""><!----->
	<div id="innerWrapper" class="pagename">
		<div id="paymentoption" style="float:left;">
        	<strong class="heading_payment"> SELECT PAYMENT OPTION </strong>
            <ul style="float:left;">
                <li> <a href="#"> Credit Card </a> </li>
                <li> <a href="#"> Debit Card </a> </li>	
            </ul>
            
    			
            <div class="cc-container" >
                <div class="cc-header"> Please enter your billing information </div>
                <div class="cc-contents">
                    <form method="POST" id="form" action="<?php echo siteUrl; ?>action_model.php">
                        <table width="423" border="0" cellspacing="0" cellpadding="3" class="paymentcard">
                            <span class="payusing">
                                <strong>Pay using Credit Card</strong> 
                                <img src="<?php echo siteUrl; ?>images/pay_using.jpg" />
                            </span>
                            <tr>
                                <td><strong>Name on Card</strong></td>
                                <td><input type="text" name="fname" id="textfield2" required placeholder="Name on Card" /></td>
                            </tr>
                            <tr>
                                <td><strong>Card Type</strong></td>
                                <td>
                                    <select id="cc-types" name="ctype" class="cc-ddl-type">
                                        <option value="mcd">Master Card</option>
                                        <option value="vis">Visa Card</option>
                                        <option value="amx">American Express</option>
                                        <option value="dnr">Diner Club</option>
                                        <option value="dis">Discover</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Card Number</strong></td>
                                <td>
                                    <input type="text" name="card_no" id="card-number" class="large cc-card-number" autocomplete="off" />
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Expiry Date</strong></td>
                                <td>
                                    <select name="month" id="cc-month">
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                
                                    <select name="year" id="Select1">
                                        <option value="14">2014</option>
                                        <option value="15">2015</option>
                                        <option value="16">2016</option>
                                        <option value="17">2017</option>
                                        <option value="18">2018</option>
                                        <option value="19">2019</option>
                                        <option value="20">2020</option>
                                        <option value="21">2021</option>
                                        <option value="22">2022</option>
                                        <option value="23">2023</option>
                                        <option value="24">2024</option>
                                        <option value="25">2025</option>
                                        <option value="26">2026</option>
                                        <option value="27">2027</option>
                                        <option value="28">2028</option>
                                        <option value="29">2029</option>
                                        <option value="30">2030</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>CVV No.</strong></td>
                                <td>
                                    <input type="text" name="cvv" autocomplete="off" required />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="hidden" name="amount" value="<?php echo $total = $max * 2.49; ?>">
                                    <input type="submit" value="Checkout" class="cc-checkout" id="check-out" />
                                    <input type="hidden" name="action" value="processPayment" />
                                </td>
                            </tr>
                        </table>
                    </form>
                    <p class="payment_p"> By placing this order, you're agreeing to the Terms of Use of Jhm.co.nz </p>
                </div>
            </div>
        </div>
		<?php include 'include/cartRightBar.php'; ?>
        <div class="clr"> </div>
	</div>
</div>
<?php include("include/footer.php"); ?>
</body>
</html>