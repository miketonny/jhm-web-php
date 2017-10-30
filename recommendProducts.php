<?php include "include/new_top.php";?>
<?php include "include/new_header.php";?>
<?php chkParam($_SESSION['user'], siteUrl);
$user_id = $_SESSION['user'];
?>

<section class="block-pt5">
    <div class="container">

	<div  class="dashboard-holder">
       <div class="col-sm-12">
    	<h1 class="mainHeading">Recommended Products</h1>
            </div>
           <div class="col-md-3 col-sm-4">
      	<?php include 'include/userNavigation.php';?>
           </div>


 <div class="col-md-9 col-sm-8">
                <div class="clr"> </div>


    <div class="products">
        	<h3> Recommendations for You </h3>
            <ul id="putProduct" class="recentProduct">
				<?php /* recent viewed ranmdom product */
$similarQ = "SELECT tp.product_id, tp.slug, tp.product_name, tbl_brand.brand_name,
				tp.brand_id, tpm.media_thumb, tpp.product_price, tpp.color_id, tpp.product_rrp,
				GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
				LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
				LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
				LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
				LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
				LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
				WHERE tpm.media_type = 'img' AND tp.is_activate = 1 GROUP BY tp.product_id ORDER BY RAND() limit 0, 16";
$similarRs = exec_query($similarQ, $con);
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
		</div>



   </div>


 <div class="clr"> </div>
	</div>


  </div>
</section>





<?php include "include/new_footer.php";?>
<?php include "include/new_bottom.php";?>