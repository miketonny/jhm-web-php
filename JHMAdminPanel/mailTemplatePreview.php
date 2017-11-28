<?php include '../include/config.php';
include '../include/function.php';
$content = '';
if(isset($_GET['data1']) && $_GET['data1'] != ''){
	$id = $_GET['data1'];
	$rs = exec_query("SELECT * FROM tbl_email_template WHERE recid = '$id'", $con);
	if(mysqli_num_rows($rs)){
		$row = mysqli_fetch_object($rs);
		echo $content = html_entity_decode($row->content);
	}
}
if($content == ''){ echo '<script> history.back(); </script>'; die(); }