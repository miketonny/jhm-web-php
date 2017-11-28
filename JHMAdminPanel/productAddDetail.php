<?php include 'include/header.php';
chkParam($_GET['data1'], 'productAdd.php');
chkParam($_SESSION['color'], 'productAdd.php');
$pid = $_GET['data1'];
$colorArr = $_SESSION['color'];

$isAddSimilar = false;
if(isset($_GET['dataCopy']) && $_GET['dataCopy'] != ''){
	$isAddSimilar = true;
	$idCopy = $_GET['dataCopy'];
}
?>
	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data" onsubmit="">
        <div class="warper container-fluid">
            <div class="page-header"><h1>Product <small>Add Product Detail</small></h1></div>
        	<div class="row">
            
			<?php $i = 1;
			foreach($colorArr AS $key => $value){ ?>
			
            	<div class="col-md-6">
                 	<div class="panel panel-default">
	 
                        <div class="panel-heading" style=" background:<?php echo $value; ?>; ">Add Product Detail (<?php echo $color = getColor($value, $con)->color; ?>)</div>
                        <div class="panel-body">
							
                            <?php /* add similar operation */
							$copyPrice = '';
							$copyRrp = '';
							$copyColorId = '';
							if($isAddSimilar){
								$copyProq = "SELECT tpc.color_id, tpp.product_price, tpp.product_rrp FROM tbl_product_color tpc
								LEFT JOIN tbl_color ON tbl_color.color_code = tpc.color_id
								LEFT JOIN tbl_product_price tpp ON tpp.color_id = tpc.color_id
								WHERE tpc.product_id = '$idCopy' AND tpc.color_code = '$value'";
								$rsCopyProColor = exec_query($copyProq, $con);
								if(mysqli_num_rows($rsCopyProColor)){
									$copyProColor = mysqli_fetch_object($rsCopyProColor);
									$copyPrice = $copyProColor->product_price;
									$copyRrp = $copyProColor->product_rrp;
									$copyColorId = $copyProColor->color_id;
								}
							}
							?>
                            
							<div class="form-group">
								<label class="col-sm-4 control-label">Price</label>
								<div class="col-sm-4">
									<input type="text" class="form-control inputmask" data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" name="price[]" placeholder="Product Price for <?php echo $color; ?> Color" value="<?php echo $copyPrice; ?>">
								</div>
							</div>
										
							<div class="form-group">
								<label class="col-sm-4 control-label">Stock</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" required name="stock[]" placeholder="Available Stock <?php echo $color; ?> Color" value="">
								</div>
							</div>		

							<div class="form-group">
								<label class="col-sm-4 control-label">Cost</label>
								<div class="col-sm-4">
									<input type="text" class="form-control inputmask" data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" required  name="cost[]" placeholder="Cost" value="">
								</div>
							</div>
														
							
							<div class="form-group">
								<label class="col-sm-4 control-label">Produt RRP</label>
								<div class="col-sm-4">
									<input type="text" class="form-control inputmask" data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" required name="rrp[]" placeholder="Product RRP for <?php echo $color; ?> Color" value="<?php echo $copyRrp; ?>">
								</div>
							</div>
                            
                            <div class="form-group">
								<label class="col-sm-4 control-label">Product UPC #</label>
								<div class="col-sm-4">
									<input class="form-control" name="upc[]" placeholder="Product UPC #" type="text" required="required" onblur="chkUpc(this.id)" id="a<?php echo rand(100000, 99999999999); ?>" />
								</div>
							</div><hr/>
                            
                            <?php /*
                            if($isAddSimilar){
								$rsProImg = exec_query("SELECT recid, media_src FROM tbl_product_media tpm WHERE tpm.media_type = 'img' AND tpm.color_id = '".$copyColorId."' AND tpm.product_id = '$idCopy'", $con);
                                if(mysql_num_rows($rsProImg) > 0){
							?>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Product Image</label>
                                <div class="col-sm-8">
                                    
                                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner">
                                        <?php
                                        $j = 1;
                                        $ol = '';
                                        while($rowProImg = mysql_fetch_object($rsProImg)){
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
                                    
                                </div>
                            </div><hr/>
                            <?php } }*/ ?>
                            
							<div class="form-group">
								<label class="col-sm-4 control-label">Product Main Image</label>
								<div class="col-sm-8">
									<input name="imgMain<?php echo $i; ?>" type="file"  />
                                    <small class="gray">
                                        Upload Image of resolution 450 x 650,
                                    </small>
								</div>
							</div>
                            
                            <div class="form-group">
								<label class="col-sm-4 control-label">Product Image</label>
								<div class="col-sm-8">
									<input name="img<?php echo $i; ?>[]" type="file" multiple />
                                    <small class="gray">
                                        Press Ctrl and select multiple images,<br/>
                                        Upload Image of resolution 450 x 650,
                                    </small>
								</div>
							</div>
							
                            <?php /*
                            if($isAddSimilar){
								$isVideoId = '';
                                $productVidRs = exec_query("SELECT recid, media_src FROM tbl_product_media WHERE media_type = 'video' AND color_id = '".$copyColorId."' AND product_id = '$idCopy'", $con);
								if(mysql_num_rows($productVidRs)){
							?>
                            <div class="form-group" style="margin:0 auto;">
                                <div class="col-sm-12"><b>Product Video </b> 
                                <?php
                                	$productVidRow = mysql_fetch_object($productVidRs);
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
                                    
                                <?php $isVideoId = $productVidRow->recid; ?>
                                </div>
                            </div><hr/>
                            <?php } }*/ ?>
                            
							<div class="form-group">
								<label class="col-sm-4 control-label">Product Video</label>
								<div class="col-sm-8">
									<input name="video<?php echo $i; ?>[]" type="file" /> <small class="gray">(.mp4 format & maximum 36mb size)</small>
								</div>
							</div>
						
						</div>
					</div>
				</div>
				<!-- for add similar related -->
                <input type="hidden" name="tempColorId[]" value="<?php echo $copyColorId; ?>" />
                
                <input type="hidden" name="colorCode[]" value="<?php echo $value; ?>" />
				<input type="hidden" name="i[]" value="<?php echo $i; ?>" />
				<?php echo (($i % 2) == 0)?'':''; $i++; } ?>
				
				<hr/>
				<div class="form-group">
					<input type="hidden" name="data1" value="<?php echo $pid; ?>" />
					<input type="hidden" name="action" value="productAddDetail" />
                    <?php if($isAddSimilar){ ?> <input type="hidden" name="dataCopy" value="<?php echo $idCopy; ?>" /> <?php } ?>
					<div class="col-lg-9 col-lg-offset-3">
						<button class="btn btn-primary" type="submit" name="psubmit" id="btn111"> Save & Publish! </button>
						<button class="btn btn-info" type="submit" name="dsubmit" id="btn222"> Save Only ! </button>
						<button class="btn btn-info" type="submit" name="nsubmit" id="btn333"> Add & New ! </button>
					</div>
				</div>
				
            </div>
        </div>
        <!-- Wrapper Ends Here (working area) -->
	</form>
<?php include 'include/footer.php'; ?>
<script type="text/javascript" src="assets/js/js.js"></script>
<?php include 'include/formJs.php'; ?>
<script>
function chkUpc(id){
	upc1 = $('#'+id).val();
	if(upc1 != ''){
		$.get('adminAjax.php', {'upc1' : upc1, 'action' : 'chkUPC'}, function(data){
			if(data == 1){
				$('#'+id).css('border', 'thin solid #ff0000');
				$('#'+id).focus();
				alert('UPC already Exist, Try Another!');
				$('#btn111, #btn222, #btn333').attr('disabled', 'disabled');
			}
			else if(data == 0){
				$('#'+id).css('border', '1px solid #ccc');
				$('#btn111, #btn222, #btn333').removeAttr('disabled');
			}
		});
	}
	else{
		alert('Invalid UPC!');
	}
}
</script>
</body>
</html>