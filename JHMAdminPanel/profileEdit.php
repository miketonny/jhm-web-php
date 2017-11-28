<?php include 'include/header.php';
$admin = mysqli_fetch_object(exec_query("SELECT * FROM admin WHERE recid = '".$_SESSION['admin']."'", $con));
?>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Admin Profile <small>Edit Profile</small></h1></div>
        	<div class="row">
            
            	<div class="col-md-12">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Edit Profile</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="edit_profile_model.php">
                            	
								<div class="form-group">
									<label class="col-sm-3 control-label">Email Id</label>
                                    <div class="col-sm-6">
										<div id="picker"></div>
										<input class="form-control" required name="email" placeholder="Email Id" type="email" onblur="getSlug(this.id)" value="<?php echo $admin->email; ?>" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Username</label>
                                    <div class="col-sm-6">
										<div id="picker"></div>
										<input class="form-control" required name="uname" placeholder="Username" type="text" onblur="getSlug(this.id)" value="<?php echo $admin->username; ?>" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Current Password</label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="old_password" placeholder="Current Password" type="password" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">New Password</label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="new_password" placeholder="New Password" type="password" pattern=".{4,}" title="4 characters minimum" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Confirm New Password</label>
                                    <div class="col-sm-6">
										<input class="form-control" required pattern=".{4,}" title="4 characters minimum" name="confirm_new_password" placeholder="Confirm New Password" type="password" />
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
									<div class="col-lg-9 col-lg-offset-3">
										<button class="btn btn-primary" type="submit"> Submit </button>
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