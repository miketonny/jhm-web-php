<?php include_once "include/new_top.php";?>
<?php include "include/new_header.php";?>
<?php include_once "include/analyticstracking.php"?>
<style>
    .middle-dvdr{
        display:none;
    }
    .row.product_list_big li {
    width: 185px;
    text-align: center;
    border: none !important;
    padding:0 36px;
}
</style>

<script type="text/javascript">
     $( function() {
		 $( "#accordion" ).accordion({
		    	autoHeight: false,
		    	heightStyle: "content"
		    });
		  } )
</script>
<?php
//print_r($_GET);
/* product filter start */
$bread = '';
$condi = '';
$condiBrand = '';
$href = 'javascript:void(0);';
$subCatArray[] = null;
$subsubCatArray[] = null;
$isCat = '';
$isSubCat = '';
$isSubSubCat = '';
/* from header text field search */
if (isset($_REQUEST['searchText']) && $_REQUEST['searchText'] != '') {
	$searchText = addslashes($_REQUEST['searchText']);
	if (isset($_REQUEST['searchTextId']) && $_REQUEST['searchTextId'] != '' && $_REQUEST['searchTextType'] != '') {
		$searchTextId = $_REQUEST['searchTextId'];
		if ($_REQUEST['searchTextType'] == 'cat') {
			$condi .= " AND (tpcat.category_id = '" . $searchTextId . "')"; /* category */
		} elseif ($_REQUEST['searchTextType'] == 'brand') {
			$condiBrand .= " AND (tp.brand_id = '" . $searchTextId . "')"; /* brand *///////////// yha p chng kiya for side bar condi, not included brand
			if (isset($_REQUEST['searchTextData']) && $_REQUEST['searchTextData'] != '') {
				$searchTextData = $_REQUEST['searchTextData'];
				$condi .= " AND tpcat.category_id = '" . $searchTextData . "' "; /* category */
			}
		}
	} else {
		$condiLoop = '';
		$singleValueCondi = '';
		$searchTextArr = explode(' ', $searchText);
		$loopCount = 1;
		foreach ($searchTextArr AS $loopValue) {
			$condiLoop .= ($condiLoop != '') ? ' OR ' : '';
			$condiLoop .= " tp.product_name LIKE '%" . $loopValue . "%' OR
				tpp.product_upc LIKE '%" . $loopValue . "%' OR product_sku LIKE '%" . $loopValue . "%' OR
				tp.keyword LIKE '%" . $loopValue . "%' OR tbl_brand.brand_name LIKE '%" . $loopValue . "%' ";

			if ($loopCount == 1) {
				$orderByCondition = " ORDER BY tp.product_name LIKE '%" . $searchText . "%' DESC, tpp.product_upc LIKE '%" . $searchText . "%' DESC, product_sku LIKE '%" . $searchText . "%' DESC, tp.keyword LIKE '%" . $searchText . "%' DESC, tbl_brand.brand_name LIKE '%" . $searchText . "%' DESC ";
			}
			$loopCount++;
		}
		if ($loopCount > 1) {
			$singleValueCondi = " tp.product_name LIKE '%" . $searchText . "%' OR
				tpp.product_upc LIKE '%" . $searchText . "%' OR product_sku LIKE '%" . $searchText . "%' OR
				tp.keyword LIKE '%" . $searchText . "%' OR tbl_brand.brand_name LIKE '%" . $searchText . "%' OR ";
		}
		$condi .= ($condiLoop != '') ? ' AND (' . $singleValueCondi . $condiLoop . ' ) ' : '';
	}
} elseif (isset($_REQUEST['cat_id']) && $_REQUEST['cat_id'] > 0) {
	//fetch all sub categories for this category
	$catID = $_REQUEST['cat_id'];
	$subcQuery = mysqli_query($con, "SELECT category_id FROM tbl_category WHERE parent_id = 0 AND superparent_id ='$catID' ORDER BY category_name");
	$condi .= " AND (tpcat.category_id = '" . $catID . "' or tblcat.parent_id = '" . $catID . "' or tblcat.superparent_id = '" . $catID . "')"; /* get all products under this main category */
	while ($subCat = mysqli_fetch_object($subcQuery)) {
		// $condi .= " Or tpcat.category_id = '" . $subCat->category_id . "'";
		array_push($subCatArray, $subCat->category_id);

	}

	$genderSideBar = 'all';
	// $bread .= '<li><span> <a href="' . siteUrl . 'all/">ALL</a> </span></li>';
	$condi .= "";

} elseif (isset($_GET['gender']) && $_GET['gender'] != '') {
	/* menu gender, main cat and sub cat brand */
	$bread = '';
	// first get gender
	$genderSearch = $_GET['gender'];
	if ($genderSearch == 'male') {
		$genderSideBar = 'mens';
		$condi .= " AND ( tp.user_group LIKE 'male' OR tp.user_group LIKE 'male%') ";
		$bread .= '<li><span> <a href="' . siteUrl . 'mens/">MENS</a> </span></li>';
	} elseif ($genderSearch == 'female') {
		$genderSideBar = 'womens';
		$condi .= " AND tp.user_group LIKE '%female' ";
		$bread .= '<li><span> <a href="' . siteUrl . 'womens/">WOMENS</a> </span></li>';
	} elseif ($genderSearch == 'all') {
		$genderSideBar = 'all';
		$bread .= '<li><span> <a href="' . siteUrl . 'all/">ALL</a> </span></li>';
		$condi .= "";
	}

	// chk all cat related condition

	if (isset($_GET['data1'])) {
		$data1Arr = explode("-", $_GET['data1']);
		if ($data1Arr[0] == '0') {
			$isCat = 1;
		}
	}

	if (isset($_GET['data2'])) {
		$data2Arr = explode("-", $_GET['data2']);
		if ($data2Arr[0] == 1) {
			$isSubCat = 1;
		}
	}

	if (isset($_GET['data3'])) {
		$data3Arr = explode("-", $_GET['data3']);
		if ($data3Arr[0] == 2) {
			$isSubSubCat = 1;
		}
	}

	if ($isCat == 1 || $isSubCat == 1 || $isSubSubCat == 1) {
		// get cat id
		if ($isCat == 1 && $isSubCat == '') {
			// main cat
			$catSlug = $data1Arr[1];
			$catRs = mysqli_query($con, "SELECT * FROM tbl_category WHERE slug = '$catSlug'");
			$categoryId = mysqli_fetch_object($catRs)->category_id;
		} elseif ($isSubCat == 1 && $isSubSubCat == '') {
			// sub cat
			$catSlug = $data2Arr[1];
			$catRs = mysqli_query($con, "SELECT * FROM tbl_category WHERE slug = '$catSlug'");
			$categoryId = mysqli_fetch_object($catRs)->category_id;
		} elseif ($isSubSubCat == 1) {
			// sub sub cat
			$catSlug = $data3Arr[1];
			$catRs = mysqli_query($con, "SELECT * FROM tbl_category WHERE slug = '$catSlug'");
			$categoryId = mysqli_fetch_object($catRs)->category_id;
		}

		// get cat childs id
		if (isset($categoryId) && $categoryId != '') {
			$catQ = "SELECT category_id FROM `tbl_category` WHERE parent_id IN ($categoryId) OR superparent_id IN ($categoryId)";
			$catRs = exec_query($catQ, $con);
			$subCatArray = array();
			while ($catRow = mysqli_fetch_object($catRs)) {
				$subCatArray[] = $catRow->category_id;
			}
			$categories = implode(',', $subCatArray);
			$categories = ($categories == '') ? $categoryId : $categories . ',' . $categoryId;
			$condi .= " AND tpcat.category_id IN ($categories)";
		}
	}

	// price and brand
	foreach ($_GET AS $urlKey => $urlParam) {
		$urlArray = explode('-', $urlParam);

		if ($urlKey != 'gender' && $urlArray[0] == 'b' && $urlArray[1] != '') {
			$brandData = $urlArray[1];
			$brandArr = explode('nbsp', $urlArray[1]);

			$brandSlug = '';
			foreach ($brandArr AS $brandLoopVal) {
				$brandSlug = ($brandSlug == '') ? "'" . $brandLoopVal . "'" : $brandSlug . ",'" . $brandLoopVal . "'";
			}
			$condiBrand .= " AND tbl_brand.slug IN ($brandSlug) ";
		} elseif ($urlKey != 'gender' && $urlArray[0] == 'price' && $urlArray[1] != '') {
			$searchPrice = $urlArray[1];

			$priceRange = explode('X', $searchPrice);
			$condi .= " AND tpp.product_price >= '" . $priceRange[0] . "' AND tpp.product_price <= '" . $priceRange[1] . "' ";
		} elseif ($urlKey != 'gender' && $urlArray[0] == 'sort' && $urlArray[1] != '') {
			$sortId = $urlArray[1];
			if ($sortId == 'datAsc') {
				$orderByCondition = 'ORDER BY tp.created_on DESC';
			} elseif ($sortId == 'priAsc') {
				$orderByCondition = 'ORDER BY tpp.product_price ASC';
			} elseif ($sortId == 'priDesc') {
				$orderByCondition = 'ORDER BY tpp.product_price DESC';
			}
		}
	}
	//echo $condi.$condiBrand;
	/* fully new end */
} elseif (isset($_GET['tag']) && $_GET['tag'] != '') {
	/* for menu tag */
	$searchText = str_replace('-', ' ', $_GET['tag']);
	$condi .= "AND tp.tag LIKE '%" . $searchText . "%'"; /* product tags */
} elseif (isset($_GET['searchByBrand']) && $_GET['searchByBrand'] != '') {
	$brandId = $_GET['searchByBrand'];
	$condiBrand .= " AND tp.brand_id = '$brandId' "; ////////////////////////////////////////////////////////////////////////////////////
}

