<?php include "include/new_top.php";?>
<?php include "include/new_header.php";?>
<?php include_once "include/analyticstracking.php"?>
<?php // echo "<pre>";
//echo "----";
//print_r($_SESSION);exit; ?>
<?php chkParam($_GET['alias'], siteUrl);
$proData = explode('HXYK', $_GET['alias']);
$id = $proData[0];
$proData1 = explode('TZS1YL', $proData[1]);
$alias = $proData1[0];
$colorId = $proData1[1];
if (!isset($colorId)) {
	die;
}
$cartQty = 0;
$query = "SELECT tp.*, tbl_brand.brand_id AS brandId, tbl_brand.brand_name, GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
WHERE tp.product_id = $id AND tp.is_activate = 1 GROUP BY tp.product_id";
$rs = exec_query($query, $con);
$num = mysqli_num_rows($rs);
if ($num == 0 || $num != 1) {redirect('../');}
$productRow = mysqli_fetch_object($rs);
/* fetch price */
$priceRs = exec_query("SELECT product_price, qty as stock, backorder_qty as backorderstock FROM tbl_product_price WHERE product_id = $id AND color_id = $colorId", $con);
$priceRow = mysqli_fetch_object($priceRs);
$price = $priceRow->product_price;
$backorderRs = exec_query("SELECT SUM(od_qty) as backorder_qty FROM tbl_order_item WHERE product_id = $id AND color_id=$colorId AND isbackOrd=1", $con);
$backorderRow = mysqli_fetch_object($backorderRs);
$backorderQty = $backorderRow->backorder_qty;
$cartRs = exec_query("SELECT qty FROM tbl_cart WHERE product_id = $id AND color_id = $colorId", $con);
$cartItem = mysqli_fetch_object($cartRs);
if (isset($cartItem) && $cartItem->qty > 0) {
	$cartQty = $cartItem->qty;
}
if ($productRow->stock_availability == 2) {
	//back order allowed for prod
	$priceRow->stock = $priceRow->backorderstock - $backorderQty;
	$priceRow->stock = $priceRow->stock - $cartQty;
	$priceRow->stock = $priceRow->stock < 0 ? 0 : $priceRow->stock;
	//$priceRow->stock = $priceRow->product_price->backorder_qty;
} else {
	$priceRow->stock = $priceRow->stock - $backorderQty;
	$priceRow->stock = $priceRow->stock - $cartQty;
	$priceRow->stock = $priceRow->stock < 0 ? 0 : $priceRow->stock;
}

/* chk promotion */
$all_cat = $productRow->all_cat;
$promoType = '';
$promoValue = '';
$promoArr = getPromotionForProduct($id, $productRow->brandId, $all_cat, $con);
//echo "<pre>";
//print_r($promoArr);
////echo $query = "SELECT tp.*, tbl_brand.brand_id AS brandId, tbl_brand.brand_name, GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
////LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
////LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
////WHERE tp.product_id = $id GROUP BY tp.product_id";
////print_r($all_cat);
////print_r($productRow);
////print_r($id);
////print_r($promoArr);
//exit;
if (!empty($promoArr)) {
	$promoType = $promoArr['percent_or_amount'];
	$promoValue = $promoArr['promo_value'];
}
// set session for category.php page, so that on press back button last searched query worked, which is in session
$_SESSION['isLastPageIsDetail'] = 'yes';
?>



<style>
#imgCon{ border: 1px solid #adadad; float: left; margin-right: 18px; width:450px !important; }
#imgCon div{ width:450px !important; }
/* for qty */
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number] {
    -moz-appearance:textfield;
}
.btnQty{ font-weight:bold; outline:none; }
#qty10{ display:none; }
.exempleAvg{ z-index:0 !important; }
</style>


<!--<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">
	stLight.options({
		publisher: "6f857400-5eaa-4da3-b6a9-0f979a3d5dc9", doNotHash: false, doNotCopy: false, hashAddressBar: false
	});
</script>-->



<?php if (isset($_SESSION['openCartDialog'])) {
	echo '<script> getCartDataInDialog(); </script>';
	unset($_SESSION['openCartDialog']);
}
?>
<!-- rating star js n css -->
<link rel="stylesheet" href="<?php echo siteUrl; ?>jquery/jRating.jquery.css" type="text/css" />
<script type="text/javascript" src="<?php echo siteUrl; ?>jquery/jRating.jquery.js" ></script>
<script type="text/javascript" language="javascript" src="<?php echo siteUrl; ?>carouFredSel/jquery-1.8.2.min.js"></script>

<section class="block-pt5">
    <div class="container">


	<div class="productDetails">
    <div  class="col-sm-12">
		<div class="product">
			<script type="text/javascript" src="<?php echo siteUrl; ?>pdZoom/multizoom.js"></script>
            <link rel="stylesheet" href="<?php echo siteUrl; ?>pdZoom/multizoom.css" type="text/css" />
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    $('#multizoom2').addimagezoom({
                        zoomrange: [2, 4],
                        magnifiersize: [300,300],
                        magnifierpos: 'right',
                        cursorshade: true,
                        disablewheel: true
                    });
                })
            </script>

        	<div id="productsImages">

            	<?php
