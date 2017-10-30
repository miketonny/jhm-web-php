<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Manager & Moderators <small>Add New Manager</small></h1></div>
        	<div class="row">
            
            	<div class="col-md-12">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Add New Manager & Moderators</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                            	
								<div class="form-group">
									<label class="col-sm-3 control-label">Email Address</label>
                                    <div class="col-sm-6">
										<div id="picker"></div>
										<input class="form-control" required name="email" placeholder="Email Address" type="email" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Username</label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="username" placeholder="Username" type="text" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Password</label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="password" placeholder="Password" type="password" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Confirm Password</label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="cpassword" placeholder="Confirm Password" type="password" />
                                    </div>
                                </div>
								
								<hr/>
								<div class="form-group">
									<div class="col-lg-9 col-lg-offset-3">
										<input type="hidden" name="action" value="managerAdd" />
										<button class="btn btn-primary" type="submit"> Add Now ! </button>
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
