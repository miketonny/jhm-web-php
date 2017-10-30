<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Brand <small>All Brands</small></h1></div>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Brand</th>
								<!--<th>Category</th>-->
								<th>Logo</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							$query = "SELECT tbl_brand.*, tbl_category.category_name FROM tbl_brand LEFT JOIN tbl_category ON tbl_category.category_id = tbl_brand.category_id ORDER BY brand_name";
							$rs_br = mysql_query($query, $con);
							while($row_br = mysql_fetch_object($rs_br)){ $img = $row_br->brand_img;
							?>
								<tr <?php if($img == ''){ echo 'class="danger"'; } ?>>
									<td><?php echo $i; ?></td>
									<td><?php echo $row_br->brand_name; ?></td>
									<!--<td><?php //echo $row_br->category_name; ?></td>-->
									<td align="center"><?php
										echo ($img != '')?'<img src="../site_image/brand_logo/'.$img.'" height="45" />':'No Image!'; ?>
									</td>
									<td><?php if($brandPerm['status']){ ?>
										<button onClick="window.location='brandColorEdit.php?data1=<?php echo $row_br->brand_id; ?>&data2=<?php echo $row_br->slug; ?>'" class="btn btn-primary btn-xs">Manage Color</button>
										<?php }if($brandPerm['edit']){ ?>
                                        <button onClick="window.location='brandEdit.php?data1=<?php echo $row_br->brand_id; ?>&data2=<?php echo $row_br->slug; ?>'" class="btn btn-info btn-xs">Edit</button>
                                        <?php }if($brandPerm['status']){ ?>
										<button type="button" class="btn btn-danger btn-xs" onClick="delete_br(<?php echo $row_br->brand_id; ?>);">Delete</button>
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
function delete_br(id){
	if(confirm('Do you want to Delete this Brand?')){
		$.get('delete.php', {'table' : 'tbl_brand', 'pk' : 'brand_id', 'id' : id}, function(data){
			alert(data);
			location.reload();
		});
	}
}
</script>
<?php include 'include/tableJs.php'; ?>
</body>
</html>