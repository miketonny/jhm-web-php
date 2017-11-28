<?php

session_start();
include '../include/config.php';
include '../include/function.php'; 
chkParam($_SESSION['admin'], 'index.php');
$randSlug = ''; /* it is blank bcoz of slug changing */
$action = $_POST['action'];
switch ($action) {
    case 'categoryAdd' : categoryAdd($con);
        break;
    case 'categoryEdit' : categoryEdit($con);
        break;

    case 'brandAdd' : brandAdd($con);
        break;
    case 'brandEdit' : brandEdit($con);
        break;
    case 'brandColorEdit' : brandColorEdit($con);
        break;
    case 'ColorEdit2' : ColorEdit2($con);
        break;

    case 'productPreferenceEdit' : productPreferenceEdit($con);
        break;
    case 'productAdd' : productAdd($con);
        break;
    case 'productAddDesc' : productAddDesc($con);
        break;
    case 'productAddDetail' : productAddDetail($con);
        break;

    case 'productEdit' : productEdit($con);
        break;
    case 'productEditDesc' : productEditDesc($con);
        break;
    case 'productStepImgEdit' : productStepImgEdit($con);
        break;
    case 'productEditDetail' : productEditDetail($con);
        break;
    case 'productColorAdd' : productColorAdd($con);
        break;
    case 'updateProductCategory' : updateProductCategory($con);
        break;
    case 'assignMoreCategories' : assignMoreCategories($con);
        break;

    case 'sizeAdd' : sizeAdd($con);
        break;
    case 'sizeEdit' : sizeEdit($con);
        break;

    case 'managerAdd' : managerAdd($con);
        break;
    case 'setPermission' : setPermission($con);
        break;

    case 'promotionAdd' : promotionAdd($con);
        break;
    case 'promotionEdit' : promotionEdit($con);
        break;
    case 'dealBannerImage' : dealBannerImage($con);
        break;

    case 'masterPromotionAdd' : masterPromotionAdd($con);
        break;
    case 'masterPromotionEdit' : masterPromotionEdit($con);
        break;

    case 'promoCodeAdd' : promoCodeAdd($con);
        break;
    case 'promoCodeAddEmail' : promoCodeAddEmail($con);
        break;
    case 'promoCodeExtra' : promoCodeExtra($con);
        break;
    case 'extendPromotionExpiry' : extendPromotionExpiry($con);
        break;

    case 'taxAdd' : taxAdd($con);
        break;
    case 'taxEdit' : taxEdit($con);
        break;

    case 'tagAdd' : tagAdd($con);
        break;
    case 'tagEdit' : tagEdit($con);
        break;

    case 'config' : config($con);
        break;
    case 'manage' : manage($con);
        break;

    case 'addNewsletter' : addNewsletter($con);
        break;
    case 'editEmailTemplate' : editEmailTemplate($con);
        break;
    case 'addEmailTemplate' : addEmailTemplate($con);
        break;

    case 'adminSearchTagAdd' : adminSearchTagAdd($con);
        break;
    case 'adminSearchTagEdit' : adminSearchTagEdit($con);
        break;

    case 'shippingPriceAdd' : shippingPriceAdd($con);
        break;
    case 'shippingPriceEdit' : shippingPriceEdit($con);
        break;
    case 'shippingCityAdd' : shippingCityAdd($con);
        break;
    case 'shippingCityEdit' : shippingCityEdit($con);
        break;

    case 'dispatchInfo' : dispatchInfo($con);
        break;

    case 'userEdit' : userEdit($con);
        break;

    default : redirect('home.php');
}

function categoryAdd($con) {
    $slug = addslashes($_POST['slug']);
    if (chkSlug('tbl_category', $slug)) {
        setMessage('Slug already Exist. Try another.', 'alert alert-error');
        redirect('categoryAdd.php');
        die();
    }
    $mainCat = $_POST['mainCat'];
    $subCat = $_POST['subCat'];
    $name = $_POST['name'];
    $desc = addslashes($_POST['desc']);
    $flag = isset($_POST['flag']) ? "1" : "0";
    $color_code = $_POST['color_code'];

    $col = '';
    $val = '';
    if (isset($_POST['featured']) && $_POST['featured'] != '') {
        $col = ', is_featured';
        $val = ', 1';
    }
    $image_path = "";
    if (isset($_FILES['category_image']['name'])) {
        $r = rand(10, 99);
        $img = str_replace(array("'", ' ', '"'), '', $_FILES['category_image']['name']);
        $type = $_FILES['category_image']['type'];
        if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg') {

            move_uploaded_file($_FILES['category_image']['tmp_name'], "../site_image/category_image/" . $r . $img);
            $image_path = $r . $img;
        }
    }


    $query = "INSERT INTO tbl_category(category_name, category_description, slug, parent_id, superparent_id, flag,color_code,category_image,created_on $col) VALUES('$name', '$desc', '$slug', '$subCat', '$mainCat', '$flag','$color_code','$image_path','" . date('c') . "' $val)";

    if (exec_query($query, $con)) {
        $category_id = mysqli_insert_id($con);
        foreach ($_FILES['img']['name'] AS $key => $value) {
            $r = rand(10, 99);
            $img = str_replace(array("'", ' ', '"'), '', $_FILES['img']['name'][$key]);
            $type = $_FILES['img']['type'][$key];
            if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg') {
                $rs = mysqli_query($con, "INSERT INTO tbl_category_media(category_id, media_type, media_src) VALUES('$category_id', 'img', '" . $r . $img . "')");
                move_uploaded_file($_FILES['img']['tmp_name'][$key], "../site_image/category/" . $r . $img);
            }
        }
        setMessage('Category Successfully Added', 'alert alert-success');
        if (isset($_POST['nsubmit'])) {
            redirect('categoryAdd.php');
            die();
        }
        redirect('admincategory.php');
        die();
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('categoryAdd.php');
    die();
}

function categoryEdit($con) {
    $slug = addslashes($_POST['slug']);
    $mainCat = $_POST['mainCat'];
    $subCat = $_POST['subCat'];
    $id = $_POST['data1'];
    $name = $_POST['name'];
    $desc = addslashes($_POST['desc']);
    $flag = isset($_POST['flag']) ? "1" : "0";
    $color_code = $_POST['color_code'];
   
    $query = '';
    if (isset($_POST['featured']) && $_POST['featured'] != '') {
        $query = ', is_featured = 1';
    }

    $image_path = "";
    if (isset($_FILES['category_image']['name'])) {
        $r = rand(10, 99);
        $img = str_replace(array("'", ' ', '"'), '', $_FILES['category_image']['name']);
        $type = $_FILES['category_image']['type'];
        if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg') {

            move_uploaded_file($_FILES['category_image']['tmp_name'], "../site_image/category_image/" . $r . $img);
            $image_path = $r . $img;
        }else{
            
        $image_path = $_POST['cat_img']; 
        }
    } else {
       
        $image_path = $_POST['cat_img'];
    }
  

    $query = "UPDATE tbl_category SET category_name = '$name', category_description = '$desc', slug = '$slug', flag='$flag',color_code='$color_code',category_image='$image_path',parent_id = '$subCat', superparent_id = '$mainCat', modified_on = '" . date('c') . "' $query WHERE category_id = '$id'";
    if (exec_query($query, $con)) {
        $category_id = $id;
        foreach ($img = $_FILES['img']['name'] AS $key => $value) {
            $r = rand(10, 99);
            $img = str_replace(array("'", ' ', '"'), '', $_FILES['img']['name'][$key]);
            $type = $_FILES['img']['type'][$key];
            if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg') {
                $rs = mysqli_query($con, "INSERT INTO tbl_category_media(category_id, media_type, media_src) VALUES('$category_id', 'img', '" . $r . $img . "')");
                move_uploaded_file($_FILES['img']['tmp_name'][$key], "../site_image/category/" . $r . $img);
            }
        }
        setMessage('Category Successfully Edited', 'alert alert-success');
        if (isset($_POST['nsubmit'])) {
            redirect('categoryAdd.php');
            die();
        }
        redirect('admincategory.php');
        die();
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('admincategory.php');
    die();
}

