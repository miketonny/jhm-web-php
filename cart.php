<?php 
include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>
<?php //include("include/header.php"); ?>
<?php
if(isset($_SESSION['user']) && $_SESSION['user'] != ''){ $user_id = $_SESSION['user']; }
elseif(isset($_SESSION['tempUser']) && $_SESSION['tempUser'] != ''){ $user_id = $_SESSION['tempUser']; }
else{ redirect(siteUrl); }

/* get cart sum */
$sumOfCart = getCartValue($user_id, $con);
?>
<style> .qtty{ width:50px; }
#guestForm, #normalForm{ display:none; border: thin solid #e1e1e1; display: none; margin: 10px 0; padding: 10px; }
.txtMargin{ margin:0 0 7px !important; }
.recentProduct, .similarProduct{ display:none; }
</style>
<section class="block-pt5">
    <div class="container">
        <div class="shopping-holder">
       <div class="col-sm-8">
        <form action="<?php echo siteUrl; ?>action_model.php" method="post">
            <div id="shopingbag" class="shopingbag-box">
				<?php $i = 1;
				$grandTotal = 0;
				$grandTotalOriginalPrice = 0;
				$promotionDiscount = 0;
				$promoCodeDiscount = 0;
				$promoCodeChk = 'no';
				$amGst = 0;
				$cdate = date('Y-m-d H:i:s');
				$cartQuery = "SELECT tbl_cart.*, case isbackOrd when '1' then 'Backorder' else '' end as backOrd, tbl_product.product_name, tbl_product.slug, tbl_product_color.color_code, tbl_brand.brand_name FROM tbl_cart
				LEFT JOIN tbl_product ON tbl_product.product_id = tbl_cart.product_id
				LEFT JOIN tbl_product_color ON tbl_product_color.color_id = tbl_cart.color_id
				LEFT JOIN tbl_brand ON tbl_brand.brand_id = tbl_product.brand_id
				WHERE tbl_cart.user_id = '".$user_id."' ORDER BY tbl_cart.datetime DESC";
				$pro_rs = mysqli_query($con, $cartQuery);
				$numCart = mysqli_num_rows($pro_rs); ?>
				<h3> My Shopping Bag (<?php echo $numCart; ?> Item) </h3>
				<ul>
				<?php
				$grandtotalwithGST=0;
                if($numCart > 0){
                    $discountPromo = '';
		$discountPromoCode = '';
		$isPromotion = false;
		$isPromoCode = false;
        $subTotalAllProducts = 0;
        $priceAfterDiscount=0;
                    while($pro_row = mysqli_fetch_object($pro_rs)){
						$img = mysqli_fetch_object(mysqli_query($con, "SELECT media_src, media_thumb FROM tbl_product_media WHERE media_type = 'img' AND is_main = 1  AND product_id = '$pro_row->product_id' AND color_id = '$pro_row->color_id'"));
						
						include 'include/promotionCalc.php';
						$grandtotalwithGST= $grandtotalwithGST+$priceFull;
                    ?>
					<li>
						<span class="item_img">
							<?php $src = (isset($img->media_thumb) && $img->media_thumb != '')?'site_image/product/'.$img->media_thumb:'images/product.png'; ?>
							<a href="<?php echo siteUrl; ?>detail/<?php echo $pro_row->product_id; ?>HXYK<?php echo $pro_row->slug; ?>TZS1YL<?php echo $pro_row->color_id; ?>">
                            	<img src="<?php echo siteUrl.$src; ?>" />
                            </a>
						</span>
						
						<div class="item_detail">
							<h4><a href="<?php echo siteUrl; ?>detail/<?php echo $pro_row->product_id; ?>HXYK<?php echo $pro_row->slug; ?>TZS1YL<?php echo $pro_row->color_id; ?>">
								<?php echo $pro_row->brand_name.' '.$pro_row->product_name; ?>
                            </a></h4>
							<p>Size : Set </p>
							<strong> Qty : <input type="number" value="<?php echo $pro_row->qty; ?>" min="1" required name="qty[]" class="qtty" />
							</strong>
						</div>
						
						<div class="item_price">
							<strong>$ <?php //echo $discountPromo;
								if(isset($discountPromo) && ($discountPromo != 0)){
									//echo formatCurrency(($priceFull-round(formatCurrency($discountPromo), 2))*$pro_row->qty );
									echo $pro_row->qty *$pro_row->product_promo_price;
								}
								else{
//									echo formatCurrency($pro_row->qty * $priceFull);
                                                                        echo $pro_row->qty *$pro_row->product_promo_price;
								}?>
							</strong>
							
							<?php if($isPromotion){ ?>
								<p>$ <?php //echo formatCurrency($pro_row->qty * $priceFull); 
                                                                echo $pro_row->qty *$pro_row->product_price;
                                                                ?> </p>
                            <?php } ?>
                            
                            <?php if($isPromoCode){ ?> <h4>(Promo Code Applied)</h4> <?php } ?>
						</div>
						
						<span class="item_bottom">
						 
						<?php if(($pro_row->backOrd)!=''){ ?>
							<span class="backorderLine"><strong><?php echo $pro_row->backOrd; ?></strong></span>
						<?php } ?>
							<a href="javascript:void(0);" onclick="addToWish(<?php echo $pro_row->cart_id.','.$pro_row->product_id.','.$pro_row->color_id; ?>);"> Add to Wish List </a>
							<a href="javascript:void(0)" onclick="deleteProCart(<?php echo $pro_row->cart_id; ?>);">
								<img src="<?php echo siteUrl; ?>images/delete_10.png" />
							</a>
						</span>
						<div class="clr"> </div>
						<input type="hidden" value="<?php echo $pro_row->cart_id; ?>" name="cartId[]" />
					</li>
					
					<?php } }else{ echo '<li> <div class="item_detail"> <h4>Cart is Empty...</h4> </div> <div class="clr"></div> </li>'; }?>
					
					<?php if($numCart > 0){ ?>
					<button type="submit" class="cart_button">Update Cart</button>
                    <button type="button" onclick="moveAllCartToWish();" class="cart_button" >Move all to Wishlist</button>
					<button type="button" onclick="emptyCart();" class="cart_button" >Empty Cart</button>
					<?php } ?>
					<button type="button" onclick="window.location='<?php echo siteUrl; ?>product-search/'" class="cart_button">Continue Shopping</button>
					<input type="hidden" value="cartUpdate" name="action" />
				</ul>
			</div> <!--SHOPPING BAG END ---------------------------------------------------------------------------------------->
		</form>
       </div>
        <div class="col-sm-4">
        <?php include 'include/cartRightBar.php'; ?>
        </div>
        <div class="clr"> </div>
        </div>
        </div>
