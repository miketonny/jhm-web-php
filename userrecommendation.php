<?php session_start();
include("include/config.php");
include("include/functions.php");
if(!isset($_SESSION['user']) || $_SESSION['user'] == '' || empty($_SESSION['user'])){ redirect(siteUrl); } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: <?php echo siteName; ?> ::</title>
<link href="<?php echo siteUrl; ?>style@jhmshop.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<style> .products li{ height:295px; } </style>
</head>
<body>
<?php include("include/header.php"); ?>
<?php include("navigation.php"); ?>
<div id="mainWrapper">
	<div id="innerWrapper" class="pagename">
    	<h1 class="mainHeading">Recommended Product</h1>
        <div id="textWrapper">
        	<div id="leftWrapper" class="width100">
                <div id="profile">
               		<?php include 'include/userNavigation.php'; ?>
            		<div id="clr"></div>
                    <div id="userpages">
            			
                        <?php
	$product_q = "SELECT tp.product_id, tp.slug, tp.product_name, tbl_brand.brand_name,
	tp.brand_id, tpm.media_thumb, tpp.product_price, tpp.color_id,
	GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
	LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
	LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
	LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
	LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
	LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
	WHERE tpm.media_type = 'img' AND tp.is_activate = 1 GROUP BY tp.product_id ORDER BY RAND() LIMIT 0, 8";
	$product_rs = exec_query($product_q, $con);
	$totalProducts = mysql_num_rows($product_rs);
						?>
                        
                        <div class="products" style="float:none; width:100%;">
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
                        <div class="clr"></div>
                        
                        
                   	</div>
             	</div>
                <div style="clear:both;"></div>
       		</div>
			<div style="clear:both;"></div>
    	</div>
        <div style="clear:both;"></div>
	</div>
</div>
<?php echo include("include/footer.php"); ?>
<script src="<?php echo siteUrl; ?>js/jquery.lazyload.min.js"></script>
<script>$(function(){ $("img.lazy").lazyload({ effect : "fadeIn" }); });</script>
</body>
</html>
