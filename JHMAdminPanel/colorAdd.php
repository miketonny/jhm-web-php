<?php include 'include/header.php'; ?>
<link rel="stylesheet" href="assets/css/farbtastic.css" type="text/css" />
        <div class="warper container-fluid">
            <div class="page-header"><h1>Color <small>Add New Color</small></h1></div>
        	<div class="row">
            
            	<div class="col-md-12">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Add New Color</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                            	
								<div class="form-group">
									<label class="col-sm-3 control-label">Select Color</label>
                                    <div class="col-sm-6">
										<div id="picker"></div>
										<input class="form-control" required name="colorCode" id="color" placeholder="Select Color" value="#999999" type="text" readonly="readonly" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Color Name</label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="name" id="col" placeholder="Color Name" type="text" />
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
									<input type="hidden" name="action" value="colorAdd" />
									<div class="col-lg-9 col-lg-offset-3">
										<button class="btn btn-primary" type="submit"> Add Now ! </button>
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
<script>
$(document).ready(function() {
	$('#demo').hide();
	$('#picker').farbtastic('#color');
});
</script>
<script type="text/javascript" src="assets/js/farbtastic.js"></script>
<script type="text/javascript" src="assets/js/js.js"></script>
</body>
</html>
