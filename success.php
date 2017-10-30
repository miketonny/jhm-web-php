<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>
<?php if(isset($_GET['ordId']) && $_GET['ordId'] != ''){ $orderId = $_GET['ordId']; }
else{ redirect(siteUrl); die(); }
$orderId = str_replace('orderId4xip', '', $orderId);

$query = "SELECT ord.*, tu.email, CONCAT_WS(' ', tu.first_name, tu.last_name) AS uname,
CONCAT_WS(' ', ord.od_shipping_first_name, ord.od_shipping_last_name) AS shipuname,
(SELECT COUNT(toi.order_item_id) FROM tbl_order_item toi WHERE toi.order_id = ord.order_id) AS items
FROM tbl_order ord
LEFT JOIN tbl_user tu ON tu.user_id = ord.user_id
WHERE ord.order_id = '$orderId'
ORDER BY ord.order_id DESC";
$rs = mysql_query($query, $con);
$row = mysql_fetch_object($rs);
$status = $row->status;
$orderNo = getOrderId($orderId);

if($status == 0){ $oStatus = 'In Process'; }
elseif($status == 1){ $oStatus = 'Dispatched'; }
elseif($status == -1){ $oStatus = 'Rejected'; }
elseif($status == 2){ $oStatus = 'Delivered'; }
?>

    
  <link href="<?php echo siteUrl; ?>css/styleCheckout.css" rel="stylesheet" type="text/css" />  
    
  <section class="block-pt5">
    <div class="container">
        <div class="other-page-holder">
  <div  class="col-md-12">
       
        <div class="success_heading"> 
        <h1>Transaction Successfully Completed</h1>
        <span> <button type="button" onclick="window.location='<?php echo siteUrl; ?>product-search/'">CONTINUE SHOPPING</button>
        <button type="button" onclick="window.location='<?php echo siteUrl; ?>'">HOME</button> </span>
        <div class="clr"> </div>
        </div>
        
        <div id="detailsorder">
        	<h4>Order Details of Order <strong>#<?php echo $orderNo; ?></strong></h4>
            <p> Order Placed on : <?php echo date('d M, Y h:i A', strtotime($row->od_date)); ?> </p>
            <p style="text-transform: capitalize;" > Payment type : <?php echo str_replace("_", " ",  $row->payment_type); ?> </p>
        </div>
        
        <div id="shipingaddress_suc">
        	<h3> Shipping Address </h3>
            <p> <?php echo $row->shipuname; ?> </p>
            <p> <?php $phoneAlt = $row->od_shipping_alt_phone;
                                echo $row->od_shipping_phone; echo ($phoneAlt != '')?' ('.$phoneAlt.')':'';
                            ?>
            </p>
            <p> <?php echo $row->od_shipping_locality; ?> </p>
            <p> <?php echo $row->od_shipping_address; ?> </p>
            <p> <?php echo $row->od_shipping_city; ?> </p>
            <p> <?php echo $row->od_shipping_postal_code; ?> </p>
            
        </div>
        
      <div id="ordersummary" class="pro-dt-list">
        	<h3> Order Info </h3>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr class="summary_1tr"> 
    <td>Order Amount</td>
    <td>Order Date</td>
    <td>Order Payment type</td>
    <td>Order Payment Status</td> 
    <!--<td>Order #</td>-->
    <td>Order Status</td>
  </tr>
  
  <tr> 
    
    <td>NZD <?php echo $row->amount; ?></td>
    <td><?php echo date('d M, Y h:i A', strtotime($row->od_date)); ?></td>
    <td><?php echo str_replace("_", " ", $row->payment_type); ?></td>
    <td><?php echo $row->payment_status; ?></td>
    <!--<td><?php echo $row->trn_ref; ?></td>-->
    <td><?php echo $oStatus; ?></td>
  </tr>
          </table>

        </div>
        
        <!--<table width="550" border="0" align="center" cellpadding="5" cellspacing="1" class="entryTable" >
            <tr class="entryTableHeader"> 
                <td>Payment Process Successfully Completed.</td>
            </tr>
            <tr>
                <td class="label" align="center">Congratulations! Order Placed Successfully, </td>
            </tr>
            <tr>
                <td class="label" align="center">
                	
                </td>
            </tr>
        </table>-->
        
        
        
        <!-- kaam ka data -->
        
        <div id="orderitems" class="pro-dt-list">
    <h3> Order Items </h3>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr class="summary_1tr">
    <td width="2%">#</td>
    <td width="51%">Product</td>
    <td width="12%">Color</td>
    <td width="11%">Price</td>
    <td width="10%">Qty</td>
    <td width="14%">Total</td>
  </tr>
   <?php
					$gTotal = 0;
					$gstCalcTotal = 0;
					$pricePromotionTotal = 0;
					$pricePromoCodeTotal = 0;
					$gst = getGst();
					$ooii = 1;
                                        //taslim//
                                        $totprice=0;
                                        $cart_total=0;
                                        $after_discount_total=0;
                                        //taslim//
                    $oiQuery = "SELECT toi.*, case toi.isbackOrd when 1 then '(Backorder)' else '' end as backOrd,  tp.product_name, tp.slug, tc.color, tbl_brand.brand_name FROM tbl_order_item toi
                    LEFT JOIN tbl_product tp ON tp.product_id = toi.product_id
                    LEFT JOIN tbl_product_color tpc ON tpc.color_id = toi.color_id
                    LEFT JOIN tbl_color tc ON tc.color_code = tpc.color_code
                    LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
					WHERE toi.order_id = '$orderId'
                    GROUP BY toi.product_id, toi.color_id";
                    $oiRs = exec_query($oiQuery, $con);
                    while($oiRow = mysql_fetch_object($oiRs)){
						$pricePromotion = $oiRow->product_promo_price;
						$pricePromoCode = $oiRow->product_promo_code_price;
                        $qty = $oiRow->od_qty;
						$priceNoDiscount = $oiRow->product_price;
						$productDiscountedPrice = 0; 
						
						if($pricePromotion > 0){
							$productDiscountedPrice = $oiRow->product_promo_price;
							$promoType = $oiRow->product_promo_type;
							$promoVal = $oiRow->product_promo_value;
							if($promoType == 'percent'){
								$discountPromo = ($priceNoDiscount * $promoVal) / 100;
								$pricePromotionTotal += ($qty * $discountPromo);
							}
							elseif($promoType == 'amount'){
								$discountPromo = $promoVal;
								$pricePromotionTotal += $discountPromo;
							}
						}
						if($pricePromoCode > 0){
							$productDiscountedPrice = $oiRow->product_promo_code_price;
							$promoCodeType = $oiRow->product_promo_code_type;
							$promoCodeVal = $oiRow->product_promo_code_value;
							if($promoCodeType == 'percent'){
								$discountPromoCode = ($priceNoDiscount * $promoCodeVal) / 100;
								$pricePromoCodeTotal += ($qty * $discountPromoCode);
							}
							elseif($promoCodeType == 'amount'){
								$discountPromoCode = $promoCodeVal;
								$pricePromoCodeTotal += $discountPromoCode;
							}
						}
						
						if ($productDiscountedPrice == 0) {
							$productDiscountedPrice = $priceNoDiscount;
						}  //no promotion, use normal price
							 
						
						$Gst = $productDiscountedPrice*.15;	//gst value
						$priceWithoutGST = $productDiscountedPrice-$Gst;	//price without tax
						
						$rowTotal = ($productDiscountedPrice * $qty);
						$gstCalc = ($Gst * $qty);
						$gstCalcTotal += $gstCalc;
                        $cart_total+=$rowTotal;
					?>
  
  <tr style="background:#F2F2F2;">
    <td><?php echo $ooii; ?></td>
    <td><?php echo $oiRow->brand_name.' '.$oiRow->product_name."  <strong>".$oiRow->backOrd."</strong>"; ?></td>
    <td><?php if ($oiRow->color != "White") {
                            echo $oiRow->color;
                        }else{echo "-";} ?></td>
    <td>$ <?php echo $productDiscountedPrice;//$oiRow->product_price;?></td>
    <td><?php echo $qty; ?></td>
    <td>$ <?php echo  formatCurrency(round($rowTotal, 2, PHP_ROUND_HALF_UP));//(round($total, 2, PHP_ROUND_HALF_UP)); ?></td>
  </tr>
  	
	<?php $ooii++; }
					$delChrgCalc = $row->od_shipping_cost;
                                        //taslim//
                                        //$gTotal=$totprice;
                                        //taslim//
					//$gstCalcTotal = ($totpriceNoDiscount - ($pricePromoCodeTotal + $pricePromotionTotal))*0.15;
					// $allTotal = ($gTotal + $gstCalcTotal + $delChrgCalc) - ($pricePromoCodeTotal + $pricePromotionTotal + $row->od_point_deduct);
					// $allTotal = $cart_total + $delChrgCalc;
					// $fAallTotal = $allTotal;
	?>
                    
  <tr>
    <td colspan="5"  align="right">Cart Total</td>
    <td>$ <?php echo formatCurrency(round($cart_total, 2, PHP_ROUND_HALF_UP));//formatCurrency(round($gTotal, 2, PHP_ROUND_HALF_UP)); ?></td>
  </tr>
