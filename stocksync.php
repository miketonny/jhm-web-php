<?php
require_once "vendor/autoload.php";
require_once "include/config.php";
require_once "include/functions.php";
 
$fileName = "uploads/test.xlsx";
 
if (!file_exists($fileName)) {
    echo 'Could not open import file for reading';
   
 }else{
 	// echo "good stuff";
 }

/** automatically detect the correct reader to load for this file type */
$excelReader = PHPExcel_IOFactory::createReaderForFile($fileName);


/** Create a reader by explicitly setting the file type.
// $inputFileType = 'Excel5';
// $inputFileType = 'Excel2007';
// $inputFileType = 'Excel2003XML';
// $inputFileType = 'OOCalc';
// $inputFileType = 'SYLK';
// $inputFileType = 'Gnumeric';
// $inputFileType = 'CSV';
$excelReader = PHPExcel_IOFactory::createReader($inputFileType);
*/

//if we dont need any formatting on the data
// $excelReader->setReadDataOnly();
//the default behavior is to load all sheets
// $excelReader->setLoadAllSheets();

// //load only certain sheets from the file
// $loadSheets = array('products');
// $excelReader->setLoadSheetsOnly($loadSheets);

// $excelObj = $excelReader->load($fileName);
$updatedCnt = 0;
$addedCnt = 0;
$xlsData = $excelReader->load($fileName);
$allData = $xlsData->getActiveSheet()->toArray(null, true,true,true);
$rowNo = $xlsData->getActiveSheet()->getHighestRow();
$lastRow = 'A' . $rowNo;
$pIdArrays= $xlsData->getActiveSheet()->rangeToArray('A2:'.$lastRow.''); 
$qtyArray = $xlsData->getActiveSheet()->rangeToArray('A2:J'.$rowNo.'');
$pIDs = array();//one-dimensional array
foreach ($pIdArrays as $id) { 
	array_push($pIDs, $id[0]);
}

$prodIds = array_map('strval', $pIDs); //convert to string for all array elements

//get all sheet names from the file
// $worksheetNames = $excelObj->getSheetNames($fileName);
// echo $worksheetNames; 
// $return = array();
// foreach($worksheetNames as $key => $sheetName){
// //set the current active worksheet by name
// $excelObj->setActiveSheetIndexByName($sheetName);
// //create an assoc array with the sheet name as key and the sheet contents array as value
// $return[$sheetName] = $excelObj->getActiveSheet()->toArray(null, true,true,true);
// }
//show the final array
 // var_dump($prodIDs);

//fetch existing product sku list, and matching with the excel list to find products need to be inserted as new or update existing stock qty
// $existingProdIDs = mysqli_fetch_assoc();
$existingPIDs = array(); //one-dimensional array
$result = mysqli_query($con, "SELECT product_sku FROM tbl_product");
while($row = mysqli_fetch_assoc($result)) {
        array_push($existingPIDs, $row['product_sku']);
}

$newProds = array_diff($prodIds, $existingPIDs);
$stockUpdateProdIds = array_intersect($prodIds, $existingPIDs); 
//update stock qty of existing product
foreach ($stockUpdateProdIds as $id) {
	$key = array_search($id, array_column($qtyArray, 0));//get the array item key
	$qty = $qtyArray[$key][8]; //get the qty value for this product
	//check this product's current qty in db
	$currentQty = mysqli_fetch_assoc(mysqli_query($con, "SELECT pr.qty as qty FROM tbl_product p INNER JOIN tbl_product_price pr on p.product_id = pr.product_id WHERE p.product_sku = '".$id."'"));
	//update qty when stock qty isnt matching to reduce load ==============================================	
	if ($qty != $currentQty['qty']) {
		//get query ====================
		$updateQuery = "Update tbl_product p INNER JOIN tbl_product_price pr ON p.product_id = pr.product_id 
	SET pr.qty = ". $qty. "  where p.product_sku = '". $id ."'";
		//execute query ========================
	 	if(!mysqli_query($con, $updateQuery)){
	 		echo "Error updating record: " . mysqli_error($con);
	 	}else{
	 		$updatedCnt ++;
	 	}
	}
}

//insert new products into JHM DB for further editing by admins
foreach ($newProds as $sku) {
	$key = array_search($sku, array_column($qtyArray, 0));//get the array item key
	$pName = $qtyArray[$key][5];
	$qty = $qtyArray[$key][8];
	$upc = $qtyArray[$key][1];
	$price = $qtyArray[$key][9];

	//add new product
	$sql = "INSERT INTO tbl_product (temp_subcategory, brand_id, product_name, product_description, product_summary, slug, user_group, 
	age_group, size, stock_availability, qty, product_sku, product_upc, manufacturer_code, tag, keyword, height, width, weight, created_on, created_by, modified_on, modified_by, is_activate, promotion_state) VALUES(
	1, 63, '".$pName."', '', '', '', '', '', '', 1, 0, '".$sku."', '".$upc."', '', '".$pName."', '', 0, 0, '', NOW(), 1, NOW(), 1, 0, 0)";
	if (mysqli_query($con, $sql)) {
    	$last_id = mysqli_insert_id($con);
    	//add new product color==========================
    	$sql = "INSERT INTO tbl_product_color (product_id, color_code) VALUES(".$last_id.", '#ffffff')";
    	mysqli_query($con, $sql);
    	$color_id = mysqli_insert_id($con);
		//add new product price===================================
 		$sql = "INSERT INTO tbl_product_price (product_id, product_upc, product_price, qty, cost, product_rrp, color_id, isDiscount, discount_start_date, discount_end_date, is_activate, backorder_qty) VALUES(".$last_id.", '".$upc."', ".$price.", ".$qty.", 0, ".$price.", ".$color_id.", 0, '0000-00-00', '0000-00-00', 0, 0)";
 		if(mysqli_query($con, $sql)){
 			$addedCnt++;
 		};

	} else {
	    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	
}
  
  echo "Updated Products Count:" .$updatedCnt;
  echo " Added Products Count:" .$addedCnt;
?>