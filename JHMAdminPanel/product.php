<?php
include 'include/header.php';
$sel = 'selected="selected"';
/*'product_upc' => 'UPC #',  */
$array = array('category_name' => 'Category', 'brand_name' => 'Brand', 'product_sku' => 'SKU #', 'qty' => 'Inventory', 'created_on' => 'Created', 'modified_on' => 'Last Modified', 'is_activate' => 'Status');
$prefData = mysqli_fetch_object(mysqli_query($con,"SELECT columns FROM tbl_preference WHERE type = 'tbl_product'"));
$pref = '';
if(isset($prefData->columns)){
	$pref = $prefData->columns;
}?>
<style>
#parentSel_chosen, #sub_category_chosen, #subsub_category_chosen, #parentSel1_chosen, #sub_category1_chosen, #subsub_category1_chosen{ width:400px !important; }
</style>
<div class="warper container-fluid">
    
    <div class="page-header"><h1>Product <small>All Products</small></h1></div>
    <button type="button" class="btn btn-purple btn-flat btn-lg" style="margin: 0px 0px 8px;" data-toggle="modal" data-target="#modal-form">Edit Preference!</button>
    
        <div class="panel panel-default">
            <div class="panel-heading" onclick="hideshow()">Filter Products</div>
            <div class="panel-body" id="filterProducts" <?php if(!isset($_REQUEST['filter'])){ ?> style="display:none;" <?php } ?>>
                <form method="get" class="form-horizontal" action="product.php" id="filterForm">
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Keyword</label>
                        <div class="col-sm-5">
                            <input class="form-control" name="pname" placeholder="Enter Keyword" type="text" value="<?php if(isset($_REQUEST['pname']) && $_REQUEST['pname'] != ''){ echo $_REQUEST['pname']; }?>" />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Main Category</label>
                        <div class="col-sm-5">
                            <select class="form-control chosen-select" name="mainCategory" id="parentSel" onchange="getSubCategory(this.id)" data-placeholder="SELECT MAIN CATEGORY">
                                <option value=""></option>
                                <?php $isMainCat = false; $isSubCat = false;
                                $cat_rs = exec_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0 ORDER BY category_name", $con);
                                while($cat_row = mysqli_fetch_object($cat_rs)){ ?>
                                    <option <?php if(isset($_REQUEST['filter'])){ if($_REQUEST['mainCategory'] == $cat_row->category_id){ echo $sel; $isMainCat = true; } } ?> value="<?php echo $cat_row->category_id;?>"><?php echo $cat_row->category_name;?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group" id="subcategoryhere">
                        <label class="col-sm-3 control-label">Sub Category</label>
                        <div class="col-sm-5">
                            <select class="form-control chosen-select" name="subcategory" id="sub_category" onchange="getSubSubCategory(this.id)" data-placeholder="SELECT SUB CATEGORY">
                                <option value=""></option>
                                <?php if(isset($_REQUEST['subcategory']) && $_REQUEST['subcategory'] != ''){ $isSubCat = true;
                                    $sCat = getCategory(array('category_name'), $_REQUEST['subcategory'], $con); ?>
                                    <option <?php echo $sel; ?> value="<?php echo $_REQUEST['subcategory']; ?>"><?php echo $sCat->category_name; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Sub Sub Category</label>
                        <div class="col-sm-5">
                            <select class="form-control chosen-select" name="subsubcategory" id="subsub_category" data-placeholder="SELECT SUB SUB CATEGORY">
                                <option value="0"></option>
                                <?php if(isset($_REQUEST['subsubcategory']) && $_REQUEST['subsubcategory'] != '' && $_REQUEST['subsubcategory'] != 0){
                                    $ssCat = getCategory(array('category_name'), $_REQUEST['subsubcategory'], $con); ?>
                                    <option <?php echo $sel; ?> value="<?php echo $_REQUEST['subsubcategory']; ?>"><?php echo $ssCat->category_name; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Select Brand</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="brand" id="brandSel" >
                                <option value="">- SELECT BRAND -</option>
                                <?php $br_rs = exec_query("SELECT * FROM tbl_brand ORDER BY brand_name", $con);
                                while($br_row = mysqli_fetch_object($br_rs)){ ?>
                                    <option <?php if(isset($_REQUEST['brand']) && $_REQUEST['brand'] != ''){ getSelected($_REQUEST['brand'], $br_row->brand_id); } ?> value="<?php echo $br_row->brand_id;?>"><?php echo $br_row->brand_name; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <div class="switch-button showcase-switch-button xs">
                                <input value="1" id="switch-button6f" name="status" type="radio" <?php if(isset($_REQUEST['status'])){ getChecked(1, $_REQUEST['status']); } ?>>
                                <label for="switch-button6f"></label> Publish
                            </div> &nbsp; &nbsp;
                            <div class="switch-button showcase-switch-button xs">
                                <input value="0" id="switch-button7f" name="status" type="radio" <?php if(isset($_REQUEST['status'])){ getChecked(0, $_REQUEST['status']); } ?>>
                                <label for="switch-button7f"></label> Unpublish
                            </div> &nbsp; &nbsp;
                            <div class="switch-button showcase-switch-button xs">
                                <input value="2" id="switch-button8f" name="status" type="radio" <?php if(isset($_REQUEST['status'])){ getChecked(2, $_REQUEST['status']); } ?>>
                                <label for="switch-button8f"></label> Save in Draft
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Records Order By </label>
                        <div class="col-sm-9">
                            <div class="switch-button showcase-switch-button xs">
                                <input value="1" id="switchutton6f" name="ordReg" type="checkbox" <?php if(isset($_REQUEST['ordReg'])){ getChecked(1, $_REQUEST['ordReg']); } ?>>
                                <label for="switchutton6f"></label> Register Date
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                            <button class="btn btn-primary" type="submit" name="filter1111" id="filterBtn" value="filter">Filter Now</button>
                            <input type="hidden" name="filter" value="filter" />
                            <input type="hidden" name="orderCol" id="orderCol" />
                            <input type="hidden" name="orderType" id="orderType" />
                        </div>
                    </div>
                    <div id="appendHere"></div>
                </form>
            </div>
        </div>
    
    
    <div class="panel panel-default">
        <div class="panel-heading">All Products</div>
        <div class="panel-body">
            <?php if($productPerm['status']){ ?>
            <span><button type="button" class="btn btn-purple btn-xs" onclick="chkAll();">Check/Uncheck All Products</button></span> |
            <span><button type="button" class="btn btn-purple btn-xs" data-toggle="modal" data-target="#modal-catt">Assign Categories</button></span> |
            <button type="button" class="btn btn-purple btn-xs" onclick="multiPublish('publish');">Publish</button>
            <button type="button" class="btn btn-purple btn-xs" onclick="multiPublish('unpublish');">Unpublish</button>
            <button type="button" class="btn btn-purple btn-xs" onclick="multiPromote('promote');">Promote</button>
            <button type="button" class="btn btn-purple btn-xs" onclick="multiPromote('unpromote');">Unpromote</button>
            <button type="button" class="btn btn-purple btn-xs" onclick="multiPublish('delete');">Delete</button>
            <?php
                /**
                * @turturkeykey - added export button
                */
            ?>
            <button type="button" class="btn btn-purple btn-xs" onclick="exportSelected('export');">Export CSV</button>
            <?php
                /**
                * @turturkeykey - added upload button
                */
            ?>
            <button type="button" class="btn btn-purple btn-xs" data-toggle="modal" data-target="#modal-upload" data-keyboard="false" data-backdrop="static">Bulk Update Stocks</button>
            <small>Select Product and Press Publish</small>
            
            <br/>
            
            <div style="margin-top:8px;">
            	<input type="text" placeholder="Enter Page No." id="pageNoText" value="<?php echo (isset($_GET['page']) && $_GET['page'] != '')?$_GET['page']:''; ?>" />
            	<button type="button" class="btn btn-purple btn-xs" onclick="goPage(pageNoText.value);">Go</button>
            </div>
			<?php } ?>
            
            <!-- form for assign more cats, whole trable+popup inside -->
            <form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
            
            <!-- assign popup start -->
            <div class="modal fade" id="modal-catt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="myModalLabel">Assign New Categories</h4>
                        </div>
                        <div class="modal-body">
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Main Category</label>
                                <div class="col-sm-5">
                                    <select class="form-control chosen-select" name="mainCategory[]" id="parentSel1" onchange="getSubCategory(this.id)" data-placeholder="SELECT MAIN CATEGORY" multiple >
                                        <option value=""></option>
                                        <?php $cat_rs = exec_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0 ORDER BY category_name", $con);
                                        while($cat_row = mysqli_fetch_object($cat_rs)){ ?>
                                            <option value="<?php echo $cat_row->category_id;?>"><?php echo $cat_row->category_name;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group" id="subcategoryhere">
                                <label class="col-sm-3 control-label">Sub Category</label>
                                <div class="col-sm-5">
                                    <select class="form-control chosen-select" name="subcategory[]" id="sub_category1" onchange="getSubSubCategory(this.id)" data-placeholder="SELECT SUB CATEGORY" multiple>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Sub Sub Category</label>
                                <div class="col-sm-5">
                                    <select class="form-control chosen-select" name="subsubcategory[]" id="subsub_category1" data-placeholder="SELECT SUB SUB CATEGORY" multiple>
                                        <option value="0"></option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Assign Now !</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="hidden" name="action" value="assignMoreCategories" />
                            <input type="hidden" name="data1" value="tbl_product" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- assign popup end -->
            
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="1toggleColumn-datatable" style="margin-top: 13px;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product <?php
						if(isset($_REQUEST['orderCol']) && $_REQUEST['orderCol'] == 'product_name'){
                        	if($_REQUEST['orderType'] == 'DESC'){ ?>
                                <button type="button" onclick="_REQUEST('product_name', 'ASC')" class="btn btn-info btn-xs"><span class="fa fa-arrow-circle-down"></span></button>
                            <?php }else{ ?>
                            	<button type="button" onclick="setProOrder('product_name', 'DESC')" class="btn btn-info btn-xs"><span class="fa fa-arrow-circle-up"></span></button>
                        <?php } }else{ ?>
                        	<button type="button" onclick="setProOrder('product_name', 'ASC')" class="btn btn-info btn-xs"><span class="fa fa-arrow-circle-up"></span></button>
                        <?php } ?>
                        </th>
                        <?php $cols = array();
                        if($pref != ''){
                            $cols = explode(',', $pref);
                        }
                        foreach($cols AS $val){	echo '<th>'.$array[$val].'<br />';
                        	
							if($val != 'category_name' && $val != 'is_activate'){
								if(isset($_REQUEST['orderCol']) && $_REQUEST['orderCol'] == $val){
									if($_REQUEST['orderType'] == 'DESC'){ ?>
										<button type="button" onclick="setProOrder('<?php echo $val; ?>', 'ASC')" class="btn btn-info btn-xs"><span class="fa fa-arrow-circle-down"></span></button>
									<?php }else{ ?>
										<button type="button" onclick="setProOrder('<?php echo $val; ?>', 'DESC')" class="btn btn-info btn-xs"><span class="fa fa-arrow-circle-up"></span></button>
							<?php } }else{ ?>
                            	<button type="button" onclick="setProOrder('<?php echo $val; ?>', 'ASC')" class="btn btn-info btn-xs"><span class="fa fa-arrow-circle-up"></span></button>
							<?php } } ?>
                        </th>
                        <?php } ?>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="chkPro">
                <?php
                if(isset($_GET['page'])){ $i = (75 * $_GET['page'] - 75) + 1; }
                else{ $i = 1; }
                $condi = ''; $filterVar = ''; $isFilter = false;
				$order = 'ORDER BY tbl_product.modified_on DESC';
                if(isset($_REQUEST['filter'])){
                    $isFilter = true;
                    
					
					/*if(isset($_POST['subsubcategory']) && $_POST['subsubcategory'] != '' && $_POST['subsubcategory'] != 0){
						$categoryId = $_POST['subsubcategory'];
					}
                    if(isset($_POST['subcategory']) && $_POST['subcategory'] != ''){
						$categoryId = $_POST['subcategory'];
						//$filterVar .= ($filterVar != '')?','.$_POST['subcategory']:$_POST['subcategory'];
					}
					if(isset($_POST['mainCategory']) && $_POST['mainCategory'] != ''){
						$categoryId = $_POST['mainCategory'];
						//$filterVar .= ($filterVar != '')?','.$_POST['mainCategory']:$_POST['mainCategory'];
					}*/
					
					$catArray = array();
					if(isset($_REQUEST['mainCategory']) && $_REQUEST['mainCategory'] != ''){
						
						if(isset($_REQUEST['subsubcategory']) && $_REQUEST['subsubcategory'] != '' && $_REQUEST['subsubcategory'] != 0){
							$subsubcategory = $_REQUEST['subsubcategory'];
							$catArray[] = $subsubcategory;
						}
						elseif(isset($_REQUEST['subcategory']) && $_REQUEST['subcategory'] != ''){
							$subcategory = $_REQUEST['subcategory'];
							$catArray[] = $subcategory;
						}
						else{
							$categoryId = $_REQUEST['mainCategory'];
							$catRs = exec_query("SELECT category_id FROM `tbl_category` WHERE parent_id = $categoryId OR superparent_id = $categoryId OR category_id = $categoryId", $con);
							while($catRow = mysqli_fetch_object($catRs)){ $catArray[] = $catRow->category_id; }
						}
						
					}
					
					if(isset($catArray) && !empty($catArray)){
						$categories = implode(',', $catArray);
						$condi .= ($categories != '') ? ' AND tpcat.category_id IN ('.$categories.')' : '';
					}
					
                    if(isset($_REQUEST['pname']) && $_REQUEST['pname'] != ''){
						$condi .= " AND (tbl_product.product_name LIKE '%".$_REQUEST['pname']."%' OR tbl_product.product_sku LIKE '%".$_REQUEST['pname']."%' OR tpp.product_upc LIKE '%".$_REQUEST['pname']."%')";
					}
					if(isset($_REQUEST['brand']) && $_REQUEST['brand'] != ''){ $condi .= " AND tbl_product.brand_id = '".$_REQUEST['brand']."'"; }
                    if(isset($_REQUEST['status']) && $_REQUEST['status'] != ''){ $condi .= " AND tbl_product.is_activate = '".$_REQUEST['status']."'"; }
					if(isset($_REQUEST['ordReg']) && $_REQUEST['ordReg'] != ''){ $order = 'ORDER BY tbl_product.created_on DESC'; }
                }
                include '../include/ps_pagination.php';
                /*LEFT JOIN tbl_category ON tbl_category.category_id = tbl_product.subcategory_id*/
    //             $query = "SELECT tbl_product.*, tbl_brand.brand_name, tpp.color_id, tpp.product_upc AS upc1 FROM tbl_product
    //             LEFT JOIN tbl_brand ON tbl_brand.brand_id = tbl_product.brand_id
                
				// LEFT JOIN tbl_product_media tpm ON tpm.product_id = tbl_product.product_id
				// LEFT JOIN tbl_product_price tpp ON tpp.product_id = tpm.product_id
				
				// LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tbl_product.product_id
    //             WHERE tbl_product.product_id != '' AND tbl_product.is_activate != 4 $condi
    //             GROUP BY tbl_product.product_id ";

                 $query = "SELECT tbl_product.*, tbl_brand.brand_name, tpp.color_id, tpp.product_upc AS upc1 FROM tbl_product
                LEFT JOIN tbl_brand ON tbl_brand.brand_id = tbl_product.brand_id
                INNER JOIN tbl_product_price tpp ON tpp.product_id = tbl_product.product_id
                INNER JOIN tbl_product_category tpcat ON tpcat.product_id = tbl_product.product_id         
                WHERE tbl_product.product_id != '' AND tbl_product.is_activate != 4 $condi
                Group by tbl_product.product_id";
				
				if(isset($_REQUEST['orderType']) && $_REQUEST['orderType'] != '' && isset($_REQUEST['orderCol']) && $_REQUEST['orderCol'] != ''){
					$order = 'ORDER BY '.$_REQUEST['orderCol'].' '.$_REQUEST['orderType'];
				}
                $query = $query.' '.$order;
                if(!$isFilter){
					// fetch no of pro
					$pagePro = 15;
					$noPro = mysqli_fetch_object(exec_query("SELECT no FROM tbl_config WHERE type = 'noProductPerPage'", $con));
					if(isset($noPro->no) && $noPro->no != ''){ $pagePro = $noPro->no; }
                    $pager = new PS_Pagination($con, $query, $pagePro, 10);
                    $pager->setDebug(true);
                    $rs_pro = $pager->paginate();
                }
                else{ $rs_pro = exec_query($query, $con); }
                
                if($rs_pro){

                while($row_pro = mysqli_fetch_object($rs_pro)){ $stat = $row_pro->is_activate;
					/* for show cat, sub cat, sub sub cat */
                    $strr = '';
                    $catArr = array();
                    $cat_rs = exec_query("select category_id from tbl_product_category WHERE product_id = '$row_pro->product_id'", $con);
                    while($cat_row = mysqli_fetch_object($cat_rs)){ $catArr[] = $cat_row->category_id; }
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
                                if (isset($scat)) {
                                    $cat = getCategory(array('category_name'), $scat->superparent_id, $con);
                                    $strr .= ($strr == '')?'':'<br/>'; $strr .= $cat->category_name.' <b>></b> '.$scat->category_name.' <b>></b> '.$sscat->category_name;
                                }

                            }
                        }
                        /*if($strr == ''){ //if no category is theree continue; }*/
                    }
                ?>
                    <tr <?php if($i%2 == 0){ echo 'class="info"'; } ?> >
                        <td><?php echo $i; ?> <input type="checkbox" id="chk" name="chk[]" value="<?php echo $row_pro->product_id; ?>" /> </td>
                        <td><?php echo $row_pro->brand_name.' '.$row_pro->product_name; ?></td>
                        
                        <?php
                        foreach($cols AS $val){ echo '<td>';
                            if($val == 'is_activate'){
                                if($stat == 0){ echo 'Unpublish'; }elseif($stat == 1){ echo 'Publish'; }elseif($stat == 2){ echo 'In Draft'; }elseif($stat == 3){ echo 'Inactive'; }
                            }elseif($val == 'modified_on' || $val == 'created_on'){
                                if($row_pro->$val != '0000-00-00 00:00:00'){ echo date('d M, Y', strtotime($row_pro->$val)); }
                                else{ echo "Didn't Modified"; }
                            }elseif($val == 'category_name'){
                                echo '<p style="font-size:12px;">'.$strr.'</p>';
                            }elseif($val == 'product_upc'){
								echo $row_pro->upc1;
							}else if($val == 'qty'){ 
								$inventories=mysqli_query($con, "SELECT product_price as price, product_upc as upc, qty,  color_code FROM `tbl_product_price` price left join tbl_product_color  color on price.color_id = color.color_id where color.product_id = '".$row_pro->product_id."'");
								while($inventory = mysqli_fetch_object($inventories)){
									if(($inventory->qty)==0){
										$fontcolor="#FF0000;";
									}else if(($inventory->qty)<=3){
										$fontcolor="#FAC100;";
									}else{
										$fontcolor="#333333;";
									}
									echo '<p style="width:175px; font-size:13px;"><span style="background:'.$inventory->color_code.'; display:inline-block; float:left; margin-right:5px; width:20px; height:20px;"></span>| Stock: <b style="color:'.$fontcolor.'">'.$inventory->qty."</b> | Price: $".$inventory->price." | UPC : ".$inventory->upc."</p>";
								}
							}else{
                                echo $row_pro->$val;
                            }
                        echo '</td>'; } ?>
                        
                        <td>
                        	<a href="<?php echo siteUrl; ?>detail/<?php echo $row_pro->product_id; ?>HXYK<?php echo $row_pro->slug; ?>TZS1YL<?php echo $row_pro->color_id; ?>" target="_blank" class="btn btn-info btn-xs">Preview</a>
                        	<?php if($productPerm['add']){ ?>
                        	<button type="button" onClick="window.location='productAddSimilar.php?data1=<?php echo $row_pro->product_id; ?>&data2=<?php echo $row_pro->slug; ?>'" class="btn btn-purple btn-xs">Add as Similar</button>
                            <?php } ?>
                            
                            <?php if($productPerm['edit']){ ?>
                            <button type="button" onClick="window.open('<?php echo siteUrl; ?>JHMAdminPanel/productEdit.php?data1=<?php echo $row_pro->product_id; ?>&data2=<?php echo $row_pro->slug; ?>')" class="btn btn-info btn-xs">View</button>
                            <?php } ?>
                            
							<?php if($productPerm['status']){
							if($stat == 0){ ?>
                            <button type="button" onClick="publish(<?php echo $row_pro->product_id; ?>);" class="btn btn-primary btn-xs">Publish</button>
                            <?php }elseif($stat == 1){ ?>
                            <button type="button" onClick="unpublish(<?php echo $row_pro->product_id; ?>);" class="btn btn-primary btn-xs">Unpublish</button>
                            <?php }elseif($stat == 2 || $stat == 3){ ?>
                            <button type="button" onClick="publish(<?php echo $row_pro->product_id; ?>);" class="btn btn-primary btn-xs">Publish</button>
                            <?php } ?>
                            <button type="button" class="btn btn-danger btn-xs" onClick="delete_pro(<?php echo $row_pro->product_id; ?>, 'qqq');">Inactive</button>
                            <?php } ?>
                            <button type="button" class="btn btn-danger btn-xs" onClick="delete_pro(<?php echo $row_pro->product_id; ?>, 'delete');">Delete</button>
                            <?php if ($row_pro->promotion_state == 0) {?>
                                <button type="button" class="btn btn-danger btn-xs" onClick="promote(<?php echo $row_pro->product_id; ?>);">Promote</button>
                            <?php } else {?>
                                <button type="button" class="btn btn-danger btn-xs" onClick="unpromote(<?php echo $row_pro->product_id; ?>);">UnPromote</button>
                            <?php }?>
                        </td>
                    </tr>
                <?php
                    $i++;
                } } ?>
                </tbody>
            </table>
            </form>
            <div style="text-align:center;"><?php if(!$isFilter){ echo $pager->renderFullNav(); }?></div>

        </div>
    </div>
    
</div>
        <!-- Warper Ends Here (working area) -->
        
<?php include 'include/footer.php'; ?>
<script>
function goPage(no){
	if(no != '' && no != 0){
		window.location = 'product.php?page='+no+'&';
	}
}
function setProOrder(col, order){
	$('#orderCol').val(col);
	$('#orderType').val(order);
	//$('#appendHere').html('<input type="hidden" name=""');
	document.getElementById('filterForm').submit();
}
function delete_pro(id, type){
	if(type == 'delete'){
		if(confirm('Do you want to Delete this Product?')){
			//$.get('delete.php', {'table' : 'tbl_product', 'pk' : 'product_id', 'id' : id}, function(data){ alert(data); location.reload(); });
			$.get('change_status.php', {'table' : 'tbl_product', 'pk_column' : 'product_id', 'pk_val' : id, 'up_column' : 'is_activate', 'up_val' : 4}, function(data){ alert('Product Successfully Deleted !'); location.reload(); });
		}
	}
	else{
		if(confirm('Do you want to Inactive this Product?')){
			$.get('change_status.php', {'table' : 'tbl_product', 'pk_column' : 'product_id', 'pk_val' : id, 'up_column' : 'is_activate', 'up_val' : 3}, function(data){ alert(data); location.reload(); });
		}
	}
}
function promote(id){
    if(confirm('Do you want to Promote this Product?')){
        $.get('change_status.php', {'table' : 'tbl_product', 'pk_column' : 'product_id', 'pk_val' : id, 'up_column' : 'promotion_state', 'up_val' : 1}, function(data){
            alert("Promotion is succeeded."); location.reload();
        });
    }
}
function unpromote(id){
    if(confirm('Do you want to cancel the promotion of this Product?')){
        $.get('change_status.php', {'table' : 'tbl_product', 'pk_column' : 'product_id', 'pk_val' : id, 'up_column' : 'promotion_state', 'up_val' : 0}, function(data){
            alert("Promotion is canceled."); location.reload();
        });
    }
}
function unpublish(id){
	if(confirm('Do you want to Unpublish this Product?')){
		$.get('change_status.php', {'table' : 'tbl_product', 'pk_column' : 'product_id', 'pk_val' : id, 'up_column' : 'is_activate', 'up_val' : 0}, function(data){
			alert(data); location.reload();
		});
	}
}
function publish(id){
	if(confirm('Do you want to Publish this Product?')){
		$.get('change_status.php', {'table' : 'tbl_product', 'pk_column' : 'product_id', 'pk_val' : id, 'up_column' : 'is_activate', 'up_val' : 1}, function(data){
			alert(data); location.reload();
		});
	}
}
function multiPublish(type){
	var obj = '';
	$('#chkPro input[type=checkbox]:checked').each(function() {
		if(this.value != ''){
			if (obj == ''){ obj = this.value; }
			else{ obj = obj+','+this.value; }
		}
	});
	if(obj != ''){
		if(type == 'unpublish'){
			if(confirm('Do you want to Unpublish these Products?')){
				$.get('change_status.php', {'table' : 'tbl_product', 'pk_column' : 'product_id', 'pk_val' : obj, 'up_column' : 'is_activate', 'up_val' : 0, 'multi' : true}, function(data){ alert(data); location.reload(); });
			}
		}
		else if(type == 'publish'){
			if(confirm('Do you want to Publish these Products?')){
				$.get('change_status.php', {'table' : 'tbl_product', 'pk_column' : 'product_id', 'pk_val' : obj, 'up_column' : 'is_activate', 'up_val' : 1, 'multi' : true}, function(data){ alert(data); location.reload(); });
			}
		}
        else if(type == 'delete') {
            if(confirm('Do you want to Delete these Products?')){
				$.get('change_status.php', {'table' : 'tbl_product', 'pk_column' : 'product_id', 'pk_val' : obj, 'up_column' : 'is_activate', 'up_val' : 4, 'multi' : true}, function(data){ alert(data); location.reload(); });
			}
        }
	}
	else{ alert('Please select at least one product.'); }
}

function multiPromote(type){
    var obj = '';
    $('#chkPro input[type=checkbox]:checked').each(function() {
        if(this.value != ''){
            if (obj == ''){ obj = this.value; }
            else{ obj = obj+','+this.value; }
        }
    });
    if(obj != ''){
        if(type == 'unpromote'){
            if(confirm('Do you want to cancel the promotion of selected Products?')){
                $.get('change_status.php', {'table' : 'tbl_product', 'pk_column' : 'product_id', 'pk_val' : obj, 'up_column' : 'promotion_state', 'up_val' : 0, 'multi' : true}, function(data){ alert(data); location.reload(); });
            }
        }
        else if(type == 'promote'){
            if(confirm('Do you want to promote selected Products?')){
                $.get('change_status.php', {'table' : 'tbl_product', 'pk_column' : 'product_id', 'pk_val' : obj, 'up_column' : 'promotion_state', 'up_val' : 1, 'multi' : true}, function(data){ alert(data); location.reload(); });
            }
        }
    }
    else{ alert('Please Select at least one Product!'); }
}

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
	var ids = getSelOpt(id);
	$.get('adminAjax.php', {'action' : 'getSubCategory', 'data1' : ids, 'mul' : 'mul', 'dataTempId' : '3fda701a15a12ded0c3e1d46d0f2158b.cloud'}, function(data){
		$('#sub_category1').html(data);
		$('#sub_category').html('<option value="">SELECT SUBCATEGORY</option>'+data);
		<?php if(isset($_REQUEST['subcategory']) && $_REQUEST['subcategory'] != ''){ ?>
			$('#sub_category option[value="<?php echo $_REQUEST['subcategory']; ?>"]').attr("selected","selected");
		<?php } ?>
		
		$("#sub_category, #sub_category1").trigger("chosen:updated");
	});
}
function getSubSubCategory(id){
	var ids = getSelOpt(id);
	$.get('adminAjax.php', {'action' : 'getSubSubCategory', 'data1' : ids, 'mul' : 'mul', 'dataTempId' : '3fda701a25541656728de7d809dfgc21.cloud'}, function(data){
		$('#subsub_category').html(data);
		$('#subsub_category').html('<option value="">SELECT SUB SUB CATEGORY</option>'+data);
		<?php if(isset($_REQUEST['subsubcategory']) && $_REQUEST['subsubcategory'] != ''){ ?>
			$('#subsub_category option[value="<?php echo $_REQUEST['subsubcategory']; ?>"]').attr("selected","selected");
		<?php } ?>
		
		$("#subsub_category, #subsub_category1").trigger("chosen:updated");
	});
}
function hideshow(){
	if($('#filterProducts').css('display') == 'block'){ $('#filterProducts').css('display', 'none'); }
	else{ $('#filterProducts').css('display', 'block'); }
}
<?php
/**
 * @turturkeykey - added export function
 */
?>
function exportSelected() {
    var obj = '';
    $('#chkPro input[type=checkbox]:checked').each(function() {
        if(this.value !== ''){
            if (obj === ''){ obj = this.value; }
            else{ obj = obj+','+this.value; }
        }
    });
    if(obj === '') {
        alert('Please select at least one product.');
        return;
    }
    if(confirm('Proceed with export (will open a new window)?')){
        window.open('./export.php?ids=' + obj);
    }
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
<script>
<?php if($isMainCat){ ?>getSubCategory('parentSel'); <?php } ?>
<?php if($isSubCat){ ?>getSubSubCategory('sub_category'); <?php } ?>
</script>
<!-- preference popup start -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form method="post" class="form-horizontal" action="admin_action_model.php" >
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Edit Preference</h4>
			</div>
			<div class="modal-body">
				<label for="">Select Columns to Show</label>
				<div class="form-group" style="margin:0 auto; padding:7px;">
					<div class="col-lg-offset-3	col-lg-9">
					<?php $ch = 1;
					foreach($array AS $key => $val){ $chk = '';
						if (strpos($pref, $key) !== false){ $chk = 'checked="checked"'; }
					?>
					<div>
						<div class="switch-button showcase-switch-button control-label sm primary">
							<input id="switch-button-<?php echo $ch; ?>" name="prefer[]" value="<?php echo $key; ?>" type="checkbox" <?php echo $chk; ?> >
							<label for="switch-button-<?php echo $ch; ?>"></label> <?php echo $val; ?>
						</div> &nbsp; &nbsp;
					</div>
					<?php $ch++; } ?><br/>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Save Changes !</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input type="hidden" name="action" value="productPreferenceEdit" />
				<input type="hidden" name="data1" value="tbl_product" />
			</div>
		</div>
		</form>
	</div>
</div>
<!-- preference popup end -->
<!-- upload popup start -->
<form action="./productUpdateStock.php" method="POST" enctype="multipart/form-data">
<div class="modal fade" id="modal-upload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Upload CSV file</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">File: </label>
                    <div class="col-sm-5">
                        <input type="file" name="csv">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Upload now!</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</form>
<!-- upload popup end -->
</body>
</html>