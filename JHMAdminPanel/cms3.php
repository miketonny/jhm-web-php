<?php include 'include/header.php';
$support = mysql_fetch_object(exec_query("SELECT content FROM tbl_manage WHERE type = 'support'", $con))->content;
$sArr = explode('|', $support);
?>
<style>.margin{ margin-bottom: 10px; }</style>
<div class="warper container-fluid">
    <div class="page-header"><h1>CMS</h1></div>
    
    <div class="row">
        <ul role="tablist" class="nav nav-tabs" id="myTab">
            <li class="active"><a data-toggle="tab" role="tab" href="#support">Support Details</a></li>
            <!--<li><a data-toggle="tab" role="tab" href="#accept_cards">Accept Cards</a></li>
            <li><a data-toggle="tab" role="tab" href="#cancellationReturn">Cancellation & Return</a></li>
            <li><a data-toggle="tab" role="tab" href="#faq">FAQ</a></li>
            <li><a data-toggle="tab" role="tab" href="#ship">Shipping Detail</a></li>-->
        </ul>

        <div class="tab-content" id="myTabContent">
            
            <div id="support" class="tab-pane tabs-up fade in active panel panel-default">
            	<form method="post" action="admin_action_model.php">
                <div class="panel-body">
                	
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Phone 1</label>
                        <div class="col-sm-6">
                            <div id="picker"></div>
                            <input class="form-control margin" required name="support[]" placeholder="Phone 1" type="text" value="<?php echo ($sArr[0] != '>')?$sArr[0]:''; ?>"  />
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Phone 2</label>
                        <div class="col-sm-6">
                            <div id="picker"></div>
                            <input class="form-control margin" name="support[]" placeholder="Phone 2" vtype="text" value="<?php echo ($sArr[1] != '>')?$sArr[1]:''; ?>"  />
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email 1</label>
                        <div class="col-sm-6">
                            <div id="picker"></div>
                            <input class="form-control margin" required name="support[]" placeholder="Email 1" type="text" value="<?php echo ($sArr[2] != '>')?$sArr[2]:''; ?>" />
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email 2</label>
                        <div class="col-sm-6">
                            <div id="picker"></div>
                            <input class="form-control margin" name="support[]" placeholder="Email 2" type="text" value="<?php echo ($sArr[3] != '>')?$sArr[3]:''; ?>" />
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Other 1</label>
                        <div class="col-sm-6">
                            <div id="picker"></div>
                            <input class="form-control margin" required name="support[]" placeholder="Other 1" type="text" value="<?php echo ($sArr[4] != '>')?$sArr[4]:''; ?>" />
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Other 2</label>
                        <div class="col-sm-6">
                            <div id="picker"></div>
                            <input class="form-control margin" name="support[]" placeholder="Other 1" type="text" value="<?php echo ($sArr[5] != '>')?$sArr[5]:''; ?>" />
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Other 3</label>
                        <div class="col-sm-6">
                            <div id="picker"></div>
                            <input class="form-control margin" name="support[]" placeholder="Other 1" type="text" value="<?php echo ($sArr[6] != '>')?$sArr[6]:''; ?>" />
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    
                    <hr/>
                    <div class="form-group">
                        <input type="hidden" name="type" value="support" />
                        <input type="hidden" name="action" value="manage" />
                        <div class="col-lg-9 col-lg-offset-3">
                            <button class="btn btn-primary" type="submit"> Save Changes ! </button>
                        </div>
                    </div>
                    
                </div>
                </form>
            </div>
            
        </div>
	</div>
</div>
<!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php'; ?>
<?php include 'include/formJs.php'; ?>
<!-- Data Table -->
<script src="assets/js/plugins/datatables/jquery.dataTables.js"></script>
<script src="assets/js/plugins/datatables/DT_bootstrap.js"></script>
<script src="assets/js/plugins/datatables/jquery.dataTables-conf.js"></script>
</body>
</html>