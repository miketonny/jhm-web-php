<?php include '../include/config.php';
include '../include/functions.php';
if(!isset($_GET['id']) || $_GET['id'] == ''){ die('<<<<<<- SOMETHING WENT WRONG ->>>>>>'); }
$id = $_GET['id'];

$userRow = ''; $oStatus = '';
$query = "SELECT ord.*, tu.email, CONCAT_WS(' ', tu.first_name, tu.last_name) AS uname,
CONCAT_WS(' ', ord.od_shipping_first_name, ord.od_shipping_last_name) AS shipuname,
(SELECT COUNT(toi.order_item_id) FROM tbl_order_item toi WHERE toi.order_id = ord.order_id) AS items
FROM tbl_order ord
LEFT JOIN tbl_user tu ON tu.user_id = ord.user_id
WHERE ord.order_id = '$id'
ORDER BY ord.order_id DESC";
$rs = mysql_query($query, $con);
$row = mysql_fetch_object($rs);
$orderId = $row->order_id;
$status = $row->status;
$orderNo = getOrderId($orderId);

if($row->uname != ' '){
	$userRow .= '<tr><td>'.$row->uname.'</td></tr>';
	$userRow .= '<tr><td>'.$row->email.'</td></tr>';
}else{ $userRow .= '<tr><td>'.$row->email.'</td></tr>'; }

if($status == 0){ $oStatus = 'In Process'; }
elseif($status == 1){ $oStatus = 'Dispatched'; }
elseif($status == -1){ $oStatus = 'Rejected'; }
elseif($status == 2){ $oStatus = 'Delivered'; }

$phoneAlt = $row->od_shipping_alt_phone;
$pphone = ($phoneAlt != '')?$row->od_shipping_phone.' ('.$phoneAlt.')':$row->od_shipping_phone;

require_once('tcpdf_include.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$r = rand(10000000, 99999999);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Invoice'.$r);
$pdf->SetTitle('Invoice'.$r);
$pdf->SetSubject('Invoice'.$r);
$pdf->SetKeywords('Invoice'.$r);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// set font
$pdf->SetFont('helvetica', 'B', 20);

// add a page
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 9);

