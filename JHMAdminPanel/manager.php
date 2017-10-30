<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Manager & Moderators <small>All Managers & Moderators</small></h1></div>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Username</th>
								<th>Email</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						$query = "SELECT * FROM admin WHERE atype = 1";
						$rs_ad = mysql_query($query, $con);
						while($row_ad = mysql_fetch_object($rs_ad)){
						?>
							<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
								<td><?php echo $i; ?></td>
								<td><?php echo $row_ad->username;?></td>
								<td><?php echo $row_ad->email; ?></td>
								<?php /*<td><?php echo getForeignPassportId($row_ad->password, $con); ?></td>*/ ?>
								<td>
                                	<?php if($managerPerm['status']){ ?>
									<button onClick="window.location='managerPermission.php?data1=<?php echo $row_ad->recid; ?>'" class="btn btn-info btn-xs">Set Permission</button>
                                    <?php }if($managerPerm['status']){ ?>
									<button type="button" class="btn btn-danger btn-xs" onClick="delete_user(<?php echo $row_ad->recid; ?>);">Delete</button>
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
function delete_user(id){
	if(confirm('Do you want to Delete this Sub Admin?')){
		$.get('delete.php', {'table' : 'admin', 'pk' : 'recid', 'id' : id}, function(data){
			alert(data);
			location.reload();
		});
	}
}
</script>
<?php include 'include/tableJs.php'; ?>
</body>
</html>