function colorAdd($con) {
    $name = $_POST['name'];
    $code = $_POST['colorCode'];
    if (exec_query("INSERT INTO tbl_color(color_code, color) VALUES('$code', '$name')", $con)) {
        setMessage('Color Successfully Added', 'alert alert-success');
        if (isset($_POST['nsubmit'])) {
            redirect('colorAdd.php');
            die();
        }
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('color.php');
    die();
}

function colorEdit($con) {
    $id = $_POST['data1'];
    $name = $_POST['name'];
    $code = $_POST['colorCode'];
    if (exec_query("UPDATE tbl_color SET color_code = '$code', color = '$name' WHERE color_id = '$id'", $con)) {
        setMessage('Color Successfully Edited', 'alert alert-success');
        if (isset($_POST['nsubmit'])) {
            redirect('colorAdd.php');
            die();
        }
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('color.php');
    die();
}

function brandAdd($con) {
    $slug = addslashes($_POST['slug']);
    $flag = isset($_POST['flag']) ? "1" : "0";
    if (chkSlug('tbl_brand', $slug)) {
        setMessage('Slug already Exist. Try another.', 'alert alert-error');
        redirect('brandAdd.php');
        die();
    }

    $name = addslashes($_POST['name']);

    foreach ($_FILES['img']['name'] AS $key => $value) {
        $r = rand(10, 99);
        $img = str_replace(array("'", ' ', '"'), '', $_FILES['img']['name'][$key]);
        $type = $_FILES['img']['type'][$key];
        if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg') {
            $rs = exec_query("INSERT INTO tbl_brand(brand_name, brand_img, slug, flag,created_on) VALUES('$name', '" . $r . $img . "', '$slug','$flag' ,'" . date('c') . "')", $con);
        }
        if (isset($rs)) {
            move_uploaded_file($_FILES['img']['tmp_name'][$key], "../site_image/brand_logo/" . $r . $img);
        }
    }

    if (!isset($rs)) {
        $rs = exec_query("INSERT INTO tbl_brand(brand_name, slug, created_on) VALUES('$name', '$slug', '" . date('c') . "')", $con);
    }

    /* foreach($_FILES['img']['name'] AS $key => $value){
      $r = rand(10, 99);
      $img = str_replace(array("'", ' ', '"'), '', $_FILES['img']['name'][$key]);
      $type = $_FILES['img']['type'][$key];
      if($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg'){
      foreach($category AS $value){
      $rs = exec_query("INSERT INTO tbl_brand(category_id, brand_name, brand_img, slug, created_on) VALUES('$value', '$name', '".$r.$img."', '$slug', '".date('c')."')", $con);
      }
      }
      if(isset($rs)){ move_uploaded_file($_FILES['img']['tmp_name'][$key], "../site_image/brand_logo/".$r.$img); }
      }

      if(!isset($rs)){
      foreach($category AS $value){
      $rs = exec_query("INSERT INTO tbl_brand(category_id, brand_name, slug, created_on) VALUES('$value', '$name', '$slug', '".date('c')."')", $con);
      }
      } */

    if ($rs) {
        setMessage('Brand successfully added.', 'alert alert-success');
        if (isset($_POST['nsubmit'])) {
            redirect('brandAdd.php');
            die();
        }
        redirect('brand.php');
        die();
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('brandAdd.php');
    die();
}

function ColorEdit2($con) {
    $id = $_POST['data1'];

    $name = $_POST['name'];
    $code = $_POST['colorCode'];
    $dname = $_POST['dname'];

    $dataLast1 = $_POST['dataLast1'];
    $dataLast2 = $_POST['dataLast2'];

    if (exec_query("UPDATE tbl_color SET color_code = '$code', color = '$name', display_name = '$dname' WHERE color_id = '$id'", $con)) {
        setMessage('Color Successfully Edited', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('brandColorEdit.php?data1=' . $dataLast1 . '&data2=' . $dataLast2);
    die();
}

function brandEdit($con) {
    $oldSlug = $_POST['data2'];
    $dataImg = $_POST['dataImg'];
    $name = addslashes($_POST['name']);
    $slug = addslashes($_POST['slug']);
    $flag = isset($_POST['flag']) ? "1" : "0";
    $id = $_POST['data1'];
    //$category = $_POST['category'];

    $dataUp = '';
    if (isset($_FILES['img']['name']) && $_FILES['img']['name'] != '' && !empty($_FILES['img']['name'])) {
        $r = rand(10, 99);
        $img = str_replace(array("'", ' ', '"'), '', $_FILES['img']['name']);
        $type = $_FILES['img']['type'];
        if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg') {
            move_uploaded_file($_FILES['img']['tmp_name'], "../site_image/brand_logo/" . $r . $img);
            $dataUp = ", brand_img = '" . $r . $img . "'";
        } else {
            setMessage('Invalid Image', 'alert alert-error');
            redirect('brand.php');
            die();
        }
    }
    $rs = exec_query("UPDATE tbl_brand SET brand_name = '$name', flag='$flag',slug = '$slug' $dataUp WHERE brand_id = '$id'", $con);
    /* foreach($category AS $value){
      $chkRs = exec_query("SELECT brand_img FROM tbl_brand WHERE category_id = '$value' AND slug = '$oldSlug'", $con);
      if(mysql_num_rows($chkRs) > 0){
      $rs = exec_query("UPDATE tbl_brand SET brand_name = '$name', slug = '$slug' $dataUp WHERE category_id = '$value' AND slug = '$oldSlug'", $con);
      }
      else{
      $rs = exec_query("INSERT INTO tbl_brand(category_id, brand_name, brand_img, slug, created_on) VALUES('$value', '$name', '$dataIns', '$slug', '".date('c')."')", $con);
      }
      exec_query("UPDATE tbl_color SET brand = '$slug' WHERE brand = '$oldSlug'", $con);
      } */

    if ($rs) {
        setMessage('Brand successfully edited.', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    if (isset($_POST['nsubmit'])) {
        redirect('brandAdd.php');
        die();
    }
    redirect('brand.php');
    die();
}

function brandColorEdit($con) {
    $id = $_POST['data1'];
    $slug = $_POST['data2'];
    $name = $_POST['name'];
    $dname = addslashes($_POST['dname']);
    $code = $_POST['colorCode'];
    if (exec_query("INSERT INTO tbl_color(brand, color_code, color, display_name) VALUES('$id', '$code', '$name', '$dname')", $con)) {
        setMessage('Color Successfully Added', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('brandColorEdit.php?data1=' . $id . '&data2=' . $slug);
    die();
}

function productAdd($con) {
    /* for add similar */
    $slug = addslashes($_POST['slug']);
    $str = '';
    if (isset($_POST['dataCopy']) && $_POST['dataCopy'] != '') {
        $slug = addslashes($_POST['slug']) . rand(1, 99);
        $str = '&dataCopy=' . $_POST['dataCopy'];
    }

    if (chkSlug('tbl_product', $slug)) {
        setMessage('Slug already Exist. Try another.', 'alert alert-error');
        redirect('productAdd.php');
        die();
    }

    $sku = $_POST['sku'];
    $upc = 1; //$_POST['upc'];
    $chkSkuRs = exec_query("SELECT product_id FROM tbl_product WHERE product_sku = '$sku' and is_activate != 4", $con);
    //$chkUpcRs = exec_query("SELECT product_id FROM tbl_product WHERE product_upc = '$upc'", $con);
    if (mysqli_num_rows($chkSkuRs) > 0) { 
        setMessage('Failed, Product SKU must be unique!', 'alert alert-error');
        redirect('productAdd.php');
        die();
    }
    /* elseif(mysql_num_rows($chkUpcRs) > 0){
      setMessage('Failed, Product UPC must be unique!', 'alert alert-error'); redirect('productAdd.php'); die();
      } */

    // category validation
    if (isset($_POST['subsubcategory']) && $_POST['subsubcategory'] != 0 && $_POST['subsubcategory'] != '' && !empty($_POST['subsubcategory'])) {
        $cat = $_POST['subsubcategory'];
    } elseif (isset($_POST['subcategory']) && $_POST['subcategory'] != 0 && $_POST['subcategory'] != '' && !empty($_POST['subcategory'])) {
        $cat = $_POST['subcategory'];
    } elseif (isset($_POST['mainCategory']) && $_POST['mainCategory'] != 0 && $_POST['mainCategory'] != '' && !empty($_POST['mainCategory'])) {
        $cat = $_POST['mainCategory'];
    } else {
        setMessage('Some Error Occured in Category Selection!', 'alert alert-error');
        redirect('productAdd.php');
        die();
    }

    /* take all selected subcategory in temp subcat col for size */
    $tempSubCatValue = '';
    if (isset($_POST['subcategory']) && !empty($_POST['subcategory'])) {
        foreach ($_POST['subcategory'] AS $val) {
            $tempSubCatValue .= ($tempSubCatValue == '') ? $val : ',' . $val;
        }
    }

    $brand = $_POST['brand'];
    $name = addslashes($_POST['name']);

    $stock = '';
    if (isset($_POST['stock']) && $_POST['stock'] != '') {
        $stock = $_POST['stock'];
    }

    $qty = '';
    if (isset($_POST['qty']) && $_POST['qty'] != '') {
        $qty = $_POST['qty'];
    }

    $mcode = '';
    if (isset($_POST['mcode']) && $_POST['mcode'] != '') {
        $mcode = addslashes($_POST['mcode']);
    }

    $age_value = '';
    if (isset($_POST['age'])) {
        foreach ($_POST['age'] AS $value) {
            $age_value .= ($age_value == '') ? $value : ',' . $value;
        }
    }

    $group_value = '';
    if (isset($_POST['group'])) {
        foreach ($_POST['group'] AS $value) {
            $group_value .= ($group_value == '') ? $value : ',' . $value;
        }
    }

    $size_value = '';
    if (isset($_POST['size']) && $_POST['size'] != '' && isset($_POST['sizeUnit']) && $_POST['sizeUnit'] != '') {
        $size_value = $_POST['size'] . ' ' . $_POST['sizeUnit'];
    }
    /* old size if(isset($_POST['size'])){
      foreach($_POST['size'] AS $value){ $size_value .= ($size_value == '')?$value:','.$value; }
      } */

    $keyword = '';
    if (isset($_POST['keyword']) && $_POST['keyword'] != '') {
        $keyword = addslashes($_POST['keyword']);
    }

    $query = "INSERT INTO tbl_product(temp_subcategory, brand_id, product_name, slug, qty, product_sku, product_upc, manufacturer_code, user_group, age_group, size, keyword, stock_availability, created_on, created_by) VALUES('$tempSubCatValue', '$brand', '$name', '$slug', '$qty', '$sku', '$upc', '$mcode', '$group_value', '$age_value', '$size_value', '$keyword', '$stock', '" . date('c') . "', '" . $_SESSION['admin'] . "')";
    if (exec_query($query, $con)) {
        $product_id = mysqli_insert_id($con);

        // category insetrtion
        foreach ($cat AS $value) {
            if ($value != '' && $value != 0) {
                $cat_rs = exec_query("INSERT INTO tbl_product_category(product_id, category_id) VALUES('$product_id', '$value')", $con);
            }
        }

        setMessage('Product successfully added. Please give product description & usage.', 'alert alert-success');
        if (isset($_POST['color']) && !empty($_POST['color'])) {
            $_SESSION['color'] = $_POST['color'];
        } else {
            $_SESSION['color'] = array('#ffffff');
        }
        //$_SESSION['sessionProductId'] = $product_id;
        redirect('productAddDesc.php?data1=' . $product_id . $str);
        die();
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('productAdd.php');
    die();
}

function productAddDesc($con) {
    $pid = $_POST['data1'];

    $usage = '';
    if (isset($_POST['usage']) && $_POST['usage'] != '') {
        $usage = addslashes($_POST['usage']);
    }

    $summary = '';
    if (isset($_POST['summary']) && $_POST['summary'] != '') {
        $summary = addslashes($_POST['summary']);
    }

    $tagValue = '';
    if (isset($_POST['tag']) && $_POST['tag'] != '') {
        $tag = $_POST['tag'];
        foreach ($tag AS $val) {
            if ($val != '') {
                $tagValue .= ($tagValue == '') ? $val : ',' . $val;
            }
        }
    }

    $height = '';
    if (isset($_POST['height']) && $_POST['height'] != '') {
        $height = $_POST['height'];
    }
    $width = '';
    if (isset($_POST['width']) && $_POST['width'] != '') {
        $width = $_POST['width'];
    }

    $weight = '';
    $weightUnit = '';
    if (isset($_POST['weight']) && $_POST['weight'] != '' && isset($_POST['weightUnit']) && $_POST['weightUnit'] != '') {
        $weight = $_POST['weight'];
        $weightUnit = $_POST['weightUnit'];
    }
    $weight = $weight . ' ' . $weightUnit;

    $rs = exec_query("UPDATE tbl_product SET product_description = '$usage', product_summary = '$summary', tag = '" . addslashes($tagValue) . "', height = '$height', width = '$width', weight = '$weight' WHERE product_id = '$pid'", $con);

    /* for add similar */
    $str = '';
    if (isset($_POST['dataCopy']) && $_POST['dataCopy'] != '') {
        $str = '&dataCopy=' . $_POST['dataCopy'];
    }

    /* $text = $_POST['text'];
      //usage/steps if not blank
      define('LIMIT_PRODUCT_WIDTH',true);
      define('MAX_PRODUCT_IMAGE_WIDTH',480);
      define('THUMBNAIL_WIDTH',180);

      if(isset($_POST['text']) && !empty($_POST['text'])){
      foreach($_POST['text'] AS $key => $value){
      if($value != ''){
      $img = ''; $type = ''; $thumb = '';

      if(isset($_FILES['img']['name'][$key]) && !empty($_FILES['img']['name'][$key]) && $_FILES['img']['name'][$key] != ''){
      $type = $_FILES['img']['type'][$key];
      if($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg'){
      $thumbimg = uploadMultiProductImage('img', $key, '../site_image/usage/');
      $img = $thumbimg['image'];
      $thumb = $thumbimg['thumbnail'];
      }
      }
      mysql_query("INSERT INTO tbl_product_usage(product_id, img, thumb, text)
      VALUES('$pid', '".$img."', '".$thumb."', '".$value."')", $con);
      }
      }
      } */

    /* print_r($_POST);
      echo '<pre>';print_r($_FILES);echo '</pre>';
      die(); */

    /* foreach($_FILES['img']['name'] AS $imgKey => $value){
      $img = ''; $type = '';
      $type = $_FILES['img']['type'][$imgKey];
      if($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg'){
      $thumbimg = uploadMultiProductImage('img', $imgKey, '../site_image/usage/');
      $img = $thumbimg['image'];
      $thumb = $thumbimg['thumbnail'];
      mysql_query("INSERT INTO tbl_product_usage(product_id, img, thumb, text) VALUES('$pid', '".$img."', '".$thumb."', '".$text[$imgKey]."')", $con);
      }
      } */

    setMessage('Product description successfully added. Please give following information.', 'alert alert-success');
    redirect('productAddDetail.php?data1=' . $pid . $str);
    die();
}

function productAddDetail($con) {
    $pid = $_POST['data1'];
    $i = $_POST['i'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $rrp = $_POST['rrp'];
    $colorCode = $_POST['colorCode'];
    $upc = $_POST['upc'];

    $tempColorId = $_POST['tempColorId'];
    define('LIMIT_PRODUCT_WIDTH', true);
    define('MAX_PRODUCT_IMAGE_WIDTH', 1000);
    define('THUMBNAIL_WIDTH', 180);
    foreach ($colorCode AS $key => $value) {
        $colorId = '';
        if ($colorCode[$key] != '' && $price[$key] != '' && $i[$key] != '') {
            $rsColor = exec_query("INSERT INTO tbl_product_color(product_id, color_code) VALUES('$pid', '" . $colorCode[$key] . "') ", $con);
            if ($rsColor) {
                $colorId = mysqli_insert_id($con);
                // price operation
                $rsPrice = exec_query("INSERT INTO tbl_product_price(product_id, product_upc, product_price, qty, product_rrp, color_id) VALUES('$pid', '" . $upc[$key] . "', '" . $price[$key] . "', '" . $stock[$key] . "','" . $rrp[$key] . "', '$colorId')", $con);

                /* similar img operation
                  if(isset($_POST['dataCopy']) && $_POST['dataCopy'] != '' && isset($tempColorId[$key]) && $tempColorId[$key] != ''){
                  $dataCopy = $_POST['dataCopy'];
                  $rsCopyImg = exec_query("SELECT * FROM tbl_product_media WHERE product_id = '$dataCopy' AND color_id = '".$tempColorId[$key]."'", $con);
                  while($rowCopyImg = mysql_fetch_object($rsCopyImg)){
                  $imgRs = mysql_query("INSERT INTO tbl_product_media(product_id, color_id, media_type, media_src, media_thumb) VALUES('$pid', '$colorId', '".$rowCopyImg->media_type."', '".$rowCopyImg->media_src."', '".$rowCopyImg->media_thumb."')", $con);
                  }
                  } */

                //main img operation
                //print_r($_FILES['imgMain'.$i[$key]]);
                //echo '<br/><br/>';
                //print_r($_FILES['img'.$i[$key]]);
                if (isset($_FILES['imgMain' . $i[$key]]['name']) && $_FILES['imgMain' . $i[$key]]['name'] != '' && !empty($_FILES['imgMain' . $i[$key]])) {
                    $r = rand(10, 99);
                    $type = $_FILES['imgMain' . $i[$key]]['type'];
                    if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg') {
                        $thumbimg = uploadProductImage('imgMain' . $i[$key], '../site_image/product/');
                        $img = $thumbimg['image'];
                        $thumb = $thumbimg['thumbnail'];
                        $imgRsMain = mysqli_query($con, "INSERT INTO tbl_product_media(product_id, color_id, media_type, media_src, media_thumb, is_main) VALUES('$pid', '$colorId', 'img', '" . $img . "', '" . $thumb . "', 1)");
                    }
                }

                // img operation
                foreach ($_FILES['img' . $i[$key]]['name'] AS $imgKey => $value) {
                    $img = '';
                    $type = '';
                    $r = rand(10, 99);
                    $type = $_FILES['img' . $i[$key]]['type'][$imgKey];
                    if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg') {
                        $thumbimg = uploadMultiProductImage('img' . $i[$key], $imgKey, '../site_image/product/');
                        $img = $thumbimg['image'];
                        $thumb = $thumbimg['thumbnail'];
                        $imgRs = mysqli_query($con, "INSERT INTO tbl_product_media(product_id, color_id, media_type, media_src, media_thumb) VALUES('$pid', '$colorId', 'img', '" . $img . "', '" . $thumb . "')");
                    }
                }
                if (!isset($imgRsMain)) {
                    $imgg = 'product.png';
                    mysqli_query($con, "INSERT INTO tbl_product_media(product_id, color_id, media_type, media_src, media_thumb, is_main) VALUES('$pid', '$colorId', 'img', '" . $imgg . "', '" . $imgg . "', 1)");
                }
                // video operation
                foreach ($_FILES['video' . $i[$key]]['name'] AS $vidKey => $value) {
                    $vid = '';
                    $type = '';
                    $r = rand(10, 99);
                    $vid = str_replace(array("'", ' ', '"'), '', $_FILES['video' . $i[$key]]['name'][$vidKey]);
                    $type = $_FILES['video' . $i[$key]]['type'][$vidKey];
                    if ($type == 'video/mp4') {
                        $rsVid = mysqli_query($con, "INSERT INTO tbl_product_media(product_id, color_id, media_type, media_src) VALUES('$pid', '$colorId', 'video', '" . $r . $vid . "')");
                        if ($rsVid) {
                            move_uploaded_file($_FILES['video' . $i[$key]]['tmp_name'][$vidKey], "../site_image/productvideo/" . $r . $vid);
                        }
                    }
                }
            }
        }
    }
    //unset($_SESSION['sessionProductId']);
    unset($_SESSION['color']);
    if (isset($rsColor) && isset($rsPrice)) {
        if (isset($_POST['dsubmit'])) {
            exec_query("UPDATE tbl_product SET is_activate = 2 WHERE product_id = '$pid'", $con);
        } elseif (isset($_POST['psubmit'])) {
            exec_query("UPDATE tbl_product SET is_activate = 1 WHERE product_id = '$pid'", $con);
        }
        setMessage('Product successfully added.', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    if (isset($_POST['nsubmit'])) {
        redirect('productAdd.php');
        die();
    }
    redirect('product.php');
    die();
}

function productEdit($con) {
    $id = $_POST['data1'];
    $brand = $_POST['brand'];
    $slug = addslashes($_POST['slug']);

    if (isset($_POST['subsubcategory']) && $_POST['subsubcategory'] != 0 && $_POST['subsubcategory'] != '' && !empty($_POST['subsubcategory'])) {
        $cat = $_POST['subsubcategory'];
    } elseif (isset($_POST['subcategory']) && $_POST['subcategory'] != 0 && $_POST['subcategory'] != '' && !empty($_POST['subcategory'])) {
        $cat = $_POST['subcategory'];
    } elseif (isset($_POST['mainCategory']) && $_POST['mainCategory'] != 0 && $_POST['mainCategory'] != '' && !empty($_POST['mainCategory'])) {
        $cat = $_POST['mainCategory'];
    } else {
        setMessage('Some Error Occured in Category Selection!', 'alert alert-error');
        redirect('productAdd.php');
        die();
    }

    $name = $_POST['name'];

    $qty = '';
    if (isset($_POST['qty']) && $_POST['qty'] != '') {
        $qty = $_POST['qty'];
    }

    $stock = '';
    if (isset($_POST['stock']) && $_POST['stock'] != '') {
        $stock = $_POST['stock'];
    }

    $sku = $_POST['sku'];
    $upc = 1; //$_POST['upc'];

    $mcode = '';
    if (isset($_POST['mcode']) && $_POST['mcode'] != '') {
        $mcode = addslashes($_POST['mcode']);
    }

    $age_value = '';
    if (isset($_POST['age'])) {
        foreach ($_POST['age'] AS $value) {
            $age_value .= ($age_value == '') ? $value : ',' . $value;
        }
    }

    $group_value = '';
    if (isset($_POST['group'])) {
        foreach ($_POST['group'] AS $value) {
            $group_value .= ($group_value == '') ? $value : ',' . $value;
        }
    }

    $size_value = '';
    if (isset($_POST['size']) && $_POST['size'] != '' && isset($_POST['sizeUnit']) && $_POST['sizeUnit'] != '') {
        $size_value = $_POST['size'] . ' ' . $_POST['sizeUnit'];
    }

    $keyword = addslashes($_POST['keyword']);
    /* if(isset($_POST['size'])){
      foreach($_POST['size'] AS $value){ $size_value .= ($size_value == '')?$value:','.$value; }
      } */

    /* FOR TEMP PRODUCT FIELD */
    $tempSubCatValue = '';
    if (isset($_POST['subcategory']) && !empty($_POST['subcategory'])) {
        foreach ($_POST['subcategory'] AS $val) {
            $tempSubCatValue .= ($tempSubCatValue == '') ? $val : ',' . $val;
        }
    }

    $mainquery = "UPDATE tbl_product SET temp_subcategory = '$tempSubCatValue', brand_id = '$brand', keyword = '" . str_replace("'", "&#39;", $keyword) . "', product_name = '" . str_replace("'", "&#39;", $name) . "', slug = '$slug', user_group = '$group_value', age_group = '$age_value', qty = '$qty', product_sku = '$sku', product_upc = '$upc', manufacturer_code = '$mcode', size = '$size_value', stock_availability = '$stock', modified_on = '" . date('c') . "', modified_by = '" . $_SESSION['admin'] . "' WHERE product_id = '$id'";
    $rs = exec_query($mainquery, $con);
    if ($rs) {
        // category insetrtion
        exec_query("DELETE FROM tbl_product_category WHERE product_id = '$id'", $con);
        foreach ($cat AS $value) {
            $cat_rs = exec_query("INSERT INTO tbl_product_category(product_id, category_id) VALUES('$id', '$value')", $con);
        }
        setMessage('Product successfully edited.', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('productEdit.php?data1=' . $id . '&data2=' . $slug . '&qty=' . $qty);
    die();
}

function productEditDesc($con) {
    $id = $_POST['data1'];
    $slug = $_POST['data2'];
    $usage = addslashes($_POST['usage']);
    $summary = addslashes($_POST['summary']);
    //$keyword = addslashes($_POST['keyword']);

    $tagValue = '';
    if (isset($_POST['tag'])) {
        foreach ($_POST['tag'] AS $val) {
            if ($val != '') {
                $tagValue .= ($tagValue == '') ? $val : ',' . $val;
            }
        }
    }

    $height = '';
    if (isset($_POST['height']) && $_POST['height'] != '') {
        $height = $_POST['height'];
    }
    $width = '';
    if (isset($_POST['width']) && $_POST['width'] != '') {
        $width = $_POST['width'];
    }

    $weight = '';
    $weightUnit = '';
    if (isset($_POST['weight']) && $_POST['weight'] != '' && isset($_POST['weightUnit']) && $_POST['weightUnit'] != '') {
        $weight = $_POST['weight'];
        $weightUnit = $_POST['weightUnit'];
    }
    $weight = $weight . ' ' . $weightUnit;

    $rs = exec_query("UPDATE tbl_product SET product_description = '$usage', product_summary = '$summary', modified_on = '" . date('c') . "', modified_by = '" . $_SESSION['admin'] . "', tag = '" . addslashes($tagValue) . "', height = '$height', width = '$width', weight = '$weight' WHERE product_id = '$id'", $con);
    if ($rs) {
        setMessage('Product successfully edited.', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('productEdit.php?data1=' . $id . '&data2=' . $slug);
    die();
}

function productStepImgEdit($con) {
    $id = $_POST['data1'];
    $slug = $_POST['data2'];
    $text = $_POST['text'];

    define('LIMIT_PRODUCT_WIDTH', true);
    define('MAX_PRODUCT_IMAGE_WIDTH', 480);
    define('THUMBNAIL_WIDTH', 180);

    if (isset($_POST['text']) && !empty($_POST['text'])) {
        foreach ($_POST['text'] AS $key => $value) {
            $img = '';
            $type = '';
            $thumb = '';

            if (isset($_FILES['img']['name'][$key]) && !empty($_FILES['img']['name'][$key]) && $_FILES['img']['name'][$key] != '') {
                $type = $_FILES['img']['type'][$key];
                if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg') {
                    $thumbimg = uploadMultiProductImage('img', $key, '../site_image/usage/');
                    $img = $thumbimg['image'];
                    $thumb = $thumbimg['thumbnail'];
                }
            }
            $rsImg = mysqli_query($con, "INSERT INTO tbl_product_usage(product_id, img, thumb, text)
							VALUES('$id', '" . $img . "', '" . $thumb . "', '" . $value . "')");
        }
    }

    /* foreach($_FILES['img']['name'] AS $imgKey => $value){
      $img = ''; $type = '';
      $type = $_FILES['img']['type'][$imgKey];
      if($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg'){
      $thumbimg = uploadMultiProductImage('img', $imgKey, '../site_image/usage/');
      $img = $thumbimg['image'];
      $thumb = $thumbimg['thumbnail'];
      $rsImg = mysql_query("INSERT INTO tbl_product_usage(product_id, img, thumb, text) VALUES('$id', '".$img."', '".$thumb."', '".$text[$imgKey]."')", $con);
      }
      } */
    if (isset($rsImg)) {
        exec_query("UPDATE tbl_product SET modified_on = '" . date('c') . "', modified_by = '" . $_SESSION['admin'] . "' WHERE product_id = '$id'", $con);
        setMessage('Product Usage Images successfully added.', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('productEdit.php?data1=' . $id . '&data2=' . $slug);
    die();
}

function productEditDetail($con) {
    $pid = $_POST['data1'];
    $slug = $_POST['data2'];
    $i = $_POST['i'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $backorderstock = $_POST['backorderstock'];
    $cost = $_POST['cost'];
    $rrp = $_POST['rrp'];
    $upc = $_POST['upc'];
    $colorCode = $_POST['colorCode'];
    $color_id = $_POST['color_id'];
    $isVideo = $_POST['isVideo']; // used to chk if video exist, update, ow insert
    define('LIMIT_PRODUCT_WIDTH', true);
    define('MAX_PRODUCT_IMAGE_WIDTH', 480);
    define('THUMBNAIL_WIDTH', 180);
    foreach ($colorCode AS $key => $value) {
        if ($colorCode[$key] != '' && $price[$key] != '' && $i[$key] != '') {
            // main op
            $rsPrice = exec_query("UPDATE tbl_product_price SET product_upc = '" . $upc[$key] . "', cost = '" . $cost[$key] . "', product_price = '" . $price[$key] . "', qty = '" . $stock[$key] . "', backorder_qty = '" . $backorderstock[$key] . "', product_rrp = '" . $rrp[$key] . "' WHERE product_id = '$pid' AND color_id = '" . $color_id[$key] . "' ", $con);

            /* chk that if blank img is added */
            $chkCondi = "WHERE product_id = '$pid' AND color_id = '" . $color_id[$key] . "' AND media_src = 'product.png'";
            $rsChkBlnkImg = mysqli_query($con, "SELECT recid FROM tbl_product_media $chkCondi");


            //main img operation
            if (isset($_FILES['imgMain' . $i[$key]]['name']) && $_FILES['imgMain' . $i[$key]]['name'] != '' && !empty($_FILES['imgMain' . $i[$key]])) {
                $r = rand(10, 99);
                $type = $_FILES['imgMain' . $i[$key]]['type'];
                if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg') {
                    $thumbimg = uploadProductImage('imgMain' . $i[$key], '../site_image/product/');
                    $img = $thumbimg['image'];
                    $thumb = $thumbimg['thumbnail'];

                    mysqli_query($con, "DELETE FROM tbl_product_media WHERE product_id = '$pid' AND color_id = '" . $color_id[$key] . "' AND media_type = 'img' AND is_main = 1");

                    $imgRsMain = mysqli_query($con, "INSERT INTO tbl_product_media(product_id, color_id, media_type, media_src, media_thumb, is_main) VALUES('$pid', '" . $color_id[$key] . "', 'img', '" . $img . "', '" . $thumb . "', 1)");
                }
            }

            // img operation
            foreach ($_FILES['img' . $i[$key]]['name'] AS $imgKey => $value) {
                $img = '';
                $type = '';
                $r = rand(10, 99);
                $type = $_FILES['img' . $i[$key]]['type'][$imgKey];
                if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg') {
                    $thumbimg = uploadMultiProductImage('img' . $i[$key], $imgKey, '../site_image/product/');
                    $img = $thumbimg['image'];
                    $thumb = $thumbimg['thumbnail'];
                    $rsImg = mysqli_query($con, "INSERT INTO tbl_product_media(product_id, color_id, media_type, media_src, media_thumb) VALUES('$pid', '" . $color_id[$key] . "', 'img', '" . $img . "', '" . $thumb . "')");
                }
            }

            if (isset($imgRsMain) && mysqli_num_rows($rsChkBlnkImg) > 0) {
                mysqli_query($con, "DELETE FROM tbl_product_media $chkCondi");
            }

            // video operation
            foreach ($_FILES['video' . $i[$key]]['name'] AS $vidKey => $value) {
                $vid = '';
                $type = '';
                $r = rand(10, 99);
                $vid = str_replace(array("'", ' ', '"'), '', $_FILES['video' . $i[$key]]['name'][$vidKey]);
                $type = $_FILES['video' . $i[$key]]['type'][$vidKey];
                if ($type == 'video/mp4') {

                    if (isset($isVideo[$key]) && $isVideo[$key] != '' && $isVideo[$key] != 0) {
                        $queryVid = "UPDATE tbl_product_media SET media_src = '" . $r . $vid . "' WHERE recid = '" . $isVideo[$key] . "'";
                    } else {
                        $queryVid = "INSERT INTO tbl_product_media(product_id, color_id, media_type, media_src) VALUES('$pid', '" . $color_id[$key] . "', 'video', '" . $r . $vid . "')";
                    }
                    if (mysqli_query($con, $queryVid)) {
                        move_uploaded_file($_FILES['video' . $i[$key]]['tmp_name'][$vidKey], "../site_image/productvideo/" . $r . $vid);
                    }
                }
            }
        }
    }
    if (isset($rsPrice) || isset($rsImg)) {
        exec_query("UPDATE tbl_product SET modified_on = '" . date('c') . "', modified_by = '" . $_SESSION['admin'] . "' WHERE product_id = '$pid'", $con);
        setMessage('Product successfully edited.', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('productEdit.php?data1=' . $pid . '&data2=' . $slug);
    die();
}

function productColorAdd($con) {
    $pid = $_POST['data1'];
    $_SESSION['color'] = $_POST['color'];
    setMessage('Please give following information.', 'alert alert-success');
    redirect('productAddDetail.php?data1=' . $pid);
    die();
}

function updateProductCategory($con) {
    $oldCat = $_POST['oldCat'];
    $chk = $_POST['chk'];

    if (isset($_POST['subsubcategory']) && $_POST['subsubcategory'] != 0 && $_POST['subsubcategory'] != '' && !empty($_POST['subsubcategory'])) {
        $cat = $_POST['subsubcategory'];
    } elseif (isset($_POST['subcategory']) && $_POST['subcategory'] != 0 && $_POST['subcategory'] != '' && !empty($_POST['subcategory'])) {
        $cat = $_POST['subcategory'];
    } elseif (isset($_POST['mainCategory']) && $_POST['mainCategory'] != 0 && $_POST['mainCategory'] != '' && !empty($_POST['mainCategory'])) {
        $cat = $_POST['mainCategory'];
    } else {
        setMessage('Some Error Occured in Category Selection!', 'alert alert-error');
        redirect('admincategory.php');
        die();
    }

    foreach ($chk AS $key1 => $productId) {
        if ($productId != '') {
            // category for each loop
            foreach ($cat AS $key2 => $catId) {
                $chkRs = exec_query("SELECT recid FROM tbl_product_category WHERE product_id = '$productId' AND category_id = '$catId'", $con);
                if (!mysqli_num_rows($chkRs)) {
                    exec_query("INSERT INTO tbl_product_category(product_id, category_id) VALUES('$productId', '$catId')", $con);
                }
            }
            // delete old category
            exec_query("DELETE FROM tbl_product_category WHERE product_id = '$productId' AND category_id = '$oldCat'", $con);
        }
    }
    setMessage('Product Successfully moved to new categories.', 'alert alert-success');
    redirect('admincategory.php');
    die();
}

function assignMoreCategories($con) {
    if (isset($_POST['chk'])) {
        $chk = $_POST['chk'];
    } else {
        $chk = array();
    }
    if (isset($_POST['subsubcategory']) && $_POST['subsubcategory'] != 0 && $_POST['subsubcategory'] != '' && !empty($_POST['subsubcategory'])) {
        $cat = $_POST['subsubcategory'];
    } elseif (isset($_POST['subcategory']) && $_POST['subcategory'] != 0 && $_POST['subcategory'] != '' && !empty($_POST['subcategory'])) {
        $cat = $_POST['subcategory'];
    } elseif (isset($_POST['mainCategory']) && $_POST['mainCategory'] != 0 && $_POST['mainCategory'] != '' && !empty($_POST['mainCategory'])) {
        $cat = $_POST['mainCategory'];
    } else {
        setMessage('Some Error Occured in Category Selection!', 'alert alert-error');
        redirect('product.php');
        die();
    }

    foreach ($chk AS $key1 => $productId) {
        if ($productId != '') {
            // category for each loop
            foreach ($cat AS $key2 => $catId) {
                $chkRs = exec_query("SELECT recid FROM tbl_product_category WHERE product_id = '$productId' AND category_id = '$catId'", $con);
                if (!mysqli_num_rows($chkRs)) {
                    exec_query("INSERT INTO tbl_product_category(product_id, category_id) VALUES('$productId', '$catId')", $con);
                }
            }
        }
    }
    setMessage('Product Successfully moved to new categories.', 'alert alert-success');
    redirect('product.php');
    die();
}

function sizeAdd($con) {
    //$subcategory = $_POST['subcategory'];
    $size = addslashes($_POST['size']);
    //$query = "INSERT INTO tbl_size(subcategory_id, size) VALUES('$subcategory', '$size')";
    $query = "INSERT INTO tbl_size(size) VALUES('$size')";
    if (exec_query($query, $con)) {
        setMessage('Size successfully added.', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    if (isset($_POST['nsubmit'])) {
        redirect('sizeAdd.php');
        die();
    }
    redirect('size.php');
    die();
}

function sizeEdit($con) {
    $id = $_POST['data1'];
    //$subcategory = $_POST['subcategory'];
    $size = addslashes($_POST['size']);
    //$query = "UPDATE tbl_size SET subcategory_id = '$subcategory', size = '$size' WHERE size_id = '$id'";
    $query = "UPDATE tbl_size SET size = '$size' WHERE size_id = '$id'";
    if (exec_query($query, $con)) {
        setMessage('Size successfully edited.', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    if (isset($_POST['nsubmit'])) {
        redirect('sizeAdd.php');
        die();
    }
    redirect('sizeEdit.php?data1=' . $id);
    die();
}

function managerAdd($con) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['cpassword']);
    //$originPass = $_POST['password'];
    if ($password == $cpassword) {
        $query = "INSERT INTO `admin` (`username`, `password`, `email`, `atype`) VALUES ('$username', '$password', '$email', 1)";
        if (exec_query($query, $con)) {
            $id = mysqli_insert_id($con);
            setMessage('Sub Admin Successfully Registered', 'alert alert-success');
            redirect('managerPermission.php?data1=' . $id);
            die();
        } else {
            setMessage('Some Error Occured, Try Again..', 'alert alert-error');
        }
    } else {
        setMessage('Please match the password and confirm password.', 'alert alert-error');
    }
    redirect('manager.php');
    die();
}

function setPermission($con) {
    global $permissionArray;
    $id = $_POST['id'];

    // temp array for alag wale chkbox
    $tempArr = array(
        'cms' => 'CMS',
        'tax' => 'Tax',
        'size' => 'Size',
        'config' => 'Configuration',
        'tag' => 'Tag',
        'stag' => 'Search Tag',
        'emailtemp' => 'Email Template',
        'newsletter' => 'Newsletter'
    );

    $del = mysqli_query($con, "DELETE FROM tbl_permission WHERE user_id = '$id'");
    foreach ($permissionArray AS $key => $val) {
        if (isset($_POST[$key])) {

            $add = '';
            $edit = '';
            $read = '';
            $status = '';
            if (isset($_POST[$key . 'add']) && $_POST[$key . 'add'] != '') {
                $add = 1;
            }
            if (isset($_POST[$key . 'edit']) && $_POST[$key . 'edit'] != '') {
                $edit = 1;
            }
            if (isset($_POST[$key . 'read']) && $_POST[$key . 'read'] != '') {
                $read = 1;
            }
            if (isset($_POST[$key . 'status']) && $_POST[$key . 'status'] != '') {
                $status = 1;
            }

            // skip some rights temp wale
            if (in_array($val, $tempArr)) {
                continue;
            }

            $ins = mysqli_query($con, "INSERT INTO tbl_permission(user_id, permission, tbl_permission.add, edit, tbl_permission.read, status) VALUES('$id', '" . $_POST[$key] . "', '$add', '$edit', '$read', '$status')");

            if ($key == 'sysConfig') {
                foreach ($tempArr AS $key1 => $val1) {
                    $ins = mysqli_query($con, "INSERT INTO tbl_permission(user_id, permission, tbl_permission.add, edit, tbl_permission.read, status) VALUES('$id', '" . $key1 . "', '$add', '$edit', '$read', '$status')");
                }
                break;
            }
        }
    }
    setMessage('Permissions successfully updated.', 'alert alert-success');
    if (isset($_POST['nsubmit'])) {
        redirect('managerAdd.php');
        die();
    }
    redirect('managerPermission.php?data1=' . $id);
    die();
}

function promotionAdd($con) {
    $slug = addslashes($_POST['slug']);
    if (chkSlug('tbl_promotion', $slug)) {
        setMessage('Slug already Exist. Try another.', 'alert alert-error');
        redirect('promotionAdd.php');
        die();
    }

    $ccode = $_POST['ccode'];
    $type = $_POST['data'];
    $amType = $_POST['amType'];
    $cvalue = $_POST[$amType . 'Text'];
    $title = addslashes($_POST['title']);

    $fdate = $_POST['fdate'];
    $ftime = $_POST['ftime'];
    $tdate = $_POST['tdate'];
    $ttime = $_POST['ttime'];
    $fdt = $fdate . ' ' . $ftime . ':00';
    $tdt = $tdate . ' ' . $ttime . ':00';

    $fea = 0;
    $pimg = '';
    $bimg = '';
    define('LIMIT_PRODUCT_WIDTH', true);
    define('MAX_PRODUCT_IMAGE_WIDTH', 480);
    define('THUMBNAIL_WIDTH', 180);
    if (isset($_POST['featured']) && $_POST['featured'] != '') {
        $fea = 1;
        $pthumbimg = uploadProductImage('proImg', '../site_image/promotion/');
        $pimg = $pthumbimg['image'];
        $pthumb = $pthumbimg['thumbnail'];
        $bthumbimg = uploadProductImage('bgImg', '../site_image/promotion/');
        $bimg = $bthumbimg['image'];
        $bthumb = $bthumbimg['thumbnail'];
    }
    $query = "INSERT INTO `tbl_promotion`(`title`, `slug`, `promo_code`, `promo_type`, `percent_or_amount`, `promo_value`, bg_img, product_img, start_date, end_date, created_on, is_featured) VALUES ('$title', '$slug', '$ccode', '$type', '$amType', '$cvalue', '$bimg', '$pimg', '$fdt', '$tdt', '" . date('c') . "', '$fea')";
    if (exec_query($query, $con)) {
        $promoId = mysqli_insert_id($con);
        if ($type != 'allPro') {
            if ($type == 'allCat') {
                $cat_value = '';
                if (isset($_POST['subsubcategory']) && !empty($_POST['subsubcategory'])) {
                    $cat = $_POST['subsubcategory'];
                } elseif (isset($_POST['subcategory']) && !empty($_POST['subcategory'])) {
                    $cat = $_POST['subcategory'];
                } elseif (isset($_POST['mainCategory']) && !empty($_POST['mainCategory'])) {
                    $cat = $_POST['mainCategory'];
                } else {
                    setMessage('Some Error Occured in Category Selection!', 'alert alert-error');
                    redirect('promotiontAdd.php');
                    die();
                }
                foreach ($cat AS $value) {
                    $cat_value .= ($cat_value == '') ? $value : ',' . $value;
                }
                $q = "INSERT INTO tbl_promotion_detail(promo_id, ids) VALUES('$promoId', '$cat_value')";

                /* get product id from cats */
                /* old query $getProQ = "SELECT product_id FROM tbl_product_category WHERE category_id IN ($cat_value)"; */
                $catQ = "SELECT category_id FROM `tbl_category` WHERE (parent_id IN ($cat_value)) OR (superparent_id IN ($cat_value)) OR (category_id IN ($cat_value))";
                $catRs = exec_query($catQ, $con);
                $catArray = array();
                while ($catRow = mysqli_fetch_object($catRs)) {
                    $catArray[] = $catRow->category_id;
                }
                $categoriesAll = implode(',', $catArray);
                //$condi .= " AND tpcat.category_id IN ($categories)";
                $getProQ = "SELECT product_id FROM tbl_product_category WHERE category_id IN ($categoriesAll)";
            } elseif ($type == 'allBrand') {
                // cat operation
                $cat_value = '';
                $categoriesAll = '';
                $condPlus = '';
                $cat = array();
                if (isset($_POST['subsubcategory']) && !empty($_POST['subsubcategory'])) {
                    $cat = $_POST['subsubcategory'];
                } elseif (isset($_POST['subcategory']) && !empty($_POST['subcategory'])) {
                    $cat = $_POST['subcategory'];
                } elseif (isset($_POST['mainCategory']) && !empty($_POST['mainCategory'])) {
                    $cat = $_POST['mainCategory'];
                }
                foreach ($cat AS $value) {
                    $cat_value .= ($cat_value == '') ? $value : ',' . $value;
                }

                // now in brand + cat, fetch all child cats, and then create condition - naya wala, bt first chk cat come or not */
                if ($cat_value != '') {
                    $catQ = "SELECT category_id FROM `tbl_category` WHERE (parent_id IN ($cat_value)) OR (superparent_id IN ($cat_value)) OR (category_id IN ($cat_value))";
                    $catRs = exec_query($catQ, $con);
                    $catArray = array();
                    while ($catRow = mysqli_fetch_object($catRs)) {
                        $catArray[] = $catRow->category_id;
                    }
                    $categoriesAll = implode(',', $catArray);
                }

                $br_value = '';
                $ib = 1;
                foreach ($_POST['brand'] AS $value) {
                    // brand id operation
                    $br_value .= ($br_value == '') ? $value : ',' . $value;

                    $condPlus .= ($ib == 1) ? '' : ' OR ';
                    $condPlus .= "(tp.brand_id = $value AND (";
                    // category opearation
                    if ($categoriesAll != '') {
                        $catArr = explode(',', $categoriesAll);
                        $ic = 1;
                        foreach ($catArr AS $catt) {
                            $condPlus .= ($ic == 1) ? '' : ' OR ';
                            $condPlus .= "tpc.category_id = $catt";
                            $ic++;
                        }
                        $condPlus .= ' )) ';
                    }
                    $ib++;
                }

                $q = "INSERT INTO tbl_promotion_detail(promo_id, category_id, ids) VALUES('$promoId', '$cat_value', '$br_value')";

                /* get product id from cat+brand
                  old condi - //tp.brand_id IN ($br_value) AND tpc.category_id IN ($cat_value) */
                if ($cat_value != '') {
                    $getProQ = "SELECT DISTINCT(tp.product_id) FROM tbl_product tp
					LEFT JOIN tbl_product_category tpc ON tpc.product_id = tp.product_id WHERE $condPlus";
                } else {
                    $getProQ = "SELECT product_id FROM tbl_product WHERE brand_id IN ($br_value)";
                }
            } elseif ($type == 'parPro') {
                $p_value = '';
                foreach ($_POST['product'] AS $value) {
                    $p_value .= ($p_value == '') ? $value : ',' . $value;
                }
                $q = "INSERT INTO tbl_promotion_detail(promo_id, ids) VALUES('$promoId', '$p_value')";

                /* get product id from products */
                $getProQ = "SELECT product_id FROM tbl_product WHERE product_id IN ($p_value)";
            }
            exec_query($q, $con);

            //echo $getProQ;
            $productIdPromotion = array();
            $getProRs = exec_query($getProQ, $con);
            while ($getPro = mysqli_fetch_object($getProRs)) {
                $productIdPromotion[] = $getPro->product_id;
            }
        } else {
            $productIdPromotion[] = 'all';
        } /* Array ( [0] => all ) */

        /* cal the function where code of mail is written */
        sendMailForWishlistProductPromotion($promoId, $productIdPromotion, $con);

        setMessage('Promotion Successfully Added', 'alert alert-success');
        if (isset($_POST['nsubmit'])) {
            redirect('promotionAdd.php');
            die();
        }
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('promotion.php');
    die();
}

function sendMailForWishlistProductPromotion($promoId, $productIdPromotion, $con) {
    // first chk if promotion is for all product
    if (in_array('all', $productIdPromotion)) {
        $qq = "SELECT DISTINCT(tw.user_id), tu.email, tu.username FROM tbl_user_wishlist tw LEFT JOIN tbl_user tu ON tu.user_id = tw.user_id";
    } else {
        $pids = implode(',', $productIdPromotion);
        $qq = "SELECT DISTINCT(tw.user_id), tu.email FROM tbl_user_wishlist tw LEFT JOIN tbl_user tu ON tu.user_id = tw.user_id WHERE tw.product_id IN ($pids)";
    }
    $rs = exec_query($qq, $con);
    if (mysqli_num_rows($rs)) {
        $emails = array();
        while ($row = mysqli_fetch_object($rs)) {
            $emails[] = $row->email;
        }

        /* fetch promotion details */
        $rsPromo = exec_query("SELECT * FROM tbl_promotion WHERE promo_id = '$promoId'", $con);
        $promotion = mysqli_fetch_object($rsPromo);
        if ($promotion->percent_or_amount == 'percent') {
            $detail = "FLAT $promotion->promo_value % OFF !!!";
        } elseif ($promotion->percent_or_amount == 'amount') {
            $detail = "SAVE $ $promotion->promo_value !!!";
        }


        /* fetch emaiul template */
        $rsEmail = exec_query("SELECT * FROM tbl_email_template WHERE type = 'promotion'", $con);
        $rowEmail = mysqli_fetch_object($rsEmail);
        $content = $rowEmail->content;

        $contentHTML = html_entity_decode($content);
        $contentHTML = str_replace('{jhm :', '', $contentHTML); // replace all '{jhm : '
        $arraySearch = array(' promotion_title}', ' promotion_detail}'); // isko replace krna h
        $arrayReplace = array($promotion->title, $detail); // isse replace krna h
        $content = str_replace($arraySearch, $arrayReplace, $contentHTML); // yha milega sb
        // now send mail
        sendMail('New Promotion!!! Check it out on ' . siteName, $content, $emails, $con);
    }
}

function promotionEdit($con) {
    $couponId = $_POST['data1'];
    $ccode = $_POST['ccode'];
    $type = $_POST['data'];
    $amType = $_POST['amType'];
    $cvalue = $_POST[$amType . 'Text'];
    $title = addslashes($_POST['title']);
    $slug = addslashes($_POST['slug']);
    $fdate = $_POST['fdate'];
    $ftime = $_POST['ftime'];
    $tdate = $_POST['tdate'];
    $ttime = $_POST['ttime'];
    $fdt = $fdate . ' ' . $ftime . ':00';
    $tdt = $tdate . ' ' . $ttime . ':00';

    $fea = 0;
    $condi = '';
    define('LIMIT_PRODUCT_WIDTH', true);
    define('MAX_PRODUCT_IMAGE_WIDTH', 480);
    define('THUMBNAIL_WIDTH', 180);
    if (isset($_POST['featured']) && $_POST['featured'] != '') {
        $fea = 1;
        if (isset($_FILES['proImg']) && isset($_FILES['proImg']['name']) && $_FILES['proImg']['name'] != '') {
            $pthumbimg = uploadProductImage('proImg', '../site_image/promotion/');
            $pimg = $pthumbimg['image'];
            $pthumb = $pthumbimg['thumbnail'];
            $condi = ", product_img = '$pimg'";
        }
        if (isset($_FILES['bgImg']) && isset($_FILES['bgImg']['name']) && $_FILES['bgImg']['name'] != '') {
            $bthumbimg = uploadProductImage('bgImg', '../site_image/promotion/');
            $bimg = $bthumbimg['image'];
            $bthumb = $bthumbimg['thumbnail'];
            $condi .= ", bg_img = '$bimg'";
        }
    }

    $query = "UPDATE `tbl_promotion` SET `title` = '$title', slug = '$slug', `promo_code` = '$ccode', `promo_type` = '$type', `percent_or_amount` = '$amType', `promo_value` = '$cvalue', start_date = '$fdt', end_date = '$tdt', is_featured = '$fea' $condi WHERE promo_id = '$couponId'";
    if (exec_query($query, $con)) {
        if ($type != 'allPro') {
            if ($type == 'allCat') {
                $cat_value = '';
                if (isset($_POST['subsubcategory']) && !empty($_POST['subsubcategory'])) {
                    $cat = $_POST['subsubcategory'];
                } elseif (isset($_POST['subcategory']) && !empty($_POST['subcategory'])) {
                    $cat = $_POST['subcategory'];
                } elseif (isset($_POST['mainCategory']) && !empty($_POST['mainCategory'])) {
                    $cat = $_POST['mainCategory'];
                }
                foreach ($cat AS $value) {
                    $cat_value .= ($cat_value == '') ? $value : ',' . $value;
                }
                $q = "UPDATE tbl_promotion_detail SET ids = '$cat_value' WHERE promo_id = '$couponId'";
            } elseif ($type == 'allBrand') {
                // cat operation
                $cat_value = '';
                $cat = array();
                if (isset($_POST['subsubcategory']) && !empty($_POST['subsubcategory'])) {
                    $cat = $_POST['subsubcategory'];
                } elseif (isset($_POST['subcategory']) && !empty($_POST['subcategory'])) {
                    $cat = $_POST['subcategory'];
                } elseif (isset($_POST['mainCategory']) && !empty($_POST['mainCategory'])) {
                    $cat = $_POST['mainCategory'];
                }
                foreach ($cat AS $value) {
                    $cat_value .= ($cat_value == '') ? $value : ',' . $value;
                }

                $br_value = '';
                foreach ($_POST['brand'] AS $value) {
                    $br_value .= ($br_value == '') ? $value : ',' . $value;
                }

                $q = "UPDATE tbl_promotion_detail SET category_id = '$cat_value', ids = '$br_value' WHERE promo_id = '$couponId'";
            } elseif ($type == 'parPro') {
                $p_value = '';
                foreach ($_POST['product'] AS $value) {
                    $p_value .= ($p_value == '') ? $value : ',' . $value;
                }
                $q = "UPDATE tbl_promotion_detail SET ids = '$p_value' WHERE promo_id = '$couponId'";
            }
            exec_query($q, $con);
        }setMessage('Promotion Successfully Edited', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    if (isset($_POST['nsubmit'])) {
        redirect('promotionAdd.php');
        die();
    }
    redirect('promotionEdit.php?data1=' . $couponId);
    die();
}

function dealBannerImage($con) {
    $r = rand(10, 99);
    $img = $_FILES['img']['name'];
    $imgNm = $r . $img;
    $type = $_FILES['img']['type'];
    if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/jpg') {
        $rs = mysqli_query($con, "UPDATE tbl_config SET other = '$imgNm' WHERE type = 'dealBanner'");
        if ($rs) {
            move_uploaded_file($_FILES['img']['tmp_name'], "../site_image/promotion/" . $imgNm);
            setMessage('Deal Banner Image Successfully Changed', 'alert alert-success');
        } else {
            setMessage('Some Error Occured, Try Again..', 'alert alert-error');
        }
    } else {
        setMessage('Invalid Image!', 'alert alert-error');
    }
    redirect('adminDeal.php');
    die();
}

function masterPromotionAdd($con) {
    $promo_id = $_POST['promo'];
    define('LIMIT_PRODUCT_WIDTH', true);
    define('MAX_PRODUCT_IMAGE_WIDTH', 1600);
    define('THUMBNAIL_WIDTH', 125);
    $thumbimg = uploadProductImage('img', '../site_image/promotion/');
    $img = $thumbimg['image'];
    $thumb = $thumbimg['thumbnail'];
    $q = "INSERT INTO tbl_promotion_master(promo_id, banner, thumb, created_on) VALUES('$promo_id', '$img', '$thumb', '" . date('c') . "')";
    if (exec_query($q, $con)) {
        setMessage('Master Promotion Successfully Added', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('promotionMaster.php');
    die();
}

function masterPromotionEdit($con) {
    $id = $_POST['data1'];
    $promo_id = $_POST['promo'];
    $condi = '';
    if (isset($_FILES['img']['name']) && !empty($_FILES['img']) && $_FILES['img']['name'] != '') {
        define('LIMIT_PRODUCT_WIDTH', true);
        define('MAX_PRODUCT_IMAGE_WIDTH', 1600);
        define('THUMBNAIL_WIDTH', 125);
        $thumbimg = uploadProductImage('img', '../site_image/promotion/');
        $img = $thumbimg['image'];
        $thumb = $thumbimg['thumbnail'];
        $condi = ", banner = '$img', thumb = '$thumb'";
    }
    $q = "UPDATE tbl_promotion_master SET promo_id = '$promo_id' $condi WHERE recid = '$id'";
    if (exec_query($q, $con)) {
        setMessage('Promotion Successfully Edited', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('promotionMaster.php');
    die();
}

function promoCodeAdd($con) {
    $img = '';
    $thumb = '';
    $title = addslashes($_POST['title']);
    $minVal = $_POST['minVal'];
    $amType = $_POST['amType'];
    $cvalue = $_POST[$amType . 'Text'];
    /* $promo_id = $_POST['promo']; */
    $pcode = $_POST['pcode'];
    $fdate = $_POST['fdate'];
    $ftime = $_POST['ftime'];
    $tdate = $_POST['tdate'];
    $ttime = $_POST['ttime'];
    $fdt = $fdate . ' ' . $ftime . ':00';
    $tdt = $tdate . ' ' . $ttime . ':00';
    if (isset($_FILES['img']['name']) && !empty($_FILES['img']) && $_FILES['img']['name'] != '') {
        define('LIMIT_PRODUCT_WIDTH', true);
        define('MAX_PRODUCT_IMAGE_WIDTH', 1600);
        define('THUMBNAIL_WIDTH', 125);
        $thumbimg = uploadProductImage('img', '../site_image/promotion/');
        $img = $thumbimg['image'];
        $thumb = $thumbimg['thumbnail'];
    }

    /* chk that promo is for insert or update */
    if (isset($_POST['promoId']) && $_POST['promoId'] != '') {
        $promoId = $_POST['promoId'];
        if ($_POST['promoId'] != 'allPro') {
            $q = "UPDATE tbl_promo_code SET title = '$title', min_cart_value = '$minVal', percent_or_amount = '$amType', promo_value = '$cvalue', promo_code = '$pcode', start_date = '$fdt', end_date = '$tdt', banner_thumb = '$thumb', banner_img = '$img', created_on = '" . date('c') . "' WHERE recid = '$promoId'";
            if (!exec_query($q, $con)) {
                $promoId = '';
            }
        } else {
            $q = "INSERT INTO tbl_promo_code(title, min_cart_value, percent_or_amount, promo_value, promo_code, promo_type, start_date, end_date, banner_thumb, banner_img, created_on) VALUES('$title', '$minVal', '$amType', '$cvalue', '$pcode', '$promoId', '$fdt', '$tdt', '$thumb', '$img', '" . date('c') . "')";
            unset($promoId);
            if (exec_query($q, $con)) {
                $promoId = mysqli_insert_id($con);
            }
        }
    } else {
        $q = "INSERT INTO tbl_promo_code(title, min_cart_value, percent_or_amount, promo_value, promo_code, start_date, end_date, banner_thumb, banner_img, created_on)
		VALUES('$title', '$minVal', '$amType', '$cvalue', '$pcode', '$fdt', '$tdt', '$thumb', '$img', '" . date('c') . "')";
        if (exec_query($q, $con)) {
            $promoId = mysqli_insert_id($con);
        }
    }

    if (isset($promoId) && $promoId != '') {
        setMessage('Promo Code Successfully Added', 'alert alert-success');
        $str = promoCodeGenerateEmail($promoId, $con);
        if ($str != '') {
            $_SESSION['emailStr'] = $str;
        }
        redirect('promoCodeAddEmail.php?data1=' . $promoId);
        die();
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('promoCodeAdd.php');
    die();
}

/* '.siteName.' added new promo code ('.$promotion->title.'),<br/>
  <b>Minimum Cart Value  :</b> '.$promotion->min_cart_value.'<br/>
  <b>Promotion  :</b> '.$promotion->promo_value.' '.$type.'<br/>
  <b>Promo Code  :</b> '.$promotion->promo_code.'<br/>
  <b>Validity  : from</b> '.date('d M, Y h:i A', strtotime($promotion->start_date)).' <b>to</b> '.date('d M, Y h:i A', strtotime($promotion->end_date)).' */

function promoCodeAddEmail($con) {
    $admin = $_SESSION['admin'];
    $id = $_POST['data1'];
    $emails = str_replace(array(' ', "'"), '', $_POST['userEmail']);
    $emailArr = explode(',', $emails);

    $q = "INSERT INTO tbl_promo_code_detail(promo_code_id, admin_id, email) VALUES('$id', '$admin', '$emails')";
    if (exec_query($q, $con)) {
        //echo $str;
        /* for php mailer lib, set to(receiver), subject, message text, recepients are multiple or not, */
        /* date_default_timezone_set('Etc/UTC'); it must be here, commented bcoz set in config file */
        require '../mail/PHPMailerAutoload.php';
        $phpMailerSubject = 'New Promo Code - ' . siteName;
        $str = $_POST['content'];
        $phpMailerText = $str;
        foreach ($emailArr AS $email) {
            if ($email != '') {
                $phpMailerTo = $email;
                include '../mail/PHPMailerConfig.php';
            }
        }
        setMessage('Promo Code Successfully Submitted', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    unset($_SESSION['emailStr']);
    redirect('promotion.php');
    die();
}

function promoCodeGenerateEmail($id, $con) {
    $str = '';
    $content = '';
    $promotion = mysqli_fetch_object(exec_query("SELECT * FROM tbl_promo_code WHERE recid = '$id'", $con));
    /* chk now */
    if (isset($promotion->recid) && $promotion->recid != '') {
        $condi = '';
        $promoCode = $promotion->recid;
        $img = (isset($promotion->banner_img) && $promotion->banner_img != '') ?
                '<img src="' . siteUrl . '/site_image/promotion/' . $promotion->banner_img . '" style="width:100%;" />' : '';
        $type = ($promotion->percent_or_amount == 'percent') ? '%' : '$';

        if ($promotion->promo_type == 'allBrand') {

            $br_value = '';
            $condPlus = '';
            $ib = 1;
            $bArr = explode(',', $promotion->ids);
            foreach ($bArr AS $value) {
                $condPlus .= ($ib == 1) ? ' AND ' : ' OR ';
                $condPlus .= "(tp.brand_id = $value AND (";
                // category opearation
                if ($promotion->category_id != '') {
                    $ic = 1;
                    $catArr = explode(',', $promotion->category_id);
                    foreach ($catArr AS $catt) {
                        $condPlus .= ($ic == 1) ? '' : ' OR ';
                        $condPlus .= "tpcat.category_id = $catt";
                        $ic++;
                    }
                    $condPlus .= ' )) ';
                }
                $ib++;
            }
            $condi .= $condPlus;

            /* old code
              $condi .= " AND tp.brand_id IN ($promotion->ids)";
              if($promotion->category_id != ''){
              $promoCategory = $promotion->category_id;
              $catQ = "SELECT category_id FROM `tbl_category` WHERE (parent_id IN ($promoCategory)) OR (superparent_id IN ($promoCategory)) OR (category_id IN ($promoCategory))";
              $catRs = exec_query($catQ, $con);
              $catArray = array();
              while($catRow = mysql_fetch_object($catRs)){ $catArray[] = $catRow->category_id; }
              $categories = implode(',', $catArray);
              $condi .= " AND tpcat.category_id IN ($categories)";
              } */
        } elseif ($promotion->promo_type == 'parPro') {
            $condi .= " AND tp.product_id IN ($promotion->ids)";
        } elseif ($promotion->promo_type == 'allCat') {
            $promoCategory = $promotion->ids;
            $catQ = "SELECT category_id FROM `tbl_category` WHERE (parent_id IN ($promoCategory)) OR (superparent_id IN ($promoCategory)) OR (category_id IN ($promoCategory))";
            $catRs = exec_query($catQ, $con);
            $catArray = array();
            while ($catRow = mysql_fetch_object($catRs)) {
                $catArray[] = $catRow->category_id;
            }
            $categories = implode(',', $catArray);
            $condi .= " AND tpcat.category_id IN ($categories)";
        } elseif ($promotion->promo_type == 'allPro') {
            /* nothing to do */
        }

        /* $str = '<div style="width:700px; margin:0px auto; font-family:'."'Trebuchet MS'".', Arial, Helvetica, sans-serif; text-align:center;">
          <p style="font-size:12px; color:#999; margin-bottom:0px; border-bottom:1px solid #CCC; padding-bottom:5px;">'.$promotion->title.'</p>
          <!--top_wrapper-->
          <div style="background:url('.siteUrl.'images/header_02.jpg); width:100%; height:163px; border-bottom:1px solid #CCC;">
          <div style="padding:10px 0px;"> <img src="'.siteUrl.'images/logo_03.png" height="104" /></div>
          <div style="border:1px dashed #333333; background:#FFF; padding:5px; width:120px; margin:0px auto; font-size:13px;">
          '.$promotion->promo_code.'
          </div>
          </div><!--HEADER-->'; */

        /* product query */

        $promoType = $promotion->percent_or_amount;
        $promoValue = $promotion->promo_value;

        $product_q = "SELECT tp.product_id, tp.slug, tp.product_name, tp.brand_id, tpp.product_price,tbl_brand.brand_name,
		tpm.media_thumb, tpp.product_price, tpm.color_id, GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
		LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
		LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
		LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
		LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
		WHERE tpm.media_type = 'img' $condi GROUP BY tp.product_id LIMIT 0, 6";
        $product_rs = exec_query($product_q, $con);
        $totalProducts = mysqli_num_rows($product_rs);
        if ($totalProducts > 0) {
            $i = 1;
            while ($row = mysqli_fetch_object($product_rs)) {
                /* for get promotion start */
                $all_cat = $row->all_cat;

                $price = $row->product_price;
                if ($promoType != '' && $promoValue != '') {
                    if ($promoType == 'percent') {
                        $discountOf = (($price * $promoValue) / 100);
                        $discount = str_replace(".00", "", $promoValue) . "%";
                        $disPrice = $price - $discountOf;
                    } elseif ($promoType == 'amount') {
                        $discountOf = $promoValue;
                        $discount = "$" . $promoValue;
                        $disPrice = $price - $discountOf;
                    }
                }
                /* echo $price.' -price,,,,,'.$discountOf.' -discount of,,,,,,,,';
                  echo $disPrice.' -final price,,,,,'.$promoValue.'- of promo code<br/>';
                  for get promotion end */

                if (($i % 2) != 0) {
                    $shadow = 'shadow_09.png';
                    $border = 'right';
                    $float = 'left';
                } else {
                    $shadow = 'shadow_right_09.png';
                    $border = 'left';
                    $float = 'right';
                }

                $str .= '
				<div style="width:345px; height:332px; background:#f4f4f4; border-' . $border . ':1px solid #fcd116; float:' . $float . ';">
					<div style="width:330px; height:44px; background:#fcd116; margin:15px 0px; float:' . $border . '; text-align:center; line-height:44px; font-family:calibri; font-weight:bold; color:#333;"> ' . $row->product_name . '
						
					</div>
					
					<div style="width:345px; float:left;"> <img src="' . siteUrl . 'site_image/product/' . $row->media_thumb . '"> </div>
					
					<div style="height:40px; float:left; margin:10px 53px;">
						<h3 style="float:left; margin:0px 5px; line-height:40px; font-weight:normal; font-size:16px;"> Extra $ ' . $disPrice . ' off </h3>
						<input type="button" value="Read More" style="float:left; border:1px solid #333; background:none; padding:10px; font-family:calibri; cursor:pointer;">
					</div>
				</div>';

                if ($i == 2 && $img != '') {
                    $str .= '<div style="width:100%; height:150px; float:left; margin:10px 0px; overflow:hidden;">
						' . $img . '
						<div style="clear:both;"> </div>
					</div>';
                }
                $i++;
            }
        }

        /* $str .= '<div style="width:100%; height:100px; background:#bdbdbd; float:left; ">
          <div style="width:150px; margin:0px auto; line-height:121px;">
          <a href="#" style="margin-right:10px; text-decoration:none;"> <img src="'.siteUrl.'images/twitter_05.png"> </a>
          <a href="#" style="margin-right:10px; text-decoration:none;"> <img src="'.siteUrl.'images/fb_07.png"> </a>
          <a href="#"> <img src="'.siteUrl.'images/gplus_09.png"> </a>
          </div>
          </div> <!--Footer-->
          </div>'; */
        /* new template end */


        //echo $str; die();
        // before return add content of templete
        $rsEmail = exec_query("SELECT * FROM tbl_email_template WHERE type = 'promocode'", $con);
        $rowEmail = mysqli_fetch_object($rsEmail);
        $content = $rowEmail->content;

        $contentHTML = html_entity_decode($content);
        $contentHTML = str_replace('{jhm :', '', $contentHTML); // replace all '{jhm : '
        $arraySearch = array(' title}', ' code}', ' products}'); // isko replace krna h
        $arrayReplace = array($promotion->title, $promotion->promo_code, $str); // isse replace krna h
        $content = str_replace($arraySearch, $arrayReplace, $contentHTML); // yha milega sb
    }
    return $content;
}

function promoCodeExtra($con) {
    $type = $_POST['data'];
    if ($type == 'allCat') {
        $cat_value = '';
        if (isset($_POST['subsubcategory']) && !empty($_POST['subsubcategory'])) {
            $cat = $_POST['subsubcategory'];
        } elseif (isset($_POST['subcategory']) && !empty($_POST['subcategory'])) {
            $cat = $_POST['subcategory'];
        } elseif (isset($_POST['mainCategory']) && !empty($_POST['mainCategory'])) {
            $cat = $_POST['mainCategory'];
        } else {
            setMessage('Some Error Occured in Category Selection!', 'alert alert-error');
            redirect('promotiontAdd.php');
            die();
        }
        foreach ($cat AS $value) {
            $cat_value .= ($cat_value == '') ? $value : ',' . $value;
        }
        $q = "INSERT INTO tbl_promo_code(promo_type, ids) VALUES('$type', '$cat_value')";
    } elseif ($type == 'allBrand') {
        // cat operation
        $cat_value = '';
        $cat = array();
        if (isset($_POST['subsubcategory']) && !empty($_POST['subsubcategory'])) {
            $cat = $_POST['subsubcategory'];
        } elseif (isset($_POST['subcategory']) && !empty($_POST['subcategory'])) {
            $cat = $_POST['subcategory'];
        } elseif (isset($_POST['mainCategory']) && !empty($_POST['mainCategory'])) {
            $cat = $_POST['mainCategory'];
        }
        foreach ($cat AS $value) {
            $cat_value .= ($cat_value == '') ? $value : ',' . $value;
        }

        $br_value = '';
        foreach ($_POST['brand'] AS $value) {
            $br_value .= ($br_value == '') ? $value : ',' . $value;
        }
        $q = "INSERT INTO tbl_promo_code(promo_type, category_id, ids) VALUES('$type', '$cat_value', '$br_value')";
    } elseif ($type == 'parPro') {
        $p_value = '';
        foreach ($_POST['product'] AS $value) {
            $p_value .= ($p_value == '') ? $value : ',' . $value;
        }
        $q = "INSERT INTO tbl_promo_code(promo_type, ids) VALUES('$type', '$p_value')";
    }
    if (exec_query($q, $con)) {
        $promoCodeId = mysqli_insert_id($con);
        if (isset($promoCodeId) && $promoCodeId != '') {
            setMessage('Promo Code Successfully Added. Now Fill the Promo Code Details.', 'alert alert-success');
            redirect('promoCodeAdd.php?data1=' . $promoCodeId);
        } else {
            setMessage('Some Error Occured, Try Again..', 'alert alert-error');
        }
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('promotion.php');
    die();
}

function extendPromotionExpiry($con) {
    $tdate = $_POST['tdate'];
    $ttime = $_POST['ttime'];
    $tdt = $tdate . ' ' . $ttime . ':00';

    $id = $_POST['data1'];
    $table = $_POST['data2'];
    $pk = ($table == 'tbl_promotion') ? 'promo_id' : 'recid';

    $q = "UPDATE $table SET end_date = '$tdt' WHERE $pk = '$id'";
    if (exec_query($q, $con)) {
        setMessage('Expiry Date Successfully Saved', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('promotion.php');
    die();
}

function productPreferenceEdit($con) {
    $type = $_POST['data1'];
    $col = '';
    foreach ($_POST['prefer'] AS $value) {
        $col .= ($col == '') ? $value : ',' . $value;
    }
    $q = "UPDATE tbl_preference SET columns = '$col' WHERE type = '$type'";
    if (exec_query($q, $con)) {
        setMessage('Preference Successfully Saved', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('product.php');
    die();
}

function taxAdd($con) {
    $name = strtoupper(addslashes($_POST['name']));
    $per = $_POST['per'];
    if (exec_query("INSERT INTO tbl_tax(tax_name, tax_percent) VALUES('$name', '$per')", $con)) {
        setMessage('Tax Successfully Added', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    if (isset($_POST['nsubmit'])) {
        redirect('taxAdd.php');
        die();
    }
    redirect('tax.php');
    die();
}

function taxEdit($con) {
    $id = strtoupper(addslashes($_POST['data1']));
    $name = addslashes($_POST['name']);
    $per = $_POST['per'];
    if (exec_query("UPDATE tbl_tax SET tax_name = '$name', tax_percent = '$per' WHERE recid = '$id'", $con)) {
        setMessage('Tax Successfully Edited', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('tax.php');
    die();
}

function tagAdd($con) {
    $title = addslashes($_POST['title']);
    $desc = addslashes($_POST['desc']);

    $menu = 0;
    if (isset($_POST['menu']) && $_POST['menu'] != '') {
        $menu = 1;
    }

    $fea = 0;
    $pimg = '';
    $pthumb = '';
    if (isset($_POST['featured']) && $_POST['featured'] != '' && isset($_FILES['img']) && isset($_FILES['img']['name']) && $_FILES['img']['name'] != '') {
        define('LIMIT_PRODUCT_WIDTH', true);
        define('MAX_PRODUCT_IMAGE_WIDTH', 800);
        define('THUMBNAIL_WIDTH', 180);

        $fea = 1;
        $thumbimg = uploadProductImage('img', '../site_image/tag/');
        $pimg = $thumbimg['image'];
        $pthumb = $thumbimg['thumbnail'];
    }

    if (exec_query("INSERT INTO tbl_tag(title, description, is_menu, is_featured, banner_img, banner_thumb) VALUES('$title', '$desc', '$menu', '$fea', '$pimg', '$pthumb')", $con)) {
        setMessage('Tag Successfully Added', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('tag.php');
    die();
}

function tagEdit($con) {
    $id = $_POST['data1'];
    $title = addslashes($_POST['title']);
    $desc = addslashes($_POST['desc']);

    $query = ", is_menu = 0";
    if (isset($_POST['menu']) && $_POST['menu'] != '') {
        $query = ", is_menu = 1";
    }

    $fea = 0;
    $pimg = '';
    $pthumb = '';
    if (isset($_POST['featured']) && $_POST['featured'] != '' && isset($_FILES['img']) && isset($_FILES['img']['name']) && $_FILES['img']['name'] != '') {
        define('LIMIT_PRODUCT_WIDTH', true);
        define('MAX_PRODUCT_IMAGE_WIDTH', 800);
        define('THUMBNAIL_WIDTH', 180);

        $fea = 1;
        $thumbimg = uploadProductImage('img', '../site_image/tag/');
        $pimg = $thumbimg['image'];
        $pthumb = $thumbimg['thumbnail'];

        $query .= ", is_featured = 1, banner_img = '$pimg', banner_thumb = '$pthumb'";
    } elseif (!isset($_POST['featured'])) {
        $query .= ", is_featured = 0";
    }

    if (exec_query("UPDATE tbl_tag SET title = '$title', description = '$desc' $query WHERE recid = '$id'", $con)) {
        setMessage('Tag Successfully Edited', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('tag.php');
    die();
}

function config($con) {
    $type = $_POST['type'];
    $no = $_POST['no'];
    $q = "UPDATE tbl_config SET no = '$no' WHERE type = '$type'";
    if (exec_query($q, $con)) {
        setMessage('Configuration Successfully Edited', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    if (isset($_POST['redirect'])) {
        redirect($_POST['redirect']);
        die();
    } else {
        redirect('siteConfig.php');
        die();
    }
}

function shippingPriceAdd($con) {
    $sector = addslashes($_POST['scode']);
    $price = $_POST['price'];

    if (exec_query("INSERT INTO tbl_shipping_price(sector_code, price) VALUES('$sector', '$price')", $con)) {
        setMessage('Shipping Price Successfully Added', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('shipingPrice.php');
    die();
}

function shippingPriceEdit($con) {
    $id = $_POST['data1'];
    $sector = addslashes($_POST['scode']);
    $price = $_POST['price'];

    if (exec_query("UPDATE tbl_shipping_price SET sector_code = '$sector', price = '$price' WHERE recid = '$id'", $con)) {
        setMessage('Shipping Price Successfully Edited', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('shipingPrice.php');
    die();
}

function shippingCityAdd($con) {
    $sname = addslashes($_POST['sname']);
    $scode = addslashes($_POST['scode']);
    $postcode = addslashes($_POST['postcode']);
    $suburb = addslashes($_POST['suburb']);
    $town = addslashes($_POST['town']);

    $isRural = 0;
    if (isset($_POST['isRural']) && $_POST['isRural'] != '') {
        $isRural = 1;
    }

    $sql = "INSERT into tbl_shipping_sector(
		sector_code, sector_name,
		postcode, is_rural,
		suburb_name, town_name
	)values(
		'" . $scode . "', '" . $sname . "',
		'" . $postcode . "', '" . $isRural . "',
		'" . $suburb . "', '" . $town . "'
	)";

    if (exec_query($sql, $con)) {
        setMessage('Shipping Sector Successfully Added', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('shipingSector.php');
    die();
}

function shippingCityEdit($con) {
    $id = $_POST['data1'];
    $sname = addslashes($_POST['sname']);
    $scode = addslashes($_POST['scode']);
    $postcode = addslashes($_POST['postcode']);
    $suburb = addslashes($_POST['suburb']);
    $town = addslashes($_POST['town']);

    $isRural = 0;
    if (isset($_POST['isRural']) && $_POST['isRural'] != '') {
        $isRural = 1;
    }

    $sql = "UPDATE tbl_shipping_sector SET sector_code = '" . $scode . "', sector_name = '" . $sname . "', postcode = '" . $postcode . "', is_rural = '" . $isRural . "', suburb_name = '" . $suburb . "', town_name = '" . $town . "' WHERE recid = '$id'";

    if (exec_query($sql, $con)) {
        setMessage('Shipping Sector Successfully Edited', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('shipingSector.php');
    die();
}

function dispatchInfo($con) {
    $orderId = $_POST['data1'];
    $delDate = $_POST['delDate'];
    $trackNo1 = addslashes($_POST['trackNo1']);
    $trackNo2 = addslashes($_POST['trackNo2']);
    $trackNo3 = addslashes($_POST['trackNo3']);
    $cName = addslashes($_POST['cName']);
    $note = addslashes($_POST['note']);

    if (isset($orderId) && $orderId != '') {
        if (exec_query("INSERT INTO `tbl_order_ship` (`order_id`, `del_date`, `tracking_no1`, `tracking_no2`, `tracking_no3`, `courier_name`, `note`, `datetime`) VALUES ('$orderId', '$delDate', '$trackNo1', '$trackNo2', '$trackNo3', '$cName', '$note', '" . date('c') . "')", $con)) {
            $update = exec_query("UPDATE tbl_order SET status = 1 WHERE order_id = '" . $orderId . "'", $con);

            // get email
            $orderRs = exec_query("SELECT user_id FROM tbl_order WHERE order_id = '$orderId'", $con);
            $user_id = mysqli_fetch_object($orderRs)->user_id;
            // send mail
            $email = mysqli_fetch_object(mysqli_query($con, "SELECT email FROM tbl_user WHERE user_id = '$user_id'"));
            if (isset($email->email) && $email->email != '') {
                $rsEmail = mysqli_query($con, "SELECT * FROM tbl_email_template WHERE type = 'orderDispatch'");
                $rowEmail = mysqli_fetch_object($rsEmail);
                $content = $rowEmail->content;

                //get delivery addreess
                $row = mysqli_fetch_object(mysqli_query($con, "SELECT * FROM tbl_order WHERE order_id = '$orderId'"));
                $shipuname = $row->od_shipping_first_name . ' ' . $row->od_shipping_last_name;
                $phoneAlt = $row->od_shipping_alt_phone;
                $phoneAlt1 = ($phoneAlt != '') ? ' (' . $phoneAlt . ')' : '';
                $delAddress = '<table border="0" cellspacing="0" cellpadding="2">
					<tbody>
						<tr>
							<td>Shipping Name</td>
							<td>' . $shipuname . '</td>
						</tr>
						<tr>
							<td>Contact No.</td>
							<td>' . $row->od_shipping_phone . ', ' . $phoneAlt1 . '</td>
						</tr>
						<tr>
							<td>Locality</td>
							<td>' . $row->od_shipping_locality . '</td>
						</tr>
						<tr>
							<td>Address</td>
							<td>' . $row->od_shipping_address . '</td>
						</tr>
						<tr>
							<td>City, State</td>
							<td>' . $row->od_shipping_city . '</td>
						</tr>
						<tr>
							<td>Postal Code</td>
							<td>' . $row->od_shipping_postal_code . '</td>
						</tr>
					</tbody>
				</table>';
                $style = 'style="border:thin dotted #d3d3d3;"';
                $table = '<table cellspacing="0" cellpadding="5" border="0" style="font-size:13px; width:100%">
				<thead>
					<tr>
						<th colspan="2" ' . $style . '>Product</th>
						<th ' . $style . '>Price</th>
						<th ' . $style . '>Qty</th>
						<th ' . $style . '>Subtotal</th>
					</tr>
				</thead>
				<tbody>';
                // for prepare order items in table
                $gTotal = 0;
				$gstCalcTotal = 0;
				$cart_total = 0;
				$pricePromotionTotal = 0;
				$pricePromoCodeTotal = 0;
                $oiQuery = "SELECT toi.*, tp.product_name, tp.slug, tc.color FROM tbl_order_item toi
				LEFT JOIN tbl_product tp ON tp.product_id = toi.product_id
				LEFT JOIN tbl_product_color tpc ON tpc.color_id = toi.color_id
				LEFT JOIN tbl_color tc ON tc.color_code = tpc.color_code
				WHERE toi.order_id = '$orderId'
				GROUP BY toi.product_id, toi.color_id";
                $oiRs = mysql_query($oiQuery);
                while ($oiRow = mysql_fetch_object($oiRs)) {
                    $imgRow = mysql_fetch_object(mysql_query("SELECT media_thumb FROM tbl_product_media WHERE product_id = '" . $oiRow->product_id . "' AND color_id = '" . $oiRow->color_id . "' LIMIT 0,1"));
                    $img = '';
                    if (isset($imgRow->media_thumb) && $imgRow->media_thumb != '') {
                        $img = $imgRow->media_thumb;
                    }
                    $pname = $oiRow->product_name . ' (' . $oiRow->color . ')';
                    // get price
						$pricePromotion = $oiRow->product_promo_price;
						$pricePromoCode = $oiRow->product_promo_code_price;
                        $qty = $oiRow->od_qty;
						$priceNoDiscount = $oiRow->product_price;
						$productDiscountedPrice = 0; 
						
						if($pricePromotion > 0){
							$productDiscountedPrice = $oiRow->product_promo_price;
							$promoType = $oiRow->product_promo_type;
							$promoVal = $oiRow->product_promo_value;
							if($promoType == 'percent'){
								$discountPromo = ($priceNoDiscount * $promoVal) / 100;
								$pricePromotionTotal += ($qty * $discountPromo);
							}
							elseif($promoType == 'amount'){
								$discountPromo = $promoVal;
								$pricePromotionTotal += $discountPromo;
							}
						}
						if($pricePromoCode > 0){
							$productDiscountedPrice = $oiRow->product_promo_code_price;
							$promoCodeType = $oiRow->product_promo_code_type;
							$promoCodeVal = $oiRow->product_promo_code_value;
							if($promoCodeType == 'percent'){
								$discountPromoCode = ($priceNoDiscount * $promoCodeVal) / 100;
								$pricePromoCodeTotal += ($qty * $discountPromoCode);
							}
							elseif($promoCodeType == 'amount'){
								$discountPromoCode = $promoCodeVal;
								$pricePromoCodeTotal += $discountPromoCode;
							}
						}
						
						if ($productDiscountedPrice == 0) {
							$productDiscountedPrice = $priceNoDiscount;
						}  //no promotion, use normal price
							 
						
						$Gst = $productDiscountedPrice*.15;	//gst value
						$priceWithoutGST = $productDiscountedPrice-$Gst;	//price without tax
						
						$rowTotal = ($productDiscountedPrice * $qty);
						$gstCalc = ($Gst * $qty);
						$gstCalcTotal += $gstCalc;
                        $cart_total+=$rowTotal;
						$pPrice = formatCurrency(round($productDiscountedPrice, 2, PHP_ROUND_HALF_UP));
						$rowTotal = formatCurrency(round($rowTotal, 2, PHP_ROUND_HALF_UP)); 
 
                    $table .= "<tr>
						<td $style><img src='" . siteUrl . "site_image/product/" . $img . "' width='100' height='80' /></td>
						<td $style>$pname</td>
						<td $style>$ $pPrice</td>
						<td $style>$qty</td>
						<td $style>$ $rowTotal</td>
					</tr>";
                }
				$delChrgCalc = $row->od_shipping_cost;
				$allTotal = ($cart_total + $delChrgCalc);
				$fAallTotal = $allTotal;

                $table .= '<tr>
					<td ' . $style . ' colspan="4" align="right">Sub Total</td>
					<td ' . $style . '>$ '.formatCurrency(round($cart_total, 2, PHP_ROUND_HALF_UP)).'</td>
				</tr>
				<tr>
					<td colspan="4" ' . $style . ' align="right">Includes Tax</td>
					<td ' . $style . '>$ '.formatCurrency(round($gstCalcTotal, 2, PHP_ROUND_HALF_UP)).'</td>
				</tr>
				<tr>
					<td colspan="4" ' . $style . ' align="right">Delivery Charge</td>
					<td ' . $style . '>$ '.formatCurrency(round($delChrgCalc, 2, PHP_ROUND_HALF_UP)).'</td>
				</tr>
				<tr style="font-size:14px;">
					<td colspan="4" align="right" ' . $style . '>Grand Total</td>
					<td ' . $style . '>$ '.formatCurrency(round($fAallTotal, 2, PHP_ROUND_HALF_UP)).'</td>
				</tr>';
                $table .= '</tbody></table>';
                $data = explode('@', $email->email);

                $cDetail = '<table border="0" cellspacing="0" cellpadding="2">
					<tbody>
						<tr>
							<td>Expected Delivery Date</td>
							<td>' . date('d M, Y', strtotime($delDate)) . '</td>
						</tr>
						<tr>
							<td>Tracking No.</td>
							<td>' . $trackNo1 . ', ' . $trackNo2 . ', ' . $trackNo3 . '</td>
						</tr>
						<tr>
							<td>Courier Name</td>
							<td>' . $cName . '</td>
						</tr>
						<tr>
							<td>Note</td>
							<td>' . $note . '</td>
						</tr>
					</tbody>
				</table>';

                $contentHTML = html_entity_decode($content);
                $contentHTML = str_replace('{jhm :', '', $contentHTML); // replace all '{jhm : '

                $orderNo = getOrderId($orderId);
                $arraySearch = array(' username}', ' orderNo}', ' orderItem}', ' price}', ' delAddress}', ' courierDetail}'); // isko replace krna h
                $arrayReplace = array($data[0], $orderNo, $table, '$ ' . $fAallTotal, $delAddress, $cDetail); // isse replace krna h

                $content = str_replace($arraySearch, $arrayReplace, $contentHTML); // yha milega sb

                $subject = 'Order Dispatched - Your Order(' . getOrderId($orderId) . ') has been successfully dispatched.';
                sendMail($subject, $content, array($email->email), $con);
            }
        } else {
            setMessage('Some Error Occured, Try Again..', 'alert alert-error');
        }
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('adminOrder.php');
    die();
}

function userEdit($con) {
    $id = $_POST['data1'];
    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $title = $_POST['title'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $address = addslashes($_POST['address']);
    $city = $_POST['city'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $zip = $_POST['zip'];

    $qVal = '';
    if (isset($_FILES['img']) && $_FILES['img']['name'] != '' && !empty($_FILES['img'])) {
        define('LIMIT_PRODUCT_WIDTH', true);
        define('MAX_PRODUCT_IMAGE_WIDTH', 450);
        define('THUMBNAIL_WIDTH', 150);

        $thumbimg = uploadProductImage('img', '../site_image/profile_pic/');
        $img = $thumbimg['image'];
        $thumb = $thumbimg['thumbnail'];
        $qVal = ", img = '$thumb'";
    }
    $q = "UPDATE `tbl_user` SET `title` = '$title', `last_name` = '$lname', `first_name` = '$fname', `email` = '$email', `username` = '$uname', `phone_1` = '$phone', `address_1` = '$address', `city` = '$city', `state` = '$state', `country_id` = '$country', `zip` = '$zip' $qVal WHERE user_id = '$id'";
    if (exec_query($q, $con)) {
        setMessage('User Successfully Edited.', 'alert alert-success');
    } else {
        setMessage('Error, Some error occured.', 'alert alert-error');
    }
    redirect('userEdit.php?data1=' . $id);
    die();
}

function adminSearchTagAdd($con) {
    $keyword = addslashes($_POST['keyword']);
    if (exec_query("INSERT INTO tbl_search_admin(keyword, date) VALUES('$keyword', '" . date('c') . "')", $con)) {
        setMessage('Search Tag Successfully Added', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('searchTag.php');
    die();
}

function adminSearchTagEdit($con) {
    $id = $_POST['data1'];
    $keyword = addslashes($_POST['keyword']);
    if (exec_query("UPDATE tbl_search_admin SET keyword = '$keyword' WHERE recid = '$id'", $con)) {
        setMessage('Search Tag Successfully Edited', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('searchTag.php');
    die();
}

function manage($con) {
    $type = $_POST['type'];
    if ($type == 'support') {
        $support = $_POST['support'];
        $content = '';
        foreach ($support AS $key => $value) {
            $content .= ($key != 0) ? '|' : '';
            if ($value != '') {
                $content .= $value;
            } else {
                $content .= '>';
            }
        }
    } else {
        $content = $_POST['content'];
    }
    $rs = exec_query("UPDATE tbl_manage SET content = '$content' WHERE type = '$type'", $con);

    if ($rs) {
        setMessage('Content Successfully Edited', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    //redirect('cms.php');
    echo '<script> history.back(); </script>';
    die();
}

function addNewsletter($con) {
    $subjectDB = htmlentities($_POST['subject']);
    $subject = $_POST['subject'];
    $nid = $_POST['nid'];
    $rs = exec_query("SELECT * FROM tbl_email_template WHERE recid = '$nid'", $con);
    $row = mysql_fetch_object($rs);

    $contentDB = $row->content;
    $content = html_entity_decode($row->content);

    /* $contentDB = htmlentities($_POST['content']);
      $content = $_POST['content']; */

    $email = $_POST['email'];
    if (exec_query("INSERT INTO tbl_newsletter(subject, content, datetime) VALUES('$subjectDB', '" . $contentDB . "', '" . date('c') . "')", $con)) {
        sendMail($subject . ' - ' . siteName, $content, $email, $con);
        setMessage('Newsletter Successfully Sent', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('newsletterAdd.php');
    die();
}

function addEmailTemplate($con) {
    $title = addslashes($_POST['title']);
    $content = $_POST['content'];
    $contentDB = htmlentities($_POST['content']);
    $type = $_POST['type'];
    if (exec_query("INSERT INTO tbl_email_template(title, type, content, datetime) VALUES('$title', '$type', '" . $contentDB . "', '" . date('c') . "')", $con)) {
        setMessage('Newsletter Successfully Added', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('manageTemplate.php');
    die();
}

function editEmailTemplate($con) {
    $title = addslashes($_POST['title']);
    $content = $_POST['content'];
    $contentDB = htmlentities($_POST['content']);
    $type = $_POST['type'];
    $id = $_POST['data1'];
    if (exec_query("UPDATE tbl_email_template SET title = '$title', type = '$type', content = '" . $contentDB . "', datetime = '" . date('c') . "' WHERE recid = '$id'", $con)) {
        setMessage('Content Successfully Edited', 'alert alert-success');
    } else {
        setMessage('Some Error Occured, Try Again..', 'alert alert-error');
    }
    redirect('manageTemplate.php');
    die();
}

function sendMail($sub, $content, $emails, $con) {
    $phpMailerSubject = $sub;
    require '../mail/PHPMailerAutoload.php';

    $phpMailerText = $content;
    foreach ($emails AS $email) {
        if ($email != '') {
            $phpMailerTo = $email;
            include '../mail/PHPMailerConfig.php';
        }
    }
}

function uploadProductImage($inputName, $uploadDir) { /* img ////////////// function //////////////////////// start /////////////////////////// */
    $image = $_FILES[$inputName];
    $imagePath = '';
    $thumbnailPath = '';
    // if a file is given
    if (trim($image['tmp_name']) != '') {
        $ext = substr(strrchr($image['name'], "."), 1); //$extensions[$image['type']];
        // generate a random new file name to avoid name conflict
        $imagePath = md5(rand() * time()) . ".$ext";
        list($width, $height, $type, $attr) = getimagesize($image['tmp_name']);
        // make sure the image width does not exceed the
        // maximum allowed width
        if (LIMIT_PRODUCT_WIDTH && $width > MAX_PRODUCT_IMAGE_WIDTH) {
            $result = createThumbnail($image['tmp_name'], $uploadDir . $imagePath, MAX_PRODUCT_IMAGE_WIDTH);
            $imagePath = $result;
        } else {
            $result = move_uploaded_file($image['tmp_name'], $uploadDir . $imagePath);
        }
        if ($result) { // create thumbnail
            $thumbnailPath = md5(rand() * time()) . ".$ext";
            $result = createThumbnail($uploadDir . $imagePath, $uploadDir . $thumbnailPath, THUMBNAIL_WIDTH);

            // create thumbnail failed, delete the image
            if (!$result) {
                unlink($uploadDir . $imagePath);
                $imagePath = $thumbnailPath = '';
            } else {
                $thumbnailPath = $result;
            }
        } else {
            // the product cannot be upload / resized
            $imagePath = $thumbnailPath = '';
        }
    } return array('image' => $imagePath, 'thumbnail' => $thumbnailPath);
}

function uploadMultiProductImage($inputName, $key, $uploadDir) {
    $image = $_FILES[$inputName];
    $imagePath = '';
    $thumbnailPath = '';
    // if a file is given
    if (trim($image['tmp_name'][$key]) != '') {
        $ext = substr(strrchr($image['name'][$key], "."), 1); //$extensions[$image['type']];
        // generate a random new file name to avoid name conflict
        $imagePath = md5(rand() * time()) . ".$ext";
        list($width, $height, $type, $attr) = getimagesize($image['tmp_name'][$key]);
        // make sure the image width does not exceed the
        // maximum allowed width
        if (LIMIT_PRODUCT_WIDTH && $width > MAX_PRODUCT_IMAGE_WIDTH) {
            $result = createThumbnail($image['tmp_name'][$key], $uploadDir . $imagePath, MAX_PRODUCT_IMAGE_WIDTH);
            $imagePath = $result;
        } else {
            $result = move_uploaded_file($image['tmp_name'][$key], $uploadDir . $imagePath);
        }
        if ($result) { // create thumbnail
            $thumbnailPath = md5(rand() * time()) . ".$ext";
            $result = createThumbnail($uploadDir . $imagePath, $uploadDir . $thumbnailPath, THUMBNAIL_WIDTH);

            // create thumbnail failed, delete the image
            if (!$result) {
                unlink($uploadDir . $imagePath);
                $imagePath = $thumbnailPath = '';
            } else {
                $thumbnailPath = $result;
            }
        } else {
            // the product cannot be upload / resized
            $imagePath = $thumbnailPath = '';
        }
    } return array('image' => $imagePath, 'thumbnail' => $thumbnailPath);
}

function createThumbnail($srcFile, $destFile, $width, $quality = 75) {
    $thumbnail = '';
    if (file_exists($srcFile) && isset($destFile)) {
        $size = getimagesize($srcFile);
        $w = number_format($width, 0, ',', '');
        $h = number_format(($size[1] / $size[0]) * $width, 0, ',', '');
        $thumbnail = copyImage($srcFile, $destFile, $w, $h, $quality);
    }// return the thumbnail file name on sucess or blank on fail
    return basename($thumbnail);
}

/* Copy an image to a destination file. The destination image size will be $w X $h pixels */

function copyImage($srcFile, $destFile, $w, $h, $quality = 75) {
    $tmpSrc = pathinfo(strtolower($srcFile));
    $tmpDest = pathinfo(strtolower($destFile));
    $size = getimagesize($srcFile);
    if ($tmpDest['extension'] == "gif" || $tmpDest['extension'] == "jpg" || $tmpDest['extension'] == "jpeg") {
        $destFile = substr_replace($destFile, 'jpg', -3);
        $dest = imagecreatetruecolor($w, $h);
        imageantialias($dest, TRUE);
    } elseif ($tmpDest['extension'] == "png") {
        $dest = imagecreatetruecolor($w, $h);
       // Make the background transparent
       imagealphablending($dest, false);
     imagesavealpha($dest, true);
     $transparentindex = imagecolorallocatealpha($dest, 255, 255, 255, 127);
     imagefill($dest, 0, 0, $transparentindex);
        //imageantialias($dest, TRUE);
    } else {
        return false;
    }
    switch ($size[2]) {
        //GIF
        case 1: $src = imagecreatefromgif($srcFile);
            break;
        //JPEG
        case 2: $src = imagecreatefromjpeg($srcFile);
            break;
        //PNG
        case 3: $src = imagecreatefrompng($srcFile);
            break;
        default: return false;
            break;
    }
    imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
    switch ($size[2]) {
        case 1:
        case 2: imagejpeg($dest, $destFile, $quality);
            break;
        case 3: imagepng($dest, $destFile);
    }return $destFile;
}

function chkSlug($table, $slug) {
    $rs = mysqli_query("SELECT slug FROM $table WHERE slug = '$slug'");
    if (mysqli_num_rows($rs) > 0) {
        return true;
    } else {
        return false;
    }
}

?>