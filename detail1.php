<?php session_start();
include("include/config.php");
include("include/functions.php");
chkParam($_GET['alias'], 'product-category');
$proData = explode('HXYK', $_GET['alias']);
$id = $proData[0];
$proData1 = explode('TZS1YL', $proData[1]);
$alias = $proData1[0];
$colorId = $proData1[1];
$query = "SELECT tp.product_id AS pid, tp.*, tbl_brand.brand_name, GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
WHERE tp.product_id = $id GROUP BY tp.product_id";
$rs = exec_query($query, $con);
$num = mysql_num_rows($rs);
if($num == 0 || $num != 1){ redirect('category'); }
$row = mysql_fetch_object($rs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: <?php echo siteName; ?> ::</title>
<link href="<?php echo siteUrl; ?>style@jhmshop.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<script type="text/javascript" src="<?php echo siteUrl; ?>js/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo siteUrl; ?>jquery/jRating.jquery.css" type="text/css" />
<script type="text/javascript" src="<?php echo siteUrl; ?>jquery/jRating.jquery.js"></script>
</head>
<body>
<?php include("include/header.php"); ?>
<?php include("navigation.php"); ?>
<div id="mainWrapper">
	<div id="innerWrapper" class="pagename">
    	<h1 class="mainHeading"> <?php echo $row->product_name; ?> </h1>
		<div>
			<ul>
				<li>Brand - <?php echo $row->brand_name; ?></li>
				<br/>
				<li>Price - $ <?php
					$priceRs = exec_query("SELECT product_price, product_rrp FROM tbl_product_price WHERE product_id = $id AND color_id = $colorId", $con);
					$priceRow = mysql_fetch_object($priceRs);
					echo $priceRow->product_price;
				 ?></li>
				<br/>
				<?php /*
				<li>Usage -
					<div id="usageID" class="showcase">
					<?php $usageRs = exec_query("SELECT text, thumb, img FROM tbl_product_usage WHERE product_id = $id", $con);
					if(mysql_num_rows($usageRs) > 0){
						while($usageRow = mysql_fetch_object($usageRs)){ ?>
							<div class="showcase-slide">
								<div class="showcase-content">
									<div class="showcase-content-wrapper">
										<img src="<?php echo siteUrl; ?>site_image/usage/<?php echo $usageRow->img; ?>" alt="02" width="400px" height="270px" />
									</div>
								</div>
								<div class="showcase-thumbnail">
									<img src="<?php echo siteUrl; ?>site_image/usage/<?php echo $usageRow->thumb; ?>" alt="01" width="140px" />
									<div class="showcase-thumbnail-cover"></div>
								</div>
								<div class="showcase-tooltips">
									<a href="javascript:void(0);" coords="150,50"><?php echo $usageRow->text; ?></a>
								</div>
							</div>
						<?php }
					}
					else{ echo 'No Usage'; } ?>
					</div>
				</li>
				<hr/>
				<li>Images - <!-- product carousellllllllllllllllllllllllllllllllllllllllllllllllllllll -->
					<div id="productID" class="showcase">
					<?php $imageRs = exec_query("SELECT media_src, media_thumb FROM tbl_product_media WHERE product_id = $id AND color_id = $colorId AND media_type = 'img'", $con);
					if(mysql_num_rows($imageRs) > 0){
						while($imageRow = mysql_fetch_object($imageRs)){ ?>
							<div class="showcase-slide">
								<div class="showcase-content">
									<img src="<?php echo siteUrl; ?>site_image/product/<?php echo $imageRow->media_src; ?>" alt="Product Image" />
								</div>
								<div class="showcase-thumbnail">
									<img src="<?php echo siteUrl; ?>site_image/product/<?php echo $imageRow->media_thumb; ?>" alt="Product Thumbnail" width="140px" />
								</div>
							</div>
						<?php }
					}
					?>
					</div>
				</li>*/ ?>
				<br/>
				<li>Color -
					<?php $colorRs = exec_query("SELECT color_code, color_id FROM tbl_product_color WHERE product_id = $id AND color_id != $colorId", $con);
					if(mysql_num_rows($colorRs) > 0){
						while($colorRow = mysql_fetch_object($colorRs)){ ?>
							<a href="<?php echo siteUrl; ?>detail/<?php echo $id; ?>HXYK<?php echo $alias; ?>TZS1YL<?php echo $colorRow->color_id; ?>">
								<span class="colorBlock" style="background:<?php echo $colorRow->color_code; ?>;"> &nbsp; &nbsp; </span>
							</a>
						<?php }
					}
					?>
				</li>
				<br/>
				<li>Summary - <?php echo $row->product_summary; ?></li>
				<br/>
				<li>Description - <?php echo $row->product_description; ?></li>
				<br/>
				<li>REVIEW -
					<ul>
						<?php $iReview = 1;
						$reviewRs = exec_query("SELECT tbl_user.email, tbl_review.* FROM tbl_review LEFT JOIN tbl_user ON tbl_review.user_id = tbl_review.user_id", $con); /* condition lagan h product ki */
						if(mysql_num_rows($reviewRs) > 0){
							while($reviewRow = mysql_fetch_object($reviewRs)){ ?>
							<li>
								<h4><?php echo $reviewRow->email; ?></h4>
								<h6><?php echo date('d M, Y h:i A', strtotime($reviewRow->datetime)); ?></h6>
								<p><?php echo $reviewRow->review; ?></p>
								<div class="example">
                                	<div style="float:left; margin-right: 10px;" class="example<?php echo $iReview; ?>" data-average="<?php echo $reviewRow->rating; ?>" data-id="1"></div>
									<p class="blue" style="font-weight:bold; text-align:left;"><?php echo $reviewRow->rating; ?> / 5</p>
                                </div><br/>
								<script type="text/javascript">
									$(document).ready(function(){
										$('.example<?php echo $iReview; ?>').jRating({
											length:5,
											rateMax:5,
											decimalLength:1,
											isDisabled : true
										});
									});
								</script>
							</li>
							<?php $iReview++; }
						}
						?>
					</ul>
				</li>
			</ul>
		</div>
		<div class="clr"></div>
    </div>
</div>
<?php include("include/footer.php"); ?>
</body>
</html>