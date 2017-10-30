<?php include 'include/header.php';
$form = '<div class="form-group" style="display:none;">
	<label class="col-sm-3 control-label">Coupon Code</label>
	<div class="col-sm-6">
		<input class="form-control" required name="ccode" placeholder="Coupon Code" type="text" value="0" />
	</div>
</div>
<div class="form-group">
	<label class="col-sm-3 control-label">Title</label>
	<div class="col-sm-6">
		<input class="form-control" required name="title" placeholder="Title" type="text" onblur="getSluggPromotion(this.value)" />
	</div>
</div>
<div class="form-group">
	<label class="col-sm-3 control-label">Slug</label>
	<div class="col-sm-6">
		<input class="form-control slugg" required name="slug" placeholder="Slug" type="text" />
	</div>
</div>
<div class="form-group">
	<label class="col-sm-3 control-label">Promotion in </label>
	<div class="col-sm-9">
		<input class="form-control percentText" name="percentText" placeholder="Enter Percent" type="text" />
		<input class="form-control amountText" name="amountText" placeholder="Enter Amount" type="text" />
		<div class="btn-group">
			<button class="btn btn-warning percentBtn" type="button" onclick="changeTypeField('."'percent'".');"><b>%</b></button>
			<button class="btn btn-warning amountBtn" type="button" onclick="changeTypeField('."'amount'".');"><span class="fa fa-dollar"></span></button>
		</div> &nbsp; &nbsp; 
		<input type="hidden" name="amType" class="promoValType" /> <div style="clear:both;"></div>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-3 control-label">From </label>
	<div class="col-sm-9">
		<input type="text" class="form-control col-sm-3 low-widd" data-date-format="YYYY-MM-DD" id="fdate" name="fdate" placeholder="From Date" required />
		<input type="text" class="form-control col-sm-3 low-widd" data-date-format="HH:mm" id="ftime" name="ftime" placeholder="From Time" required value="'.date('H:m').'" />
	</div>
</div>
<div class="form-group">
	<label class="col-sm-3 control-label">To </label>
	<div class="col-sm-9">
		<input type="text" class="form-control col-sm-3 low-widd" data-date-format="YYYY-MM-DD" id="tdate" name="tdate" placeholder="To Date" required />
		<input type="text" class="form-control col-sm-3 low-widd" data-date-format="HH:mm" id="ttime" name="ttime" placeholder="To Time" required value="'.date('H:m').'" />
	</div>
</div>';
$formImg = '
<div class="imgCon" style="display:none;">
<div class="form-group">
	<label class="col-sm-3 control-label">Background Image</label>
	<div class="col-sm-9">
		<input name="bgImg" type="file" >
	</div>
</div>
<div class="form-group">
	<label class="col-sm-3 control-label">Product Image</label>
	<div class="col-sm-9">
		<input name="proImg" type="file" > <small>(Only .png format!)</small>
	</div>
