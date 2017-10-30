<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Product <small>Add New Product</small></h1></div>
        	<div class="row">
            
            	<div class="col-md-12">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Add New Product</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                                <div class="form-group">
									<label class="col-sm-3 control-label">Select Main Category</label>
                                    <div class="col-sm-5">
										<select class="form-control chosen-select" required name="mainCategory" onchange="getSubCategory(this.value)" data-placeholder="SELECT MAIN CATEGORY">
											<option value=""></option>
											<?php
											$cat_rs = exec_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 ORDER BY category_name", $con);
											while($cat_row = mysql_fetch_object($cat_rs)){
												echo '<option value="'.$cat_row->category_id.'">'.$cat_row->category_name.'</option>';
											}?>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Select Sub Category</label>
                                    <div class="col-sm-5">
										<select class="form-control" required name="subcategory" id="sub_category" onchange="getSubcategorySize(this.value)">
											<option value="">- SELECT SUB CATEGORY -</option>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Select Brand</label>
                                    <div class="col-sm-5">
										<select class="form-control" required name="brand" id="brandSel" onchange="getColorfromBrand(this.value);">
											<option value="">- SELECT BRAND -</option>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Product Name</label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="name" id="prod" placeholder="Product Name" type="text" onblur="getSlug(this.id)" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Slug</label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="slug" id="prodSlug" placeholder="Slug" type="text" />
                                    </div>
                                </div>
								<!-- hide it ----------------------------------------------------------------------------------------------------------------- --->
								<div class="form-group" style="display:none;">
									<label class="col-sm-3 control-label">Qty Available</label>
                                    <div class="col-sm-2">
										<input class="form-control inputmask" required name="qty" placeholder="Qty Available" type="text" data-inputmask="'alias': 'decimal', 'autoGroup': true" style="text-align:left;" value="10" />
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Gender</label>
                                    <div class="col-sm-9">
                                        <div class="switch-button showcase-switch-button">
                                            <input value="male" id="switch-button-6" name="group[]" type="checkbox" >
                                            <label for="switch-button-6"></label> Male
                                        </div> &nbsp; &nbsp;
                                        <div class="switch-button showcase-switch-button">
                                            <input value="female" id="switch-button-7" name="group[]" type="checkbox" >
                                            <label for="switch-button-7"></label> Female
                                        </div> &nbsp; &nbsp;
										<div class="switch-button showcase-switch-button">
                                            <input value="boy" id="switch-button-8" name="group[]" type="checkbox" >
                                            <label for="switch-button-8"></label> Boy
                                        </div>
                                	</div>
                                </div>
								<hr/>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Age Group</label>
                                    <div class="col-sm-9">
                                        <div class="switch-button showcase-switch-button">
                                            <input value="0-5" id="switch-button-a1" name="age" type="radio" >
                                            <label for="switch-button-a1"></label> 0-5
                                        </div> &nbsp; &nbsp;
                                        <div class="switch-button showcase-switch-button">
                                            <input value="5-15" id="switch-button-a2" name="age" type="radio" >
                                            <label for="switch-button-a2"></label> 5-15
                                        </div> &nbsp; &nbsp;
										<div class="switch-button showcase-switch-button">
                                            <input value="15-25" id="switch-button-a3" name="age" type="radio" >
                                            <label for="switch-button-a3"></label> 15-25
                                        </div> &nbsp; &nbsp;
                                        <div class="switch-button showcase-switch-button">
                                            <input value="25-40" id="switch-button-a4" name="age" type="radio" >
                                            <label for="switch-button-a4"></label> 25-40
                                        </div> &nbsp; &nbsp;
                                        <div class="switch-button showcase-switch-button">
                                            <input value="40-55" id="switch-button-a5" name="age" type="radio" >
                                            <label for="switch-button-a5"></label> 40-55
                                        </div> &nbsp; &nbsp;
										<div class="switch-button showcase-switch-button">
                                            <input value="above 55" id="switch-button-a6" name="age" type="radio" >
                                            <label for="switch-button-a6"></label> above 55
                                        </div>
                                	</div>
                                </div>
								<hr/>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Stock Availability</label>
                                    <div class="col-sm-9">
                                        <div class="switch-button showcase-switch-button">
                                            <input value="0" id="switch-button-6i" name="stock" type="radio" >
                                            <label for="switch-button-6i"></label> Available
                                        </div> &nbsp; &nbsp;
                                        <div class="switch-button showcase-switch-button">
                                            <input value="1" id="switch-button-7i" name="stock" type="radio" >
                                            <label for="switch-button-7i"></label> Not Available
                                        </div> &nbsp; &nbsp;
										<div class="switch-button showcase-switch-button">
                                            <input value="2" id="switch-button-8i" name="stock" type="radio" >
                                            <label for="switch-button-8i"></label> Allow Backorder
                                        </div>
                                	</div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Product SKU #</label>
                                    <div class="col-sm-4">
										<input class="form-control" required name="sku" placeholder="Product SKU #" type="text" />
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Product UPC #</label>
                                    <div class="col-sm-4">
										<input class="form-control" required name="upc" placeholder="Product UPC #" type="text" />
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
									<label class="col-sm-3 control-label">Summary</label>
                                    <div class="col-sm-8">
										<textarea class="wysihtml form-control" name="summary" placeholder="Summary" style="height: 200px" cols="100" rows="10" ></textarea>
                                    </div>
                                </div>
                       			<hr/>
								<div class="form-group">
									<label class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-8">
										<textarea class="form-control" id="ckeditor" name="desc" placeholder="Description" cols="100" rows="10" ></textarea>
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
									<label class="col-sm-3 control-label">Usage</label>
                                    <div class="col-sm-9">
										<input type="file" name="img[]" multiple /> <small class="gray"> Press Ctrl and select multiple images (Step wise) </small>
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
									<label class="col-sm-3 control-label">Select Color</label>
                                    <div class="col-sm-9" id="colorpallet"></div>
								</div>
								<hr/>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Size</label>
                                    <div class="col-sm-9" id="sizeBlock"></div>
                                </div>
								<hr/>
								<div class="form-group">
									<input type="hidden" name="action" value="productAdd" />
									<input type="hidden" value="<?php echo $id; ?>" name="data1" />
									<div class="col-lg-9 col-lg-offset-3">
										<button class="btn btn-primary" type="submit"> Proceed to Next Step! </button>
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
	$.get('adminAjax.php', {'action' : 'getSubCategory', 'data1' : cid, 'dataTempId' : '3fda701a15a12ded0c3e1d46d0f2158b.cloud'}, function(data){
		$('#sub_category').html(data);
		getCategoryBrand(cid);
	});
}
function getCategoryBrand(cid){
	$.get('adminAjax.php', {'action' : 'getCategoryBrand', 'data1' : cid, 'dataTempId' : '3qdv701a15a22ded0c311d46d0f2157c.cloud'}, function(data){
		$('#brandSel').html(data);
	});
}
function getSubcategorySize(scid){
	$.get('adminAjax.php', {'action' : 'getSubcategorySize', 'data1' : scid, 'dataTempId' : '3qdv701a15a22ded1c311d46d0f2157c.cloud.uk'}, function(data){
		$('#sizeBlock').html(data);
	});
}
function getColorfromBrand(bid){
	$.get('adminAjax.php', {'action' : 'getColorfromBrand', 'data1' : bid, 'dataTempId' : '3qv701a15a22ded00c31d46d1f21571.cloud.uk'}, function(data){
		$('#colorpallet').html(data);
	});
}
/*bkLib.onDomLoaded(function() {
    nicEditors.editors.push(
        new nicEditor().panelInstance(
            document.getElementById('myNicEditor')
        )
    );
});*/
</script>
<script type="text/javascript" src="assets/js/js.js"></script>
<?php include 'include/formJs.php'; ?>
</body>
</html>
