<?php session_start();
include("include/config.php");
include("include/functions.php");
if(isset($_SESSION['user'], $_SESSION['user_email'], $_SESSION['user_name'])){ redirect(siteUrl.'cart/'); } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: <?php echo siteName; ?> ::</title>
<link href="<?php echo siteUrl; ?>style@jhmshop.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
</head>
<body>
<?php include("include/header.php"); ?>
<?php include("navigation.php"); ?>
<div id="mainWrapper">
	<div id="innerWrapper" class="pagename">
    	<?php
		$product_q = "SELECT tp.product_id, tp.slug, tp.product_name, tbl_brand.brand_name, tpm.media_thumb, tpp.product_price, tp.brand_id, tpm.color_id,
		GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
		LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
		LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
		LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
		LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
		WHERE tpm.media_type = 'img' GROUP BY tp.product_id ORDER BY tp.product_id DESC LIMIT 0, 24";
		$product_rs = exec_query($product_q, $con);
		$totalProducts = mysql_num_rows($product_rs);
		?>
        <div class="products" style="width:100%;">
            <div class="heading">
            	<h5> What's New <strong id="totalProductHeading"><?php /*echo ($totalProducts.' Items');*/ ?></strong> </h5>
                <div style="clear:both;"></div>
            </div>
            <ul id="putProduct">
				<?php if($totalProducts > 0){
                    while($row = mysql_fetch_object($product_rs)){
                        /* for get promotion */
                        $all_cat = $row->all_cat;
                        $promoType = ''; $promoValue = '';
                        $promoArr = getPromotionForProduct($row->product_id, $row->brand_id, $all_cat, $con);
                        if(!empty($promoArr)){
                            $promoType = $promoArr['percent_or_amount'];
                            $promoValue = $promoArr['promo_value'];
                        }
                        include 'include/productRow.php';
                    }
                }else{ echo 'No Product'; }	?>
            </ul>
        </div>
        
		<div style="clear:both;"></div>
    </div>
</div>
<?php include("include/footer.php"); ?>
<script src="<?php echo siteUrl; ?>js/jquery.lazyload.min.js"></script>
<script>$(function(){ $("img.lazy").lazyload({ effect : "fadeIn" }); });</script>
</body>
</html>
