<?php include '../include/config.php';
if(isset($_GET['table']) && isset($_GET['pk_column']) && isset($_GET['pk_val']) && isset($_GET['up_column']) && isset($_GET['up_val'])){
	if(isset($_GET['multi'])){
		$ids = explode(',', $_GET['pk_val']);
		foreach($ids AS $val){ $rs = mysql_query("UPDATE ".$_GET['table']." SET ".$_GET['up_column']." = '".$_GET['up_val']."' WHERE ".$_GET['pk_column']." = '".$val."'", $con); }
	}
	elseif(isset($_GET['promotion'])){
		$ids = explode(',', $_GET['pk_val']);
		foreach($ids AS $val){
			$colsData = explode('|', $val);
			$colval = $colsData[0];
			$table = $colsData[1];
			$pk = ($table == 'tbl_promotion')? 'promo_id' : 'recid';
			$rs = mysql_query("UPDATE $table SET ".$_GET['up_column']." = '".$_GET['up_val']."' WHERE $pk = '".$colval."'", $con);
		}
	}
	else{ $rs = mysql_query("UPDATE ".$_GET['table']." SET ".$_GET['up_column']." = '".$_GET['up_val']."' WHERE ".$_GET['pk_column']." = '".$_GET['pk_val']."'", $con); }
	if($rs){ echo 'Record successfully Updated.'; }
	else{ echo 'Some error occured, Try again.'; }
}else{ echo 'Some error occured, Try again.'; }?>