if (isset($orderByCondition) && $orderByCondition != '') {
	$orderBy = $orderByCondition;
} else {
	$orderBy = ' ORDER BY tbl_brand.brand_name ';
}
/* product filter finishhhh */

$promoCondi = '';

if (isset($_REQUEST['promotion'])) {
	$promoQuery = mysqli_query($con, "SELECT promo.promo_id, title, promo_type, ids  FROM `tbl_promotion` promo left join `tbl_promotion_detail` promodetail on promo.promo_id = promodetail.promo_id  where slug = '" . $_REQUEST['promotion'] . "'");
	$promo = mysqli_fetch_object($promoQuery);

	if (($promo->promo_type) == 'allCat') {
		$ids = explode(",", $promo->ids);
		foreach ($ids as $id) {
			$cats = mysqli_query($con, "select category_id from tbl_category where category_id = '$id' or superparent_id = '$id' or parent_id = '$id'");
			while ($cat = mysqli_fetch_object($cats)) {
				$promotionsCats[] = $cat->category_id;
			}
		}
		$promoCats = implode(",", $promotionsCats);
		$promoCondi = "tpcat.category_id IN ($promoCats) AND";
	} else if (($promo->promo_type) == 'allPro') {
		$promoCondi = "";
	}
}

$product_q_raw = "SELECT tp.product_id, tp.slug, tp.product_name, tbl_brand.brand_name,
	tp.brand_id, tpm.media_thumb, tpp.product_price, tpp.color_id,
	GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
	LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
	LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
	LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
	LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
	LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
	WHERE $promoCondi tpm.media_type = 'img' AND tpm.is_main = 1 AND tp.is_activate = 1 $condi $condiBrand Group By tp.product_id $orderBy";
