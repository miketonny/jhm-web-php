<aside class="left-panel">
	<div class="user text-center">
        <a href="home.php"><img src="assets/images/avtar/jhm-logo.png" alt="..."></a>
        <h4 class="user-name"><?php echo siteName; ?></h4>
	</div>
    
    <?php
    $managerPerm = chkPermission('manager', $con);
	$userPerm = chkPermission('user', $con);
	$brandPerm = chkPermission('brand', $con);
	$categoryPerm = chkPermission('category', $con);
	$productPerm = chkPermission('product', $con);
	$promotionPerm = chkPermission('promotion', $con);
	$reviewPerm = chkPermission('review', $con);
	$cmsPerm = chkPermission('cms', $con);
	//print_r($productPerm);
	?>
    
	<nav class="navigation">
		<ul class="list-unstyled">
			<?php $pagee = basename($_SERVER['PHP_SELF']);
			if($managerPerm[0]){
			if($pagee == 'manager.php' || $pagee == 'managerAdd.php' || $pagee == 'managerPermission.php'){ $csss1 = 'active'; $csss2 = 'style="display:block;"'; }
			else{ $csss1 = ''; $csss2 = ''; }
			?>
			<li class="has-submenu <?php echo $csss1; ?>"><a href="#"><i class="fa fa-user"></i> <span class="nav-label small">Manager & Moderators</span></a>
				<ul class="list-unstyled" <?php echo $csss2; ?>>
					<?php if($managerPerm['edit'] || $managerPerm['status'] || $managerPerm['read']){ ?>
                    	<li><a href="manager.php">All Managers</a></li>
                    <?php }if($managerPerm['add']){ ?>
						<li><a href="managerAdd.php">Add New Manager</a></li>
                    <?php } ?>
				</ul>
			</li>
			<?php }if($userPerm[0]){
			if($pagee == 'user.php' || $pagee == 'userEdit.php' || $pagee == 'userNewsLetter.php'){ $csss1 = 'active'; $csss2 = 'style="display:block;"'; }
			else{ $csss1 = ''; $csss2 = ''; }
			?>
			<li class="has-submenu <?php echo $csss1; ?>"><a href="#"><i class="fa fa-users"></i> <span class="nav-label">Customer</span></a>
				<ul class="list-unstyled" <?php echo $csss2; ?>>
					<?php if($userPerm['edit'] || $userPerm['status'] || $userPerm['read']){ ?>
                    	<li><a href="user.php">All Customers</a></li>
                    <?php }if($userPerm['edit'] || $userPerm['status'] || $userPerm['read']){ ?>
                    	<li><a href="userNewsLetter.php">All Newsletter Customers</a></li>
                    <?php } ?>
				</ul>
			</li>
			<?php }if($brandPerm[0]){
			if($pagee == 'brand.php' || $pagee == 'brandAdd.php' || $pagee == 'brandEdit.php' || $pagee == 'brandColorEdit.php'){ $csss1 = 'active'; $csss2 = 'style="display:block;"'; }
			else{ $csss1 = ''; $csss2 = ''; }
			?>
			<li class="has-submenu <?php echo $csss1; ?>"><a href="#"><i class="fa fa-flag-o"></i> <span class="nav-label">Brand</span></a>
				<ul class="list-unstyled" <?php echo $csss2; ?>>
					<?php if($brandPerm['edit'] || $brandPerm['status'] || $brandPerm['read']){ ?>
                    	<li><a href="brand.php">All Brands</a></li>
                    <?php }if($brandPerm['add']){ ?>
						<li><a href="brandAdd.php">Add New Brand</a></li>
                    <?php } ?>
				</ul>
			</li>
			<?php }if($categoryPerm[0]){
			if($pagee == 'admincategory.php' || $pagee == 'categoryAdd.php' || $pagee == 'categoryEdit.php' || $pagee == 'categoryTree.php'){ $csss1 = 'active'; $csss2 = 'style="display:block;"'; }
			else{ $csss1 = ''; $csss2 = ''; }
			?>
			<li class="has-submenu <?php echo $csss1; ?>"><a href="#"><i class="fa fa-file-text-o"></i> <span class="nav-label">Category</span></a>
				<ul class="list-unstyled" <?php echo $csss2; ?>>
					<?php if($categoryPerm['edit'] || $categoryPerm['status'] || $categoryPerm['read']){ ?>
 	                   	<li><a href="admincategory.php">All Categories</a></li>
                    <?php }if($categoryPerm['add']){ ?>
						<li><a href="categoryAdd.php">Add New Category</a></li>
                   	<?php } ?>
                    <li><a href="categoryTree.php">Category Tree</a></li>
				</ul>
			</li>
			<?php }if($productPerm[0]){
			if($pagee == 'product.php' || $pagee == 'productAdd.php' || $pagee == 'productAddDesc.php' || $pagee == 'productAddDetail.php' || $pagee == 'productEdit.php' || $pagee == 'productStockUpdates.php' || $pagee == 'productAddBulk.php'){ $csss1 = 'active'; $csss2 = 'style="display:block;"'; }
			else{ $csss1 = ''; $csss2 = ''; }
			?>
			<li class="has-submenu <?php echo $csss1; ?>"><a href="#"><i class="fa fa-heart-o"></i> <span class="nav-label">Product</span></a>
				<ul class="list-unstyled" <?php echo $csss2; ?>>
					<?php if($productPerm['add'] || $productPerm['edit'] || $productPerm['status'] || $productPerm['read']){ ?>
                    	<li><a href="product.php">All Products</a></li>
                    <?php }if($productPerm['add']){ ?>
						<li><a href="productAdd.php">Add New Product</a></li>
                        <li><a href="productAddBulk.php">Add Products Bulk</a></li>
                    <?php } ?>
                    <li><a href="productStockUpdates.php">Stock Updates</a></li>
				</ul>
			</li>
			<?php }if($promotionPerm[0]){
			if($pagee == 'promotion.php' || $pagee == 'promotionAdd.php' || $pagee == 'promotionEdit.php' || $pagee == 'promotionMaster.php' || $pagee == 'promoCodeAdd.php' || $pagee == 'promoCode.php'  || $pagee == 'promoCodeEdit.php' || $pagee == 'adminDeal.php'){ $csss1 = 'active'; $csss2 = 'style="display:block;"'; }
			else{ $csss1 = ''; $csss2 = ''; }
			?>
			<li class="has-submenu <?php echo $csss1; ?>"><a href="#"><i class="fa fa-gift"></i> <span class="nav-label">Promotion</span></a>
				<ul class="list-unstyled" <?php echo $csss2; ?>>
					<?php if($promotionPerm['edit'] || $promotionPerm['status'] || $promotionPerm['read']){ ?>
                    	<li><a href="promotion.php">All Promotions</a></li>
                    <?php }if($promotionPerm['add']){ ?>
                        <li><a href="promotionAdd.php">Add New Promotion</a></li><hr style="margin:0px; border:1px solid #5e6271;" />
                    <?php }if($promotionPerm['add'] || $promotionPerm['edit'] || $promotionPerm['status'] || $promotionPerm['read']){ ?>
                        <li><a href="promotionMaster.php">Master Promotions</a></li><hr style="margin:0px; border:1px solid #5e6271;" />
                        <li><a href="adminDeal.php">Manage Deals</a></li>
                    <?php } ?>
                    <!-- <hr style="margin:0px; border:1px solid #5e6271;" /> <li><a href="promoCodeAdd.php">Add New Promo Code</a></li>
                    <li><a href="promoCode.php">All Promo Codes</a></li>-->
				</ul>
			</li>
			<?php }if($reviewPerm[0]){
			if($pagee == 'reviewAdmin.php'){ $csss1 = 'active'; $csss2 = 'style="display:block;"'; }
			else{ $csss1 = ''; $csss2 = ''; }
			?>
            <li class="has-submenu <?php echo $csss1; ?>"><a href="#"><i class="fa fa-gift"></i> <span class="nav-label">Review & Rating</span></a>
				<ul class="list-unstyled" <?php echo $csss2; ?>>
					<?php if($reviewPerm['status'] || $reviewPerm['read']){ ?>
	                    <li><a href="reviewAdmin.php">All Reviews</a></li>
                    <?php } ?>
				</ul>
			</li>
            <?php }if($cmsPerm[0]){
			if($pagee == 'cms1.php' || $pagee == 'cms2.php' || $pagee == 'cms3.php'){ $csss1 = 'active'; $csss2 = 'style="display:block;"'; }
			else{ $csss1 = ''; $csss2 = ''; }
			?>
            <li class="has-submenu <?php echo $csss1; ?>"><a href="#"><i class="fa fa-gift"></i> <span class="nav-label">CMS</span></a>
				<ul class="list-unstyled" <?php echo $csss2; ?>>
					<?php if($cmsPerm['add'] || $cmsPerm['edit'] || $cmsPerm['status'] || $cmsPerm['read']){ ?>
                    	<li><a href="cms1.php">CMS1 (JHM Shop)</a></li>
                        <li><a href="cms2.php">CMS2 (Help)</a></li>
                        <li><a href="cms3.php">CMS3 (Support)</a></li>
                   	<?php } ?>
				</ul>
			</li>
            <?php } ?>
            
            <li class="has-submenu"><a href="#"><i class="fa fa-gift"></i> <span class="nav-label">Order</span></a>
				<ul class="list-unstyled">
					<li><a href="adminOrder.php">All Order</a></li>
					<li><a href="adminBackOrder.php">All Back Order</a></li>
                    <li><a href="adminOrderCancel.php">Order Cancellation Request</a></li>
				</ul>
			</li>
            
            <li class="has-submenu"><a href="#"><i class="fa fa-gift"></i> <span class="nav-label">Manage Shipping</span></a>
				<ul class="list-unstyled">
					<li><a href="shipingPrice.php">All Shipping Charges</a></li>
                    <li><a href="shipingSector.php">All Sector</a></li>
				</ul>
			</li>
            
            <li class="has-submenu"><a href="#"><i class="fa fa-gift"></i> <span class="nav-label">User Points</span></a>
				<ul class="list-unstyled">
                    <li><a href="adminPointHistoty.php">All Points History</a></li>
				</ul>
			</li>
            
		</ul>
	</nav>
</aside>