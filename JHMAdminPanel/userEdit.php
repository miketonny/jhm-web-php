<?php include 'include/header.php';
chkParam($_GET['data1'], 'home.php');
$id = $_GET['data1'];
$user = mysql_fetch_object(exec_query("SELECT * FROM tbl_user WHERE user_id = '$id'", $con));
?>
        <div class="warper container-fluid">
            <div class="page-header"> <h1>Customer <small>Edit Customer</small></h1> </div>
        	<div class="row">
            
            	<div class="col-md-12">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Edit Customer</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                                <div class="form-group">
									<label class="col-sm-3 control-label">Title</label>
                                    <div class="col-sm-5">
										<select class="form-control" name="title" required >
											<option value="">- SELECT TITLE -</option>
                                            <option <?php getSelected('Mr', $user->title); ?>>Mr</option>
                                            <option <?php getSelected('Mrs', $user->title); ?>>Mrs</option>
                                            <option <?php getSelected('Ms', $user->title); ?>>Ms</option>
										</select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
									<label class="col-sm-3 control-label">First Name</label>
                                    <div class="col-sm-5">
										<input class="form-control" required name="fname" placeholder="First Name" type="text" value="<?php echo $user->first_name; ?>" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
									<label class="col-sm-3 control-label">Last Name</label>
                                    <div class="col-sm-5">
										<input class="form-control" required name="lname" placeholder="Last Name" type="text" value="<?php echo $user->last_name; ?>" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
									<label class="col-sm-3 control-label">Username</label>
                                    <div class="col-sm-5">
										<input class="form-control" required name="uname" placeholder="Username" type="text" value="<?php echo $user->username; ?>" readonly="readonly" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
									<label class="col-sm-3 control-label">Email Address</label>
                                    <div class="col-sm-5">
										<input class="form-control" required name="email" placeholder="Email Address" type="email" value="<?php echo $user->email; ?>" readonly="readonly" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
									<label class="col-sm-3 control-label">Phone</label>
                                    <div class="col-sm-5">
										<input class="form-control" required name="phone" placeholder="Phone" type="text" value="<?php echo $user->phone_1; ?>" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
									<label class="col-sm-3 control-label">Address</label>
                                    <div class="col-sm-5">
										<textarea class="form-control" required name="address" placeholder="Address" ><?php echo $user->address_1; ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="form-group">
									<label class="col-sm-3 control-label">City</label>
                                    <div class="col-sm-5">
										<input class="form-control" required name="city" placeholder="City" type="text" value="<?php echo $user->city; ?>" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
									<label class="col-sm-3 control-label">State</label>
                                    <div class="col-sm-5">
										<input class="form-control" required name="state" placeholder="State" type="text" value="<?php echo $user->state; ?>" />
                                    </div>
                                </div>
								
                                <div class="form-group">
									<label class="col-sm-3 control-label">Country</label>
                                    <div class="col-sm-5">
										<select class="form-control" name="country" required>
											<option value="">- SELECT COUNTRY -</option>
                                            <?php $country_rs = mysql_query("SELECT country_id, country_name FROM tbl_country ORDER BY country_name", $con);
											while($country_row = mysql_fetch_object($country_rs)){ ?>
												<option <?php getSelected($user->country_id, $country_row->country_id); ?> value="<?php echo $country_row->country_id; ?>"><?php echo $country_row->country_name; ?></option>
											<?php }	?>
										</select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
									<label class="col-sm-3 control-label">Zip</label>
                                    <div class="col-sm-5">
										<input class="form-control" required name="zip" placeholder="Zip" type="text" value="<?php echo $user->zip; ?>" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
									<label class="col-sm-3 control-label">Profile Picture</label>
                                    <div class="col-sm-5">
										<input name="img" type="file" />
                                        <?php if($user->img != ''){ echo '<img src="../site_image/profile_pic/'.$user->img.'" />'; } ?>
                                    </div>
                                </div>
                                <hr/>
								<div class="form-group">
									<input type="hidden" name="action" value="userEdit" />
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