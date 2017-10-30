<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Customer <small>All Customers</small></h1></div>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Image</th>
                                <th>Name</th>
                                <th>Email (Username)</th>
								<th>Phone</th>
								<th>Address</th>
                                <th>State, Country</th>
                                <th>Register on</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							$query = "SELECT tbl_user.*, tbl_country.country_name FROM tbl_user LEFT JOIN tbl_country ON tbl_country.country_id = tbl_user.country_id ORDER BY user_id";
							$rs = mysql_query($query, $con);
							while($row = mysql_fetch_object($rs)){ $img = $row->img; ?>
								<tr <?php if(($i%2) == 0){ echo 'class="info"'; } ?>>
									<td><?php echo $i; ?></td>
									<td align="center"><?php
										echo ($img != '')?'<img src="../site_image/profile_pic/'.$img.'" height="45" />':'-'; ?>
									</td>
                                    <td><?php echo $row->title.' '.$row->first_name.' '.$row->last_name; ?></td>
									<td><?php echo $row->email.' ('.$row->username.')'; ?></td>
									<td><?php echo $row->phone_1; ?></td>
                                    <td><?php echo $row->address_1.', '.$row->city.'-'.$row->zip; ?></td>
                                    <td><?php echo $row->state.', '.$row->country_name; ?></td>
                                    <td><?php echo date('d M, Y h:i A', strtotime($row->register_on)); ?></td>
                                    <td>
                                    	<?php if($userPerm['edit']){ ?>
										<button onClick="window.location='userEdit.php?data1=<?php echo $row->user_id; ?>'" class="btn btn-info btn-xs">Edit</button>
                                        <?php }if($userPerm['status']){ ?>
										<button type="button" class="btn btn-danger btn-xs" onClick="delete_u(<?php echo $row->user_id; ?>);">Delete</button>
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
		$.get('delete.php', {'table' : 'tbl_user', 'pk' : 'user_id', 'id' : id}, function(data){ alert(data); location.reload(); });
	}
}
</script>
<?php include 'include/tableJs.php'; ?>
</body>
</html>