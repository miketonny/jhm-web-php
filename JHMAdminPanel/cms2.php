<?php include 'include/header.php';
//$emailTemp = mysql_fetch_object(exec_query("SELECT content FROM tbl_manage WHERE type = 'emailTemplate'", $con))->content;
$payment = mysql_fetch_object(exec_query("SELECT content FROM tbl_manage WHERE type = 'payment'", $con))->content;
$accept_cards = mysql_fetch_object(exec_query("SELECT content FROM tbl_manage WHERE type = 'accept_cards'", $con))->content;
$cancellationReturn = mysql_fetch_object(exec_query("SELECT content FROM tbl_manage WHERE type = 'cancellationReturn'", $con))->content;
$faq = mysql_fetch_object(exec_query("SELECT content FROM tbl_manage WHERE type = 'faq'", $con))->content;
//$shipping = mysql_fetch_object(exec_query("SELECT content FROM tbl_manage WHERE type = 'shipping'", $con))->content;
?>
<style>
#cke_1_contents, #cke_2_contents, #cke_3_contents, #cke_4_contents, #cke_5_contents{ height:420px !important; }
.modal-dialog{ width:900px !important; }
.tdd{ width: 25%; margin: 5px; padding: 5px; border: 1px solid rgb(236, 236, 236); }
</style>
<div class="warper container-fluid">
    <div class="page-header"><h1>CMS</h1></div>
    
    <div class="row">
        <ul role="tablist" class="nav nav-tabs" id="myTab">
            <!--<li class="active"><a data-toggle="tab" role="tab" href="#payment">Payment</a></li>-->
            <li class="active"><a data-toggle="tab" role="tab" href="#accept_cards">Payment</a></li>
            <!--<li><a data-toggle="tab" role="tab" href="#cancellationReturn">Cancellation & Return</a></li>-->
            <li><a data-toggle="tab" role="tab" href="#faq">FAQs</a></li>
            <!--<li><a data-toggle="tab" role="tab" href="#ship">Shipping Detail</a></li>-->
        </ul>

        <div class="tab-content" id="myTabContent">
            
            <?php /*<div id="payment" class="tab-pane tabs-up fade in active panel panel-default">
            	<form method="post" action="admin_action_model.php">
                <div class="panel-body">
                	<div class="form-group">
						<div class="col-sm-10">
							<textarea class="form-control" id="ckeditor1" name="content" placeholder="Description" cols="100" rows="10" ><?php echo $payment; ?></textarea>
                            <input type="hidden" name="type" value="payment" />
                            <input type="hidden" name="action" value="manage" />
						</div>
					</div>
                    <div class="form-group">
						<div class="col-lg-9 col-lg-offset-3"><br/>
                        	<?php if($cmsPerm['add'] || $cmsPerm['edit']){ ?>
								<button class="btn btn-primary" type="submit"> Save Now ! </button>
                            <?php } ?>
						</div>
					</div>
                </div>
                </form>
            </div>*/ ?>
            
            <div id="accept_cards" class="tab-pane tabs-up fade in active panel panel-default">
            	<form method="post" action="admin_action_model.php">
                <div class="panel-body">
                	<div class="form-group">
						<div class="col-sm-10">
							<textarea class="form-control" id="ckeditor2" name="content" placeholder="Description" cols="100" rows="10" ><?php echo $accept_cards; ?></textarea>
                            <input type="hidden" name="type" value="accept_cards" />
                            <input type="hidden" name="action" value="manage" />
						</div>
					</div>
                    <div class="form-group">
						<div class="col-lg-9 col-lg-offset-3"><br/>
							<?php if($cmsPerm['add'] || $cmsPerm['edit']){ ?>
								<button class="btn btn-primary" type="submit"> Save Now ! </button>
                            <?php } ?>
						</div>
					</div>
                </div>
                </form>
            </div>
            
            <?php /*<div id="cancellationReturn" class="tab-pane tabs-up fade panel panel-default">
            	<form method="post" action="admin_action_model.php">
                <div class="panel-body">
                	<div class="form-group">
						<div class="col-sm-10">
							<textarea class="form-control" id="ckeditor3" name="content" placeholder="Description" cols="100" rows="10" ><?php echo $cancellationReturn; ?></textarea>

                            <input type="hidden" name="type" value="cancellationReturn" />
                            <input type="hidden" name="action" value="manage" />
						</div>
					</div>
                    <div class="form-group">
						<div class="col-lg-9 col-lg-offset-3"><br/>
							<?php if($cmsPerm['add'] || $cmsPerm['edit']){ ?>
								<button class="btn btn-primary" type="submit"> Save Now ! </button>
                            <?php } ?>
						</div>
					</div>
                </div>
                </form>
            </div>*/ ?>
            
            <div id="faq" class="tab-pane tabs-up fade panel panel-default">
            	<form method="post" action="admin_action_model.php">
                <div class="panel-body">
                	<div class="form-group">
						<div class="col-sm-10">
							<textarea class="form-control" id="ckeditor4" name="content" placeholder="Description" cols="100" rows="10" ><?php echo $faq; ?></textarea>
                            <input type="hidden" name="type" value="faq" />
                            <input type="hidden" name="action" value="manage" />
						</div>
					</div>
                    <div class="form-group">
						<div class="col-lg-9 col-lg-offset-3"><br/>
							<?php if($cmsPerm['add'] || $cmsPerm['edit']){ ?>
								<button class="btn btn-primary" type="submit"> Save Now ! </button>
                            <?php } ?>
						</div>
					</div>
                </div>
                </form>
            </div>
            
            <?php /*<div id="ship" class="tab-pane tabs-up fade panel panel-default">
            	<form method="post" action="admin_action_model.php">
                <div class="panel-body">
                	<div class="form-group">
						<div class="col-sm-10">
							<textarea class="form-control" id="ckeditor5" name="content" placeholder="Description" cols="100" rows="10" ><?php echo $shipping; ?></textarea>
                            <input type="hidden" name="type" value="shipping" />
                            <input type="hidden" name="action" value="manage" />
						</div>
					</div>
                    <div class="form-group">
						<div class="col-lg-9 col-lg-offset-3"><br/>
							<?php if($cmsPerm['add'] || $cmsPerm['edit']){ ?>
								<button class="btn btn-primary" type="submit"> Save Now ! </button>
                            <?php } ?>
						</div>
					</div>
                </div>
                </form>
            </div>*/ ?>
             
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

<script>
$('#ckeditor, #ckeditor1, #ckeditor2, #ckeditor3, #ckeditor4, #ckeditor5').ckeditor();
</script>
</body>
</html>