$product_q = $product_q_raw;
// this is main query without limit, find all no. of records by this query
$totalProducts = mysqli_num_rows(mysqli_query($con, $product_q));
// get number
$limitNo = getNoOfProductOnCategoryPage($con);
//echo $product_q." LIMIT 0, $limitNo";
$product_rs = mysqli_query($con, $product_q . " LIMIT 0, $limitNo");
?>
<div class="clearfix"></div>

<section class="block-pt4">
    <div class="container">

        <div class="row">
            <ol class="breadcrumb bdcam">
                <?php echo (isset($bread) && $bread != '') ? $bread : '<li><span class="searchheading"> Search Result <span></li>'; ?>
                <!--                <li><a href="#">Home</a></li>
                                <li><a href="#">Deals</a></li>
                                <li><a href="#">Maskara</a></li>						-->
            </ol>
        </div>

        <div class="row">
            <div class="col-sm-12 pd-banner">
                <a href="#"><img src="<?php echo siteUrl; ?>images/new/JHM_productList_banner copy.jpg" class="img-responsive"></a>
            </div>
        </div>
        <div class="top_height">
            <div class="row">
                <div class="col-sm-12">
                    <!--start-->
                    <?php
$maxRange = 1;
$minRange = 11000;
$proPriceRs = exec_query("SELECT MAX(product_price) AS maxRange, MIN(product_price) AS minRange FROM tbl_product_price", $con);
if (mysqli_num_rows($proPriceRs)) {
	$proPrice = mysqli_fetch_object($proPriceRs);
	$maxRange = $proPrice->maxRange;
	$minRange = $proPrice->minRange;
}
?>
                    <script>
                        /* price range slider */

                        function format(n) {
                            return n.toFixed(2).replace(/./g, function(c, i, a) {
                                return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
                            });
                        }

                        $("ul#putCatHere li, ul#putCatHere li a").click(function() {
                            alert('sdsdsds');
                            $('#black-overlay').addClass('black-overlay');
                        });

                        function generateUrl() {
                            $('#black-overlay').addClass('black-overlay');
<?php
$url = siteUrl;
if (isset($genderSideBar) && $genderSideBar != '') {
	$url = $url . $genderSideBar;

	if ($isSubSubCat == 1) {
		$url = $url . '/0-' . $data1Arr[1] . '/1-' . $data2Arr[1] . '/2-' . $data3Arr[1];
	} elseif ($isSubCat == 1) {
		$url = $url . '/0-' . $data1Arr[1] . '/1-' . $data2Arr[1];
	} elseif ($isCat == 1) {
		$url = $url . '/0-' . $data1Arr[1];
	}
} else {
	$url = $url . 'all';
}
echo "var url = '$url'";
?>

                            var obj = '';
                            brandChk = false;
                            $('#brandUl input[type=checkbox]:checked').each(function() {
                                if (this.value != '') {
                                    brandChk = true;
                                    if (obj == '') {
                                        obj = this.value;
                                    }
                                    else {
                                        obj = obj + 'nbsp' + this.value;
                                    }
                                }
                            });
                            if (brandChk) {
                                url = url + '/b-' + obj;
                            }

                            sortId = document.getElementById('sortId').value;
                            if (sortId != '') {
                                url = url + '/sort-' + sortId;
                            }

                           // var price = $(this).attr('data-price');
                            var price = document.getElementById('priceId').value;
                            window.location = url + '/price-' + price;
                        }
                        function filterPrice(e){
                                 $('#priceId').val($(e).attr('data-price'));
                             generateUrl();
                        }
                        function sleepBrand() {
                            $('#black-overlay').addClass('black-overlay');
                            setTimeout(function() {
                                generateUrl();
                            }, 1500);
                        }

                        function clearBrandCondition() {
                            var xmlhttp;
                            $('#brandUl input[type=checkbox]:checked').each(function() {
                                this.checked = false;
                            });
                            generateUrl();
                        }
                    </script>
                    <!--end-->
                    <div class="col-sm-3 onl-fr-border">
                        <div class="row">
                            <div class="frst-row">
                                <!--                                <h1>CATEGORIES</h1>-->
                                <div id="bodyDiv">
                                    <div id="scrollbox3">

                                        <?php
