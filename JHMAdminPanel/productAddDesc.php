<?php include 'include/header.php';
chkParam($_GET['data1'], 'productAdd.php'); ?>

<script>
	window.history.forward();
	function noBack(){ window.history.forward(); }
</script>
<div onload="noBack();" onpageshow="if (event.persisted) noBack();">  </div>

<?php $id = $_GET['data1'];

/* get product name */
$proRs = exec_query('SELECT product_name FROM tbl_product WHERE product_id = '.$id, $con);
$pro = mysql_fetch_object($proRs);

/* for add similar */
$sel = 'selected="selected"';
$taggArr = array();
$isAddSimilar = false;
if(isset($_GET['dataCopy']) && $_GET['dataCopy'] != ''){
	$isAddSimilar = true;
	$pro = getProduct($_GET['dataCopy'], $con);
	$taggArr = explode(',', $pro->tag);
}
?>
<style>
#cke_1_contents, #cke_2_contents{ height:420px !important; }
</style>
		<div class="warper container-fluid">
        	<div class="page-header"><h1>Product <small>Add Product Description & Usage</small></h1></div>
        	<div class="row">
            
            	<div class="col-md-12">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Add Product Description & Usage</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                                <div class="form-group">
									<label class="col-sm-2 control-label">Summary</label>
                                    <div class="col-sm-9">
										<textarea class="form-control" id="ckeditor1" name="summary" placeholder="Summary" cols="100" rows="10" ><?php if($isAddSimilar){ echo $pro->product_summary; } ?></textarea>
                                    </div>
                                </div>
                       			<hr/>
								<div class="form-group">
									<label class="col-sm-2 control-label">Usage</label>
                                    <div class="col-sm-9">
										<textarea class="form-control" id="ckeditor2" name="usage" placeholder="Description" cols="100" rows="10" ><?php if($isAddSimilar){ echo $pro->product_description; } ?></textarea>
                                    </div>
                                </div>
								<hr/>
								
                                <?php /* if($isAddSimilar){ ?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Usage Image</label>
                                    <div class="col-sm-5">
                                        <!-- -0-------------- -->
                                        <?php $ol = ''; $i = 0;
                                        $rsStepImg = getProductStepImg($_GET['dataCopy'], $con);
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
                                                <div class="item <?php echo $active; ?>">
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
                                <?php } ?>
                                
                                <div class="form-group">
									<label class="col-sm-3 control-label">Usage</label>
                                    <div class="col-sm-9">
										<input class="form-control input-sm" name="text[]" placeholder="Your Content" type="text" style="width:50%;float:left;" />
										<input type="file" name="img[]" />
                                    </div>
									<div id="usageHere" style="margin-left: 25%;width: 100%;"></div>
									<div style="clear:both;"></div>
									<div> <button type="button" onclick="addMore();">Add More!</button> </div>
									<input type="hidden" id="co" value="2" />
                                </div>
                                <hr/>*/ ?>
                                
                                <div class="form-group">
									<label class="col-sm-2 control-label">Tag (Optional)</label>
                                    <div class="col-sm-9">
                                		<select class="form-control chosen-select" name="tag[]" data-placeholder="SELECT TAG" multiple>
											<option value=""></option>
											<?php
											$tag_rs = exec_query("SELECT title FROM tbl_tag ORDER BY title", $con);
											while($tag_row = mysql_fetch_object($tag_rs)){ ?>
												<option <?php echo (in_array($tag_row->title, $taggArr))?$sel:''; ?>><?php echo $tag_row->title;?></option>
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
										<input class="form-control col-sm-2" name="height" placeholder="Enter Height" type="text" style="width:115px; margin-right:10px;" <?php if($isAddSimilar && isset($pro->height) && $pro->height != 0){ ?> value="<?php echo $pro->height; ?>" <?php } ?> />
                                        <input class="form-control col-sm-2" name="width" placeholder="Enter Width" type="text" style="width:115px; margin-right:10px;" <?php if($isAddSimilar && isset($pro->width) && $pro->width != 0){ ?> value="<?php echo $pro->width; ?>" <?php } ?> />
                                        <small>in CM (centimeter)</small>
                                    </div>
                                </div>
                                
                                <div class="form-group">
									<label class="col-sm-2 control-label">Weight</label>
                                    <?php $w = ''; $wUnit = '';
                                    if($isAddSimilar){
										if($pro->weight != ''){
											$weight = $pro->weight;
											$weightArr = explode(' ', $weight);
											$w = $weightArr[0];
											$wUnit = $weightArr[1];
										}
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
									<input type="hidden" name="action" value="productAddDesc" />
									<input type="hidden" value="<?php echo $id; ?>" name="data1" />
                                    
                                    <?php if($isAddSimilar){ ?> <input type="hidden" value="<?php echo $_GET['dataCopy']; ?>" name="dataCopy" /> <?php } ?>

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
<?php include 'include/formJs.php'; ?>
<script>
/*function addMore(){
	co = document.getElementById('co').value;
	data = '<div class="col-sm-9"> <input class="form-control input-sm" name="text[]" placeholder="Your Content" type="text" style="width:50%;float:left;" /> <input type="file" name="img[]" /> </div>';
	document.getElementById("usageHere").innerHTML += data;
	document.getElementById("co").value = ++co;
}*/

function addMore(){
	co = document.getElementById('co').value;
	data = '<div class="col-sm-9"> <input class="form-control input-sm" name="text[]" placeholder="Your Content" type="text" style="width:50%;float:left;" /> <input type="file" name="img[]" /> </div>';
	
	$('#usageHere').append(data);
	
	//document.getElementById("usageHere").innerHTML += data;
	document.getElementById("co").value = ++co;
}

$('#ckeditor1, #ckeditor2').ckeditor();
</script>
</body>
</html>