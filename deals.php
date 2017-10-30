<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>
<?php $topOffer = mysql_fetch_object(exec_query("SELECT no FROM tbl_config WHERE type = 'dealsTopOffer'", $con));
$superDealNo = mysql_fetch_object(exec_query("SELECT no FROM tbl_config WHERE type = 'dealsSuper'", $con)); ?>

<section class="block-pt5">
    <div class="container">
        <div class="other-page-holder">
             <div class="col-md-12">
    <div id="DealBanner" style="text-align:center;">
		<?php $imgBanner = mysql_fetch_object(exec_query("SELECT other FROM tbl_config WHERE type = 'dealBanner'", $con));
    	if(isset($imgBanner->other) && $imgBanner->other != ''){ ?>
			<img  style="max-width:100%;" src="<?php echo siteUrl; ?>site_image/promotion/<?php echo $imgBanner->other; ?>" />
		<?php }else{ ?>
                        <img src="<?php echo siteUrl; ?>images/bannerdeas.jpg" style="max-width:100%;" />
        <?php } ?>
    </div>
                 
                 
             <div id="deals">
	 		<div id="topoffers">
                <h2 class="offerHeading">Top Offers</h2>
                <h3 class="offersubHeading">Our top offers for the day in one place</h3>
                <ul id="topdeals">
                <?php $limit1 = 7;
                $today = date('Y-m-d');
                $now = date('Y-m-d H:i:s');
                if(isset($topOffer->no) && $topOffer->no != 0){ $limit1 = $topOffer->no; }
                $deal_q = "SELECT promo.*, tpd.ids, tpd.category_id FROM tbl_promotion promo
                LEFT JOIN tbl_promotion_detail tpd ON tpd.promo_id = promo.promo_id
                WHERE promo.is_publish = 1 AND DATE_FORMAT(promo.end_date, '%Y-%m-%d %H:%i:%s') > '$now'
                ORDER BY promo.end_date DESC limit 0, $limit1";
                $deals=mysql_query($deal_q);
                while($deal=mysql_fetch_object($deals)){ ?>
                <li>
                    <a href="<?php echo siteUrl; ?>product-promotion/<?php echo $deal->slug; ?>/">
                    <span class="dealpicture"><img src="<?php echo siteUrl; ?>site_image/promotion/<?php echo $deal->product_img; ?>"/></span>
                    <h2><?php echo $deal->title; ?></h2>
                    <p> FLAT 60% OFF</p>
                    </a>
                </li>
                <?php } ?>
                </ul>
                <div id="clr"></div>
            </div>
            
            <div id="superDeals" style="display:none;">
                <h2 class="offerHeading">Super Deals</h2>
                <h3 class="offersubHeading">New limited time events everyday</h3>
                <ul id="superDeal">
                <?php $limit2 = 2;
                $today = date('Y-m-d');
                $now = date('Y-m-d H:i:s');
                if(isset($superDealNo->no) && $superDealNo->no != 0){ $limit2 = $superDealNo->no; }
                $superDeal = "SELECT promo.*, tpd.ids, tpd.category_id FROM tbl_promotion promo
                LEFT JOIN tbl_promotion_detail tpd ON tpd.promo_id = promo.promo_id
                WHERE promo.is_publish = 1 AND is_super = 1 AND DATE_FORMAT(promo.end_date, '%Y-%m-%d %H:%i:%s') > '$now'
                ORDER BY promo.end_date  DESC limit 0, $limit2";
                $deals=mysql_query($superDeal);
                $i=1;
                while($deal=mysql_fetch_object($deals)){
                ?>
                <li>
                    <a href="<?php echo siteUrl; ?>product-promotion/<?php echo $deal->slug; ?>/">
                    <span class="dealpicture"><img src="<?php echo siteUrl; ?>site_image/promotion/<?php echo $deal->product_img; ?>"/></span>
                    <h1><?php echo $deal->title; ?></h1>
                    </a>
                    <div id="countbars">
                        <a href="<?php echo siteUrl; ?>product-promotion/<?php echo $deal->slug; ?>/" class="buynow bttn_1 addtocatbtn">Explore More</a>
                        <span class="countdown" id="promoid<?php echo $deal->promo_id; ?>">
                            <strong>Deals end in </strong>
                            <span class="countdownarea" id="countdown<?php echo $i; ?>"><?php echo $deal->end_date; ?> GMT+00:00</span>
                        </span>
                        <div id="clr"></div>
                    </div>
                </li>
                <?php //echo ($i%2==0)?'<div id="clr"></div>':'';
                $i++; } ?>
                </ul>
            </div>
        </div>    
                 
                 
                 </div>
            
            
            
            <div class="clr"></div>
        </div>
            
    </div>
</section>

    
<?php include("include/new_footer.php"); ?>

<script type="text/javascript" src="<?php echo siteUrl; ?>js/countdown.js" defer="defer"></script>
    
<?php include("include/new_bottom.php"); ?>