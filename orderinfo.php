<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>
<?php if(!isset($_SESSION['user']) || $_SESSION['user'] == '' || empty($_SESSION['user'])){ redirect(siteUrl); }
$userId = $_SESSION['user'];
?>

<section class="block-pt5">
    <div class="container">

	<div  class="dashboard-holder">
    
     <div class="col-sm-12">
    	<h1 class="mainHeading">Order Info </h1>
            </div>
           <div class="col-md-3 col-sm-4"> 
      	<?php include 'include/userNavigation.php'; ?>
           </div>

<div class="col-md-9 col-sm-8">  
<div class="clr"> </div>
    
  <div id="userpages">
                    <h2> My Orders</h2>
                    <?php 
							$orderId = $_REQUEST['odid'];
							$query = "SELECT ord.*, tu.email, CONCAT_WS(' ', tu.first_name, tu.last_name) AS uname,
							CONCAT_WS(' ', ord.od_shipping_first_name, ord.od_shipping_last_name) AS shipuname,
							(SELECT COUNT(toi.order_item_id) FROM tbl_order_item toi WHERE toi.order_id = ord.order_id) AS items
							FROM tbl_order ord
							LEFT JOIN tbl_user tu ON tu.user_id = ord.user_id
							WHERE ord.order_id = '".$_REQUEST['odid']."'
							ORDER BY ord.order_id DESC";
							$rs = mysqli_query($con, $query);
							$orderNo = getOrderId($orderId, $con);
							$row = mysqli_fetch_object($rs); ?>
                    	<div id="popid<?php echo $orderNo; ?>" class="orderInfoWrapper" style="width:100%; left:45%; background:none; border:1px solid #d3d3d3; box-sizing:border-box; position:static; margin:0px; box-shadow:0px 0px; font-size:15px;">
	<div>
        <div id="loginBox">
            <h4 class="signin">Order Details of Order <strong>#<?php echo $orderNo; ?></strong></h4>
            
            <div  class="order-info-box" style="float:left; width:50%;">
            	<h3>Order Info</h3>
                <div class="table-responsive">
            	<table style="width:100%;">
                    <tbody>
                        <tr>
                            <td>User</td>
                            <td><?php
                                if($row->uname != ' '){ echo $row->uname.' ('.$row->email.')'; }
                                else{ echo $row->email; }
                            ?></td>
                        </tr>
                        <tr>
                            <td>Order Amount</td>
                            <td>$ <?php echo $row->amount; ?></td>
                        </tr>
                        <tr>
                            <td>Order Date</td>
                            <td><?php echo date('d M, Y h:i A', strtotime($row->od_date)); ?></td>
                        </tr>
                        <tr>
                            <td>Order Payment Type</td>
                            <td><?php echo $row->payment_type; ?></td>
                        </tr>
                        <tr>
                            <td>Order Payment Status</td>
                            <td><?php echo $row->payment_status; ?></td>
                        </tr>
                        <!--<tr>
                            <td>Transaction#</td>
                            <td><?php echo $row->trnid; ?></td>
                        </tr>
                        <tr>
                            <td>Transaction Ref</td>
                            <td><?php echo $row->trn_ref; ?></td>
                        </tr>-->
                        <tr>
                            <td>Order Status</td>
                            <td><?php echo checkOrderStatus($row->status); ?></td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            
            <div class="order-shipping-box" style="width:49%; float:right;">
            	<h3>Shipping Info</h3>
                <div class="table-responsive">
            	<table style="width:100%;">
                    <tbody>
                        <tr>
                            <td>Shipping Name</td>
                            <td><?php echo $row->shipuname; ?></td>
                        </tr>
                        <tr>
                            <td>Contact No.</td>
                            <td><?php $phoneAlt = $row->od_shipping_alt_phone;
                                echo $row->od_shipping_phone; echo ($phoneAlt != '')?' ('.$phoneAlt.')':'';
                            ?></td>
                        </tr>
                        <tr>
                            <td>Locality</td>
                            <td><?php echo $row->od_shipping_locality; ?></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><?php echo $row->od_shipping_address; ?></td>
                        </tr>

                        <tr>
                            <td>City</td>
                            <td><?php echo $row->od_shipping_city; ?></td>
                        </tr>
                        <tr>
                            <td>Postal Code</td>
                            <td><?php echo $row->od_shipping_postal_code; ?></td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            <div id="clr"></div>
            <br/><br/>
            <div>
            	<h3>Order Items</h3>
                <div class="pro-dt-list">
            	<table style="width:100%;" cellpadding="5" cellspacing="2">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Color</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
					$gTotal = 0;
					$gstCalcTotal = 0;
					$pricePromotionTotal = 0;
					$pricePromoCodeTotal = 0;
					$cart_total=0;
					$gst = getGst($con);
					$ooii = 1;
                    $oiQuery = "SELECT toi.*, tp.product_name, tp.slug, tc.color, tbl_brand.brand_name FROM tbl_order_item toi
                    LEFT JOIN tbl_product tp ON tp.product_id = toi.product_id
                    LEFT JOIN tbl_product_color tpc ON tpc.color_id = toi.color_id
                    LEFT JOIN tbl_color tc ON tc.color_code = tpc.color_code
                    LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
					WHERE toi.order_id = '".$_REQUEST['odid']."'
                    GROUP BY toi.product_id, toi.color_id";
                    $oiRs = exec_query($oiQuery, $con);
                    while($oiRow = mysqli_fetch_object($oiRs)){
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
												$pPrice = formatCurrency(round($productDiscountedPrice, 2, PHP_ROUND_HALF_UP));
												$rowTotal = formatCurrency(round($rowTotal, 2, PHP_ROUND_HALF_UP));
					?>
                    <tr>
                        <td><?php echo $ooii; ?></td>
                        <td><?php echo $oiRow->brand_name.' '.$oiRow->product_name; ?></td>
                        <td><?php echo $oiRow->color; ?></td>
                        <td>$ <?php echo round($pPrice, 2, PHP_ROUND_HALF_UP);?></td>
                        <td><?php echo $qty; ?></td>
                        <td>$ <?php echo  (round($rowTotal, 2, PHP_ROUND_HALF_UP)); ?></td>
                    </tr>
                    <?php $ooii++; }
					$delChrgCalc = $row->od_shipping_cost;
                    //$gstCalcTotal = ($gTotal - ($pricePromoCodeTotal + $pricePromotionTotal))*0.15;
					$allTotal = $cart_total + $delChrgCalc; // ($gTotal + $gstCalcTotal + $delChrgCalc) - ($pricePromoCodeTotal + $pricePromotionTotal + $row->od_point_deduct);
					$fAallTotal = $allTotal;
					?>
                    <tr>
                        <td colspan="5" align="right">Sub Total</td>
                        <td>$ <?php echo $cart_total; ?></td>
                    </tr>
                    <!--<tr>
                        <td colspan="5" align="right">Cart Discount</td>
                        <td>- $ <?php echo formatCurrency($pricePromotionTotal); ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right">Promocode Discount</td>
                        <td>- $ <?php echo formatCurrency(round($pricePromotionTotal, 2, PHP_ROUND_HALF_UP)); ?></td>
                    </tr>
                    
                    <tr>
                        <td colspan="5" align="right">Sub Total</td>
                        <td>$ <?php echo formatCurrency(round(($gTotal - ($pricePromoCodeTotal + $pricePromotionTotal)), 2, PHP_ROUND_HALF_UP)); ?></td>
                    </tr>-->
                    <tr>
                        <!--<td colspan="5" align="right">GST <?php echo $gst; ?>%</td>-->
						<td colspan="5" align="right" style="font-size:12px;">Includes Tax</td>
                        <td>$ <?php echo formatCurrency(round($gstCalcTotal, 2, PHP_ROUND_HALF_UP)); ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right">Shipping Charge</td>
                        <td>$ <?php echo formatCurrency(round($delChrgCalc, 2, PHP_ROUND_HALF_UP)); ?></td>
                    </tr>
                    <!--<tr>
                        <td colspan="5" align="right">Point Deduction</td>
                        <td>- $ <?php echo formatCurrency($row->od_point_deduct); ?></td>
                    </tr>-->
                    <tr style="font-size:20px;font-weight: 600;">
                        <td colspan="5" align="right">Grand Total</td>
                        <td>$ <?php echo formatCurrency(round($fAallTotal, 2, PHP_ROUND_HALF_UP)); ?></td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
            
        </div>
  	</div>
</div>
                    </div>  
    
    
    </div>
    
    <div class="clr"> </div>
	</div>
  
        
  </div>
</section>







<?php include("include/new_footer.php"); ?>

<script>
function openOrderDetail(id, classs){
	document.getElementById(id).className = 'orderInfoWrapper '+classs;
	document.getElementById('blackoverlayOrder').style.display="block";
	document.getElementById('blackoverlayOrder').className=id;
}
function closeOrderDetail(id){
	document.getElementById(id).className='orderInfoWrapper';
	document.getElementById('blackoverlayOrder').style.display='';
	document.getElementById('blackoverlayOrder').className='';
}
function applyForCredit(id){
	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){ alert(xmlhttp.responseText); location.reload(); }
	}
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=applyForCredit&data1="+id+"&dataTempId=f3vcfs1543652ce90hch14lk6217900.cloud.uk", true);
	xmlhttp.send();
}
</script>

<?php include("include/new_bottom.php"); ?>