<?php session_start(); include '../include/config.php';
if(isset($_SESSION['admin']) || $_SESSION['admin'] != '' || !empty($_SESSION['admin'])){
	if(isset($_GET['table']) && isset($_GET['id']) && isset($_GET['pk'])){
		$table = $_GET['table'];
		$recid = $_GET['id'];
		$pk = $_GET['pk'];
		$rs = mysql_query("DELETE FROM $table WHERE $pk = '$recid'", $con);
		if($rs){ /* main record deleted */
			if($table == 'tbl_product_color'){ $pid = $_GET['pid'];
				mysql_query("DELETE FROM tbl_product_media WHERE color_id = '$recid' AND product_id = '$pid'", $con);
				mysql_query("DELETE FROM tbl_product_price WHERE color_id = '$recid' AND product_id = '$pid'", $con);
			}elseif($table == 'tbl_product'){
				mysql_query("DELETE FROM tbl_product_color WHERE product_id = '$recid'", $con);
				mysql_query("DELETE FROM tbl_product_media WHERE product_id = '$recid'", $con);
				mysql_query("DELETE FROM tbl_product_price WHERE product_id = '$recid'", $con);
				mysql_query("DELETE FROM tbl_product_usage WHERE product_id = '$recid'", $con);
			}elseif($table == 'tbl_category'){
				mysql_query("DELETE FROM tbl_category_media WHERE category_id = '$recid'", $con);
				/* unpublish all related product */
				$rs = mysql_query("SELECT product_id FROM tbl_product_category WHERE category_id = '$recid'");
				while($row = mysql_fetch_object($rs)){
					mysql_query("UPDATE tbl_product SET is_activate = 0 WHERE product_id = '$row->product_id'");
				}
			}elseif($table == 'tbl_promotion'){
				mysql_query("DELETE FROM tbl_promotion_detail WHERE promo_id = '$recid'", $con);
			}
			echo 'Record successfully deleted.';
		}else{ echo 'Some error occured, Try again.'; }
	}else{ echo 'Some error occured, Try again.'; }
}else{ echo 'Some error occured, Try again.'; } ?>