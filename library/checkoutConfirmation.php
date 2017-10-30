<?php $shippingCost = 0;
/*
Line 1 : Make sure this file is included instead of requested directly
Line 2 : Check if step is defined and the value is two
Line 3 : The POST request must come from this page but the value of step is one
*/
if (!isset($_GET['step']) || (int)$_GET['step'] != 2) { exit; }

$errorMessage = '&nbsp;';

/*
 Make sure all the required field exist is $_POST and the value is not empty
 Note: txtShippingAddress2 and txtPaymentAddress2 are optional
*/
$requiredField = array('txtShippingFirstName'	, 'txtShippingLastName'	, 'txtShippingAddress1',
						'txtShippingPhone'		, 'txtShippingState'	, 'txtShippingCity',
						'txtShippingPostalCode'	, 'txtPaymentFirstName'	, 'txtPaymentLastName',
						'txtPaymentAddress1'	, 'txtPaymentPhone'		, 'txtPaymentState',
						'txtPaymentCity'		, 'txtPaymentPostalCode');
					   
if (!checkRequiredPost($requiredField)){ $errorMessage = 'Input not complete'; }
if (empty($requiredField)){ redirect(siteUrl.'checkOut/1/'); }

$sql_cart = "SELECT tbl_cart.cart_id, tbl_cart.promo_id, tbl_cart.product_id, tbl_cart.color_id, tbl_cart.qty, tbl_cart.product_price, tbl_cart.product_promo_price, tbl_product.product_name, tbl_product_color.color_code
FROM tbl_cart
LEFT JOIN tbl_product ON tbl_product.product_id = tbl_cart.product_id
LEFT JOIN tbl_product_color ON tbl_product_color.color_id = tbl_cart.color_id
WHERE tbl_cart.user_id = $user_id ORDER BY tbl_cart.datetime DESC";
$rs_cart = mysql_query($sql_cart, $con);
?>
<p id="errorMessage"><?php echo $errorMessage; ?></p>
<form action="<?php echo siteUrl."checkOut/3/"; ?>" method="post" name="frmCheckout" id="frmCheckout">
	<?php if ($_POST['optPayment'] == 'paypal'){ echo '<p>&nbsp;</p>'; } ?>
	<table width="550" border="0" align="center" cellpadding="5" cellspacing="1" class="infoTable">
		<tr> 
			<td><b>Step 2 Of 3 : Confirm Order </b></td>
		</tr>
	</table>
    <table width="550" border="0" align="center" cellpadding="5" cellspacing="1" class="infoTable">
        <tr class="infoTableHeader"> 
            <td colspan="3">Ordered Item</td>
        </tr>
        <tr class="label"> 
            <td>Item</td>
            <td>Unit Price</td>
            <td>Total</td>
        </tr>
        <?php
		$cdate = date('Y-m-d H:i:s');
		$numItem  = mysql_num_rows($rs_cart);
		$subTotal = 0;
		while($pro_row = mysql_fetch_object($rs_cart)){
			/*$img = mysql_fetch_object(mysql_query("SELECT media_src, media_thumb FROM tbl_product_media WHERE media_type = 'img' AND product_id = '$pro_row->product_id' AND color_id = '$pro_row->color_id'", $con));*/
			$price = checkPromoValidity($pro_row, $con);
			$total = $pro_row->qty * $price;

			$subTotal += $pro_row->qty * $price;
		?>
        <tr class="content"> 
            <td class="content"><?php echo $pro_row->qty." x ".$pro_row->product_name; ?></td>
            <td align="right">$ <?php echo number_format($price); ?></td>
            <td align="right">$ <?php echo number_format($pro_row->qty * $price); ?></td>
        </tr>
        <?php } ?>
        <tr class="content strong">
            <td colspan="2" align="right">Sub-total</td>
            <td align="right">$ <?php echo number_format($subTotal); ?></td>
        </tr>
        <tr class="content strong">
            <td colspan="2" align="right">Shipping</td>
            <td align="right">$ <?php echo number_format($shippingCost); ?></td>
        </tr>
        <tr class="content strong">
            <td colspan="2" align="right">Total</td>
            <td align="right">$ <?php echo number_format($shippingCost + $subTotal); ?> <input type="hidden" name="totalCartAmount" value="<?php echo $shopConfig['shippingCost'] + $subTotal; ?>" /> </td>
        </tr>
    </table>
    <p>&nbsp;</p>
    <table width="550" border="0" align="center" cellpadding="5" cellspacing="1" class="infoTable">
        <tr class="infoTableHeader"> 
            <td colspan="2">Shipping Information</td>
        </tr>
        <tr> 
            <td width="150" class="label">First Name</td>
            <td class="content"><?php echo $_POST['txtShippingFirstName']; ?>
                <input name="hidShippingFirstName" type="hidden" id="hidShippingFirstName" value="<?php echo $_POST['txtShippingFirstName']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="label">Last Name</td>
            <td class="content"><?php echo $_POST['txtShippingLastName']; ?>
                <input name="hidShippingLastName" type="hidden" id="hidShippingLastName" value="<?php echo $_POST['txtShippingLastName']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="label">Address1</td>
            <td class="content"><?php echo $_POST['txtShippingAddress1']; ?>
                <input name="hidShippingAddress1" type="hidden" id="hidShippingAddress1" value="<?php echo $_POST['txtShippingAddress1']; ?>"><input name="txtemail" type="hidden" id="txtemail" value="<?php echo $_POST['txtemail']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="label">Address2</td>
            <td class="content"><?php echo $_POST['txtShippingAddress2']; ?>
                <input name="hidShippingAddress2" type="hidden" id="hidShippingAddress2" value="<?php echo $_POST['txtShippingAddress2']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="label">Phone Number</td>
            <td class="content"><?php echo $_POST['txtShippingPhone'];  ?>
                <input name="hidShippingPhone" type="hidden" id="hidShippingPhone" value="<?php echo $_POST['txtShippingPhone']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="label">Province / State</td>
            <td class="content"><?php echo $_POST['txtShippingState']; ?> <input name="hidShippingState" type="hidden" id="hidShippingState" value="<?php echo $_POST['txtShippingState']; ?>" ></td>
        </tr>
        <tr> 
            <td width="150" class="label">City</td>
            <td class="content"><?php echo $_POST['txtShippingCity']; ?>
                <input name="hidShippingCity" type="hidden" id="hidShippingCity" value="<?php echo $_POST['txtShippingCity']; ?>" ></td>
        </tr>
        <tr> 
            <td width="150" class="label">Postal Code</td>
            <td class="content"><?php echo $_POST['txtShippingPostalCode']; ?>
                <input name="hidShippingPostalCode" type="hidden" id="hidShippingPostalCode" value="<?php echo $_POST['txtShippingPostalCode']; ?>"></td>
        </tr>
    </table>
    <p>&nbsp;</p>
    <table width="550" border="0" align="center" cellpadding="5" cellspacing="1" class="infoTable">
        <tr class="infoTableHeader"> 
            <td colspan="2">Payment Information</td>
        </tr>
        <tr> 
            <td width="150" class="label">First Name</td>
            <td class="content"><?php echo $_POST['txtPaymentFirstName']; ?>
                <input name="hidPaymentFirstName" type="hidden" id="hidPaymentFirstName" value="<?php echo $_POST['txtPaymentFirstName']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="label">Last Name</td>
            <td class="content"><?php echo $_POST['txtPaymentLastName']; ?>
                <input name="hidPaymentLastName" type="hidden" id="hidPaymentLastName" value="<?php echo $_POST['txtPaymentLastName']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="label">Address1</td>
            <td class="content"><?php echo $_POST['txtPaymentAddress1']; ?>
                <input name="hidPaymentAddress1" type="hidden" id="hidPaymentAddress1" value="<?php echo $_POST['txtPaymentAddress1']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="label">Address2</td>
            <td class="content"><?php echo $_POST['txtPaymentAddress2']; ?> <input name="hidPaymentAddress2" type="hidden" id="hidPaymentAddress2" value="<?php echo $_POST['txtPaymentAddress2']; ?>"> 
            </td>
        </tr>
        <tr> 
            <td width="150" class="label">Phone Number</td>
            <td class="content"><?php echo $_POST['txtPaymentPhone'];  ?> <input name="hidPaymentPhone" type="hidden" id="hidPaymentPhone" value="<?php echo $_POST['txtPaymentPhone']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="label">Province / State</td>
            <td class="content"><?php echo $_POST['txtPaymentState']; ?> <input name="hidPaymentState" type="hidden" id="hidPaymentState" value="<?php echo $_POST['txtPaymentState']; ?>" ></td>
        </tr>
        <tr> 
            <td width="150" class="label">City</td>
            <td class="content"><?php echo $_POST['txtPaymentCity']; ?>
                <input name="hidPaymentCity" type="hidden" id="hidPaymentCity" value="<?php echo $_POST['txtPaymentCity']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="label">Postal Code</td>
            <td class="content"><?php echo $_POST['txtPaymentPostalCode']; ?>
                <input name="hidPaymentPostalCode" type="hidden" id="hidPaymentPostalCode" value="<?php echo $_POST['txtPaymentPostalCode']; ?>"></td>
        </tr>
    </table>
    <p>&nbsp;</p>
    <table width="550" border="0" align="center" cellpadding="5" cellspacing="1" class="infoTable">
      <tr>
        <td width="150" class="infoTableHeader">Payment Method </td>
        <td class="content"><?php echo $_POST['optPayment'] == 'paypal' ? 'Paypal' : 'Cash on Delivery'; ?>
          <input name="hidPaymentMethod" type="hidden" id="hidPaymentMethod" value="<?php echo $_POST['optPayment']; ?>" />
        </tr>
    </table>
    <p>&nbsp;</p>
    <p align="center"> 
        <input name="btnBack" type="button" id="btnBack" value="&lt;&lt; Modify Shipping/Payment Info" onClick="window.location.href='<?php echo siteUrl."checkOut/1/"; ?>'" class="box">
        &nbsp;&nbsp; 
        <input name="btnConfirm" type="submit" id="btnConfirm" value="Confirm Order &gt;&gt;" class="box">
</form>
<p>&nbsp;</p>