$imageRs1 = exec_query("SELECT media_src, media_thumb FROM tbl_product_media WHERE product_id = $id AND color_id = $colorId AND media_type = 'img' AND is_main = 1", $con);
$imageRs0 = exec_query("SELECT media_src, media_thumb FROM tbl_product_media WHERE product_id = $id AND color_id = $colorId AND media_type = 'img' AND is_main = 0", $con);
?>
				<div class="multizoom2 thumbs">
                	<?php
while ($imageRow1 = mysqli_fetch_object($imageRs1)) {
	$shareimage = $imageRow1->media_src;
	?>
                        <a href="<?php echo siteUrl; ?>site_image/product/<?php echo $imageRow1->media_src; ?>" data-large="<?php echo siteUrl; ?>site_image/product/<?php echo $imageRow1->media_src; ?>">
                            <img src="<?php echo siteUrl; ?>site_image/product/<?php echo $imageRow1->media_thumb; ?>" title=""/>
                        </a>
					<?php }

while ($imageRow0 = mysqli_fetch_object($imageRs0)) {
	//$shareimage = $imageRow->media_src;
	?>
                        <a href="<?php echo siteUrl; ?>site_image/product/<?php echo $imageRow0->media_src; ?>" data-large="<?php echo siteUrl; ?>site_image/product/<?php echo $imageRow0->media_src; ?>">
                            <img src="<?php echo siteUrl; ?>site_image/product/<?php echo $imageRow0->media_thumb; ?>" title=""/>
                        </a>
					<?php }?>
                </div>
				<?php if ($priceRow->stock <= 0) {?>
				<!--<div id="outOfStock"></div>-->
                                <div id="lessStock">
					<p>Out of Stock</p>
				</div>
				<?php } else if ($priceRow->stock <= 3) {?>
				<div id="lessStock">
					<p><?php echo $priceRow->stock; ?> Items Left</p>
				</div>
				<?php }?>
            	<div class="targetarea diffheight">
                	<img id="multizoom2" alt="zoomable" title="" src="<?php echo siteUrl; ?>site_image/product/<?php echo $shareimage; ?>"/>
                    <!--style="height:600px;"-->
              	</div>
            </div>


            <meta property="og:site_name" content="JHM.co.nz">
			<meta property="og:title" content="<?php echo $productRow->product_name . " - Price : " . $price; ?>">
            <meta property="og:image" content="<?php echo siteUrl; ?>ad_img/<?php echo $shareimage; ?>" />
            <meta property="og:image:width" content="700">


            <div class="right_side">
            	<h4> <?php echo $productRow->brand_name . ' ' . $productRow->product_name; ?> <span id="setColNameHere" style="font-weight:normal;"></span></h4>
                <div class="size_button">
                	<span class="parameter"><strong>SKU#</strong><?php echo $productRow->product_sku; ?></span>
   		            <span class="parameter"><strong>Size</strong><?php echo $productRow->size; ?></span>
                    <a href="javascript:void(0);" onclick="return expandquestion();" class="haveaquestion">Have a Question?</a>

					<div id="questionWrapper">
						<div style="padding:10px;">
						<p>Any question in your mind? Ask Now!</p>
						<div style="text-align:left;">
							<form class="formQuestion" action="<?php echo siteUrl; ?>action_model.php" method="post" >
								<div>
									<input type="email" name="email" placeholder="Enter Email Address" required class="txtMargin" value="<?php if (isset($_SESSION['user_email']) && $_SESSION['user_email'] != '') {echo $_SESSION['user_email'];}?>" />
									<select required name="type" style="margin:0 0 7px;">
										<option value="">- SELECT QUERY TYPE -</option>
										<option>Sales</option>
										<option>Complaint</option>
										<option>Other</option>
									</select>
								</div>
								<div>
									<input type="text" class="questionTitle" name="title" placeholder="Subject" required class="txtMargin" />
									<textarea required="required" name="desc" class="questionDesc" placeholder="Question" style="height:100px;" ></textarea>
								</div>
								<input type="submit" name="button" value="SUBMIT" class="loginbutton txtMargin">
								<input name="action" type="hidden" value="askAQuestion" />
							</form>
						</div>
						</div>
					</div>

                </div>
                <div id="clr"></div>
                <p><?php
