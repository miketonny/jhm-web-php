<?php include 'include/header.php'; ?>
<style> #putSKUHint{ z-index:9; absolute; width: 324px; display:none; } </style>
<script>
	window.history.forward();
	function noBack(){ window.history.forward(); }
</script>
<div onload="noBack();" onpageshow="if (event.persisted) noBack();"></div>

<?php
// define vars and arrs
$chk = 'checked="checked"';
$sel = 'selected="selected"';
$sCatOp = ''; $ssCatOp = ''; $sCats = ''; $pro = '';
$mCats = array();
$pColor = array();
$tempSubArr = array();
$catArr = array();
$isSession = false;
if(isset($_SESSION['sessionProductId'])){
	$isSession = true;
	$id = $_SESSION['sessionProductId'];
	$pro = getProduct($id, $con);
	$cat_rs = exec_query("select category_id from tbl_product_category WHERE product_id = '$id'", $con);
	while($cat_row = mysqli_fetch_object($cat_rs)){ $catArr[] = $cat_row->category_id; }
	if(!empty($catArr)){
		foreach($catArr AS $cat){
			$level = chkCategoryLevel($cat, $con);
			if($level == 'subCat'){
				$scat = getCategory(array('category_name', 'category_id', 'superparent_id'), $cat, $con);
				$sCatOp .= '<option '.$sel.' value="'.$scat->category_id.'">'.$scat->category_name.'</option>';
				
				/* for similar */
				$mainCats = getCategory(array('category_name', 'category_id'), $scat->superparent_id, $con);
				$mCats[] = $mainCats->category_id;
			}
			elseif($level == 'subSubCat'){
				$sscat = getCategory(array('category_name', 'category_id', 'parent_id'), $cat, $con);
				$ssCatOp .= '<option '.$sel.' value="'.$sscat->category_id.'">'.$sscat->category_name.'</option>';
				
				/* for similar */
				$subCats = getCategory(array('category_name', 'category_id', 'superparent_id'), $sscat->parent_id, $con);
				if(!in_array($subCats->category_id, $tempSubArr)){
					$tempSubArr[$subCats->category_name] = $subCats->category_id;
				}
				$mainCats = getCategory(array('category_name', 'category_id'), $subCats->superparent_id, $con);
				$mCats[] = $mainCats->category_id;
			}
		}
	}
	
	if(!empty($tempSubArr)){
		foreach($tempSubArr AS $key => $val){
			$sCats .= '<option '.$sel.' value="'.$val.'">'.$key.'</option>';
		}
	}
	
	/* get color */
	$proColRs = exec_query("SELECT color_code FROM tbl_product_color WHERE product_id = '$id'", $con);
	while($proColRow = mysqli_fetch_object($proColRs)){ $pColor[] = $proColRow->color_code; }
}
?>
        <div class="warper container-fluid" >
            <div class="page-header"><h1>Product <small>Add New Product</small></h1></div>
        	<div class="row">
            
            	<div class="col-md-12">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Add New Product</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" onsubmit="return chkSizeColor();" >
                                <div class="form-group">
 
									<label class="col-sm-3 control-label">Main Category</label>
                                    <div class="col-sm-5">
										<select class="form-control chosen-select" required name="mainCategory[]" id="parentSel" onchange="getSubCategory(this.id)" data-placeholder="SELECT MAIN CATEGORY" multiple>
											<option value=""></option>
											<?php
											$cat_rs = exec_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0 ORDER BY category_name", $con);
											while($cat_row = mysqli_fetch_object($cat_rs)){ ?>
                                            	
                                                <option
												<?php echo (in_array($cat_row->category_id, $mCats) || in_array($cat_row->category_id, $catArr))?$sel:''; ?>
                                                value="<?php echo $cat_row->category_id; ?>"><?php echo $cat_row->category_name; ?></option>
                                                
												<?php /*<option value="'.$cat_row->category_id.'">'.$cat_row->category_name.'</option>';*/ ?>
											<?php } ?>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group" id="subcategoryhere">
									<label class="col-sm-3 control-label">Sub Category</label>
                                    <div class="col-sm-5">
										<select class="form-control chosen-select" name="subcategory[]" id="sub_category" onchange="getSubSubCategory(this.id)" multiple data-placeholder="SELECT SUB CATEGORY">
											<option value=""></option>
                                            <?php
                                            if(isset($pro->temp_subcategory) && $pro->temp_subcategory != '' && $pro->temp_subcategory != 0){
												$arrSubCat = explode (',', $pro->temp_subcategory);
												foreach($arrSubCat AS $sc){
													$scData = getCategory(array('category_name'), $sc, $con);
													echo '<option '.$sel.' value="'.$sc.'">'.$scData->category_name.'</option>';
												}
											}
											else{
												echo ($sCatOp == '') ? $sCats : $sCatOp;
											}?>
										</select>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Sub Sub Category</label>
                                    <div class="col-sm-5">
										<select class="form-control chosen-select" name="subsubcategory[]" id="subsub_category" multiple data-placeholder="SELECT SUB SUB CATEGORY">
											<option value="0"></option>
                                            <?php echo $ssCatOp; ?>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Brand</label>
                                    <div class="col-sm-5">
										<select class="form-control" required name="brand" id="brandSel" onchange="getColorfromBrand(this.value);">
											<option value="">- SELECT BRAND -</option>
											<?php $br_rs = exec_query("SELECT * FROM tbl_brand ORDER BY brand_name", $con);
											while($br_row = mysqli_fetch_object($br_rs)){ ?>
												<option <?php if($isSession){ getSelected($br_row->brand_id, $pro->brand_id); } ?> value="<?php echo $br_row->brand_id; ?>"><?php echo $br_row->brand_name; ?></option>
											<?php } ?>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Product Name</label>
                                    <div class="col-sm-9">
										<input class="form-control" required name="name" id="prod" placeholder="Product Name" type="text" onblur="getSlug(this.id)" onKeyUp="getCount(this.value.length);" maxlength="70" style="float: left; width: 65%;" <?php if($isSession){ ?> value="<?php echo $pro->product_name; ?>" <?php } ?> />
                                        &nbsp; &nbsp; <small id="countSpan"></small>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Slug</label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="slug" id="prodSlug" placeholder="Slug" type="text" <?php if($isSession){ ?> value="<?php echo $pro->slug.rand(1, 99); ?>" <?php } ?> />
                                    </div>
                                </div>
                                <!-- hide it ----------------------------------------------------------------------------------------------------------------- --->
								<div class="form-group" style="display:none;">
									<label class="col-sm-3 control-label">Qty Available</label>
                                    <div class="col-sm-2">
										<input class="form-control inputmask" name="qty" placeholder="Qty Available" type="text" data-inputmask="'alias': 'decimal', 'autoGroup': true" style="text-align:left; width:40px;" value="10" />
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Gender</label><?php if($isSession){ $userGroup = explode(',', $pro->user_group); } ?>
                                    <div class="col-sm-9">
                                        <div class="switch-button showcase-switch-button sm">
                                            <input value="male" id="switch-button-6" name="group[]" type="checkbox" <?php if($isSession){ if(in_array('male', $userGroup)){ echo $chk; } } ?> >
                                            <label for="switch-button-6"></label> Male
                                        </div> &nbsp; &nbsp;
                                        <div class="switch-button showcase-switch-button sm">
                                            <input value="female" id="switch-button-7" name="group[]" type="checkbox" <?php if($isSession){ if(in_array('female', $userGroup)){ echo $chk; } } ?> >
                                            <label for="switch-button-7"></label> Female
                                        </div> &nbsp; &nbsp;
                                	</div>
                                </div>
								<hr/>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Age Group</label>
									<?php $ageGroup = array(); if($isSession){ $ageGroup = explode(',', $pro->age_group); } ?>
                                    <div class="col-sm-9">
                                        <div class="switch-button showcase-switch-button sm">
                                            <input value="0-5" id="switch-button-a1" name="age[]" type="checkbox" <?php if(in_array('0-5', $ageGroup)){ echo $chk; } ?> >
                                            <label for="switch-button-a1"></label> 0-5
                                        </div> &nbsp; &nbsp;
                                        <div class="switch-button showcase-switch-button sm">
                                            <input value="5-15" id="switch-button-a2" name="age[]" type="checkbox" <?php if(in_array('5-15', $ageGroup)){ echo $chk; } ?> >
                                            <label for="switch-button-a2"></label> 5-15
                                        </div> &nbsp; &nbsp;
										<div class="switch-button showcase-switch-button sm">
                                            <input value="15-25" id="switch-button-a3" name="age[]" type="checkbox" <?php if(in_array('15-25', $ageGroup)){ echo $chk; } ?> >
                                            <label for="switch-button-a3"></label> 15-25
                                        </div> &nbsp; &nbsp;
                                        <div class="switch-button showcase-switch-button sm">
                                            <input value="25-40" id="switch-button-a4" name="age[]" type="checkbox" <?php if(in_array('25-40', $ageGroup)){ echo $chk; } ?> >
                                            <label for="switch-button-a4"></label> 25-40
                                        </div> &nbsp; &nbsp;
                                        <div class="switch-button showcase-switch-button sm">
                                            <input value="40-55" id="switch-button-a5" name="age[]" type="checkbox" <?php if(in_array('40-55', $ageGroup)){ echo $chk; } ?> >
                                            <label for="switch-button-a5"></label> 40-55
                                        </div> &nbsp; &nbsp;
										<div class="switch-button showcase-switch-button sm">
                                            <input value="above 55" id="switch-button-a6" name="age[]" type="checkbox" <?php if(in_array('above 55', $ageGroup)){ echo $chk; } ?> >
                                            <label for="switch-button-a6"></label> above 55
                                        </div>
                                	</div>
                                </div>
								<hr/>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Stock Availability</label>
                                    <div class="col-sm-9">
                                    	<?php $avail = '100000000'; if($isSession){ $avail = $pro->stock_availability; } ?>
                                        <div class="switch-button showcase-switch-button sm">
                                            <input value="0" id="switch-button-6i" name="stock" type="radio" <?php echo getChecked(0, $avail); ?> >
                                            <label for="switch-button-6i"></label> Available
                                        </div> &nbsp; &nbsp;
                                        <div class="switch-button showcase-switch-button sm">
                                            <input value="1" id="switch-button-7i" name="stock" type="radio" <?php echo getChecked(1, $avail); ?> >
                                            <label for="switch-button-7i"></label> Not Available
                                        </div> &nbsp; &nbsp;
										<div class="switch-button showcase-switch-button sm">
                                            <input value="2" id="switch-button-8i" name="stock" type="radio" <?php echo getChecked(2, $avail); ?> >
                                            <label for="switch-button-8i"></label> Allow Backorder
                                        </div>
                                	</div>
                                </div>
								
                                <!-- hide it ----------------------------------------------------------------------------------------------------------------- --->
								<div class="form-group">
									<label class="col-sm-3 control-label">Qty Available</label>
                                    <div class="col-sm-2">
										<input class="form-control inputmask" name="qty" placeholder="Qty Available" type="text" data-inputmask="'alias': 'decimal', 'autoGroup': true" style="text-align:left; width:40px;" value="10" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Product SKU #</label>
                                    <div class="col-sm-4">
										<input class="form-control" required name="sku" placeholder="Product SKU #" type="text" onBlur="chkProductFields('SKU');" id="pSKU" onKeyUp="getSKUHint(this.value)" autocomplete="off" />
                                        <div id="putSKUHint"></div>
                                        <input type="hidden" id="hSKU" value="0" />
                                    </div>
                                </div>
								<div class="form-group" style="display:none;">
									<label class="col-sm-3 control-label">Product UPC #</label>
                                    <div class="col-sm-4">
										<input class="form-control" name="upc" placeholder="Product UPC #" type="text" value="" />
                                        <input type="hidden" id="hUPC" value="1" />
                                    </div>
                                </div>
                                <div class="form-group">
									<label class="col-sm-3 control-label">Manufacturer Code</label>
                                    <div class="col-sm-6">
										<input class="form-control" name="mcode" placeholder="Manufacturer Code" type="text" <?php if($isSession){ ?> value="<?php echo $pro->manufacturer_code; ?>" <?php } ?> />
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
									<label class="col-sm-3 control-label">Color</label>
                                    <div class="col-sm-9" id="colorpallet">
                                    	<?php if($isSession){ $iCol = 1;
										$col_rs = exec_query("SELECT * FROM tbl_color WHERE (brand = '$pro->brand_id' OR color_code = '#ffffff') ORDER BY color", $con);
										while($col_row = mysqli_fetch_object($col_rs)){
										?>
											<input id="color<?php echo $iCol; ?>" type="checkbox" value="<?php echo $col_row->color_code; ?>" name="color[]" <?php if(in_array($col_row->color_code, $pColor)){ echo $chk; } ?> />
											<label for="color<?php echo $iCol; ?>" style="background-color:<?php echo $col_row->color_code; ?>" class="tooltip-btn" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo $col_row->color; ?>">
												<span class="fa fa-check">&nbsp;</span>
											</label>
										<?php $iCol++; } } ?>
                                    </div>
								</div>
								<hr/>
                                
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Size</label>
                                    <div class="col-sm-9">
                                    	<?php $size = ''; $sizeUnit = '';
                                        if($isSession){
											if($pro->size != ''){
												$sizeArr = explode(' ', $pro->size);
												$size = $sizeArr[0];
												$sizeUnit = $sizeArr[1];
											}
										}
										?>
                                   		<div class="col-sm-12">
										<input class="form-control col-sm-2" name="size" placeholder="Enter Size" type="text" style="width:92px; margin-right:10px;" <?php if($isSession){ ?> value="<?php echo $size; ?>" <?php } ?> />
                                        <select class="form-control" name="sizeUnit" style="width: 196px;">
											<option value="">- SELECT SIZE UNIT -</option>
											<?php $si_rs = exec_query("SELECT * FROM tbl_size ORDER BY size", $con);
											while($si_row = mysqli_fetch_object($si_rs)){ ?>
												<option <?php if($sizeUnit == $si_row->size){ echo $sel; } ?> value="<?php echo $si_row->size;?>"><?php echo $si_row->size;?></option>
											<?php }?>
										</select>
                                    </div>
                                    </div>
                                </div>
                                
                                <hr/>
                                <div class="form-group">
									<label class="col-sm-3 control-label">Keyword</label>
                                    <div class="col-sm-9">
										<textarea class="form-control" name="keyword" placeholder="Keywords" cols="" rows="3" ><?php if($isSession){ echo $pro->keyword; } ?></textarea>
                                    </div>
                                </div>
                                
								<!-- commented size is independent <div class="form-group">
                                    <label class="col-sm-3 control-label">Size</label>
                                    <div class="col-sm-9" id="sizeBlock"></div>
                                </div>-->
								<hr/>
								<div class="form-group">
									<input type="hidden" name="action" value="productAdd" />
									<input type="hidden" value="" name="data1" />
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
function getSKUHint(val){
	length = val.length;
	if(val != '' && length > 1){
		$.get('adminAjax.php', {'action' : 'getSku', 'data1' : val, 'dataTempId' : '3fda7g5a12yed0udi3e1duiv6d02158b.cloud'}, function(data){
			if(data != 0){
				$('#putSKUHint').html('You can try : '+data).css('display', 'block');
			}
			else{
				$('#putSKUHint').html('<div></div>').css('display', 'none');
			}
		});
	}
}
function getCount(data){
	no = 70 - data;
	$('#countSpan').text(no+' Character Left');
}
function getSubCategory(id){
	ids = getSelOpt(id);
	$.get('adminAjax.php', {'action' : 'getSubCategory', 'data1' : ids, 'mul' : 'mul', 'dataTempId' : '3fda701a15a12ded0c3e1d46d0f2158b.cloud'}, function(data){
		$('#sub_category').html(data);
		$("#sub_category").trigger("chosen:updated");
		//getCategoryBrand(cid); commented because, now brand is independent!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	});
}
/*function getCategoryBrand(cid){
	$.get('adminAjax.php', {'action' : 'getCategoryBrand', 'data1' : cid, 'dataTempId' : '3qdv701a15a22ded0c311d46d0f2157c.cloud'}, function(data){
		$('#brandSel').html(data);
	});
}*/
function getSubSubCategory(id){
	ids = getSelOpt(id);
	$.get('adminAjax.php', {'action' : 'getSubSubCategory', 'data1' : ids, 'mul' : 'mul', 'dataTempId' : '3fda701a25541656728de7d809dfgc21.cloud'}, function(data){
		$('#subsub_category').html(data);
		$("#subsub_category").trigger("chosen:updated");
		
		/*getSubcategorySize(ids);  -  commendet bcoz size is now independent, so function definition also commented */
	});
}
/*function getSubcategorySize(scid){
	$.get('adminAjax.php', {'action' : 'getSubcategorySize', 'data1' : scid, 'mul' : 'mul', 'dataTempId' : '3qdv701a15a22ded1c311d46d0f2157c.cloud.uk'}, function(data){
		$('#sizeBlock').html(data);
	});
}*/
function getColorfromBrand(bid){
	$.get('adminAjax.php', {'action' : 'getColorfromBrand', 'data1' : bid, 'dataTempId' : '3qv701a15a22ded00c31d46d1f21571.cloud.uk'}, function(data){
		$('#colorpallet').html(data);
	});
}
function chkSizeColor(){
	sku = $('#hSKU').val();
	//upc = $('#hUPC').val();
	if(sku == 1){ return true; }
	else{ alert('Invalid SKU'); return false; }
}

function chkProductFields(type){
	val = $('#p'+type).val();
	
	if(type == 'UPC'){  }
	else if(type == 'SKU'){  }
	
	$.get('adminAjax.php', {'action' : 'chkProductFields', 'data1' : val, 'data2' : type, 'dataTempId' :  'd3w701a255416561wde7d809dfga1.cloud'}, function(data){
		if(data){ put = 1; }
		else{
			put = 0;
			alert(type+' Already Exist, Try Another.');
		}
		$('#h'+type).val(put);
	});
}
function setSku(sku){
	$('#pSKU').val(sku);
}
</script>
<script type="text/javascript" src="assets/js/js.js"></script>

<?php include 'include/formJs.php'; ?>
</body>
</html>