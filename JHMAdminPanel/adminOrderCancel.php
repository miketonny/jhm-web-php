<?php include 'include/header.php'; ?>
<style>
.mytable{ border-bottom:1px solid #ddd; font-size:13px; margin-bottom:0px; }
.mytable td:nth-child(2){ font-weight:bold; }
.mytable td{ padding:5px !important; }
.orderItemTable td:last-child{ text-align:right; }
</style>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Order <small>Order Cancellation Request</small></h1>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>Order#</th>
								<th>Request Date</th>
                                <th>Reason</th>
                                <th>User</th>
                                <!--<th>Amount<br/>(Item)</th>-->
                                <th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1;
							$query = "SELECT toc.reason, toc.description, toc.datetime AS canDate,
							ord.*, tu.email, CONCAT_WS(' ', tu.first_name, tu.last_name) AS uname,
							CONCAT_WS(' ', ord.od_shipping_first_name, ord.od_shipping_last_name) AS shipuname,
							(SELECT COUNT(toi.order_item_id) FROM tbl_order_item toi WHERE toi.order_id = ord.order_id) AS items
							FROM tbl_order_cancel toc
							LEFT JOIN tbl_order ord ON ord.order_id = toc.order_id
							LEFT JOIN tbl_user tu ON tu.user_id = ord.user_id
							ORDER BY ord.order_id DESC";
							$rs = mysql_query($query, $con);
							while($row = mysql_fetch_object($rs)){
								$orderId = $row->order_id;
								$status = $row->status;
								$orderNo = getOrderId($orderId);
							?>
								<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
									<td><?php echo $orderNo; ?></td>
									<td><?php echo date('d M, Y h:i A', strtotime($row->canDate)); ?></td>
                                    <td><?php echo $row->reason; ?></td>
                                    <td><?php echo $row->email; ?></td>
                                    <?php /*<td>NZD <?php echo $row->amount;
										echo '<br/>('.$row->items.' items)';
									?></td> */
                                    ?> 
									
                                    <td><?php  $oStatus = '';
                                    if($status == 0){ $oStatus = 'In Process'; }
									elseif($status == 1){ $oStatus = 'Dispatched'; }
									elseif($status == -1){ $oStatus = 'Rejected'; }
									elseif($status == 2){ $oStatus = 'Delivered'; }
									elseif($status == 3){ $oStatus = 'Cancellation Requested'; }
									elseif($status == 4){ $oStatus = 'Refund Procced'; }
									elseif($status == 5){ $oStatus = 'Return Accepted, Refunded'; }
									elseif($status == 6){ $oStatus = 'Return/Cancel Request Rejected'; }
                                    echo $oStatus;
									?></td> 
									<td>
                                    	<button type="button" data-target="#modall<?php echo $orderId; ?>" data-toggle="modal" class="btn btn-info btn-xs">Info</button>
                                        <?php /*<a type="button" target="_blank" href="../pdf/invoice.php?id=<?php echo $orderId; ?>" class="btn btn-info btn-xs">Invoice</a>
                                        <?php if($status == 0){ ?>
											<button type="button" data-target="#dis<?php echo $orderId; ?>" data-toggle="modal" class="btn btn-success btn-xs">Dispatch</button> */ ?>
											<?php if($status == 4){ ?>
                                            <button type="button" onClick="process(<?php echo $orderId; ?>, 'Refund Completed?', 5)" class="btn btn-info btn-xs">Refund Completed?</button>
											<?php }  else if($status != 5){ ?>
                                            <button type="button" onClick="process(<?php echo $orderId; ?>, 'Do you want to Reject this Request?', 6)" class="btn btn-danger btn-xs">Reject</button>
                                            <button type="button" onClick="process(<?php echo $orderId; ?>, 'Do you want to Process Refund for this Request?', 4)" class="btn btn-success btn-xs">Accept</button>
											<?php } ?>
										<?php /* }if($status == 1){ ?>
                                        	<button type="button" onClick="process(<?php echo $orderId; ?>, 'Do you want to Confirm this order?', 2)" class="btn btn-success btn-xs">Confirm Delivery</button>
                                        <?php }*/ ?>


<?php if($status == 0){ ?>
<!-- dispatch popup start -->
<div class="modal fade" id="dis<?php echo $orderId; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Dispatch Information of Order <strong>#<?php echo $orderNo; ?></strong></h4>
			</div>
			<div class="modal-body panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Order No#</label>
                    <div class="col-sm-8">
                    	<input class="form-control" required name="order" placeholder="Order No#" value="<?php echo $orderNo; ?>" type="text" readonly />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Expected Delivery Date</label>
                    <div class="col-sm-8">
                    	<input class="form-control ddate" required name="delDate" placeholder="Expected Delivery Date" type="text" data-date-format="YYYY-MM-DD" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Tracking No.</label>
                    <div class="col-sm-8">
                    	<input class="form-control" required name="trackNo" placeholder="Tracking No." type="text" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Courier Name</label>
                    <div class="col-sm-8">
                    	<input class="form-control" required name="cName" placeholder="Courier Name" type="text" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Note</label>
                    <div class="col-sm-8">
                   		<textarea class="form-control" name="note" placeholder="Note" cols="" rows="3" required style="height:250px;" ></textarea>
                    </div>
                </div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Save!</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input type="hidden" name="data1" value="<?php echo $orderId; ?>" />
                <input type="hidden" name="action" value="dispatchInfo" />
			</div>
		</div>
		</form>
	</div>
</div><!-- dispatch popup end -->
<?php } ?>

<!-- order detail popup chalu -->
<div class="modal fade" id="modall<?php echo $orderId; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Order Details of Order <strong>#<?php echo $orderNo; ?></strong></h4>
            </div>
            
            <div class="warper container-fluid" style="min-height: 400px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Order Info</div>
                        <div class="panel-body">
                            <div class="form-group" style="margin-bottom:0px;">
                                <div class="col-sm-12">
                                    <table class="table table-hover mails mytable">
            							<tbody>
                                        	<tr class="active">
                                                <td>User</td>
                                                <td><?php
                                                	if($row->uname != ' '){ echo $row->uname.' ('.$row->email.')'; }
													else{ echo $row->email; }
												?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Order Amount</td>
                                                <td>NZD <?php echo $row->amount; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Order Date</td>
                                                <td><?php echo date('d M, Y h:i A', strtotime($row->od_date)); ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Order Payment Type</td>
                                                <td><?php echo $row->payment_type; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Order Payment Status</td>
                                                <td><?php echo $row->payment_status; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Transaction#</td>
                                                <td><?php echo $row->trnid; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Transaction Ref</td>
                                                <td><?php echo $row->trn_ref; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Order Status</td>
                                                <td><?php echo $oStatus; ?></td>
                                            </tr>
                                        </tbody>
                               		</table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Order Shipping Info</div>
                        <div class="panel-body">
                            <div class="form-group" style="margin-bottom:0px;">
                                <div class="col-sm-12">
                                    <table class="table table-hover mails mytable">
            							<tbody>
                                        	<tr class="active">
                                                <td>Shipping Name</td>
                                                <td><?php echo $row->shipuname; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Contact No.</td>
                                                <td><?php $phoneAlt = $row->od_shipping_alt_phone;
													echo $row->od_shipping_phone; echo ($phoneAlt != '')?' ('.$phoneAlt.')':'';
												?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Locality</td>
                                                <td><?php echo $row->od_shipping_locality; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Address</td>
                                                <td><?php echo $row->od_shipping_address; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Address Type</td>
                                                <td><?php echo $row->od_shipping_address_type; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>City, State</td>
                                                <td><?php echo $row->od_shipping_city.', '.$row->od_shipping_state; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Postal Code</td>
                                                <td><?php echo $row->od_shipping_postal_code; ?></td>
                                            </tr>
                                        </tbody>
                               		</table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Order Items</div>
                        <div class="panel-body">
                            <div class="form-group" style="margin-bottom:0px;">
                                <div class="col-sm-12">
                                    
                                    <table class="table table-bordered mytable orderItemTable">
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
										<?php $ooii = 1;
										$gTotal = 0;
										$gstCalcTotal = 0;
										$cart_total = 0;
										$pricePromotionTotal = 0;
										$pricePromoCodeTotal = 0;
										$delCharge = 0; $gst = 15; // fixed
										$oiQuery = "SELECT toi.*, tp.product_name, tp.slug, tc.color, tbl_brand.brand_name
										FROM tbl_order_item toi
										LEFT JOIN tbl_product tp ON tp.product_id = toi.product_id
										LEFT JOIN tbl_brand ON tp.brand_id = tbl_brand.brand_id
										LEFT JOIN tbl_product_color tpc ON tpc.color_id = toi.color_id
										LEFT JOIN tbl_color tc ON tc.color_code = tpc.color_code
										WHERE toi.order_id = '$orderId'
										GROUP BY toi.product_id, toi.color_id";
										$oiRs = exec_query($oiQuery, $con);
										while($oiRow = mysql_fetch_object($oiRs)){ 
										echo $oiRow->brand_name;
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
                                            <td><?php echo $oiRow->brand_name.' '.$oiRow->product_name; ?>
                                            	<a href="../detail/<?php echo $oiRow->product_id; ?>HXYK<?php echo $oiRow->slug; ?>TZS1YL<?php echo $oiRow->color_id; ?>" class="label label-info" target="_blank">View</a>
                                            </td>
                                            <td><?php echo $oiRow->color; ?></td>
                                            <td>$ <?php
												echo $pPrice;
                                            	// if($oiRow->product_promo_code_price != 0){ $price = $oiRow->product_promo_code_price; }
												// elseif($oiRow->product_promo_price != 0){ $price = $oiRow->product_promo_price; }
												// else{ $price = $oiRow->product_price; }
												// echo number_format((float)$price, 2, '.', '');
                                            ?></td>
                                            <td><?php echo $oiRow->od_qty; ?></td>
                                            <td><?php
												echo $rowTotal;
                                            	// // $total = ($price * $oiRow->od_qty);
												// // $gTotal += $total;
												// echo number_format((float)$total, 2, '.', '');
											?></td>
                                        </tr>
                                       	<?php $ooii++; } ?>
                                        <tr>
                                        	<td colspan="5" align="right">Sub Total</td>
                                            <td>$ <?php echo $cart_total; ?></td>
                                        </tr>
                                        <tr>
                                        	<td colspan="5" align="right">Includes Tax</td>
                                            <td>$ <?php
                                            	// $gstCalc = ($gTotal * $gst) / 100;
												// echo number_format((float)$gstCalc, 2, '.', '');
												echo $gstCalcTotal;
											?></td>
                                        </tr>
                                        <tr>
                                        	<td colspan="5" align="right">Shipping & Handling</td>
                                            <td>$ <?php
												$delChrgCalc = $row->od_shipping_cost;
                                            	// $delChrgCalc = ($gTotal * $delCharge) / 100;
												echo number_format((float)$delChrgCalc, 2, '.', '');
											?></td>
                                        </tr>
                                        <tr style="font-size:18px;">
                                        	<td colspan="5" align="right">Grand Total</td>
                                            <td>$ <?php
                                            	$allTotal = $cart_total + $delChrgCalc; // $gTotal + ($gstCalc + $delChrgCalc);
												echo number_format((float)$allTotal, 2, '.', '');
											?></td>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- order detail popup khatam -->
                                        
                                        
									</td>
								</tr>
							<?php $i++; } ?>
						</tbody>
					</table>

				</div>
			</div>
            
        </div>
        <!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php'; ?>
<script>
function process(id, msg, val){	
	if(confirm(msg)){
		$.get('change_status.php', {'table' : 'tbl_order', 'pk_column' : 'order_id', 'pk_val' : id, 'up_column' : 'status', 'up_val' : val}, function(data){
			alert(data); location.reload();
		});
	}
}
</script>
<?php include 'include/tableJs.php'; ?>
<script src="assets/js/moment/moment.js"></script>
<script src="assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
<script>
$('.ddate').datetimepicker({pickTime: false});
</script>
</body>
</html>