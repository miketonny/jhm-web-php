<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Promotion <small> All Promo Codes</small></h1></div>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Title</th>
								<th>Created By</th>
								<th>Promo Value</th>
                                <th>User Emails</th>
								<th>Validity</th>
								<th>Created On</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php $i = 1;
						$query = "SELECT tbl_promo_code.*, tbl_promo_code_detail.email, tbl_promo_code_detail.admin_id
						FROM tbl_promo_code
						LEFT JOIN tbl_promo_code_detail ON tbl_promo_code.recid = tbl_promo_code_detail.promo_code_id
						ORDER BY tbl_promo_code.recid DESC";
						$rs = mysql_query($query, $con);
						while($row = mysql_fetch_object($rs)){
							$adminData = mysql_fetch_object(exec_query("SELECT username, email FROM admin WHERE recid = '$row->admin_id'", $con));
						?>
							<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
								<td><?php echo $i; ?></td>
								<td><?php echo $row->title; ?></td>
								<td><?php echo (isset($adminData->username) && isset($adminData->email))?$adminData->username.'<br/>'.$adminData->email:''; ?></td>
								<td><?php echo $row->promo_value; echo ($row->percent_or_amount == 'amount')?' $':' %'; ?></td>
                                <td><?php echo substr($row->email, 0, 50); ?>...</td>
                                <td><?php echo 'from: '.date('d M, Y h:i A', strtotime($row->start_date)).'<br/> to: '.date('d M, Y h:i A', strtotime($row->end_date)); ?></td>
								<td><?php echo date('d M, Y h:i A', strtotime($row->created_on)); ?></td>
								<td><button type="button" class="btn btn-danger btn-xs" onClick="delete_pm(<?php echo $row->recid; ?>);">Delete</button></td>
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
function delete_pm(id){
	if(confirm('Do you want to Delete this Promo Code?')){
		$.get('delete.php', {'table' : 'tbl_promo_code', 'pk' : 'recid', 'id' : id}, function(data){ alert(data); location.reload(); });
	}
}
</script>
<?php include 'include/tableJs.php'; ?>
</body>
</html>