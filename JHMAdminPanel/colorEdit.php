<?php include 'include/header.php';
chkParam($_GET['data1'], 'home.php');
$colId = $_GET['data1'];
$id = $_GET['dataLast1'];
$slug = $_GET['dataLast2'];
$brand = getBrand(array('brand_name'), $id, $con);

$col_rs = exec_query("SELECT * FROM tbl_color WHERE color_id = '$colId'", $con);
$roww = mysql_fetch_object($col_rs);
?>
<style>.my{ margin-bottom:5px; width:200px; }</style>
<link rel="stylesheet" href="assets/css/farbtastic.css" type="text/css" />
        <div class="warper container-fluid">
            <div class="page-header"><h1>Color (<?php echo $brand->brand_name; ?>) <small> Edit Color</small></h1></div>
        	<div class="row">
				<div class="col-md-6">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Edit Color</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                            	
								<div class="form-group">
									<label class="col-sm-3 control-label">Select Color</label>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6" style="border-right:#e0e0e0 thin solid;">
										<div id="picker"></div>
                                    </div>
                                    <div class="col-sm-3">
                                    	<label class="col-sm-3 control-label my" style="text-align: left;">RGB</label>
										<input class="form-control my" name="r" id="r" placeholder="'R' Value" maxlength="3" />
                                        <input class="form-control my" name="g" id="g" placeholder="'G' Value" maxlength="3" />
                                        <input class="form-control my" name="b" id="b" placeholder="'B' Value" maxlength="3" />
                                        <button class="btn btn-primary" type="button" onclick="validateRGB();">Convert to Hex</button>
                                    </div>
                                </div>
                                
                                <div class="form-group">
									<label class="col-sm-3 control-label"></label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="colorCode" id="color" placeholder="Select Color" type="text" value="<?php echo $roww->color_code; ?>" />
                                    </div>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Color Name</label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="name" id="col" placeholder="Color Name" type="text" value="<?php echo $roww->color; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
									<label class="col-sm-3 control-label">Display Name</label>
                                    <div class="col-sm-6">
										<input class="form-control" required name="dname" placeholder="Display Name" type="text" value="<?php echo $roww->display_name; ?>" />
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
									<div class="col-lg-9 col-lg-offset-3">
										<input type="hidden" name="action" value="ColorEdit2" />
										<input type="hidden" name="data1" value="<?php echo $colId; ?>" />
										<input type="hidden" name="dataLast1" value="<?php echo $id; ?>" />
                                        <input type="hidden" name="dataLast2" value="<?php echo $slug; ?>" />
										<button class="btn btn-primary" type="submit"> Save Changes ! </button>
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
$(document).ready(function() {
	$('#picker').farbtastic('#color');
});
function validateRGB(){
	r = document.getElementById('r').value;
	g = document.getElementById('g').value;
	b = document.getElementById('b').value;
	if(r == '' || g == '' || b == '' || isNaN(r) || isNaN(g) || isNaN(b) || r > 255 || g > 255 || b > 255 ){
		alert('Invalid RGB Values!!!');
	}
	else{
		heX = rgbToHex(r,g,b);
		document.getElementById('color').value = '#'+heX;
		$.farbtastic('#picker').setColor('#'+heX);
	}
}
function rgbToHex(R,G,B) { return toHex(R)+toHex(G)+toHex(B); }
function toHex(n) {
	n = parseInt(n,10);
	if (isNaN(n)) return "00";
	n = Math.max(0,Math.min(n,255));
	return "0123456789ABCDEF".charAt((n-n%16)/16) + "0123456789ABCDEF".charAt(n%16);
}
</script>
<script type="text/javascript" src="assets/js/farbtastic.js"></script>
<script type="text/javascript" src="assets/js/js.js"></script>
</body>
</html>