$avgRs = exec_query("SELECT AVG(rating) AS avg FROM tbl_review WHERE product_id = '$id'", $con);
if (mysqli_num_rows($avgRs)) {
	$ratingAvgRow = mysqli_fetch_object($avgRs);
	if (isset($ratingAvgRow->avg) && $ratingAvgRow->avg != '') {$ratingAvg = $ratingAvgRow->avg;} else { $ratingAvg = 0;}
} else { $ratingAvg = 0;}
?>
                	<!-- show AVG rating -->
                    <script type="text/javascript">
					  $(document).ready(function(){
						$(".exempleAvg").jRating({
						  length:5,
						  decimalLength:1,
						  showRateInfo:false,
						  isDisabled : true
						});
					  });
					</script>
                    <div class="exemple">
                        <div class="exempleAvg" data-average="<?php echo $ratingAvg; ?>" data-id="600"></div>
                    </div>

                </p>
				<hr/>
                <div  class="colors">
                    <?php // AND color_id != $colorId
$colorCode = '';

$colorRs = exec_query("SELECT pdclr.color_code, pdclr.color_id, color FROM tbl_product_color pdclr left join tbl_color clr on pdclr.color_code = clr.color_code WHERE pdclr.product_id = $id", $con);

if (mysqli_num_rows($colorRs) > 1) {
	echo '<p> Also available in colors: </p>';
	while ($colorRow = mysqli_fetch_object($colorRs)) {
		$colorClass = '';
		if ($colorRow->color_id == $colorId) {
			$colorCode = $colorRow->color_code;
			$colorClass = "activeColor";}
		?>
							<a href="<?php echo siteUrl; ?>detail/<?php echo $id; ?>HXYK<?php echo $alias; ?>TZS1YL<?php echo $colorRow->color_id; ?>">
								<span class="colorBlock colorBlockLarge <?php echo $colorClass; ?>" title="<?php echo $colorRow->color; ?>" style="background-color:<?php echo $colorRow->color_code; ?>;"> &nbsp; &nbsp; </span>
							</a>
						<?php
}
} else {
	while ($colorRow = mysqli_fetch_object($colorRs)) {
		$colorClass = '';
		if ($colorRow->color_id == $colorId) {
			$colorCode = $colorRow->color_code;
			$colorClass = "activeColor";}
		?>

						<?php
}
}
$colorNameRs = exec_query("SELECT color, display_name FROM tbl_color WHERE color_code = '$colorCode'", $con);
$colorNameRow = mysqli_fetch_object($colorNameRs);
?>

                </div>

                <div class="selling_price">
                	<h1>Selling Price
                    <?php
//                    echo "-->".$promoValue;
if ($promoType != '' && $promoValue != '') {
	if ($promoType == 'percent') {

		$disPrice = $price - round((($price * $promoValue) / 100), 2);
		$discount = str_replace(".00", "", $promoValue) . "% Off";
	} elseif ($promoType == 'amount') {
		$disPrice = $price - $promoValue;
		$discount = "Save $" . $promoValue;
	}
	//$gstDisPrice = $disPrice + (($disPrice * 15) / 100);
	?>
                        <strong>$ <?php echo round($disPrice, 2, PHP_ROUND_HALF_UP); ?></strong>
                        <del>$ <?php echo formatCurrency($price); ?></del>&nbsp;<span class="discount" style="width:100px;color: red;"><?php echo $discount; ?></span><?php
} else {
	//$gstPrice = $price + (($price * 15) / 100);
	echo '<strong>$ ' . round($price, 2, PHP_ROUND_HALF_UP) . '</strong>';
}?>
                    </h1>
					<?php
