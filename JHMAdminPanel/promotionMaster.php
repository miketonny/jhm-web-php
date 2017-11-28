<?php include 'include/header.php';
$cdate = date('Y-m-d H:i:s');
?>
        <div class="warper container-fluid">
        	
            <div class="page-header"><h1>Promotion<small>All Master Promotion</small></h1></div>
            <?php if($promotionPerm['add']){ ?>
            <button class="btn btn-purple btn-flat" data-target="#modal-add" data-toggle="modal" style="margin: 0px 0px 8px;" type="button">Add Master Promotion!</button>
            <?php } ?>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Promotion Title</th>
								<th>Banner</th>
								<th>Created On</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						$query = "SELECT tbl_promotion_master.*, tbl_promotion.title, tbl_promotion.promo_value, tbl_promotion.percent_or_amount FROM tbl_promotion_master
						LEFT JOIN tbl_promotion ON tbl_promotion.promo_id = tbl_promotion_master.promo_id
						ORDER BY tbl_promotion_master.recid DESC";
						$rs = mysqli_query($con, $query);
						while($row = mysqli_fetch_object($rs)){ $stat = $row->is_activate;
						?>
							<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
								<td><?php echo $i; ?></td>
								<td><?php echo $row->title.' ('.$row->promo_value; echo ($row->percent_or_amount == 'amount')?' $)':' %)'; ?></td>
                                <td><img src="../site_image/promotion/<?php echo $row->thumb; ?>" /></td>
								<td><?php echo date('d M, Y h:i A', strtotime($row->created_on)); ?></td>
								<td>
								<?php if($promotionPerm['status']){ ?>
									<?php if($stat == 0){ ?>
									<button onClick="publish(<?php echo $row->recid; ?>);" class="btn btn-primary btn-xs">Publish</button>
									<?php }elseif($stat == 1){ ?>
									<button onClick="unpublish(<?php echo $row->recid; ?>);" class="btn btn-primary btn-xs">Unpublish</button>
								<?php } } ?>
                                
                                <?php if($promotionPerm['edit']){ ?>
									<button data-target="#modal-edit<?php echo $row->recid; ?>" data-toggle="modal" class="btn btn-info btn-xs">Edit</button>
                                <?php }if($promotionPerm['status']){ ?>
									<button type="button" class="btn btn-danger btn-xs" onClick="delete_pm(<?php echo $row->recid; ?>);">Delete</button>
                                <?php } ?>
                                    
                                    <!-- edit popup start -->
                                    <div class="modal fade" id="modal-edit<?php echo $row->recid; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Edit Master Promotion</h4>
                                                </div>
                                                <div class="modal-body panel-body">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Banner Image</label>
                                                        <div class="col-sm-6"> <input name="img" type="file" />
                                                        	<img src="../site_image/promotion/<?php echo $row->thumb; ?>" />
                                                        </div>
                                                    </div><hr/>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Promotion</label>
                                                        <div class="col-sm-6">
                                                            <select class="form-control chosen-select" required name="promo" >
                                                                <option value="">- SELECT PROMOTION -</option>
                                                                <?php
                                                                
																//$pro_rs = exec_query("SELECT title, promo_id, promo_value, percent_or_amount FROM tbl_promotion ORDER BY promo_id DESC", $con);
																$pro_rs = exec_query("SELECT * FROM tbl_promotion WHERE (DATE_FORMAT(start_date, '%Y-%m-%d %H:%i:%s') <= '$cdate' AND DATE_FORMAT(end_date, '%Y-%m-%d %H:%i:%s') >= '$cdate')", $con);
                                                                while($pro_row = mysqli_fetch_object($pro_rs)){ ?>
                                                                   	
                                                                    <option <?php getSelected($row->promo_id,$pro_row->promo_id); ?> value="<?php echo $pro_row->promo_id;?>"><?php echo $pro_row->title.' ('.$pro_row->promo_value; echo ($pro_row->percent_or_amount == 'amount')?' $)':' %)'; echo ' (valid from '.date('d M, Y h:i A', strtotime($pro_row->start_date)).' to '.date('d M, Y h:i A', strtotime($pro_row->end_date)).')'; ?></option>
                                                                    
                                                                <?php }?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save Changes !</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <input type="hidden" name="action" value="masterPromotionEdit" />
                                                    <input type="hidden" name="data1" value="<?php echo $row->recid; ?>" />
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div><!-- edit popup end -->
                                    
								</td>
							</tr>
						<?php $i++; } ?>
						</tbody>
					</table>
				</div>
			</div>
        </div>
        <!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php'; ?>