$urlExtra = '';
// generate url
if (isset($brandData) && $brandData != '') {
	$urlExtra = "b-$brandData/";
}
if (isset($searchPrice) && $searchPrice != '') {
	$urlExtra = $urlExtra . "price-$searchPrice/";
}
if (isset($sortId) && $sortId != '') {
	$urlExtra = $urlExtra . "sort-$sortId/";
}

if (isset($genderSideBar) && $genderSideBar != '') {
	?>
	<h1>CATEGORIES</h1> 
    <div class="level0" >
        <div id="accordion" style="display: none;" >
        <?php
	if ($subCatArray != null) {
		$condition = "";
		$condition .= implode(',', array_filter($subCatArray));
		// if ($condition == null) {
		// 	//lowest level of category reached, use current category id to search
		// 	$condition = $catID;
		// }
		if($condition == null){
			//checkwhether it has subsubcat
			$subsubCatsQry = mysqli_query($con, "SELECT category_id, category_name FROM tbl_category WHERE parent_id = '$catID' ORDER BY category_name");
			if (mysqli_num_rows($subsubCatsQry) > 0) {
			while($subsubCat = mysqli_fetch_object($subsubCatsQry)){
				array_push($subCatArray, $subsubCat->category_id);
				}
			$condition .= implode(',', array_filter($subCatArray));
			}else{
				//lowest level of category reached, use current category id to search
				$condition = $catID;
			}
		}
		$cat_rs = exec_query("SELECT category_name, category_id, slug FROM tbl_category WHERE is_activate = 0 and category_id IN (" . $condition . ") Order By category_name", $con);
		while ($cat_row = mysqli_fetch_object($cat_rs)) {
			?>
            <h3 style="background:transparent;border:none;"><a id="subCatRef" href="<?php echo siteUrl; ?>product-search?cat_id=<?php echo $cat_row->category_id ?> " onclick="gotoURL(this);"><?php echo $cat_row->category_name; ?></a></h3>
            <div style="border:none;background:transparent;min-height:0;padding-top:0;">
            <?php
$sscrs = mysqli_query($con, "SELECT category_id, category_name FROM tbl_category WHERE parent_id = '$cat_row->category_id' AND superparent_id = '$catID' ORDER BY category_name");
			while ($ssCat = mysqli_fetch_object($sscrs)) {?>
			 
			 	<div><a href="<?php echo siteUrl; ?>product-search?cat_id=<?php echo $ssCat->category_id ?> "><?php echo $ssCat->category_name; ?></a></div>		
        <?php }?>
       		</div>
    	<?php }}?>
        	 
        </div>
    </div>
                                                <input type="hidden" id="mainCat" value="" />
                                                <input type="hidden" id="subCat" value="" />
                                                <input type="hidden" id="subSubCat" value="" />
                                                <?php
if ($isCat == 1 || $isSubCat == 1 || $isSubSubCat == 1) {
		if ($isCat == 1 && $isSubCat == '') {
			// main cat
			$catSlug = $data1Arr[1];
			$catRs = mysqli_query($con, "SELECT category_id, category_name FROM tbl_category WHERE slug = '$catSlug'");
			$categoryData = mysqli_fetch_object($catRs);
			$categoryId = $categoryData->category_id;
			?>

                                                    <h1><a href="<?php echo siteUrl . $genderSideBar . '/' . $urlExtra; ?>">CATEGORIES </a></h1>
                                                    <div class="level0">
                                                        <h4><a href="#"> <?php echo $categoryData->category_name; ?> </a></h4>
                                                        <?php if ($genderSideBar == 'all') {?>
                                                            <a href="<?php echo siteUrl . 'mens/0-' . $catSlug . '/' . $urlExtra; ?>"> MENS </a><br/>
                                                            <a href="<?php echo siteUrl . 'womens/1-' . $catSlug . '/' . $urlExtra; ?>"> WOMENS </a>
                                                        <?php }?>
                                                        <ul id="putCatHere">
                                                            <?php
$catSideRs = exec_query("SELECT category_name,category_id,slug FROM tbl_category WHERE flag = 1 AND parent_id=0 AND superparent_id='$categoryId'", $con);
			while ($catSideRow = mysqli_fetch_object($catSideRs)) {
				?>
                                                                <li>
                                                                    <a href="<?php echo siteUrl . $genderSideBar . '/0-' . $catSlug . '/1-' . $catSideRow->slug . '/' . $urlExtra; ?>"><?php echo ucfirst($catSideRow->category_name); ?></a>
                                                                </li>
                                                            <?php }?>
                                                        </ul>
                                                    </div>
                                                    <input type="hidden" id="mainCat" value="0-<?php echo $catSlug; ?>" />
                                                    <input type="hidden" id="subCat" value="" />
                                                    <input type="hidden" id="subSubCat" value="" />
                                                    <?php
$bread .= "<li><span> <a href='" . siteUrl . $genderSideBar . "/0-$catSlug/'>$categoryData->category_name</a> </span></li>";
			////////////////////////
		} elseif ($isSubCat == 1 && $isSubSubCat == '') {
			// sub cat
			$catSlug = $data2Arr[1];
			$catRs = mysqli_query($con, "SELECT * FROM tbl_category WHERE slug = '$catSlug'");
			$categoryData = mysqli_fetch_object($catRs);
			$categoryId = $categoryData->category_id;

			$parentCat = getCategory(array('category_name', 'category_id', 'superparent_id', 'slug'), $categoryId, $con);
			$superParentCat = getCategory(array('category_name', 'category_id', 'slug'), $parentCat->superparent_id, $con);
			?>

                                                    <h1><a href="<?php echo siteUrl . $genderSideBar . '/' . $urlExtra; ?>">CATEGORIES </a> </h1>
                                                    <div class="level0">
                                                        <h4><a href="<?php echo siteUrl . $genderSideBar . '/0-' . $superParentCat->slug . '/' . $urlExtra; ?>"><?php echo $superParentCat->category_name; ?></a></h4>
                                                        <div class="level1">
                                                            <h4><?php echo $categoryData->category_name; ?></h4>
                                                            <ul id="putCatHere">
                                                                <?php
$catSideRs = exec_query("SELECT category_name, category_id, slug FROM tbl_category WHERE flag = 1 AND parent_id = '$categoryId' AND superparent_id != 0", $con);
			while ($catSideRow = mysqli_fetch_object($catSideRs)) {
				?>
                                                                    <li>
                                                                        <a href="<?php echo siteUrl . $genderSideBar . '/0-' . $superParentCat->slug . '/1-' . $parentCat->slug . '/2-' . $catSideRow->slug . '/' . $urlExtra; ?>"><?php echo $catSideRow->category_name; ?></a>
                                                                    </li>
                                                                <?php }?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="mainCat" value="0-<?php echo $superParentCat->slug; ?>" />
                                                    <input type="hidden" id="subCat" value="1-<?php echo $parentCat->slug; ?>" />
                                                    <input type="hidden" id="subSubCat" value="" />
                                                    <?php
$bread .= "<li><span> <a href='" . siteUrl . $genderSideBar . '/0-' . $superParentCat->slug . "/'>$superParentCat->category_name</a> </span></li> <li><span> <a href='" . siteUrl . $genderSideBar . '/0-' . $superParentCat->slug . "/1-" . $catSlug . "/'>$categoryData->category_name</a> </span></li>";
			////////////////////////
		} elseif ($isSubSubCat == 1) {
			// sub sub cat
			$catSlug = $data3Arr[1];
			$catRs = mysqli_query($con, "SELECT * FROM tbl_category WHERE slug = '$catSlug'");
			$categoryData = mysqli_fetch_object($catRs);
			$categoryId = $categoryData->category_id;

			$superParentCat = getCategory(array('category_name', 'category_id', 'slug'), $parentCat->superparent_id, $con);
			?>

                                                    <h1><a href="<?php echo siteUrl . $genderSideBar . '/' . $urlExtra; ?>">CATEGORIES </a></h1>
                                                    <div class="level0">
                                                        <h4><a href="<?php echo siteUrl . $genderSideBar . '/0-' . $superParentCat->slug . '/' . $urlExtra; ?>"> <?php echo $superParentCat->category_name; ?></a></h4>
                                                        <div class="level1">
                                                            <h4><a href="<?php echo siteUrl . $genderSideBar . '/0-' . $superParentCat->slug . '/1-' . $parentCat->slug . '/' . $urlExtra; ?>"><?php echo $parentCat->category_name; ?></a></h4>
                                                            <div class="level2">
                                                                <h4><?php echo $categoryData->category_name; ?></h4>
                                                                <ul id="putCatHere">
                                                                    <?php
$catSideRs = exec_query("SELECT category_name, category_id, slug FROM tbl_category WHERE flag = 1 AND parent_id = '$parentCat->category_id' AND superparent_id != 0", $con);
			while ($catSideRow = mysqli_fetch_object($catSideRs)) {
				?>
                                                                        <li>
                                                                            <a <?php echo ($catSlug == $catSideRow->slug) ? 'style="font-weight:bold;"' : ''; ?> href="<?php echo siteUrl . $genderSideBar . '/0-' . $superParentCat->slug . '/1-' . $parentCat->slug . '/2-' . $catSideRow->slug . '/' . $urlExtra; ?>/"><?php echo $catSideRow->category_name; ?></a>
                                                                        </li>
                                                                    <?php }?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="mainCat" value="0-<?php echo $superParentCat->slug; ?>" />
                                                    <input type="hidden" id="subCat" value="1-<?php echo $parentCat->slug; ?>" />
                                                    <input type="hidden" id="subSubCat" value="2-<?php echo $catSlug; ?>" />
                                                    <?php
$bread .= "<li><span> <a href='" . siteUrl . $genderSideBar . '/0-' . $superParentCat->slug . "/'>$superParentCat->category_name</a> </span></li>
                            <li><span> <a href='" . siteUrl . $genderSideBar . '/0-' . $superParentCat->slug . '/1-' . $parentCat->slug . "/'>$parentCat->category_name</a> </span></li>
                            <li><span> <a href='" . siteUrl . $genderSideBar . '/0-' . $superParentCat->slug . '/1-' . $parentCat->slug . '/2-' . $categoryData->slug . "/'>$categoryData->category_name</a> </span></li>";
			////////////////////////
		}
	} else {

	}
} else {
	?> <h1>CATEGORIES </h1>
                                            <div class="level0">
                                                <ul id="putCatHere">
                                                    <?php
$cat_rs = exec_query("SELECT category_name, category_id, slug FROM tbl_category WHERE flag = 1 AND parent_id = 0 AND superparent_id = 0", $con);
	while ($cat_row = mysqli_fetch_object($cat_rs)) {
		?>
                                                        <li>
                                                            <a href="<?php echo siteUrl; ?>product-search?cat_id=<?php echo $cat_row->category_id?>"><?php echo $cat_row->category_name; ?></a>
                                                        </li>
                                                    <?php }?>
                                                </ul>
                                            </div>
                                            <input type="hidden" id="mainCat" value="" />
                                            <input type="hidden" id="subCat" value="" />
                                            <input type="hidden" id="subSubCat" value="" />
                                        <?php }?>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 middle-dvdr"></div>

                            <div class="frst-row" style="display:none;">

                                <h1>BRANDS<a href="javascript:void(0);" style="float:right; font-size:14px; padding:5px;" onclick="clearBrandCondition();"><strong>X</strong> Reset</a></h1>
                                <div id="bodyDiv">
                                    <form action="<?php echo $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" method="get" id="bFormB">
                                        <div id="scrollbox3">
                                            <ul id="brandUl">
                                                <?php
