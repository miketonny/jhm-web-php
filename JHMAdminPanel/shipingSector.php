<?php include 'include/header.php'; $sel = 'selected="selected"'; ?>
        <div class="warper container-fluid">
        	
            <div class="page-header"><h1>Manage Shipping <small> All Shipping Sector</small></h1></div>
            <?php if($tagPerm['add']){ ?>
            <button class="btn btn-purple btn-flat" data-target="#modal-add" data-toggle="modal" style="margin: 0px 0px 8px;" type="button">Add New Shipping Sector</button>
            <?php } ?>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="1toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Sector</th>
								<th>Post Code</th>
								<th>Suburb</th>
                                <th>Town</th>
                                <th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						include '../include/ps_pagination.php';
						/*$query = "SELECT tss.recid AS mid, tss.*, tsp.sector_name FROM tbl_shipping_sector tss
						LEFT JOIN tbl_shipping_price tsp ON tsp.recid = tss.sector_code ORDER BY tss.sector_name";*/
						$query = "SELECT tss.recid AS mid, tss.* FROM tbl_shipping_sector tss ORDER BY tss.sector_name";
               			
						$pagePro = 30;
						$pager = new PS_Pagination($con, $query, $pagePro, 15);
						$pager->setDebug(true);
						$rs = $pager->paginate();
                		if($rs){ //$rs = mysql_query($query, $con);
							if(isset($_GET['page'])){ $i = ($pagePro * $_GET['page'] - $pagePro) + 1; }
							while($row = mysqli_fetch_object($rs)){
							?>
								<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
									<td><?php echo $i; ?></td>
									<td><?php echo $row->sector_name.' ('.$row->sector_code.')'; ?></td>
									<td><?php echo $row->postcode; ?></td>
									<td><?php echo $row->suburb_name; ?></td>
									<td><?php echo $row->town_name; ?></td>
									<td>
									<?php if($tagPerm['edit']){ ?>
										<button type="button" class="btn btn-info btn-xs" onClick="window.location='shipingSectorEdit.php?data1=<?php echo $row->mid; ?>';">Edit</button>
									<?php }if($tagPerm['status']){ ?>
										<button type="button" class="btn btn-danger btn-xs" onClick="delete_t(<?php echo $row->mid; ?>);">Delete</button>
									<?php } ?>
										
									</td>
								</tr>
							<?php $i++; }
						}?>
						</tbody>
					</table>
                    <div style="text-align:center;"><?php echo $pager->renderFullNav(); ?></div>
				</div>
			</div>
        </div>
        <!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php'; ?>
<script>
function delete_t(id){
	if(confirm('Do you want to Delete this Sector?')){
		$.get('delete.php', {'table' : 'tbl_shipping_sector', 'pk' : 'recid', 'id' : id}, function(data){ alert(data); location.reload(); });
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
				<h4 class="modal-title" id="myModalLabel">Add New Shipping Sector</h4>
			</div>
			<div class="modal-body panel-body">
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Sector Name</label>
                    <div class="col-sm-8">
                    	<input class="form-control" required name="sname" placeholder="Sector Name" type="text" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Sector Code</label>
                    <div class="col-sm-8">
                    	<input class="form-control" required name="scode" placeholder="Sector Code" type="text" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Postcode</label>
                    <div class="col-sm-8">
                        <input class="form-control" required name="postcode" placeholder="Postcode" type="text" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Suburb</label>
                    <div class="col-sm-8">
                    	<input class="form-control" required name="suburb" placeholder="Suburb" type="text" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Town</label>
                    <div class="col-sm-8">
                    	<input class="form-control" required name="town" placeholder="Town" type="text" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Is Rural?</label>
                    <div class="col-sm-8">
                    	<div class="switch-button showcase-switch-button control-label sm primary">
							<input id="switch-button-1" name="isRural" value="1" type="checkbox" >
							<label for="switch-button-1"></label> 
						</div> &nbsp; &nbsp;
					</div>
                </div>
                
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Add Now !</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input type="hidden" name="action" value="shippingCityAdd" />
			</div>
		</div>
		</form>
	</div>
</div><!-- add popup end -->
</body>
</html>