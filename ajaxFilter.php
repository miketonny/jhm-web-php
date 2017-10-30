<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('include/config.php');
include('include/functions.php');
$condi = ''; $order = ''; $countPrice = 0; $categories = ''; $condiBrand = ''; $condiCol = ''; $headingText = '';
$href = 'javascript:void(0);';
$categoryId = $_GET['categoryId'];
$brandId = $_GET['brandId'];
$colorId = $_GET['colorId'];
$genderId = $_GET['genderId'];
$price = $_GET['priceId'];
$availId = $_GET['availId'];
$sortId = $_GET['sortId'];
$discountFilter = $_GET['discountId'];
/* price */
if($price != '' && $price != 0){ $prange = explode('-', $price);
	$condi .= ' AND tpp.product_price >= '.$prange[0].' AND tpp.product_price <= '.$prange[1];
}
/* gender */
if($genderId != ''){
	if($genderId == 'male' || $genderId == 'men'){
		$genCat = 'male';
		$headingText = 'MENS';
		$condi .= " AND ( tp.user_group LIKE 'male' OR tp.user_group LIKE 'male%' )";
	}elseif($genderId == 'female' || $genderId == 'women'){
		$genCat = 'female';
		$headingText = 'WOMENS';
		$condi .= " AND tp.user_group LIKE '%female'";
	}
}
/* category if($categoryId != '' && $categoryId != 0){ $condi .= " AND tpcat.category_id = $categoryId"; }*/
if($categoryId != '' && $categoryId != 0){
	$headingTextCat = '';
	$catRs = exec_query("SELECT category_id FROM `tbl_category` WHERE parent_id = $categoryId OR superparent_id = $categoryId OR category_id = $categoryId", $con);
	$catArray = array();
	while($catRow = mysql_fetch_object($catRs)){ $catArray[] = $catRow->category_id; }
	$categories = implode(',', $catArray);
	
	$sidebarBrandCondi = ' AND tpcat.category_id IN ('.$categories.')';
	$condi .= $sidebarBrandCondi;
	
	$genCat = (isset($genCat) && $genCat != '')?$genCat:'male';
	
	// for create heading
	$level = chkCategoryLevel($categoryId, $con);
	if($level == 'cat'){
		$cat = getCategory(array('category_name', 'category_id'), $categoryId, $con);
		$headingTextCat = '<a href="'.$href.'" onclick="getMaleFemaleOnCategoryPage('.$cat->category_id.', '."'".$cat->category_name."'".');">'.$cat->category_name.'</a>';
		
	}
	elseif($level == 'subCat'){
		$scat = getCategory(array('category_name', 'category_id', 'superparent_id'), $categoryId, $con);
		//$headingTextCat = $scat->category_name;
		$headingTextCat = '<a href="'.$href.'" onclick="getSubSubCategoryOnCategoryPage('.$scat->category_id.', '."'".$scat->category_name."'".', '."'".$genCat."'".');">'.$scat->category_name.'</a>';
		
		$mainCats = getCategory(array('category_name', 'category_id'), $scat->superparent_id, $con);
		//$headingTextCat = $mainCats->category_name.' > '.$headingTextCat;
		$headingTextCat = '<a href="'.$href.'" onclick="getMaleFemaleOnCategoryPage('.$mainCats->category_id.', '."'".$mainCats->category_name."'".');">'.$mainCats->category_name.'</a>'.' > '.$headingTextCat;
	}
	elseif($level == 'subSubCat'){
		$sscat = getCategory(array('category_name', 'parent_id'), $categoryId, $con);
		$headingTextCat = $sscat->category_name;
		
		$subCats = getCategory(array('category_name', 'category_id', 'superparent_id'), $sscat->parent_id, $con);
		//$headingTextCat = $subCats->category_name.' > '.$headingTextCat;
		$headingTextCat = '<a href="'.$href.'" onclick="getSubSubCategoryOnCategoryPage('.$subCats->category_id.', '."'".$subCats->category_name."'".', '."'".$genCat."'".');">'.$subCats->category_name.'</a>'.' > '.$headingTextCat;
		
		$mainCats = getCategory(array('category_name', 'category_id'), $subCats->superparent_id, $con);
		//$headingTextCat = $mainCats->category_name.' > '.$headingTextCat;
		$headingTextCat = '<a href="'.$href.'" onclick="getMaleFemaleOnCategoryPage('.$mainCats->category_id.', '."'".$mainCats->category_name."'".');">'.$mainCats->category_name.'</a>'.' > '.$headingTextCat;
	}
	if($headingText != ''){	$headingText = $headingText.' > '.$headingTextCat; }
	else{ $headingText = $headingTextCat; }
}
/* brand */
if($brandId != '' && $brandId != 0){ $condiBrand .= ' AND tp.brand_id IN ('.$brandId.')'; }
/* color */
if($colorId != ''){ $colorId = str_replace('@', '#', $colorId);
	$condiCol .= ' AND tpcol.color_code IN ('.$colorId.')';
}
/* avail */
if($availId != ''){ $condi .= ' AND tp.stock_availability = '.$availId; }
/* sort */
if($sortId != ''){
	if($sortId == 'datAsc'){ $order = 'ORDER BY tp.created_on DESC'; }
	elseif($sortId == 'priAsc'){ $order = 'ORDER BY tpp.product_price ASC'; }
	elseif($sortId == 'priDesc'){ $order = 'ORDER BY tpp.product_price DESC'; }
}else{ $order = 'ORDER BY tbl_brand.brand_name'; }

