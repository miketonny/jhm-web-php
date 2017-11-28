<?php include 'include/header.php';
$emailTemp = mysqli_fetch_object(exec_query("SELECT content FROM tbl_manage WHERE type = 'emailTemplate'", $con))->content;
$about = mysqli_fetch_object(exec_query("SELECT content FROM tbl_manage WHERE type = 'about'", $con))->content;
$terms = mysqli_fetch_object(exec_query("SELECT content FROM tbl_manage WHERE type = 'terms'", $con))->content;
$career = mysqli_fetch_object(exec_query("SELECT content FROM tbl_manage WHERE type = 'career'", $con))->content;
$return = mysqli_fetch_object(exec_query("SELECT content FROM tbl_manage WHERE type = 'return'", $con))->content;
?>
<div class="warper container-fluid">
    <div class="page-header"><h1>Manage <small>Manage Site</small></h1></div>
    
    <div class="row">
        <ul role="tablist" class="nav nav-tabs" id="myTab">
            <li class="active"><a data-toggle="tab" role="tab" href="#etempl">Email Template</a></li>
           	<!--<li><a data-toggle="tab" role="tab" href="#abt">About US</a></li>
            <li><a data-toggle="tab" role="tab" href="#rpol">Return Policy</a></li>
            <li><a data-toggle="tab" role="tab" href="#carr">Career</a></li>
            <li><a data-toggle="tab" role="tab" href="#tnc">Terms & Conditions</a></li>-->
        </ul>
    	        
        <div class="tab-content" id="myTabContent">
            
            <div id="etempl" class="tab-pane tabs-up fade in active panel panel-default">
            	<form method="post" action="admin_action_model.php">
                <div class="panel-body">
                	<div class="form-group">
						<div class="col-sm-10">
							<textarea class="form-control" id="ckeditor" name="content" placeholder="Description" cols="100" rows="10" ><?php echo $emailTemp; ?></textarea>
                            <input type="hidden" name="type" value="emailTemplate" />
                            <input type="hidden" name="action" value="manage" />
						</div>
					</div>
                    <div class="form-group">
						<div class="col-lg-9 col-lg-offset-3"><br/>
							<button class="btn btn-primary" type="submit"> Save Now ! </button>
						</div>
					</div>
                </div>
                </form>
            </div>
            
            <?php /*<div id="abt" class="tab-pane tabs-up fade panel panel-default">
            	<form method="post" action="admin_action_model.php">
                <div class="panel-body">
                	<div class="form-group">
						<div class="col-sm-10">
							<textarea class="form-control" id="ckeditor1" name="content" placeholder="Description" cols="100" rows="10" ><?php echo $about; ?></textarea>
                            <input type="hidden" name="type" value="about" />
                            <input type="hidden" name="action" value="manage" />
						</div>
					</div>
                    <div class="form-group">
						<div class="col-lg-9 col-lg-offset-3"><br/>
							<button class="btn btn-primary" type="submit"> Save Now ! </button>
						</div>
					</div>
                </div>
                </form>
            </div>
            
            <div id="rpol" class="tab-pane tabs-up fade panel panel-default">
            	<form method="post" action="admin_action_model.php">
                <div class="panel-body">
                	<div class="form-group">
						<div class="col-sm-10">
							<textarea class="form-control" id="ckeditor2" name="content" placeholder="Description" cols="100" rows="10" ><?php echo $return; ?></textarea>
                            <input type="hidden" name="type" value="return" />
                            <input type="hidden" name="action" value="manage" />
						</div>
					</div>
                    <div class="form-group">
						<div class="col-lg-9 col-lg-offset-3"><br/>
							<button class="btn btn-primary" type="submit"> Save Now ! </button>
						</div>
					</div>
                </div>
                </form>
            </div>
            
            <div id="carr" class="tab-pane tabs-up fade panel panel-default">
            	<form method="post" action="admin_action_model.php">
                <div class="panel-body">
                	<div class="form-group">
						<div class="col-sm-10">
							<textarea class="form-control" id="ckeditor3" name="content" placeholder="Description" cols="100" rows="10" ><?php echo $career; ?></textarea>
                            <input type="hidden" name="type" value="career" />
                            <input type="hidden" name="action" value="manage" />
						</div>
					</div>
                    <div class="form-group">
						<div class="col-lg-9 col-lg-offset-3"><br/>
							<button class="btn btn-primary" type="submit"> Save Now ! </button>
						</div>
					</div>
                </div>
                </form>
            </div>
            
            <div id="tnc" class="tab-pane tabs-up fade panel panel-default">
            	<form method="post" action="admin_action_model.php">
                <div class="panel-body">
                	<div class="form-group">
						<div class="col-sm-10">
							<textarea class="form-control" id="ckeditor4" name="content" placeholder="Description" cols="100" rows="10" ><?php echo $terms; ?></textarea>
                            <input type="hidden" name="type" value="terms" />
                            <input type="hidden" name="action" value="manage" />
						</div>
					</div>
                    <div class="form-group">
						<div class="col-lg-9 col-lg-offset-3"><br/>
							<button class="btn btn-primary" type="submit"> Save Now ! </button>
						</div>
					</div>
                </div>
                </form>
            </div>*/ ?>
             
        </div>
	</div>
</div>
<!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php'; ?>
<?php include 'include/formJs.php'; ?>
<script> $('#ckeditor, #ckeditor1, #ckeditor2, #ckeditor3, #ckeditor4').ckeditor(); </script>
</body>
</html>