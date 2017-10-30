<div id="cart_checkout">
        	<h3> Price Details </h3>
            <?php if($numCart > 0){ $page = basename($_SERVER['PHP_SELF']); ?>
			<form action="<?php echo siteUrl; ?>action_model.php" method="post" onsubmit="return chkPromoCode();">
				<ul style="font-size:14px;">
					<?php if(isset($_SESSION['user']) && ($page == 'cart.php')){ ?>
                    <li>
						<h4 style="float:left;"> Coupons </h4> <br /><br /><br />
                        <?php if($promoCodeDiscount == 0 && $promoCodeChk == 'no'){ ?>
                            <p> <input type="text" name="promoCode" id="promoCode" required placeholder="Enter Promo Code" autocomplete="off" /> </p>
                            <button type="submit" >APPLY</button>
                            <input type="hidden" name="redirect" value="<?php echo $page; ?>" />
                        <?php }else{ ?>
                        	<button type="button" onclick="removePromoCode();" >REMOVE PROMO CODE</button>
                        <?php } ?>
					</li>
                    <?php } ?>
					
					<li>
						<h4> Cart Total<strong>$ <?php $grandPlusPromotion = $grandTotalOriginalPrice - $promoCodeDiscount;
						echo formatCurrency($grandPlusPromotion);//echo formatCurrency($grandTotalOriginalPrice); ?></strong> </h4>
                        <!--<h4> Cart Discount <strong style="color:#F33;">$ - <?php //echo formatCurrency($promotionDiscount); ?></strong> </h4>-->
                        <h4> Promo Code Discount <strong style="color:#F33;">$ - <?php echo formatCurrency($promoCodeDiscount); ?></strong> </h4>
                    </li>
                    
                    <li>
                    	<h4> Sub Total <strong> $
						<?php 
                                                $grandPlusPromotion = $grandTotalOriginalPrice - $promoCodeDiscount;
						echo formatCurrency($grandPlusPromotion);
						?></strong> </h4>
                    </li>
                    
                    <?php $delCharge = 0; //$gst = 15; /* change it only later if any changes */
					$gst = getGst();
					?>
					<li>
                    	<h3> Other Charges </h3>
<!--						<h4> GST (<?php //echo $gst; ?> %) <strong>$
							<?php 
							//echo formatCurrency($amGst);
                            ?></strong>
                        </h4>-->
                        <div id="shippingLi"></div>
                        <div id="applyPointLi"></div>
					</li>
					
                    <li>
                    	<h2> Total <strong><span id="grandTotalSpan">
							<?php 
//                                                        $finalAmount = $grandPlusPromotion + $amGst; // + $amDel;
                                                        $finalAmount = $grandPlusPromotion + $amGst; // + $amDel;
                            echo formatCurrency($finalAmount);
							?></span></strong>
                        </h2>
                        <h5>This order includes <strong>$
                                <?php echo $amGst; ?></strong> GST
                        </h5>
                        
						<?php if($page == 'cart.php'){?>
                            <button type="button" class="checkout" onClick="window.location='<?php echo siteUrl; ?>checkOut/';" >GO TO NEXT STEP. >></button>
                        <?php } ?>
                        
                    </li>
				</ul>
				<input type="hidden" id="forShipAmount" value="<?php echo round($finalAmount, 2); ?>" />
                <input type="hidden" name="shippingFeeExtra" id="shippingFeeExtra" value="0" />
                <input type="hidden" name="shippingFee" id="shippingFee" value="" />
                <input type="hidden" name="pointDeduct" id="pointDeduct" value="0" /><!-- point jo deduct krna h, 1$=1p -->
                <input type="hidden" id="userTotalPoint" name="userTotalPoint" value="<?php echo $point; ?>" /><!-- user all point -->
                
                <input type="hidden" name="cartValue" id="cartValue" value="<?php echo round($grandPlusPromotion, 2); ?>" />
				<input type="hidden" name="action" value="applyPromo" />
            </form>
            <?php }else{ echo 'Cart is Empty'; } ?>
        </div>