</div>
</div>';
?>
<style>
#allPro, #allCat, #allBrand, #parPro, .percentText, .amountText{ display:none; }
.percentText, .amountText{ width:16%; /*margin-top:4px;*/ float:left; margin-right:4px; }
#apppl .switch-button input:checked + label{ border-color:#0066FF; background:#0066FF; }
.low-widd{ width:30% !important; }
#parentSel0_chosen, #parentSel1_chosen, #parentSel2_chosen, #brandSel_chosen, #sub_category0_chosen, #sub_category1_chosen, #sub_category2_chosen, #subsub_category0_chosen, #subsub_category1_chosen, #subsub_category2_chosen, #product2_chosen{ width: 54% !important; }
</style>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Promotion<small>Add New Promotion</small></h1></div>
        	<div class="row">
            	
				<div class="col-md-12" id="apppl">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Apply Promotion On</div>
                        <div class="panel-body">
							<div class="form-group">
								<div class="col-sm-12">
									<div class="switch-button showcase-switch-button sm">
										<input value="allPro" id="switch-button-6i" name="stock" type="radio" onclick="changeBlocks(this.value)" >
										<label for="switch-button-6i"></label> All Products
									</div> &nbsp; &nbsp;
									<div class="switch-button showcase-switch-button sm">
										<input value="allCat" id="switch-button-7i" name="stock" type="radio" onclick="changeBlocks(this.value)" >
										<label for="switch-button-7i"></label> Categories
									</div> &nbsp; &nbsp;
									<div class="switch-button showcase-switch-button sm">
										<input value="allBrand" id="switch-button-8i" name="stock" type="radio" onclick="changeBlocks(this.value)" >
										<label for="switch-button-8i"></label> Brand
									</div>
									<div class="switch-button showcase-switch-button sm">
										<input value="parPro" id="switch-button-9i" name="stock" type="radio" onclick="changeBlocks(this.value)" >
										<label for="switch-button-9i"></label> Products
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Apply Promotion on all products -->
            	<div class="col-md-12" id="allPro">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Apply Promotion on all Products</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
								<div class="form-group">
									<label class="col-sm-3 control-label">Add Promo Code for All Product?</label>
									<div class="col-sm-9">
										<div class="switch-button showcase-switch-button control-label sm">
											<input id="switch-b118" type="checkbox" onchange="window.location='promoCodeAdd.php';">
											<label for="switch-b118"></label>
										</div>
									</div>
								</div>
								<hr/>
								<?php echo $form;?>
								<hr/>
								<div class="form-group">
									<label class="col-sm-3 control-label">Make it Featured?</label>
									<div class="col-sm-9">
										<div class="switch-button showcase-switch-button control-label sm">
											<input id="switch-buttza" name="featured" value="1" type="checkbox" onchange="showHide();">
											<label for="switch-buttza"></label>  <small>(Optional)</small>
										</div>
									</div>
								</div>
								<?php echo $formImg; ?>
								<div class="form-group">
									<div class="col-lg-9 col-lg-offset-3">
										<input type="hidden" name="action" value="promotionAdd" />
										<input type="hidden" name="data" value="allPro" />
										<button class="btn btn-primary" type="submit"> Add Now ! </button>
										<button class="btn btn-info" type="submit" name="nsubmit"> Add & New ! </button>
									</div>
								</div>
                        	</form>
                        </div>
                    </div>
                </div>
				
				<!-- Apply Promotion on all main cat -->
            	<div class="col-md-12" id="allCat">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Apply Promotion on Categories</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                            	<div class="form-group">
									<label class="col-sm-3 control-label">Main Category</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" required name="mainCategory[]" id="parentSel0" onchange="getSubCategory(0)" data-placeholder="SELECT MAIN CATEGORY" multiple>
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
										<select class="form-control chosen-select" name="subcategory[]" id="sub_category0" onchange="getSubSubCategory(0)" multiple data-placeholder="SELECT SUB CATEGORY">
											<option value=""></option>
										</select>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Sub Sub Category</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" name="subsubcategory[]" id="subsub_category0" multiple data-placeholder="SELECT SUB SUB CATEGORY">
											<option value="0"></option>
										</select>
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
									<label class="col-sm-3 control-label">Add Promo Code for this?</label>
									<div class="col-sm-9">
										<div class="switch-button showcase-switch-button control-label sm">
											<input id="switch-b118a" type="checkbox" onchange="removeData('allCat', 1);">
											<label for="switch-b118a"></label>
										</div>
									</div>
								</div>
								<hr/>
								<div class="removeMe1"><!-- remove it -->
									<?php echo $form;?>
									<hr/>
									<div class="form-group">
										<label class="col-sm-3 control-label">Make it Featured?</label>
										<div class="col-sm-9">
											<div class="switch-button showcase-switch-button control-label sm">
												<input id="switch-buttonza" name="featured" value="1" type="checkbox" onchange="showHide();">
												<label for="switch-buttonza"></label>  <small>(Optional)</small>
											</div>
										</div>
									</div>
									<?php echo $formImg; ?>
									<hr/>
									<div class="form-group">
										<div class="col-lg-9 col-lg-offset-3">
											<input type="hidden" name="action" value="promotionAdd" />
											<input type="hidden" name="data" value="allCat" />
											<button class="btn btn-primary" type="submit"> Add Now ! </button>
											<button class="btn btn-info" type="submit" name="nsubmit"> Add & New ! </button>
										</div>
									</div>
								</div>
                        	</form>
                        </div>
                    </div>
                </div>
				<!-- Apply Promotion on brands -->
            	<div class="col-md-12" id="allBrand">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Apply Promotion on Brands</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                            	<div class="form-group">
									<label class="col-sm-3 control-label">Main Category</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" name="mainCategory[]" id="parentSel1" onchange="getSubCategory(1)" data-placeholder="SELECT MAIN CATEGORY" multiple>
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
										<select class="form-control chosen-select" name="subcategory[]" id="sub_category1" onchange="getSubSubCategory(1)" multiple data-placeholder="SELECT SUB CATEGORY">
											<option value=""></option>
										</select>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Sub Sub Category</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" name="subsubcategory[]" id="subsub_category1" multiple data-placeholder="SELECT SUB SUB CATEGORY">
											<option value="0"></option>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Brand</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" required name="brand[]" id="brandSel" data-placeholder="SELECT BRAND" multiple>
											<option value=""></option>
											<?php $br_rs = exec_query("SELECT * FROM tbl_brand ORDER BY brand_name", $con);
											while($br_row = mysql_fetch_object($br_rs)){
												echo '<option value="'.$br_row->brand_id.'">'.$br_row->brand_name.'</option>';
											}?>
										</select>
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
									<label class="col-sm-3 control-label">Add Promo Code for this?</label>
									<div class="col-sm-9">
										<div class="switch-button showcase-switch-button control-label sm">
											<input id="switch-b117" type="checkbox" onchange="removeData('allBrand', 2);">
											<label for="switch-b117"></label>
										</div>
									</div>
								</div>
								<hr/>
								<div class="removeMe2"><!-- remove it -->
									<?php echo $form;?>
									<hr/>
									<div class="form-group">
										<label class="col-sm-3 control-label">Make it Featured?</label>
										<div class="col-sm-9">
											<div class="switch-button showcase-switch-button control-label sm">
												<input id="switch-buwa" name="featured" value="1" type="checkbox" onchange="showHide();">
												<label for="switch-buwa"></label>  <small>(Optional)</small>
											</div>
										</div>
									</div>
									<?php echo $formImg; ?>
									<hr/>
									<div class="form-group">
										<div class="col-lg-9 col-lg-offset-3">
											<input type="hidden" name="action" value="promotionAdd" />
											<input type="hidden" name="data" value="allBrand" />
											<button class="btn btn-primary" type="submit"> Add Now ! </button>
											<button class="btn btn-info" type="submit" name="nsubmit"> Add & New ! </button>
										</div>
									</div>
								</div>
                        	</form>
                        </div>
                    </div>
                </div>
				<!-- Apply Promotion on particular products -->
            	<div class="col-md-12" id="parPro">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Apply Promotion on Products</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                            	<div class="form-group">
									<label class="col-sm-3 control-label">Main Category</label>
                                    <div class="col-sm-9">
										<select class="form-control chosen-select" required name="mainCategory" id="parentSel2" onchange="getSubCategory(2)" data-placeholder="SELECT MAIN CATEGORY" multiple>
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
										</select>
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
									<label class="col-sm-3 control-label">Add Promo Code for this?</label>
									<div class="col-sm-9">
										<div class="switch-button showcase-switch-button control-label sm">
											<input id="switch-b116" type="checkbox" onchange="removeData('parPro', 3);">
											<label for="switch-b116"></label>
										</div>
									</div>
								</div>
								<hr/>
								<div class="removeMe3"><!-- remove it -->
									<?php echo $form;?>
									<hr/>		
									<div class="form-group">
										<label class="col-sm-3 control-label">Make it Featured?</label>
										<div class="col-sm-9">
											<div class="switch-button showcase-switch-button control-label sm">
												<input id="switch-button-qa" name="featured" value="1" type="checkbox" onchange="showHide();">
												<label for="switch-button-qa"></label>  <small>(Optional)</small>
											</div>
										</div>
									</div>
									<?php echo $formImg; ?>
									<hr/>
									<div class="form-group">
										<div class="col-lg-9 col-lg-offset-3">
											<input type="hidden" name="action" value="promotionAdd" />
											<input type="hidden" name="data" value="parPro" />
											<button class="btn btn-primary" type="submit"> Add Now ! </button>
											<button class="btn btn-info" type="submit" name="nsubmit"> Add & New ! </button>
										</div>
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
function changeBlocks(rad){ $('#allPro, #allCat, #allBrand, #parPro').css('display', 'none');	$('#'+rad).css('display', 'block'); }
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
	getProductOnPromotion('subsub_category2');
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
changeTypeField('percent');

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
function removeData(hiddenData, no){
	//$('.removeMe'+no).empty().html(
	$('.removeMe'+no).fadeOut().empty().html(' <div class="form-group"> <div class="col-lg-9 col-lg-offset-3"> <input type="hidden" name="action" value="promoCodeExtra" /> <input type="hidden" name="data" value="'+hiddenData+'" /> <button class="btn btn-primary" type="submit"> Click here for Next Step >> </button> </div> </div> ').fadeIn();
}
</script>
</body>
</html>