<!-- js function for filter searching start -->
<?php $maxRange = 1; $minRange = 11000;
$proPriceRs = exec_query("SELECT MAX(product_price) AS maxRange, MIN(product_price) AS minRange FROM tbl_product_price", $con);
if(mysql_num_rows($proPriceRs)){ $proPrice = mysql_fetch_object($proPriceRs); $maxRange = $proPrice->maxRange; $minRange = $proPrice->minRange; } ?>
<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>-->
<script>
function setValues(id, value){
	document.getElementById(id).value = value;
	ajaxCall('abc');
}
function setMultiChkValues(id){ var obj = '';
	$('#'+id+' input[type=checkbox]:checked').each(function() {
		if(this.value != ''){
			if (obj == ''){ obj = this.value; }
			else{ obj = obj+','+this.value; }
		}
	});
	if(obj != ''){ document.getElementById(id+"Id").value = obj; }
	else{ document.getElementById(id+"Id").value = ''; }
	ajaxCall('abc');
}

function setMultiChkValuesBrand(id){ var obj = '';
	$('#'+id+' input[type=checkbox]:checked').each(function() {
		if(this.value != ''){
			if (obj == ''){ obj = this.value; }
			else{ obj = obj+','+this.value; }
		}
	});
	if(obj != ''){ document.getElementById(id+"Id").value = obj; }
	else{ document.getElementById(id+"Id").value = ''; }
	ajaxCall('brand');
}

function setGenderValues(gen, catId, catName){
	getSubCategoryOnCategoryPage(catId, catName, gen);
	document.getElementById('genderId').value = gen;
	document.getElementById('categoryId').value = catId;
	ajaxCall('abc');
}
/* price range slider */
$(function() {
	$("#slider-range").slider({ range: true, min: <?php echo $minRange; ?>, max: <?php echo $maxRange; ?>, values: [<?php echo $minRange.', '.$maxRange; ?>],
		slide: function(event, ui) {
			$("#priceId").val(ui.values[0]+"-"+ui.values[1]);
			amount1 = format(ui.values[0]);
			amount2 = format(ui.values[1]);
			$("#price_field").text(amount1+" - "+amount2);
		}
	});
	$("#priceId").val($("#slider-range").slider("values", 0)+"-"+$("#slider-range").slider("values", 1));
	amount = format($("#slider-range").slider("values", 1));
	$("#price_field").text($("#slider-range").slider("values", 0)+" - "+amount);
});
function format(n){
    return n.toFixed(2).replace(/./g, function(c, i, a){ return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c; });
}
function ajaxCall(call){
	$('.animation_image').show();
	categoryId = document.getElementById('categoryId').value;
	brandId = document.getElementById('brandId').value;
	
	colorId = ''; //colorId = document.getElementById('colorId').value;
	
	genderId = document.getElementById('genderId').value;
	priceId = document.getElementById('priceId').value;
	availId = document.getElementById('availId').value;
	discountId = document.getElementById('discountId').value;
	sortId = document.getElementById('sortId').value;
	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			document.getElementById("putProduct").innerHTML = xmlhttp.responseText;
			$('#totalProductSidebar, #totalProductHeading').text('('+document.getElementById('countAll').value+' Items)');
			total = document.getElementById('autoLoadTotalGroup').value;
			heading = document.getElementById('headingAjax').value;
			$('#categoryTotalGroups').val(total);
			 
			heading1 = unescape(heading);
			callAutoLoad();
			if(heading != ''){ $('#productHeading').html(heading1); }
			//if(call != 'brand'){
				getFilterBrandChecked(brandId);
				//getFilterBrand();
			//}
			//$(function(){ $("img.lazy").lazyload({ effect : "fadeIn" }); });
		}
	}
	path="&categoryId="+categoryId+"&brandId="+brandId+"&genderId="+genderId+"&priceId="+priceId+"&availId="+availId+"&sortId="+sortId+"&discountId="+discountId+"&colorId="+colorId;
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajaxFilter.php?action=getFilter&dataTempId=v1qv9a1512ded0ac31dzldy1571.cloud.uk"+path, true);
	xmlhttp.send();
}
function getFilterBrand(){
	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			document.getElementById("brandContainer").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=getFilterBrand&dataTempId=v1qv958sbdd31dzl5ee2y1571.cloud.uk", true);
	xmlhttp.send();
}
function getFilterColor(){
	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			document.getElementById("colorContainer").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=getFilterColor&dataTempId=v1qv958sbdd31dzl5ee2y1571.cloud.uk", true);
	xmlhttp.send();
}