$chks = 'checked="checked"';
$bQ = "SELECT tbl_brand.brand_name, tbl_brand.slug,tbl_brand.flag, tbl_brand.brand_id, (
		SELECT COUNT(DISTINCT tp.product_id) AS count FROM tbl_product tp
		LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
		LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
		LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
		LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
		WHERE tpm.media_type = 'img' AND tpm.is_main = 1 AND tp.is_activate = 1 AND tp.brand_id = tbl_brand.brand_id $condi $condiBrand) AS totalProduct
		FROM tbl_brand ORDER BY tbl_brand.brand_name";
$br_rs = mysqli_query($con, $bQ);
$brand_available = "false";
while ($br_row = mysqli_fetch_object($br_rs)) {
	if ($br_row->flag == 1) {
		$brand_available = "true";
		?>

                                                    <li>
                                                        <input name="brandFilter[]" type="checkbox" value="<?php echo $br_row->slug; ?>" onchange="sleepBrand();"
                                                               <?php echo (isset($brandArr) && in_array($br_row->slug, $brandArr)) ? $chks : ''; ?> />
                                                               <?php echo $br_row->brand_name . ' (' . $br_row->totalProduct . ')'; ?>

                                                    </li>
                                                    <?php }}
if ($brand_available == "false") {
	?>
                                                    <li>
                                                        <label>No Brand Available</label>
                                                    </li>
                                                    <?php }?>
                                            </ul>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-sm-12 middle-dvdr"></div>

                            <div class="frst-row">
                                <h1>PRICE</h1>
                                <div id="bodyDiv">
                                    <div id="scrollbox3">
                                        <ul>
                                            <li><a href="javascript:void(0);" data-price="0X50" class="search_price" onclick="filterPrice(this);">Less than 50.00</a></li>
                                            <li><a href="javascript:void(0);" data-price="50X100" class="search_price" onclick="filterPrice(this);">50.00    -  100.00</a></li>
                                            <li><a href="javascript:void(0);" data-price="100X200" class="search_price" onclick="filterPrice(this);">100.00 - 200.00</a></li>
                                            <li><a href="javascript:void(0);" data-price="200X300" class="search_price" onclick="filterPrice(this);">200.00 - 300.00</a></li>
                                            <li><a href="javascript:void(0);" data-price="300X400" class="search_price" onclick="filterPrice(this);">300.00 - 400.00</a></li>
                                            <li><a href="javascript:void(0);" data-price="400X500" class="search_price" onclick="filterPrice(this);">400.00 - 500.00</a></li>
                                            <li><a href="javascript:void(0);" data-price="500X600" class="search_price" onclick="filterPrice(this);">500.00 - 600.00</a></li>
                                            <li><a href="javascript:void(0);" data-price="600X700" class="search_price" onclick="filterPrice(this);">600.00 - 700.00</a></li>
                                            <li><a href="javascript:void(0);" data-price="700X800" class="search_price" onclick="filterPrice(this);">700.00 - 800.00</a></li>
                                            <li><a href="javascript:void(0);" data-price="800X900" class="search_price" onclick="filterPrice(this);">800.00 - 900.00</a></li>
                                            <li><a href="javascript:void(0);" data-price="900X30000" class="search_price" onclick="filterPrice(this);">More than 900.00</a></li>
                                        </ul>
                                        <input type="hidden" id="priceId" value="0X10000" />
                                    </div>

                                </div>
                            </div>
                            <div class="sight-btm"></div>

                        </div>
                    </div>

                    <div class="col-sm-9">

                        <div class=" row right-pro-top">
                            <div class="col-sm-6 shortby">
                                <select id="sort" onchange="$('#sortId').val(this.value);generateUrl();" class="selectpicker">
                                    <option value=""> Sort by </option>
                                    <option value="datAsc"> What's NEW ! </option>
                                    <option value="priDesc"> Price: High to Low </option>
                                    <option value="priAsc"> Price: Low to High </option>
                                </select>
                                <input type="hidden" id="sortId" value="" />

                            </div>
                            <div class="col-sm-6  pagi-hold">
                                <nav>
