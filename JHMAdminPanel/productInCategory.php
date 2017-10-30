<?php include 'include/header.php';
if(!isset($_GET['data1']) || $_GET['data1'] == ''){ redirect('admincategory.php'); }
$catId = $_GET['data1'];
?>
<style> #parentSel_chosen, #sub_category_chosen, #subsub_category_chosen{ width:400px !important; } </style>
<div class="warper container-fluid">
    
    <div class="page-header"><h1>Product <small>All Products</small></h1></div>
    	<form method="post" class="form-horizontal" action="admin_action_model.php" >
        <div class="panel panel-default">
            <div class="panel-heading" onclick="hideshow()">Filter Products</div>
            <div class="panel-body" id="filterProducts" style="display:none;">
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Main Category</label>
                        <div class="col-sm-5">
                            <select class="form-control chosen-select" name="mainCategory[]" id="parentSel" onchange="getSubCategory(this.id)" data-placeholder="SELECT MAIN CATEGORY" multiple>
                                <option value=""></option>
                                <?php
                                $cat_rs = exec_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0 ORDER BY category_name", $con);
                                while($cat_row = mysql_fetch_object($cat_rs)){ ?>
                                    <option <?php if(isset($_POST['filter'])){ if($_POST['mainCategory'] == $cat_row->category_id){ echo $sel; } } ?> value="<?php echo $cat_row->category_id;?>"><?php echo $cat_row->category_name;?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group" id="subcategoryhere">
                        <label class="col-sm-3 control-label">Sub Category</label>
                        <div class="col-sm-5">
                            <select class="form-control chosen-select" name="subcategory[]" id="sub_category" onchange="getSubSubCategory(this.id)" data-placeholder="SELECT SUB CATEGORY" multiple>
                                <option value=""></option>
                                <?php if(isset($_POST['subcategory']) && $_POST['subcategory'] != ''){
                                    $sCat = getCategory(array('category_name'), $_POST['subcategory'], $con); ?>
                                    <option <?php echo $sel; ?> value="<?php echo $_POST['subcategory']; ?>"><?php echo $sCat->category_name; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Sub Sub Category</label>
                        <div class="col-sm-5">
                            <select class="form-control chosen-select" name="subsubcategory[]" id="subsub_category" data-placeholder="SELECT SUB SUB CATEGORY" multiple>
                                <option value="0"></option>
                                <?php if(isset($_POST['subsubcategory']) && $_POST['subsubcategory'] != '' && $_POST['subsubcategory'] != 0){
                                    $ssCat = getCategory(array('category_name'), $_POST['subsubcategory'], $con); ?>
                                    <option <?php echo $sel; ?> value="<?php echo $_POST['subsubcategory']; ?>"><?php echo $ssCat->category_name; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                            <button class="btn btn-primary" type="submit" name="filter" > Move Now ! </button>
                        </div>
                    </div>
            </div>
        </div>
    
    
    <div class="panel panel-default">
        <div class="panel-heading">All Products</div>
        <div class="panel-body">
            <div style="margin-bottom:12px;">
            	<button type="button" class="btn btn-purple btn-xs" onclick="chkAll();">Check/Uncheck All Products</button>
                <button type="button" class="btn btn-info btn-xs" onclick="hideshow();">Move Now</button>
           	</div>
            
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="chkPro">
                <?php /*LEFT JOIN tbl_category ON tbl_category.category_id = tbl_product.subcategory_id*/
				$i = 1;
                $query = "SELECT tbl_product.*, tbl_brand.brand_name FROM tbl_product
                LEFT JOIN tbl_brand ON tbl_brand.brand_id = tbl_product.brand_id
                LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tbl_product.product_id
                WHERE tpcat.category_id = '$catId'
                GROUP BY tbl_product.product_id";
                $rs_pro = exec_query($query, $con);
                while($row_pro = mysql_fetch_object($rs_pro)){ $stat = $row_pro->is_activate;
                    /* for show cat, sub cat, sub sub cat */
                    /*$strr = '';
                    $catArr = array();
                    $cat_rs = exec_query("select category_id from tbl_product_category WHERE product_id = '$row_pro->product_id'", $con);
                    while($cat_row = mysql_fetch_object($cat_rs)){ $catArr[] = $cat_row->category_id; }
                    $sCatOp = ''; $ssCatOp = ''; $cat4Size = '';
                    if(!empty($catArr)){
                        foreach($catArr AS $cat){
                            $level = chkCategoryLevel($cat, $con);
                            if($level == 'cat'){
                                $mainccat = getCategory(array('category_name'), $cat, $con);
                                if(isset($mainccat->category_name)){
                                    $strr .= ($strr == '')?'':'<br/>'; $strr .= $mainccat->category_name;
                                }
                            }
                            elseif($level == 'subCat'){
                                $scat = getCategory(array('category_name', 'superparent_id'), $cat, $con);
                                $cat = getCategory(array('category_name'), $scat->superparent_id, $con);
                                $strr .= ($strr == '')?'':'<br/>'; $strr .= $cat->category_name.' <b>></b> '.$scat->category_name;
                            }
                            elseif($level == 'subSubCat'){
                                $sscat = getCategory(array('category_name', 'parent_id'), $cat, $con);
                                $scat = getCategory(array('category_name', 'category_id', 'superparent_id'), $sscat->parent_id, $con);
                                $cat = getCategory(array('category_name'), $scat->superparent_id, $con);
                                $strr .= ($strr == '')?'':'<br/>'; $strr .= $cat->category_name.' <b>></b> '.$scat->category_name.' <b>></b> '.$sscat->category_name;
                            }
                        }
                        /*if($strr == ''){ //if no category is theree continue; }
                    }*/
                ?>
                    <tr <?php if($i%2 == 0){ echo 'class="info"'; } ?> >
                        <td><?php //echo $i; ?> <input type="checkbox" id="chk" name="chk[]" value="<?php echo $row_pro->product_id; ?>" /> </td>
                        <td><?php echo $row_pro->product_name; ?></td>
                        <td>
                        	
                        </td>
                    </tr>
                <?php $i++; } ?>
                </tbody>
            </table>

        </div>
    </div>
    <input type="hidden" name="action" value="updateProductCategory" />
    <input type="hidden" name="oldCat" value="<?php echo $catId; ?>" />
    </form>
</div>
        <!-- Warper Ends Here (working area) -->
        
<?php include 'include/footer.php'; ?>
<script>
function chkAll(){
	var obj = '';
	var isChk;
	$('#chkPro input[type=checkbox]').each(function(){
		if(this.checked == true){ isChk = true; }
		else{ isChk = false; }
		return false;
	});
	$('#chkPro input[type=checkbox]').each(function() {
		if(this.value != ''){
			if (obj == ''){ obj = this.value; }
			else{ obj = obj+','+this.value; }
		}
		
		if(!isChk){ this.checked = true; }
		else{ this.checked = false; }
	});
}

/////////////////////////////////////////////////////////////////////////////////// for filtert
function getSubCategory(id){
	ids = getSelOpt(id);
	$.get('adminAjax.php', {'action' : 'getSubCategory', 'data1' : ids, 'mul' : 'mul', 'dataTempId' : '3fda701a15a12ded0c3e1d46d0f2158b.cloud'}, function(data){
		$('#sub_category').html(data);
		$("#sub_category").trigger("chosen:updated");
	});
}
function getSubSubCategory(id){
	ids = getSelOpt(id);
	$.get('adminAjax.php', {'action' : 'getSubSubCategory', 'data1' : ids, 'mul' : 'mul', 'dataTempId' : '3fda701a25541656728de7d809dfgc21.cloud'}, function(data){
		$('#subsub_category').html(data);
		$("#subsub_category").trigger("chosen:updated");
	});
}
function hideshow(){
	if($('#filterProducts').css('display') == 'block'){ $('#filterProducts').css('display', 'none'); }
	else{ $('#filterProducts').css('display', 'block'); }
}
</script>

<script type="text/javascript" src="assets/js/js.js"></script>
<?php include 'include/tableJs.php'; ?>
<script src="assets/js/plugins/underscore/underscore-min.js"></script>
<!-- Globalize -->
<script src="assets/js/globalize/globalize.min.js"></script>
<!-- TypeaHead -->
<script src="assets/js/plugins/typehead/typeahead.bundle.js"></script>
<script src="assets/js/plugins/typehead/typeahead.bundle-conf.js"></script>
<!-- InputMask -->
<script src="assets/js/plugins/inputmask/jquery.inputmask.bundle.js"></script>
<!-- TagsInput -->
<script src="assets/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<!-- Chosen -->
<script src="assets/js/plugins/bootstrap-chosen/chosen.jquery.js"></script>
<script src="assets/js/moment/moment.js"></script>
</body>
</html>