<?php include 'include/header.php';?>
<div class="warper container-fluid">
	<div class="page-header"><h1>Category <small>Category Tree</small></h1>
    	<button type="button" class="openall btn btn-purple btn-xs" >Expand All</button>
    	<button type="button" class="closeall btn btn-purple btn-xs" >Collapse All</button>
    </div>

<!-- main cat start -->
<div class="panel-group" id="accordionCat">
    <?php
$cat_rs = exec_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0 ORDER BY category_name", $con);
while ($cat_row = mysqli_fetch_object($cat_rs)) {
	?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordionCat" href="#<?php echo $cat_row->category_id; ?>">
                    <?php echo $cat_row->category_name; ?>
                </a>
            </h4>
        </div>
        <div id="<?php echo $cat_row->category_id; ?>" class="panel-collapse collapse">
            <div class="panel-body">

            <!-- subcat loop start -->
            <div class="panel-group" id="accordionSubCat">
            <?php
$scrs = mysqli_query($con, "SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = '$cat_row->category_id' ORDER BY category_name");
	while ($scrow = mysqli_fetch_object($scrs)) {
		?>

                <div class="panel panel-default">
                    <div class="panel-heading" style="background:#b8b8b8; color:#fff;">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordionSubCat" href="#<?php echo $scrow->category_id; ?>">
                                <?php echo $scrow->category_name; ?>
                            </a>
                        </h4>
                    </div>
                    <div id="<?php echo $scrow->category_id; ?>" class="panel-collapse collapse">
                        <div class="panel-body">

                            <!-- subsubcat loop start -->
                            <div class="panel-group" id="accordionSubSubCat">
                            <?php
$sscrs = mysqli_query($con, "SELECT category_id, category_name FROM tbl_category WHERE parent_id = '$scrow->category_id' AND superparent_id = '$cat_row->category_id' ORDER BY category_name");
		while ($sscrow = mysqli_fetch_object($sscrs)) {?>

                                <div class="panel panel-default">
                                    <div class="panel-heading" style="background:#828282; color:#fff;">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordionSubSubCat" href="#<?php echo $sscrow->category_id; ?>">
                                                <?php echo $sscrow->category_name; ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <?php /*<div id="<?php echo $sscrow->category_id;?>" class="panel-collapse collapse">
		<div class="panel-body">

		</div>
		</div>*/?>
                                </div>

                            <?php }?>
                            </div>
                            <!-- subsubcat loop end -->

                        </div>
                    </div>
                </div>

            <?php }?>
            </div>
            <!-- subcat loop end -->

            </div>
        </div>
    </div>

    <?php }?>

</div>
<!-- main cat end -->

</div>
<!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php';?>
<!-- JQuery v1.9.1 -->
<script src="assets/js/jquery/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="assets/js/plugins/underscore/underscore-min.js"></script>
<!-- Bootstrap -->
<script src="assets/js/bootstrap/bootstrap.min.js"></script>
<!-- Globalize -->
<script src="assets/js/globalize/globalize.min.js"></script>
<!-- NanoScroll -->
<script src="assets/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
<!-- Custom JQuery -->
<script src="assets/js/app/custom.js" type="text/javascript"></script>
<script>
$('.closeall').click(function(){
	$('.panel-collapse.in').collapse('hide');
});
$('.openall').click(function(){
	$('.panel-collapse:not(".in")').collapse('show');
});
</script>
</body>
</html>