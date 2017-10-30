<?php include "include/new_top.php";?>
<?php include "include/new_header.php";?>
<?php chkParam($_SESSION['user'], siteUrl);
$user_id = $_SESSION['user'];
?>


<section class="block-pt5">
    <div class="container">

	<div  class="dashboard-holder">

   <div class="col-sm-12">
    	<h1 class="mainHeading">Recent Viewed  </h1>
            </div>
           <div class="col-md-3 col-sm-4">
      	<?php include 'include/userNavigation.php';?>
           </div>


 <div class="col-md-9 col-sm-8">
                <div class="clr"> </div>

<div class="products">
        	<h3> Recent Viewed Product</h3>
            <ul id="putProduct" class="recentProduct">
				<?php /* recent viewed ranmdom product */
$recentProRs1 = exec_query("SELECT * FROM tbl_user_recent_product WHERE user_id = '" . $_SESSION['user'] . "' order by datetime DESC", $con);
while ($recentProRow1 = mysqli_fetch_object($recentProRs1)) {
	$recentQ = "SELECT tp.product_id, tp.slug, tp.product_name, tbl_brand.brand_name,
                    tp.brand_id, tpm.media_thumb, tpp.product_price, tpp.color_id, tpp.product_rrp,
                    GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
                    LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
                    LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
                    LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
                    LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
                    LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
                    WHERE tp.product_id = '$recentProRow1->product_id' AND tpp.color_id = '$recentProRow1->color_id'";
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