<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>
<section id="main-slider"  data-ride="carousel">
    <div class="carousel slide wet-asphalt hidden-xs">
        <ol class="carousel-indicators">
            <li data-target="#main-slider" data-slide-to="0" class="active"></li>
            <li data-target="#main-slider" data-slide-to="1"></li>

        </ol>
        <div class="carousel-inner">
            <?php $cdate = date('Y-m-d H:i:s');
				$slideRs = exec_query("SELECT tpm.banner, tp.slug FROM tbl_promotion_master tpm LEFT JOIN tbl_promotion tp ON tp.promo_id = tpm.promo_id WHERE (DATE_FORMAT(tp.start_date, '%Y-%m-%d %H:%i:%s') <= '$cdate' AND DATE_FORMAT(tp.end_date, '%Y-%m-%d %H:%i:%s') >= '$cdate') AND tpm.is_activate = 1", $con);
				$i=1;
                                while($slideRow = mysqli_fetch_object($slideRs)){ ?>
            <div class="item <?=($i==1)?'active':''?>">
                <div class="container">
                    <a href="<?php echo siteUrl; ?>promotionproduct/<?php echo $slideRow->slug; ?>/">    
                        <img src="site_image/promotion/<?php echo $slideRow->banner; ?>" class="img-responsive" alt="" style="width:100%">     
                    </a>
				
                    </div>
            </div>
            <?php $i++; } ?>


        </div>
        <!--/.carousel-inner-->
        <a class="prev hidden-xs" href="#main-slider" data-slide="prev"> <i class="fa fa-angle-left"></i> </a> <a class="next hidden-xs" href="#main-slider" data-slide="next"> <i class="fa fa-angle-right"></i> </a> </div>
    <!--/.carousel-->
</section>
<!--/#main-slider-->

<div class="clearfix"></div>
<section class="block-pt1">
    <div class="container">
        <div class="row">
            <div class="first-prod">
                <?php
                $sql = "SELECT * FROM tbl_product as tp, (SELECT * FROM tbl_product_media WHERE is_main=1) as tpm, tbl_product_price as tpp WHERE tpm.product_id=tp.product_id and tp.promotion_state=1 and tp.is_activate=1 and tp.product_id=tpp.product_id GROUP BY tp.product_id";
                $promotedProductRs = mysqli_query($con,$sql);
                
                $index = 0;
                while ($promotedProduct = mysqli_fetch_object($promotedProductRs)) {
//                    echo "<pre>";
//                print_r($promotedProduct);exit;
                //taslim//
                $cat_id=exec_query("select * from tbl_product_category where product_id=$promotedProduct->product_id", $con);
                $productRow = mysqli_fetch_object($cat_id);
                /* chk promotion */
$all_cat = $productRow->category_id;
$promoType = ''; $promoValue = '';
$promoArr = getPromotionForProduct($promotedProduct->product_id, $promotedProduct->brand_id, $all_cat, $con);
if(!empty($promoArr)){
	$promoType = $promoArr['percent_or_amount'];
	$promoValue = $promoArr['promo_value'];
}
                //taslim//
                    $img = 'site_image/product/' . $promotedProduct->media_src;
                    ?>

                    <div class="col-sm-3 new-merg">
                        <div class="feture-grid">
                            <?php
                            
                        if($promotedProduct->product_rrp > 0 && ($promoType == '' && $promoValue == '') && ($promotedProduct->product_rrp!=$promotedProduct->product_price) && ($promotedProduct->product_rrp > $promotedProduct->product_price)){
                               
                            $off = (($promotedProduct->product_rrp - $promotedProduct->product_price) / $promotedProduct->product_rrp) * 100;
                            if(!$off==0){
                                ?>
                                <div class="discount hh"> <span><?php echo substr($off, 0, 2); ?>% OFF</span></div>
                                <?php
                            }
                            }
                            if($promoType != '' && $promoValue != ''){
							if($promoType == 'percent'){ 
    							$disPrice = $promotedProduct->product_price-round((($promotedProduct->product_price * $promoValue) / 100), 3);
    							$discount=str_replace(".00", "",$promoValue)."% <br/>Off";
    						}elseif($promoType == 'amount'){
    							$disPrice = $promotedProduct->product_price - $promoValue; 
    							$discount = "Save $".$promoValue; 
    						}
                            if ($discount!="0% <br/>Off" && $discount!="Save $0") {      
                            ?>
                                <div class="discount tt"> <span><?php echo $discount;?></span></div>
                            <?php   }} ?>
                            <a href="<?php echo siteUrl; ?>detail/<?php echo $promotedProduct->product_id; ?>HXYK<?php echo $promotedProduct->slug; ?>TZS1YL<?php echo $promotedProduct->color_id; ?>" class="">
                                <img style="max-width: 100%;height:200px;" src="<?php echo siteUrl . $img; ?>" class="center-block img-style" >
                            </a>
                            <!--                            <h1>ALBION</h1>-->
                            <p style="height: 64px;overflow: hidden;">
                                <a href="<?php echo siteUrl; ?>detail/<?php echo $promotedProduct->product_id; ?>HXYK<?php echo $promotedProduct->slug; ?>TZS1YL<?php echo $promotedProduct->color_id; ?>" class="">
                                    <?php // echo (strlen($promotedProduct->product_name) > 40) ? substr($promotedProduct->product_name, 0, 40) . '...' : $promotedProduct->product_name; ?>
                                    <?php echo $promotedProduct->product_name; ?>
                                </a>
                            </p>
                            <ul>
                                <?php
                                //taslim//
                                if($promoType != '' && $promoValue != ''){
						if($promoType == 'percent'){ 
							
							$disPrice = $promotedProduct->product_price-round((($promotedProduct->product_price * $promoValue) / 100), 2);
							$discount=str_replace(".00", "",$promoValue)."% <br/>Off";
						}elseif($promoType == 'amount'){
							$disPrice = $promotedProduct->product_price - $promoValue; 
							$discount = "Save $".$promoValue; 
						}
						//$gstDisPrice = $disPrice + (($disPrice * 15) / 100);
						?>
                        <li class=""><del>$ <?php echo formatCurrency($promotedProduct->product_price); ?></del></li>
                                
                                <li class="productRRP">$<?php echo round($disPrice, 2, PHP_ROUND_HALF_UP); ?>&nbsp;&nbsp;&nbsp;</li>
                                <?php }if(($promotedProduct->product_rrp != $promotedProduct->product_price) && ($promoType == '' && $promoValue == '') ){ 
                                    $disPrice=$promotedProduct->product_rrp;
                                    ?>
                                <li class="producePrice">$<?php echo $promotedProduct->product_rrp ?></li>
                                <?php }if($promoType == '' && $promoValue == ''){ 
                                    $disPrice=$promotedProduct->product_price;
                                    ?>
                                <li class="productRRP">$<?php echo $promotedProduct->product_price ?></li>
                                <?php } ?>
                             </ul>
                            <!--<div class="know-more1"><a href="javascript:void(0);" onclick="addProductToCart(<?php //echo $promotedProduct->product_id . ',' . $promotedProduct->color_id . ",'" . $promotedProduct->product_price . "','" . $promotedProduct->product_rrp . "','0','0'"; ?>);" class="more-one"><img src="<?php echo siteUrl; ?>/images/new/btn_one.png"></a></div>-->
                            <div class="know-more1"><a href="javascript:void(0);" onclick="addProductToCart(<?php echo $promotedProduct->product_id . ',' . $promotedProduct->color_id . ",'" . $promotedProduct->product_price . "','" . $disPrice . "','0','0'"; ?>);" class="more-one"><img src="<?php echo siteUrl; ?>/images/new/btn_one.png"></a></div>
                        </div>    
                    </div>
                    <?php
                    $index ++;
                }
                ?>
            </div>
        </div>
    </div>