function getFilterBrandChecked(ids){
	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			document.getElementById("brandContainer").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=getFilterBrand&ids="+ids+"&dataTempId=v1qv958sbdd31dzl5ee2y1571.cloud.uk", true);
	xmlhttp.send();
}


function callAutoLoad(){
	$('.animation_image').show();
	//var track_load = 0; //total loaded record group(s)
	var loading  = false; //to prevents multipal ajax loads
	//var total_groups = <?php //echo $total_groups; ?>; //total record group(s)
	
	$.get('<?php echo siteUrl; ?>autoload_process.php', {'group_no':'group_no'}, function(data){
		$('.animation_image').hide();
		document.getElementById('putProduct').innerHTML = data;
		//track_load++;
	});
}
	/*$('#putProduct').load("<?php echo siteUrl; ?>autoload_process.php", {'group_no':track_load}, function() {track_load++;}); //load first group
	$(window).scroll(function() { //detect page scroll
		if($(window).scrollTop() + $(window).height() == $(document).height()){  //user scrolled to bottom of the page?
			if(track_load <= total_groups && loading==false){ //there's more data to load
				loading = true; //prevent further ajax loading
				$('.animation_image').show(); //show loading image
				//load data from the server using a HTTP POST request
				$.post('<?php //echo siteUrl; ?>autoload_process.php',{'group_no': track_load}, function(data){
					$("#putProduct").append(data); //append received data into the element
					//hide loading image
					$('.animation_image').hide(); //hide loading image once data is received
					track_load++; //loaded group increment
					loading = false;
				}).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
					alert(thrownError); //alert with HTTP error
					$('.animation_image').hide(); //hide loading image
					loading = false;
				});
			}
		}
	});
}*/

function clearBrandCondition(){	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			document.getElementById("brandId").value = 0;
			ajaxCall('abc');
			document.getElementById("brandContainer").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=clearBrandCondition&dataTempId=v1qv958dbdd3ddzlsee2yj531.cloud.uk", true);
	xmlhttp.send();
}




