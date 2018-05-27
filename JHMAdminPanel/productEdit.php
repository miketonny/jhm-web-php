<?php include 'include/header.php';
chkParam($_GET['data1'], 'home.php');
$id = $_GET['data1'];
$pro = getProduct($id, $con);
$chk = 'checked="checked"';
$sel = 'selected="selected"';
$cat_rs = exec_query("select category_id from tbl_product_category WHERE product_id = '$id'", $con);
while($cat_row = mysqli_fetch_object($cat_rs)){ $catArr[] = $cat_row->category_id; }
$sCatOp = ''; $ssCatOp = ''; $sCats = '';
$mCats = array();
$tempSubArr = array();
if(!empty($catArr)){
	foreach($catArr AS $cat){
		$level = chkCategoryLevel($cat, $con);
		if($level == 'subCat'){
			$scat = getCategory(array('category_name', 'category_id', 'superparent_id'), $cat, $con);
			$sCatOp .= '<option '.$sel.' value="'.$scat->category_id.'">'.$scat->category_name.'</option>';
			
			/* for show cat */
			$mainCats = getCategory(array('category_name', 'category_id'), $scat->superparent_id, $con);
			$mCats[] = $mainCats->category_id;
		}
		elseif($level == 'subSubCat'){
			$sscat = getCategory(array('category_name', 'category_id', 'parent_id'), $cat, $con);
			$ssCatOp .= '<option '.$sel.' value="'.$sscat->category_id.'">'.$sscat->category_name.'</option>';
			
			/* for show cat */
			$subCats = getCategory(array('category_name', 'category_id', 'superparent_id'), $sscat->parent_id, $con);
			if (isset($subCats)) {
				if(!in_array($subCats->category_id, $tempSubArr)){
				$tempSubArr[$subCats->category_name] = $subCats->category_id;
			}
				$mainCats = getCategory(array('category_name', 'category_id'), $subCats->superparent_id, $con);
				$mCats[] = $mainCats->category_id;
			}		
		}
	}
}

