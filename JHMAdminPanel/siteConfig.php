<?php include 'include/header.php';
$noCats = mysql_fetch_object(exec_query("SELECT no FROM tbl_config WHERE type = 'configNoCategories'", $con));
$mins = mysql_fetch_object(exec_query("SELECT no FROM tbl_config WHERE type = 'shoppingTimeoutCart'", $con));
$noPro = mysql_fetch_object(exec_query("SELECT no FROM tbl_config WHERE type = 'noProductPerPage'", $con));
$noProductOnCategoryPage = mysql_fetch_object(exec_query("SELECT no FROM tbl_config WHERE type = 'noProductOnCategoryPage'", $con));
?>
        <div class="warper container-fluid">
            <div class="page-header"> <h1>Site Configuration <small>Site Configuration</small></h1> </div>
        	<div class="row">
            	
            	<div class="col-md-4">
                 	<div class="panel panel-default">
                        <div class="panel-heading">No. of categories on front page</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php">
                                <div class="form-group">
									<label class="col-sm-4 control-label">Enter No.</label>
                                    <?php
                                    $count = mysql_fetch_object(exec_query("SELECT COUNT(category_id) AS count FROM `tbl_category` WHERE `parent_id` = 0 AND `superparent_id` = 0", $con))->count;
									?>
                                    <div class="col-sm-7">
										<input class="form-control input-sm" required name="no" placeholder="Enter No. of Categories" type="number" min="1" value="<?php echo $noCats->no; ?>" />
                                        <small>Maximum Allow <?php echo $count; ?> Categories</small>
                                    </div>
                                </div>
								<div class="form-group">
									<input type="hidden" name="type" value="configNoCategories" />
                                    <input type="hidden" name="action" value="config" />
									<div class="col-lg-9 col-lg-offset-3">
										<?php if($configPerm['add'] || $configPerm['edit']){ ?>
                                        	<button class="btn btn-primary btn-sm" type="submit"> Save Changes! </button>
                                        <?php } ?>
									</div>
								</div>
                        	</form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Shopping Cart Session Timeout</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php">
                                <div class="form-group">
									<label class="col-sm-4 control-label">Enter Minutes</label>
                                    <div class="col-sm-7">
										<input class="form-control input-sm" required name="no" placeholder="Enter No. of Deals" type="number" min="1" value="<?php echo $mins->no; ?>" />
                                        <small>(Only Minutes Allowed)</small>
                                    </div>
                                </div>
								<div class="form-group">
									<input type="hidden" name="type" value="shoppingTimeoutCart" />
                                    <input type="hidden" name="action" value="config" />
									<div class="col-lg-9 col-lg-offset-3">
										<?php if($configPerm['add'] || $configPerm['edit']){ ?>
                                        	<button class="btn btn-primary btn-sm" type="submit"> Save Changes! </button>
                                        <?php } ?>
									</div>
								</div>
                        	</form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                 	<div class="panel panel-default">
                        <div class="panel-heading">No. of Product per Page</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php">
                                <div class="form-group">
									<label class="col-sm-4 control-label">Enter No.</label>
                                    <div class="col-sm-7">
										<input class="form-control input-sm" required name="no" placeholder="Enter No. of Products" type="number" min="1" value="<?php echo $noPro->no; ?>" />
                                    </div>
                                </div>
								<div class="form-group">
									<input type="hidden" name="type" value="noProductPerPage" />
                                    <input type="hidden" name="action" value="config" />
									<div class="col-lg-9 col-lg-offset-3">
										<?php if($configPerm['add'] || $configPerm['edit']){ ?>
                                        	<button class="btn btn-primary btn-sm" type="submit"> Save Changes! </button>
                                        <?php } ?>
									</div>
								</div>
                        	</form>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="row">
            
            	<div class="col-md-4">
                 	<div class="panel panel-default">
                        <div class="panel-heading">No. of Product on Category Page</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php">
                                <div class="form-group">
									<label class="col-sm-4 control-label">Enter No.</label>
                                    <div class="col-sm-7">
										<input class="form-control input-sm" required name="no" placeholder="Enter No. of Products" type="number" min="1" value="<?php echo $noProductOnCategoryPage->no; ?>" />
                                    </div>
                                </div>
								<div class="form-group">
									<input type="hidden" name="type" value="noProductOnCategoryPage" />
                                    <input type="hidden" name="action" value="config" />
									<div class="col-lg-9 col-lg-offset-3">
										<?php if($configPerm['add'] || $configPerm['edit']){ ?>
                                        	<button class="btn btn-primary btn-sm" type="submit"> Save Changes! </button>
                                        <?php } ?>
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