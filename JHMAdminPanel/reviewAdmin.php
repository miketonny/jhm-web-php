<?php include 'include/header.php'; ?>
<link rel="stylesheet" href="jquery/jRating.jquery.css" type="text/css" />
<!-- JQuery v1.9.1 -->
<script src="assets/js/jquery/jquery-1.9.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="jquery/jRating.jquery.js"></script>

        <div class="warper container-fluid">
            <div class="page-header"><h1>Review & Rating <small>All Review & Rating</small></h1></div>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>User</th>
                                <th>Product</th>
								<th>Rating</th>
                                <th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							$query = "SELECT tr.*, tu.email, tp.product_name FROM tbl_review tr LEFT JOIN tbl_user tu ON tu.user_id = tr.user_id LEFT JOIN tbl_product tp ON tp.product_id = tr.product_id ORDER BY datetime DESC";
							$rs = mysql_query($query, $con);
							while($row = mysql_fetch_object($rs)){
								$appr = $row->is_approve;
							?>
								<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
									<td><?php echo $i; ?></td>
									<td><?php echo $row->email; ?></td>
									<td><?php echo $row->product_name; ?></td>
                                    <td>
										<div class="exemple">
											<div class="exempleRow<?php echo $i; ?>" data-average="<?php echo $row->rating; ?>" data-id="600"></div>
										</div>
                                    </td>
                                    <td><?php if($appr == 1){ echo 'Approved'; }elseif($appr == 2){ echo 'Rejected'; }elseif($appr == 0){ echo '-'; } ?></td>
									<td>
                                    <?php if($reviewPerm['status']){ ?>
										<?php if($appr == 0){ ?>
                                        <button onClick="approve(<?php echo $row->review_id; ?>);" type="button" class="btn btn-primary btn-xs">Approve</button>
                                        <button onClick="block(<?php echo $row->review_id; ?>);" type="button" class="btn btn-danger btn-xs">Reject</button>
                                    	<?php } ?>
										<button type="button" class="btn btn-danger btn-xs" onClick="delete_r(<?php echo $row->review_id; ?>);">Delete</button>
									<?php } ?>
                                    	<button data-target="#modal-view<?php echo $row->review_id; ?>" data-toggle="modal" class="btn btn-purple btn-xs">View</button>
                                        
                                   	<!-- view popup start -->
                                    <div class="modal fade" id="modal-view<?php echo $row->review_id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">View Review</h4>
                                                </div>
                                                <div class="modal-body panel-body">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Title</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control" required name="title" placeholder="Title" type="text" value="<?php echo $row->title; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Review</label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control" name="desc" placeholder="Description" cols="" rows="3" required style="height:260px;" ><?php echo $row->review; ?></textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Rating</label>
                                                        <div class="col-sm-8">
                                                        <div class="exemple">
                                                            <div class="exemplePop<?php echo $i; ?>" data-average="<?php echo $row->rating; ?>" data-id="600"></div>
                                                            <span><?php echo $row->rating; ?> / 5</span>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <?php /*<div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save Changes !</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <input type="hidden" name="action" value="tagEdit" />
                                                    <input type="hidden" name="data1" value="<?php echo $row->recid; ?>" />
                                                </div>*/ ?>
                                            </div>
                                            </form>
                                        </div>
                                    </div><!-- view popup end -->
                                        
									</td>
								</tr>
                                <script type="text/javascript">
									$(".exempleRow<?php echo $i; ?>, .exemplePop<?php echo $i; ?>").jRating({
									  length:5,
									  decimalLength:1,
									  showRateInfo:false,
									  isDisabled : true
									});
								</script>
							<?php $i++; } ?>
						</tbody>
					</table>
				</div>
			</div>
            
        </div>
        <!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php'; ?>
<script>
function delete_r(id){
	if(confirm('Do you want to Delete this Review?')){
		$.get('delete.php', {'table' : 'tbl_review', 'pk' : 'review_id', 'id' : id}, function(data){ alert(data); location.reload(); });
	}
}
function approve(id){
	if(confirm('Do you want to Approve this Review?')){
		$.get('change_status.php', {'table' : 'tbl_review', 'pk_column' : 'review_id', 'pk_val' : id, 'up_column' : 'is_approve', 'up_val' : 1}, function(data){
			alert(data); location.reload();
		});
	}
}
function block(id){
	if(confirm('Do you want to Reject this Review?')){
		$.get('change_status.php', {'table' : 'tbl_review', 'pk_column' : 'review_id', 'pk_val' : id, 'up_column' : 'is_approve', 'up_val' : 2}, function(data){
			alert(data); location.reload();
		});
	}
}
</script>
<!-- Bootstrap -->
<script src="assets/js/bootstrap/bootstrap.min.js"></script>
<!-- NanoScroll -->
<script src="assets/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
<!-- Data Table -->
<script src="assets/js/plugins/datatables/jquery.dataTables.js"></script>
<script src="assets/js/plugins/datatables/DT_bootstrap.js"></script>
<script src="assets/js/plugins/datatables/jquery.dataTables-conf.js"></script>
<!-- Custom JQuery -->
<script src="assets/js/app/custom.js" type="text/javascript"></script>
</body>
</html>