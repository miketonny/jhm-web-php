<?php session_start();
error_reporting(E_ALL);
include("include/config.php");
include("include/functions.php");
if($_REQUEST){
	$trackLoad = $_SESSION['trackLoad'];
	//sanitize post value
	$group_number = filter_var($trackLoad, FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	//throw HTTP error if group number is not valid
	/*
	if(!is_numeric($group_number)){
		header('HTTP/1.1 500 Invalid number!');
		exit();
	}
	*/
	$items_per_group = getNoOfProductOnCategoryPage();
	//get current starting point of records
	$position = ($group_number * $items_per_group);
	
	
	//Limit our results within a specified range. 
	//$results = $mysql_query("SELECT id,name,message FROM paginate ORDER BY id ASC LIMIT $position, $items_per_group");
	if(isset($_SESSION['autoLoad']) && $_SESSION['autoLoad'] != ''){
		echo $product_q = $_SESSION['autoLoad']." Limit $position, $items_per_group";
	}
	else{
		$product_q = "SELECT tp.product_id, tp.slug, tp.product_name, tbl_brand.brand_name,
		tp.brand_id, tpm.media_thumb, tpp.product_price, tpp.color_id,
		GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
		LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
		LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
		LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
		LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
		LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
		WHERE tpm.media_type = 'img' AND tpm.is_main = 1 AND tp.is_activate = 1 group By tp.product_id ORDER BY tbl_brand.brand_name
		LIMIt $position, $items_per_group";
	}
	//echo $product_q;
	$results = mysql_query($product_q);
	
	if ($results){
		while($row = mysql_fetch_object($results)){
			// for get promotion
			$all_cat = $row->all_cat;
			$promoType = ''; $promoValue = '';
			$promoArr = getPromotionForProduct($row->product_id, $row->brand_id, $all_cat, $con);
			if(!empty($promoArr)){
				$promoType = $promoArr['percent_or_amount'];
				$promoValue = $promoArr['promo_value'];
			}
			include 'include/productRow.php';
		}
		//while($obj = $results->fetch_object()){
	}
	$_SESSION['trackLoad']++;
	unset($row);
}
?>