$tbl = '
<table border="1" cellpadding="5" cellspacing="0" nobr="true">
	
	<tr>
  		<td>
			<table border="0" cellpadding="3" cellspacing="0" nobr="true">
				<tr>
  					<td align="left"><h3>'.siteName.' (Invoice for Order #'.$orderNo.')</h3></td>
					<td align="right">Order Date:'.date('d M, Y h:i A', strtotime($row->od_date)).'</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table border="0" cellpadding="3" cellspacing="0" nobr="true">
				<tr>
  					<td align="left">
						
						<table border="0" cellpadding="3" cellspacing="0" nobr="true">
							<tr><td><b>Company Info</b></td></tr>
							<tr><td><b>'.siteName.'</b></td></tr>
							<tr><td>New Zealand</td></tr>
							<tr><td>admin@jhm.co.nz</td></tr>
							<tr><td>9988776655</td></tr>
						</table>
						
					</td>
					<td align="right">
						
						<table border="0" cellpadding="3" cellspacing="0" nobr="true">
							<tr><td><b>Customer & Order</b></td></tr>
							'.$userRow.'
							<tr><td>Order Amount: $'.formatCurrency($row->amount).'</td></tr>
							<tr><td>Payment Status: '.$row->payment_status.'</td></tr>
							<tr><td>Order Status: '.$oStatus.'</td></tr>
						</table>
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table border="0" cellpadding="9" cellspacing="0" nobr="true">
				<tr>
  					<td>
						<center><b>Order Item Details</b></center>
						<br/><br/>
						<table border="1" cellpadding="3" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th style="width:5%;">#</th>
									<th style="width:50%;">Product</th>
									<th style="width:17%;">Color</th>
									<th style="width:10%;">Price</th>
									<th style="width:8%;">Qty</th>
									<th style="width:10%;">Total</th>
								</tr>
							</thead>
							<tbody>';
							
							$ooii = 1;
							$gTotal = 0;
							$gstCalcTotal = 0;
							$cart_total = 0;
							$pricePromotionTotal = 0;
							$pricePromoCodeTotal = 0;
							$delCharge = 0; $gst = 15; // fixed
							$oiQuery = "SELECT toi.*, case toi.isbackOrd when 1 then '(Backorder)' else '' end as backOrd,  tp.product_name, tp.slug, tc.color
							FROM tbl_order_item toi
							LEFT JOIN tbl_product tp ON tp.product_id = toi.product_id
							LEFT JOIN tbl_product_color tpc ON tpc.color_id = toi.color_id
							LEFT JOIN tbl_color tc ON tc.color_code = tpc.color_code
							WHERE toi.order_id = '$orderId'
							GROUP BY toi.product_id, toi.color_id";
							$oiRs = exec_query($oiQuery, $con);
							$discount=0;
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
								$pPrice = formatCurrency(round($productDiscountedPrice, 2, PHP_ROUND_HALF_UP));
								$rowTotal = formatCurrency(round($rowTotal, 2, PHP_ROUND_HALF_UP));
								
								$tbl .= '<tr>
									<td> '.$ooii.'</td>
									<td> '.$oiRow->product_name.'  <strong>'.$oiRow->backOrd.'</strong></td>
									<td> '.$oiRow->color.'</td>
									<td> $'.$pPrice.'</td>
									<td> '.$oiRow->od_qty.'</td>
									<td align="right"> $'.$rowTotal.'</td>
								</tr>';
								
								// if($oiRow->product_promo_code_price != 0){ 
									// $discount+= $originalPrice-($oiRow->product_promo_code_price/1.15); 
								// }elseif($oiRow->product_promo_price != 0){ 
									// $discount+= $originalPrice-($oiRow->product_promo_price/1.15); 
								// }
								$ooii++;
							}
							
							
							
							// $priceGst = $priceFull-$priceFull/1.15;
							$delChrgCalc = $row->od_shipping_cost;
							
							
							$allTotal = ($cart_total + $delChrgCalc);
							//$allTotal = $gTotal + ((($gTotal-$discount)*0.15)+ $delChrgCalc)-$discount;
							
							$tbl .= '<tr>
								<td colspan="5" align="right"> Sub Total </td>
								<td align="right"> $'.formatCurrency(round($cart_total, 2, PHP_ROUND_HALF_UP)).' </td>
							</tr>
							<!--<tr>
								<td colspan="5" align="right"> Discount </td>
								<td> -$'.round((formatCurrency($discount)), 2, PHP_ROUND_HALF_UP).' </td>
							</tr>-->
							<tr>
								<td colspan="5" align="right">Includes Tax</td>
								<td align="right"> $'.formatCurrency(round($gstCalcTotal, 2, PHP_ROUND_HALF_UP)).'</td>
							</tr>
							<tr>
								<td colspan="5" align="right"> Shipping Charge</td>
								<td align="right"> $'.formatCurrency($delChrgCalc).'</td>
							</tr>
							<!--<tr>
								<td colspan="5" align="right"> Point Deduction</td>
								<td> -$'.formatCurrency($row->od_point_deduct).'</td>
							</tr>-->
							<tr style="font-weight:bold;">
								<td colspan="5" align="right"> Grand Total</td>
								<td align="right"> $'.formatCurrency(round($allTotal,2, PHP_ROUND_HALF_UP)).'</td>
							</tr>
							</tbody>
							
						</table>
						<br/>
						<div height="1" align="right"><font size="10"><b>Total Amount: $ '.formatCurrency($allTotal).'</b></font></div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
 	
	<tr>
		<td>
			<table border="0" cellpadding="3" cellspacing="0" nobr="true">
				<tr>
  					<td align="left">
						
						<table border="0" cellpadding="3" cellspacing="0" nobr="true">
							<tr><td align="right"><b>Delivery Information</b></td></tr>
							<tr><td align="right">'.$row->shipuname.'</td></tr>
							<tr><td align="right">Contact No.: '.$pphone.'</td></tr>
							<tr><td align="right">Address: '.$row->od_shipping_address.'</td></tr>
							<tr><td align="right">'.$row->od_shipping_locality.', '.$row->od_shipping_city.', '.$row->od_shipping_state.' '.$row->od_shipping_postal_code.'</td></tr>
						</table>
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr>
  		<td>
			<table border="0" cellpadding="3" cellspacing="0" nobr="true">
				<tr>
  					<td align="left">Thank you for shopping with JHM.</td>
				</tr>
			</table>
		</td>
	</tr>
	
</table>
';//$row->amount

$pdf->writeHTML($tbl, true, false, false, false, '');

//Close and output PDF document
$pdf->Output('invoice.pdf', 'I');