$avail = $productRow->stock_availability;
if ($avail == 1) {
	echo '<p class="stock" >Out of Stock</p>';
}
?>
                    <?php if ($avail != 1) {
	?>

                    <form action="<?php echo siteUrl; ?>action_model.php" method="post" id="addCartForm" >
					<p class="stock" ><?php
if ($avail == 0) {
		echo 'In Stock';
		$qtyClass = '';
		$qty = $priceRow->stock;
		if (($priceRow->stock) == 0) {
			$qtyClass = "colorred";
		} else if (($priceRow->stock) <= 5 && ($priceRow->stock) > 0) {
			$qtyClass = "colororange";
		}
		if ($qty > 10) {
			echo "<strong class='itemleft " . $qtyClass . "'><b>10+</b></strong>";
		} elseif ($qty <= 5) {
			echo "<strong class='itemleft " . $qtyClass . "'><b>low stock</b></strong>";
		}
		//echo "<strong class='itemleft ".$qtyClass."'><b>".$priceRow->stock."</b></strong>";
	} else {
		echo 'Backorder Allowed';
		/*$qtyClass='';

							if(($priceRow->stock)==0){
								$qtyClass="colorred";
							}else if(($priceRow->stock)<=3 && ($priceRow->stock)>0){
								$qtyClass="colororange";
							}

							echo "<strong class='itemleft ".$qtyClass."'><b>".$priceRow->stock."</b></strong>";*/
	}
	?>
						<span class="quantity" style="margin-bottom:10px;">QTY:
                        	<select style="padding:5px;" onchange="setNewQty(this.value);" id="selectQty" name="selectQty" required>
                                <script> var i = 1;
                                while(i <= <?php echo $priceRow->stock; ?>){
									document.write('<option> '+i+' </option>');
									i++;
								}
                                </script>
                            </select>
                            <b id="qty10">
                            	<input type="number" name="inputQty" id="inputQty" placeholder="Enter Qty"  />
                            </b>
                        </span>
						</p>

						<?php
$isbackord = 0;
	if ($avail == 2) {
		$isbackord = 1;
	}
	?>
                        <input type="hidden" value="addToCart" name="action" id="actionToChange" />
                        <input type="hidden" value="<?php echo $id; ?>" name="product_id" />
						<input type="hidden" value="<?php echo $isbackord; ?>" name="isbackord" />
                        <input type="hidden" value="<?php echo $colorId; ?>" name="color_id" />
                        <input type="hidden" value="<?php echo $price; ?>" name="price" />
                        <input type="hidden" value="<?php echo $promoPrice = ($promoType != '' && $promoValue != '') ? $disPrice : $price; ?>" name="promoPrice" />
                       	<input type="hidden" value="<?php echo $promoId = ($promoType != '' && $promoValue != '') ? $promoArr['promo_id'] : 0; ?>" name="promoId" />
                        <input type="hidden" name="redirect" id="redirectPage"  />
                         <?php if ($priceRow->stock != 0) {?>
                       	 <a class="bttn_1 addtocatbtn" onclick="addProductToCart(<?php echo $id . ',' . $colorId . ",'" . $price . "','" . $promoPrice . "','" . $promoId . "','" . $isbackord . "'"; ?>);">ADD TO CART</a>
						 <?php }?>

                        <!-- old button <input type="button" value="ADD TO CART" class="bttn_1" onclick="doneCheckout('addToCart');" />-->
                   	</form>
                    <?php }?>

