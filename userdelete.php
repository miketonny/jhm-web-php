<?php session_start(); include 'include/config.php';
if(isset($_GET['table']) && isset($_GET['id']) && isset($_GET['pk'])){
	$table = $_GET['table'];
	$recid = $_GET['id'];
	$pk = $_GET['pk'];
	$rs = mysqli_query($con, "DELETE FROM $table WHERE $pk = '$recid'");
	if($rs){ echo 'Record successfully deleted.';
	}else{ echo 'Some error occured, Try again.'; }
}else{ echo 'Some error occured, Try again.'; }?>