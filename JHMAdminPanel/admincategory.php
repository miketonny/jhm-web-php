<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
            <div class="page-header"><h1>Category <small>All Categories</small></h1></div>
			<div class="panel panel-default">
				<div class="panel-body">
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Category Name</th>
								<th>Parent Sub Categories</th>
								<th>Parent Categories</th>
								<th>Date</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						$query = "SELECT tc.*,
						(SELECT category_name FROM tbl_category WHERE tbl_category.category_id = tc.parent_id) AS parentCat,
						(SELECT category_name FROM tbl_category WHERE tbl_category.category_id = tc.superparent_id) AS superParentCat
						FROM tbl_category tc ORDER BY tc.category_name";
						$rs_cat = mysql_query($query, $con);
						while($row_cat = mysql_fetch_object($rs_cat)){
						?>
							<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
								<td><?php echo $i; ?></td>
								<td><?php echo $row_cat->category_name; ?></td>
								<td><?php echo (isset($row_cat->parentCat) && $row_cat->parentCat != '')?$row_cat->parentCat:'-'; ?></td>
								<td><?php echo (isset($row_cat->superParentCat) && $row_cat->superParentCat != '')?$row_cat->superParentCat:'-'; ?></td>
								<td><?php
									echo 'Created - '.date('d M, Y', strtotime($row_cat->created_on)).'<br/>';
									echo ($row_cat->modified_on != '0000-00-00 00:00:00')? 'Modified - '.date('d M, Y', strtotime($row_cat->modified_on)):'';
								?></td>
								<td><?php /* if($row_cat->is_featured == 0){ ?>
									<button type="button" class="btn btn-purple btn-xs" onClick="addFea(<?php echo $row_cat->category_id; ?>);">Make Featured</button>
									<?php }elseif($row_cat->is_featured == 1){ ?>
									<button type="button" class="btn btn-primary btn-xs" onClick="remFea(<?php echo $row_cat->category_id; ?>);">Remove Featured</button>
									<?php }*/ ?>
									<?php if($categoryPerm['edit']){ ?>
                                    <button onClick="window.location='categoryEdit.php?data1=<?php echo $row_cat->category_id; ?>&data2=<?php echo $row_cat->slug; ?>'" class="btn btn-info btn-xs"><span class="icomoon-icon-pencil-2 white"></span>Edit</button>
                                   
                                    <button type="button" class="btn btn-primary btn-xs" onClick="window.location='productInCategory.php?data1=<?php echo $row_cat->category_id; ?>';">Product in it</button>
                                    <?php }if($categoryPerm['status']){ ?>
									<button type="button" class="btn btn-danger btn-xs" onClick="delete_cat(<?php echo $row_cat->category_id; ?>);">Delete</button>
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
function delete_cat(id){
	if(confirm('Do you want to Delete this Category?')){
		$.get('delete.php', {'table' : 'tbl_category', 'pk' : 'category_id', 'id' : id}, function(data){ alert(data); location.reload(); });
	}
}
function addFea(id){
	if(confirm('Do you Make Featured this Category?')){
		$.get('change_status.php', {'table' : 'tbl_category', 'pk_column' : 'category_id', 'pk_val' : id, 'up_column' : 'is_featured', 'up_val' : 1}, function(data){
			alert(data); location.reload();
		});
	}
}
function remFea(id){
	if(confirm('Do you Remove Featured from this Category?')){
		$.get('change_status.php', {'table' : 'tbl_category', 'pk_column' : 'category_id', 'pk_val' : id, 'up_column' : 'is_featured', 'up_val' : 0}, function(data){
			alert(data); location.reload();
		});
	}
}
</script>
<?php include 'include/tableJs.php'; ?>
</body>
</html>
