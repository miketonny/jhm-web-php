<?php
/* group concat create prb here. bcoz on detail page categories are ok, bt on category page sm prb, singl catehgory returnnnnnnnn,  */
?>
<li>
	<!-- know more upr s girne wala -->
	
    
    <!-- bich ka  -->
	<span class="itemimage">
     
    	<?php /*?><div id="buttonsbar">
        	<a href="<?php echo siteUrl; ?>detail/<?php echo $row->product_id; ?>HXYK<?php echo $row->slug; ?>TZS1YL<?php echo $row->color_id; ?>"  class="detailbtn roundbuttons tooltips"></a>
            
            <a href="javascript:void(0)" onclick="addProductToCartB(<?php echo $row->product_id; ?>, <?php echo $row->color_id; ?>, <?php echo $price; ?>, <?php echo $disPrice; ?>, <?php echo $promotionId; ?>)" class="addtocartBtn roundbuttons tooltips"></a>
            
            <a href="javascript:void(0)" class="wishlistbtn roundbuttons tooltips" onclick="
			<?php if(isset($_SESSION['user']) && isset($_SESSION['user_email'])){ ?> addToWish(<?php echo $row->product_id; ?>, <?php echo $row->color_id; ?>); <?php }else{ echo "alert('Oops!!! Please login for add product in wishlist.');"; } ?>" ></a>
        </div>
        <?php */?>
    	<a href="<?php echo siteUrl; ?>detail/<?php echo $row->product_id; ?>HXYK<?php echo $row->slug; ?>TZS1YL<?php echo $row->color_id; ?>">
			<img src="<?php echo siteUrl; ?>site_image/product/<?php echo $row->media_thumb; ?>" alt="<?php echo $row->product_name; ?>" />
		</a>
    </span>
    
    
	<h4><a href="<?php echo siteUrl; ?>detail/<?php echo $row->product_id; ?>HXYK<?php echo $row->slug; ?>TZS1YL<?php echo $row->color_id; ?>">
		<?php echo $row->brand_name.' '.$row->product_name ; ?>
    </a></h4>
	<h3>$<?php $price = $row->product_price;
	if($promoType != '' && $promoValue != ''){
		$promotionId = $promoArr['promo_id'];
		if($promoType == 'percent'){
                    $disPrice = $price - (($price * $promoValue) / 100); 
                    $discount=str_replace('.00', '',$promoValue).'% <br/> Off';  
                    
                }
		elseif($promoType == 'amount'){ 
                    $disPrice = $price - $promoValue; 
                    $discount='Save $'.$promoValue; 
                    
                }
		echo formatCurrency($disPrice); ?> <strong>$<?php echo formatCurrency($price); ?></strong> <span class="discount"><?php echo $discount; ?></span><?php
	}if((isset($row->product_rrp) && $row->product_rrp > $row->product_price) && ($promoType == '' && $promoValue == '') ){ 
                                    $price=$row->product_rrp;
                                    $disPrice=$row->product_price;
                                    $discount1=(($row->product_rrp - $row->product_price) / $row->product_rrp) * 100;
                                    $discount=substr($discount1, 0, 2).'% <br/> Off';  
                                    echo formatCurrency($disPrice); ?> <strong>$<?php echo formatCurrency($price); ?></strong> <span class="discount"><?php echo $discount; ?></span>
        <?php }if((($row->product_rrp < $row->product_price)||($row->product_rrp == $row->product_price)) && ($promoType == '' && $promoValue == '') ){ 
                                    $disPrice=$row->product_price;
                                    echo formatCurrency($disPrice); ?>
        <?php
        }
                                    
                                    
                                    ?>
	</h3>
<?php if(!isset($promotionId)){
    $promotionId=0;
} ?>
    
    <!-- cart button niche s upr ane wala 
    <span class="cartButtonBlock">
    	<button type="button"
        onclick="addProductToCart(<?php echo $row->product_id; ?>, <?php echo $row->color_id; ?>, <?php echo $price; ?>, <?php echo $disPrice; ?>, <?php echo $promotionId; ?>)">Add to Cart</button>
    </span>-->
    
    
    <a href="javascript:void(0)" onclick="addProductToCart(<?php echo $row->product_id; ?>, <?php echo $row->color_id; ?>, <?php echo $price; ?>, <?php echo $disPrice; ?>, <?php echo $promotionId; ?>,0)" class="addtocart_button">ADD TO CART</a>
</li>