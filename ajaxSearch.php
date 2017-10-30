<?php ob_start("ob_gzhandler");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("include/config.php");
include("include/functions.php");
$data = '';
$char = addslashes($_GET['term']);
$charArr = explode(' ', $char);
$i = 1; $catCondi = ''; $brandCondi = ''; $proCondi = '';
foreach($charArr AS $value){
	$or = ($i == 1) ? '' : 'AND';
	$catCondi .= " $or category_name LIKE '%".$value."%'";
	$brandCondi .= " $or brand_name LIKE '%".$value."%'";
	$proCondi .= " $or ((tp.product_name LIKE '%".$value."%' OR tbl_product_price.product_upc LIKE '%".$value."%' OR tp.product_sku LIKE '%".$value."%') AND tp.is_activate = 1) ";
	$i++;
}
$q = "SELECT * FROM(
(SELECT tp.product_id AS id, tp.product_name AS name, 'product' AS type FROM tbl_product tp LEFT JOIN tbl_product_price ON tbl_product_price.product_id = tp.product_id WHERE ($proCondi) LIMIT 0,10)
UNION
(SELECT category_id AS id, category_name AS name, 'mainCat' AS type FROM tbl_category WHERE ($catCondi) AND superparent_id = 0 LIMIT 0,10)
UNION
(SELECT brand_id AS id, brand_name AS name, 'brand' AS type FROM tbl_brand WHERE ($brandCondi) LIMIT 0,10)
UNION
(SELECT category_id AS id, category_name AS name, 'subCat' AS type FROM tbl_category WHERE ($catCondi) AND superparent_id != 0 AND parent_id = 0 LIMIT 0,10)
UNION
(SELECT category_id AS id, category_name AS name, 'subsubCat' AS type FROM tbl_category WHERE ($catCondi) AND superparent_id != 0 AND parent_id != 0 LIMIT 0,10)
) AS temp"; // LIMIT 0, 15 ORDER BY RAND()
$rs = mysql_query($q, $con);
if(mysql_num_rows($rs)){
	while($row = mysql_fetch_object($rs)){
		$url = '';
		$type = $row->type;
		$name = $row->name;
		$id = $row->id;
		$str = '';
		if($type == 'product'){
			/* fetch category name of product */
			$catRs = mysql_query("SELECT tc.category_name, tp.product_name, tp.product_id, tp.slug, tpcol.color_id FROM tbl_product_category tpc LEFT JOIN tbl_category tc ON tc.category_id = tpc.category_id LEFT JOIN tbl_product tp ON tp.product_id = tpc.product_id LEFT JOIN tbl_product_color tpcol ON tpcol.product_id = tp.product_id WHERE tpc.product_id = '$id'", $con);
			if(mysql_num_rows($catRs) > 0){
				while($proData = mysql_fetch_object($catRs)){
					$name1='';
					//$str = $proData->product_name;
					$catName = $proData->category_name;
					$colId = $proData->color_id;
					$id = $proData->product_id;
					$slug = $proData->slug;
					$name1 = $name.' in <b>'.$catName.'</b>';
					$url = siteUrl."detail/".$id."HXYK".$slug."TZS1YL".$colId;
					//$data .= "<a style='color:#214C53;' href='".siteUrl."detail/".$id."HXYK".$slug."TZS1YL".$colId."'>".$link."</a>";
					
					$name1 = ucfirst(strtolower($name1));
					$json[] = array(
					   'id' => $id,
					   'type' => $type,
					   'name' => $name,
					   'url' => $url
					);
					
				}
			}
		}
		elseif($type == 'mainCat' || $type == 'subCat' || $type == 'subsubCat'){
			/* fetch product name of typed category */
			$proRs = mysql_query("SELECT tp.product_name, tp.product_id FROM tbl_product_category tpc LEFT JOIN tbl_product tp ON tp.product_id = tpc.product_id WHERE tpc.category_id = '$id'", $con);
			while($proData = mysql_fetch_object($proRs)){
				if(isset($proData->product_name) && $proData->product_name != ''){
					$name1='';
					$str = $proData->product_name;
					$name1 = $str.' in <b>'.$name.'</b>';
					
					$name1 = ucfirst(strtolower($name1));
					$json[] = array(
					   'id' => $id,
					   'type' => $type,
					   'name' => $name1,
					   'url' => $url
					);
		
				}
			}
		}
		elseif($type == 'brand'){
			/* fetch category on brand */
			$catRs = mysql_query("SELECT tc.category_name, tc.category_id FROM tbl_product_category tpc LEFT JOIN tbl_product tp ON tp.product_id = tpc.product_id LEFT JOIN tbl_category tc ON tc.category_id = tpc.category_id WHERE tp.brand_id = '$id' GROUP BY tpc.category_id", $con);
			while($catData = mysql_fetch_object($catRs)){
				if(isset($catData->category_name) && $catData->category_name != ''){
					$name1='';
					$str = ' in <b>'.$catData->category_name.'</b>';
					$name1 = $name.$str;
					$name1 = ucfirst(strtolower($name1));
					$url = $catData->category_id;
					$json[] = array(
					   'id' => $id,
					   'type' => $type,
					   'name' => $name1,
					   'url' => $url
					);
					
				}
			}
		}
		/*$name = ucfirst(strtolower($name));
		$json[] = array(
		   'id' => $id,
		   'type' => $type,
		   'name' => $name,
		   'url' => $url
		);*/
	}
}
else{
	$json[] = array(
	   'id' => 'No',
	   'type' => 'No',
	   'name' => 'No',
	   'url' => 'No'
	);
}
echo json_encode($json); die();
/* phle ki query h , alone condition
$q = "SELECT * FROM(
(SELECT category_id AS id, category_name AS name, 'mainCat' AS type FROM tbl_category WHERE category_name LIKE '%".$char."%' AND superparent_id = 0 LIMIT 0, 10)
UNION
(SELECT brand_id AS id, brand_name AS name, 'brand' AS type FROM tbl_brand WHERE brand_name LIKE '%".$char."%' LIMIT 0, 10)
UNION
(SELECT category_id AS id, category_name AS name, 'subCat' AS type FROM tbl_category WHERE category_name LIKE '%".$char."%' AND superparent_id != 0 AND parent_id = 0 LIMIT 0, 10)
UNION
(SELECT category_id AS id, category_name AS name, 'subsubCat' AS type FROM tbl_category WHERE category_name LIKE '%".$char."%' AND superparent_id != 0 AND parent_id != 0 LIMIT 0, 10)
UNION
(SELECT product_id AS id, product_name AS name, 'product' AS type FROM tbl_product WHERE product_name LIKE '%".$char."%' LIMIT 0, 10)
) AS temp ORDER BY RAND() LIMIT 0, 15";
*/
?>