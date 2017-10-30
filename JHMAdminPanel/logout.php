<?php
session_start();
include '../include/function.php';
if(isset($_GET['logout']) && $_GET['logout'] == 'true'){
	session_unset();
	session_destroy();
	redirect('index.php');
}
else{
	redirect('index.php');
}
?>