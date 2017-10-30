<?php session_start();
include("include/config.php");
include("include/functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: <?php echo siteName; ?> ::</title>
<link href="<?php echo siteUrl; ?>style@jhmshop.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php getIcon(); ?>
<script src="//code.jquery.com/jquery-1.7.1.min.js"></script>
</head>
<body>

<?php include("include/header.php"); ?>
<div id="sitemenu"><?php include("navigation.php"); ?></div>

<div id="banner_outer">
	<?php include("slideshow.php"); ?>
</div>
<div class="container_outer"> </div> <!--BANNER--> 


	<div class="container">
    	<div class="mustcheckout">
        	<h1 class="mustcheckoutImg">Must Checkout</h1>
            <div id="groupDeals">
            	<ul>
                	<?php 
					$deals=deals(10, true);
					$i=1;
					while($deal=mysql_fetch_object($deals)){
						$img = '';
						$ptype = $deal->promo_type;
						$promoDetailRs = mysql_query("SELECT * FROM tbl_promotion_detail WHERE promo_id = '$deal->promo_id'");
						if(mysql_num_rows($promoDetailRs)){
							$pDetail = mysql_fetch_object($promoDetailRs);
						}
						
						if(isset($deal->bg_img) && $deal->bg_img != ''){
							$img = 'site_image/promotion/'.$deal->bg_img;
						}
						else{
							
							if($ptype == 'allPro'){
								$array = array(1,2,3,4,5,6,25,172,189);
								/*$qqq = "SELECT tcm.media_src FROM tbl_product_category tpc LEFT JOIN tbl_category_media tcm ON tcm.category_id = tpc.category_id WHERE tpc.category_id IN (".$array[array_rand($array)].")";*/
								$qqq = "SELECT media_src FROM tbl_category_media WHERE category_id IN (".$array[array_rand($array)].")";
								$imgRs = mysql_query($qqq);
								$imgRow = mysql_fetch_object($imgRs);
								$img = 'site_image/category/'.$imgRow->media_src;
							}
							else{
								if(isset($pDetail)){
									if($ptype == 'parPro'){
										$ids = $pDetail->ids; // pid
										$img = getImage1($ids, $con);
									}
									elseif($ptype == 'allCat'){
										$ids = $pDetail->ids; // cid
										$img = getImage2($ids, $con);
									}
								}
							}
							
						}
					?>
            		<li>
						<span class="price"><?php echo $deal->percent_or_amount == 'percent' ? .$deal-> promo_value.'% OFF' : '$'.$deal->promo_value; ?></span>
                        <p class="bar">
                        	<a href="<?php echo siteUrl; ?>product-promotion/<?php echo $deal->slug; ?>/" class="buynow">Buy Now</a>							
                            <span class="countdown" id="promoid<?php echo $deal->promo_id; ?>">
                            	<strong>Deals end in </strong>
								<span class="countdownarea" id="countdown<?php echo $i; ?>"><?php echo $deal->end_date; ?> GMT+00:00</span>
                            </span>
                        </p>
                        <span class="promotionBanner"><img src="<?php echo siteUrl; ?><?php echo $img; ?>" /></span>
                    </li>
                    <?php $i++; } ?>
	            </ul>
	            <div class="clr"></div>
            </div>
        </div>  
        
        <?php /* featured wala
        <div id="featured">
        	<ul>
				<?php $tagRs = featuredTag();
				while($tagRow=mysql_fetch_object($tagRs)){ ?>
	                <li style="margin-bottom: 10px; margin-right: 10px; float: left;">
                    	<a href="<?php echo siteUrl.'online-sale/'.str_replace(' ', '-', $tagRow->title); ?>/">
                    		<img src="<?php echo siteUrl; ?>site_image/tag/<?php echo $tagRow->banner_img; ?>" height="240" />
                        </a>
                    </li>
                <?php } ?>
                <div style="clear:both;"></div>
            </ul>
            <div class="clr"> </div>
        </div> <!--FEATURED-->*/ ?>
	</div> <!--CONTAINER-->

<?php include("include/footer.php"); ?>
<script type="text/javascript" src="<?php echo siteUrl; ?>js/countdown.js" defer="defer"></script>
</body>
</html>