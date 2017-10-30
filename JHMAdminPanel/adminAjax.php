<?php ob_start("ob_gzhandler"); session_start();
include("../include/config.php");
include("../include/function.php");
$chk = 'checked="checked"';
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getSubCategory'){
	echo '<option value=""></option>';
	if(isset($_GET['data1'])){
		$id = $_GET['data1'];
		if(isset($_GET['mul'])){ $condi = "superparent_id IN ($id)"; }
		else{ $condi = "superparent_id = '$id'"; }
		$rs = mysqli_query($con, "SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND $condi ORDER BY category_name");
		while($row = mysqli_fetch_object($rs)){ ?><option value="<?php echo $row->category_id; ?>"><?php echo $row->category_name; ?></option> <?php }
	}
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getSubSubCategory'){
	echo '<option value="0"></option>';
	if(isset($_GET['data1'])){
		$id = $_GET['data1'];
		if(isset($_GET['mul'])){ $condi = "parent_id IN ($id)"; }
		else{ $condi = "parent_id = '$id'"; }
		$rs = mysql_query("SELECT category_id, category_name FROM tbl_category WHERE superparent_id != 0 AND $condi ORDER BY category_name", $con);
		while($row = mysql_fetch_object($rs)){ ?><option value="<?php echo $row->category_id; ?>"><?php echo $row->category_name; ?></option> <?php }
	}
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getCategoryBrand'){
	$id = $_GET['data1'];
	$rs = mysql_query("SELECT * FROM tbl_brand WHERE category_id = '$id' ORDER BY brand_name", $con);
	echo '<option value="">- SELECT BRAND -</option>';
	while($row = mysql_fetch_object($rs)){ echo "<option value='$row->brand_id'>$row->brand_name</option>"; }
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getSubcategorySize'){
	if(isset($_GET['data1']) && $_GET['data1'] != ''){
		$id = $_GET['data1']; $s = 100;
		if(isset($_GET['mul'])){ $condi = "subcategory_id IN ($id)"; }
		else{ $condi = "subcategory_id = '$id'"; }
		$rs = mysql_query("SELECT * FROM tbl_size WHERE $condi GROUP BY size ORDER BY size", $con);
		while($row = mysql_fetch_object($rs)){ ?>
		<div class="switch-button showcase-switch-button sm">
			<input value="<?php echo $row->size; ?>" id="switch-button-<?php echo $s; ?>" name="size[]" type="radio" > <!-- checkbox -->
			<label for="switch-button-<?php echo $s; ?>"></label> <?php echo $row->size; ?>
		</div> &nbsp; &nbsp; <?php $s++; }
	}
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getSubcategoryEditSize'){
	if(isset($_GET['data1']) && $_GET['data1'] != ''){
		$id = $_GET['data1']; $sizeData = $_GET['data2']; $size = explode(',', $sizeData); $s = 100;
		if(isset($_GET['mul'])){ $condi = "subcategory_id IN ($id)"; }
		else{ $condi = "subcategory_id = '$id'"; }
		$rs = mysql_query("SELECT * FROM tbl_size WHERE $condi GROUP BY size ORDER BY size", $con);
		while($row = mysql_fetch_object($rs)){ ?>
		<div class="switch-button showcase-switch-button sm">
			<input value="<?php echo $row->size; ?>" id="switch-button-<?php echo $s; ?>" name="size[]" type="radio" <?php echo (in_array($row->size, $size))?$chk:''; ?> > <!-- checkbox -->
			<label for="switch-button-<?php echo $s; ?>"></label> <?php echo $row->size; ?>
		</div> &nbsp; &nbsp; <?php $s++; }
	}
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getProductOnPromotion'){
	if(isset($_GET['data1']) && $_GET['data1'] != ''){
		$id = $_GET['data1'];
		$categoryId = $id;
		
		if($categoryId != ''){
			$catQ = "SELECT category_id FROM `tbl_category` WHERE parent_id IN ($categoryId) OR superparent_id IN ($categoryId)";
			$catRs = exec_query($catQ, $con);
			$catArray = array();
			while($catRow = mysql_fetch_object($catRs)){ $catArray[] = $catRow->category_id; }
			$categories = implode(',', $catArray);
			$categories = ($categories == '')? $categoryId : $categories.','.$categoryId ;
		}
		
		//$condi = "tbl_product_category.category_id IN ($categories)";
		
		if(isset($_GET['mul'])){ $condi = "tbl_product_category.category_id IN ($categories)"; }
		else{ $condi = "tbl_product_category.category_id = '$categories'"; }
		
		$q = "SELECT tbl_product.product_name, tbl_brand.brand_name, tbl_product.product_id FROM tbl_product LEFT JOIN tbl_product_category ON tbl_product_category.product_id = tbl_product.product_id LEFT JOIN tbl_brand ON tbl_brand.brand_id = tbl_product.brand_id
		WHERE tbl_product.is_activate = 1 AND $condi GROUP BY tbl_product.product_id ORDER BY tbl_product.product_name";
		$rs = mysql_query($q, $con);
		while($row = mysql_fetch_object($rs)){ ?> <option value="<?php echo $row->product_id; ?>" ><?php echo $row->brand_name.' '.$row->product_name; ?></option> <?php }
	}
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getColorfromBrand'){
	$bid = $_GET['data1']; $i = 1;
	//$slug = getBrand(array('slug'), $bid, $con)->slug;
	$col_rs = exec_query("SELECT * FROM tbl_color WHERE brand = '$bid' ORDER BY color", $con);
	while($col_row = mysqli_fetch_object($col_rs)){ ?>
		<input id="color<?php echo $i; ?>" type="checkbox" value="<?php echo $col_row->color_code; ?>" name="color[]">
		<label for="color<?php echo $i; ?>" style="background-color:<?php echo $col_row->color_code; ?>" class="tooltip-btn" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo $col_row->color; ?>"><span class="fa fa-check">&nbsp;</span></label>
	<?php $i++; }
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getSubCategoryOnCategory'){
	$id = $_GET['data1'];
	$rs = mysql_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = '$id' ORDER BY category_name", $con);
	while($row = mysql_fetch_object($rs)){ ?> <option value="<?php echo $row->category_id; ?>"><?php echo $row->category_name; ?></option> <?php }
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getPromotionDateTime'){
	$id = $_GET['data1'];
	$row = mysql_fetch_object(mysql_query("SELECT start_date, end_date FROM tbl_promotion WHERE promo_id = '$id'", $con));
	$fromArr = explode(' ', $row->start_date); $toArr = explode(' ', $row->end_date);
	$fromDate = $fromArr[0]; $toDate = $toArr[0];
	$fromTime = substr($fromArr[1], 0, 5); $toTime = substr($toArr[1], 0, 5);
	echo json_encode(array('fDate' => $fromDate, 'tDate' => $toDate, 'fTime' => $fromTime, 'tTime' => $toTime));
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'chkProductFields'){
	$value = $_GET['data1'];
	$column = 'product_'.strtolower($_GET['data2']);
	if(isset($_GET['type']) && $_GET['type'] != ''){
		$pid = $_GET['pid'];
		$rs = mysql_query("SELECT product_id FROM tbl_product WHERE $column = '$value' AND $column != '' AND product_id != '$pid' AND is_activate != 4", $con);
	}
	else{
		$rs = mysql_query("SELECT product_id FROM tbl_product WHERE $column = '$value' AND $column != '' AND is_activate != 4", $con);
	}
	if(mysql_num_rows($rs) >= 1){ echo false; }
	else{ echo true; }
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'moveSearchTagToAdmin'){
	$id = $_GET['data1'];
	$row = mysql_fetch_object(mysql_query("SELECT * FROM tbl_search_history WHERE recid = '$id'", $con));
	$tag = addslashes($row->keyword);
	mysql_query("INSERT INTO tbl_search_admin(keyword, date) VALUES('$tag' ,'".date('c')."')", $con);
	mysql_query("DELETE FROM tbl_search_history WHERE recid = '$id'", $con);
	echo 'Tag Successfully Moved!!!';
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'setSearchTagOrder'){
	$recid = explode(',', $_GET['data1']);
	$order = explode(',', $_GET['data2']);
	
	foreach($recid AS $key => $id){
		$rs = exec_query("UPDATE tbl_search_admin SET order_no = '".$order[$key]."' WHERE recid = '$id'", $con);
	}
	if($rs){ echo 'Search Tag Successfully Ordered'; }
	else{ echo 'Some Error Occured, Try Again..'; }
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getNewsletterEmailIds'){
	$email = $_GET['email'];
	$ii = 1;
	$rs = exec_query("SELECT email FROM tbl_user_newsletter WHERE email LIKE '%".$email."%'", $con);
	if(mysql_num_rows($rs) > 0){
		echo '<tr>';
		while($row = mysql_fetch_object($rs)){ ?>
			<td class="tdd">
				<input type="checkbox" name="email[]" value="<?php echo $row->email; ?>" />
				<?php echo $row->email; ?>
			</td>
		<?php echo (($ii % 4)==0)?'</tr><tr>':''; $ii++; }
		echo '</tr>';
	}
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'chkUPC'){
	$upc = $_GET['upc1'];
	$rs = exec_query("SELECT recid FROM `tbl_product_price` WHERE `product_upc` = '$upc'", $con);
	if(mysql_num_rows($rs)){
		echo 1;
	}
	else{
		echo 0;
	}
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getSku'){
	$sku = $_GET['data1'];
	$skuData = '';
	// $skuRs = exec_query("SELECT product_sku FROM `tbl_product` WHERE `product_sku` LIKE '".$sku."%' AND product_sku != '' LIMIT 0, 7", $con);
	$skuRs = exec_query("SELECT product_sku FROM `tbl_product` WHERE `product_sku` LIKE '".$sku."%' AND is_activate != 4 AND product_sku != '' order by Cast(REPLACE(lower(product_sku), '".$sku."','') as int) DESC LIMIT 0,1", $con);
	if(@mysqli_num_rows($skuRs)){
		$i = 1;
		while($row = mysqli_fetch_object($skuRs)){
			$sku1 = $row->product_sku;
			$skuNew = getNchkSKU($sku1, $con);
			if($skuNew != ''){
				$skuData .= '<div style="cursor:pointer; background:#efefef; margin:3px; font-size:12px; padding: 4px;" onclick="setSku('."'".$skuNew."'".')">'.$skuNew.'</div>';
				$i++;
			}
			if($i == 6){ break; }
		}
		echo $skuData;
	}
	else{ return 0; }
}
?>