if(!empty($tempSubArr)){
	foreach($tempSubArr AS $key => $val){
		$sCats .= '<option '.$sel.' value="'.$val.'">'.$key.'</option>';
	}
}
?>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Product <small>Info / Edit Product</small></h1></div>
        	<div class="row">
            
            	<ul role="tablist" class="nav nav-tabs" id="myTab">
                	<li class="active"><a data-toggle="tab" role="tab" href="#product">Product</a></li>
                    <li><a data-toggle="tab" role="tab" href="#desc">Description</a></li>
					<!--<li><a data-toggle="tab" role="tab" href="#step">Usage</a></li>-->
					<li><a data-toggle="tab" role="tab" href="#detail">Price & Media</a></li>
                </ul>
                
				<div class="tab-content" id="myTabContent">
					<!-- product basic details start -->
                	<div id="product" class="tab-pane tabs-up fade in active panel panel-default">
                    	<div class="panel-body">
						<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data" onsubmit="return chkFormData();">
                        	
							<div class="form-group">
								<label class="col-sm-3 control-label">Main Category</label>
								<div class="col-sm-5">
									<select class="form-control chosen-select" name="mainCategory[]" id="parentSel" onchange="getSubCategory(this.id)" data-placeholder="SELECT MAIN CATEGORY" multiple required>
										<option value=""></option>
										<?php
										$cat_rs = exec_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0 ORDER BY category_name", $con);
										while($cat_row = mysqli_fetch_object($cat_rs)){ ?>
											<option
											<?php echo (in_array($cat_row->category_id, $mCats) || in_array($cat_row->category_id, $catArr))?$sel:''; ?>
                                            value="<?php echo $cat_row->category_id;?>"><?php echo $cat_row->category_name; ?></option>
										<?php }?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Sub Category</label>
								<div class="col-sm-5">
									<select class="form-control chosen-select" name="subcategory[]" id="sub_category" onchange="getSubSubCategory(this.id)" multiple data-placeholder="SELECT SUB CATEGORY">
										<option value=""></option>
										<?php //echo $sCatOp; ?>
                                        <?php //echo ($sCatOp == '') ? $sCats : $sCatOp; ?>
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
									<select class="form-control chosen-select" name="subsubcategory[]" id="subsub_category" multiple data-placeholder="SELECT SUB SUB CATEGORY">
										<option value="0"></option>
										<?php echo $ssCatOp; ?>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">Brand</label>
								<div class="col-sm-5">
									<select class="form-control" required name="brand" id="brandSel">
										<option value="">- SELECT BRAND -</option>
										<?php $br_rs = exec_query("SELECT * FROM tbl_brand ORDER BY brand_name", $con);
										while($br_row = mysqli_fetch_object($br_rs)){ ?>
											<option <?php getSelected($br_row->brand_id, $pro->brand_id); ?> value="<?php echo $br_row->brand_id; ?>"><?php echo $br_row->brand_name; ?></option>
										<?php }?>
										<option value="<?php echo $pro->brand_id; ?>" selected="selected"><?php echo $pro->brand_name; ?></option>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">Product Name</label>
								<div class="col-sm-9">
									<input class="form-control" required name="name" id="prod" placeholder="Product Name" type="text" onblur="getSlug(this.id)" value="<?php echo $pro->product_name; ?>" onKeyUp="getCount(this.value.length);" maxlength="70" style="float: left; width: 65%;" /> 
                                     &nbsp; &nbsp; <small id="countSpan"></small>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">Slug</label>
								<div class="col-sm-6">
									<input class="form-control" required name="slug" id="prodSlug" placeholder="Slug" type="text"
                                    value="<?php echo $pro->slug; ?>" />
								</div>
							</div>
							
							
							<hr/>
							<div class="form-group">
								<label class="col-sm-3 control-label">Gender</label><?php $userGroup = explode(',', $pro->user_group); ?>
								<div class="col-sm-9">
									<div class="switch-button showcase-switch-button sm">
										<input value="male" id="switch-button-6" name="group[]" type="checkbox" <?php if(in_array('male', $userGroup)){ echo $chk; } ?>>
										<label for="switch-button-6"></label> Male
									</div> &nbsp; &nbsp;
									<div class="switch-button showcase-switch-button sm">
										<input value="female" id="switch-button-7" name="group[]" type="checkbox" <?php if(in_array('female', $userGroup)){ echo $chk; } ?>>
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
										<input value="0-5" id="switch-button-a1" name="age[]" type="checkbox" <?php if(in_array('0-5', $ageGroup)){ echo $chk; } ?>>
										<label for="switch-button-a1"></label> 0-5
									</div> &nbsp; &nbsp;
									<div class="switch-button showcase-switch-button sm">
										<input value="5-15" id="switch-button-a2" name="age[]" type="checkbox" <?php if(in_array('5-15', $ageGroup)){ echo $chk; } ?>>
										<label for="switch-button-a2"></label> 5-15
									</div> &nbsp; &nbsp;
									<div class="switch-button showcase-switch-button sm">
										<input value="15-25" id="switch-button-a3" name="age[]" type="checkbox" <?php if(in_array('15-25', $ageGroup)){ echo $chk; } ?>>
										<label for="switch-button-a3"></label> 15-25
									</div> &nbsp; &nbsp;
									<div class="switch-button showcase-switch-button sm">
										<input value="25-40" id="switch-button-a4" name="age[]" type="checkbox" <?php if(in_array('25-40', $ageGroup)){ echo $chk; } ?>>
										<label for="switch-button-a4"></label> 25-40
									</div> &nbsp; &nbsp;
									<div class="switch-button showcase-switch-button sm">
										<input value="40-55" id="switch-button-a5" name="age[]" type="checkbox" <?php if(in_array('40-55', $ageGroup)){ echo $chk; } ?>>
										<label for="switch-button-a5"></label> 40-55
									</div> &nbsp; &nbsp;
									<div class="switch-button showcase-switch-button sm">
										<input value="above 55" id="switch-button-a6" name="age[]" type="checkbox" <?php if(in_array('above 55', $ageGroup)){ echo $chk; } ?>>
										<label for="switch-button-a6"></label> above 55
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label class="col-sm-3 control-label">Stock Availability</label>
								<div class="col-sm-9"> <?php $avail = $pro->stock_availability; ?>
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
							
							<!-- hide it ----------------------------------------------------------------------------------------------------------------- -->
							<div class="form-group" style="display:none;">
								<label class="col-sm-3 control-label">Qty Available</label>
								<div class="col-sm-2"> 
									<input class="form-control inputmask" required name="qty" placeholder="Qty Available" type="text" data-inputmask="'alias': 'decimal', 'autoGroup': true" style="text-align:left; width:45px;" value="<?php echo $pro->qty; ?>" />
								</div>
							</div>
							
							
							<div class="form-group">
								<label class="col-sm-3 control-label">Product SKU #</label>
								<div class="col-sm-4">
									<input class="form-control" required name="sku" placeholder="Product SKU #" type="text" value="<?php echo $pro->product_sku; ?>" onBlur="chkProductFields('SKU');" id="pSKU" />
                                	<input type="hidden" id="hSKU" value="1" />
								</div>
							</div>
							<div class="form-group" style="display:none;">
								<label class="col-sm-3 control-label">Product UPC #</label>
								<div class="col-sm-4">
									<input class="form-control" name="upc" placeholder="Product UPC #" type="text" value="<?php echo $pro->product_upc; ?>" onBlur="chkProductFields('UPC');" id="pUPC" />
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
                                        while($si_row = mysqli_fetch_object($si_rs)){ ?>
                                        	<option <?php if($sizeUnit == $si_row->size){ echo $sel; } ?> value="<?php echo $si_row->size; ?>"><?php echo $si_row->size; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                </div>
                            </div>
                            
                            <hr/>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Keyword</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="keyword" placeholder="Keywords" cols="" rows="3" required ><?php echo $pro->keyword; ?></textarea>
                                </div>
                            </div>
                            
							<!--<div class="form-group">
								<label class="col-sm-3 control-label">Size</label>
								<div class="col-sm-9" id="sizeBlock"></div>
							</div>-->
							<hr/>
							<div class="form-group">
								<div class="col-lg-9 col-lg-offset-3">
									<input type="hidden" name="action" value="productEdit" />
									<input type="hidden" name="data1" value="<?php echo $id; ?>" />
									<input type="hidden" name="data2" value="<?php echo $_GET['data2']; ?>" />
									<button class="btn btn-primary" type="submit"> Save Changes! </button>
								</div>
							</div>
						</form>	
                        </div>
                    </div><!-- product basic details end --------------------------------------------------------  -->
                    
					<!-- desc details start -->
                    <style> #tagSel_chosen{ width:80% !important; } #cke_1_contents, #cke_2_contents{ height:420px !important; } </style>
					<div id="desc" class="tab-pane tabs-up fade panel panel-default">
                    	<div class="panel-body">
						<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                        	<div class="form-group">
								<label class="col-sm-2 control-label">Summary</label>
								<div class="col-sm-9">
									<textarea class="form-control" id="ckeditor1" name="summary" placeholder="Summary" cols="100" rows="10" ><?php echo $pro->product_summary; ?></textarea>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label class="col-sm-2 control-label">Usage</label>
								<div class="col-sm-9">
									<textarea class="form-control" id="ckeditor2" name="usage" placeholder="Description" cols="100" rows="10" ><?php echo $pro->product_description; ?></textarea>
								</div>
							</div>
                            
							<hr/>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tag</label>
                                <div class="col-sm-9">
                                    <select class="form-control chosen-select" name="tag[]" data-placeholder="SELECT TAG" multiple id="tagSel">
                                        <option value=""></option>
                                        <?php
										$taggArr = explode(',', $pro->tag);
                                        $tag_rs = exec_query("SELECT title FROM tbl_tag ORDER BY title", $con);
                                        while($tag_row = mysqli_fetch_object($tag_rs)){ ?>
                                         	<option <?php echo (in_array($tag_row->title, $taggArr))?$sel:''; ?> ><?php echo $tag_row->title;?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <hr/>
                                <h3>Product Specification</h3>
                            <hr/>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Dimensions</label>
                                <div class="col-sm-9">
                                    <input class="form-control col-sm-2" name="height" placeholder="Enter Height" type="text" style="width:115px; margin-right:10px;" value="<?php if($pro->height != 0){ echo $pro->height; } ?>" />
                                    <input class="form-control col-sm-2" name="width" placeholder="Enter Width" type="text" style="width:115px; margin-right:10px;" value="<?php if($pro->width != 0){ echo $pro->width; } ?>" />
                                    <small>in CM (centimeter)</small>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Weight</label>
                                <?php $w = ''; $wUnit = '';
								if($pro->weight != ''){
									$weight = $pro->weight;
									$weightArr = explode(' ', $weight);
									$w = $weightArr[0];
									$wUnit = $weightArr[1];
								} ?>
                                <div class="col-sm-9">
                                    <input class="form-control col-sm-2" name="weight" placeholder="Enter Weight" type="text" style="width:115px; margin-right:10px;"  value="<?php echo $w; ?>" />
                                    <select class="form-control" name="weightUnit" style="width: 196px;" >
                                        <option value="">- SELECT WEIGHT UNIT -</option>
                                        <option <?php getSelected($wUnit, 'G'); ?>>G</option>
                                        <option <?php getSelected($wUnit, 'KG'); ?>>KG</option>
                                        <option <?php getSelected($wUnit, 'LBS'); ?>>LBS</option>
                                    </select>
                                </div>
                            </div>
                            <hr/>
                            
							<div class="form-group">
								<div class="col-lg-9 col-lg-offset-3">
									<input type="hidden" name="action" value="productEditDesc" />
									<input type="hidden" name="data1" value="<?php echo $id; ?>" />
									<input type="hidden" name="data2" value="<?php echo $_GET['data2']; ?>" />
									<button class="btn btn-primary" type="submit"> Save Changes! </button>
								</div>
							</div>
						</form>	
                        </div>
                    </div><!-- desc details start -->
					
					<!-- step details start -->
					<?php /*<div id="step" class="tab-pane tabs-up fade panel panel-default">
                    	<div class="panel-body">
						<style>#utext{ position:absolute; }</style>
						<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                        	
							<div class="form-group">
								<label class="col-sm-3 control-label">Usage Image</label>
								<div class="col-sm-5">
									<!-- -0-------------- -->
									<?php $ol = ''; $i = 0;
									$rsStepImg = getProductStepImg($id, $con);
									$stepNum = mysql_num_rows($rsStepImg);
									if($stepNum > 0){
									?>
									<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
										<div class="carousel-inner">
										<?php
										while($rowStepImg = mysql_fetch_object($rsStepImg)){
											$active = ($i == 0)?'active':'';
											$ol .= '<li data-target="#carousel-example-generic" data-slide-to="'.$i.'" class="'.$active.'"></li>';
										?>
											<div class="item <?php echo $active; ?>" <?php if($rowStepImg->img == ''){ echo 'style="height: 150px;"'; } ?> >
												<img src="../site_image/usage/<?php echo $rowStepImg->img; ?>" alt="Images..." style="">
												<div class="carousel-caption">
													<p style="color:#333;"> <?php echo $rowStepImg->text; ?> <br/> <button onclick="deleteStepImg(<?php echo $rowStepImg->recid; ?>)" type="button" class="btn btn-danger btn-xs"> <span class="glyphicon glyphicon-trash"></span> Delete Image</button> </p>
												</div>
											</div>
										<?php $i++; } ?>
										</div>
										
										<ol class="carousel-indicators"><?php echo $ol; ?></ol>
										
										<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
											<span class="glyphicon glyphicon-chevron-left"></span>
										</a>
										<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
											<span class="glyphicon glyphicon-chevron-right"></span>
										</a>
									</div>
									<?php }else{ echo 'No Images Found!'; } ?>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label class="col-sm-3 control-label">Add More Usage Image</label>
								<div class="col-sm-9">
									<input class="form-control input-sm" name="text[]" placeholder="Your Content" type="text" style="width:50%;float:left;" required /> 
									<input type="file" name="img[]" />
								</div>
								<div id="usageHere" style="margin-left: 25%;width: 100%;"></div>
								<div style="clear:both;"></div>
								<div> <button type="button" onclick="addMore();">Add More!</button> </div>
								<input type="hidden" id="co" value="<?php echo $stepNum+2; ?>" />
							</div>
							<hr/>
							<div class="form-group">
								<div class="col-lg-9 col-lg-offset-3">
									<input type="hidden" name="action" value="productStepImgEdit" />
									<input type="hidden" name="data1" value="<?php echo $id; ?>" />
									<input type="hidden" name="data2" value="<?php echo $_GET['data2']; ?>" />
									<button class="btn btn-primary" type="submit"> Save Changes! </button>
								</div>
							</div>
						</form>	
                        </div>
                    </div><!-- step details start -->*/ ?>
					
					<!-- detail details start -->
					<div id="detail" class="tab-pane tabs-up fade panel panel-default">
						<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
						<button type="button" class="btn btn-purple btn-flat btn-lg" style="margin: 16px 0px 0px 31px;" data-toggle="modal" data-target="#modal-form">Add New Color!</button>
                    	<div class="panel-body">
							<!-- loop start -->
							<?php
							$i = 1;
							$colorNotGet = '';
							$rsProColor = exec_query("SELECT tpc.*, tpp.product_price, tpp.qty as stock, tpp.backorder_qty as backorderstock, tpp.cost as cost, tpp.product_rrp, tpp.product_upc, tbl_color.color FROM tbl_product_color tpc
							LEFT JOIN tbl_color ON tbl_color.color_code = tpc.color_code
							LEFT JOIN tbl_product_price tpp ON tpp.color_id = tpc.color_id
							WHERE tpc.product_id = '$id'", $con);
							$numCol = mysqli_num_rows($rsProColor);
							while($rowProColor = mysqli_fetch_object($rsProColor)){
								$colorNotGet .= ($colorNotGet == '')?"'".$rowProColor->color."'":",'".$rowProColor->color."'";
							?>
							<div class="col-md-6">
								<div class="panel panel-default">
									<!-- <div><P> <?php echo $colorNotGet?></p></div>-->
									<div class="panel-heading" style=" background:<?php echo $rowProColor->color_code; ?>; ">Edit Product Detail (<?php echo $color = $rowProColor->color; ?>)</div>
									<div class="panel-body">
											
										<div class="form-group">
											<label class="col-sm-4 control-label">Price</label>
											<div class="col-sm-4">
												<input type="text" class="form-control inputmask" data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" required name="price[]" placeholder="Product Price for <?php echo $color; ?> Color" value="<?php echo $rowProColor->product_price; ?>">
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-sm-4 control-label">Stock</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" required name="stock[]" placeholder="Available Stock <?php echo $color; ?> Color" value="<?php echo $rowProColor->stock; ?>">
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-sm-4 control-label">Cost</label>
											<div class="col-sm-4">
												<input type="text" class="form-control inputmask" data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" required  name="cost[]" placeholder=" Color" value="<?php echo $rowProColor->cost; ?>">
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-sm-4 control-label">Produt RRP</label>
											<div class="col-sm-4">
												<input type="text" class="form-control inputmask" data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" required name="rrp[]" placeholder="Product RRP for <?php echo $color; ?> Color" value="<?php echo $rowProColor->product_rrp; ?>">
											</div>
										</div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Product UPC #</label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="upc[]" placeholder="Product UPC #" type="text" required value="<?php echo $rowProColor->product_upc; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
											<label class="col-sm-4 control-label">Back Order Quantity</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" required name="backorderstock[]" placeholder="Available Back Order Stock <?php echo $color; ?> Color" value="<?php echo $rowProColor->backorderstock; ?>">
											</div>
										</div>
                                        <hr/>
										
                                        
                                        <div class="form-group">
                                            <?php
											$rsProImg = exec_query("SELECT recid, media_src, media_thumb FROM tbl_product_media tpm WHERE tpm.media_type = 'img' AND tpm.color_id = '$rowProColor->color_id' AND tpm.product_id = '$id' AND tpm.media_src != 'product.png' AND tpm.is_main = 1", $con);
											if(mysqli_num_rows($rsProImg)){ $rowMain = mysqli_fetch_object($rsProImg); ?>
												<img src="../site_image/product/<?php echo $rowMain->media_src; ?>" height="120" />
											<?php }else{ echo 'No Images Found!'; } ?>
                                            <label class="col-sm-4 control-label">Product Main Image</label>
                                            <div class="" style="margin-left: 35%;">
                                                <input name="imgMain<?php echo $i; ?>" type="file" />
                                                <small class="gray">Upload Image of resolution 450 x 650,</small>
                                            </div>
                                        </div><hr/>
                                        
                                        
										<div class="form-group">
											<label class="col-sm-4 control-label">Product Image</label>
											<div class="col-sm-8">
												<!-- -0-------------- -->
												<?php
												$rsProImg = exec_query("SELECT recid, media_src FROM tbl_product_media tpm WHERE tpm.media_type = 'img' AND tpm.color_id = '$rowProColor->color_id' AND tpm.product_id = '$id' AND tpm.media_src != 'product.png' AND tpm.is_main = 0", $con);
												if(mysqli_num_rows($rsProImg) > 0){
												?>
												<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
													<div class="carousel-inner">
													<?php
													$j = 1;
													$ol = '';
													while($rowProImg = mysqli_fetch_object($rsProImg)){
														$active = ($j == 1)?'active':'';
														$ol .= '<li data-target="#carousel-example-generic" data-slide-to="'.$j.'" class="'.$active.'"></li>';
													?>
														<div class="item <?php echo $active; ?>">
															<img src="../site_image/product/<?php echo $rowProImg->media_src; ?>" alt="Images..." style="">
															<div class="carousel-caption">
																<p> <button onclick="deleteProImg(<?php echo $rowProImg->recid; ?>)" type="button" class="btn btn-danger btn-xs"> <span class="glyphicon glyphicon-trash"></span> Delete Image</button> </p>
															</div>
														</div>
														
													<?php $j++; } ?>
													</div>
													
													<ol class="carousel-indicators"><?php echo $ol; ?></ol>
													
													<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
														<span class="glyphicon glyphicon-chevron-left"></span>
													</a>
													<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
														<span class="glyphicon glyphicon-chevron-right"></span>
													</a>
												</div>
												<?php }else{ echo 'No Images Found!'; } ?>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-sm-4 control-label">Add Product Image</label>
											<div class="col-sm-8">    
												<input name="img<?php echo $i; ?>[]" type="file" multiple />
                                                <small class="gray">
                                                	Press Ctrl and select multiple images,<br/>
                                                    Upload Image of resolution 450 x 650,
                                                </small>
											</div>
										</div><hr/>
										
										<div class="form-group" style="margin:0 auto;">
											<div class="col-sm-12"><b>Product Video </b> 
											<?php
											$isVideoId = '';
											$productVidRs = exec_query("SELECT recid, media_src FROM tbl_product_media WHERE media_type = 'video' AND color_id = '$rowProColor->color_id' AND product_id = '$id'", $con);
											if(mysqli_num_rows($productVidRs)){ $productVidRow = mysqli_fetch_object($productVidRs);
												$mp4_vid = $productVidRow->media_src;
												?>
												<link href="../player/video-js.css" rel="stylesheet" type="text/css">
												<script src="../player/video.js"></script>
												<script>videojs.options.flash.swf = "../player/video-js.swf";</script>
												<div style="position: absolute; text-align: right; width: 94%; z-index: 214;">
													<button onclick="deleteProVideo(<?php echo $productVidRow->recid; ?>)" type="button" class="btn btn-danger btn-xs"> <span class="glyphicon glyphicon-trash"></span> Delete Video</button>
												</div>
												<video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" height="251" width="445" poster="http://video-js.zencoder.com/oceans-clip.png" data-setup="{}">
													<source src="../site_image/productvideo/<?php echo $mp4_vid; ?>" type='video/mp4' />
												</video>
												
											<?php $isVideoId = $productVidRow->recid; }else{ echo 'No video Found'; } ?>
											</div>
										</div><hr/>
										
										<div class="form-group">
											<label class="col-sm-4 control-label">Add Product Video</label>
											<div class="col-sm-8">
												<input name="video<?php echo $i; ?>[]" type="file" /> <small class="gray">(.mp4 format & maximum 36mb size)</small>
												<input type="hidden" name="isVideo[]" value="<?php echo $isVideoId; ?>" />
											</div>
										</div>
                                        <?php if($numCol > 1){ ?>
                                        <!-- delete color -->
										<hr/>
                                        <div class="form-group">
											<div class="col-lg-8 col-lg-offset-4">
												<button onclick="deleteProColor(<?php echo $rowProColor->color_id; ?>)" type="button" class="btn btn-danger"> <span class="glyphicon glyphicon-trash"></span> Delete Color</button>
											</div>
										</div>
                                        <?php } ?>
									</div>
								</div>
							</div>
							<input type="hidden" name="colorCode[]" value="<?php echo $rowProColor->color_code; ?>" />
							<input type="hidden" name="i[]" value="<?php echo $i; ?>" />
							<input type="hidden" name="color_id[]" value="<?php echo $rowProColor->color_id; ?>" />
							<?php $i++; } ?>
							<!-- loooop end -->
							<hr/>
							<div class="form-group">
								<input type="hidden" name="data1" value="<?php echo $id; ?>" />
								<input type="hidden" name="data2" value="<?php echo $_GET['data2']; ?>" />
								<input type="hidden" name="action" value="productEditDetail" />
								<div class="col-lg-9 col-lg-offset-3">
									<button class="btn btn-primary" type="submit"> Save Changes! </button>
								</div>
							</div>
						</div>
						</form>
                    </div><!-- detail details end -->
					
                </div>
            </div>
        </div><!-- wrapper -->
<?php include 'include/footer.php'; ?>
<?php include 'include/formJs.php'; ?>
<script type="text/javascript" src="assets/js/js.js"></script>
<script type="text/javascript" src="assets/js/nicEdit.js"></script>
<script>
function getCount(data){
	no = 70 - data;
	$('#countSpan').text(no+' Character Left');
}
// used in 1st tab
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
function getSubcategorySize(scid){
	$.get('adminAjax.php', {'action' : 'getSubcategoryEditSize', 'data1' : scid, 'mul' : 'mul', 'data2' : '<?php echo $pro->size; ?>', 'dataTempId' : 'v3.5qdv701a15a22ded1c311d0f217c.cloud.uk'}, function(data){ $('#sizeBlock').html(data); });
}
$('#ckeditor1, #ckeditor2').ckeditor();
// used in 3rd tab
function addMore(){
	co = document.getElementById('co').value;
	data = '<div class="col-sm-9"> <input class="form-control input-sm" name="text[]" placeholder="Your Content" type="text" style="width:50%;float:left;" /> <input type="file" name="img[]" /> </div>';
	$('#usageHere').append(data);
	//document.getElementById("usageHere").innerHTML += data;
	document.getElementById("co").value = ++co;
}
function deleteStepImg(id){
	if(confirm('Do you want to Delete this Image?')){
		$.get('delete.php', {'table' : 'tbl_product_usage', 'pk' : 'recid', 'id' : id}, function(data){ alert(data); location.reload();	});
	}
}
// used in 3rd tab
function deleteProImg(id){
	if(confirm('Do you want to Delete this Image?')){
		$.get('delete.php', {'table' : 'tbl_product_media', 'pk' : 'recid', 'id' : id}, function(data){	alert(data); location.reload();	});
	}
}
function deleteProVideo(id){
	if(confirm('Do you want to Delete this Video?')){
		$.get('delete.php', {'table' : 'tbl_product_media', 'pk' : 'recid', 'id' : id}, function(data){	alert(data); location.reload();	});
	}
}
function deleteProColor(id){
	if(confirm('Do you want to Delete this Color & its all details?')){
		$.get('delete.php', {'table' : 'tbl_product_color', 'pk' : 'color_id', 'id' : id, 'pid' : <?php echo $id; ?>}, function(data){ alert(data); location.reload(); });
	}
}
// call function
getSubcategorySize('<?php echo $pro->temp_subcategory; ?>');

/* chk upc and sku */
function chkProductFields(type){
	val = $('#p'+type).val();
	
	if(type == 'UPC'){  }
	else if(type == 'SKU'){  }
	
	$.get('adminAjax.php', {'action' : 'chkProductFields', 'data1' : val, 'data2' : type, 'type' : 'edit', 'pid' : <?php echo $id; ?>, 'dataTempId' :  'd3w701a255416561wde7d809dfga1.cloud'}, function(data){
		if(data){ put = 1; }
		else{
			put = 0;
			alert(type+' Already Exist, Try Another.');
		}
		$('#h'+type).val(put);
	});
}
function chkFormData(){
	sku = $('#hSKU').val();
	upc = $('#hUPC').val();
	if(sku == 1 && upc == 1){ return true; }
	else{ alert('Invalid SKU or UPC'); return false; }
}
</script>
<!-- color add popup start -->
<!-- Modal With Form -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Add New Color</h4>
			</div>
			<div class="modal-body">
				
					<label for="">Select Color</label>
					<div class="form-group" style="margin:0 auto; padding:7px;">
						<div  id="colorpallet">
							<?php $i = 1;
							//$slug = getBrand(array('slug'), $pro->brand_id, $con)->slug;
							$condi = ($colorNotGet != '')?"AND color NOT IN($colorNotGet)":'';
							$col_rs = exec_query("SELECT * FROM tbl_color WHERE brand = '$pro->brand_id' $condi ORDER BY color", $con);
							$ccount = mysqli_num_rows($col_rs);
							if($ccount > 0){
								while($col_row = mysqli_fetch_object($col_rs)){ ?>
									<input id="color<?php echo $i; ?>" type="checkbox" value="<?php echo $col_row->color_code; ?>" name="color[]">
									<label for="color<?php echo $i; ?>" style="background-color:<?php echo $col_row->color_code; ?>" class="tooltip-btn" title="" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo $col_row->color; ?>"><span class="fa fa-check">&nbsp;</span></label>
							<?php $i++; } } ?>
						</div>
					</div>
				
			</div>
			<div class="modal-footer">
				<button <?php if($ccount > 0){ echo 'type="submit"'; }else{ echo 'type="button"'; } ?> class="btn btn-primary">Add Now !</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input type="hidden" name="action" value="productColorAdd" />
				<input type="hidden" name="data1" value="<?php echo $id; ?>" />
			</div>
		</div>
		</form>
	</div>
</div>
<!-- color add popup end -->
</body>
</html>