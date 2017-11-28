<?php include 'include/header.php';
chkParam($_GET['data1'], 'home.php');
$id = $_GET['data1'];
$manager = mysqli_fetch_object(mysqli_query($con, "SELECT * FROM admin WHERE recid = '$id'"));
?>
<style> .hide1{ display:none; } .inline1{ display:inline; } </style>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Manager & Moderators <small>Set Manager Permissions</small></h1></div>
        	<div class="row">
            
            	<div class="col-md-12">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Set Manager Permissions</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                            	
								<div class="form-group">
									<label class="col-sm-3 control-label">Email Address</label>
                                    <div class="col-sm-6">
										<div id="picker"></div>
										<input class="form-control" required name="email" placeholder="Email Address" type="email" readonly="readonly" value="<?php echo $manager->email; ?>" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Username</label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="username" placeholder="Username" type="text" readonly="readonly" value="<?php echo $manager->username; ?>" />
                                    </div>
                                </div><hr/>
								
								<div class="col-lg-offset-2	col-lg-9">
								<?php $c = 1;
								$chk = 'checked="checked"';
								
								$cssConfig = '';
								$classAdd = '';
								$classEdit = '';
								$classRead = '';
								$classStatus = '';
								
								foreach($permissionArray AS $key => $val){
									$sel = 'selected="selected"';
									$add = ''; $edit = ''; $read = ''; $status = '';
									$chk = '';
									$chkPermission = mysqli_query($con, "SELECT * FROM tbl_permission WHERE user_id = '".$id."' AND permission = '$key'");
									if(mysqli_num_rows($chkPermission)){
										$row = mysqli_fetch_object($chkPermission);
										$add = $row->add;
										$edit = $row->edit;
										$read = $row->read;
										$status = $row->status;
										$chk = 'checked="checked"';
									}
									
									/* for hide some operations */
									$addCss = ''; $editCss = ''; $readCss = ''; $statusCss = '';
									
									// add
									if($key == 'review'){ $addCss = 'display:none;'; }
									
									// edit
									if($key == 'review'){ $editCss = 'display:none;'; }
									
									if($key == 'sysConfig'){
										$classAdd = 'classAdd';
										$classEdit = 'classEdit';
										$classRead = 'classRead';
										$classStatus = 'classStatus';
										$chk1 = 'checked="checked"';
									}
								?>
								
                        <div <?php echo $cssConfig; ?>>
                            <div class="switch-button showcase-switch-button control-label sm" style="width:240px; text-align:left;">
                                <input id="val<?php echo $c; ?>" name="<?php echo $key; ?>" value="<?php echo $key; ?>" type="checkbox" onchange="khel(this.id)" <?php if($cssConfig != ''){ echo $chk1; }else{ echo $chk; } ?> >
                                <label for="val<?php echo $c; ?>"></label> <?php echo $val; ?>
                            </div>
                            
                            <div class="<?php if($chk == ''){ echo 'hide1'; }else{ echo 'inline1'; }?>" id="sval<?php echo $c; ?>">
                                <span style=" <?php echo $addCss; ?>">
                                    <input class="<?php echo $classAdd; ?>" type="checkbox" value="1" name="<?php echo $key; ?>add" <?php if($add == 1){ echo $chk; } ?> <?php if($key == 'sysConfig'){ ?> onclick="ekOrKhel('Add');" id="idAdd" <?php } ?> /> Add
                                </span>
                                
                                <span style=" <?php echo $editCss; ?>">
                                    <input class="<?php echo $classEdit; ?>" type="checkbox" value="1" name="<?php echo $key; ?>edit" <?php if($edit == 1){ echo $chk; } ?> <?php if($key == 'sysConfig'){ ?> onclick="ekOrKhel('Edit');" id="idEdit" <?php } ?> /> Edit
                                </span>
                                
                                <span style=" <?php echo $readCss; ?>">
                                    <input class="<?php echo $classRead; ?>" type="checkbox" value="1" name="<?php echo $key; ?>read" <?php if($read == 1){ echo $chk; } ?> <?php if($key == 'sysConfig'){ ?> onclick="ekOrKhel('Read');" id="idRead" <?php } ?> /> Readonly
                                </span>
                                
                                <span style=" <?php echo $statusCss; ?>">
                                	<input class="<?php echo $classStatus; ?>" type="checkbox" value="1" name="<?php echo $key;?>status" <?php if($status == 1){echo $chk;}?> <?php if($key == 'sysConfig'){ ?> onclick="ekOrKhel('Status');" id="idStatus" <?php } ?> /> Status Change
                                </span>
                            </div>
                            
                        </div>
                                
                                <br/>
								<?php
									/* for system configurations */
									if($key == 'sysConfig'){
										$cssConfig = 'style="display:none;"';
									}
                                	$c++;
								} ?>
                                <br/>
								</div>
								<hr/>
								<div class="form-group">
									<div class="col-lg-9 col-lg-offset-3">
										<input type="hidden" name="action" value="setPermission" />
										<input type="hidden" name="id" value="<?php echo $id; ?>" />
										<button class="btn btn-primary" type="submit"> Save Changes ! </button>
										<button class="btn btn-info" type="submit" name="nsubmit"> Save & New ! </button>
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
<script>
function khel(id){
	if(document.getElementById(id).checked == true){ $('#s'+id).attr('required', 'required').css('display', 'inline'); }
	else{ $('#s'+id).removeAttr('required').css('display', 'none'); }
}

function ekOrKhel(type){
	if(document.getElementById('id'+type).checked == true){
		$('.class'+type).attr('checked', 'checked');
	}
	else{
		$('.class'+type).removeAttr('checked', 'checked');
	}
}

ekOrKhel('Add');
ekOrKhel('Edit');
ekOrKhel('Read');
ekOrKhel('Status');
</script>
</body>
</html>
<?php /*<select <?php if($chk == ''){ ?> class="hide1" <?php } ?> id="sval<?php echo $c; ?>" name="<?php echo $key; ?>permission">
	<option value="">- Select Permission -</option>
	<option <?php if($add == 1 && $edit == 1 && $read == 1 && $status == 1){ echo $sel; $sel = ''; } ?> value="all">ALL</option>
	<option <?php if($add == 1){ echo $sel; } ?> value="add">ADD</option>
	<option <?php if($edit == 1){ echo $sel; } ?> value="edit">EDIT</option>
	<option <?php if($read == 1){ echo $sel; } ?> value="read">READONLY</option>
	<option <?php if($status == 1){ echo $sel; } ?> value="status">STATUS CHANGE</option>
</select>*/ ?>