<!-- choose signup type start top 50% -->
<script>
function chkUserType(){
	if ($('#guestForm').length > 0) {
		$('#guestForm').style.display = 'none';
	}
	if ($('#normalForm').length > 0) {
		$('#normalForm').style.display = 'none';
	}

	guest = document.getElementById('guestRadio');
	nor = document.getElementById('norRadio');
	if ($('#guestRadio').length > 0){
		if(guest.checked == true){
		document.getElementById('guestForm').style.display = 'block';
		}
	}
	else if ($('#norRadio').length > 0) {
		if(nor.checked == true){
		document.getElementById('normalForm').style.display = 'block';
	}
	} 
}
</script>
<style>
#guestForm, #normalForm{ display:none; border: thin solid #e1e1e1; display: none; margin: 10px 0; padding: 10px; }
.txtMargin{ margin:0 0 7px !important; }
.recentProduct, .similarProduct{ display:none; }
</style>
<?php
if (isset($_SESSION['abc'])) {
	?>
<div id="chooseSignUp" style="width:430px; left:64%; ">
	<div id="loginBox">
    	<h4 class="signin">Checkout as a Guest or Register</h4>
        <p>Register with us for future convenience</p>

        <div id="expresscheckout">
        	<input type="radio" name="utype" value="guest" id="guestRadio" checked="checked" onclick="chkUserType();" />
            <label for="guestRadio">Sign Up as Guest User</label>
            <input type="radio" name="utype" value="normal" id="norRadio" onclick="chkUserType();" />
            <label for="norRadio">Sign Up as Normal User</label><br/>
        </div>

        <div id="guestForm">
            <form class="signin" action="<?php echo siteUrl; ?>action_model.php" method="post" style="display:block !important;" id="formGuest" onsubmit="return getQtyForm();">
                <input type="email" name="email" placeholder="Enter email address" required class="txtMargin" id="emailGuest" /> <br/>
                <input type="submit" name="button" value="GO" class="loginbutton txtMargin" style="width:100px; margin-top:0px;">
                <input name="action" type="hidden" value="userSignUpFromDetailExpressCheckout" />
                <input name="type" type="hidden" value="guest" />
                <input name="isProduct" type="hidden" value="yes" />
                <!-- data needed for cart -->
                <input type="hidden" value="" name="qty" id="qtyGuest" />
                <input type="hidden" value="<?php echo $id; ?>" name="product_id" />
                <input type="hidden" value="<?php echo $colorId; ?>" name="color_id" />
                <input type="hidden" value="<?php echo $price; ?>" name="price" />
                <input type="hidden" value="<?php echo ($promoType != '' && $promoValue != '') ? $disPrice : 0; ?>" name="promoPrice" />
                <input type="hidden" value="<?php echo ($promoType != '' && $promoValue != '') ? $promoArr['promo_id'] : 0; ?>" name="promoId" />
            </form>
        </div>

        <div id="normalForm">
            <form class="signin" action="<?php echo siteUrl; ?>action_model.php" method="post" style="display:block !important;" id="formNormal" onsubmit="return getQtyForm();">
                <input type="email" name="email" placeholder="Enter email address" required class="txtMargin" id="emailNormal" /> <br/>
                <input type="password" name="pass" placeholder="Password" required class="txtMargin" id="passNormal" /> <br/>
                <input type="submit" name="button" value="GO" class="loginbutton" style="width:100px; margin-top:0px;">
                <input name="action" type="hidden" value="userSignUpFromDetail" />
                <input name="type" type="hidden" value="normal" />
                <input name="isProduct" type="hidden" value="yes" />
                <!-- data needed for cart -->
                <input type="hidden" value="" name="qty" id="qtyNormal" />
                <input type="hidden" value="<?php echo $id; ?>" name="product_id" />
				<input type="hidden" value="<?php echo $isbackord; ?>" name="isbackord" />
                <input type="hidden" value="<?php echo $colorId; ?>" name="color_id" />
                <input type="hidden" value="<?php echo $price; ?>" name="price" />
                <input type="hidden" value="<?php echo ($promoType != '' && $promoValue != '') ? $disPrice : 0; ?>" name="promoPrice" />
                <input type="hidden" value="<?php echo ($promoType != '' && $promoValue != '') ? $promoArr['promo_id'] : 0; ?>" name="promoId" />
            </form>
        </div>
        <p style="font-weight:bold;">Register and save time!</p>
        <p>> Fast and easy Checkout,</p>
        <p>> Easy access to your order history and status,</p>
    </div>
</div>
<?php }?>
<!-- choose sign up type end -->
<script> chkUserType(); </script>

                    <!-- save for letter btn are wishlist hai -->
                    <?php if (isset($_SESSION['user'])) {?>
                    <a href="javascript:void(0)" class="saveforlater"
                    onclick="<?php if (isset($_SESSION['user'])) {?>addToWish(<?php echo $id; ?>, <?php echo $colorId; ?>);<?php } else {?>activelogin('loginscreen');<?php }?>"> SAVE FOR LATER </a>
                    <?php }?>
                    <!-- express checkout btn -->
                    <?php /*onclick="<?php if(isset($_SESSION['user'])){ ?>doneCheckout('expressCheckout');<?php }else{ ?>signUpChooseDialog('chooseSignUp', 'top50');<?php } ?>" href="<?php echo siteUrl; ?>checkOut/"  */?>
					<?php if ($avail != 1) {if ($priceRow->stock != 0) {?>
						<a href="javascript:void(0)"  onclick="doneCheckout('expressCheckout');" class="bttn_1 cartbtn" >EXPRESS CHECKOUT</a>
                    <?php }}?>

                    <div class="rate-social" >
                        <p class="writeareview">
	                  <?php if (isset($_SESSION['user'])) {
	?>  <input  class="writeareview" id="writeareview" type="button" value="Rate this Product" onclick="<?php
if (isset($_SESSION['user'])) {
		$user_id = $_SESSION['user'];
		$chkRateRs = mysqli_query($con, "SELECT review_id FROM tbl_review WHERE user_id = '$user_id' AND product_id = '$id'");
		if (mysqli_num_rows($chkRateRs)) {?> alert('You’ve already rated this product.'); <?php } else {?> signUpChooseDialog('rateProductWrapper', 'top40'); <?php }
	} else {?>alert('Oops! Please Sign-in for Rate this Product,');<?php }?>" /><?php }?>


                   	</p>

                   <!-- <p class="sharing">
                    	<span class='st_facebook_large' displayText=''></span>
                        <span class='st_twitter_large' displayText=''></span>
                        <span class='st_googleplus_large' displayText=''></span>
                        <span class='st_pinterest_large' displayText=''></span>
                    </p>-->
                    </div>

                </div>

                <div class="product_summary">
                    <h1><a href="javascript:void(0)" id="desc_btn" onclick="switchdesctab('desc')" class="activedesctab">Description</a>
                    	<a onclick="switchdesctab('usage')" id="usage_btn" href="javascript:void(0)">Usage</a></h1>
                    <div class="content" id="desc_tab"><?php echo isset($productRow->product_summary) ? $productRow->product_summary : ""; ?></div>
                    <div class="content" id="usage_tab" style="display:none;"><?php echo isset($productRow->product_description) ? $productRow->product_description : ""; ?></div>
                </div>
                <script>
					function switchdesctab(tab){
						document.getElementById('desc_tab').style.display="none";
						document.getElementById('usage_tab').style.display="none";
						document.getElementById('usage_btn').className="";
						document.getElementById('desc_btn').className="";

						document.getElementById(tab+'_btn').className="activedesctab";
						document.getElementById(tab+'_tab').style.display="";
					}
				</script>
        	</div>

            <div class="clr"></div>
    	</div>

        <div class="clr"></div>

        <div id="recentProductTab">
        	<script>
            function productUlFunction(type){
				$('.recentProduct, .similarProduct, .alsoProduct').css('display', 'none');
				$('.'+type+'Product').css('display', 'block');

				document.getElementById('recentbtn').className='';
				document.getElementById('similarbtn').className='';
				document.getElementById('alsobtn').className='';
				document.getElementById(type+'btn').className='activeTabtn';
			}
            </script>
        	<p class="tabbtn">
            	<a href="javascript:void(0);" id="recentbtn" onclick="productUlFunction('recent');" class="activeTabtn">RECENT VIEWED PRODUCT</a>
            	<a href="javascript:void(0);" id="similarbtn" onclick="productUlFunction('similar');">SIMILAR PRODUCT</a>
                <a href="javascript:void(0);" id="alsobtn" onclick="productUlFunction('also');">YOU MAY ALSO LIKE</a>
          	</p>
       		<div class="recent-viewed products" style="float:none; width:100%;">
                <ul id="putProduct" class="recentProduct">
					<?php /* recent viewed ranmdom product */
$order = '';
$condi = '';
if (isset($_SESSION['user']) && $_SESSION['user'] != '') {$condi = " AND user_id = '" . $_SESSION['user'] . "' ORDER BY datetime DESC ";} elseif (isset($_SESSION['tempRecent']) && $_SESSION['tempRecent'] != '') {$condi = " AND user_id = '" . $_SESSION['tempRecent'] . "' ORDER BY datetime DESC ";}
if ($condi != '') {
//						$recentProRs12 = "SELECT * FROM tbl_user_recent_product WHERE product_id != 0 AND product_id != '$id' AND color_id != 0 $condi  group by product_id LIMIT 0,5";
	$recentProRs12 = "SELECT * FROM tbl_user_recent_product WHERE product_id != 0 AND product_id != '$id' AND color_id != 0 $condi  LIMIT 0,5";
	$recentProRs1 = exec_query($recentProRs12, $con);
	if (!$recentProRs1) {
		echo mysqli_error($con);
	}
	while ($recentProRow1 = mysqli_fetch_object($recentProRs1)) {

		$recentQ = "SELECT tp.product_id, tp.slug, tp.product_name, tbl_brand.brand_name,
							tp.brand_id, tpm.media_thumb, tpp.product_price,tpp.product_rrp, tpp.color_id, tpp.product_rrp,
							GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
							LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
							LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
							LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
							LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
							LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
							WHERE tpm.media_type = 'img' AND tpm.is_main = 1 AND tp.product_id = '$recentProRow1->product_id' AND tpp.color_id = '$recentProRow1->color_id'";
		$recentRs = exec_query($recentQ, $con);
		$row = mysqli_fetch_object($recentRs);
		/* for get promotion */
		$all_cat = $row->all_cat;
		$promoType = '';
		$promoValue = '';
		$promoArr = getPromotionForProduct($row->product_id, $row->brand_id, $all_cat, $con);
		if (!empty($promoArr)) {
			$promoType = $promoArr['percent_or_amount'];
			$promoValue = $promoArr['promo_value'];
		}
		include 'include/productRowForDetail.php';
	}
} else {echo 'No Recent Product!';}
?>
                </ul>

                <ul class="similarProduct" id="putProduct">
					<?php /* recent viewed */
$condiSimilar = '';
$all_cat = $productRow->all_cat;
if ($all_cat != '') {
	$catQ = "SELECT parent_id, superparent_id FROM `tbl_category` WHERE category_id IN ($all_cat)";
	$catRs = exec_query($catQ, $con);
	$catArray = array();
	while ($catRow = mysqli_fetch_object($catRs)) {
		if ($catRow->parent_id != 0) {$catArray[] = $catRow->parent_id;}
		if ($catRow->superparent_id != 0) {$catArray[] = $catRow->superparent_id;}
	}
	$categories = implode(',', $catArray);
	$categories = ($categories == '') ? $all_cat : $categories; //.','.$all_cat;
	$condiSimilar .= " AND tpcat.category_id IN ($categories)";
}

$similarQ = "SELECT tp.product_id, tp.slug, tp.product_name, tbl_brand.brand_name,
					tp.brand_id, tpm.media_thumb, tpp.product_price, tpp.color_id, tpp.product_rrp,
					GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
					LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
					LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
					LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
					LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
					LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
					WHERE tpm.media_type = 'img' AND tpm.is_main = 1 AND tpm.media_type = 'img' AND tp.is_activate = 1 $condiSimilar GROUP BY tp.product_id ORDER BY RAND()";
$similarRs = exec_query($similarQ . ' limit 0,5 ', $con);
while ($row = mysqli_fetch_object($similarRs)) {
	/* for get promotion */
	$all_cat = $row->all_cat;
	$promoType = '';
	$promoValue = '';
	$promoArr = getPromotionForProduct($row->product_id, $row->brand_id, $all_cat, $con);
	if (!empty($promoArr)) {
		$promoType = $promoArr['percent_or_amount'];
		$promoValue = $promoArr['promo_value'];
	}
	include 'include/productRowForDetail.php';
}
?>
                </ul>

                <ul id="putProduct" class="alsoProduct">
					<?php $similarRs = exec_query($similarQ . ' limit 0,5', $con);
while ($row = mysqli_fetch_object($similarRs)) {
	/* for get promotion */
	$all_cat = $row->all_cat;
	$promoType = '';
	$promoValue = '';
	$promoArr = getPromotionForProduct($row->product_id, $row->brand_id, $all_cat, $con);
	if (!empty($promoArr)) {
		$promoType = $promoArr['percent_or_amount'];
		$promoValue = $promoArr['promo_value'];
	}
	include 'include/productRowForDetail.php';
}
?>
                </ul>

                <script>productUlFunction('recent');</script>

            </div>
	        <div class="clr"></div>
        </div>
		<div class="clr"></div>



    	<div class="review">
        	<h1> CUSTOMER REVIEWS </h1>
            <ul>
			<?php $iReview = 1;
$reviewRs = exec_query("SELECT tbl_user.email, tbl_review.* FROM tbl_review LEFT JOIN tbl_user ON tbl_review.user_id = tbl_user.user_id WHERE tbl_review.product_id = '$id' AND tbl_review.is_approve = 1", $con);
if (mysqli_num_rows($reviewRs) > 0) {
	while ($reviewRow = mysqli_fetch_object($reviewRs)) {?>
				<script type="text/javascript">
				  $(document).ready(function(){
					$(".exemple<?php echo $iReview; ?>").jRating({
					  length:5,
					  decimalLength:1,
					  showRateInfo:false,
					  isDisabled : true
					});
				  });
                </script>
                <li>
                    <div style="float:left; width:150px;"><?php echo $reviewRow->email; ?></div>
                    <div class="exemple">
                        <div class="exemple<?php echo $iReview; ?>" data-average="<?php echo $reviewRow->rating; ?>" data-id="6"></div>
                        <span> <?php echo $reviewRow->rating; ?> / 5 </span>
                    </div>

                    <h4 style="display:inline-block; margin:5px 0px;"> <?php echo $reviewRow->title; ?></h4><br/>
                    <div><?php echo $reviewRow->review; //substr($reviewRow->review, 0, 150);   ?></div>
                    <h6 style="margin:5px 0px; font-size:12px;"><?php echo date('d M, Y h:i A', strtotime($reviewRow->datetime)); ?></h6>

                </li>
                <?php $iReview++;}
}
?>
            </ul>

    	</div>
		<div style="clear:both"> </div>


        </div>
        	<div style="clear:both"> </div>
    </div>



  </div>
</section>


<?php include "include/new_footer.php";?>

<script>
function getQtyForm(){
	qty = document.getElementById('selectQty').value;
	if(qty == 11){
		qty = document.getElementById('inputQty').value;
	}
	$('#qtyNormal, #qtyGuest').val(qty);

	if(qty != '' && !isNaN(qty)){
		return true;
	}
	else{
		alert('Something went wrong!');
		return false;
	}
}
function setNewQty(qty){
	document.getElementById('qty10').style.display = 'none';
	$('#selectQty, #inputQty').removeAttr('required');
	if(qty == 11){
		document.getElementById('qty10').style.display = 'inline-block';
		document.getElementById('selectQty').style.display = 'none';
		$('#inputQty').attr('required', 'required');
	}
	else{ $('#selectQty').attr('required', 'required'); }

}
function chkQty(qty){
	if(isNaN(qty)){
		alert('Please Enter Valid Quantity.');
		return false;
	}
	else{
		return true;
	}
}
function setQty(sign){
	getQty = document.getElementById('qtyId');
	qty = getQty.value;
	if(qty == ''){
		getQty.value = 1;
	}
	else if(!isNaN(qty)){
		if(sign == '+'){
			getQty.value = parseInt(qty) + 1;
		}
		else if(sign == '-'){
			if(qty > 1){
				getQty.value = qty - 1;
			}
		}
	}
	else if(isNaN(qty)){
		alert('Please Enter Valid Quantity.');
		getQty.value = 1;
	}
}

function doneCheckout(type){
	var flag = false; var qty;
	if(document.getElementById('selectQty').value != ''){
		flag = true;
		qty = document.getElementById('selectQty').value;
		if(qty == 11){
			if(document.getElementById('inputQty').value != ''){
				flag = true;
				qty = document.getElementById('inputQty').value;
			}
			else{
				qty = '';
				flag = false;
			}
		}
	}
	else{
		flag = false;
	}

	if(!isNaN(qty)){
		flag = true;
	}
	else if(isNaN(qty)){
		flag = false;
		alert('Please Enter Valid Quantity.');
	}

	if(flag && qty != ''){//alert(qty);
		//document.getElementById('actionToChange').value = type;
		document.getElementById('redirectPage').value = type;
		document.getElementById('addCartForm').submit();
	}
	else{ alert('some error occured!'); }
}
function addToWish(pid, cid){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function(){
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200){ alert(xmlhttp.responseText); }
	}
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=addToWishFromDetail&pid="+pid+"&cid="+cid+"&dataTempId=szcva15g662d0xq316ld5hu1.cloud.us", true);
	xmlhttp.send();
}


