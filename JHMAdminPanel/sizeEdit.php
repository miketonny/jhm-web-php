<?php include 'include/header.php';
chkParam($_GET['data1'], 'home.php');
$id = $_GET['data1'];
$size = getSize($id, $con);
//$cat = getCategory(array('category_name'), $size->subcategory_id, $con)->category_name; ?>
        <div class="warper container-fluid">
            <div class="page-header"> <h1>Size <small>Edit Size</small></h1> </div>
        	<div class="row">
            
            	<div class="col-md-12">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Edit Size</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                                <?php /*<div class="form-group">
									<label class="col-sm-3 control-label">Select Main Category</label>
                                    <div class="col-sm-5">
										<select class="form-control chosen-select" name="mainCategory" onchange="getSubCategory(this.value)" data-placeholder="SELECT MAIN CATEGORY">
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
											<option selected="selected" value="<?php echo $size->subcategory_id; ?>"><?php echo $cat; ?></option>
										</select>
                                    </div>
                                </div>*/ ?>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Enter Size Unit</label>
                                    <div class="col-sm-5">
										<input class="form-control" required name="size" id="prod" placeholder="Enter Size Unit" type="text" value="<?php echo $size->size; ?>" />
                                    </div>
                                </div>
								
								<hr/>
								<div class="form-group">
									<input type="hidden" name="action" value="sizeEdit" />
									<input type="hidden" name="data1" value="<?php echo $id; ?>" />
									<div class="col-lg-9 col-lg-offset-3">
										<button class="btn btn-primary" type="submit"> Save Changes! </button>
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