<!--                                    <ul class="pagination fr-pad-one">
                                        <li class="page-item nw-page-item">
                                            <a class="page-link" href="#" aria-label="Previous">
                                                <span aria-hidden="true"><</span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                        </li>
                                        <li class="page-item nw-page-item"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item nw-page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item nw-page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item nw-page-item">
                                            <a class="page-link" href="#" aria-label="Next">
                                                <span aria-hidden="true">></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </li>
                                    </ul>-->
                                </nav>
                            </div>
                        </div>

                        <div class=" row product_list_big">
                            <?php
if ($totalProducts > 0) {
	while ($row = mysqli_fetch_object($product_rs)) {
		// for get promotion
		$all_cat = $row->all_cat;
		$promoType = '';
		$promoValue = '';
		$promoArr = getPromotionForProduct($row->product_id, $row->brand_id, $all_cat, $con);
		if (!empty($promoArr)) {
			$promoType = $promoArr['percent_or_amount'];
			$promoValue = $promoArr['promo_value'];
		}

		$img = 'site_image/product/' . $row->media_thumb;
		$price = $row->product_price;
//                                    $disPrice=0;
		$disPrice = $row->product_price;
		$promotionId = 0;
		?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="feture-grid">
                                    <a class="pdphoto" href="<?php echo siteUrl; ?>detail/<?php echo $row->product_id; ?>HXYK<?php echo $row->slug; ?>TZS1YL<?php echo $row->color_id; ?>">
                                        <img  style="max-width: 100%;height:200px;"  src="<?php echo siteUrl . $img; ?>" class="center-block img-style">
                                        </a>
                                        <h1><?php echo $row->brand_name; ?></h1>
                                        <a class="pdphoto" href="<?php echo siteUrl; ?>detail/<?php echo $row->product_id; ?>HXYK<?php echo $row->slug; ?>TZS1YL<?php echo $row->color_id; ?>">
                                        <p><?php echo (strlen($row->product_name) > 25) ? substr($row->product_name, 0, 25) . '...' : $row->product_name; ?></p>
                                        </a>
                                        <ul>

                                            <li class="productRRP">$<?php echo $row->product_price; ?>&nbsp;&nbsp;&nbsp;<p></p></li>
                                        </ul>
                                        <div class="know-more1"><a href="javascript:void(0);"  onclick="addProductToCart(<?php echo $row->product_id; ?>, <?php echo $row->color_id; ?>, <?php echo $price; ?>, <?php echo $disPrice; ?>, <?php echo $promotionId; ?>)" class="more-one"><img src="<?php echo siteUrl; ?>images/new/btn_one.png"></a></div>
                                    </div>
                                </div>
                                <?php
}
} else {
	if (@$_REQUEST['searchText'] != '') {
		echo '<span style="color:red;text-align:center!important; margin-left:40%;">Search result for "' . $_REQUEST['searchText'] . '", your search returns no result.</span>';
	}
	?>
                            <div style="width: 100%;">
                                <h2 style="background-color: #4f3737;
    color: #fff;
    padding: 4px;
    text-align: center;
    margin: 12px;">POPULAR BRANDS</h2>
                <div style="display: block; padding: 0 8px;">
                    <ul>
					<?php
$brandQ = "SELECT * FROM tbl_brand WHERE brand_img != '' AND flag=1 ORDER BY brand_name LIMIT 0, 20";
	$brandRs = exec_query($brandQ, $con);
	if (mysqli_num_rows($brandRs)) {
		while ($brandRow = mysqli_fetch_object($brandRs)) {?>

                        <li style="border: 1px #ccc solid;float: left;list-style: none; height: 104px;margin: 6px;">
                            	<a href="<?php echo siteUrl; ?>all/b-<?php echo $brandRow->slug; ?>/">
                                    <img height="100" alt="<?php echo $brandRow->brand_name; ?>" src="<?php echo siteUrl . 'site_image/brand_logo/' . $brandRow->brand_img; ?>" style="max-width: 180px; min-width: 170px;">
                                </a>
                            </li>

                        <?php }}?>
                        <div style="clear:both;"></div>
                    </ul>
                </div>
       </div>
                            <?php
}
?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div><!--container-->
</section><!--block-pt4-->
<div class="bottom_height"></div>

