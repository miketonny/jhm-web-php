<?php session_start(); include '../include/config.php';
if(isset($_SESSION['admin']) || $_SESSION['admin'] != '' || !empty($_SESSION['admin'])){
	if(isset($_GET['table']) && isset($_GET['id']) && isset($_GET['pk'])){
		$table = $_GET['table'];
		$recid = $_GET['id'];
		$pk = $_GET['pk'];
		$rs = mysqli_query($con, "DELETE FROM $table WHERE $pk = '$recid'");
		if($rs){ /* main record deleted */
			if($table == 'tbl_product_color'){ $pid = $_GET['pid'];
				mysqli_query($con, "DELETE FROM tbl_product_media WHERE color_id = '$recid' AND product_id = '$pid'");
				mysqli_query($con, "DELETE FROM tbl_product_price WHERE color_id = '$recid' AND product_id = '$pid'");
			}elseif($table == 'tbl_product'){
				mysqli_query($con, "DELETE FROM tbl_product_color WHERE product_id = '$recid'");
				mysqli_query($con, "DELETE FROM tbl_product_media WHERE product_id = '$recid'");
				mysqli_query($con, "DELETE FROM tbl_product_price WHERE product_id = '$recid'");
				mysqli_query($con, "DELETE FROM tbl_product_usage WHERE product_id = '$recid'");
			}elseif($table == 'tbl_category'){
				mysqli_query($con, "DELETE FROM tbl_category_media WHERE category_id = '$recid'");
				/* unpublish all related product */
				$rs = mysqli_query($con, "SELECT product_id FROM tbl_product_category WHERE category_id = '$recid'");
				while($row = mysqli_fetch_object($rs)){
					mysqli_query($con, "UPDATE tbl_product SET is_activate = 0 WHERE product_id = '$row->product_id'");
				}
			}elseif($table == 'tbl_promotion'){
				mysqli_query($con, "DELETE FROM tbl_promotion_detail WHERE promo_id = '$recid'");
			}
			echo 'Record successfully deleted.';
		}else{ echo 'Some error occured, Try again.'; }
	}else{ echo 'Some error occured, Try again.'; }
}else{ echo 'Some error occured, Try again.'; } ?>