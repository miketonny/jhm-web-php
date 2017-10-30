<?php
//if (session_status() == PHP_SESSION_NONE) {
    //session_start();
//}
include("include/config.php");
include("include/functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: <?php echo siteName; ?> ::</title>
<link href="<?php echo siteUrl; ?>style@jhmshop.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php getIcon(); ?>
<script src="//code.jquery.com/jquery-1.7.1.min.js"></script>
</head>-->
<body>
<div id="black-overlay"><img src="<?php echo siteUrl; ?>images/load.gif" /></div>
<?php include("include/header.php"); ?>
<div id="sitemenu"><?php include("navigation.php"); ?></div>
<div id="mainWrapper">
	<div id="innerWrapper" class="pagename">
	<?php
	/* product filter start */
	$condi = '';
	$condiBrand = '';
	$href = 'javascript:void(0);';
	/* from header text field search ** exec_query("INSERT INTO tbl_search_history(keyword, date) VALUES('$searchText', '".date('Y-m-d')."')", $con);*/
	if(isset($_REQUEST['searchText']) && $_REQUEST['searchText'] != ''){
		$searchText = addslashes($_REQUEST['searchText']);
		if(isset($_REQUEST['searchTextId']) && $_REQUEST['searchTextId'] != '' && $_REQUEST['searchTextType'] != ''){
			$searchTextId = $_REQUEST['searchTextId'];
			if($_REQUEST['searchTextType'] == 'cat'){
				$condi .= " AND (tpcat.category_id = '".$searchTextId."')"; /* category */
			}
			elseif($_REQUEST['searchTextType'] == 'brand'){
				$condiBrand .= " AND (tp.brand_id = '".$searchTextId."')"; /* brand *///////////// yha p chng kiya for side bar condi, not included brand
				if(isset($_REQUEST['searchTextData']) && $_REQUEST['searchTextData'] != ''){
					$searchTextData = $_REQUEST['searchTextData'];
					$condi .= " AND tpcat.category_id = '".$searchTextData."' "; /* category */
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
	elseif(isset($_GET['gender']) && $_GET['gender'] != ''){ /* menu gender, main cat and sub cat */
		$categoryId = '';
		$gender = $_GET['gender'];
		if($gender == 'men'){ $genCat = 'male';
			$condi .= "AND ( tp.user_group LIKE 'male' OR tp.user_group LIKE 'male%')";
			$headingText = 'MENS';
		}
		elseif($gender == 'women'){ $genCat = 'female';
			$condi .= "AND tp.user_group LIKE '%female'";
			$headingText = 'WOMENS';
		}
		elseif($gender == 'all'){ $genCat = 'all';
			$condi .= "";
			$headingText = 'ALL PRODUCTS';
		}

		$genCat = (isset($genCat) && $genCat != '')?$genCat:'male';
		
		if(isset($_GET['subSubCat']) && $_GET['subSubCat'] != ''){
			// sub sub category
			$subsubCategoryData = getCategoryFromSlug(array('category_id', 'category_name', 'superparent_id', 'parent_id'), $_GET['subSubCat'], $con);
			$subsubCategoryId = $subsubCategoryData->category_id;
			$categoryId = $subsubCategoryId;
			
			$subCategoryData = getCategory(array('category_id', 'category_name', 'superparent_id'), $subsubCategoryData->parent_id, $con);
			
			//set heading
			$headingTextCat = '<a href="'.$href.'" onclick="">'.$subsubCategoryData->category_name.'</a>';
			
			$headingTextCat = '<a href="'.$href.'" onclick="getSubSubCategoryOnCategoryPage('.$subCategoryData->category_id.', '."'".$subCategoryData->category_name."'".', '."'".$genCat."'".');">'.$subCategoryData->category_name.'</a> > '.$headingTextCat;
		
			$mainCats = getCategory(array('category_name', 'category_id'), $subCategoryData->superparent_id, $con);
			$headingTextCat = '<a href="'.$href.'" onclick="getMaleFemaleOnCategoryPage('.$mainCats->category_id.', '."'".$mainCats->category_name."'".');">'.$mainCats->category_name.'</a>'.' > '.$headingTextCat;
			
			
			
		}
		elseif(isset($_GET['sub']) && $_GET['sub'] != ''){
			// sub category
			$subCategoryData = getCategoryFromSlug(array('category_id', 'category_name', 'superparent_id'), $_GET['sub'], $con);
			$subCategoryId = $subCategoryData->category_id;
			$categoryId = $subCategoryId;
			
			//set heading
			$headingTextCat = '<a href="'.$href.'" onclick="getSubSubCategoryOnCategoryPage('.$subCategoryData->category_id.', '."'".$subCategoryData->category_name."'".', '."'".$genCat."'".');">'.$subCategoryData->category_name.'</a>';
		
			$mainCats = getCategory(array('category_name', 'category_id'), $subCategoryData->superparent_id, $con);
			$headingTextCat = '<a href="'.$href.'" onclick="getMaleFemaleOnCategoryPage('.$mainCats->category_id.', '."'".$mainCats->category_name."'".');">'.$mainCats->category_name.'</a>'.' > '.$headingTextCat;
		}
		elseif(isset($_GET['category']) && $_GET['category'] != ''){
			// category
			$categoryData = getCategoryFromSlug(array('category_id', 'category_name'), $_GET['category'], $con);
			$categoryId = $categoryData->category_id;
			$catForSideBar = $categoryId;
			$headingTextCat = '<a href="'.$href.'" onclick="getMaleFemaleOnCategoryPage('.$categoryData->category_id.', '."'".$categoryData->category_name."'".');">'.$categoryData->category_name.'</a>';
		}
		/*if($subCategoryId != ''){ $categoryId = ($categoryId != '')? $categoryId.','.$subCategoryId : $subCategoryId; }*/
		
		// if post is set of brand
		if(isset($_POST['brandFilter']) && !empty($_POST['brandFilter'])){
			$brandIdsForQ = implode(',', $_POST['brandFilter']);
			$condiBrand .= " AND tp.brand_id IN ($brandIdsForQ) ";
		}
		
		if($categoryId != ''){
			$catQ = "SELECT category_id FROM `tbl_category` WHERE parent_id IN ($categoryId) OR superparent_id IN ($categoryId)";
			$catRs = exec_query($catQ, $con);
			$catArray = array();
			while($catRow = mysql_fetch_object($catRs)){ $catArray[] = $catRow->category_id; }
			$categories = implode(',', $catArray);
			$categories = ($categories == '')? $categoryId : $categories.','.$categoryId ;
			$condi .= " AND tpcat.category_id IN ($categories)";
		}
		$heading = '';
		if(isset($headingText) && $headingText != ''){ $heading = $headingText; }
		if(isset($headingTextCat) && $headingTextCat != ''){ $heading = $heading.' > '.$headingTextCat; }
	}
	elseif(isset($_GET['tag']) && $_GET['tag'] != ''){ /* for menu tag */
		$searchText = str_replace('-', ' ', $_GET['tag']);
		$condi .= "AND tp.tag LIKE '%".$searchText."%'"; /* product tags */
	}
	elseif(isset($_GET['promotion']) && $_GET['promotion'] != ''){ /* for home promotion */
		$promotion = promotionFromSlug($_GET['promotion'], true);
		if(isset($promotion->promo_id) && $promotion->promo_id != ''){
			
			if($promotion->promo_type == 'allBrand'){
				$condiBrand .= " AND tp.brand_id IN ($promotion->ids)"; ////////////////////////////////////////////////////////////////////////////
				/* from brand + cat */
				if($promotion->category_id != ''){
					$promoCategory = $promotion->category_id;
					$catQ = "SELECT category_id FROM `tbl_category` WHERE (parent_id IN ($promoCategory)) OR (superparent_id IN ($promoCategory)) OR (category_id IN ($promoCategory))";
					$catRs = exec_query($catQ, $con);
					$catArray = array();
					while($catRow = mysql_fetch_object($catRs)){ $catArray[] = $catRow->category_id; }
					$categories = implode(',', $catArray);
					$condi .= " AND tpcat.category_id IN ($categories)";
				}
			}
			elseif($promotion->promo_type == 'parPro'){
				if($promotion->ids != ''){
					$condi .= " AND tp.product_id IN ($promotion->ids)";
				}
			}
			elseif($promotion->promo_type == 'allCat'){
				$promoCategory = $promotion->ids;
				$catQ = "SELECT category_id FROM `tbl_category` WHERE (parent_id IN ($promoCategory)) OR (superparent_id IN ($promoCategory)) OR (category_id IN ($promoCategory))";
				$catRs = exec_query($catQ, $con);
				$catArray = array();
				while($catRow = mysql_fetch_object($catRs)){ $catArray[] = $catRow->category_id; }
				$categories = implode(',', $catArray);
				$condi .= " AND tpcat.category_id IN ($categories)";
			}
			elseif($promotion->promo_type == 'allPro'){
				/* nothing to do */
			}
		}
	}
	elseif(isset($_GET['searchByBrand']) && $_GET['searchByBrand'] != ''){
		$brandId = $_GET['searchByBrand'];
		$condiBrand .= " AND tp.brand_id = '$brandId' "; ////////////////////////////////////////////////////////////////////////////////////
	}
	/* product filter finishhhh - tpm.color_id */
	
	$items_per_group = getNoOfProductOnCategoryPage();
	if(isset($orderByCondition) && $orderByCondition != ''){ $orderBy = $orderByCondition; }
	else{ $orderBy = ' ORDER BY tbl_brand.brand_name '; }
	
	$product_q_raw = "SELECT tp.product_id, tp.slug, tp.product_name, tbl_brand.brand_name,
	tp.brand_id, tpm.media_thumb, tpp.product_price, tpp.color_id,
	GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
	LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
	LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
	LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
	LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
	LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
	WHERE tpm.media_type = 'img' AND tpm.is_main = 1 AND tp.is_activate = 1 $condi $condiBrand Group By tp.product_id $orderBy";
	
	if(isset($_REQUEST['searchText']) && $_REQUEST['searchText'] != ''){
		$product_q = $product_q_raw;
	}
	elseif(isset($headingText) && $headingText != ''){ //ydi user menu s aya h
		echo $product_q = $product_q_raw;
	}
	elseif(isset($_SESSION['isLastPageIsDetail']) && isset($_SESSION['autoLoad']) && $_SESSION['autoLoad'] != ''){
		$product_q = $_SESSION['autoLoad'];
	}
	else{
		$product_q = $product_q_raw;
	}
	//echo $product_q;
	// this is main query without limit, find all no. of records by this query
	$autoLoadNo = mysql_num_rows(mysql_query($product_q));
	$_SESSION['autoLoad'] = $product_q;
	$total_groups = round($autoLoadNo/$items_per_group);
	$_SESSION['trackLoad'] = 1;
	
	$product_rs = exec_query($product_q." LIMIT 0, $items_per_group ", $con);
	$totalProducts = mysql_num_rows($product_rs);
	
	
	
	
?>




<input type="hidden" id="categoryTotalGroups" value="<?php echo $total_groups; ?>" />
<script type="text/javascript">
$(document).ready(function() {
	//var track_load = 0; //total loaded record group(s)
	//var track_load = 1;
	var loading  = false; //to prevents multipal ajax loads
	var total_groups = $('#categoryTotalGroups').val(); //total record group(s)
	
	//$('#putProduct').load("<?php echo siteUrl; ?>autoload_process.php", {'group_no':track_load}, function() {track_load++;}); //load first group
	
	$(window).scroll(function() { //detect page scroll
		//alert( $(window).scrollTop()+' + '+$(window).height()+' == '+$(document).height() );
		if($(window).scrollTop() + $(window).height() == $(document).height()){  //user scrolled to bottom of the page?
			
			//if(track_load <= total_groups && loading==false){ //there's more data to load
			if(loading==false){
				loading = true; //prevent further ajax loading
				$('.animation_image').show(); //show loading image
				
				//load data from the server using a HTTP POST request
				$.post('<?php echo siteUrl; ?>autoload_process.php',{'group_no': 'group_no'}, function(data){
									
					$("#putProduct").append(data); //append received data into the element
					
					// for left bar scroll operation
					$('#filter').trigger("sticky_kit:detach");
					$('#filter').stick_in_parent();
					
					//hide loading image
					$('.animation_image').hide(); //hide loading image once data is received
					
					//track_load++; //loaded group increment
					loading = false; 
				
				}).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
					//alert(thrownError); //alert with HTTP error
					$('.animation_image').hide(); //hide loading image
					loading = false;
				});
			}
		}
	});
});
</script>




 
<?php include 'include/sidebarCategory.php'; ?>
<div class="products">
	<div class="heading">
    	<h5>
			<span id="productHeading"><?php echo (isset($heading))? $heading : 'JHM'; ?></span>
        	<strong id="totalProductHeading">(<?php echo $autoLoadNo.' Items'; ?>)</strong>
        </h5>
    	<select id="sort" onchange="setValues('sortId', this.value);">
        	<option value=""> Sort by </option>
			<option value="datAsc"> What's NEW ! </option>
			<option value="priDesc"> Price: High to Low </option>
			<option value="priAsc"> Price: Low to High </option>
        </select>
		<input type="hidden" id="sortId" value="" />
        <div class="clr"> </div>
    </div>
    <ul id="putProduct">
	<?php if($totalProducts > 0){
		while($row = mysql_fetch_object($product_rs)){
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
		
		/* query for get updated brand */
		$_SESSION['productQuery'] = "SELECT tp.product_id, tp.brand_id, tbl_brand.brand_name FROM tbl_product tp
		LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
		LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
		LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
		LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
		LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
		WHERE tpm.media_type = 'img' AND tp.is_activate = 1 $condi GROUP BY tp.product_id";
		/* ?> <script> //getFilterBrand(); </script> <?php */

// new brand query
$_SESSION['sidebarBrandQ'] = "SELECT tbl_brand.brand_name, tbl_brand.brand_id, (
SELECT COUNT(DISTINCT tp.product_id) AS count FROM tbl_product tp
LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
WHERE tpm.media_type = 'img' AND tpm.is_main = 1 AND tp.is_activate = 1 AND tp.brand_id = tbl_brand.brand_id $condi) AS totalProduct
FROM tbl_brand ORDER BY tbl_brand.brand_name";
//  <script> getFilterBrandChecked(document.getElementById('brandId').value);  <?php

		$_SESSION['productQueryColor'] = "SELECT tpcol.product_id, tpcol.color_code, tcol.color FROM tbl_product tp
		LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
		LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
		LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
		LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
		LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
		LEFT JOIN tbl_product_color tpcol ON tpcol.product_id = tp.product_id
		LEFT JOIN tbl_color tcol ON tcol.color_code = tpcol.color_code
		WHERE tpm.media_type = 'img' AND tp.is_activate = 1 $condi GROUP BY tpcol.color_code, tpcol.product_id";
		/* ?> <script> //getFilterColor(); </script> <?php */
	}else{ echo 'No Product'; }	?>
    </ul>
    
    
    
    

    <style> .animation_image {background: #F9FFFF;border: 1px solid #E1FFFF;padding: 10px;width: 500px;margin-right: auto;margin-left: auto;} </style>
    <div class="animation_image" style="display:none" align="center">
    	<img src="<?php echo siteUrl; ?>images/ajax-loading.gif">
    </div>
    
    
    
    
</div>
<div class="clr"></div>
    </div>
</div>
<?php include("include/footer.php"); ?>
<script>
function getMaleFemaleOnCategoryPage(catId, catName){
	<?php if(isset($_GET['gender']) && $_GET['gender'] != ''){
		if($_GET['gender'] != 'all'){ ?>
			var gender = '<?php echo $_GET['gender']; ?>s';
		<?php }else{ ?>
			var gender = 'all';
		<?php } ?>
		//////////////////////////////////////////////////////////////////////////////////////
		var xmlhttp;
		if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
		else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
				slugg = xmlhttp.responseText;
				window.location = '<?php echo siteUrl; ?>'+gender+'/1/'+slugg+'/';
			}
		}
		xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=getSlugByCat&data1="+catId+"&dataName="+catName+"&dataTempId=txc7ea15392ded0xc31d8ld1f21571.cloud.uk", true);
		xmlhttp.send();
		////////////////////////////////////////////////////////////////////////////////////////
		
		/*genderId = document.getElementById("genderId").value;
		if(genderId == 'women'){ genderId = 'female'; }
		else if(genderId == 'men'){ genderId = 'male'; }
		ajaxCall('abc');
		getSubCategoryOnCategoryPage(catId, catName, genderId);*/
		
		//setGenderValues(genderId, catId, catName);
		
	<?php }else{ ?>
	$('.animation_image').show();
	document.getElementById('categoryId').value = catId;
	
	ajaxCall('abc');
	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			document.getElementById("putCatHere").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=getMaleFemaleOnCategoryPage&data1="+catId+"&dataName="+catName+"&dataTempId=tqv7ea153302ded0xc31d4ld1f21571.cloud.uk", true);
	xmlhttp.send();
	<?php } ?>
}
function getSubCategoryOnCategoryPage(catId, catName, gen){
	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			document.getElementById("putCatHere").innerHTML = xmlhttp.responseText;
			/*ajaxCall('abc');*/
		}
	}
	//catName = encodeURIComponent(catName);
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=getSubCategoryOnCategoryPage&data1="+catId+"&dataName="+catName+"&gen="+gen+"&dataTempId=tqv7ea153302ded0xc31d4ld1f21571.cloud.uk", true);
	xmlhttp.send();
}
function getSubSubCategoryOnCategoryPage(catId, catName, gen){
	$('.animation_image').show();
	setValues('categoryId', catId);
	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			document.getElementById("putCatHere").innerHTML = xmlhttp.responseText;
			/*ajaxCall('abc');*/
		}
	}
	//catName = encodeURIComponent(catName);
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=getSubSubCategoryOnCategoryPage&data1="+catId+"&dataName="+catName+"&gen="+gen+"&dataTempId=c3v7fa153302ded0hc314ld1f21679.cloud.uk", true);
	xmlhttp.send();
}
function getCategoryOnCategoryPage(){
	$('.animation_image').show();
	document.getElementById('categoryId').value = 0;
	ajaxCall('abc');
	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			document.getElementById("putCatHere").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=getCategoryOnCategoryPage&dataTempId=tqa7ea153102ded0xq31d4ld1f200571.cloud.uk", true);
	xmlhttp.send();
}
/* for menu search - avoid ajaxcall, so new function */
function getSubSubCategoryOnCategoryPageSearch(catId, catName, gen){
	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){ document.getElementById("putCatHere").innerHTML = xmlhttp.responseText; }
	}
	catName = encodeURIComponent(catName);
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=getSubSubCategoryOnCategoryPage&data1="+catId+"&dataName="+catName+"&gen="+gen+"&dataTempId=c3v7fa153302ded0hc314ld1f21679.cloud.uk", true);
	xmlhttp.send();
}
/* new function for add to cart from xcategory paGE */
function addProductToCart(pdId, colId, price, promotionPrice, promotionId){
	document.getElementById('overlay4Cart').style.display = 'block';
	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			if(xmlhttp.responseText == 1){ getCartDataInDialog(1); }
			else { alert('Product is out of stock, please contact support for product availability.'); }
		}
	}
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=addToCartFromCategory&product_id="+pdId+"&color_id="+colId+"&price="+price+"&promoPrice="+promotionPrice+"&promoId="+promotionId+"&dataTempId=c3vcfa1543652de90hch14lkf217900.cloud.uk", true);
	xmlhttp.send();
}
// product add to wishlist
function addToWish(pid, cid){
	if(confirm('Do you want to save this product in Wishlist?')){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200){ alert(xmlhttp.responseText); }
		}
		xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=addToWish&pid="+pid+"&cid="+cid+"&dataTempId=sz7xa1g262d0xq316ld3fhu1.cloud.uk", true);
		xmlhttp.send();
	}
}
</script>

<script src="<?php echo siteUrl; ?>js/toTop.js"></script>
<script>$('#filter').stick_in_parent(); </script>
<?php if(isset($_SESSION['isLastPageIsDetail'])){ unset($_SESSION['isLastPageIsDetail']); }?>
</body>
</html>