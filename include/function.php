<?php global $permissionArray;
$permissionArray = array(
	'manager'	=> 'Manager & Moderators',
	'user'		=> 'Customer',
	'brand'		=> 'Brand',
	'category'	=> 'Category',
	'product'	=> 'Product',
	'promotion' => 'Promotion',
	'review'	=> 'Review & Rating',
	
	'sysConfig'		=> 'System Config',
	
	'cms'		=> 'CMS',
	'tax'		=> 'Tax',
	'size'		=> 'Size',
	'config'	=> 'Configuration',
	'tag'		=> 'Tag',
	'stag'		=> 'Search Tag',
	'emailtemp'	=> 'Email Template',
	'newsletter'=> 'Newsletter'
);

function tres($text,$con){ return trim(mysqli_real_escape_string($con,$text)); }

function redirect($location){ echo '<script>window.location.href="'.$location.'"</script>'; }

function setMessage($message, $type){ $_SESSION['message'] = "<div id='alert_message_div' class='".$type."'>".$message."</div>"; }

function getMessage(){
	if(isset($_SESSION['message'])){
		$message = @$_SESSION['message'];
		unset($_SESSION['message']);
		echo $message;
		?> <script> //setTimeout(function(){ document.getElementById('alert_message_div').style.transform = 'rotateY(-90deg)'; }, 3000); </script> <?php
	}
}

function chkParam($var, $redirect){ if(!isset($var) || $var == '' || empty($var)){ redirect($redirect); die(); } }

function getSelected($val1, $val2){ echo ($val1 == $val2)?'selected="selected"':''; }
function getChecked($val1, $val2){ echo ($val1 == $val2)?'checked="checked"':''; }
function chkCategoryLevel($id, $con){
	$catData = getCategory(array('parent_id', 'superparent_id'), $id, $con);
	if(isset($catData->superparent_id) && isset($catData->parent_id)){
		if($catData->superparent_id == 0 && $catData->parent_id == 0){ return 'cat'; }
		elseif($catData->superparent_id != 0 && $catData->parent_id == 0){ return 'subCat'; }
		elseif($catData->superparent_id != 0 && $catData->parent_id != 0){ return 'subSubCat'; }
	}
	else{
		return '';
	}
}

/* DB related functions start -------------------------------------------------------------------------------------- */
function exec_query($query, $con){ return mysqli_query($con, $query); }

function delete_data($table, $column, $data, $con){	mysqli_query($con, "DELETE FROM $table WHERE $column = '$data'"); }
/* DB functions end ------------------------------------------------------------------------------------------------ */

/* site related function start ------------------------------------------------------------------------------------- */
function getCategory($colArr, $id, $con){
	$col = implode(',',$colArr);
	return mysqli_fetch_object(mysqli_query($con, "SELECT $col FROM tbl_category WHERE category_id = '$id'"));
}
function getCategoryImg($id, $con){	return mysqli_query($con, "SELECT * FROM tbl_category_media WHERE category_id = '$id'"); }

function getBrand($colArr, $id, $con){
	$col = implode(',',$colArr);
	return mysqli_fetch_object(mysqli_query($con, "SELECT $col FROM tbl_brand WHERE brand_id = '$id'"));
}

function getProduct($id, $con){
	$query = "SELECT tbl_product.*, tbl_brand.brand_name FROM tbl_product
	LEFT JOIN tbl_brand ON tbl_brand.brand_id = tbl_product.brand_id WHERE tbl_product.product_id = '$id'";
	return mysqli_fetch_object(mysqli_query($con, $query));
}

function getProductStepImg($id, $con){	return mysqli_query($con, "SELECT * FROM tbl_product_usage WHERE product_id = '$id'"); }

function getColor($id, $con){ return mysqli_fetch_object(mysqli_query($con, "SELECT * FROM tbl_color WHERE (color_id = '$id') OR (color_code = '$id')")); }

function getSize($id, $con){ return mysqli_fetch_object(mysqli_query($con, "SELECT * FROM tbl_size WHERE size_id = '$id'")); }
/* site related function end --------------------------------------------------------------------------------------- */

