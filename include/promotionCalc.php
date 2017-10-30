<?php
		
		
		$priceFull = $pro_row->product_price; // include gst
                $price_discount=$pro_row->product_promo_price;
		$gst = getGst($con);
//		$priceNoGst = $priceFull/1.15; 
		$priceNoGst = $priceFull-($priceFull*.15); 
		$price = $priceNoGst; // this for price operation
		 $amGst += ($pro_row->qty*($priceFull-$priceNoGst));
//		echo "-->".$amGst;
		/* grand total operatoin */
		$grandTotalOriginalPrice += ($pro_row->qty * $priceNoGst);

		$subTotalAllProducts += ($pro_row->qty * $priceFull);
		$priceAfterDiscount += ($pro_row->qty * $price_discount);
		/* first check promotion */
		if($pro_row->promo_id != 0){
			$pricePromotion = checkPromoValidity($pro_row, $con);
			if($priceFull != $pricePromotion){ /* valid promotion */
				$promotion = mysqli_fetch_array($con, exec_query("SELECT percent_or_amount, promo_value FROM tbl_promotion WHERE promo_id = '$pro_row->promo_id'"));
				$promoType = $promotion['percent_or_amount'];
				$promoval = $promotion['promo_value'];
				if($promoType == 'percent'){
					$discountPromo = ($price * $promoval) / 100;
					$promotionDiscount += ($pro_row->qty * $discountPromo);
				}
				elseif($promoType == 'amount'){
					$discountPromo = $promoval;
					$promotionDiscount += $discountPromo;
				}
				$promoTypeSymbol = ($promoType == 'amount')?'$':'%';
				$isPromotion = true;
			}
		}
		/* now chk promo code */
		if($pro_row->promo_code_id != 0){
			$promoCodeChk = 'yes';
			$promoCodeData = checkPromoCodeValidity($pro_row, $sumOfCart, $con);
			if($promoCodeData[3]){ /* valid promo code */
				$promoCodeType = $promoCodeData[1];
				$promoCodeval = $promoCodeData[2];
				if($promoCodeType == 'percent'){
					$discountPromoCode = ($price * $promoCodeval) / 100;
					$promoCodeDiscount += ($pro_row->qty * $discountPromoCode);
				}
				elseif($promoCodeType == 'amount'){
					$discountPromoCode = $promoCodeval;
					$promoCodeDiscount += $discountPromoCode;
				}
				$promoCodeTypeSymbol = ($promoCodeType == 'amount')?'$':'%';
				$isPromoCode = true;
			}
		}
?>