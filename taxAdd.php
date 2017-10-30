<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
            <div class="page-header"> <h1>Tax <small>Add New Tax</small></h1> </div>
        	<div class="row">
            
            	<div class="col-md-12">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Add New Tax</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                                <div class="form-group">
									<label class="col-sm-3 control-label">Tax Name</label>
                                    <div class="col-sm-5">
										<input class="form-control" required name="name" placeholder="Tax Name" type="text" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Tax Percent</label>
                                    <div class="col-sm-5">
										<div id="" class="input-group">
											<input class="form-control" required name="per" placeholder="Tax Percent" type="text" />
											<span class="input-group-addon"><span class=" glyphicon">%</span></span>
										</div>
                                    </div>
                                </div>
								
								<hr/>
								<div class="form-group">
									<input type="hidden" name="action" value="taxAdd" />
									<div class="col-lg-9 col-lg-offset-3">
										<button class="btn btn-primary" type="submit"> Add Now! </button>
										<button class="btn btn-info" type="submit" name="nsubmit"> Add & New ! </button>
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