<?php

/**
 * @turturkeykey - export feature implementation
 * @exportFormat csv
 */


include '../include/config.php';
include '../include/function.php';

$ids = explode(',', $_GET['ids']);
$filtered = array_filter($ids, 'ctype_digit');

$usable = implode(',', $filtered);

$query = "SELECT tbl_product.*, tbl_brand.brand_name, tpc.color_code as color, tpp.product_upc AS upc1, tpp.product_price, tpp.qty, tpp.cost, tpu.text AS `usage`, tpb.brand_name
          FROM tbl_product
          LEFT JOIN tbl_brand ON tbl_brand.brand_id = tbl_product.brand_id
          INNER JOIN tbl_product_price tpp ON tpp.product_id = tbl_product.product_id
          INNER JOIN tbl_product_category tpcat ON tpcat.product_id = tbl_product.product_id         
          LEFT OUTER JOIN tbl_product_usage tpu ON tpu.product_id = tbl_product.product_id         
          LEFT OUTER JOIN tbl_product_color tpc ON tpc.product_id = tbl_product.product_id         
          LEFT OUTER JOIN tbl_brand tpb ON tpb.brand_id = tbl_product.brand_id         
          WHERE tbl_product.product_id != '' AND tbl_product.is_activate != 4 AND tbl_product.product_id IN ({$usable})
          GROUP BY tbl_product.product_id
          ORDER BY tbl_product.modified_on DESC";

$rs_pro = mysqli_query($con, $query);
$exportable = [];
array_push($exportable, [
    'product_id',
    'sku',
    'manufacturer_code',
    'upc',
    'name',
    'description',
    'usage',
    'qty',
    'availability',
    'price',
    'cost',
    'color',
    'brand',
    'main_category',
    'sub_category',
    'sub_sub_category',
    'keyword'
]);
while($product = mysqli_fetch_object($rs_pro)) {
    $cat_rs = mysqli_query( $con, "select category_id from tbl_product_category WHERE product_id = '$product->product_id'");
    $catArr = [];
    while($cat_row = mysqli_fetch_object($cat_rs)){ $catArr[] = $cat_row->category_id; }
    $mainCategory = '';
    $subCategory = '';
    $subSubCategory = '';
    if(!empty($catArr)){
        foreach($catArr AS $cat) {
            $level = chkCategoryLevel($cat, $con);
            if($level == 'cat'){
                $mainccat = getCategory(array('category_id'), $cat, $con);
                if(isset($mainccat->category_name)){
                    $mainCategory = $mainccat->category_id;
                }
            }
            elseif($level == 'subCat'){
                $scat = getCategory(array('category_id', 'superparent_id'), $cat, $con);
                $cat = getCategory(array('category_id'), $scat->superparent_id, $con);
                $mainCategory = $cat->category_id;
                $subCategory = $scat->category_id;
            }
            elseif($level == 'subSubCat'){
                $sscat = getCategory(array('category_id', 'parent_id'), $cat, $con);
                $subSubCategory = $sscat->category_id;
                $scat = getCategory(array('category_id', 'category_id', 'superparent_id'), $sscat->parent_id, $con);
                $subCategory = $scat->category_id;
                if (isset($scat)) {
                    $cat = getCategory(array('category_id'), $scat->superparent_id, $con);
                    $mainCategory = $cat->category_id;
                }

            }
        }
    }
    $description = strip_tags($product->product_description);
    array_push($exportable, [
        $product->product_id,
        $product->product_sku,
        $product->manufacturer_code,
        $product->upc1,
        $product->product_name,
        preg_replace('(\r?\n)+|(\t)', '', $description),
        $product->usage,
        $product->qty,
        $product->stock_availability,
        $product->product_price,
        $product->cost,
        $product->color,
        $product->brand_name,
        $mainCategory,
        $subCategory,
        $subSubCategory,
        $product->keyword
    ]);
}

header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="export-'.date('s').'.csv";');

$f = fopen('php://output', 'w');

foreach ($exportable as $line) {
    fputcsv($f, $line);
}
