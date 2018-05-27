<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>
<?php include_once("include/analyticstracking.php") ?>
<?php 
$isLogin = false;
$point = 0;
if(isset($_SESSION['user']) && $_SESSION['user'] != ''){
	$isLogin = true; $user_id = $_SESSION['user'];
	if(!isset($_SESSION['user_email'])){
		$emailRs = mysqli_query($con, "SELECT email FROM tbl_user WHERE user_id = '$user_id'");
		$email = mysqli_fetch_object($emailRs)->email;
	}
	else{
		$email = $_SESSION['user_email'];
	}
	$point = getUserPoint($user_id, $con);
}
elseif(isset($_SESSION['tempUser']) && $_SESSION['tempUser'] != ''){ $user_id = $_SESSION['tempUser']; }

/* get cart sum */
$sumOfCart = getCartValue($user_id, $con);

// get bill and ship info
if($isLogin){
	$oldOrderQ = "SELECT * FROM tbl_order WHERE user_id = '$user_id' AND od_shipping_postal_code != '' AND od_shipping_address != '' AND od_shipping_locality != '' GROUP BY od_shipping_postal_code, od_shipping_address, od_shipping_locality";
	$oldOrderRs = exec_query($oldOrderQ, $con);
}
?>



<style>
.addEffect{
	border: 1px solid #999;
	height:auto;
	overflow:visible;
}
.pad15{ padding: 15px; }
</style>

