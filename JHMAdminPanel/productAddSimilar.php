<?php include 'include/header.php';
chkParam($_GET['data1'], 'home.php'); ?>

<script>
	window.history.forward();
	function noBack(){ window.history.forward(); }
</script>
<div onload="noBack();" onpageshow="if (event.persisted) noBack();">  </div>

<?php $id = $_GET['data1'];
$pro = getProduct($id, $con);
$chk = 'checked="checked"';
$sel = 'selected="selected"';
$cat_rs = exec_query("select category_id from tbl_product_category WHERE product_id = '$id'", $con);
while($cat_row = mysql_fetch_object($cat_rs)){ $catArr[] = $cat_row->category_id; }
$sCatOp = ''; $ssCatOp = ''; $sCats = '';
$mCats = array();
$pColor = array();
$tempSubArr = array();
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
while($proColRow = mysql_fetch_object($proColRs)){ $pColor[] = $proColRow->color_code; }
?>
        <div class="warper container-fluid">
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
										<select class="form-control chosen-select" name="mainCategory[]" id="parentSel" onchange="getSubCategory(this.id)" data-placeholder="SELECT MAIN CATEGORY" multiple required >
											<option value=""></option>
											<?php
											$cat_rs = exec_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0 ORDER BY category_name", $con);
											while($cat_row = mysql_fetch_object($cat_rs)){ ?>
												<option
												<?php echo (in_array($cat_row->category_id, $mCats) || in_array($cat_row->category_id, $catArr))?$sel:''; ?>
                                                value="<?php echo $cat_row->category_id; ?>"><?php echo $cat_row->category_name; ?></option>
											<?php }?>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Sub Category <?php //echo $pro->temp_subcategory; ?></label>
                                    <div class="col-sm-5">
										<select class="form-control chosen-select" name="subcategory[]" id="sub_category" onchange="getSubSubCategory(this.id)" multiple>
											<option value="">- SELECT SUB CATEGORY -</option>
                                            <?php
                                            if($pro->temp_subcategory != '' && $pro->temp_subcategory != 0){
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
										<select class="form-control chosen-select" name="subsubcategory[]" id="subsub_category" multiple>
											<option value="0">- SELECT SUB SUB CATEGORY -</option>
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
											while($br_row = mysql_fetch_object($br_rs)){ ?>
												<option <?php getSelected($br_row->brand_id, $pro->brand_id); ?> value="<?php echo $br_row->brand_id;?>"><?php echo $br_row->brand_name; ?></option>
											<?php }?>
										</select>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Product Name</label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="name" id="prod" placeholder="Product Name" type="text" onblur="getSlug(this.id)" value="<?php echo $pro->product_name; ?>" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Slug</label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="slug" id="prodSlug" placeholder="Slug" type="text"
                                        value="<?php echo $pro->slug; ?>" />
                                    </div>
                                </div>
								<!-- hide it ----------------------------------------------------------------------------------------------------------------- --->
								<div class="form-group" style="display:none;">
									<label class="col-sm-3 control-label">Qty Available</label>
                                    <div class="col-sm-2">
										<input class="form-control inputmask" name="qty" placeholder="Qty Available" type="text" data-inputmask="'alias': 'decimal', 'autoGroup': true" style="text-align:left;" value="<?php echo $pro->qty; ?>" />
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Gender</label><?php $userGroup = explode(',', $pro->user_group); ?>
                                    <div class="col-sm-9">
                                        <div class="switch-button showcase-switch-button sm">
                                            <input value="male" id="switch-button-6" name="group[]" type="checkbox" <?php if(in_array('male', $userGroup)){ echo $chk; } ?> >
                                            <label for="switch-button-6"></label> Male
                                        </div> &nbsp; &nbsp;
                                        <div class="switch-button showcase-switch-button sm">
                                            <input value="female" id="switch-button-7" name="group[]" type="checkbox" <?php if(in_array('female', $userGroup)){ echo $chk; } ?> >
                                            <label for="switch-button-7"></label> Female
                                        </div> &nbsp; &nbsp;
										<!--<div class="switch-button showcase-switch-button">
                                            <input value="boy" id="switch-button-8" name="group[]" type="checkbox" >
                                            <label for="switch-button-8"></label> Boy
                                        </div>-->
                                	</div>
                                </div>
								<hr/>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Age Group</label><?php $ageGroup = explode(',', $pro->age_group); ?>
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
                                    <div class="col-sm-9"><?php $avail = $pro->stock_availability; ?>
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
								<div class="form-group"> <?php /*$pro->product_sku;*/ ?>
									<label class="col-sm-3 control-label">Product SKU #</label>
                                    <div class="col-sm-4">
										<input class="form-control" required name="sku" placeholder="Product SKU #" type="text" onBlur="chkProductFields('SKU');" id="pSKU" />
                                        <input type="hidden" id="hSKU" value="0" />
                                    </div>
                                </div>
								<div class="form-group" style="display:none;">
									<label class="col-sm-3 control-label">Product UPC #</label>
                                    <div class="col-sm-4">
										<input class="form-control" name="upc" placeholder="Product UPC #" type="text" />
                                        <input type="hidden" id="hUPC" value="1" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Manufacturer Code</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" name="mcode" placeholder="Manufacturer Code" type="text" value="<?php echo $pro->manufacturer_code; ?>" />
                                    </div>
                                </div>
								<hr/>
                                
								<div class="form-group">
									<label class="col-sm-3 control-label">Color</label>
                                    <div class="col-sm-9" id="colorpallet">
                                    <?php $iCol = 1;
                                    $col_rs = exec_query("SELECT * FROM tbl_color WHERE (brand = '$pro->brand_id' OR color_code = '#ffffff') ORDER BY color", $con);
                                    while($col_row = mysql_fetch_object($col_rs)){
									?>
                                        <input id="color<?php echo $iCol; ?>" type="checkbox" value="<?php echo $col_row->color_code; ?>" name="color[]" <?php if(in_array($col_row->color_code, $pColor)){ echo $chk; } ?> />
                                        <label for="color<?php echo $iCol; ?>" style="background-color:<?php echo $col_row->color_code; ?>" class="tooltip-btn" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo $col_row->color; ?>">
                                        	<span class="fa fa-check">&nbsp;</span>
                                        </label>
                                    <?php $iCol++; } ?>
                                    </div>
								</div>
								<hr/>
								
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Size</label>
                                    <div class="col-sm-9">
                                    	<?php $size = ''; $sizeUnit = '';
										if($pro->size != ''){
											$sizeArr = explode(' ', $pro->size);
											$size = $sizeArr[0];
											$sizeUnit = $sizeArr[1];
										}
										?>
                                   		<div class="col-sm-12">
										<input class="form-control col-sm-2" name="size" placeholder="Enter Size" type="text" style="width:92px; margin-right:10px;" value="<?php echo $size; ?>" />
                                        <select class="form-control" name="sizeUnit" style="width: 196px;">
											<option value="">- SELECT SIZE UNIT -</option>
											<?php $si_rs = exec_query("SELECT * FROM tbl_size ORDER BY size", $con);
											while($si_row = mysql_fetch_object($si_rs)){ ?>
												<option <?php if($sizeUnit == $si_row->size){ echo $sel; } ?> value="<?php echo $si_row->size; ?>"><?php echo $si_row->size; ?></option>
											<?php } ?>
										</select>
                                    </div>
                                    </div>
                                </div>
                                
                                <hr/>
                                <div class="form-group">
									<label class="col-sm-3 control-label">Keyword</label>
                                    <div class="col-sm-9">
										<textarea class="form-control" name="keyword" placeholder="Keywords" cols="" rows="3" ><?php echo $pro->keyword; ?></textarea>
                                    </div>
                                </div>
                                
                                <!--<div class="form-group">
                                    <label class="col-sm-3 control-label">Size</label>
                                    <div class="col-sm-9" id="sizeBlock"></div>
                                </div>
								<hr/>-->
								<div class="form-group">
									<input type="hidden" name="action" value="productAdd" />
									<input type="hidden" value="<?php echo $id; ?>" name="dataCopy" />
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
function getSubCategory(id){
	ids = getSelOpt(id);
	$.get('adminAjax.php', {'action' : 'getSubCategory', 'data1' : ids, 'mul' : 'mul', 'dataTempId' : '3fda701a15a12ded0c3e1d46d0f2158b.cloud'}, function(data){
		$('#sub_category').html(data);
		$("#sub_category").trigger("chosen:updated");
		//$('#sub_category').chosen();
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
		getSubcategorySize(ids);
	});
}
function getSubcategorySize(scid){
	$.get('adminAjax.php', {'action' : 'getSubcategorySize', 'data1' : scid, 'mul' : 'mul', 'dataTempId' : '3qdv701a15a22ded1c311d46d0f2157c.cloud.uk'}, function(data){
		$('#sizeBlock').html(data);
	});
}
function getColorfromBrand(bid){
	$.get('adminAjax.php', {'action' : 'getColorfromBrand', 'data1' : bid, 'dataTempId' : '3qv701a15a22ded00c31d46d1f21571.cloud.uk'}, function(data){
		$('#colorpallet').html(data);
	});
}
function chkSizeColor(){
	/*var objColor = '';
	$('#colorpallet input[type=checkbox]:checked').each(function() {
		if(this.value != ''){
			if (objColor == ''){ objColor = this.value; }
			else{ objColor = objColor+','+this.value; }
		}
	});
	if(objColor != ''){
		var objSize = '';
		$('#sizeBlock input[type=radio]:checked').each(function() {
			if(this.value != ''){
				if (objSize == ''){ objSize = this.value; }
				else{ objSize = objSize+','+this.value; }
			}
		});
		if(objSize != ''){ /* now chk sku upc */
			sku = $('#hSKU').val();
			//upc = $('#hUPC').val();
			if(sku == 1){ return true; }
			else{ alert('Invalid SKU or UPC'); return false; }
		/*}
		else{ alert('Failed, Please select atleast one size!'); return false; }
	}
	else{ alert('Failed, Please select atleast one color!'); return false; }*/
	//alert(objColor);
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
</script>
<script type="text/javascript" src="assets/js/js.js"></script>
<?php include 'include/formJs.php'; ?>
<script>
getSubcategorySize('<?php echo $pro->temp_subcategory; ?>');
</script>
</body>
</html>