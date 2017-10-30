<?php include 'include/header.php';
chkParam($_GET['data1'], 'home.php');
$id = $_GET['data1'];
$tax = mysql_fetch_object(exec_query("SELECT * FROM tbl_tax WHERE recid = '$id'", $con));
?>
        <div class="warper container-fluid">
            <div class="page-header"> <h1>Tax <small>Edit Tax</small></h1> </div>
        	<div class="row">
            
            	<div class="col-md-12">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Edit Tax</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                                <div class="form-group">
									<label class="col-sm-3 control-label">Tax Name</label>
                                    <div class="col-sm-5">
										<input class="form-control" required name="name" placeholder="Tax Name" type="text" value="<?php echo $tax->tax_name; ?>" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Tax Percent</label>
                                    <div class="col-sm-5">
										<div id="" class="input-group">
											<input class="form-control" required name="per" placeholder="Tax Percent" type="text" value="<?php echo $tax->tax_percent; ?>" />
											<span class="input-group-addon"><span class=" glyphicon">%</span></span>
										</div>
                                    </div>
                                </div>
								
								<hr/>
								<div class="form-group">
									<input type="hidden" name="action" value="taxEdit" />
									<input type="hidden" name="data1" value="<?php echo $id; ?>" />
									<div class="col-lg-9 col-lg-offset-3">
										<button class="btn btn-primary" type="submit"> Save Changes! </button>
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