/* new function for add to cart from xcategory paGE */
function addProductToCartB(pdId, colId, price, promotionPrice, promotionId){
	document.getElementById('overlay4Cart').style.display = 'block';
	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			if(xmlhttp.responseText == 1){ getCartDataInDialog(); }
			else{ alert('Oops!! Product not added to cart,'); }
		}
	}
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=addToCartFromCategory&product_id="+pdId+"&color_id="+colId+"&price="+price+"&promoPrice="+promotionPrice+"&promoId="+promotionId+"&dataTempId=c3vcfa1543652de90hch14lkf217900.cloud.uk", true);
	xmlhttp.send();
}
/* new function for add to cart by ajax same as from category page */
function addProductToCart(pdId, colId, price, promotionPrice, promotionId, isbackord){
   // alert('ddd');
	//document.getElementById('overlay4Cart').style.display = 'block';
	// PELE QUANTITY LELO
	if(document.getElementById('selectQty').value != ''){
		qty = document.getElementById('selectQty').value;
		if(qty == 11){
			if(document.getElementById('inputQty').value != ''){ qty = document.getElementById('inputQty').value; }
			else{ qty = 1; }
		}
	}else{ qty = 1; }
	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			if(xmlhttp.responseText == 1){ getCartDataInDialog(); }
			else{ alert('Oops!! Product not added to cart,'); }
		}
	}
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?qty="+qty+"&action=addToCartFromCategory&product_id="+pdId+"&isbackord="+isbackord+"&color_id="+colId+"&price="+price+"&promoPrice="+promotionPrice+"&promoId="+promotionId+"&dataTempId=c3vcfa1543652de90hch14lkf217900.cloud.uk", true);
	xmlhttp.send();
}


