<?php

/**
 * @turturkeykey - import feature implementation
 * @expectedFileFormat csv
 */

ini_set('display_errors', 'on');
error_reporting(E_ALL);


if(empty($_FILES))
    die('No file');

session_start();

if(empty($_SESSION['admin']))
    die('Forbidden');


include '../include/config.php';
include '../include/function.php';

if($_FILES['csv']['error'] !== 0) {
    setMessage('Failed to process upload!', 'alert alert-error');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$file = $_FILES['csv']['tmp_name'];

$lines = array_map('str_getcsv', file($file, FILE_SKIP_EMPTY_LINES));
$keys = array_shift($lines);

$products = [];
foreach ($lines as $i => $row) {
    $products[$i] = array_combine($keys, $row);
}

$stmt = $con->prepare("UPDATE `tbl_product_price` SET `qty`=? WHERE `product_upc`=?");
$stmtLog = $con->prepare("INSERT INTO `tbl_update_log` (`product_id`, `admin_id`, `new_qty`, `old_qty`, `ref`) VALUES (?, ?, ?, ?, ?)");
$stmtProduct = $con->prepare("SELECT `product_id`, `qty` FROM `tbl_product_price` WHERE `product_upc`=?");
$adminID = intval($_SESSION['admin']);
$time = time();
$qty = null;
$updates = 0;
foreach($products as $product) {
    $stmtProduct->bind_param(
        's',
        $product['upc']
    );
    $stmtProduct->execute();
    $stmtProduct->bind_result($product_id, $qty);

    if(!$stmtProduct->fetch())
        continue;

    $stmtProduct->free_result();

    if($qty !== (int)$product['qty']) {

        $stmt->bind_param(
            'is',
            $product['qty'],
            $product['upc']
        );
        $stmt->execute();
        $ref = $_FILES['csv']['name'] . '_' . $time;
        $stmtLog->bind_param(
            'iiiis',
            $product_id,
            $adminID,
            $product['qty'],
            $qty,
            $ref
        );
        $stmtLog->execute();
        $updates++;
    }
}
if($updates > 0) {
    setMessage($updates . ' products updated!', 'alert alert-success');
} else {
    setMessage('No products were updated!', 'alert alert-info');
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
