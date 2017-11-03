<?php /* fun for chk invalid chars in string using in login */
function tres($text, $con){ return trim(mysqli_real_escape_string($con,$text)); }
/* fun for redirect */
function redirect($location){ echo '<script>window.location.href="'.$location.'"</script>'; }
/* funcs for set and get message */
// function setMessage1($message, $type){ $_SESSION['message'] = "<div id='alert_message_div' class='".$type."'><span>&#9658;</span> <span class='alert-message'>".$message."</span></div>"; }
function setMessage($message, $type) {
    $_SESSION['message'] = "<div id='alert_message_div' class='".$type."'>$message</div>'";
//    setTimeout(function() {
//        $(".alert-success").fadeOut();
//    }, 5000);
    
    }

function getMessage(){
	if(isset($_SESSION['message'])){
		$message = @$_SESSION['message'];
		unset($_SESSION['message']);
		echo $message;
	}
}
function getSelected($val1, $val2){ echo ($val1 == $val2)?'selected="selected"':''; }
function getChecked($val1, $val2){ echo ($val1 == $val2)?'checked="checked"':''; }
/* func for chk param anywhere, and if not redirect to some page */
function chkParam($var, $redirect){ if(!isset($var) || $var == '' || empty($var)){ redirect($redirect); die(); } }
/* func for chk param anywhere, and if not return false, or true */
function chkParam1($var){ if($var == '' || empty($var)){ return false; }else{ return true; } }
/* DB related func for executed query */
function exec_query($query, $con){ return mysqli_query($con,$query); }

