<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Customer <small>All Newsletter Customers</small></h1></div>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Email</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							$query = "SELECT * FROM tbl_user_newsletter ORDER BY email";
							$rs = mysql_query($query, $con);
							while($row = mysql_fetch_object($rs)){ ?>
								<tr <?php if(($i%2) == 0){ echo 'class="info"'; } ?>>
									<td><?php echo $i; ?></td>
									<td><?php echo $row->email; ?></td>
                                    <td>
										<?php if($userPerm['status']){ ?>
										<button type="button" class="btn btn-danger btn-xs" onClick="delete_u(<?php echo $row->recid; ?>);">Delete</button>
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
function delete_u(id){
	if(confirm('Do you want to Delete this Customer?')){
		$.get('delete.php', {'table' : 'tbl_user_newsletter', 'pk' : 'recid', 'id' : id}, function(data){ alert(data); location.reload(); });
	}
}
</script>
<?php include 'include/tableJs.php'; ?>
</body>
</html>