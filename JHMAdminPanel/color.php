<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
        	
            <div class="page-header">
				<h1>Color <small>All Colors</small></h1>
				<button class="btn btn-info" type="button" onclick="window.location='colorAdd.php'">Add New Color</button>
			</div>			    
			<div class="panel panel-default">
				<!--<div class="panel-heading">
					<a href="#" data-column="0" class="toggle-vis btn btn-default btn-sm">Name</a>
					<a href="#" data-column="1" class="toggle-vis btn btn-default btn-sm">Position</a>
					<a href="#" data-column="2" class="toggle-vis btn btn-default btn-sm">Office</a>
					<a href="#" data-column="3" class="toggle-vis btn btn-default btn-sm">Age</a>
					<a href="#" data-column="4" class="toggle-vis btn btn-default btn-sm">Start date</a>
					<a href="#" data-column="5" class="toggle-vis btn btn-default btn-sm">Salary</a>
				</div>-->
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Color</th>
								<th> </th>
								<th>Color Code</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							$query = "SELECT tbl_color.* FROM tbl_color ORDER BY color";
							$rs_c = mysqli_query($con, $query, $con);
							while($row = mysqli_fetch_object($rs_c)){
							?>
								<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
									<td><?php echo $i; ?></td>
									<td><?php echo $row->color; ?></td>
									<td style="background:<?php echo $row->color_code; ?>;">&nbsp;  </td>
									<td><?php echo $row->color_code; ?></td>
									<td>
										<button onClick="window.location='colorEdit.php?data1=<?php echo $row->color_id; ?>'" class="btn btn-info btn-xs">Edit</button>
										<button type="button" class="btn btn-danger btn-xs" onClick="delete_c(<?php echo $row->color_id; ?>);">Delete</button>
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
function delete_c(id){
	if(confirm('Do you want to Delete this Color?')){
		$.get('delete.php', {'table' : 'tbl_color', 'pk' : 'color_id', 'id' : id}, function(data){
			alert(data);
			location.reload();
		});
	}
}
</script>
<?php include 'include/tableJs.php'; ?>
</body>
</html>