<!--end-->


<?php include "include/new_footer.php";?>

<script type="text/javascript">
    $(document).ready(function() {
        var loading = false; //to prevents multipal ajax loads
        var no = <?php echo getNoOfProductOnCategoryPage($con); ?>;
        //display accordion after page load
        if ($('#accordion').length > 0 ) {
        var accc = document.getElementById('accordion');
    		accc.style.display = "";    
        }
       
          $( function() {
		    $( "#accordion" ).accordion({
		    	autoHeight: false,
		    	heightStyle: "content"
		    });
		  } );
        $(window).scroll(function() {

            if ($(window).scrollTop() > 100) {
                $('#filter').css('top', $(window).scrollTop() - 150);
            } else
            {
                $('#filter').css('top', 'auto');
            }
            if (($(window).scrollTop() + $(window).height()) > ($(document).height() - 300)) {  //user scrolled to bottom of the page?
                if (loading == false) {
                    $('#black-overlay').addClass('black-overlay');
                    loading = true; //prevent further ajax loading

                    var searchText = '';
                    var searchTextId = '';
                    var searchTextType = '';
                    var searchTextData = '';
<?php
if (isset($_REQUEST['searchTextId']) && $_REQUEST['searchTextId'] != '') {
	echo "searchTextId = '" . $_REQUEST['searchTextId'] . "';";
}
if (isset($_REQUEST['searchTextType']) && $_REQUEST['searchTextType'] != '') {
	echo "searchTextType = '" . $_REQUEST['searchTextType'] . "';";
}
if (isset($_REQUEST['searchTextData']) && $_REQUEST['searchTextData'] != '') {
	echo "searchTextData = '" . $_REQUEST['searchTextData'] . "';";
}
if (isset($_REQUEST['searchText']) && $_REQUEST['searchText'] != '') {
	echo "searchText = '" . $_REQUEST['searchText'] . "';";
}
?>

                    var tag = '';
<?php
if (isset($_GET['tag']) && $_GET['tag'] != '') {
	echo "tag = '" . $_GET['tag'] . "';";
}
?>

                    var gender = '';
<?php
if (isset($genderSideBar) && $genderSideBar != '') {
	echo "gender = '$genderSideBar';";
}
?>

                    var cat = '';
<?php
if (isset($categoryId) && $categoryId != '') {
	echo "cat = '$categoryId';";
}
?>

<?php
if (isset($promoCats) && $promoCats != '') {
	echo "cat = '$promoCats';";
}
?>

                    var brands = '';
                    $('#brandUl input[type=checkbox]:checked').each(function() {
                        if (this.value != '') {
                            if (brands == '') {
                                brands = this.value;
                            }
                            else {
                                brands = brands + 'nbsp' + this.value;
                            }
                        }
                    });
                    sortId = document.getElementById('sortId').value;
                    price = document.getElementById('priceId').value;

                    var noProChange = $('#noOfProductOnCategoryPage').val();
                    $.get('<?php echo siteUrl; ?>autoload_process.php', {
                        'limit1': noProChange, 'limit2': no, 'tag': tag,
                        'gender': gender, 'cat': cat, 'brands': brands, 'price': price, 'sortId': sortId,
                        'searchText': searchText, 'searchTextId': searchTextId, 'searchTextType': searchTextType, 'searchTextData': searchTextData
                    }, function(data) {
                        $("#putProduct").append(data);
                        $("#black-overlay").remove();
                        $("#putProduct").append('<li id="black-overlay" style="height:273px"><table style="width:100%;height:100%;"><tr><td valign="middle"><img src="<?php echo siteUrl; ?>images/load.gif"/></td></tr></table></li>');
                        loading = false;
                        $('#noOfProductOnCategoryPage').val(parseInt(noProChange) + parseInt(no));
                        $('#black-overlay').removeClass('black-overlay');
                        $('#filter').removeClass('is_stuck');
                    });
                }

            }
        });
    });

    /* new function for add to cart from xcategory paGE */
    function addProductToCart(pdId, colId, price, promotionPrice, promotionId) {
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }
        else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == 1) {
                    getCartDataInDialog(1);
                }
                else {
                    alert('Oops!! Product not added to cart,');
                }
            }
        }
        xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=addToCartFromCategory&product_id=" + pdId + "&color_id=" + colId + "&price=" + price + "&promoPrice=" + promotionPrice + "&promoId=" + promotionId + "&dataTempId=c3vcfa1543652de90hch14lkf217900.cloud.uk", true);
        xmlhttp.send();
    }
// product add to wishlist
    function addToWish(pid, cid) {
        if (confirm('Do you want to save this product in Wishlist?')) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert(xmlhttp.responseText);
                }
            }
            xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=addToWish&pid=" + pid + "&cid=" + cid + "&dataTempId=sz7xa1g262d0xq316ld3fhu1.cloud.uk", true);
            xmlhttp.send();
        }
    }


   function gotoURL(t){
    var href = $(t).attr("href");//get the href so we can navigate later
    //when update has finished, navigate to the other page
    window.location = href;
	}	



</script>
<script>
    function remove_product(user_id, product_id, price) {
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }
        else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == 1) {
                    getCartDataInDialog();
                }
                else {
                    alert('Oops!! Product not added to cart,');
                }
            }
        }
        xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=removeToCartFromCategory&product_id=" + product_id + "&price=" + price + "&dataTempId=c3vcfa1543652de90hch14lkf217900.cloud.uk", true);
        xmlhttp.send();
    }
</script>
<!--<div id="black-overlay" class=""> <img src="<?php //echo siteUrl;       ?>images/load.gif"> </div>-->
<?php include "include/new_bottom.php";?>