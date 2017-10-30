<?php
ob_start("ob_gzhandler");
session_start();
include("include/config.php");
include("include/functions.php");

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getMaleFemaleOnCategoryPage') {
    $catId = $_GET['data1'];
    $catName = $_GET['dataName'];
    $slugRs = exec_query("SELECT slug FROM tbl_category WHERE category_id = '$catId'", $con);
    $slugRow = mysqli_fetch_object($slugRs);
    ?>
    <strong>
        <?php /* href="javascript:void(0);" onclick="getCategoryOnCategoryPage();" */ ?>
        <a href="<?php echo siteUrl; ?>all/">ALL CATEGORIES</a><br/>>
        <a href="javascript:void(0);">ALL <?php echo $catName; ?></a><br/>

        <a href="<?php echo siteUrl; ?>all/1/<?php echo $slugRow->slug; ?>/" >All</a><br/>
        <a href="<?php echo siteUrl; ?>mens/1/<?php echo $slugRow->slug; ?>/" >Male</a><br/>
        <a href="<?php echo siteUrl; ?>womens/1/<?php echo $slugRow->slug; ?>/" >Female</a>
        <?php /* <a href="javascript:void(0);" onclick="setGenderValues(<?php echo "'all', ".$catId.", '".$catName."'"; ?>)">All</a><br/>
          <a href="javascript:void(0);" onclick="setGenderValues(<?php echo "'male', ".$catId.", '".$catName."'"; ?>)">Male</a><br/>
          <a href="javascript:void(0);" onclick="setGenderValues(<?php echo "'female', ".$catId.", '".$catName."'"; ?>)">Female</a> */ ?>
    </strong>
    <?php
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getSubCategoryOnCategoryPage') {
    $catId = $_GET['data1'];
    $catName = $_GET['dataName'];
    $gen = $_GET['gen'];
    // main cat
    $slugRs = exec_query("SELECT slug FROM tbl_category WHERE category_id = '$catId'", $con);
    $slug = mysqli_fetch_object($slugRs)->slug;
    // sub cat
    $cat_rs = exec_query("SELECT category_name, category_id, slug FROM tbl_category WHERE parent_id = 0 AND superparent_id = '$catId'", $con);
    ?>
    <strong>
        <?php /* href="javascript:void(0);" onclick="getCategoryOnCategoryPage();" */ ?>
        <a href="<?php echo siteUrl; ?>all/">ALL CATEGORIES</a><br/>>
        <a href="javascript:void(0);" onclick="getMaleFemaleOnCategoryPage(<?php echo $catId . ", '" . $catName . "'"; ?>);">ALL <?php echo $_GET['dataName']; ?></a><br/>   
    </strong>
    <?php
    if ($gen != 'all') {
        if ($gen == 'men') {
            $gen = 'mens';
        } else {
            $gen = 'womens';
        }
    }
    while ($cat_row = mysqli_fetch_object($cat_rs)) {
        ?>
        <li>
            <a href="<?php echo siteUrl . $gen; ?>/1/<?php echo $slug . '/' . $cat_row->slug; ?>/"><?php echo $cat_row->category_name; ?></a>
            <?php /* <a href="javascript:void(0);" onclick="getSubSubCategoryOnCategoryPage(<?php echo $cat_row->category_id.", '".$cat_row->category_name."', '".$gen."'"; ?>)"><?php echo $cat_row->category_name; ?></a> */ ?>
        </li>
        <?php
    }
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getSubSubCategoryOnCategoryPage') {
    $gen = $_GET['gen'];
    $catId = $_GET['data1'];

    $parentCat = getCategory(array('category_name', 'category_id', 'superparent_id', 'slug'), $catId, $con);

    $superParentCat = getCategory(array('category_name', 'category_id', 'slug'), $parentCat->superparent_id, $con);

    $cat_rs = exec_query("SELECT category_name, category_id, slug FROM tbl_category WHERE parent_id = '$catId' AND superparent_id != 0", $con);
    ?>
    <strong>
        <?php /* href="javascript:void(0);" onclick="getCategoryOnCategoryPage();" */ ?>
        <a href="<?php echo siteUrl; ?>all/">ALL CATEGORIES</a><br/>>
        <a href="javascript:void(0);" onclick="getMaleFemaleOnCategoryPage(<?php echo $superParentCat->category_id . ", '" . $superParentCat->category_name . "'"; ?>);">ALL <?php echo $superParentCat->category_name; ?></a>
        <br/>>
        ALL <?php echo $_GET['dataName']; ?>
    </strong>
    <?php
    if ($gen != 'all') {
        if ($gen == 'men') {
            $gen = 'mens';
        } else {
            $gen = 'womens';
        }
    }
    while ($cat_row = mysqli_fetch_object($cat_rs)) {
        ?>
        <li>
            <a href="<?php echo siteUrl . $gen; ?>/1/<?php echo $superParentCat->slug . '/' . $parentCat->slug . '/' . $cat_row->slug; ?>/"><?php echo $cat_row->category_name; ?></a>
            <?php /* <a href="javascript:void(0);" onclick="setValues('categoryId', <?php echo $cat_row->category_id; ?>);"><?php echo $cat_row->category_name; ?></a> */ ?>
        </li>
        <?php
    }
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getCategoryOnCategoryPage') {
    $cat_rs = exec_query("SELECT category_name, category_id FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0", $con);
    ?>
    <strong>ALL CATEGORIES</strong>
    <?php while ($cat_row = mysqli_fetch_object($cat_rs)) { ?>
        <li>
            <?php /* href="javascript:void(0);" onclick="getMaleFemaleOnCategoryPage(<?php echo $cat_row->category_id.", '".$cat_row->category_name."'"; ?>)" */ ?>
            <a href="javascript:void(0);" onclick="getMaleFemaleOnCategoryPage(<?php echo $cat_row->category_id . ", '" . $cat_row->category_name . "'"; ?>)"><?php echo $cat_row->category_name; ?></a>
        </li>
        <?php
    }
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'emptyCart') {
    if (isset($_SESSION['user']) && $_SESSION['user'] != '') {
        $user_id = $_SESSION['user'];
    } elseif (isset($_SESSION['tempUser']) && $_SESSION['tempUser'] != '') {
        $user_id = $_SESSION['tempUser'];
    }
    $rs = exec_query("DELETE FROM tbl_cart WHERE user_id = " . $user_id, $con);
    if ($rs) {
        echo 'Now cart is empty!!!';
    } else {
        echo 'Some error occured, try again later.';
    }
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'removePromoCode') {
    if (isset($_SESSION['user']) && $_SESSION['user'] != '') {
        $user_id = $_SESSION['user'];
    } elseif (isset($_SESSION['tempUser']) && $_SESSION['tempUser'] != '') {
        $user_id = $_SESSION['tempUser'];
    }
    $rs = exec_query("UPDATE tbl_cart SET promo_code_id = 0, product_promo_code_price = 0 WHERE user_id = " . $user_id, $con);
    if ($rs) {
        echo 'Promo Code Removed!!!';
    } else {
        echo 'Some error occured, try again later.';
    }
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'clearBrandCondition') {

    $bQ = "SELECT tbl_brand.brand_name, tbl_brand.brand_id, (
	SELECT COUNT(DISTINCT tp.product_id) AS count FROM tbl_product tp
	LEFT JOIN tbl_product_media tpm ON tpm.product_id = tp.product_id
	LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
	LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
	LEFT JOIN tbl_category tblcat ON tpcat.category_id = tblcat.category_id
	WHERE tpm.media_type = 'img' AND tpm.is_main = 1 AND tp.is_activate = 1 AND tp.brand_id = tbl_brand.brand_id) AS totalProduct
	FROM tbl_brand ORDER BY tbl_brand.brand_name";
    $br_rs = mysqli_query($con, $bQ);
    while ($br_row = mysqli_fetch_object($br_rs)) {
        if ($br_row->totalProduct != 0) {
            $countBPro = $br_row->totalProduct;
            ?>
            <li>
                <input name="brand" type="checkbox" value="<?php echo $br_row->brand_id; ?>" onchange="setMultiChkValuesBrand('brand');" />
                <?php echo $br_row->brand_name . ' (' . $countBPro . ')'; ?>
            </li><?php
        }
    }
    //echo ($countBPro==0)?'disabled':'';
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getFilterBrand') {
    $array = array();
    if (isset($_GET['ids']) && $_GET['ids'] != '' && $_GET['ids'] != 0) {
        $array = explode(',', $_GET['ids']);
    }

    if (isset($_SESSION['sidebarBrandQ'])) {
        $query = $_SESSION['sidebarBrandQ'];
        $query = $_SESSION['sidebarBrandQ'];
        $rs = mysqli_query($con, $query);
        while ($br_row = mysqli_fetch_object($rs)) {
            if ($br_row->totalProduct != 0) {
                $countBPro = $br_row->totalProduct;
                ?>
                <li>
                    <input name="brand" type="checkbox" value="<?php echo $br_row->brand_id; ?>" onchange="setMultiChkValuesBrand('brand');"
                           <?php echo (in_array($br_row->brand_id, $array)) ? 'checked="checked"' : ''; ?> />

                    <?php echo $br_row->brand_name . ' (' . $countBPro . ')'; ?>
                </li>
                <?php
            }
        }
        unset($_SESSION['sidebarBrandQ']);
    }
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getFilterColor') {

    if (isset($_SESSION['productQueryColor'])) {
        $query = $_SESSION['productQueryColor'];
        $rs = mysqli_query($con, $query);
        if (mysqli_num_rows($rs) > 0) {
            $myColors = array();
            while ($row = mysqli_fetch_object($rs)) {
                $myColors[] = $row->color_code . "||" . $row->color;
                $i = 1;
            }
            $colors = array_count_values($myColors);
            foreach ($colors as $key => $color) {
                $col = explode("||", $key);
                $count = $color;
                ?>
                <li class="tooltips">
                    <strong><?php
                        echo $col[1] . ' (';
                        echo ($count == '') ? '0)' : $count . ')';
                        ?></strong>
                    <input name="color" type="checkbox" id="color<?php echo str_replace('#', '', $col[0]); ?>" value="'<?php echo str_replace('#', '@', $col[0]); ?>'" onclick="setMultiChkValues('color');" />
                    <label class="colorBlock" for="color<?php echo str_replace('#', '', $col[0]); ?>" style="background:<?php echo $col[0]; ?>;"><span class="tick">&nbsp;</span> </label>

                </li>
                <?php
            }
        }
        unset($_SESSION['productQueryColor']);
    }
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addToWish') {
    if (isset($_SESSION['user']) && $_SESSION['user'] != '') {
        $pid = $_GET['pid'];
        $cid = $_GET['cid'];
        $user_id = $_SESSION['user'];
        $chk = mysqli_num_rows(exec_query("SELECT recid FROM tbl_user_wishlist WHERE user_id = '$user_id' AND product_id = '$pid' AND color_id = '$cid'", $con));
        if ($chk > 0) {
            $rs = true;
        } else {
            $rs = exec_query("INSERT INTO tbl_user_wishlist(user_id, product_id, color_id, datetime) VALUES('$user_id', '$pid', '$cid', '" . date('c') . "')", $con);
        }

        if ($rs) {
            if (isset($_GET['cartId'])) {
                $cartId = $_GET['cartId'];
                $del_rs = exec_query("DELETE FROM tbl_cart WHERE cart_id = '$cartId'", $con);
            }
            echo 'Product added to Wishlist.';
        } else {
            echo 'Some error occured, try again later.';
        }
    } else {
        echo 'Please login for add product in wishlist, try again later.';
    }
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'emptyWishlist') {
    $user_id = $_SESSION['user'];
    $rs = exec_query("DELETE FROM tbl_user_wishlist WHERE user_id = " . $user_id, $con);
    if ($rs) {
        echo 'Now Wishlist is empty!!!';
    } else {
        echo 'Some error occured, try again later.';
    }
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addToCartFromCategory') {
    if (isset($_GET['qty']) && $_GET['qty'] != '') {
        $qty = $_GET['qty'];
    } else {
        $qty = 1;
    }
    $product_id = $_GET['product_id'];
    $color_id = $_GET['color_id'];
    $backord = 0;
    if (isset($_REQUEST['isbackord'])) {
        $backord = $_REQUEST['isbackord'];
    }
    $price = $_GET['price'];
    $promoPrice = $_GET['promoPrice'];
    $promoId = $_GET['promoId'];

    /* if user is login */
    if (isset($_SESSION['user']) && $_SESSION['user'] != '') {
        $user_id = $_SESSION['user'];
    } else { /* if user is not login - cart will be manage in session, so include function related file */
        if (isset($_SESSION['tempUser']) && $_SESSION['tempUser'] != '') {
            $user_id = $_SESSION['tempUser'];
        } else {
            $_SESSION['tempUser'] = rand(10000000, 99999999);
            $user_id = $_SESSION['tempUser'];
        }
    }
    //check if the product is already out of stock, if so stop the rest process, as well as check available stock level
    $productRow = mysqli_fetch_object(mysqli_query($con, "SELECT stock_availability FROM tbl_product WHERE product_id = '$product_id'"));
    $product =mysqli_fetch_object(mysqli_query($con, "SELECT qty, backorder_qty FROM tbl_product_price WHERE product_id = '$product_id' AND color_id = '$color_id'"));
    $backorderRs = exec_query("SELECT SUM(od_qty) as backorder_qty FROM tbl_order_item WHERE product_id = $product_id AND color_id=$color_id AND isbackOrd=1", $con);
    $backorderRow = mysqli_fetch_object($backorderRs);
    $backorderQty = isset($backorderRow->backorder_qty) ? $backorderRow->backorder_qty : 0;
    $cartQty = 0;
    $cartRs = exec_query("SELECT qty FROM tbl_cart WHERE product_id = $product_id AND color_id = $color_id", $con);
    $cartItem = mysqli_fetch_object($cartRs);
    if (isset($cartItem->qty) && $cartItem->qty > 0 ) {
        $cartQty = $cartItem->qty;
    }
    $availableStock = 0;
    if ($productRow->stock_availability == 2) { //back order allowed for prod
        $availableStock = $product->backorder_qty;
    } else {
        $availableStock = $product->qty;
    }
    $availableStock = $availableStock - $backorderQty;
    $availableStock = $availableStock - $cartQty;
    $availableStock = $availableStock < 0 ? 0 : $availableStock;

    if ($availableStock <= 0) {
         echo 0; //failed
    }else {
        $rs_chk = mysqli_query($con, "SELECT qty FROM tbl_cart WHERE product_id = '$product_id' AND user_id = '$user_id' AND `color_id` = '$color_id'");
        if (mysqli_num_rows($rs_chk)) {
            $rs = mysqli_query($con, "UPDATE tbl_cart SET `qty` = qty+$qty, product_price = '$price', product_promo_price = '$promoPrice', datetime = '" . date('c') . "' WHERE user_id = '$user_id' AND `product_id` = '$product_id' AND `color_id` = '$color_id'");
        } else {
            $rs = mysqli_query($con, "INSERT INTO tbl_cart(`user_id`, `product_id`, `color_id`, `qty`, `product_price`, `product_promo_price`, `promo_id`, `datetime`, `isbackOrd`) VALUES ('$user_id', '$product_id', '$color_id', '$qty', '$price', '$promoPrice', '$promoId', '" . date('c') . "', $backord)");
        }

        if ($rs) {
            echo 1;
        } else {
            echo 0;
        }
    }

} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'removeToCartFromCategory') {

    $product_id = $_GET['product_id'];

    /* if user is login */
    if (isset($_SESSION['user']) && $_SESSION['user'] != '') {
        $user_id = $_SESSION['user'];
    } else { /* if user is not login - cart will be manage in session, so include function related file */
        if (isset($_SESSION['tempUser']) && $_SESSION['tempUser'] != '') {
            $user_id = $_SESSION['tempUser'];
        } else {
            $_SESSION['tempUser'] = rand(10000000, 99999999);
            $user_id = $_SESSION['tempUser'];
        }
    }
    $remove_sql = "DELETE FROM tbl_cart WHERE product_id = '$product_id' AND user_id = '$user_id'";

    if (mysqli_query($con, $remove_sql)) {
        echo 1;
    } else {
        echo 0;
    }
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addToWishFromDetail') {
    if (isset($_SESSION['user']) && $_SESSION['user'] != '') {
        $user_id = $_SESSION['user'];
        $pid = $_GET['pid'];
        $cid = $_GET['cid'];

        $chk = mysqli_num_rows(exec_query("SELECT recid FROM tbl_user_wishlist WHERE user_id = '$user_id' AND product_id = '$pid' AND color_id = '$cid'", $con));
        if ($chk > 0) {
            $rs = '';
        } else {
            $rs = exec_query("INSERT INTO tbl_user_wishlist(user_id, product_id, color_id, datetime) VALUES('$user_id', '$pid', '$cid', '" . date('c') . "')", $con);
        }

        if (isset($rs)) {
            echo 'Product added to Wishlist.';
        } else {
            echo 'Some error occured, try again later.';
        }
    } else {
        echo 'Oops!!! Something went wrong,';
    }
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'moveAllProductCartToWishlist') {
    if (isset($_SESSION['user']) && $_SESSION['user'] != '') {
        $user_id = $_SESSION['user'];

        $rs = exec_query("SELECT * FROM tbl_cart WHERE user_id = '$user_id'", $con);
        if (mysqli_num_rows($rs) > 0) {
            while ($row = mysqli_fetch_object($rs)) {

                $chk = mysqli_num_rows(exec_query("SELECT recid FROM tbl_user_wishlist WHERE user_id = '$user_id' AND product_id = '$row->product_id' AND color_id = '$row->color_id'", $con));
                if ($chk > 0) {

                } else {
                    exec_query("INSERT INTO tbl_user_wishlist(user_id, product_id, color_id, datetime) VALUES('$user_id', '$row->product_id', '$row->color_id', '" . date('c') . "')", $con);
                }
                exec_query("DELETE FROM tbl_cart WHERE cart_id = '$row->cart_id'", $con);
            }
            echo 'Product Moved to Wishlist Successfully,';
        } else {
            echo 'No Product in Cart!!!.';
        }
    } else {
        echo 'Some error occured, try again later.';
    }
}
/* WishToCart jyada code h q kii wish me promotion ki detail nhi hoti, to sara ghapla krna h */ elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'moveAllProductWishlistToCart') {
    if (isset($_SESSION['user']) && $_SESSION['user'] != '') {
        $user_id = $_SESSION['user'];

        $Query = "SELECT tuw.* FROM tbl_user_wishlist tuw WHERE tuw.user_id = '" . $user_id . "' ORDER BY tuw.recid DESC";
        $pro_rs = mysqli_query($con, $Query);
        $num = mysqli_num_rows($pro_rs);
        if ($num > 0) {
            while ($pro_row = mysqli_fetch_object($pro_rs)) {
                $id = $pro_row->product_id;
                $query1 = "SELECT tp.*, tbl_brand.brand_id AS brandId, tbl_brand.brand_name,
				GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat FROM tbl_product tp
				LEFT JOIN tbl_brand ON tbl_brand.brand_id = tp.brand_id
				LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
				WHERE tp.product_id = '$id' GROUP BY tp.product_id";
                $rs = exec_query($query1, $con);
                $row = mysqli_fetch_object($rs);
                /* fetch price */
                $priceRs = exec_query("SELECT product_price FROM tbl_product_price WHERE product_id = '$id' AND color_id = '$pro_row->color_id'", $con);
                $priceRow = mysqli_fetch_object($priceRs);
                $price = $priceRow->product_price;
                /* chk promotion */
                $all_cat = $row->all_cat;
                $promoType = '';
                $promoValue = '';
                $promoId = '';
                $promoArr = getPromotionForProduct($id, $row->brandId, $all_cat, $con);
                if (!empty($promoArr)) {
                    $promoType = $promoArr['percent_or_amount'];
                    $promoValue = $promoArr['promo_value'];
                    $promoId = $promoArr['promo_id'];
                }

                $product_id = $pro_row->product_id;
                $color_id = $pro_row->color_id;

                /* discount de do ab */
                $promoPrice = '';
                if ($promoType != '' && $promoValue != '') {
                    if ($promoType == 'percent') {
                        $promoPrice = $price - (($price * $promoValue) / 100);
                    } elseif ($promoType == 'amount') {
                        $promoPrice = $price - $promoValue;
                    }
                }

                /* DB me dal do */
                $rs_chk = mysqli_query($con, "SELECT qty FROM tbl_cart WHERE product_id = '$product_id' AND user_id = '$user_id' AND `color_id` = '$color_id'");
                if (mysqli_num_rows($rs_chk)) {
                    $rs = mysqli_query($con, "UPDATE tbl_cart SET `qty` = qty+$qty, product_price = '$price', product_promo_price = '$promoPrice', datetime = '" . date('c') . "' WHERE user_id = '$user_id' AND `product_id` = '$product_id' AND `color_id` = '$color_id'");
                } else {
                    $rs = mysqli_query($con, "INSERT INTO tbl_cart(`user_id`, `product_id`, `color_id`, `qty`, `product_price`, `product_promo_price`, `promo_id`, `datetime`) VALUES ('$user_id', '$product_id', '$color_id', '1', '$price', '$promoPrice', '$promoId', '" . date('c') . "')");
                }
                exec_query("DELETE FROM tbl_user_wishlist WHERE recid = '$pro_row->recid'", $con);
            }
            echo 'Product Successfully moved to Cart!!!';
        } else {
            echo 'No Product in Wishlist!!!';
        }
    } else {
        echo 'Some error occured, try again later.';
    }
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getCartDataInDialog') {
    $user_id = '';
    if (isset($_SESSION['user']) && $_SESSION['user'] != '') {
        $user_id = $_SESSION['user'];
    } elseif (isset($_SESSION['tempUser']) && $_SESSION['tempUser'] != '') {
        $user_id = $_SESSION['tempUser'];
    }
    $numCart = 0;
    //echo $user_id;
    if ($user_id != '') {
        $cartQuery = "SELECT tbl_cart.*, tbl_product.slug, tbl_product.product_name, tbl_product.size, tbl_product.product_description, tbl_product_color.color_code, tbl_brand.brand_name FROM tbl_cart
		LEFT JOIN tbl_product ON tbl_product.product_id = tbl_cart.product_id
		LEFT JOIN tbl_brand ON tbl_brand.brand_id = tbl_product.brand_id
		LEFT JOIN tbl_product_color ON tbl_product_color.color_id = tbl_cart.color_id
		WHERE tbl_cart.user_id = '" . $user_id . "'ORDER BY tbl_cart.datetime DESC";
        $pro_rs = mysqli_query($con, $cartQuery);
        $numCart = mysqli_num_rows($pro_rs);
        if ($numCart > 0) {
            $totalPrice = 0;
            while ($pro_row = mysqli_fetch_object($pro_rs)) {
                $img = mysqli_fetch_object(mysqli_query($con,"SELECT media_src, media_thumb FROM tbl_product_media WHERE media_type = 'img' AND product_id = '$pro_row->product_id' AND color_id = '$pro_row->color_id'"));

                if ($pro_row->product_promo_code_price != 0) {
                   $pprice = $pro_row->product_promo_code_price;
                    //$pprice = $pro_row->product_price;
                } elseif ($pro_row->product_promo_price != 0) {
                   $pprice = $pro_row->product_promo_price;
//                    $pprice = $pro_row->product_price;
                } else {
                    $pprice = $pro_row->product_price;
                }
                $ttotal = $pprice * $pro_row->qty;
?>
                <h3> your bag</h3>
                <li>
                    <?php $src = (isset($img->media_thumb) && $img->media_thumb != '') ? 'site_image/product/' . $img->media_thumb : 'images/product.png'; ?>
                    <span class="item">
                        <span class="item-left">
                            <img src="<?php echo siteUrl . $src; ?>" alt=""  style="width:50px; height:50px;"/>
                            <span class="item-info">
                                <span class="neame-p"><?php echo (strlen($pro_row->product_name) > 15) ? substr($pro_row->product_name, 0, 15) . '...' : $pro_row->product_name; ?></span>
                                <span class="qty-p">QTY:&nbsp; <?php echo $pro_row->qty; ?></span>
                                <!--<span class="color-p">Color &nbsp;<i class="fa fa-circle" aria-hidden="true" style=" color:#F1617A;"></i> </span>-->
                                <span class="price-p">$<?php
                                    echo number_format((float) $ttotal, 2, '.', '');
                                    $totalPrice += (float) $ttotal
                                    ?></span>
                            </span>
                        </span>
                        <span class="item-right">
                            <button class="btn btn-xs btn-cros pull-right" onclick="remove_product('<?php echo $user_id; ?>', '<?php echo $pro_row->product_id; ?>', '<?php echo $ttotal; ?>')">x</button>
                        </span>
                    </span>
                </li>
                <!--end-->

            <?php }
            ?>
            <li class="total-price"> <span class="item-left"><strong>Sub Total</strong></span> <span class="item-right"> $<?php echo $totalPrice; ?></span></li>				
            <?php
        }
    } else {
        ?>
        <h3> your bag</h3>
        <li>Your cart is Empty.</li>
        <li class="total-price"> <span class="item-left"><strong>Sub Total</strong></span> <span class="item-right"> $0.00</span></li>
    <?php }
    ?>
    <input type="hidden" id="cartCounter" value="<?php echo $numCart; ?>" /> <?php
} elseif (isset($_GET['action']) && $_GET['action'] == 'doProcess') {
    $email = tres(str_rot13($_GET['data1']));
    $password = tres($_GET['data2']);
    $point = 0;
    $optionBill = '<option value="">- SELECT BILLING INFORMATION -</option>';
    $optionShip = '<option value="">- SELECT SHIPPING INFORMATION -</option>';
    $rs = exec_query("SELECT user_id, email FROM tbl_user WHERE email = '$email' AND password = '$password'", $con);
    if (mysqli_num_rows($rs) && mysqli_num_rows($rs) == 1) {
        $row = mysqli_fetch_object($rs);
        $_SESSION['user'] = $row->user_id;
        $_SESSION['user_email'] = $row->email;
        $data = explode('@', $row->email);
        $_SESSION['user_name'] = $data[0];
        getTempCartToUserCart($_SESSION['user'], $con);
        $msg = '<p>You are Logged in as <strong>' . $row->email . '</strong></p>
		<button onclick="loginContinue();" class="button" type="button">Continue</button>';
        $email = $row->email;
        $point = getUserPoint($row->user_id, $con);

        $oldOrderQ = "SELECT * FROM tbl_order WHERE user_id = '$row->user_id' AND od_shipping_postal_code != '' AND od_shipping_address != '' AND od_shipping_locality != '' GROUP BY od_shipping_postal_code, od_shipping_address, od_shipping_locality";
        $oldOrderRs = exec_query($oldOrderQ, $con);
        while ($oOrderRow = mysqli_fetch_object($oldOrderRs)) {

            $oData = '';
            $oData = $oOrderRow->od_billing_first_name . ' ' . $oOrderRow->od_billing_last_name . ', ';
            $oData .= $oOrderRow->od_billing_address . ', ' . $oOrderRow->od_billing_locality . ', ';
            $oData .= $oOrderRow->od_billing_city . ', ' . $oOrderRow->od_billing_postal_code . ', ';
            $oData .= 'Phone: ' . $oOrderRow->od_billing_phone;
            $optionBill .= '<option value="' . $oOrderRow->order_id . '">' . $oData . '</option>';

            $oData2 = '';
            $oData2 .= $oOrderRow->od_shipping_first_name . ' ' . $oOrderRow->od_shipping_last_name . ', ';
            $oData2 .= $oOrderRow->od_shipping_address . ', ' . $oOrderRow->od_shipping_locality . ', ';
            $oData2 .= $oOrderRow->od_shipping_city . ', ' . $oOrderRow->od_shipping_postal_code . ', ';
            $oData2 .= 'Phone: ' . $oOrderRow->od_shipping_phone;
            $optionShip .= '<option value="' . $oOrderRow->order_id . '">' . $oData2 . '</option>';
        }
        $process = true;
    } else {
        $msg = "Invalid Email Address or Password for Login !";
        $process = false;
        $email = '';
    }
    $array = array('process' => $process, 'msg' => $msg, 'email' => $email, 'point' => $point, 'optionBill' => $optionBill, 'optionShip' => $optionShip);
    echo json_encode($array);
} elseif (isset($_GET['action']) && $_GET['action'] == 'getShippingCharges') {
    $price = '';
    $city = addslashes($_GET['city']);
    $postcode = $_GET['postcode'];
    $postcode1 = ltrim($postcode, '0');
    $chkCity = mysqli_query($con, "SELECT sector_code, is_rural FROM tbl_shipping_sector WHERE postcode = '$postcode' OR postcode = '$postcode1'");
    if (mysqli_num_rows($chkCity)) {
        $rowCity = mysqli_fetch_object($chkCity);
        if (isset($rowCity->sector_code) && $rowCity->sector_code != '') {
            $sector = $rowCity->sector_code;
            $chkShip = mysqli_query($con, "SELECT price FROM tbl_shipping_price WHERE sector_code = '$sector'");
            if ($chkShip) {
                $rowPrice = mysqli_fetch_object($chkShip);
                if (isset($rowPrice->price) && $rowPrice->price != '') {
                    $price = $rowPrice->price;
                    $valid = true;
                } else {
                    $valid = false;
                }
            } else {
                $valid = false;
            }
        } else {
            $valid = false;
        }
    } else {
        $valid = false;
    }
    echo json_encode(array('valid' => $valid, 'price' => $price, 'isRural' => $rowCity->is_rural, 'sectorCode' => $rowCity->sector_code));
} elseif (isset($_GET['action']) && $_GET['action'] == 'setAddressDataFromSelect') {
    $orderId = $_GET['id'];
    $type = $_GET['type'];
    $oldOrderQ = "SELECT * FROM tbl_order WHERE order_id = '$orderId'";
    $oldOrderRs = exec_query($oldOrderQ, $con);
    $order = mysqli_fetch_object($oldOrderRs);
    // 'altPhone' => $order->od_billing_alt_phone,
    // 'altPhone' => $order->od_shipping_alt_phone,
    if ($type == 'bill') {
        $array = array('fname' => $order->od_billing_first_name, 'lname' => $order->od_billing_last_name, 'locality' => $order->od_billing_locality,
            'address' => $order->od_billing_address, 'phone' => $order->od_billing_phone,
            'city' => $order->od_billing_city, 'postal_code' => $order->od_billing_postal_code, 'lat' => $order->od_billing_lat,
            'lng' => $order->od_billing_lng, 'valid' => true
        );
    } elseif ($type == 'ship') {
        $array = array('fname' => $order->od_shipping_first_name, 'lname' => $order->od_shipping_last_name, 'locality' => $order->od_shipping_locality,
            'address' => $order->od_shipping_address, 'phone' => $order->od_shipping_phone,
            'city' => $order->od_shipping_city, 'postal_code' => $order->od_shipping_postal_code, 'lat' => $order->od_shipping_lat,
            'lng' => $order->od_shipping_lng, 'valid' => true
        );
    }
    echo json_encode($array);
} elseif (isset($_GET['action']) && $_GET['action'] == 'changeUPC') {
    $rs_pro = mysqli_query($con, "SELECT * FROM tbl_product");
    while ($row = mysqli_fetch_object($rs_pro)) {
        $pid = $row->product_id;

        $rss = mysqli_query($con, "SELECT * FROM tbl_product_price WHERE product_id = '$pid'");
        if (mysqli_num_rows($rss) == 1 && $row->product_upc != '') {
            $rowww = mysqli_fetch_object($rss);
            mysqli_query($con, "UPDATE tbl_product_price SET product_upc = '$row->product_upc' WHERE recid = '$rowww->recid'");
            echo $pid . '<br/>';
        }
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'applyForCredit') {
    $userId = $_SESSION['user'];
    $id = $_GET['data1'];
    $chkRs = mysqli_query($con, "SELECT * FROM tbl_order WHERE order_id = '$id' AND point_status = 0", $con); // 0 means abi nh die point
    if (mysqli_num_rows($chkRs)) {
        $row = mysqli_fetch_object($chkRs);
        $pointAm = 100;
        $pointOnAm = 4;
        if (isset($row->amount) && $row->amount > 0 && $row->amount >= $pointAm) {
            $amount = $row->amount;

            // abi dynamic nhi h, let 100$ = 4Point
            //$pointEarn = floor($amount * $pointUnit);
            $point = floor($amount / $pointAm);
            $pointEarn = $point * $pointOnAm;

            $pointQ = "INSERT INTO `tbl_user_point` (`order_id`, `user_id`, `point`, `datetime`) VALUES('$id', '$userId', '$pointEarn', '" . date('c') . "')";
            if (mysqli_query($con, $pointQ)) {
                mysqli_query($con, "UPDATE tbl_order SET point_status = 1 WHERE order_id = '$id'");
                echo 'Apply for Credit Completed Successfully';
            } else {
                echo 'Failed, Some Error Occured!';
            }
        } else {
            echo 'Failed, Order Amount must be greater than 100$!';
        }
    } else {
        echo 'Failed, Some Error Occured!';
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'applyCreditOnOrder') {
    
} elseif (isset($_GET['action']) && $_GET['action'] == 'getSlugByCat') {
    $id = $_GET['data1'];
    $rs = exec_query("SELECT slug FROM tbl_category WHERE category_id = '$id'", $con);
    $row = mysqli_fetch_object($rs);
    echo $row->slug;
} elseif (isset($_GET['action']) && $_GET['action'] == 'contactUs') {
    $fname = $_GET['fname'];
    $lname = $_GET['lname'];
    $email = $_GET['email'];
    $type = $_GET['qtype'];
    $desc = $_GET['desc'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 0;
        die();
    }

    $subject = 'A user want to contct you - JHM Shop';
    $content = '<html>
	<head>
		<style>table tr td{ background:#ececec; padding: 7px; }</style>
	</head>
	<body>
	<table cellpadding="5" cellspacing="5">
		<tr>
			<td colspan="2">A user want to contct you - JHM Shop</td>
		</tr>
		<tr>
			<td><b>First Name</b></td>
			<td>' . $fname . '</td>
		</tr>
		<tr>
			<td><b>Last Name</b></td>
			<td>' . $lname . '</td>
		</tr>
		<tr>
			<td><b>Email Address</b></td>
			<td>' . $email . '</td>
		</tr>
		<tr>
			<td><b>Query Type</b></td>
			<td>' . $type . '</td>
		</tr>
		<tr>
			<td><b>Query</b></td>
			<td>' . $desc . '</td>
		</tr>
	</table>
	</body>
	</body>';
    sendMail($subject, $content, array('support@jhm.co.nz'));
    echo 1;
}

function getTempCartToUserCart($uid, $con) {
    if (isset($_SESSION['tempUser']) && $_SESSION['tempUser'] != '') {
        $tempUser = $_SESSION['tempUser'];
        $allRs = exec_query("SELECT * FROM tbl_cart WHERE user_id = $tempUser", $con);
        while ($row = mysqli_fetch_object($allRs)) {
            $rs_chk = mysqli_query($con, "SELECT qty FROM tbl_cart WHERE product_id = '$row->product_id' AND user_id = '$uid' AND `color_id` = '$row->color_id'");
            if (!mysqli_num_rows($rs_chk)) {
                $upDate = exec_query("UPDATE tbl_cart SET user_id = $uid WHERE cart_id = $row->cart_id ", $con);
            } else {
                $upDate = exec_query("DELETE FROM tbl_cart WHERE cart_id = $row->cart_id ", $con);
            }
        }
        unset($_SESSION['tempUser']);
    }
}
?>