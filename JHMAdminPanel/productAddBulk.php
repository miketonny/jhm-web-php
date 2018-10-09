<?php
    if(!empty($_FILES['csv'])) {

        // replace the 63 with the id of the temp brand
        define('TEMP_BRAND_ID', 227);

        include '../include/config.php';
        include '../include/function.php';

        session_start();

        if($_FILES['csv']['error'] !== 0) {
            setMessage('Failed to process upload!', 'alert alert-error');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $file = $_FILES['csv']['tmp_name'];
        $lines = array_map('str_getcsv', file($file, FILE_SKIP_EMPTY_LINES));


        $keys = array_shift($lines);
        $expected = [
            'upc',
            'name',
            'description',
            'usage',
            'qty',
            'availability',
            'price',
            //'cost',
            'color',
            //'brand',
            'main_category',
            //'sub_category',
            //'sub_sub_category',
            //'keyword',
            'images'
        ];
        $validator = array_intersect($expected, $keys);

        if(count($validator) !== count($expected)) {
            setMessage('The uploaded file is not compatible with this importer!', 'alert alert-error');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $products = [];

        foreach ($lines as $i => $row) {
            $products[$i] = array_combine($keys, $row);
        }
        // product statement
        $productStmt = $con->prepare("INSERT INTO `tbl_product` (
              `temp_subcategory`, `brand_id`, `product_name`, `product_description`, `product_summary`, `slug`, 
              `user_group`, `age_group`, `size`, `stock_availability`, `qty`, `product_sku`, `product_upc`, 
              `manufacturer_code`, `tag`, `keyword`, `height`, `width`, `weight`, `created_by`, `modified_by`, 
              `is_activate`, `promotion_state`, `created_on`, `modified_on`) 
                                                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        // delete product statement
        $delProduct = $con->prepare("DELETE FROM `tbl_product` WHERE `product_id`=?");
        // category statement
        $catStmt = $con->prepare("INSERT INTO `tbl_product_category` (`product_id`, `category_id`) VALUES (?, ?)");
        // color statement
        $colorStmt = $con->prepare("INSERT INTO `tbl_product_color` (`product_id`, `color_code`) VALUES(?, ?)");
        // price statement
//        $priceStmt = $con->prepare("INSERT INTO `tbl_product_price`
//                                                  (`product_id`, `product_upc`, `product_price`, `qty`, `color_id`)
//                                           VALUES (?, ?, ?, ?, ?)");
        $priceStmt = $con->prepare("INSERT INTO `tbl_product_price` 
                                                  (`product_id`, `product_upc`, `product_price`, `qty`, `color_id`, `cost`, `product_rrp`, `isDiscount`, `discount_start_date`, `discount_end_date`, `is_activate`, `backorder_qty`) 
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // image statement
        $imageStmt = $con->prepare("INSERT INTO `tbl_product_media` (`product_id`, `color_id`, `media_type`, `media_src`, `media_thumb`, `is_main`, `is_activate`) 
                                                                     VALUES(?, ?, 'img', ?, ?, ?, 0)");
        // added stats
        $added = 0;
        // error stats
        $failed = 0;
        // errors
        $errors = [];
        // loop over the products and add them to the database
        foreach($products as $product) {
            // create slug from name
            $slug = preg_replace('/[^a-z0-9]+/i', '_', $product['name']);
            // determine which id we use for the category
            $category_id = !empty($product['sub_sub_category']) ? $product['sub_sub_category'] : (!empty($product['sub_category']) ? $product['sub_category'] : $product['category']);
            $is_activate = 2;
            $temp_subcategory = $category_id;
            $brand_id = TEMP_BRAND_ID;
            $user_group = 'male,female';
            $age_group = '5-15,15-25,25-40,40-55,above 55';
            $size = '20 ML';
            $manufacturer_code = $weight = $tag = '';
            $height = $width = $promotion_state = 0;
            $sku = $con->query("SELECT MAX(CAST(`product_id` AS UNSIGNED)) + 1 AS `sku` FROM `tbl_product`")->fetch_assoc()['sku'];
            $productStmt->bind_param(
                'iisssssssiiissssiisiiii',
                $category_id,
                $brand_id,
                $product['name'],
                $product['description'],
                $product['description'], // summary
                $slug,
                $user_group, // user group
                $age_group, // user group
                $size,
                $product['availability'],
                $product['qty'],
                $sku,
                $product['upc'],
                $manufacturer_code,
                $tag,
                $product['keyword'],
                $height,
                $width,
                $weight,
                $_SESSION['admin'],
                $_SESSION['admin'],
                $is_activate,
                $promotion_state
            );
            // if we can't insert the product move to the next item
            if(!$productStmt->execute()) {
                $errors[] = $con->error;
                echo 'product error ' . $con->error;
                continue;
            }
            // get product id
            $product_id = $con->insert_id;

            // create product category association
            $catStmt->bind_param('ii', $product_id, $category_id);
            if(!$catStmt->execute()) {
                echo 'category error ' . $con->error;
                $errors[] = $con->error;
                if($product_id) {
                    $delProduct->bind_param('i', $product_id);
                    if(!$delProduct->execute())
                        $errors[] = $con->error;
                }
                continue;
            }
            // create color association
            //$r = $con->query("SELECT `color_id`, `color_code` FROM `tbl_color` WHERE `name`");
            $colorStmt->bind_param('is', $product_id, $product['color']);
            if(!$colorStmt->execute()) {
                echo 'color error ' . $con->error;
                $errors[] = $con->error;
            }
            $color_id = $con->insert_id;
            $cost = 0.00;
            $product_rrp = 0.00;
            $isDiscount = $is_activate = $backorder_qty = 0;
            $discount_start_date = $discount_end_date = '0000-00-00';
            // create price association
            $priceStmt->bind_param(
                'isdiiddissii',
                $product_id,
                $product['upc'],
                $product['price'],
                $product['qty'],
                $color_id,
                $cost,
                $product_rrp,
                $isDiscount,
                $discount_start_date,
                $discount_end_date,
                $is_activate,
                $backorder_qty
            );
            if(!$priceStmt->execute()) {
                echo 'price error ' . $con->error;
                $errors[] = $con->error;
                die();
            }
            // create media association
            if(!empty($product['images']) && 0+(int)$product['images'] > 0) {
                for($i = 0; $i < $product['images']; $i++) {
                    $mainImage = $i === 0;
                    $imageName = $product['upc'] . ($mainImage ? '' : '-' . $i) . '.jpg';
                    $imageStmt->bind_param(
                        'iissi',
                        $product_id,
                        $color_id,
                        $imageName,
                        $imageName,
                        $mainImage
                    );
                    if(!$imageStmt->execute()) {
                        echo 'image error ' . $con->error;
                        $errors[] = $con->error;
                    }
                }
            }
            $added ++;
        }
        // processing completed
        setMessage($added . ' products were added successfully!', 'alert alert-success');
        header('Location: product.php');
        exit;
    }

?>
<?php include 'include/header.php'; ?>
<div class="warper container-fluid">
    <div class="page-header"><h1>Add Products Bulk <small>upload CSV file</small></h1></div>
    <div class="row">

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Upload Products In CSV Format</div>
                <div class="panel-body">
                    <form method="post" class="form-horizontal" action="" enctype="multipart/form-data">

                        <div class="form-group">
                            <div class="col-sm-6">
                                <input name="csv" type="file"/>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <div class="col-lg-9">
                                <button class="btn btn-primary" type="submit"> IMPORT ! </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- Warper Ends Here (working area) -->

<?php include 'include/footer.php'; ?>
<?php include 'include/formJs.php'; ?>

</body>
</html>
