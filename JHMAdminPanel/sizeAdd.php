<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
            <div class="page-header"> <h1>Size <small>Add New Size</small></h1> </div>
        	<div class="row">
            
            	<div class="col-md-12">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Add New Size</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                                <?php /*<div class="form-group">
									<label class="col-sm-3 control-label">Select Main Category</label>
                                    <div class="col-sm-5">
										<select class="form-control chosen-select" required name="mainCategory" onchange="getSubCategory(this.value)" data-placeholder="SELECT MAIN CATEGORY">
											<option value=""></option>
											<?php
											$cat_rs = exec_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0 ORDER BY category_name", $con);
											while($cat_row = mysql_fetch_object($cat_rs)){
												echo '<option value="'.$cat_row->category_id.'">'.$cat_row->category_name.'</option>';
											}?>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Select Sub Category</label>
                                    <div class="col-sm-5">
										<select class="form-control" required name="subcategory" id="sub_category">
											<option value="">- SELECT SUB CATEGORY -</option>
											
										</select>
                                    </div>
                                </div>*/ ?>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Enter Size Unit</label>
                                    <div class="col-sm-5">
										<input class="form-control" required name="size" id="prod" placeholder="Enter Size Unit" type="text" />
                                    </div>
                                </div>
								
								<hr/>
								<div class="form-group">
									<input type="hidden" name="action" value="sizeAdd" />
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
<script>
function getSubCategory(cid){
	$.get('adminAjax.php', {'action' : 'getSubCategory', 'data1' : cid, 'dataTempId' : '3f299a701a15a12ded0c3e1d46d0f2158b.cloud.uk'}, function(data){
		$('#sub_category').html(data);
	});
}
</script>
<?php include 'include/formJs.php'; ?>
</body>
</html>