/* get mail content start ------------------------------------------------------------------------------------------ */
function get_mail_content($param, $heading){
	if($param == 'header'){
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;min-height:100%;width:100%!important" marginwidth="0" marginheight="0">

 <table width="99%" bgcolor="#455560" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;width:100%">
	<tbody><tr style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
		<td style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"></td>
		<td align="" style="margin:0 auto!important;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;display:block!important;max-width:600px!important;clear:both!important">
			<div style="margin:0 auto;padding:15px;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;max-width:600px;display:block">
				  <table width="600" cellspacing="0" cellpadding="0" border="0" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;width:100%">
                                        <tbody><tr style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">

                                            <td width="416" valign="top" align="left" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;font-size:12px;color:#ffffff"> '.siteName.' </td>
                                            <td width="184" valign="top" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;font-size:12px;color:#ffffff"></td>

                                        </tr>
                                    </tbody></table> 
			</div>
			
		</td>
		<td style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"></td>
	</tr>
</tbody></table>

<table width="100%" bgcolor="" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;width:100%;background-color:#f4f4f4;padding-top:20px">
	<tbody><tr style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
	  <td style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"></td>
	  <td bgcolor="#F4F4F4" align="" style="margin:0 auto!important;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;display:block!important;max-width:600px!important;clear:both!important"><div align="center" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
	  <img border="0" style="margin:0;padding:0;padding-top:25px;padding-bottom:25px;color:#666666;font-size:14px;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;max-width:100%" alt="'.siteName.'" src="'.siteUrl.'images/Logo_03.png"></div>	    </td>
	  <td style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"></td>
  </tr>
	<tr style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
		<td style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"></td>
		<td bgcolor="#FFFFFF" align="" style="margin:0 auto!important;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;display:block!important;max-width:600px!important;clear:both!important">
			
			<div style="margin:0 auto;padding:15px;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;max-width:600px;display:block">
				<table style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;width:100%">
					<tbody><tr style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
						<td style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"><h1 align="center" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;line-height:1.1;font-weight:300;font-size:28px;color:#13A3A4;margin-bottom:15px"><span style="font-size:32px;margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">'.$heading.'</span></h1>';
	}
	elseif($param == 'footer'){
		return '</td>
					</tr>
				</tbody></table>
			</div>
		<br style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
			
		</td>
		<td style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"></td>
	</tr>
	<tr style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
	  <td style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"></td>
	  <td align="" style="margin:0 auto!important;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;display:block!important;max-width:600px!important;clear:both!important">&nbsp;</td>
	  <td style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"></td>
  </tr>
</tbody></table>

<table style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;width:100%;background-color:#13A3A4;clear:both!important">
	<tbody><tr style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
		<td style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"></td>
		<td style="margin:0 auto!important;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;display:block!important;max-width:600px!important;clear:both!important">
			
				
				<div style="margin:0 auto;padding:15px;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;max-width:600px;display:block">
					<table style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;width:100%">
						<tbody><tr style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
                                                        <td valign="top" style="padding-top:20px;margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;color:#4b4b4b;font-size:12px;line-height:150%;text-align:center"><table width="237" align="center" cellspacing="0" cellpadding="0" border="0" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;width:100%" summary="">
              <tbody><tr style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
                <td width="32" align="center" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
                  <a target="_blank" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;color:#4b4b4b;font-weight:bold;text-decoration:underline" title="Twitter" href="#"><img width="49" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;max-width:100%" alt="Twitter" src="'.siteUrl.'/images/fb.png"></a>
                </td>
                <td width="32" align="center" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"><a target="_blank" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;color:#4b4b4b;font-weight:bold;text-decoration:underline" href="#"><img width="49" height="49" style="color:#666666;font-size:8px;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;margin:0;padding:0;max-width:100%" alt="Twitter" src="'.siteUrl.'/images/twit.png"></a></td>
                
				<td width="32" align="center" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
                  <a target="_blank" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;color:#4b4b4b;font-weight:bold;text-decoration:underline" title="Facebook" href="#"><img width="49" alt="YouTube" style="color:#666666;font-size:8px;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;margin:0;padding:0;max-width:100%" src="'.siteUrl.'/images/utube.png"></a>
                </td>
                <td width="32" align="center" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"><a target="_blank" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;color:#4b4b4b;font-weight:bold;text-decoration:underline" href="#"><img width="49" height="49" alt="Blog" style="color:#666666;font-size:8px;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;margin:0;padding:0;max-width:100%" src="'.siteUrl.'/images/blog.png"></a></td>
              </tr>
            </tbody></table></td>
                                                    </tr>
					</tbody></table>
				</div>
				
		</td>
		<td style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"></td>
	</tr>
</tbody></table>



<table style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;width:100%;background-color:#f4f4f4;clear:both!important">
	<tbody><tr style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
		<td style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"></td>
		<td style="margin:0 auto!important;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;display:block!important;max-width:600px!important;clear:both!important">
			
				
				<div style="margin:0 auto;padding:15px;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;max-width:600px;display:block">
					<table style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;width:100%">
						<tbody><tr style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
                                                        <td valign="top" style="padding-top:20px;margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;color:#4b4b4b;font-size:12px;line-height:150%;text-align:center"><em style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">Copyright &copy; 2014 '.siteName.', All rights reserved.</em><br style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">

<div><span>'.siteName.'</span></div>
</td>
                                                    </tr>
					</tbody></table>
				</div>
				
		</td>
		<td style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"></td>
	</tr>
</tbody></table>

</div>';
	}
	elseif($param == 'mail_header'){
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= "From: listings@myminicrib.co.uk" . "\r\n";
		return $headers;
	}
}
/* get mail content ends here */