<!--  <tr>
    <td colspan="5"  align="right">Cart Discount</td>
    <td>- $ <?php //$cart_disc=$cart_total-$after_discount_total; echo formatCurrency(round($cart_disc, 2, PHP_ROUND_HALF_UP));//formatCurrency(round($pricePromotionTotal, 2, PHP_ROUND_HALF_UP)); ?></td>
  </tr>
  <tr>
    <td colspan="5"  align="right">Promocode Discount</td>
    <td>- $ <?php echo formatCurrency(round($pricePromoCodeTotal, 2, PHP_ROUND_HALF_UP)); ?></td>
  </tr>-->
  <tr>
    <td colspan="5"  align="right">Subtotal</td>  
    <td>$ <?php echo formatCurrency(round($cart_total, 2, PHP_ROUND_HALF_UP));//formatCurrency(round(($gTotal - ($pricePromoCodeTotal + $pricePromotionTotal)), 2, PHP_ROUND_HALF_UP)); ?></td>
  </tr>
  <tr>
    <!--<td colspan="5"  align="right">GST <?php echo $gst; ?>%</td>-->
	<td colspan="5"  align="right">Includes Tax</td>
    <td>$ <?php echo formatCurrency(round($gstCalcTotal, 2, PHP_ROUND_HALF_UP));//formatCurrency(round($gstCalcTotal, 2, PHP_ROUND_HALF_UP)); ?></td>
  </tr>
  <tr>
    <td colspan="5"  align="right">Shipping Charge</td>
    <td>$ <?php echo formatCurrency(round($delChrgCalc, 2, PHP_ROUND_HALF_UP)); ?></td>
  </tr>
  <!--<tr>
    <td colspan="5"  align="right">Point Deduction</td>
    <td>- $ <?php echo formatCurrency(round($row->od_point_deduct, 2, PHP_ROUND_HALF_UP)); ?></td>
  </tr>-->
  <tr>
    <td colspan="5"  align="right">Grand Total</td>
    <td>NZD <?php 
    $allTotal = ($cart_total + $delChrgCalc);
    echo formatCurrency(round($allTotal, 2, PHP_ROUND_HALF_UP));
    
    //echo formatCurrency(round($fAallTotal, 2, PHP_ROUND_HALF_UP)); ?></td>
  </tr>
    </table>

    </div>
        
        
     	<div class="clr"> </div>
    </div>
    <div class="clr"> </div>
        </div>
        </div>
        
</section>
  

<?php include("include/new_footer.php"); ?>
<?php include("include/new_bottom.php"); ?>