<section class="block-pt5">

    <div class="container">
    
     <div class="shopping-holder">
      <div class="col-sm-8">
      <div class="checkoutpage">
      <div class="check_out" style="float:left; width:100%; font-size:14px;">
        	<h3 class="pageheading"> Checkout </h3>
            <div class="chek-out1">
            	<!-- step 1 start -->
            	<button type="button" id="btn-1" class="btnAccordion checkoutmethod" onclick="openAccordion(1);">Checkout Method</button>
                <div class="bodyAccordion" id="accordion-1">
                	<div id="checkOutMethodBox" class="pad15">
					<?php if(!$isLogin){  ?>
                    <div class="check_out2" style="float:left; margin-right:40px;">
                    <h3 class="headingh3" style="width:363px; padding:10px 10px 10px 0px;">Checkout as a Guest or Register</h3>
                        <p>Register with us for future convenience:</p>
                        <ul style="margin:10px 0px;">
                        	<input type="radio" value="guest" id="checkout_guest" name="checkout_method">
                            <label> Checkout as Guest</label><br/>
                            <input type="radio" value="register" id="checkout_register" name="checkout_method">
                            <label> Register</label>
		              	</ul>
                        
                        
                        <div class="registerpoint">
                        <h4>Register and Save Time!</h4>
                        <p>Register with us for future convenience:</p>
                        <p>Fast and easy check out </p>
                        <p>Easy access to your order history and status</p>
                        </div>
                        
                        <div>
                            <button onclick="chkCheckoutType();" class="button button-cntinue" type="button" id="">Continue</button>
                        </div>
                    </div>
                    
                    <div id="checkoutlogin">
                        <h3 class="headingh3">Connect to your account</h3>
                        <!--<h4><strong>Already registered?</strong>  Please log in below</h4> -->
                        <ul>
                        	<input type="text" placeholder="Email Address" name="loginEmail" id="loginEmail" /><br/>
	                        <input type="password" placeholder="Password" name="loginPassword" id="loginPassword" />
                        </ul>
                        <div>
                            
                            <button onclick="doProcess(loginEmail.value, loginPassword.value, '<?php echo siteUrl; ?>')" class="button" type="button" style="width:270px !important; margin:6px 0px 0px 0px !important; ">Login</button><br />
                            <a >Forgot your password?</a>
                            <!-- onclick="chkLoginValues()" -->
                        </div>        
                    </div>
                    <div id="clr"></div>
                    <?php $var = ''; }else{ $var = 'login'; ?>
                    	<p>You are logged in as <strong><?php echo $email; ?></strong>,</p>
                        <a onclick="loginContinue();" class="button button-cntinue" href="#">Continue</a>
                    <?php } ?>
                    </div>
                </div>
                <!-- step 1 end -->
                
                <form action="<?php echo siteUrl; ?>action_model.php" method="post" id="chkoutForm">
                <!-- for future use --> <input type="hidden" id="isLogin" name="isLogin" value="<?php echo $var; ?>" />
                
                <!-- step 2 start -->
                <button type="button" id="btn-2" class="btnAccordion billinginfo" onclick="openAccordion(2);">Billing Information</button>
				<a name="accordion-2"></a>
                <div class="bodyAccordion" id="accordion-2">
                	<div class="pad15">
					<link rel="stylesheet" media="screen, projection" href="<?php echo siteUrl; ?>css/fancySelect.css">
						<script src="<?php echo siteUrl; ?>js/fancySelect.js"></script>
						<script> $(document).ready(function() { $('#addressBookBill, #addressBookShip').fancySelect(); }); </script>
                        
                        <div class="chkout-addrss" style="width:355px; float:right; ">
                        	<strong class="headingh3">Choose an address from billing address book</strong>
                            <select id="addressBookBill" name="addressBookBill" onchange="setAddressDataFromSelect(this.value, 'bill', '<?php echo siteUrl; ?>');" style="width:300px;">
                            	<option value="">- SELECT BILLING INFORMATION -</option>
                                <?php $option2 = '';
                                if($isLogin){
									while($oOrderRow = mysqli_fetch_object($oldOrderRs)){
										$oData = '';
										$oData = $oOrderRow->od_billing_first_name.' '.$oOrderRow->od_billing_last_name.', ';
										$oData .= $oOrderRow->od_billing_address.', '.$oOrderRow->od_billing_locality.', ';
										$oData .= $oOrderRow->od_billing_city.', '.$oOrderRow->od_billing_postal_code.', ';
										$oData .= 'Phone: '.$oOrderRow->od_billing_phone;
										echo '<option value="'.$oOrderRow->order_id.'">'.$oData.'</option>';
										
										$oData2 = '';
										$oData2 .= $oOrderRow->od_shipping_first_name.' '.$oOrderRow->od_shipping_last_name.', ';
										$oData2 .= $oOrderRow->od_shipping_address.', '.$oOrderRow->od_shipping_locality.', ';
										$oData2 .= $oOrderRow->od_shipping_city.', '.$oOrderRow->od_shipping_postal_code.', ';
										$oData2 .= 'Phone: '.$oOrderRow->od_shipping_phone;
										$option2 .= '<option value="'.$oOrderRow->order_id.'">'.$oData2.'</option>';
									}
								}?>
                            </select>
                        </div>
                        
						
                    	<table width="50%" border="0" cellspacing="0" cellpadding="5" id="billAddressTable" style="float:left;">
                            <tr>
                                <td colspan="2"><strong class="headingh3" >Enter a new address</strong></td>
                            </tr>
                            <tr>
                                <td width="28%">First name *</td>
                                <td width="72%"><input type="text" name="bill_first_name" id="bill_first_name" /></td>
                            </tr>
                            <tr>
                                <td>Last name *</td>
                                <td><input type="text" name="bill_last_name" id="bill_last_name" /></td>
                            </tr>
                            <tr>
                                <td>Email Address *</td>
                                <td><input type="text" name="bill_email" id="bill_email" value="<?php if($isLogin){ echo $email; } ?>" /></td>
                            </tr>
                            <tr class="hideRow" id="billPassRow">
							<?php if(!$isLogin){ ?>
                                <td>Password *</td>
                                <td><input type="text" name="bill_password" id="bill_password" /></td>
                            <?php } ?>
                            </tr>
                            <tr>
                                <td>Phone *</td>
                                <td><input type="text" name="bill_phone" id="bill_phone" /></td>
                            </tr>
                            <tr>
                                <td>Alternate Phone</td>
                                <td><input type="text" name="bill_alt_phone" id="bill_alt_phone" /></td>
                            </tr>
                            <tr>
                                <td valign="middle">Address *</td>
                                <td valign="top">
                                    <input type="text" name="billAddress" id="billAddress" />
                                </td>
                            </tr>
                            <tbody id="billAddressBlock">
                            <tr>
                                <td>Post Code *</td>
                                <td>
                                	<input type="text" name="bill_postal_code" id="bill_postal_code" data-geo="postal_code" />
                                    <input name="bill_lat" type="hidden" value="" id="bill_lat" data-geo="lat" >
                                    <input name="bill_lng" type="hidden" value="" id="bill_lng" data-geo="lng" >
                                </td>
                            </tr>
                            <tr>
                                <td>Suburb</td>
                                <td>
                                    <input type="text" name="bill_locality" id="bill_locality" />
                                </td><!-- sublocality -->
                            </tr>
                            <tr>
                                <td>City *</td>
                                <td><input type="text" name="bill_city" id="bill_city" data-geo="locality" /></td>
                            </tr>
                            </tbody>
                            <tr>
                            	<td colspan="2">
                                	<div><input type="radio" name="shipAddressType" id="shipToThis" value="bill" checked="checked" />
                                    <label> Ship to this Address</label></div>
                                    <div><input type="radio" name="shipAddressType" id="shipToOther" value="new" />
                                    <label> Ship to other Address</label></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
								<a class="button-cntinue button" onclick="chkBillData(isLogin.value, '<?php echo siteUrl; ?>');"  href="#accordion-3">Continue</a></td>
                            </tr>
                        </table>
						<div id="clr"></div>
                    </div>
                </div>
                <!-- step 2 end -->


                <!-- step 3 start -->
                <button type="button" id="btn-3" class="btnAccordion shippinginfo" onclick="openAccordion(3);">Shipping Information</button>
                <div class="bodyAccordion" id="accordion-3">
                	<div class="pad15">
                    	<table width="47%" border="0" cellspacing="0" cellpadding="5" id="shipAddressTable" style="float:left;">
                            <tr>
                                <td colspan="2"><strong>Enter a new address</strong></td>
                            </tr>
                            <tr>
                                <td width="41%">First name *</td>
                                <td width="59%"><input type="text" name="ship_first_name" id="ship_first_name" /></td>
                            </tr>
                            <tr>
                                <td>Last name *</td>
                                <td><input type="text" name="ship_last_name" id="ship_last_name" /></td>
                            </tr>
                            <tr>
                                <td>Phone *</td>
                                <td><input type="text" name="ship_phone" id="ship_phone" /></td>
                            </tr>
                            <tr>
                                <td>Alternate Phone</td>
                                <td><input type="text" name="ship_alt_phone" /></td>
                            </tr>
                            <tr>
                                <td valign="top">Address *</td>
                                <td valign="top">
                                    <input type="text" name="shipAddress" id="shipAddress" />
                                </td>
                            </tr>
                            <tbody id="shipAddressBlock">
                            <tr>
                                <td>Post Code *</td>
                                <td>
                                	<input type="text" name="ship_postal_code" id="ship_postal_code" data-geo="postal_code" />
                                    <input name="ship_lat" type="hidden" value="" id="ship_lat" data-geo="lat">
                                    <input name="ship_lng" type="hidden" value="" id="ship_lng" data-geo="lng">
                                </td>
                            </tr>
                            <tr>
                                <td>Suburb</td>
                                <td>
                                    <input type="text" name="ship_locality" id="ship_locality" />
                                </td><!-- sublocality -->
                            </tr>
                            <tr>
                                <td>City *</td>
                                <td><input type="text" name="ship_city" id="ship_city" data-geo="locality" /></td>
                            </tr>
                            </tbody>
                            <tr>
                            	<td colspan="2">
                                	<div><input type="checkbox" name="sameAsBilling" id="sameAsBilling" value="true" onclick="sameAsBillingFun(this.id, isLogin.value, '<?php echo siteUrl; ?>');" />
                                    <label>Same as Billing Information</label></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="button" value="Continue" class="button button-cntinue" onclick="chkShipData('<?php echo siteUrl; ?>');" /></td>
                            </tr>
                        </table>
                        
                        <div class="chkout-addrss" style="width:370px; float:right;">
                        	<strong class="headingh3">Choose an address from shipping address book</strong>
                            <select id="addressBookShip" name="addressBookShip" onchange="setAddressDataFromSelect(this.value, 'ship', '<?php echo siteUrl; ?>');" style="width:300px;">
                            	<option value="">- SELECT SHIPPING INFORMATION -</option>
                                <?php echo $option2; ?>
                            </select>
                        </div>
                        <div id="clr"></div>
                    </div>
                </div>
                <!-- step 3 end -->
                
                <!-- step 4 start -->
                <button type="button" id="btn-4" class="btnAccordion shipandhandling" onclick="openAccordion(4);">Shipping & Payment Method</button>
                <div class="bodyAccordion" id="accordion-4">
                	<div class="pad15">
                    	<h3>Choose Shipping Method</h3>
                    	<ul id="shipping_radio">
                        	<li>
                            	<input type="radio" name="shippingType" value="Standard" id="standard" checked="checked" onclick="finalShipping(0)" />
                                <label for="standard">Standard Shipping ($ <span id="shipStandard"></span>)</label>
                            </li>
                            <li>
                            	<input type="radio" name="shippingType" value="Saturday" id="saturday" onclick="finalShipping(4)" />
                                <label for="saturday">Saturday ($ <span id="shipSaturday"></span>)</label>
                            </li>
                            <li>
                            	<input type="radio" name="shippingType" value="Rural" id="rural" onclick="finalShipping(4)" />
                                <label for="rural">Rural ($ <span id="shipRural"></span>)</label>
                            </li>
                            
                            <li id="overnightHide">
                            	<input type="radio" name="shippingType" value="Overnight" id="overnight" onclick="finalShipping(20)" />
                               	<label for="overnight">Overnight ($ <span id="shipOverNight"></span>)</label>
                            </li>
                            <div id="clr"></div>
                        </ul>
                        <h3>Choose Shipping Method</h3>
                        <ul id="payment-method">
                            <li>
                                <input type="radio" name="paymentType" value="CreditCard" id="CreditCard" checked="checked"/>
                                <label for="CreditCard" style="background-size:18%;">
                                      <img src="<?php echo siteUrl; ?>images/visa.png" alt="visa"/>
                                </label>
                              
                            </li>
                            <li>
                                <input type="radio" name="paymentType" value="Wechat" id="Wechat"/>
                                <label for="Wechat" style="background-size:auto 45%;">
                                    <img src="<?php echo siteUrl; ?>images/wechat.png" height="30px" alt="visa"/>
                                </label>                      
                            </li>
                            <div id="clr"></div>
                        </ul>
                        <input type="button" value="Continue" class="button-cntinue button" onclick="chkShipnHand();" /> 
                    </div>
                </div>
                <!-- step 4 end -->
                
                
                <!-- step 5 start -->
                
                <!-- step 5 end -->
                
                <style>.checkOutAllPro td, .checkOutAllPro th{ border:1px dotted #999; } .boldTableBody td{ font-weight:bold; }</style>
                <!-- step 6 start -->
                <button type="button" id="btn-5" class="btnAccordion reviewinfo" onclick="return false; openAccordion(5);">Review</button>
                <div class="bodyAccordion" id="accordion-5">
                	<div class="pad15">
                    	<table width="100%" border="0" cellspacing="0" cellpadding="5" class="checkOutAllPro">
                        	<thead>
                            	<tr>
                                	<th></th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Sub Total</th>
                                </tr>
                          	</thead>
                            <tbody>
							<?php $i = 1;
                            $grandTotal = 0;
                            $grandTotalOriginalPrice = 0;
                            $promotionDiscount = 0;
                            $promoCodeDiscount = 0;
                            $promoCodeChk = 'no';
                            $amGst = 0;
                            $priceAfterDiscount=0;
                            $cdate = date('Y-m-d H:i:s');
                            $cartQuery = "SELECT tbl_cart.*, tbl_product.product_name, tbl_product.slug, tbl_product_color.color_code, tbl_brand.brand_name FROM tbl_cart
                            LEFT JOIN tbl_product ON tbl_product.product_id = tbl_cart.product_id
                            LEFT JOIN tbl_product_color ON tbl_product_color.color_id = tbl_cart.color_id
                            LEFT JOIN tbl_brand ON tbl_brand.brand_id = tbl_product.brand_id
                            WHERE tbl_cart.user_id = '".$user_id."' ORDER BY tbl_cart.datetime DESC";
                            $pro_rs = mysqli_query($con, $cartQuery);
                            $numCart = mysqli_num_rows($pro_rs);
                            if($numCart > 0){
                                $amGst = 0;
                                $subTotalAllProducts=0;
                                while($pro_row = mysqli_fetch_object($pro_rs)){
                                    
                                    $img = mysqli_fetch_object(mysqli_query($con, "SELECT media_src, media_thumb FROM tbl_product_media WHERE media_type = 'img' AND product_id = '$pro_row->product_id' AND color_id = '$pro_row->color_id'"));
                                    $src = (isset($img->media_thumb) && $img->media_thumb != '')?'site_image/product/'.$img->media_thumb:'images/product.png';
                                    include 'include/promotionCalc.php';
									$pricee = 0;
                                                                        
									if(isset($discountPromo) && ($discountPromo != 0)){
										$pricee = ($priceNoGst - $discountPromo);
									}else{
										$pricee = $priceNoGst;
									}
                                                                        //echo $pricee;
									//$gstAmountt = $gstAppliedPrice;
								?>
                                <tr>
                                	<td><img src="<?php echo siteUrl.$src; ?>" style="height:30px;" /></td>
                                    <td><?php echo $pro_row->brand_name.' '.$pro_row->product_name; ?></td>
                                    <td align="right">$ <?php echo $pro_row->product_promo_price;//formatCurrency($pricee); ?></td>
                                    <td align="right"><?php echo $pro_row->qty; ?></td>
                                    <td align="right">$ <?php echo formatCurrency($pro_row->product_promo_price * $pro_row->qty);//formatCurrency($priceNoGst * $pro_row->qty); ?></td>
                                </tr>
							<?php } ?>
								<tbody class="boldTableBody">
								<tr>
                                	<td colspan="4" align="right">Cart Total</td>
                                    <td align="right">$ <?php echo formatCurrency(round($priceAfterDiscount, 2, PHP_ROUND_HALF_UP));//formatCurrency($subTotalAllProducts); ?></td>
                                </tr>
<!--                                <tr>
                                	<td colspan="4" align="right">Cart Discount</td>
                                    <td align="right">- $ <?php
                                    //echo $subTotalAllProducts-$priceAfterDiscount;?>
                                    </td>
                                </tr>-->
								<?php if($promoCodeDiscount > 0) {  ?>
                                <tr>
                                	<td colspan="4" align="right">Promo Code Discount</td>
                                    <td align="right">- $ <?php echo formatCurrency($promoCodeDiscount); ?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                	<td colspan="4" align="right">Sub Total</td>
                                    <td align="right">$ <?php 
                                    $grandPlusPromotion = $priceAfterDiscount;
//                                    $grandPlusPromotion = $grandTotalOriginalPrice - ($promotionDiscount + $promoCodeDiscount);
										echo formatCurrency($grandPlusPromotion); 
                                                                                ?>
                        			</td>
                                </tr>
                                
                                <tr>
                                	<!--<td colspan="4" align="right">GST (<?php $gst = getGst(); echo $gst; ?> %)</td>-->
									<td colspan="4" align="right">Includes Tax</td>
                                    <td align="right"> $ <?php echo formatCurrency($grandPlusPromotion*.15);//formatCurrency($amGst); ?></td>
                                </tr>
                               
                                <tr>
                                	<td colspan="4" align="right">Shipping Charge</td>
                                    <td id="shipTabletd" align="right"></td>
                                </tr>
                                
                                <!--<tr>
                                	<td colspan="4" align="right">Point Deduction</td>
                                    <td align="right" id="pointTd">- $ 0.00</td>
                                </tr>-->
                                
                                <tr>
                                	<td colspan="4" align="right">Total</td>
                                    <td id="tableTotaltd" align="right"> $ <?php 
//                                    $finalAmount = $grandPlusPromotion + $amGst;
                                    $finalAmount = $grandPlusPromotion;
                            		echo formatCurrency($finalAmount); ?></td>
                                </tr>
								<tbody>
							<?php }else{ redirect(siteUrl); die(); }?>
                            </tbody>
                      	</table>
                    </div>
                    <div>
                    	<button type="submit" class="button button-cntinue" style="margin:-5px 0px 10px 14px" >Place Order</button>
	                    <input type="hidden" name="action" value="checkOutNow" />
                    </div>
                </div>
                <!-- step 6 end -->
                </form>
            </div>
        </div>
      </div>
     </div>
     
     
     <div class="col-sm-4">
        <?php include 'include/cartRightBar.php'; ?>
        </div>
     
     <div class="clr"> </div>
        </div>
     </div>
        
</section>  



<?php include("include/new_footer.php"); ?>
<!-- f o r m a p n a d d r e s s -->
<script src="//maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>
<!--<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyAHtp966ND167ZB4daiSBYh_ocxAAJ5FEw&amp;libraries=places"></script>-->
<script src="<?php echo siteUrl; ?>js/jquery.geocomplete.js"></script>
<!-- f o r c c c a r d -->
<script src="<?php echo siteUrl; ?>credit/ccvalidate.js" type="text/javascript"></script>
<!-- c h k o u t -->
<script type="text/javascript" src="<?php echo siteUrl; ?>js/script.js"></script>
<script>
<?php if($isLogin){ echo 'openAccordion(2);'; }
else{ echo 'openAccordion(1);'; } ?>

function getCreditCardType(accountNumber){
  //start without knowing the credit card type
  var result = "unknown";
  //first check for MasterCard
  if (/^5[1-5]/.test(accountNumber))  {
    result = "mastercard";
  }
  //then check for Visa
  else if (/^4/.test(accountNumber)){
    result = "visa";
  }
  //then check for AmEx
  else if (/^3[47]/.test(accountNumber))  {
    result = "amex";
  }
  
  document.getElementById('card-number').className=" large cc-card-number "+result+"card";
}
</script>
<?php include("include/new_bottom.php"); ?>