function chkPermission($permission, $con){
	$array = array();
    $admintype = mysqli_fetch_object(mysqli_query($con,"SELECT atype FROM admin WHERE recid = '".$_SESSION['admin']."'"))->atype;
    /*if($admintype == 1){
        if(mysqli_num_rows(mysql_query("SELECT recid FROM tbl_permission WHERE user_id = '".$_SESSION['admin']."' AND permission = '$permission'", $con))){ return true; }else{ return false; }
    }else{ return true; }*/
	if($admintype == 1){
		$rss = mysqli_query($con,"SELECT * FROM tbl_permission WHERE user_id = '".$_SESSION['admin']."' AND permission = '$permission'");
        if(mysqli_num_rows($rss)){
			$row = mysqli_fetch_object($rss);
			$array[0] = true;
			$array['add'] = $row->add;
			$array['edit'] = $row->edit;
			$array['read'] = $row->read;
			$array['status'] = $row->status;
		}
		else{
			$array[0] = false;
		}
    }
	else{
		$array[0] = true;
		$array['add'] = 1;
		$array['edit'] = 1;
		$array['read'] = 1;
		$array['status'] = 1;
	}
	return $array;
}

/* for get promotion of promo code on cart page COPIED FROM function.php */
function getPromoCodeForCartProduct($product_id, $brand_id, $all_cat, $con){
	$cdate = date('Y-m-d H:i:s');
	$promoArr = array(); $qCat = ''; $qBr = '';
	$catArr = explode(',', $all_cat);
	if(!empty($catArr)){
		foreach($catArr AS $cat){
			if($cat != ''){
				/* initialize variable for query */
				$qCat .= ($qCat == '')?' AND (':' OR ';
				$qBr .= ($qBr == '')?' AND (':' OR ';
	
				/* for chk parent cat promotion */
				$level = chkCategoryLevel($cat, $con);
				if($level == 'subCat'){
					$mainCat = getCategory(array('superparent_id'), $cat, $con); /* sub cats parent is main Cat (superparent_id) */
					$qCat .= " FIND_IN_SET('$mainCat->superparent_id', promo.ids) > 0 OR ";
					$qBr .= " FIND_IN_SET('$mainCat->superparent_id', promo.category_id) > 0 OR ";
				}
				elseif($level == 'subSubCat'){
					$subSubCat = getCategory(array('parent_id'), $cat, $con); /* sub sub cats parent is sub Cat (parent_id) */
					$subcat = getCategory(array('superparent_id'), $subSubCat->parent_id, $con);  /* sub cats parent is main Cat (superparent_id) */
					$qCat .= " FIND_IN_SET('$subSubCat->parent_id', promo.ids) > 0 OR FIND_IN_SET('$subcat->superparent_id', promo.ids) > 0 OR ";
					$qBr .= " FIND_IN_SET('$subSubCat->parent_id', promo.category_id) > 0 OR FIND_IN_SET('$subcat->superparent_id', promo.category_id) > 0 OR ";
				}
				/* for product category promotion - default */
				$qCat .= " FIND_IN_SET('$cat', promo.ids) > 0 ";
				$qBr .= " FIND_IN_SET('$cat', promo.category_id) > 0 ";
			}
		}
	}
	$qCat .= ($qCat == '')?'':')';
	$qAlias = "SELECT promo_temp.promo_id, promo_temp.promo_value, promo_temp.percent_or_amount FROM ( ";
	
	$qCommon = "(SELECT promo.recid AS promo_id, promo.promo_value, promo.percent_or_amount FROM tbl_promo_code promo WHERE
	(DATE_FORMAT(promo.start_date, '%Y-%m-%d %H:%i:%s') <= '$cdate' AND DATE_FORMAT(promo.end_date, '%Y-%m-%d %H:%i:%s') >= '$cdate') AND";
	
	$query = "$qCommon (promo.promo_type = 'allBrand' AND FIND_IN_SET('$brand_id', promo.ids) > 0 ))
	UNION
	$qCommon (promo.promo_type = 'parPro' AND FIND_IN_SET('$product_id', promo.ids) > 0 ))";
	
	$query .= ($qCat == '')?'':" UNION
	$qCommon (promo.promo_type = 'allCat' $qCat ))
	UNION
	$qCommon (promo.promo_type = 'allBrand' AND FIND_IN_SET('$brand_id', promo.ids) > 0 ) $qBr ))";
	$query = $qAlias.$query;
	$query .= " ) AS promo_temp ORDER BY promo_temp.percent_or_amount DESC, promo_temp.promo_value DESC";
	$rs = exec_query($query, $con);
	$promoId = ''; $promoType = '';	$promoValue = '';
	$numPromo = mysqli_num_rows($rs);
	/*$dataReturn = '';*/
	$dataReturn = array();
	if($numPromo > 0){
		$promo = mysqli_fetch_object($rs);
		/* insert a symbol separated string in an array at index of product id - promocodeId | type(%/$) | how much value */
		$dataReturn[0] = $promo->promo_id;
		$dataReturn[1] = $promo->percent_or_amount;
		$dataReturn[2] = $promo->promo_value;
	}
	return $dataReturn;
}
function getOrderId($orderId,$con){
	$row = mysqli_fetch_object(mysqli_query($con, "SELECT od_date FROM tbl_order WHERE order_id = '$orderId'"));
	$date = date('my', strtotime($row->od_date));
	$oid = 'JHM'.$date.$orderId;
	return $oid;
}
function getNchkSKU($sku, $con){
	$arr = preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $sku);
	//print_r($arr); echo '<br/>';
	//------------
	$arrLen = count($arr);
	$lastSubStr = $arr[$arrLen - 1]; //echo '<br/>';
	if(is_numeric($lastSubStr)){
		$length = strlen($lastSubStr);
		$num = $length-1;
		$output[0] = substr($lastSubStr, 0, $num);
		$output[1] = substr($lastSubStr, $num, $length);
		
		$arr[count($arr)-1] = $output[0];
		$arr[count($arr)] = $output[1]+1;
	}
	else{
		$arr[count($arr)-1] = $lastSubStr.rand(1, 9);
	}
	$skuNew = implode('', $arr);
	//------------
	$skuChkRs = exec_query("SELECT product_id FROM `tbl_product` WHERE `product_sku` LIKE '".$skuNew."%'", $con);
	if(mysqli_num_rows($skuChkRs)){
		getNchkSKU($skuNew, $con);
	}
	else{
		return $skuNew;
	}
}
function getStats($type, $pastDate, $currDate, $con){
	if($type == 'order'){
		$statsQ = "SELECT COUNT(order_id) AS count FROM tbl_order WHERE DATE_FORMAT(od_date,'%Y-%m-%d') >= '$pastDate' AND DATE_FORMAT(od_date,'%Y-%m-%d') <= '$currDate' AND payment_status = 'Paid'";
	}elseif($type == 'user'){
		$statsQ = "SELECT COUNT(user_id) AS count FROM tbl_user WHERE DATE_FORMAT(register_on,'%Y-%m-%d') >= '$pastDate' AND DATE_FORMAT(register_on,'%Y-%m-%d') <= '$currDate'";
	}elseif($type == 'product'){
		$statsQ = "SELECT COUNT(product_id) AS count FROM tbl_product WHERE DATE_FORMAT(created_on,'%Y-%m-%d') >= '$pastDate' AND DATE_FORMAT(created_on,'%Y-%m-%d') <= '$currDate'";
	}
	$statsRs = exec_query($statsQ, $con);
	$statsRow = mysqli_fetch_object($statsRs);
	$statsData = (isset($statsRow->count) && $statsRow->count != '')?$statsRow->count:0;
	return $statsData;
}
function getStatsData($type, $loopPastDate, $con){
	if($type == 'order'){
		$graphQ = "SELECT COUNT(order_id) AS count FROM tbl_order WHERE DATE_FORMAT(od_date,'%Y-%m-%d') = '$loopPastDate' AND payment_status = 'Paid'";
	}elseif($type == 'user'){
		$graphQ = "SELECT COUNT(user_id) AS count FROM tbl_user WHERE DATE_FORMAT(register_on,'%Y-%m-%d') = '$loopPastDate'";
	}elseif($type == 'product'){
		$graphQ = "SELECT COUNT(product_id) AS count FROM tbl_product WHERE DATE_FORMAT(created_on,'%Y-%m-%d') = '$loopPastDate'";
	}
	$graphRs = exec_query($graphQ, $con);
	$graphRow = mysqli_fetch_object($graphRs);
	$graphData = (isset($graphRow->count) && $graphRow->count != '')?$graphRow->count:0;
	return $graphData;
}
function formatCurrency($no){ //return round((float)$no, 2);
	if (strpos($no, ".") === FALSE ){ $return = $no.'.00'; }
	else{ $noArr = explode('.', $no); $no1 = $noArr[0]; $no2 = substr($noArr[1], 0, 2);	$return = $no1.'.'.$no2; }
	return $return;
}
function getGst(){
	$gstRs = mysqli_query($con, "SELECT tax_percent FROM tbl_tax WHERE tax_name = 'GST'");
	$gstRow = mysqli_fetch_object($gstRs);
	if(mysqli_num_rows($gstRs) && isset($gstRow->tax_percent) && $gstRow->tax_percent > 0){ $gst = $gstRow->tax_percent; }
	else{ $gst = 15; }
	return $gst;
}
/* function for chk sll login, means secure */
/*function ssl($securepage){
	if ($_SERVER['HTTPS'] == 'on') {
        // we are on a secure page.
        if (!$securepage) {
        	// but we shouldn't be!
          	$url = str_replace('/', '', siteUrl).$_SERVER['REQUEST_URI'];
          	header('location: '.$url); exit;
        }
  	} else {
        // we aren't on a secure page.
        if ($securepage) {
        	// but we should be!
          	$url = str_replace('/', '', siteSecureUrl).$_SERVER['REQUEST_URI'];
          	header('location: '.$url); exit;
        }
  	}
}*/
?>