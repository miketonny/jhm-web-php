<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Tax <small>All Taxes</small></h1>
				<?php if($taxPerm['add']){ ?>
                <button class="btn btn-info" type="button" onclick="window.location='taxAdd.php'">Add New Tax</button>
                <?php } ?>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Tax Name</th>
								<th>Tax Percent</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							$query = "SELECT tbl_tax.* FROM tbl_tax ORDER BY tax_name";
							$rs = mysql_query($query, $con);
							while($row = mysql_fetch_object($rs)){
							?>
								<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
									<td><?php echo $i; ?></td>
									<td><?php echo $row->tax_name; ?></td>
									<td><?php echo $row->tax_percent; ?></td>
									<td>
                                    <?php if($taxPerm['edit']){ ?>
										<button onClick="window.location='taxEdit.php?data1=<?php echo $row->recid; ?>'" class="btn btn-info btn-xs">Edit</button>
                                    <?php }if($taxPerm['status']){ ?>
										<button type="button" class="btn btn-danger btn-xs" onClick="delete_t(<?php echo $row->recid; ?>);">Delete</button>
                                    <?php } ?>
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
	if(confirm('Do you want to Delete this Tax?')){
		$.get('delete.php', {'table' : 'tbl_tax', 'pk' : 'recid', 'id' : id}, function(data){ alert(data); location.reload(); });
	}
}
</script>
<?php include 'include/tableJs.php'; ?>
</body>
</html>