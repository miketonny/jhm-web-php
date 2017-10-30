<?php /* group concat create prb here. bcoz on detail page categories are ok, bt on category page sm prb, singl catehgory returnnnnnnnn,  */ ?>
<li>
	<!-- know more upr s girne wala -->
    <!-- bich ka  -->
	<span class="itemimage">
    <?php
	$price = $row->product_price;
	$style="";
	if($promoType != '' && $promoValue != ''){
		$promotionId = $promoArr['promo_id'];
		if($promoType == 'percent'){ $disPrice = $price - (($price * $promoValue) / 100); $discount=str_replace('.00', '',$promoValue).'% <br/> Off';  }
		elseif($promoType == 'amount'){ $disPrice = $price - $promoValue; $discount='Save $'.$promoValue; }
	}else{ 
		$disPrice = 0;
		$promotionId = 0;
	}?>
    	<?php /* <div id="buttonsbar">
        	<a href="<?php echo siteUrl; ?>detail/<?php echo $row->product_id; ?>HXYK<?php echo $row->slug; ?>TZS1YL<?php echo $row->color_id; ?>"  class="detailbtn roundbuttons tooltips"></a>
            
            <a href="javascript:void(0)" onclick="addProductToCart(<?php echo $row->product_id; ?>, <?php echo $row->color_id; ?>, <?php echo $price; ?>, <?php echo $disPrice; ?>, <?php echo $promotionId; ?>)" class="addtocartBtn roundbuttons tooltips"></a>
            
            <a href="javascript:void(0)" class="wishlistbtn roundbuttons tooltips" onclick="
			<?php if(isset($_SESSION['user']) && isset($_SESSION['user_email'])){ ?> addToWish(<?php echo $row->product_id; ?>, <?php echo $row->color_id; ?>); <?php }else{ echo "alert('Oops!!! Please login for add product in wishlist.');"; } ?>" ></a>
        </div>*/ ?>
    	
    	<a class="pdphoto" href="<?php echo siteUrl; ?>detail/<?php echo $row->product_id; ?>HXYK<?php echo $row->slug; ?>TZS1YL<?php echo $row->color_id; ?>">
			<img src="<?php echo siteUrl; ?>site_image/product/<?php echo $row->media_thumb; ?>" alt="<?php echo $row->product_name; ?>" />
		</a><!--data-original-->
    </span>
	<h4><a href="<?php echo siteUrl; ?>detail/<?php echo $row->product_id; ?>HXYK<?php echo $row->slug; ?>TZS1YL<?php echo $row->color_id; ?>">
		<?php echo $row->brand_name.' '.$row->product_name; ?>
    </a></h4>
	<h3>$<?php $price;
	if($promoType != '' && $promoValue != ''){
		$promotionId = $promoArr['promo_id'];
		if($promoType == 'percent'){ $disPrice = $price - (($price * $promoValue) / 100); $discount=str_replace('.00', '',$promoValue).'% <br/> Off';  }
		elseif($promoType == 'amount'){ $disPrice = $price - $promoValue; $discount='Save $'.$promoValue; $style="height: 17px !important; margin-right: -35px; margin-top: -32px; padding-top: 53px;";  }
		
		//$gstDisPrice = $disPrice + (($disPrice * 15) / 100);
		echo formatCurrency($disPrice); ?>
        
        <!--<strong>$<?php echo formatCurrency($price); ?></strong> <span class="discount" style="<?php echo $style; ?>"><?php echo $discount; ?></span>--><?php
	}else{
		//$gstPrice = $price + (($price * 15) / 100);
		//echo number_format((float)$gstPrice, 2, '.', '');
		echo formatCurrency($price);
		$disPrice = 0;
		$promotionId = 0;
	}?>
	</h3>
    
    <a href="javascript:void(0)" onclick="addProductToCart(<?php echo $row->product_id; ?>, <?php echo $row->color_id; ?>, <?php echo $price; ?>, <?php echo $disPrice; ?>, <?php echo $promotionId; ?>)" class="addtocart_button">BUY</a>
    <!-- cart button niche s upr ane wala -->
</li>