<script>
function publish(id){
	if(confirm('Do you want to Publish this Promotion?')){
		$.get('change_status.php', {'table' : 'tbl_promotion_master', 'pk_column' : 'recid', 'pk_val' : id, 'up_column' : 'is_activate', 'up_val' : 1}, function(data){
			alert(data); location.reload();
		});
	}
}
function unpublish(id){
	if(confirm('Do you want to Unpublish this Promotion?')){
		$.get('change_status.php', {'table' : 'tbl_promotion_master', 'pk_column' : 'recid', 'pk_val' : id, 'up_column' : 'is_activate', 'up_val' : 0}, function(data){
			alert(data); location.reload();
		});
	}
}
function delete_pm(id){
	if(confirm('Do you want to Delete this Master Promotion?')){
		$.get('delete.php', {'table' : 'tbl_promotion_master', 'pk' : 'recid', 'id' : id}, function(data){ alert(data);	location.reload(); });
	}
}
</script>
<?php include 'include/tableJs.php'; ?>
<!-- add popup start -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Master Promotion</h4>
			</div>
			<div class="modal-body panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Banner Image</label>
                    <div class="col-sm-6"> <input required name="img" type="file" /> </div>
                </div><hr/>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Promotion</label>
                    <div class="col-sm-6">
                        <select class="form-control chosen-select" required name="promo" >
                            <option value="">- SELECT PROMOTION -</option>
                            <?php
                            //$pro_rs = exec_query("SELECT * FROM tbl_promotion ORDER BY promo_id DESC", $con);
							
							$pro_rs = exec_query("SELECT * FROM tbl_promotion WHERE (DATE_FORMAT(start_date, '%Y-%m-%d %H:%i:%s') <= '$cdate' AND DATE_FORMAT(end_date, '%Y-%m-%d %H:%i:%s') >= '$cdate')", $con);
							
                            while($pro_row = mysqli_fetch_object($pro_rs)){ ?>
                               	<option value="<?php echo $pro_row->promo_id;?>"><?php echo $pro_row->title.' ('.$pro_row->promo_value; echo ($pro_row->percent_or_amount == 'amount')?' $)':' %)'; echo ' (valid from '.date('d M, Y h:i A', strtotime($pro_row->start_date)).' to '.date('d M, Y h:i A', strtotime($pro_row->end_date)).')'; ?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Add Now !</button>
                <button type="submit" class="btn btn-info">Add & New !</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input type="hidden" name="action" value="masterPromotionAdd" />
			</div>
		</div>
		</form>
	</div>
</div><!-- add popup end -->
</body>
</html>






<?php /*include 'include/header.php'; ?>
        <div class="warper container-fluid">
        	
            <div class="page-header"><h1>Promotion<small>All Master Promotion</small></h1></div>
            <?php if($promotionPerm['add']){ ?>
            <button class="btn btn-purple btn-flat" data-target="#modal-add" data-toggle="modal" style="margin: 0px 0px 8px;" type="button">Add Master Promotion!</button>
            <?php } ?>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Promotion Title</th>
								<th>Banner</th>
								<th>Created On</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						$query = "SELECT tbl_promotion_master.*, tbl_promotion.title, tbl_promotion.promo_value, tbl_promotion.percent_or_amount FROM tbl_promotion_master
						LEFT JOIN tbl_promotion ON tbl_promotion.promo_id = tbl_promotion_master.promo_id
						ORDER BY tbl_promotion_master.recid DESC";
						$rs = mysql_query($query, $con);
						while($row = mysql_fetch_object($rs)){ $stat = $row->is_activate;
						?>
							<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
								<td><?php echo $i; ?></td>
								<td><?php echo $row->title.' ('.$row->promo_value; echo ($row->percent_or_amount == 'amount')?' $)':' %)'; ?></td>
                                <td><img src="../site_image/promotion/<?php echo $row->thumb; ?>" /></td>
								<td><?php echo date('d M, Y h:i A', strtotime($row->created_on)); ?></td>
								<td>
								<?php if($promotionPerm['status']){ ?>
									<?php if($stat == 0){ ?>
									<button onClick="publish(<?php echo $row->recid; ?>);" class="btn btn-primary btn-xs">Publish</button>
									<?php }elseif($stat == 1){ ?>
									<button onClick="unpublish(<?php echo $row->recid; ?>);" class="btn btn-primary btn-xs">Unpublish</button>
								<?php } } ?>
                                
                                <?php if($promotionPerm['edit']){ ?>
									<button data-target="#modal-edit<?php echo $row->recid; ?>" data-toggle="modal" class="btn btn-info btn-xs">Edit</button>
                                <?php }if($promotionPerm['status']){ ?>
									<button type="button" class="btn btn-danger btn-xs" onClick="delete_pm(<?php echo $row->recid; ?>);">Delete</button>
                                <?php } ?>
                                    
                                    <!-- edit popup start -->
                                    <div class="modal fade" id="modal-edit<?php echo $row->recid; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Edit Master Promotion</h4>
                                                </div>
                                                <div class="modal-body panel-body">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Banner Image</label>
                                                        <div class="col-sm-6"> <input name="img" type="file" />
                                                        	<img src="../site_image/promotion/<?php echo $row->thumb; ?>" />
                                                        </div>
                                                    </div><hr/>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Promotion</label>
                                                        <div class="col-sm-6">
                                                            <select class="form-control chosen-select" required name="promo" >
                                                                <option value="">- SELECT PROMOTION -</option>
                                                                <?php $pro_rs = exec_query("SELECT title, promo_id, promo_value, percent_or_amount FROM tbl_promotion ORDER BY promo_id DESC", $con);
                                                                while($pro_row = mysql_fetch_object($pro_rs)){ ?>
                                                                   	<option <?php getSelected($row->promo_id,$pro_row->promo_id); ?> value="<?php echo $pro_row->promo_id;?>"><?php echo $pro_row->title.' ('.$pro_row->promo_value; echo ($pro_row->percent_or_amount == 'amount')?' $)':' %)'; ?></option>
                                                                <?php }?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save Changes !</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <input type="hidden" name="action" value="masterPromotionEdit" />
                                                    <input type="hidden" name="data1" value="<?php echo $row->recid; ?>" />
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div><!-- edit popup end -->
                                    
								</td>
							</tr>
						<?php $i++; } ?>
						</tbody>
					</table>
				</div>
			</div>
        </div>
        <!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php'; ?>
<script>
function publish(id){
	if(confirm('Do you want to Publish this Promotion?')){
		$.get('change_status.php', {'table' : 'tbl_promotion_master', 'pk_column' : 'recid', 'pk_val' : id, 'up_column' : 'is_activate', 'up_val' : 1}, function(data){
			alert(data); location.reload();
		});
	}
}
function unpublish(id){
	if(confirm('Do you want to Unpublish this Promotion?')){
		$.get('change_status.php', {'table' : 'tbl_promotion_master', 'pk_column' : 'recid', 'pk_val' : id, 'up_column' : 'is_activate', 'up_val' : 0}, function(data){
			alert(data); location.reload();
		});
	}
}
function delete_pm(id){
	if(confirm('Do you want to Delete this Master Promotion?')){
		$.get('delete.php', {'table' : 'tbl_promotion_master', 'pk' : 'recid', 'id' : id}, function(data){ alert(data);	location.reload(); });
	}
}
</script>
<?php include 'include/tableJs.php'; ?>
<!-- add popup start -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Master Promotion</h4>
			</div>
			<div class="modal-body panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Banner Image</label>
                    <div class="col-sm-6"> <input required name="img" type="file" /> </div>
                </div><hr/>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Promotion</label>
                    <div class="col-sm-6">
                        <select class="form-control chosen-select" required name="promo" >
                            <option value="">- SELECT PROMOTION -</option>
                            <?php $cdate = date('Y-m-d H:i:s');
                            //$pro_rs = exec_query("SELECT * FROM tbl_promotion ORDER BY promo_id DESC", $con);
							
							$pro_rs = exec_query("SELECT * FROM tbl_promotion WHERE (DATE_FORMAT(start_date, '%Y-%m-%d %H:%i:%s') <= '$cdate' AND DATE_FORMAT(end_date, '%Y-%m-%d %H:%i:%s') >= '$cdate')", $con);
							
                            while($pro_row = mysql_fetch_object($pro_rs)){ ?>
                               	<option value="<?php echo $pro_row->promo_id;?>"><?php echo $pro_row->title.' ('.$pro_row->promo_value; echo ($pro_row->percent_or_amount == 'amount')?' $)':' %)'; echo ' (valid from '.$pro_row->start_date.' to '.$pro_row->end_date.')'; ?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Add Now !</button>
                <button type="submit" class="btn btn-info">Add & New !</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input type="hidden" name="action" value="masterPromotionAdd" />
			</div>
		</div>
		</form>
	</div>
</div><!-- add popup end -->
</body>
</html>*/ ?>