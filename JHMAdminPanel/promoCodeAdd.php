<?php include 'include/header.php';
$promoId = (isset($_GET['data1']) && $_GET['data1'] != '')?$_GET['data1']:'allPro';
?>
<style>
.low-widd{ width:30% !important; }
.percentText, .amountText{ width:16%; /*margin-top:4px;*/ float:left; margin-right:4px; }
</style>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Promotion <small> Add New Promo Code</small></h1></div>
        	<div class="row">
            	
				<div class="col-md-12" id="allPro">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Add New Promo Code</div>
                        <div class="panel-body">
                        	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Promo Title</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" required name="title" placeholder="Promo Title" type="text" />
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Minimum Cart Value</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" required name="minVal" placeholder="Minimum Cart Value" type="number" min="1" />
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Promotion in</label>
									<div class="col-sm-9">
										<input class="form-control percentText" name="percentText" placeholder="Enter Percent" type="text" />
										<input class="form-control amountText" name="amountText" placeholder="Enter Amount" type="text" />
										<div class="btn-group">
											<button class="btn btn-warning percentBtn" type="button" onclick="changeTypeField('percent');"><b>%</b></button>
											<button class="btn btn-warning amountBtn" type="button" onclick="changeTypeField('amount');"><span class="fa fa-dollar"></span></button>
										</div> &nbsp; &nbsp; 
										<input type="hidden" name="amType" class="promoValType" /> <div style="clear:both;"></div>
									</div>
								</div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Promo Code</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" required name="pcode" placeholder="Promo Code" type="text" style="font-family:Verdana, Arial, Helvetica, sans-serif;" />
										<small style="color:#CC0033;">(Blank Space & Special Characters are not allowed)</small>
                                    </div>
                                </div>
                                <?php /*<div class="form-group">
                                    <label class="col-sm-3 control-label">Promotion</label>
                                    <div class="col-sm-6">
                                        <select class="form-control chosen-select" required name="promo" onchange="getDateTime(this.value);">
                                            <option value="">- SELECT PROMOTION -</option>
                                            <?php $pro_rs = exec_query("SELECT title, promo_id, promo_value, percent_or_amount FROM tbl_promotion ORDER BY promo_id DESC", $con);
                                            while($pro_row = mysql_fetch_object($pro_rs)){ ?>
                                               	<option value="<?php echo $pro_row->promo_id;?>"><?php echo $pro_row->title.' ('.$pro_row->promo_value; echo ($pro_row->percent_or_amount == 'amount')?' $)':' %)'; ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>*/ ?>
                                <div class="form-group">
									<label class="col-sm-3 control-label">Banner Image</label>
									<div class="col-sm-9">
										<input name="img" type="file" />
										<small>(Optional)</small>
									</div>
								</div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">From </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control col-sm-3 low-widd" data-date-format="YYYY-MM-DD" id="fdate" name="fdate" placeholder="From Date" required />
                                        <input type="text" class="form-control col-sm-3 low-widd" data-date-format="HH:mm" id="ftime" name="ftime" placeholder="From Time" required value="<?php echo date('H:m'); ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">To </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control col-sm-3 low-widd" data-date-format="YYYY-MM-DD" id="tdate" name="tdate" placeholder="To Date" required />
                                        <input type="text" class="form-control col-sm-3 low-widd" data-date-format="HH:mm" id="ttime" name="ttime" placeholder="To Time" required value="<?php echo date('H:m'); ?>" />
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
									<div class="col-lg-9 col-lg-offset-3">
										<input type="hidden" name="action" value="promoCodeAdd" />
										<input type="hidden" name="promoId" value="<?php echo $promoId; ?>" />
										<button class="btn btn-primary" type="submit"> Add Now ! </button>
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
$('#fdate, #tdate').datetimepicker({pickTime: false});
$('#ftime, #ttime').datetimepicker({pickDate: false});
/*function getDateTime(id){
	$.get("adminAjax.php", {"action" : 'getPromotionDateTime', 'data1' : id, 'dataTempId' : '3qv701a122d9d00ca1d46d1cs31.cloud.usa'}, function(data, status){
		$('#fdate').val(data.fDate); $('#tdate').val(data.tDate); $('#ftime').val(data.fTime); $('#ttime').val(data.tTime);
	}, "json");
}*/
function changeTypeField(val){
	$('.percentText, .amountText').css('display', 'none');
	$('.'+val+'Text').css('display', 'block');
	$('.percentBtn, .amountBtn').removeClass('active');
	$('.'+val+'Btn').addClass('active');
	$('.promoValType').val(val);
}
changeTypeField('percent');
</script>
</body>
</html>