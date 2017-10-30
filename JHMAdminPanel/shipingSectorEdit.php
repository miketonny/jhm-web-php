<?php include 'include/header.php';
chkParam($_GET['data1'], 'home.php');
$id = $_GET['data1'];
$row = mysql_fetch_object(exec_query("SELECT * FROM tbl_shipping_sector WHERE recid = '$id'", $con));
?>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Manage Shipping <small> Edit Shipping Sector</small></h1></div>
        	<div class="row">
            
            	<div class="col-md-12">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Edit Shipping Sector</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                                
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Sector Name</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" required name="sname" placeholder="Sector Name" type="text" value="<?php echo $row->sector_name; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Sector Code</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" required name="scode" placeholder="Sector Code" type="text" value="<?php echo $row->sector_code; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Postcode</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" required name="postcode" placeholder="Postcode" type="text" value="<?php echo $row->postcode; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Suburb</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" required name="suburb" placeholder="Suburb" type="text" value="<?php echo $row->suburb_name; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Town</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" required name="town" placeholder="Town" type="text" value="<?php echo $row->town_name; ?>" />
                                    </div>
                                </div>
                                <?php $chk = 'checked="checked"'; ?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Is Rural?</label>
                                    <div class="col-sm-8">
                                        <div class="switch-button showcase-switch-button control-label sm primary">
                                            <input id="switch-button-1" name="isRural" value="1" type="checkbox" <?php if($row->is_rural == 1){ echo $chk; }?> >
                                            <label for="switch-button-1"></label> 
                                        </div> &nbsp; &nbsp;
                                    </div>
                                </div>
								
								<hr/>
								<div class="form-group">
									<input type="hidden" name="action" value="shippingCityEdit" />
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