</section>
<?php include("include/new_footer.php"); ?>
<script>
function deleteProCart(id){
	if(confirm('Do you want to remove this product from Cart?')){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200){ alert(xmlhttp.responseText); location.reload(); }
		}
		xmlhttp.open("GET", "<?php echo siteUrl; ?>userdelete.php?table=tbl_cart&id="+id+"&pk=cart_id&dataTempId=tqa7ea153102ded0xq31d4ld1f200571.cloud.uk", true);
		xmlhttp.send();
	}
}
function emptyCart(){
	if(confirm('Do you want to Empty the Cart?')){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200){ alert(xmlhttp.responseText); location.reload(); }
		}
		xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=emptyCart&dataTempId=tqa7ea153102ded0xq31d4ld1f200571.cloud.uk", true);
		xmlhttp.send();
	}
}
function chkPromoCode(){
	code = document.getElementById('promoCode').value;
	if(code == '' || code == null){ return false; }
	return true;
}
function removePromoCode(){
	if(confirm('Do you want to Remove the Promo Code?')){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200){ alert(xmlhttp.responseText); location.reload(); }
		}
		xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=removePromoCode&dataTempId=tqa7xa153f02d0xq316ld1f20771.cloud.uk", true);
		xmlhttp.send();
	}
}
function addToWish(cartId, pid, cid){
	if(confirm('Do you want to save this product in Wishlist?')){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200){ alert(xmlhttp.responseText); location.reload(); }
		}
		xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=addToWish&pid="+pid+"&cid="+cid+"&cartId="+cartId+"&dataTempId=sz7xa15g662d0xq316ld1fhu1.cloud.uk", true);
		xmlhttp.send();
	}
}

