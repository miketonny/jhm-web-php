<?php include 'include/header.php'; ?>
<style>
.mytable{ border-bottom:1px solid #ddd; font-size:13px; margin-bottom:0px; }
.mytable td:nth-child(2){ font-weight:bold; }
.mytable td{ padding:5px !important; }
.orderItemTable td:last-child{ text-align:right; }
</style>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Order <small>All Orders</small></h1>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Order#</th>
								<th>User</th>
                                <th>Amount<br/>(Item)</th>
                                <th>Order Date</th>
                                <th>Status</th>
                                <th>Payment Stats</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1;
							$query = "SELECT ord.*, tu.email, CONCAT_WS(' ', tu.first_name, tu.last_name) AS uname,
							CONCAT_WS(' ', ord.od_shipping_first_name, ord.od_shipping_last_name) AS shipuname,
							(SELECT COUNT(toi.order_item_id) FROM tbl_order_item toi WHERE toi.order_id = ord.order_id) AS items
							FROM tbl_order ord
							LEFT JOIN tbl_user tu ON tu.user_id = ord.user_id
                            WHERE ord.isbackOrd=0
							ORDER BY ord.order_id DESC";
							$rs = mysqli_query($con, $query);
							while($row = mysqli_fetch_object($rs)){
								$orderId = $row->order_id;
								$status = $row->status;
								$orderNo = getOrderId($orderId, $con);
							?>
								<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
									<td><?php echo $i; ?></td>
									<td><?php echo $orderNo; ?></td>
									<td><?php echo $row->email; ?></td>
                                    <td>NZD <?php echo $row->amount;
										echo '<br/>('.$row->items.' items)';
									?></td>
                                    <td><?php echo date('d M, Y h:i A', strtotime($row->od_date)); ?></td>
                                    <td><?php $oStatus = '';
                                    	if($status == 0){ $oStatus = 'In Process'; }
										elseif($status == 1){ $oStatus = 'Dispatched'; }
										elseif($status == -1){ $oStatus = 'Rejected'; }
										elseif($status == 2){ $oStatus = 'Delivered'; }
										elseif($status == 3){ $oStatus = 'Cancelled'; }
										echo $oStatus;
									?></td>
                                    <td><?php if ($row-> payment_status == 'Paid')
                                              {
                                              	echo '<span style="color: Green;"> Paid </span>';
                                              }else{
                                                echo '<span style="color: Red;"> Unpaid </span>';
                                              }
                                        ?></td>
									<td>
                                    	<button type="button" data-target="#modall<?php echo $orderId; ?>" data-toggle="modal" class="btn btn-info btn-xs">Info</button>
                                        <a type="button" target="_blank" href="../pdf/invoice.php?id=<?php echo $orderId; ?>" class="btn btn-info btn-xs">Invoice</a>
                                        <?php if($status == 0){ ?>
											<button type="button" data-target="#dis<?php echo $orderId; ?>" data-toggle="modal" class="btn btn-success btn-xs">Dispatch</button>
                                            <button type="button" onClick="process(<?php echo $orderId; ?>, 'Do you want to Reject this order?', -1)" class="btn btn-danger btn-xs">Reject</button>
										<?php }if($status == 1){ ?>
                                        	<button type="button" onClick="process(<?php echo $orderId; ?>, 'Do you want to Confirm this order?', 2)" class="btn btn-success btn-xs">Confirm Delivery</button>
                                        <?php } ?>


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
                    <label class="col-sm-3 control-label">Ticket No.</label>
                    <div class="col-sm-8">
                    	<input class="form-control" required name="trackNo1" placeholder="Ticket No. 1" type="text" />
                        <input class="form-control"  name="trackNo2" placeholder="Ticket No. 2" type="text" />
                        <input class="form-control"  name="trackNo3" placeholder="Ticket No. 3" type="text" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Courier Name</label>
                    <div class="col-sm-8">
                    	<input class="form-control" required name="cName" placeholder="Courier Name" type="text" readonly value="Courier Post" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Note</label>
                    <div class="col-sm-8">
                   		<textarea class="form-control" name="note" placeholder="Note" cols="" rows="3" style="height:200px;" ></textarea>
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
                                                <td><?php echo str_replace("_", " ", ($row->payment_type)); ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Order Payment Status</td>
                                                <td><?php echo $row->payment_status; ?></td>
                                            </tr>
                                            
                                            <tr class="active">
                                                <td>Order #</td>
                                                <td><?php echo $row->trn_ref; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Order Status</td>
                                                <td><?php echo $oStatus; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Auth Code</td>
                                                <td><?php echo $row->trn_ref; ?></td>
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
                                                <td>City</td>
                                                <td><?php echo $row->od_shipping_city; ?></td>
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
										<?php
										$gTotal = 0;
										$gstCalcTotal = 0;
										$cart_total = 0;
										$pricePromotionTotal = 0;
										$pricePromoCodeTotal = 0;
										$delCharge = 0; 
                                        $ooii = 1;
                                        $oiQuery = "SELECT toi.*, case toi.isbackOrd when 1 then '(Backorder)' else '' end as backOrd, tp.product_name, tp.slug, tc.color, tbl_brand.brand_name FROM tbl_order_item toi
                                        LEFT JOIN tbl_product tp ON tp.product_id = toi.product_id
                                        LEFT JOIN tbl_product_color tpc ON tpc.color_id = toi.color_id
                                        LEFT JOIN tbl_color tc ON tc.color_code = tpc.color_code
                                        LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
                                        WHERE toi.order_id = '$orderId' and toi.isbackOrd=0
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
											<td><?php echo $oiRow->brand_name.' '.$oiRow->product_name."  <strong>".$oiRow->backOrd."</strong>"; ?></td>
                                            <td><?php echo $oiRow->color; ?></td>
                                            <td>$ <?php echo $pPrice;?></td>
                                            <td><?php echo $qty; ?></td>
                                            <td>$ <?php echo $rowTotal; ?></td>
                                        </tr>
                                        <?php $ooii++; }
                                        $delChrgCalc = $row->od_shipping_cost;
                                        $allTotal = $cart_total + $delChrgCalc; // ($gTotal + $gstCalcTotal + $delChrgCalc) - ($pricePromoCodeTotal + $pricePromotionTotal + $row->od_point_deduct);
                                        $fAallTotal = formatCurrency($allTotal);
                                        ?>
                                        <tr>
                                            <td colspan="5" align="right">Sub Total</td>
                                            <td>$ <?php echo formatCurrency($cart_total); ?></td>
                                        </tr>
                                        <!--<tr>
                                            <td colspan="5" align="right">Cart Discount</td>
                                            <td>- $ <?php echo formatCurrency($pricePromotionTotal); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" align="right">Promocode Discount</td>
                                            <td>- $ <?php echo formatCurrency($pricePromoCodeTotal); ?></td>
                                        </tr>-->
                                        
                                        <!--<tr>
                                            <td colspan="5" align="right">Sub Total</td>
                                            <td>$ <?php echo formatCurrency($gTotal - ($pricePromoCodeTotal + $pricePromotionTotal)); ?></td>
                                        </tr>-->
                                        <tr>
                                            <td colspan="5" align="right">Includes Tax</td>
                                            <td>$ <?php echo formatCurrency($gstCalcTotal); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" align="right">Shipping Charge</td>
                                            <td>$ <?php echo formatCurrency($delChrgCalc); ?></td>
                                        </tr>
                                        <!--<tr>
                                            <td colspan="5" align="right">Point Deduction</td>
                                            <td>- $ <?php echo formatCurrency($row->od_point_deduct); ?></td>
                                        </tr>-->
                                        <tr style="font-size:18px;">
                                            <td colspan="5" align="right">Grand Total</td>
                                            <td>$ <?php echo $fAallTotal; ?></td>
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