</script>

<!-- rate popup start -->
<div id="rateProductWrapper" style="width:430px; left:64%; display:none;">
	<div id="loginBox">
    	<h4 class="signin">Rate & Review this Product</h4>
        <p>Share your Review with us,</p>

        <script type="text/javascript">
		  $(document).ready(function(){
			$(".exempleRate").jRating({
			  length:5,
			  decimalLength:1,
			});
		  });
		</script>
                <div style="text-align:center;" id="reviewform">
            <form class="formRate" action="<?php echo siteUrl; ?>action_model.php" method="post" >
            	<div class="exemple">
                	<div class="exempleRate" data-average="5" data-id="111"></div>
                </div>
                <div class="datasSent" style="display:none;">
                    Datas sent to the server :
                    <p></p>
                </div>
                <div class="serverResponse">
                    Your Rating :
                    <p></p>
                </div>

                <input type="text" name="title" placeholder="Enter Title of Review" required class="txtMargin" /><br/>
                <textarea required="required" name="desc" placeholder="Description" style="height:250px;" ></textarea><br/>
                <input type="submit" name="button" value="GO" class="loginbutton txtMargin">
                <input name="action" type="hidden" value="productRating" />
                <input name="rating" type="hidden" value="0" id="rate111" />
                <input type="hidden" name="pid" value="<?php echo $id; ?>" />
            </form>
        </div>
    </div>