</script>
<!-- js function for filter searching end -->
<div id="filter" >
	<h5> FILTER PRODUCTS BY </h5>
    
    <ul id="putCatHere"> <strong> ALL CATEGORIES </strong>
		<?php $cat_rs = exec_query("SELECT category_name, category_id, slug FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0", $con);
		while($cat_row = mysql_fetch_object($cat_rs)){ ?>
		<li>
        <?php /* href="<?php echo siteUrl; ?><?php echo (isset($_GET['gender']) && $_GET['gender'] != '')? $gender.'s' : 'all'; ?>/1/<?php echo $cat_row->slug; ?>/" */ ?>
        	<a href="javascript:void(0);" onclick="getMaleFemaleOnCategoryPage(<?php echo $cat_row->category_id.",'".$cat_row->category_name."'";?>)"><?php echo $cat_row->category_name; ?></a>
        </li>
		<?php } ?>
    </ul>
	<input type="hidden" id="genderId" value="<?php echo (isset($_GET['gender']) && $_GET['gender'] != '')? $gender : ''; ?>" />
    
    <?php
	$catForAjax = '';
	if(isset($_GET['subSubCat']) && $_GET['subSubCat'] != ''){
		$catForAjax = getCategoryFromSlug(array('category_id'), $_GET['subSubCat'], $con)->category_id;
	}
    elseif(isset($_GET['sub']) && $_GET['sub'] != ''){
		$catForAjax = getCategoryFromSlug(array('category_id'), $_GET['sub'], $con)->category_id;
	}
	elseif(isset($_GET['category']) && $_GET['category'] != ''){
		$catForAjax = getCategoryFromSlug(array('category_id'), $_GET['category'], $con)->category_id;
	}
	?>
    
    <input type="hidden" id="categoryId" value="<?php echo (isset($catForAjax) && $catForAjax != '')? $catForAjax : 0; ?>" />
    
	<?php /*<input type="hidden" id="categoryId" value="<?php echo (isset($_GET['category']) && $_GET['category'] != '')? $catForSideBar : 0; ?>" />*/ ?>
    
    <ul id="priceUl"> 
		<li style="text-align:center; display:none;"><div id="totalProductSidebar"> (<?php echo $totalProducts.' Items'; ?>) </div></li>
		<li> <div id="slider-range" onclick="ajaxCall('abc');"></div> </li>
		<li style="text-align:center;">$ <span id="price_field"></span> </li>
		<input type="hidden" id="priceId" value="0" />
    </ul>
    
    
    <ul id="brand"> <strong style="float:left;"> BRANDS </strong> <a href="javascript:void(0);" style="float:right;" onclick="clearBrandCondition();"><strong>X</strong> Reset</a>
    	<div id="brandContainer" style="clear:both;">
		<?php
		$bQ = "SELECT tbl_brand.brand_name, tbl_brand.brand_id, (
		SELECT COUNT(DISTINCT tp.product_id) AS count FROM tbl_product tp
		LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
		LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
		LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
		LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
		WHERE tpm.media_type = 'img' AND tpm.is_main = 1 AND tp.is_activate = 1 AND tp.brand_id = tbl_brand.brand_id $condi) AS totalProduct
		FROM tbl_brand ORDER BY tbl_brand.brand_name";
			
		$br_rs = mysql_query($bQ, $con);
		while($br_row = mysql_fetch_object($br_rs)){
			if($br_row->totalProduct != 0){	$countBPro = $br_row->totalProduct; ?>
            <li>
                <input name="brand" type="checkbox" value="<?php echo $br_row->brand_id; ?>" onchange="setMultiChkValuesBrand('brand');" />
                <?php echo $br_row->brand_name.' ('.$countBPro.')'; ?>
            </li>
            <?php }} ?>
            <?php //echo ($countBPro==0)?'disabled':''; ?>
        </div>
		<input type="hidden" id="brandId" value="<?php echo (isset($_GET['searchByBrand']) && $_GET['searchByBrand'] != '')?$_GET['searchByBrand']:0;?>" />
    </ul>
    
    
	<?php /* ------------------------- color not in used ryt now -------------------------------- */ ?>
    <ul id="color" style="display:none;"> <strong> COLOR </strong>
    	<div id="colorContainer">
		<?php /*$cl_q = "SELECT (SELECT COUNT(color_id) AS count FROM tbl_product_color WHERE tbl_product_color.color_code = tbl_color.color_code) AS totalProduct, tbl_color.color, tbl_color.color_id, tbl_color.color_code FROM tbl_color LIMIT 0, 7";
		$cl_rs = exec_query($cl_q, $con);
		while($cl_row = mysql_fetch_object($cl_rs)){?>
        <li class="tooltips">
        	<strong><?php echo $cl_row->color.' ('.$cl_row->totalProduct.')'; ?></strong>
			<input name="color" type="checkbox" id="color<?php echo str_replace('#', '@', $cl_row->color_code); ?>" value="'<?php echo str_replace('#', '@', $cl_row->color_code); ?>'" onclick="setMultiChkValues('color');" />
			<label class="colorBlock" for="color<?php echo str_replace('#', '@', $cl_row->color_code); ?>" style="background:<?php echo $cl_row->color_code; ?>;"><span class="tick">&nbsp;</span></label>
			
		</li>
        <?php }*/ ?>
        </div>
		<input type="hidden" id="colorId" value="" />
    </ul>
    <?php /* ------------------------- color not in used ryt now -------------------------------- *//* ?>
    
    <ul> 
		<li> <input name="discount" type="radio" value="" onclick="setValues('discountId', 2);" /> Non-Discounted items </li>
        <li> <input name="discount" type="radio" value="" onclick="setValues('discountId', 1);" /> Discounted items </li>
		<input type="hidden" id="discountId" value="0" />
    </ul>
    
    <ul> 
		<li> <input name="proAvail" type="radio" value="0" onclick="setValues('availId', 0);" /> Available </li>
        <li> <input name="proAvail" type="radio" value="1" onclick="setValues('availId', 1);" /> Out of Stock </li>
		<li> <input name="proAvail" type="radio" value="2" onclick="setValues('availId', 2);" /> Allow Backorder </li>
    </ul>*/ ?>
    <input type="hidden" id="discountId" value="0" />
    <input type="hidden" id="availId" value="" />
    
</div> <!--Filter-->