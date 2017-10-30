<?php $maxRange = 1; $minRange = 11000;
$proPriceRs = exec_query("SELECT MAX(product_price) AS maxRange, MIN(product_price) AS minRange FROM tbl_product_price", $con);
if(mysql_num_rows($proPriceRs)){ $proPrice = mysql_fetch_object($proPriceRs); $maxRange = $proPrice->maxRange; $minRange = $proPrice->minRange; } ?>
<script>
/* price range slider */
$(function() {
	<?php $minRangeSearch = $minRange; $maxRangeSearch = $maxRange;
	if(isset($searchPrice) && $searchPrice != ''){
		$priceRange = explode('X', $searchPrice);
		$minRangeSearch = $priceRange[0];
		$maxRangeSearch = $priceRange[1];
	}
	?>
	$("#slider-range").slider({ range: true, min: <?php echo $minRange; ?>, max: <?php echo $maxRange; ?>, values: [<?php echo $minRangeSearch.', '.$maxRangeSearch; ?>],
		slide: function(event, ui) {
			$("#priceId").val(ui.values[0]+"X"+ui.values[1]);
			amount1 = format(ui.values[0]);
			amount2 = format(ui.values[1]);
			$("#price_field").text(amount1+" - "+amount2);
		}
	});
	$("#priceId").val($("#slider-range").slider("values", 0)+"X"+$("#slider-range").slider("values", 1));
	amount = format($("#slider-range").slider("values", 1));
	$("#price_field").text($("#slider-range").slider("values", 0)+" - "+amount);
});
function format(n){
    return n.toFixed(2).replace(/./g, function(c, i, a){ return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c; });
}

$( "ul#putCatHere li, ul#putCatHere li a" ).click(function() {
	alert('sdsdsds');
	$('#black-overlay').addClass('black-overlay');
});

function generateUrl(){
	$('#black-overlay').addClass('black-overlay');
	<?php $url = siteUrl;
	if(isset($genderSideBar) && $genderSideBar != ''){
		$url = $url.$genderSideBar;
		
		if($isSubSubCat == 1){
			$url = $url.'/0-'.$data1Arr[1].'/1-'.$data2Arr[1].'/2-'.$data3Arr[1];
		}
		elseif($isSubCat == 1){
			$url = $url.'/0-'.$data1Arr[1].'/1-'.$data2Arr[1];
		}
		elseif($isCat == 1){
			$url = $url.'/0-'.$data1Arr[1];
		}
	}
	else{ $url = $url.'all'; }
	echo "var url = '$url'";
	?>
	
	var obj = ''; brandChk = false;
	$('#brandUl input[type=checkbox]:checked').each(function() {
		if(this.value != ''){ brandChk = true;
			if (obj == ''){ obj = this.value; }
			else{ obj = obj+'nbsp'+this.value; }
		}
	});
	if(brandChk){ url = url+'/b-'+obj; }
	
	sortId = document.getElementById('sortId').value;
	if(sortId != ''){ url = url+'/sort-'+sortId; }
	
	var price = document.getElementById('priceId').value;
	window.location = url+'/price-'+price;
}

function sleepBrand(){
	$('#black-overlay').addClass('black-overlay');
	setTimeout(function(){ 
		generateUrl();
    }, 1500);  
}

function clearBrandCondition(){	var xmlhttp;
	$('#brandUl input[type=checkbox]:checked').each(function() {
		this.checked = false;
	});
	generateUrl();
}
</script>