$product_q = "SELECT tp.product_id, tp.slug, tp.product_name, tbl_brand.brand_name, tpm.media_thumb, tpp.product_price, tp.brand_id, tpp.color_id,
GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
LEFT JOIN tbl_product_color tpcol ON tpcol.product_id = tp.product_id
WHERE tpm.media_type = 'img' AND tpm.is_main = 1 AND tp.is_activate = 1 $condi $condiCol $condiBrand GROUP BY tp.product_id $order";
$product_rs = exec_query($product_q, $con);
$iCount = 0;
$num = mysql_num_rows($product_rs);



$_SESSION['autoLoadQuery'] = $product_q;
$items_per_group = getNoOfProductOnCategoryPage();



if($num > 0){
	/*while($row = mysql_fetch_object($product_rs)){
		// for get promotion
		$all_cat = $row->all_cat;
		$promoType = ''; $promoValue = '';
		$promoArr = getPromotionForProduct($row->product_id, $row->brand_id, $all_cat, $con);
		if(!empty($promoArr)){
			$promoType = $promoArr['percent_or_amount'];
			$promoValue = $promoArr['promo_value'];
		}
		
		// $discountFilter - 0-all, 1-dis, 2-non dis
		if($discountFilter != 0){
			if($discountFilter == 1 && empty($promoArr)){ continue; }
			elseif($discountFilter == 2 && !empty($promoArr)){ continue; }
		}
		//include 'include/productRow.php';
		$iCount++;
	}*/
	echo '<input type="hidden" id="countAll" value="'.$num.'" />';
	echo '<input type="hidden" id="headingAjax" value="'.htmlentities($headingText).'" />';
}else{
	echo 'No Product';
	echo '<input type="hidden" id="countAll" value="0" />'; echo '<input type="hidden" id="headingAjax" value="'.htmlentities($headingText).'" />';
}
$total_groups = ceil($num/$items_per_group);
echo '<input type="hidden" id="autoLoadTotalGroup" value="'.$total_groups.'" />';

$_SESSION['trackLoad'] = 0;
$_SESSION['productQuery'] = "SELECT tp.product_id, tp.brand_id, tbl_brand.brand_name FROM tbl_product tp
LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
WHERE tpm.media_type = 'img' AND tp.is_activate = 1 $condi GROUP BY tp.product_id";

$_SESSION['sidebarBrandQ'] = "SELECT tbl_brand.brand_name, tbl_brand.brand_id, (
SELECT COUNT(DISTINCT tp.product_id) AS count FROM tbl_product tp
LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
WHERE tpm.media_type = 'img' AND tpm.is_main = 1 AND tp.is_activate = 1 AND tp.brand_id = tbl_brand.brand_id $condi) AS totalProduct
FROM tbl_brand ORDER BY tbl_brand.brand_name";

$_SESSION['productQueryColor'] = "SELECT tpcol.product_id, tpcol.color_code, tcol.color FROM tbl_product tp
LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
LEFT JOIN tbl_product_color tpcol ON tpcol.product_id = tp.product_id
LEFT JOIN tbl_color tcol ON tcol.color_code = tpcol.color_code
WHERE tpm.media_type = 'img' AND tp.is_activate = 1 $condi $condiCol GROUP BY tpcol.color_code, tpcol.product_id";
?>