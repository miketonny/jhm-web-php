<?php include 'include/header.php';
$id = $_GET['data1'];
$coup = mysql_fetch_object(exec_query("SELECT * FROM tbl_promotion WHERE promo_id = '$id'", $con));
if($coup->promo_type != 'allPro'){
	$coupDetail = mysql_fetch_object(exec_query("SELECT * FROM tbl_promotion_detail WHERE promo_id = '$id'", $con));
	if($coup->promo_type == 'allCat'){ $str = 'Categories'; }
	elseif($coup->promo_type == 'allBrand'){ $str = 'Brands'; }
	elseif($coup->promo_type == 'parPro'){ $str = 'Products'; }
}
else{ $str = 'all Products'; }
$fdateArr = explode(' ', $coup->start_date);
$ftimeArr = explode(':', $fdateArr[1]);
$tdateArr = explode(' ', $coup->end_date);
$ttimeArr = explode(':', $tdateArr[1]);
$chk = 'checked="checked"';
$sel = 'selected="selected"';
?>
<style>
/*#allPro, #allCat, #allSub, #parPro,*/ .percentText, .amountText{ display:none; }
.percentText, .amountText{ width:16%; /*margin-top:4px;*/ float:left; margin-right:4px; }
#apppl .switch-button input:checked + label{ border-color:#0066FF; background:#0066FF; }
.low-widd, .chosen-container{ width:30% !important; }
#parentSel0_chosen, #brandSel_chosen{ width: 54% !important; }
#parentSel0_chosen, #parentSel1_chosen, #parentSel2_chosen, #brandSel_chosen, #sub_category0_chosen, #sub_category1_chosen, #sub_category2_chosen, #subsub_category0_chosen, #subsub_category1_chosen, #subsub_category2_chosen, #product2_chosen{ width: 54% !important; }
</style>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Promotion<small>Edit Promotion</small></h1></div>
        	<div class="row">
            	
				<!-- Apply Promotion on all products -->
            	<div class="col-md-12" id="allPro">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Apply Promotion on <?php echo $str; ?></div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
								<?php if($coup->promo_type == 'allCat'){ $catArr = explode(',', $coupDetail->ids);
									$scatOp = ''; $sscatOp = '';
									if(!empty($catArr)){
										foreach($catArr AS $cat){
											$level = chkCategoryLevel($cat, $con);
											if($level == 'subCat'){
												$cat = getCategory(array('category_name', 'category_id'), $cat, $con);
												$scatOp .= '<option '.$sel.' value="'.$cat->category_id.'">'.$cat->category_name.'</option>';
											}
											elseif($level == 'subSubCat'){
												$cat = getCategory(array('category_name', 'category_id'), $cat, $con);
												$sscatOp .= '<option '.$sel.' value="'.$cat->category_id.'">'.$cat->category_name.'</option>';
											}
										}
									}
								?>
								<div class="form-group">
									<label class="col-sm-3 control-label">Main Category</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" name="mainCategory[]" id="parentSel0" onchange="getSubCategory(0)" data-placeholder="SELECT MAIN CATEGORY" multiple>
											<option value=""></option>
											<?php
											$cat_rs = exec_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0 ORDER BY category_name", $con);
											while($cat_row = mysql_fetch_object($cat_rs)){ ?>
												<option <?php if(in_array($cat_row->category_id, $catArr)){ echo $sel; }?> value="<?php echo $cat_row->category_id;?>"><?php echo $cat_row->category_name;?></option>
											<?php }?>
										</select>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Sub Category</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" name="subcategory[]" id="sub_category0" onchange="getSubSubCategory(0)" multiple data-placeholder="SELECT SUB CATEGORY">
											<option value=""></option>
											<?php echo $scatOp; ?>
										</select>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Sub Sub Category</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" name="subsubcategory[]" id="subsub_category0" multiple data-placeholder="SELECT SUB SUB CATEGORY">
											<option value="0"></option>
											<?php echo $sscatOp; ?>
										</select>
                                    </div>
                                </div>
									
								<?php }elseif($coup->promo_type == 'allBrand'){ $brArr = explode(',', $coupDetail->ids);
								$catArr = (isset($coupDetail->category_id) && $coupDetail->category_id != '')? explode(',', $coupDetail->category_id):'';
								$scatOp = ''; $sscatOp = '';
								if(!empty($catArr)){
									foreach($catArr AS $cat){
										$level = chkCategoryLevel($cat, $con);
										if($level == 'subCat'){
											$cat = getCategory(array('category_name', 'category_id'), $cat, $con);
											$scatOp .= '<option '.$sel.' value="'.$cat->category_id.'">'.$cat->category_name.'</option>';
										}
										elseif($level == 'subSubCat'){
											$cat = getCategory(array('category_name', 'category_id'), $cat, $con);
											$sscatOp .= '<option '.$sel.' value="'.$cat->category_id.'">'.$cat->category_name.'</option>';
										}
									}
								}
								?>
								<div class="form-group">
									<label class="col-sm-3 control-label">Main Category</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" name="mainCategory[]" id="parentSel1" onchange="getSubCategory(1)" data-placeholder="SELECT MAIN CATEGORY" multiple>
											<option value=""></option>
											<?php
											$cat_rs = exec_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0 ORDER BY category_name", $con);
											while($cat_row = mysql_fetch_object($cat_rs)){ ?>
												<option <?php if(in_array($cat_row->category_id, $catArr)){ echo $sel; }?> value="<?php echo $cat_row->category_id;?>"><?php echo $cat_row->category_name;?></option>
											<?php }?>
										</select>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Sub Category</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" name="subcategory[]" id="sub_category1" onchange="getSubSubCategory(1)" multiple data-placeholder="SELECT SUB CATEGORY">
											<option value=""></option>
											<?php echo $scatOp; ?>
										</select>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Sub Sub Category</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" name="subsubcategory[]" id="subsub_category1" multiple data-placeholder="SELECT SUB SUB CATEGORY">
											<option value="0"></option>
											<?php echo $sscatOp; ?>
										</select>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Brand</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" required name="brand[]" id="brandSel" data-placeholder="SELECT BRAND" multiple>
											<option value=""></option>
											<?php $br_rs = exec_query("SELECT * FROM tbl_brand ORDER BY brand_name", $con);
											while($br_row = mysql_fetch_object($br_rs)){ ?>
												<option <?php if(in_array($br_row->brand_id, $brArr)){ echo $sel; } ?>  value="<?php echo $br_row->brand_id; ?>"><?php echo $br_row->brand_name; ?></option>
											<?php }?>
										</select>
                                    </div>
                                </div>
								
								<?php }elseif($coup->promo_type == 'parPro'){ $proArr = explode(',', $coupDetail->ids);
									$proOp = '';
									foreach($proArr AS $proId){
										$product = getProduct($proId, $con);
										$proOp .= '<option '.$sel.' value="'.$product->product_id.'">'.$product->product_name.'</option>';
									}
								?>
								<div class="form-group">
									<label class="col-sm-3 control-label">Main Category</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" name="mainCategory" id="parentSel2" onchange="getSubCategory(2)" data-placeholder="SELECT MAIN CATEGORY" multiple>
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
									<label class="col-sm-3 control-label">Sub Category</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" name="subcategory[]" id="sub_category2" onchange="getSubSubCategory(2)" multiple data-placeholder="SELECT SUB CATEGORY">
											<option value=""></option>
										</select>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Sub Sub Category</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" name="subsubcategory[]" id="subsub_category2" multiple onchange="getProductOnPromotion(this.id)" data-placeholder="SELECT SUB SUB CATEGORY">
											<option value="0"></option>
										</select>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Product</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" name="product[]" id="product2" multiple data-placeholder="SELECT PRODUCT">
											<option value="0"></option>
											<?php echo $proOp; ?>
										</select>
                                    </div>
                                </div>
								<?php } ?>
								<hr/>
								<div class="form-group" style="display:none;">
									<label class="col-sm-3 control-label">Coupon Code</label>
									<div class="col-sm-6">
										<input class="form-control" required name="ccode" placeholder="Coupon Code" type="text" value="<?php echo $coup->promo_code; ?>" />
									</div>
								</div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Title</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" required name="title" placeholder="Title" type="text" value="<?php echo $coup->title;?>" onblur="getSluggPromotion(this.value);"/>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Slug</label>
									<div class="col-sm-6">
										<input class="form-control slugg" required name="slug" placeholder="Slug" type="text"
                                        value="<?php echo $coup->slug; ?>" class="slugg" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Promotion in </label>
									<div class="col-sm-9">
										<div class="btn-group">
											<button class="btn btn-warning percentBtn" type="button" onclick="changeTypeField('percent')"><b>%</b></button>
											<button class="btn btn-warning amountBtn" type="button" onclick="changeTypeField('amount')"><span class="fa fa-dollar"></span></button>
										</div> &nbsp; &nbsp; 
										<?php if($coup->percent_or_amount == 'percent'){ ?>
										<input class="form-control percentText" name="percentText" placeholder="Enter Percent" type="text" value="<?php echo $coup->promo_value; ?>" />
										<?php }else{ ?>
										<input class="form-control percentText" name="percentText" placeholder="Enter Percent" type="text" />
										<?php }if($coup->percent_or_amount == 'amount'){ ?>
										<input class="form-control amountText" name="amountText" placeholder="Enter Amount" type="text" value="<?php echo $coup->promo_value; ?>" />
										<?php }else{ ?>
										<input class="form-control amountText" name="amountText" placeholder="Enter Amount" type="text" />
										<?php } ?>
										<input type="hidden" name="amType" class="promoValType" <?php echo $coup->percent_or_amount; ?> />
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">From </label>
									<div class="col-sm-9">
										<input type="text" class="form-control col-sm-3 low-widd" data-date-format="YYYY-MM-DD" id="fdate" name="fdate" placeholder="From Date" required value="<?php echo $fdateArr[0]; ?>" />
										<input type="text" class="form-control col-sm-3 low-widd" data-date-format="HH:mm" id="ftime" name="ftime" placeholder="From Time" required value="<?php echo $ftimeArr[0].':'.$ftimeArr[1]; ?>" />
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">To </label>
									<div class="col-sm-9">
										<input type="text" class="form-control col-sm-3 low-widd" data-date-format="YYYY-MM-DD" id="tdate" name="tdate" placeholder="To Date" required value="<?php echo $tdateArr[0]; ?>" />
										<input type="text" class="form-control col-sm-3 low-widd" data-date-format="HH:mm" id="ttime" name="ttime" placeholder="To Time" required value="<?php echo $ttimeArr[0].':'.$ttimeArr[1]; ?>" />
									</div>
								</div>
								<hr/>		
								<div class="form-group">
									<label class="col-sm-3 control-label">Make it Featured?</label>
									<div class="col-sm-9">
										<div class="switch-button showcase-switch-button control-label sm">
											<input id="switch-buwa" name="featured" value="1" type="checkbox" onchange="showHide();" <?php getChecked($coup->is_featured, 1); ?>>
											<label for="switch-buwa"></label>  <small>(Optional)</small>
										</div>
									</div>
								</div>
								
								<div class="imgCon" <?php if($coup->is_featured == 0){ echo 'style="display:none;"'; } ?>>
									<div class="form-group">
										<label class="col-sm-3 control-label">Background Image</label>
										<div class="col-sm-9">
											<input name="bgImg" type="file" >
											<?php if($coup->bg_img != ''){ ?><img src="../site_image/promotion/<?php echo $coup->bg_img; ?>" /><?php } ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Product Image</label>
										<div class="col-sm-9">
											<input name="proImg" type="file" > <small>(Only .png format!)</small> <br/>
											<?php if($coup->product_img != ''){ ?><img src="../site_image/promotion/<?php echo $coup->product_img; ?>" /><?php } ?>
										</div>
									</div>
								</div>
								<hr/>
								<div class="form-group">
									<div class="col-lg-9 col-lg-offset-3">
										<input type="hidden" name="action" value="promotionEdit" />
										<input type="hidden" name="data1" value="<?php echo $id; ?>" />
										<input type="hidden" name="data" value="<?php echo $coup->promo_type; ?>" />
										<button class="btn btn-primary" type="submit"> Save Changes ! </button>
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
<script type="text/javascript" src="assets/js/js.js"></script>
<script>
function changeBlocks(rad){ $('#allPro, #allCat, #allSub, #parPro').css('display', 'none');	$('#'+rad).css('display', 'block'); }
function changeTypeField(val){
	$('.percentText, .amountText').css('display', 'none');
	$('.'+val+'Text').css('display', 'block');
	$('.percentBtn, .amountBtn').removeClass('active');
	$('.'+val+'Btn').addClass('active');
	$('.promoValType').val(val);
}
function getSubCategory(id){
	ids = getSelOpt('parentSel'+id);
	$.get('adminAjax.php', {'action' : 'getSubCategory', 'data1' : ids, 'mul' : 'mul', 'dataTempId' : '3fda701a15a12ded0c3e1d46d0f2158b.cloud'}, function(data){
		$('#sub_category'+id).html(data);
		$('#sub_category'+id).trigger("chosen:updated");
	});
	
	if(document.getElementById('subsub_category2')){ getProductOnPromotion('subsub_category2'); }
}
function getSubSubCategory(id){
	ids = getSelOpt('sub_category'+id);
	$.get('adminAjax.php', {'action' : 'getSubSubCategory', 'data1' : ids, 'mul' : 'mul', 'dataTempId' : '3fda701a25541656728de7d809dfgc21.cloud'}, function(data){
		$('#subsub_category'+id).html(data);
		$('#subsub_category'+id).trigger("chosen:updated");
	});
	if(id == 2){ getProductOnPromotion('sub_category'+id); }
}
function getProductOnPromotion(id){
	if(getSelOpt(id) !== undefined){
		ids = getSelOpt(id);
	}
	else if(getSelOpt('sub_category2') !== undefined){
		ids = getSelOpt('sub_category2');
	}
	else{
		ids = getSelOpt('parentSel2');
	}
	
	if(ids !== undefined){
		$.get('adminAjax.php', {'action' : 'getProductOnPromotion', 'data1' : ids, 'mul' : 'mul', 'dataTempId' : '1qdc701a15a22ddd0cj11d5d0f2357j.icloud'}, function(data){
			$('#product2').html(data);
			$('#product2').trigger("chosen:updated");
		});
	}
}
$('#fdate, #tdate').datetimepicker({pickTime: false});
$('#ftime, #ttime').datetimepicker({pickDate: false});
changeTypeField('<?php echo $coup->percent_or_amount; ?>');
// for featured
function showHide(){
	if($('.imgCon').css('display') == 'none'){ $('.imgCon').css('display', 'block'); }
	else{ $('.imgCon').css('display', 'none'); }
}
function getSluggPromotion(text){
	no = Math.floor(Math.random() * (9 - 0 + 1)) + 0;
	text = text.replace(/[^A-Z0-9]+/ig, "_");
	$('.slugg').val(text);
}
</script>
</body>
</html>