<div id="filter" >
	<h5> FILTER PRODUCTS BY </h5>
     
    	<?php $urlExtra = '';
		// generate url
		if(isset($brandData) && $brandData != ''){
			$urlExtra = "b-$brandData/";
		}
		if(isset($searchPrice) && $searchPrice != ''){
			$urlExtra = $urlExtra."price-$searchPrice/";
		}
		if(isset($sortId) && $sortId != ''){
			$urlExtra = $urlExtra."sort-$sortId/";
		}
		
		if(isset($genderSideBar) && $genderSideBar != ''){
			if($isCat == 1 || $isSubCat == 1 || $isSubSubCat == 1){ //means ek na ek cat to aai h
				if($isCat == 1 && $isSubCat == ''){ // main cat
					$catSlug = $data1Arr[1];
					$catRs = mysql_query("SELECT category_id, category_name FROM tbl_category WHERE slug = '$catSlug'");
					$categoryData = mysql_fetch_object($catRs);
					$categoryId = $categoryData->category_id;
					?>
					 
						<h1><a href="<?php echo siteUrl.$genderSideBar.'/'.$urlExtra; ?>">CATEGORIES </a></h1>
                        <div class="level0">
                            <h2><a href="#"> <?php echo $categoryData->category_name; ?> </a></h2>
                            <?php if($genderSideBar == 'all'){ ?><br/>
                                <a href="<?php echo siteUrl.'mens/0-'.$catSlug.'/'.$urlExtra; ?>"> MENS </a><br/>
                                <a href="<?php echo siteUrl.'womens/1-'.$catSlug.'/'.$urlExtra; ?>"> WOMENS </a>
                            <?php } ?>
                            <ul id="putCatHere"> 
                            <?php
                            $catSideRs=exec_query("SELECT category_name,category_id,slug FROM tbl_category WHERE parent_id=0 AND superparent_id='$categoryId'",$con);
                            while($catSideRow = mysql_fetch_object($catSideRs)){ ?>
                            <li>
                                <a href="<?php echo siteUrl.$genderSideBar.'/0-'.$catSlug.'/1-'.$catSideRow->slug.'/'.$urlExtra; ?>"><?php echo  ucfirst($catSideRow->category_name); ?></a>
                            </li>
                            <?php } ?>
                            </ul>
                        </div>
					<input type="hidden" id="mainCat" value="0-<?php echo $catSlug; ?>" />
					<input type="hidden" id="subCat" value="" />
					<input type="hidden" id="subSubCat" value="" />
					<?php $bread .= "<li><span> <a href='".siteUrl.$genderSideBar."/0-$catSlug/'>$categoryData->category_name</a> </span></li>";
					////////////////////////
				}
				elseif($isSubCat == 1 && $isSubSubCat == ''){ // sub cat
					$catSlug = $data2Arr[1];
					$catRs = mysql_query("SELECT * FROM tbl_category WHERE slug = '$catSlug'");
					$categoryData = mysql_fetch_object($catRs);
					$categoryId = $categoryData->category_id;
					
					$parentCat = getCategory(array('category_name', 'category_id', 'superparent_id', 'slug'), $categoryId, $con);
					$superParentCat = getCategory(array('category_name', 'category_id', 'slug'), $parentCat->superparent_id, $con);
					?>
				
						<h1><a href="<?php echo siteUrl.$genderSideBar.'/'.$urlExtra; ?>">CATEGORIES </a> </h1>
                        <div class="level0">
                            <h2><a href="<?php echo siteUrl.$genderSideBar.'/0-'.$superParentCat->slug.'/'.$urlExtra; ?>"><?php echo $superParentCat->category_name;?></a></h2>
                            <div class="level1">
                            <h2><?php echo $categoryData->category_name;?></h2>
                            <ul id="putCatHere">
                            <?php
                            $catSideRs = exec_query("SELECT category_name, category_id, slug FROM tbl_category WHERE parent_id = '$categoryId' AND superparent_id != 0", $con);
                            while($catSideRow = mysql_fetch_object($catSideRs)){ ?>
                            <li>
                                <a href="<?php echo siteUrl.$genderSideBar.'/0-'.$superParentCat->slug.'/1-'.$parentCat->slug.'/2-'.$catSideRow->slug.'/'.$urlExtra; ?>"><?php echo $catSideRow->category_name; ?></a>
                            </li>
                            <?php } ?>
                            </ul>
                            </div>
                    	</div>
                            <input type="hidden" id="mainCat" value="0-<?php echo $superParentCat->slug; ?>" />
                            <input type="hidden" id="subCat" value="1-<?php echo $parentCat->slug; ?>" />
                            <input type="hidden" id="subSubCat" value="" />
                            <?php $bread .= "<li><span> <a href='".siteUrl.$genderSideBar.'/0-'.$superParentCat->slug."/'>$superParentCat->category_name</a> </span></li> <li><span> <a href='".siteUrl.$genderSideBar.'/0-'.$superParentCat->slug."/1-".$catSlug."/'>$categoryData->category_name</a> </span></li>";
                             ////////////////////////
                        }
                        elseif($isSubSubCat == 1){ // sub sub cat
                            $catSlug = $data3Arr[1];
                            $catRs = mysql_query("SELECT * FROM tbl_category WHERE slug = '$catSlug'");
                            $categoryData = mysql_fetch_object($catRs);
                            $categoryId = $categoryData->category_id;
                            
                            
                            $superParentCat = getCategory(array('category_name', 'category_id', 'slug'), $parentCat->superparent_id, $con);
                            ?>
                            
                                <h1><a href="<?php echo siteUrl.$genderSideBar.'/'.$urlExtra; ?>">CATEGORIES </a></h1>
                                <div class="level0">
                                    <h2><a href="<?php echo siteUrl.$genderSideBar.'/0-'.$superParentCat->slug.'/'.$urlExtra; ?>"> <?php echo $superParentCat->category_name;?></a></h2>
                                    <div class="level1">
                                        <h2><a href="<?php echo siteUrl.$genderSideBar.'/0-'.$superParentCat->slug.'/1-'.$parentCat->slug.'/'.$urlExtra;?>"><?php echo $parentCat->category_name; ?></a></h2>
                                        <div class="level2">
                                            <h2><?php echo $categoryData->category_name;?></h2>
                                            <ul id="putCatHere">
                                            <?php
                                            $catSideRs = exec_query("SELECT category_name, category_id, slug FROM tbl_category WHERE parent_id = '$parentCat->category_id' AND superparent_id != 0", $con);
                                            while($catSideRow = mysql_fetch_object($catSideRs)){ ?>
                                            <li>
                                                <a <?php echo ($catSlug == $catSideRow->slug)?'style="font-weight:bold;"':''; ?> href="<?php echo siteUrl.$genderSideBar.'/0-'.$superParentCat->slug.'/1-'.$parentCat->slug.'/2-'.$catSideRow->slug.'/'.$urlExtra; ?>/"><?php echo $catSideRow->category_name; ?></a>
                                            </li>
                                            <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <input type="hidden" id="mainCat" value="0-<?php echo $superParentCat->slug; ?>" />
                            <input type="hidden" id="subCat" value="1-<?php echo $parentCat->slug; ?>" />
                            <input type="hidden" id="subSubCat" value="2-<?php echo $catSlug; ?>" />
                            <?php $bread .= "<li><span> <a href='".siteUrl.$genderSideBar.'/0-'.$superParentCat->slug."/'>$superParentCat->category_name</a> </span></li>
                            <li><span> <a href='".siteUrl.$genderSideBar.'/0-'.$superParentCat->slug.'/1-'.$parentCat->slug."/'>$parentCat->category_name</a> </span></li>
                            <li><span> <a href='".siteUrl.$genderSideBar.'/0-'.$superParentCat->slug.'/1-'.$parentCat->slug.'/2-'.$categoryData->slug."/'>$categoryData->category_name</a> </span></li>";
                            ////////////////////////
                        }
                    }
                    else{
                        ?><h1>CATEGORIES </h1> 
                        <div class="level0">
                        <ul id="putCatHere">
                        <?php $cat_rs = exec_query("SELECT category_name, category_id, slug FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0", $con);
                        while($cat_row = mysql_fetch_object($cat_rs)){ ?>
                        <li>
                            <a href="<?php echo siteUrl.$genderSideBar.'/0-'.$cat_row->slug.'/'.$urlExtra; ?>"><?php echo $cat_row->category_name; ?></a>
                        </li>
                        <?php } ?>
                        </ul>
                        </div>
                        <input type="hidden" id="mainCat" value="" />
                        <input type="hidden" id="subCat" value="" />
                        <input type="hidden" id="subSubCat" value="" />
                    <?php }
                }else{ ?> <h1>CATEGORIES </h1>
                <div class="level0">
                <ul id="putCatHere">
                <?php
                    $cat_rs = exec_query("SELECT category_name, category_id, slug FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0", $con);
                    while($cat_row = mysql_fetch_object($cat_rs)){ ?>
                    <li>
                        <a href="<?php echo siteUrl.'all/0-'.$cat_row->slug.'/'.$urlExtra; ?>/"><?php echo $cat_row->category_name; ?></a>
                    </li>
                    <?php } ?>
                    </ul>
                    </div>
                    <input type="hidden" id="mainCat" value="" />
                    <input type="hidden" id="subCat" value="" />
                    <input type="hidden" id="subSubCat" value="" />
                <?php } ?>
             
    
    
    
    
    <ul id="priceUl"> 
		<li style="text-align:center; display:none;"><div id="totalProductSidebar"> (<?php echo $totalProducts.' Items'; ?>) </div></li>
		<li> <div id="slider-range" onclick="generateUrl();"></div> </li>
		<li style="text-align:center;">$ <span id="price_field"></span> </li>
		<input type="hidden" id="priceId" value="<?php echo (isset($searchPrice) && $searchPrice != '')?$searchPrice:0; ?>" />
    </ul>
    
    
    
    
    <ul id="brandUl"> <strong style="float:left;"> BRANDS </strong> <a href="javascript:void(0);" style="float:right;" onclick="clearBrandCondition();"><strong>X</strong> Reset</a>
    	<form action="<?php echo $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" method="get" id="bFormB">
    	<div id="brandContainer" style="clear:both;">
		<?php $chks = 'checked="checked"';
		$bQ = "SELECT tbl_brand.brand_name, tbl_brand.slug, tbl_brand.brand_id, (
		SELECT COUNT(DISTINCT tp.product_id) AS count FROM tbl_product tp
		LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
		LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
		LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
		LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
		WHERE tpm.media_type = 'img' AND tpm.is_main = 1 AND tp.is_activate = 1 AND tp.brand_id = tbl_brand.brand_id $condi $condiBrand) AS totalProduct
		FROM tbl_brand ORDER BY tbl_brand.brand_name";
		$br_rs = mysql_query($bQ, $con);		
		while($br_row = mysql_fetch_object($br_rs)){ if($br_row->totalProduct > 0){ ?>
            <li>
                <input name="brandFilter[]" type="checkbox" value="<?php echo $br_row->slug; ?>" onchange="sleepBrand();"
                <?php echo (isset($brandArr) && in_array($br_row->slug, $brandArr))?$chks:''; ?> />
                <?php echo $br_row->brand_name.' ('.$br_row->totalProduct.')'; ?>
            </li>
            <?php }} ?>
        </div>
        </form>
    </ul>
</div>