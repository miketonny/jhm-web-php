<?php ob_start("ob_gzhandler"); session_start();
include("include/config.php");
include("include/functions.php");

$condi = ''; 
$limit1 = $_GET['limit1'];
$limit2 = $_GET['limit2'];

// search text
if(isset($_REQUEST['searchText']) && $_REQUEST['searchText'] != ''){
	$searchText = addslashes($_GET['searchText']);
	if(isset($_GET['searchTextId']) && $_GET['searchTextId'] != '' && $_GET['searchTextType'] != ''){
		$searchTextId = $_GET['searchTextId'];
		if($_GET['searchTextType'] == 'cat'){
			
			$categoryId = $searchTextId;
			if(isset($categoryId) && $categoryId != ''){ // get child
				$catQ = "SELECT category_id FROM `tbl_category` WHERE parent_id IN ($categoryId) OR superparent_id IN ($categoryId)";
				$catRs = exec_query($catQ, $con);
				$catArray = array();
				while($catRow = mysqli_fetch_object($catRs)){ $catArray[] = $catRow->category_id; }
				$categories = implode(',', $catArray);
				$categories = ($categories == '')? $categoryId : $categories.','.$categoryId ;
				$condi .= " AND tpcat.category_id IN ($categories)";
			}
		}
		elseif($_GET['searchTextType'] == 'brand'){
			$condi .= " AND (tp.brand_id = '".$searchTextId."') "; /* brand *///////////// yha p chng kiya for side bar condi, not included brand
			if(isset($_GET['searchTextData']) && $_GET['searchTextData'] != ''){
				
				$categoryId = $_GET['searchTextData'];
				if(isset($categoryId) && $categoryId != ''){ // get child
					$catQ = "SELECT category_id FROM `tbl_category` WHERE parent_id IN ($categoryId) OR superparent_id IN ($categoryId)";
					$catRs = exec_query($catQ, $con);
					$catArray = array();
					while($catRow = mysqli_fetch_object($catRs)){ $catArray[] = $catRow->category_id; }
					$categories = implode(',', $catArray);
					$categories = ($categories == '')? $categoryId : $categories.','.$categoryId ;
					$condi .= " AND tpcat.category_id IN ($categories)";
				}
			}
		}
	}
	else{
		$condiLoop = ''; $singleValueCondi = '';
		$searchTextArr = explode(' ', $searchText);
		$loopCount = 1;
		foreach($searchTextArr AS $loopValue){
			$condiLoop .= ($condiLoop != '')?' OR ':'';
			$condiLoop .= " tp.product_name LIKE '%".$loopValue."%' OR
			tpp.product_upc LIKE '%".$loopValue."%' OR product_sku LIKE '%".$loopValue."%' OR
			tp.keyword LIKE '%".$loopValue."%' OR tbl_brand.brand_name LIKE '%".$loopValue."%' ";
			
			if($loopCount == 1){
				$orderByCondition = " ORDER BY tp.product_name LIKE '%".$searchText."%' DESC, tpp.product_upc LIKE '%".$searchText."%' DESC, product_sku LIKE '%".$searchText."%' DESC, tp.keyword LIKE '%".$searchText."%' DESC, tbl_brand.brand_name LIKE '%".$searchText."%' DESC ";
			}
			$loopCount++;
		}
		if($loopCount > 1){
			$singleValueCondi = " tp.product_name LIKE '%".$searchText."%' OR
			tpp.product_upc LIKE '%".$searchText."%' OR product_sku LIKE '%".$searchText."%' OR
			tp.keyword LIKE '%".$searchText."%' OR tbl_brand.brand_name LIKE '%".$searchText."%' OR ";
		}
		$condi .= ($condiLoop != '')?' AND ('.$singleValueCondi.$condiLoop.' ) ':'';
	}
}
elseif(isset($_GET['tag']) && $_GET['tag'] != ''){
	$searchText = str_replace('-', ' ', $_GET['tag']);
	$condi .= " AND tp.tag LIKE '%".$searchText."%' "; /* product tags */
}
else{
	if(isset($_GET['gender']) && $_GET['gender'] != ''){
		$gender = $_GET['gender'];
		if($gender == 'male' || $gender == 'mens'){
			$condi .= " AND ( tp.user_group LIKE 'male' OR tp.user_group LIKE 'male%') ";
		}
		elseif($gender == 'female' || $gender == 'womens'){
			$condi .= " AND tp.user_group LIKE '%female' ";
		}
		elseif($gender == 'all'){  }
	}
	
	if(isset($_GET['brands']) && $_GET['brands'] != ''){
		$brands = $_GET['brands'];
		$brandArr = explode('nbsp', $brands);
		$brandSlug = '';
		foreach($brandArr AS $brandLoopVal){
			$brandSlug = ($brandSlug == '')? "'".$brandLoopVal."'" : $brandSlug.",'".$brandLoopVal."'" ;
		}
		$condi .= " AND tbl_brand.slug IN ($brandSlug) ";
	}
	
	if(isset($_GET['cat']) && $_GET['cat'] != ''){
		$categoryId = $_GET['cat'];
		$catQ = "SELECT category_id FROM `tbl_category` WHERE parent_id IN ($categoryId) OR superparent_id IN ($categoryId)";
		$catRs = exec_query($catQ, $con);
		$catArray = array();
		while($catRow = mysqli_fetch_object($catRs)){ $catArray[] = $catRow->category_id; }
		$categories = implode(',', $catArray);
		$categories = ($categories == '')? $categoryId : $categories.','.$categoryId ;
		$condi .= " AND tpcat.category_id IN ($categories) ";
	}
	
	if(isset($_GET['price']) && $_GET['price'] != ''){
		$price = $_GET['price'];
		$priceRange = explode('X', $price);
		$condi .= " AND tpp.product_price >= '".$priceRange[0]."' AND tpp.product_price <= '".$priceRange[1]."' ";
	}
}

$orderBy = 'ORDER BY tbl_brand.brand_name';
if(isset($_GET['sortId']) && $_GET['sortId'] != ''){
	$sortId = $_GET['sortId'];
	if($sortId == 'datAsc'){ $orderBy = 'ORDER BY tp.created_on DESC'; }
	elseif($sortId == 'priAsc'){ $orderBy = 'ORDER BY tpp.product_price ASC'; }
	elseif($sortId == 'priDesc'){ $orderBy = 'ORDER BY tpp.product_price DESC'; }
	else{ $orderBy = 'ORDER BY tbl_brand.brand_name'; }
}


$product_q = "SELECT tp.product_id, tp.slug, tp.product_name, tbl_brand.brand_name,
tp.brand_id, tpm.media_thumb, tpp.product_price, tpp.color_id,
GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
WHERE tpm.media_type = 'img' AND tpm.is_main = 1 AND tp.is_activate = 1 $condi Group By tp.product_id $orderBy LIMIT $limit1, $limit2";
$product_rs = mysqli_query($con, $product_q);
while($row = mysqli_fetch_object($product_rs)){
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
?>