function moveAllCartToWish(){
	if(confirm('Do you want to move all Products to Wishlist?')){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200){ alert(xmlhttp.responseText); location.reload(); }
		}
		xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=moveAllProductCartToWishlist&dataTempId=tqa72a1561029ed0dd31d4fg20h571.cloud.nz", true);
		xmlhttp.send();
	}
}
</script>
<?php if(isset($_SESSION['promoArr'])){ unset($_SESSION['promoArr']); } ?>

<!-- choose sign up type popup -->
<?php 
if(isset($_SESSION['abc'])){
?>
<div id="chooseSignUp" style="width:430px; left:64%; ">
	<div id="loginBox">
        <h4 class="signin">Checkout as a Guest or Register</h4>
        <p>Register with us for future convenience</p>

    	<div class="right_side" style="float: none;">
            
            <div id="expresscheckout">
                <input type="radio" name="utype" value="guest" id="guestRadio" checked="checked" onclick="chkUserType();" />
                <label for="guestRadio">Sign Up as Guest User</label>
                <input type="radio" name="utype" value="normal" id="norRadio" onclick="chkUserType();" />
                <label for="norRadio">Sign Up as Normal User</label><br/>
            </div>
            
            <div id="guestForm">
                <form class="signin" action="<?php echo siteUrl; ?>action_model.php" method="post" style="display:block !important;" id="formGuest" >
                    <input type="email" name="email" placeholder="Enter email address" required class="txtMargin" id="emailGuest" /> <br/>
                    <input type="submit" name="button" value="GO" class="loginbutton txtMargin" style="width:100px; margin-top:0px;">
                    <input name="action" type="hidden" value="userSignUpFromDetailExpressCheckout" />
                    <input name="type" type="hidden" value="guest" />
                    <input name="isProduct" type="hidden" value="isProduct" />
                </form>
            </div>
            
            <div id="normalForm">
                <form class="signin" action="<?php echo siteUrl; ?>action_model.php" method="post" style="display:block !important;" id="formNormal" >
                    <input type="email" name="email" placeholder="Enter email address" required class="txtMargin" id="emailNormal" /> <br/>
                    <input type="password" name="pass" placeholder="Password" required class="txtMargin" id="passNormal" /> <br/>
                    <input type="submit" name="button" value="GO" class="loginbutton" style="width:100px; margin-top:0px;">
                    <input name="action" type="hidden" value="userSignUpFromDetail" />
                    <input name="type" type="hidden" value="normal" />
                </form>
            </div>
            <p style="font-weight:bold;">Register and save time!</p>
            <p>> Fast and easy Checkout,</p>
            <p>> Easy access to your order history and status,</p>
		</div>
    </div>
</div>
<?php } ?>
<!-- choose sign up type popup -->
<script>
function chkUserType(){
	document.getElementById('guestForm').style.display = 'none';
	document.getElementById('normalForm').style.display = 'none';
	guest = document.getElementById('guestRadio');
	nor = document.getElementById('norRadio');
	
	if(guest.checked == true){ document.getElementById('guestForm').style.display = 'block'; }
	else if(nor.checked == true){ document.getElementById('normalForm').style.display = 'block'; }
}
chkUserType();
</script>
<?php include("include/new_bottom.php"); ?>