</div>

<?php if (isset($colorNameRow->color) && $colorNameRow->color != '' && $colorNameRow->color != 'White') {?>
	<script>
            $('#setColNameHere').text('- <?php echo $colorNameRow->color; ?>');

	function expandquestion(){
		document.getElementById('questionWrapper').className="activated";
	}
	</script>
<?php }?>


<?php include "include/new_bottom.php";?>


<?php

if (isset($_SESSION['user']) && $_SESSION['user'] != '') {$user_id = $_SESSION['user'];} else {
	if (isset($_SESSION['tempRecent']) && $_SESSION['tempRecent'] != '') {$user_id = $_SESSION['tempRecent'];} else {
		$_SESSION['tempRecent'] = rand(10000000, 99999999);
		$user_id = $_SESSION['tempRecent'];}
}

$rs_chk = mysqli_query($con, "SELECT user_id FROM tbl_user_recent_product WHERE product_id = '$id' AND user_id = '$user_id' AND `color_id` = '$colorId'");
if (!mysqli_num_rows($rs_chk)) {
	$ins = exec_query("INSERT INTO `tbl_user_recent_product` (`user_id`, `product_id`, `color_id`, `datetime`) VALUES ('$user_id', '$id', '$colorId', '" . date('c') . "')", $con);
}
?>