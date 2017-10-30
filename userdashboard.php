<?php include "include/new_top.php";?>

<?php include "include/new_header.php";?>

<?php
if (!isset($_SESSION['user']) || $_SESSION['user'] == '' || empty($_SESSION['user'])) {redirect(siteUrl);}
$user_id = $_SESSION['user'];
?>
<section class="block-pt5">
    <div class="container">

	<div  class="dashboard-holder">
            <div class="col-sm-12">
    	<h1 class="mainHeading">User Dashboard </h1>
            </div>
           <div class="col-md-3 col-sm-4">
      	<?php include 'include/userNavigation.php';?>
           </div>

            <div class="col-md-9 col-sm-8">
                <div class="clr"> </div>
      	<div id="profileinfo">
			<h2>Hi, <span><?php echo $_SESSION['user_name']; ?></span>	</h2>
                <div class="clr"> </div>
		</div>

    	<div id="infobox">
            <ul>
                <li class="products1">
                    <h2>Cart</h2>
                    <ul>
                    <?php // cartttttttttttt
$cartQuery = "SELECT tbl_cart.*, tbl_product.product_name FROM tbl_cart
                    LEFT JOIN tbl_product ON tbl_product.product_id = tbl_cart.product_id
                    WHERE tbl_cart.user_id = '" . $user_id . "' ORDER BY tbl_cart.datetime DESC LIMIT 0,4";
$cartRs = mysqli_query($con, $cartQuery);
if (mysqli_num_rows($cartRs)) {
	while ($cartRow = mysqli_fetch_object($cartRs)) {
		$img = mysqli_fetch_object(mysqli_query($con, "SELECT media_src, media_thumb FROM tbl_product_media WHERE media_type = 'img' AND product_id = '$cartRow->product_id' AND color_id = '$cartRow->color_id'"));
		$src = (isset($img->media_thumb) && $img->media_thumb != '') ? 'site_image/product/' . $img->media_thumb : 'images/product.png';
		?>
                    		<li>
                                <img src="<?php echo siteUrl . $src; ?>" style="float:left;" />
                                <h4><?php echo $cartRow->product_name . ' (' . $cartRow->qty . ' qty)'; ?></h4>
                            </li>
                        <?php }?>
                        <a href="<?php echo siteUrl; ?>cart/" class="cartbutton">View Cart</a>
                    <?php } else {?>
                        <li> No Products </li>
                    <?php }?>
                    <div class="clr"> </div>
                    </ul>
                </li>

                <li class="products1">
                    <h2>Wishlist</h2>
                    <ul>
                    <?php // wishlisttttttttttt
$wlQuery = "SELECT tuw.*, tbl_product.product_name FROM tbl_user_wishlist tuw
                    LEFT JOIN tbl_product ON tbl_product.product_id = tuw.product_id
                    WHERE tuw.user_id = '" . $user_id . "' ORDER BY tuw.recid DESC LIMIT 0,4";
$wlRs = mysqli_query($con, $wlQuery);
if (mysqli_num_rows($wlRs)) {
	while ($wlRow = mysqli_fetch_object($wlRs)) {
		$img = mysqli_fetch_object(mysqli_query($con, "SELECT media_src, media_thumb FROM tbl_product_media WHERE media_type = 'img' AND product_id = '$wlRow->product_id' AND color_id = '$wlRow->color_id'"));
		$src = (isset($img->media_thumb) && $img->media_thumb != '') ? 'site_image/product/' . $img->media_thumb : 'images/product.png';
		?>
                            <li>
                            	<img src="<?php echo siteUrl . $src; ?>" style="float:left;" />
                                <h4><?php echo $wlRow->product_name; ?></h4>
                            </li>
                        <?php }?>
                        <a href="<?php echo siteUrl; ?>mywish/" class="cartbutton">View Wishlist</a>
                    <?php } else {?>
                        <li> No Products </li>
                    <?php }?>
                    </ul>
                </li>

                <li class="products1">
                    <h2>Order</h2>
                    <ul id="wishlist">
                    <?php // orderrrrr
$query = "SELECT ord.* FROM tbl_order ord WHERE ord.user_id = '$user_id' ORDER BY ord.order_id DESC LIMIT 0,4";
$rs = mysqli_query($con, $query);
if (mysqli_num_rows($rs)) {
	while ($row = mysqli_fetch_object($rs)) {
		$orderId = $row->order_id;
		$orderNo = getOrderId($orderId, $con);?>
							<li>
								<h4><?php echo $orderNo; ?> ($ <?php echo $row->amount; ?>)</h4>
                                <p><?php echo date('d M, Y h:i A', strtotime($row->od_date)); ?> </p>

							</li>
                  	<?php }?>
                    	<a href="<?php echo siteUrl; ?>userorder/" class="cartbutton">View Order</a>
                    <?php } else {?>
                        <li> No Orders </li>
                    <?php }?>
                    </ul>
                </li>

                <div id="clr">	</div>
            </ul>
            	<div class="clr"> </div>
        </div>

            </div>

      	<div class="clr"> </div>
	</div>


  </div>
</section>
<?php include "include/new_footer.php";?>
<?php include "include/new_bottom.php";?>