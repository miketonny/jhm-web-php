<?php include 'include/header.php';
$sel = 'selected="selected"';
?>
        <div class="warper container-fluid">
        	
            <div class="page-header"><h1>Manage Shipping <small> All Shipping Charges</small></h1></div>
            <?php if($tagPerm['add']){ ?>
            <button class="btn btn-purple btn-flat" data-target="#modal-add" data-toggle="modal" style="margin: 0px 0px 8px;" type="button">Add New Shipping Charge</button>
            <?php } ?>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Sector</th>
								<th>Price</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						//$query = "SELECT tsp.*, tss.sector_name FROM tbl_shipping_price tsp LEFT JOIN tbl_shipping_sector tss ON tss.sector_code = tsp.sector_code GROUP BY  ORDER BY sector_code";
						$query = "SELECT tsp.* FROM tbl_shipping_price tsp ORDER BY tsp.sector_code";
						$rs = mysql_query($query, $con);
						while($row = mysql_fetch_object($rs)){
							$sname1=mysql_fetch_object(mysql_query("SELECT sector_name FROM tbl_shipping_sector WHERE sector_code='$row->sector_code'"));
						?>
							<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
								<td><?php echo $i; ?></td>
								<td><?php echo $sname1->sector_name.' ('.$row->sector_code.')'; ?></td>
                                <td>$ <?php echo $row->price; ?></td>
                                <td>
                                <?php if($tagPerm['edit']){ ?>
									<button data-target="#modal-edit<?php echo $row->recid; ?>" data-toggle="modal" class="btn btn-info btn-xs">Edit</button>
                                <?php }if($tagPerm['status']){ ?>
									<button type="button" class="btn btn-danger btn-xs" onClick="delete_t(<?php echo $row->recid; ?>);">Delete</button>
                                <?php } ?>
                                    
                                    <!-- edit popup start -->
                                    <div class="modal fade" id="modal-edit<?php echo $row->recid; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Edit Shipping Charge</h4>
                                                </div>
                                                <div class="modal-body panel-body">
                                                    
                                                    <div class="form-group">
                                                    	<label class="col-sm-3 control-label">Sector</label>
                                                        <div class="col-sm-8">
                                                            <select class="form-control" required name="scode" >
                                                                <option value="">- SELECT SECTOR -</option>
                                                                <?php
                                                                $rss = mysql_query("SELECT DISTINCT(sector_code) FROM tbl_shipping_sector ORDER BY sector_code");
                                                                while($roww = mysql_fetch_object($rss)){
                                                                    $sname=mysql_fetch_object(mysql_query("SELECT sector_name FROM tbl_shipping_sector WHERE sector_code='$roww->sector_code'"));
                                                                    ?>
                                                                    <option <?php if($roww->sector_code == $row->sector_code){ echo $sel; } ?> value="<?php echo $roww->sector_code; ?>"><?php echo $sname->sector_name.' ('.$roww->sector_code.')'; ?></option>
                                                                <?php }	?>
                                                            </select>
                                                       	</div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Shipping Price</label>
                                                        <div class="col-sm-8">
                                                            <p style="float: left;line-height: 35px;margin-right: 5px;">$</p>
                                                            <input class="form-control" required name="price" placeholder="Shipping Price" type="text" value="<?php echo $row->price; ?>" style="width:96%;" />
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save Changes !</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <input type="hidden" name="action" value="shippingPriceEdit" />
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
function delete_t(id){
	if(confirm('Do you want to Delete this Sector?')){
		$.get('delete.php', {'table' : 'tbl_shipping_price', 'pk' : 'recid', 'id' : id}, function(data){ alert(data); location.reload(); });
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
				<h4 class="modal-title" id="myModalLabel">Add New Shipping Price</h4>
			</div>
			<div class="modal-body panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Sector</label>
                    <div class="col-sm-8">
                    	<select class="form-control" required name="scode" >
                        	<option value="">- SELECT SECTOR -</option>
                            <?php
							$rss = mysql_query("SELECT DISTINCT(sector_code) FROM tbl_shipping_sector ORDER BY sector_code");
							while($roww = mysql_fetch_object($rss)){
								$sname=mysql_fetch_object(mysql_query("SELECT sector_name FROM tbl_shipping_sector WHERE sector_code='$roww->sector_code'"));
								?>
                            	<option value="<?php echo $roww->sector_code; ?>"><?php echo $sname->sector_name.' ('.$roww->sector_code.')'; ?></option>
                            <?php }	?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Shipping Price</label>
                    <div class="col-sm-8">
                   		<p style="float: left;line-height: 35px;margin-right: 5px;">$</p>
                        <input class="form-control" required name="price" placeholder="Shipping Price" type="text" style="width:96%;" />
                    </div>
                </div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Add Now !</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input type="hidden" name="action" value="shippingPriceAdd" />
			</div>
		</div>
		</form>
	</div>
</div><!-- add popup end -->
</body>
</html>