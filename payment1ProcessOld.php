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
<div id="mainWrapper">
	<div id="innerWrapper" class="pagename">
    	
        
        
        
        
        
        
        
    <!-- card page -->
    <!--<script src="credit/jquery-1.4.4.min.js" type="text/javascript"></script>-->

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

    <style type="text/css">
      
        #wrapper
        {
            width: 1024px;
            height: 670px;
            margin: auto;
                       -moz-border-radius: 8px;
            -webkit-border-radius: 8px;
            border-radius: 8px;
            -moz-border-radius: 8px;
            -webkit-border-radius: 8px;
            border-radius: 8px;
        }
        #wrapper #header
        {
            /*width: 99%;*/
            height: 110px;
            color: White;
            font-size: 24px;
            font-weight: bold;
            padding-left: 20px;
            padding-top: 40px;
            -moz-border-radius-topleft: 8px;
            -webkit-border-top-left-radius: 8px;
            border-top-left-radius: 8px;
            -moz-border-radius-topright: 8px;
            -webkit-border-top-right-radius: 8px;
            border-top-right-radius: 8px;
            background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#747577), to(#363739));
            background-image: -moz-linear-gradient(#747577, #363739);
            background-image: -webkit-linear-gradient(#747577, #363739);
            background-image: -o-linear-gradient(#747577, #363739);
            filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#747577, endColorstr=#363739)";
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#747577, endColorstr=#363739)";
        }
        #wrapper #menu-bar
        {
            width: 100%;
            height: 29px;
            padding-top: 4px;
            background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#E5E5E5), to(#CFCFCF));
            background-image: -moz-linear-gradient(#E5E5E5, #CFCFCF);
            background-image: -webkit-linear-gradient(#E5E5E5, #CFCFCF);
            background-image: -o-linear-gradient(#E5E5E5, #CFCFCF);
            filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#E5E5E5, endColorstr=#CFCFCF)";
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#E5E5E5, endColorstr=#CFCFCF)";
            border-bottom: solid 1px #747577;
        }
        #wrapper #contents
        {
            width: 100%;
            height: 540px;
            padding-top: 60px;
        }

div.AuthorizeNetSeal{
     margin:31px auto!important;
}
     
        #wrapper #footer
        {
            width: 100%;
            height: 50px;
            background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#747577), to(#363739));
            background-image: -moz-linear-gradient(#747577, #363739);
            background-image: -webkit-linear-gradient(#747577, #363739);
            background-image: -o-linear-gradient(#747577, #363739);
            filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#747577, endColorstr=#363739)";
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#747577, endColorstr=#363739)";
            -moz-border-radius-bottomleft: 8px;
            -webkit-border-bottom-left-radius: 8px;
            border-bottom-left-radius: 8px;
            -moz-border-radius-bottomright: 8px;
            -webkit-border-bottom-right-radius: 8px;
            border-bottom-right-radius: 8px;
            border-top: solid 1px #747577;
        }
        #menu-bar ul
        {
            list-style: none;
            margin: 0;
            padding: 0;
            margin-left: 4px;
        }
        #menu-bar ul li
        {
            float: left;
            display: inline-block;
            padding: 4px;
        }
        #menu-bar ul li a, #menu-bar ul li a:active, #menu-bar ul li a:visited
        {
            text-decoration: none;
            color: #747577;
        }
        #menu-bar ul li a:hover
        {
            text-decoration: underline;
            color: #747577;
        }
        #footer div
        {
            padding-top: 14px;
            width: 180px;
            margin: auto;
            color: White;
        }
        #footer div a, #footer div a:active, #footer div a:visited
        {
            text-decoration: none;
            color: white;
        }
        #footer div a:hover
        {
            text-decoration: underline;
            color: white;
        }
        .cc-container
        {
            margin: auto;
        }
		input[type="text"]{ width:57px; }
    </style>

    <form method="POST" id="form" action="<?php echo siteUrl; ?>action_model.php">
    <div id="wrapper">
        <div id="contents">
            <div class="cc-container" style="height:329px;">
                <div class="cc-header">
                    Please enter your billing information</div>
                <div class="cc-contents" style="height: 225px;">
                    <table cellpadding="4" cellspacing="0">
                        <tr>
                            <td>
                                <label>First Name</label>
                            </td>
                            <td>
                                <input type="text" name="fname" required="required" class="large" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Last Name</label>
                            </td>
                            <td>
                                <input type="text" name="lname" required="required" class="large" />
                            </td>
                        </tr>
                        <!--<tr>
                            <td>
                                <label>Address</label>
                            </td>
                            <td>
                                <textarea name="address" required="required"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>State</label>
                            </td>
                            <td>
                                <input type="text" name="state" required="required" class="large" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Zip</label>
                            </td>
                            <td>
                                <input type="text" name="zip" required="required" class="large" />
                            </td>
                        </tr>-->
                        <tr>
                            <td style="width: 97px">
                                <label>
                                    Card Type</label>
                            </td>
                            <td style="width: 270px">
                                <div class="cc-ddl" style="display:block;">
                                    <div class="cc-ddl-o" style="display:block;">
                                        <select id="cc-types" name="ctype" class="cc-ddl-type">
                                            <option value="mcd">Master Card</option>
                                            <option value="vis">Visa Card</option>
                                            <option value="amx">American Express</option>
                                            <option value="dnr">Diner Club</option>
                                            <option value="dis">Discover</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    Card Number</label>
                            </td>
                            <td>
                                <input type="text" name="card_no" id="card-number" class="large cc-card-number" autocomplete="off" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    Expires on</label>
                            </td>
                            <td>
                                <div style="width: 110px; float: left;">
                                    <div>
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
                                    </div>
                                </div>
                                <div style="width: 80px; float: left; margin-left: 4px;">
                                    <div>
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
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    CVV</label>
                            </td>
                            <td>
                                <input type="text" name="cvv" class="small" autocomplete="off" />
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;
                                
                            </td>
                            <td>
                            	<input type="hidden" name="amount" value="<?php echo $total = $max * 2.49; ?>">
                                <input type="submit" value="Checkout" class="cc-checkout" id="check-out" />
                                <input type="hidden" name="action" value="processPayment" />
                            </td>
                        </tr>
                    </table>


                </div>
            </div>
		</div>
        </div>
    </div>
    </form>
<!-- acrd foem  or content end ,,,,,,,,,,,,,,,,,,,, or abi right bar hide kra h,-->
        
        
        
<div style="clear:both;"></div>
        
    </div>
</div>
<?php include("include/footer.php"); ?>
</body>
</html>
        
        <?php /*
        
        
        
        <!-- right bar start -->
        <div id="cart_checkout">
        	<h2> Order Summary </h2>
            <?php if($numCart > 0){ ?>
			<form action="<?php echo siteUrl; ?>action_model.php" method="post" onsubmit="return chkPromoCode();">
				<ul style="font-size:14px;">
					<li style="display:none;">
						<h4 style="float:left;"> Coupons </h4> <br /><br /><br />
                        <?php if($promoCodeDiscount == 0 && $promoCodeChk == 'no'){ ?>
                            <p> <input type="text" name="promoCode" id="promoCode" required placeholder="Enter Promo Code" autocomplete="off" /> </p>
                            <input type="submit" value="APPLY" />
                        <?php }else{ ?>
                        	<input type="button" value="REMOVE PROMO CODE" onclick="removePromoCode();" />
                        <?php } ?>
					</li>
					
					<li>
						<h3> Price Details </h3>
						<h4> Cart Total<strong>$ <?php echo number_format((float)$grandTotalOriginalPrice, 2, '.', ''); ?></strong> </h4>
                        <h4> Cart Discount <strong style="color:#F33;">- $ <?php echo number_format((float)$promotionDiscount, 2, '.', ''); ?></strong> </h4>
                        <h4> Promo Code Discount <strong style="color:#F33;">- $
						<?php echo number_format((float)$promoCodeDiscount, 2, '.', ''); ?></strong> </h4>
                    </li>
                    
                    <li>
                    	<h4> Sub Total <strong> $
						<?php $grandPlusPromotion = $grandTotalOriginalPrice - ($promotionDiscount + $promoCodeDiscount);
						echo number_format((float)$grandPlusPromotion, 2, '.', '');
						?></strong> </h4>
                    </li>
                    
                    <?php $delCharge = 0; $gst = 15; // change it only later if any changes  ?>
					<li>
                    	<h3> Other Charges </h3>
						<h4> GST (<?php echo $gst; ?>%) <strong>$
						<?php $amGst = round(($grandPlusPromotion * $gst) / 100, 2);
						echo number_format((float)$amGst, 2, '.', '');
						?></strong> </h4>
						<h4> Delivery Charges <span style="color:#693">FREE</span> <strong>$ <?php $amDel = (($grandPlusPromotion * $delCharge) / 100);
						echo number_format((float)$amDel, 2, '.', '');
						?></strong> </h4>
					</li>
                    
                    <li>
                    	<h2> Total <strong>$ <?php $finalAmount = $grandPlusPromotion + $amGst + $amDel;
						echo number_format((float)$finalAmount, 2, '.', '');
						?></strong> </h2>
                    	<?php //<input type="button" value="GO TO NEXT STEP >>" class="checkout" onClick="window.location='<?php echo siteUrl."shipping/"; ?>'" /> ?>
                    </li>
				</ul>
				<input type="hidden" name="cartValue" value="<?php echo round($grandPlusPromotion, 2); ?>" />
				<input type="hidden" name="action" value="applyPromo" />
            </form>
            <?php }else{ echo 'Cart is Empty'; }
            // show user and address details
			$orderShipRs = exec_query("SELECT * FROM tbl_order WHERE order_id = '$odId'", $con);
			$orderShipRow = mysql_fetch_object($orderShipRs);
			?>
            <style> #noBord ul li{ border:none; padding:0px; } </style>
            <div id="noBord">
                <ul>
                    <li><h2>User & Shipping Details</h2></li>
                    <li>Name : <?php echo $orderShipRow->od_shipping_first_name.' '.$orderShipRow->od_shipping_last_name; ?></li>
                    <li>Locality : <?php echo $orderShipRow->od_shipping_locality; ?></li>
                    <li>Address : <?php echo $orderShipRow->od_shipping_address; ?></li>
                    <li>City, State : <?php echo $orderShipRow->od_shipping_city.', '.$orderShipRow->od_shipping_state; ?></li>
                    <li>Postal Code : <?php echo $orderShipRow->od_shipping_postal_code; ?></li>
                    <li>Address Type : <?php echo $orderShipRow->od_shipping_address_type; ?></li>
                    <li>Phone : <?php echo $orderShipRow->od_shipping_phone;
						if($orderShipRow->od_shipping_alt_phone != ''){ echo ' ('.$orderShipRow->od_shipping_alt_phone.')'; } ?>
                    </li>
                </ul>
            </div>
        </div>
        <!-- right bar end -->
        <div style="clear:both;"></div>
        
    </div>
</div>
<?php echo include("include/footer.php"); ?>
</body>
</html>*/ ?>