</section>

<section class="block-pt2">
    <div class="container">
        <div class="row">

            <div class="col-sm-12 big-top-head"><img src="<?php echo siteUrl; ?>/images/new/big-heading.png"></div>
        </div>

    </div>
</section>

<section class="block-pt3">
    <div class="container">
        <?php 
        
         $cats = mysqli_query($con,"select * from tbl_category where parent_id = '0' and superparent_id = '0' and flag='1' and is_featured='1'");
            while ($cat = mysqli_fetch_object($cats)) {
                
       
        ?>
        
        <div class="col-sm-4 second-new-merg">
            <div class="color-thum" style="background-color:<?php echo $cat->color_code;?>;">
                <table width="100%" border="0">
                  <!--   <tr>
                        <td class="top-text"><h1><?php echo $cat->category_name;?></h1></td>
                    </tr> -->
                    <tr>
                        <td class="top-left-img pull-right"><img src="<?php echo siteUrl; ?>site_image/category_image/<?php echo $cat->category_image;?>"></td>
                    </tr>
                    <tr>
                        <td><a href="<?php echo siteUrl; ?>product-search?cat_id=<?php echo $cat->category_id?>" class="top-right-btn">Shop Now</a></td>
                    </tr>
                </table>
            </div>
        </div>

            <?php }?>

    </div>

</section>
<?php include("include/new_footer.php"); ?>
<script type="text/javascript" src="<?php echo siteUrl; ?>js/countdown.js" defer="defer"></script>

<script>
                                function addProductToCart(pdId, colId, price, promotionPrice, promotionId, isbackord) {
                                    // PELE QUANTITY LELO
                                    var qty = 1;
                                    var xmlhttp;
                                    if (window.XMLHttpRequest) {
                                        xmlhttp = new XMLHttpRequest();
                                    }
                                    else {
                                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                                    }
                                    xmlhttp.onreadystatechange = function() {
                                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                            if (xmlhttp.responseText == 1) {
                                                getCartDataInDialog();
                                            }
                                            else {                                                
                                                alert('Product is out of stock, please contact support for product availability.');
                                            }
                                        }
                                    }
                                    xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?qty=" + qty + "&action=addToCartFromCategory&product_id=" + pdId + "&isbackord=" + isbackord + "&color_id=" + colId + "&price=" + price + "&promoPrice=" + promotionPrice + "&promoId=" + promotionId + "&dataTempId=c3vcfa1543652de90hch14lkf217900.cloud.uk", true);
                                    xmlhttp.send();
                                }
</script>
<!--<script>
    function remove_product(user_id, product_id, price) {
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }
        else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == 1) {
                    getCartDataInDialog();
                }
                else {
                    alert('Oops!! Product not added to cart,');
                }
            }
        }
        xmlhttp.open("GET", "<?php //echo siteUrl; ?>ajax.php?action=removeToCartFromCategory&product_id=" + product_id + "&price=" + price + "&dataTempId=c3vcfa1543652de90hch14lkf217900.cloud.uk", true);
        xmlhttp.send();
    }
</script>-->
<?php include("include/new_bottom.php"); ?>