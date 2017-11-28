<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Size <small>All Sizes</small></h1>
            	<?php if($sizePerm['add']){ ?>
					<button class="btn btn-info" type="button" onclick="window.location='sizeAdd.php'">Add New Size</button>
                <?php } ?>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<!--<th>Sub Category</th>-->
								<th>Size Unit</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							/*$query = "SELECT tbl_size.*, tbl_category.category_name FROM tbl_size LEFT JOIN tbl_category ON tbl_category.category_id = tbl_size.subcategory_id ORDER BY subcategory_id";*/
							$query = "SELECT tbl_size.* FROM tbl_size ORDER BY size";
							$rs_si = mysqli_query($con, $query);
							while($row_si = mysqli_fetch_object($rs_si)){
							?>
								<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
									<td><?php echo $i; ?></td>
									<?php /*<td><?php echo $row_si->category_name; ?></td>*/ ?>
									<td><?php echo $row_si->size; ?></td>
									<td>
                                    <?php if($sizePerm['edit']){ ?>
										<button onClick="window.location='sizeEdit.php?data1=<?php echo $row_si->size_id; ?>'" class="btn btn-info btn-xs">Edit</button>
                                    <?php }if($sizePerm['status']){ ?>
										<button type="button" class="btn btn-danger btn-xs" onClick="delete_si(<?php echo $row_si->size_id; ?>);">Delete</button>
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
function delete_si(id){
	if(confirm('Do you want to Delete this Size?')){
		$.get('delete.php', {'table' : 'tbl_size', 'pk' : 'size_id', 'id' : id}, function(data){
			alert(data);
			location.reload();
		});
	}
}
</script>
<?php include 'include/tableJs.php'; ?>
</body>
</html>