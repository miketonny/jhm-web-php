<?php include "include/new_top.php";?>
<?php include "include/new_header.php";?>
<?php chkParam($_SESSION['user'], siteUrl);
$user_id = $_SESSION['user'];
?>

<section class="block-pt5">
    <div class="container">

	<div  class="dashboard-holder">

   <div class="col-sm-12">
    	<h1 class="mainHeading">Wishlist </h1>
            </div>
           <div class="col-md-3 col-sm-4">
      	<?php include 'include/userNavigation.php';?>
           </div>

<div class="col-md-9 col-sm-8">
                <div class="clr"> </div>
<div id="wishlist" style="width:100%;">
        <h3> My Wishlist </h3>
        	<ul>
            <?php
$Query = "SELECT tuw.* FROM tbl_user_wishlist tuw WHERE tuw.user_id = '" . $user_id . "' ORDER BY tuw.recid DESC";
$pro_rs = mysqli_query($con, $Query);
$num = mysqli_num_rows($pro_rs);
if ($num > 0) {
	while ($pro_row = mysqli_fetch_object($pro_rs)) {
		$id = $pro_row->product_id;
		$query1 = "SELECT tp.*, tbl_brand.brand_id AS brandId, tbl_brand.brand_name,
                    GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
                    LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
                    LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
                    WHERE tp.product_id = '$id' GROUP BY tp.product_id";
		$rs = exec_query($query1, $con);
		$row = mysqli_fetch_object($rs);
		/* fetch price */
		$priceRs = exec_query("SELECT product_price FROM tbl_product_price WHERE product_id = '$id' AND color_id = '$pro_row->color_id'", $con);
		$priceRow = mysqli_fetch_object($priceRs);
		$price = $priceRow->product_price;
		/* chk promotion */
		$all_cat = $row->all_cat;
		$promoType = '';
		$promoValue = '';
		$promoArr = getPromotionForProduct($id, $row->brandId, $all_cat, $con);
		if (!empty($promoArr)) {
			$promoType = $promoArr['percent_or_amount'];
			$promoValue = $promoArr['promo_value'];
		}
		/* img */
		$img = mysqli_fetch_object(mysqli_query($con, "SELECT media_src, media_thumb FROM tbl_product_media WHERE media_type = 'img' AND is_main = 1 AND product_id = '$id' AND color_id = '$pro_row->color_id'"));
		?>

            	<li>
                	<?php $src = (isset($img->media_thumb) && $img->media_thumb != '') ? 'site_image/product/' . $img->media_thumb : 'images/product.png';?>
                	<a href="<?php echo siteUrl; ?>detail/<?php echo $pro_row->product_id; ?>HXYK<?php echo $row->slug; ?>TZS1YL<?php echo $pro_row->color_id; ?>">
 	                	<img src="<?php echo siteUrl . $src; ?>" />
                    </a>

                    <span class="wishlist_info" style="width:80%;">
                    <p> <img src="<?php echo siteUrl; ?>images/favo.png" /> Added on <?php echo date('d M, Y', strtotime($pro_row->datetime)); ?> </p>
                    <h2>
                    	<a href="<?php echo siteUrl; ?>detail/<?php echo $pro_row->product_id; ?>HXYK<?php echo $row->slug; ?>TZS1YL<?php echo $pro_row->color_id; ?>">
							<?php echo $row->brand_name . ' ' . $row->product_name; ?>
                        </a>
                    </h2>

                    <h4  style="margin:14px 0px;"><?php
if ($promoType != '' && $promoValue != '') {
			if ($promoType == 'percent') {
				$disPrice = $price - (($price * $promoValue) / 100);
				$discount = str_replace(".00", "", $promoValue) . "% off";
			} elseif ($promoType == 'amount') {
				$disPrice = $price - $promoValue;
				$discount = "save $" . $promoValue;
			}?>
                            $ <?php echo formatCurrency($disPrice); ?>
                            <del style="font-size:14px;">$ <?php echo formatCurrency($price); ?></del>
                            <strong style="font-size:14px;"><?php echo $discount; ?></strong><?php
} else {echo '$ ' . formatCurrency($price);}?>
                    </h4>

                    <form action="<?php echo siteUrl; ?>action_model.php" method="post">
                        <input type="submit" value="ADD TO CART" class="buynow_wishlist" />
                        <input type="button" value="X" class="buynow_wishlist" onclick="deletePro(<?php echo $pro_row->recid; ?>);" />
                        <input type="hidden" value="addToCart" name="action" />
                        <input type="hidden" value="<?php echo $id; ?>" name="product_id" />
                        <input type="hidden" value="<?php echo $pro_row->color_id; ?>" name="color_id" />
                        <input type="hidden" value="<?php echo $price; ?>" name="price" />
                        <input type="hidden" value="<?php echo ($promoType != '' && $promoValue != '') ? $disPrice : 0; ?>" name="promoPrice" />
                        <input type="hidden" value="<?php echo ($promoType != '' && $promoValue != '') ? $promoArr['promo_id'] : 0; ?>" name="promoId" />
                        <input type="hidden" value="1" name="selectQty" />
                        <input type="hidden" value="<?php echo $pro_row->recid; ?>" name="comeFromWishlist" />
                    </form>
                    </span>
                    <div class="clr"> </div>
                </li>

				<?php }} else {echo '<li> <div class="item_detail"> <h4>Wishlist is Empty...</h4> </div> <div class="clr"></div> </li>';}?>

                <?php if ($num > 0) {?>
                <button type="button" onclick="emptyWishlist();" class="cart_button" >Empty Wishlist</button>
                <button type="button" onclick="moveAllWishtoCart();" class="cart_button" >Move all to Cart</button>
                <?php }?>
                <button type="button" onclick="window.location='<?php echo siteUrl; ?>product-search/'" class="cart_button">Continue Shopping</button>
            </ul>
        </div>

</div>


<div class="clr"> </div>
	</div>


  </div>
</section>




<?php include "include/new_footer.php";?>


<script>
function deletePro(id){
	if(confirm('Do you want to remove this product from Wishlist?')){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200){ alert(xmlhttp.responseText); location.reload(); }
		}
		xmlhttp.open("GET", "<?php echo siteUrl; ?>userdelete.php?table=tbl_user_wishlist&id="+id+"&pk=recid&dataTempId=a1x131023ed0xq31d4ld1f200t71.cloud.uk", true);
		xmlhttp.send();
	}
}
function emptyWishlist(){
	if(confirm('Do you want to Empty the Wishlist?')){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200){ alert(xmlhttp.responseText); location.reload(); }
		}
		xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=emptyWishlist&dataTempId=tqa7ea153102ded0xq31d4ld1f200571.cloud.uk", true);
		xmlhttp.send();
	}
}
function moveAllWishtoCart(){
	if(confirm('Do you want to move all Products to Cart?')){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200){ alert(xmlhttp.responseText); location.reload(); }
		}
		xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=moveAllProductWishlistToCart&dataTempId=tqa72a1561029ed0dd31d4fg20h571.cloud.nz", true);
		xmlhttp.send();
	}
}
</script>
<?php if (isset($_SESSION['promoArr'])) {unset($_SESSION['promoArr']);}?>

<?php include "include/new_bottom.php";?>