/* get category function */
function getCategory($colArr, $id, $con){ $col = implode(',',$colArr);
	return mysqli_fetch_object(mysqli_query($con, "SELECT $col FROM tbl_category WHERE category_id = '$id'"));
}
function getCategoryFromSlug($colArr, $slug, $con){ $col = implode(',',$colArr);
	return mysqli_fetch_object(mysqli_query($con, "SELECT $col FROM tbl_category WHERE slug = '$slug'"));
}
function getUser($user_id, $con){
	return mysqli_fetch_object(mysqli_query($con, "SELECT tbl_user.* FROM tbl_user WHERE tbl_user.user_id = '$user_id'"));
	/*return mysql_fetch_object(mysql_query("SELECT tbl_user.*, tbl_country.country_name FROM tbl_user LEFT JOIN tbl_country ON tbl_country.country_id = tbl_user.country_id WHERE tbl_user.user_id = '$user_id'", $con));*/
}
/* for chk n get category level */
function chkCategoryLevel($id, $con){
	$catData = getCategory(array('parent_id', 'superparent_id'), $id, $con);
	if($catData->superparent_id == 0 && $catData->parent_id == 0){ return 'cat'; }
	elseif($catData->superparent_id != 0 && $catData->parent_id == 0){ return 'subCat'; }
	elseif($catData->superparent_id != 0 && $catData->parent_id != 0){ return 'subSubCat'; }
}
/* for get promotion on product page */
function getPromotionForProduct($product_id, $brand_id, $all_cat, $con){
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
					$qCat .= " FIND_IN_SET('$mainCat->superparent_id', promo_det.ids) > 0 OR ";
					$qBr .= " FIND_IN_SET('$mainCat->superparent_id', promo_det.category_id) > 0 OR ";
				}
				elseif($level == 'subSubCat'){
					$subSubCat = getCategory(array('parent_id'), $cat, $con); /* sub sub cats parent is sub Cat (parent_id) */
					$subcat = getCategory(array('superparent_id'), $subSubCat->parent_id, $con);  /* sub cats parent is main Cat (superparent_id) */
					$qCat .= " FIND_IN_SET('$subSubCat->parent_id', promo_det.ids) > 0 OR FIND_IN_SET('$subcat->superparent_id', promo_det.ids) > 0 OR ";
					$qBr .= " FIND_IN_SET('$subSubCat->parent_id', promo_det.category_id) > 0 OR FIND_IN_SET('$subcat->superparent_id', promo_det.category_id) > 0 OR ";
				}
				/* for product category promotion - default */
				$qCat .= " FIND_IN_SET('$cat', promo_det.ids) > 0 ";
				$qBr .= " FIND_IN_SET('$cat', promo_det.category_id) > 0 ";
			}
		}
	}
	$qCat .= ($qCat == '')?'':')';
	$qAlias = 'SELECT promo_temp.promo_id, promo_temp.promo_value, promo_temp.percent_or_amount FROM ( ';
	
	$qCommon = "(SELECT promo.promo_id, promo.promo_value, promo.percent_or_amount FROM tbl_promotion promo
	LEFT JOIN tbl_promotion_detail promo_det ON promo_det.promo_id = promo.promo_id WHERE  `is_publish` = 1 and
	(DATE_FORMAT(promo.start_date, '%Y-%m-%d %H:%i:%s') <= '$cdate' AND DATE_FORMAT(promo.end_date, '%Y-%m-%d %H:%i:%s') >= '$cdate') AND";
	
	$query = "$qCommon (promo.promo_type = 'allBrand' AND FIND_IN_SET('$brand_id', promo_det.ids) > 0 ))
	UNION
	$qCommon (promo.promo_type = 'allPro'))
	UNION
	$qCommon (promo.promo_type = 'parPro' AND FIND_IN_SET('$product_id', promo_det.ids) > 0 ))";
	
	$query .= ($qCat == '')?'':" UNION
	$qCommon (promo.promo_type = 'allCat' $qCat ))
	UNION
	$qCommon (promo.promo_type = 'allBrand' AND FIND_IN_SET('$brand_id', promo_det.ids) > 0 ) $qBr ))";
	$query = $qAlias.$query;
	$query .= ' ) AS promo_temp ORDER BY promo_temp.percent_or_amount DESC, promo_temp.promo_value DESC';
	$rs = exec_query($query, $con);
	 
	$promoId = ''; $promoType = '';	$promoValue = '';
	$numPromo = mysqli_num_rows($rs);
	if($numPromo > 0){
		$promo = mysqli_fetch_object($rs);
		$promoArr['promo_id'] = $promo->promo_id; $promoArr['percent_or_amount'] = $promo->percent_or_amount; $promoArr['promo_value'] = $promo->promo_value;
	}
	return $promoArr;
}
/* for get promotion of promo code on cart page */
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
	
	$qCommon = "(SELECT promo.recid AS promo_id, promo.promo_value, promo.percent_or_amount FROM tbl_promo_code promo WHERE  `is_publish` = 1 and
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
/* check promotion validity for cart product, it return either promotional or normal price --------------------------- */
function checkPromoValidity($pro_row, $con){
	$cdate = date('Y-m-d H:i:s');
	$qqq = "SELECT promo_id FROM tbl_promotion WHERE promo_id = $pro_row->promo_id AND (DATE_FORMAT(start_date, '%Y-%m-%d %H:%i:%s') <= '$cdate' AND DATE_FORMAT(end_date, '%Y-%m-%d %H:%i:%s') >= '$cdate')";
	$chkPromoRs = exec_query($qqq, $con);
	if(mysqli_num_rows($chkPromoRs)){ $price = $pro_row->product_promo_price; }
	else{ $price = $pro_row->product_price; }
	return $price;
}
/* get sum of price of cart products */
function getCartValue($user_id, $con){
	$rs = exec_query("SELECT product_price, promo_id, product_promo_price, qty FROM tbl_cart WHERE user_id = '".$user_id."'", $con);
	$price = 0;
	while($row = mysqli_fetch_object($rs)){
		$price += (checkPromoValidity($row, $con) * $row->qty);
	} return $price;
}
/* check promo code validity for cart product, it return either true or false ---------------------------------------- */
function checkPromoCodeValidity($pro_row, $sumOfCart, $con){
	$dataReturn = array();
	$cdate = date('Y-m-d H:i:s');
	$chkPromoRs = exec_query("SELECT recid, percent_or_amount, promo_value FROM tbl_promo_code WHERE
	recid = $pro_row->promo_code_id AND min_cart_value <= '$sumOfCart' AND
	(DATE_FORMAT(start_date, '%Y-%m-%d %H:%i:%s') <= '$cdate' AND DATE_FORMAT(end_date, '%Y-%m-%d %H:%i:%s') >= '$cdate')", $con);
	if(mysqli_num_rows($chkPromoRs)){
		$promo = mysqli_fetch_object($chkPromoRs);
		$dataReturn[0] = $promo->recid;
		$dataReturn[1] = $promo->percent_or_amount;
		$dataReturn[2] = $promo->promo_value;
		$dataReturn[3] = true;
	}
	else{
		$dataReturn[0] = '';
		$dataReturn[1] = '';
		$dataReturn[2] = '';
		$dataReturn[3] = false;
	}
	return $dataReturn;
}

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
                                       </tbody></table></div></td>
		<td style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"></td>
	</tr></tbody></table>

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
				</tbody></table></div>
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
                  <a target="_blank" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;color:#4b4b4b;font-weight:bold;text-decoration:underline" title="Twitter" href="#"><img width="49" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;max-width:100%" alt="Twitter" src="'.siteUrl.'/images/fb.png"></a></td>
                <td width="32" align="center" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"><a target="_blank" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;color:#4b4b4b;font-weight:bold;text-decoration:underline" href="#"><img width="49" height="49" style="color:#666666;font-size:8px;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;margin:0;padding:0;max-width:100%" alt="Twitter" src="'.siteUrl.'/images/twit.png"></a></td>
				<td width="32" align="center" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif">
                  <a target="_blank" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;color:#4b4b4b;font-weight:bold;text-decoration:underline" title="Facebook" href="#"><img width="49" alt="YouTube" style="color:#666666;font-size:8px;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;margin:0;padding:0;max-width:100%" src="'.siteUrl.'/images/utube.png"></a>
                </td>
                <td width="32" align="center" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif"><a target="_blank" style="margin:0;padding:0;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;color:#4b4b4b;font-weight:bold;text-decoration:underline" href="#"><img width="49" height="49" alt="Blog" style="color:#666666;font-size:8px;font-family:Century Gothic,Calibri,Helvetica,Arial,sans-serif;margin:0;padding:0;max-width:100%" src="'.siteUrl.'/images/blog.png"></a></td>
              </tr>
            </tbody></table></td></tr>
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
</td></tr>
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

function countdowntimer($datetime, $name){ ?>
	<div class='wrapper'>
 		<div class='time-part-wrapper'>
    		<div class='time-part minutes tens'> </div>
	  	</div>
	</div>
	<script>
	$(function(){
		ts = new Date(<?php echo $datetime; ?>),
		newYear = true;
		if((new Date()) > ts){
			// The new year is here! Count towards something else.
			// Notice the *1000 at the end - time must be in milliseconds
			ts = (new Date()).getTime() + 10*24*60*60*1000;
			newYear = false;
		}
		$('#<?php echo $name; ?>').countdown({
			timestamp	: ts,
			callback	: function(days, hours, minutes, seconds){}
		});
	});
	</script>
<?php }

function catmaster($gender, $param, $con){
	//echo $query="select * from tbl_category where is_featured = 1 and superparent_id = 0";
	if($gender == 'male'){ $q = "(pd.user_group like 'male' OR pd.user_group like 'male%')"; }
	else{ $q = "pd.user_group like '%female'"; }
	$query = "SELECT cat.`category_id`, cat.`category_name`, cat.slug, (select count(pdcat.product_id) from tbl_product_category pdcat left join tbl_category catb on pdcat.category_id = catb.category_id left join tbl_product pd on pdcat.product_id = pd.product_id where pd.is_activate = 1 AND (pdcat.category_id= cat.category_id or catb.parent_id = cat.category_id or catb.superparent_id = cat.category_id ) and $q ) as pds FROM `tbl_category` cat where cat.superparent_id = 0 HAVING pds > 0";
	$array=mysqli_query($con, $query);
	return $array;
}

function subcats($gender, $cat, $con){
	//$query="select * from tbl_category where parent_id = '0' and superparent_id = '".$cat."'";
	if($gender == 'male'){ $q = "(pd.user_group like 'male' OR pd.user_group like 'male%')"; }
	else{ $q = "pd.user_group like '%female'"; }
	$query = "SELECT cat.`category_id`, cat.`category_name`, cat.slug, (select count(pdcat.product_id) from tbl_product_category pdcat left join tbl_category catb on pdcat.category_id = catb.category_id left join tbl_product pd on pdcat.product_id = pd.product_id where pd.is_activate = 1 AND (pdcat.category_id= cat.category_id or catb.parent_id = cat.category_id or catb.superparent_id = cat.category_id ) and $q ) as pds FROM `tbl_category` cat where cat.parent_id = '0' AND cat.superparent_id = '".$cat."' HAVING pds > 0";
	$array=mysqli_query($con, $query);
	return $array;
}

function deals($no, $con){
	$cdate = date('Y-m-d H:i:s');
	$query="select * from tbl_promotion tp where tp.is_featured=1 AND (DATE_FORMAT(tp.start_date, '%Y-%m-%d %H:%i:%s') <= '$cdate' AND DATE_FORMAT(tp.end_date, '%Y-%m-%d %H:%i:%s') >= '$cdate' and is_publish = 1)";
	$rs=mysqli_query($con, $query);
	return $rs;
}

function featuredTag($con){
	$query = "select * from tbl_tag";
	$rs = mysqli_query($con,$query);
	return $rs;
}

function promotionFromSlug($promo, $flag, $con){
	/* flag is for check date condition */
	$condi = '';
	$cdate = date('Y-m-d H:i:s');
	if($flag){ $condi = " AND (DATE_FORMAT(tp.start_date, '%Y-%m-%d %H:%i:%s') <= '$cdate' AND DATE_FORMAT(tp.end_date, '%Y-%m-%d %H:%i:%s') >= '$cdate') "; }
	$query = mysqli_query($con, "SELECT tp.*, tpd.ids, tpd.category_id FROM tbl_promotion tp LEFT JOIN tbl_promotion_detail tpd ON tpd.promo_id = tp.promo_id WHERE tp.slug = '$promo' $condi");
	$rs=mysqli_fetch_object($query);
	return $rs;
}

function getOrderId($orderId,$con){
	$row = mysqli_fetch_object(mysqli_query($con, "SELECT od_date FROM tbl_order WHERE order_id = '$orderId'"));
	$date = date('my', strtotime($row->od_date));
	$oid = 'JHM'.$date.$orderId;
	return $oid;
}
function formatCurrency($no){ //return round((float)$no, 2);
	if (strpos($no, ".") === FALSE ){ $return = $no.'.00'; }
	else{ $noArr = explode('.', $no); $no1 = $noArr[0]; $no2 = substr($noArr[1], 0, 2);	$return = $no1.'.'.$no2; }
	return $return;
}
function getGst($con){
	$gstRs = mysqli_query($con, "SELECT tax_percent FROM tbl_tax WHERE tax_name = 'GST'");
	$gstRow = mysqli_fetch_object($gstRs);
	if(mysqli_num_rows($gstRs) && isset($gstRow->tax_percent) && $gstRow->tax_percent > 0){ $gst = $gstRow->tax_percent; }
	else{ $gst = 15; }
	return $gst;
}
function sendMail($subject, $content, $emails){
	$phpMailerSubject = $subject;
	require  SITE_ROOT.'/mail/PHPMailerAutoload.php';

	$phpMailerText = $content;
	foreach($emails AS $email){
		if($email != ''){
			$phpMailerTo = $email;
			include SITE_ROOT.'/mail/PHPMailerConfig.php';
		}
	}
	//echo $phpMailerText; die();
}
function getUserPoint($user_id, $con){
	$sumRs = mysqli_query($con, "SELECT SUM(point) AS sum FROM `tbl_user_point` WHERE user_id = $user_id");
	$row = mysqli_fetch_object($sumRs);
	if(isset($row->sum) && $row->sum > 0){ $sum = $row->sum; }
	else{ $sum = 0; }
	return $sum;
}
function sendOrderMail($user_id, $orderId, $con){
	// send mail
	$email = mysqli_fetch_object(mysqli_query($con, "SELECT email FROM tbl_user WHERE user_id = '$user_id'"));
	if(isset($email->email) && $email->email != ''){
		$rsEmail = mysqli_query($con, "SELECT * FROM tbl_email_template WHERE type = 'orderConfirm'");
		$rowEmail = mysqli_fetch_object($rsEmail);
		$content = $rowEmail->content;

		//get delivery addreess
		$row = mysqli_fetch_object(mysqli_query($con, "SELECT * FROM tbl_order WHERE order_id = '$orderId'"));
		$shipuname = $row->od_shipping_first_name.' '.$row->od_shipping_last_name;
		$phoneAlt = $row->od_shipping_alt_phone;
		$phoneAlt1 = ($phoneAlt != '')?' ('.$phoneAlt.')':'';
		$delAddress = '<table border="0" cellspacing="0" cellpadding="2">
			<tbody>
				<tr>
					<td>Shipping Name</td>
					<td>'.$shipuname.'</td>
				</tr>
				<tr>
					<td>Contact No.</td>
					<td>'.$row->od_shipping_phone.' '.$phoneAlt1.'</td>
				</tr>
				<tr>
					<td>Locality</td>
					<td>'.$row->od_shipping_locality.'</td>
				</tr>
				<tr>
					<td>Address</td>
					<td>'.$row->od_shipping_address.'</td>
				</tr>
				<tr>
					<td>City, State</td>
					<td>'.$row->od_shipping_city.'</td>
				</tr>
				<tr>
					<td>Postal Code</td>
					<td>'.$row->od_shipping_postal_code.'</td>
				</tr>
			</tbody>
		</table>';
		$style = 'style="border:thin dotted #d3d3d3;"';
		$table = '<table cellspacing="0" cellpadding="5" border="0" style="font-size:13px; width:100%">
		<thead>
			<tr>
				<th colspan="2" '.$style.'>Product</th>
				<th '.$style.'>Price</th>
				<th '.$style.'>Qty</th>
				<th '.$style.'>Subtotal</th>
			</tr>
		</thead>
		<tbody>';
		// for prepare order items in table
		$gTotal = 0;
		$gstCalcTotal = 0;
		$cart_total = 0;
		$pricePromotionTotal = 0;
		$pricePromoCodeTotal = 0;

		$gst = getGst();
		$oiQuery = "SELECT toi.*, tp.product_name, tp.slug, tc.color FROM tbl_order_item toi
		LEFT JOIN tbl_product tp ON tp.product_id = toi.product_id
		LEFT JOIN tbl_product_color tpc ON tpc.color_id = toi.color_id
		LEFT JOIN tbl_color tc ON tc.color_code = tpc.color_code
		WHERE toi.order_id = '$orderId'
		GROUP BY toi.product_id, toi.color_id";
		$oiRs = mysqli_query($con, $oiQuery);
		while($oiRow = mysqli_fetch_object($oiRs)){
			$imgRow = mysqli_fetch_object(mysqli_query($con, "SELECT media_thumb FROM tbl_product_media WHERE product_id = '".$oiRow->product_id."' AND color_id = '".$oiRow->color_id."' LIMIT 0,1"));
			$img = '';
			if(isset($imgRow->media_thumb) && $imgRow->media_thumb != ''){ $img = $imgRow->media_thumb; }
			$pname = $oiRow->product_name.' ('.$oiRow->color.')';

						$pricePromotion = $oiRow->product_promo_price;
						$pricePromoCode = $oiRow->product_promo_code_price;
                        $qty = $oiRow->od_qty;
						$priceNoDiscount = $oiRow->product_price;
						$productDiscountedPrice = 0;

						if($pricePromotion > 0){
							$productDiscountedPrice = $oiRow->product_promo_price;
							$promoType = $oiRow->product_promo_type;
							$promoVal = $oiRow->product_promo_value;
							if($promoType == 'percent'){
								$discountPromo = ($priceNoDiscount * $promoVal) / 100;
								$pricePromotionTotal += ($qty * $discountPromo);
							}
							elseif($promoType == 'amount'){
								$discountPromo = $promoVal;
								$pricePromotionTotal += $discountPromo;
							}
						}
						if($pricePromoCode > 0){
							$productDiscountedPrice = $oiRow->product_promo_code_price;
							$promoCodeType = $oiRow->product_promo_code_type;
							$promoCodeVal = $oiRow->product_promo_code_value;
							if($promoCodeType == 'percent'){
								$discountPromoCode = ($priceNoDiscount * $promoCodeVal) / 100;
								$pricePromoCodeTotal += ($qty * $discountPromoCode);
							}
							elseif($promoCodeType == 'amount'){
								$discountPromoCode = $promoCodeVal;
								$pricePromoCodeTotal += $discountPromoCode;
							}
						}

						if ($productDiscountedPrice == 0) {
							$productDiscountedPrice = $priceNoDiscount;
						}  //no promotion, use normal price


						$Gst = $productDiscountedPrice*.15;	//gst value
						$priceWithoutGST = $productDiscountedPrice-$Gst;	//price without tax

						$rowTotal = ($productDiscountedPrice * $qty);
						$gstCalc = ($Gst * $qty);
						$gstCalcTotal += $gstCalc;
                        $cart_total+=$rowTotal;
						$pPrice = formatCurrency(round($productDiscountedPrice, 2, PHP_ROUND_HALF_UP));
						$rowTotal = formatCurrency(round($rowTotal, 2, PHP_ROUND_HALF_UP));
			$table .= "<tr>
				<td $style><img src='".siteUrl."site_image/product/".$img."' width='100' height='80' /></td>
				<td $style>$pname</td>
				<td $style>$ $pPrice</td>
				<td $style>$qty</td>
				<td $style>$ $rowTotal</td>
			</tr>";
		}

		$delChrgCalc = $row->od_shipping_cost;
		//$subTotal = $totprice - ($pricePromoCodeTotal + $pricePromotionTotal);
		//$gstCalcTotal = ($totprice - ($pricePromoCodeTotal + $pricePromotionTotal))*0.15;
		//$gstCalcTotal = $cart_total*0.15;
		//$allTotal = ($gTotal + $gstCalcTotal + $delChrgCalc) - ($pricePromoCodeTotal + $pricePromotionTotal + $row->od_point_deduct);
		$allTotal = ($cart_total + $delChrgCalc);
		$fAallTotal = $allTotal;

		$table .= '<tr>
			<td '.$style.' colspan="4" align="right">Cart Total</td>
			<td '.$style.'>$ '.formatCurrency(round($cart_total, 2, PHP_ROUND_HALF_UP)).'</td>
		</tr>';

		// if ($pricePromotionTotal > 0) {
			// $table .= '<tr>
			// <td colspan="4" '.$style.' align="right">Cart Discount</td>
			// <td '.$style.'>- $ '.formatCurrency($pricePromotionTotal).'</td>
		// </tr>';
		// }

		// if	($pricePromoCodeTotal > 0) {
			// $table .= '	<tr>
			// <td colspan="4" '.$style.' align="right">Promo Code Discount</td>
			// <td '.$style.'>- $ '.formatCurrency($pricePromoCodeTotal).'</td>
		// </tr>';
		// }

		$table .=
		'<tr>
			<td colspan="4" '.$style.' align="right">Sub Total</td>
			<td '.$style.'>$ '.formatCurrency(round($cart_total, 2, PHP_ROUND_HALF_UP)).'</td>
		</tr>

		<tr>
			<td colspan="4" '.$style.' align="right">Includes Tax</td>
			<td '.$style.'>$ '.formatCurrency(round($gstCalcTotal, 2, PHP_ROUND_HALF_UP)).'</td>
		</tr>
		<tr>
			<td colspan="4" '.$style.' align="right">Shipping Charge</td>
			<td '.$style.'>$ '.formatCurrency($delChrgCalc).'</td>
		</tr>
		<!--<tr>
			<td colspan="4" '.$style.' align="right">Point Deduction</td>
			<td '.$style.'>- $ '.formatCurrency($row->od_point_deduct).'</td>
		</tr>-->
		<tr style="font-size:14px;">
			<td colspan="4" align="right" '.$style.'>Grand Total</td>
			<td '.$style.'>$ '.formatCurrency(round($fAallTotal, 2, PHP_ROUND_HALF_UP)).'</td>
		</tr>';

		$table .= '</tbody></table>';
		$data = explode('@', $email->email);

		$contentHTML = html_entity_decode($content);
		$contentHTML = str_replace('{jhm :', '', $contentHTML); // replace all '{jhm : '

		$orderNo = getOrderId($orderId, $con);
		$arraySearch = array(' username}', ' orderNo}', ' orderItem}', ' price}', ' delAddress}'); // isko replace krna h
		$arrayReplace = array($data[0], $orderNo, $table, '$ '.formatCurrency(round($fAallTotal, 2, PHP_ROUND_HALF_UP)), $delAddress); // isse replace krna h

		$content = str_replace($arraySearch, $arrayReplace, $contentHTML); // yha milega sb

		$subject = 'Order Confirmation - Your Order('.getOrderId($orderId, $con).') with JHM has been successfully placed.';

		sendMail($subject, $content, array($email->email));
	}
}
function getIcon(){
	//echo '<link rel="shortcut icon" type="image/ico" href="'.siteUrl.'favicon.ico" />';
	echo '<link rel="shortcut icon" type="image/png" href="'.siteUrl.'/images/favicon.png" />';
	echo '<link rel="apple-touch-icon" sizes="57x57" href="'.siteUrl.'/images/favicon.png" />';
}
function getNoOfProductOnCategoryPage($con){
	$no = mysqli_fetch_object(mysqli_query($con,"SELECT no FROM tbl_config WHERE type = 'noProductOnCategoryPage'"));
	if(isset($no->no) && $no->no != ''){ return $no->no; }
	else{ return 28; }
}

function getImage1($ids, $con){
	$array = explode(',', $ids);
	$catRs = mysqli_query($con, "SELECT category_id FROM tbl_product_category WHERE product_id IN (".$array[array_rand($array)].") ");
	$catRow = mysqli_fetch_object($catRs);
	$cid = $catRow->category_id;

	$level = chkCategoryLevel($cid, $con);
	if($level == 'subCat' || $level == 'subSubCat'){
		$parent = getCategory(array('superparent_id'), $cid, $con);
		$cid = $parent->superparent_id;
	}

	$imgRs = mysqli_query($con, "SELECT media_src FROM tbl_category_media WHERE category_id = '$cid'");
	$imgRow = mysqli_fetch_object($imgRs);
	return 'site_image/category/'.$imgRow->media_src;
}

function getImage2($ids, $con){
	$array = explode(',', $ids);
	$cid = $array[array_rand($array)];

	$level = chkCategoryLevel($cid, $con);
	if($level == 'subCat' || $level == 'subSubCat'){
		$parent = getCategory(array('superparent_id'), $cid, $con);
		$cid = $parent->superparent_id;
	}
	$imgRs = mysqli_query($con, "SELECT media_src FROM tbl_category_media WHERE category_id = '$cid'");
	$imgRow = mysqli_fetch_object($imgRs);
	return 'site_image/category/'.$imgRow->media_src;
}

function checkOrderStatus($status){
		if($status == 0){ $oStatus = 'Processing'; }
		elseif($status == 1){ $oStatus = 'Dispatched'; }
		elseif($status == -1){ $oStatus = 'Rejected'; }
		elseif($status == 2){ $oStatus = 'Delivered'; }
		elseif($status == 3){ $oStatus = 'Cancelled'; }
		return $oStatus;
}

function logMessage($message, $con){
    mysqli_query($con, "INSERT INTO `eventlog` (Message) VALUES ('".$message."')");
}

function fetchRandomToken(){
	return random_int(1000001, 9999999);
}

/* function for chk sll login, means secure */
/*function ssl($securepage){
	if ($_SERVER['HTTPS'] == 'on') {
        // we are on a secure page.
        if (!$securepage) {
        	// but we shouldn't be!
          	$url = str_replace('/', '', siteUrl).$_SERVER['REQUEST_URI'];
          	//redirect($url);
			header("location : ".$url); exit;
        }
  	} else {
        // we aren't on a secure page.
        if ($securepage) {
        	// but we should be!
          	$url = str_replace('/', '', siteSecureUrl).$_SERVER['REQUEST_URI'];
          	//redirect($url);
			header(" location : ".$url); exit;
        }
  	}
}*/


?>