<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
        	
            <div class="page-header"><h1>Tags <small> All Tags</small></h1></div>
            <?php if($tagPerm['add']){ ?>
            <button class="btn btn-purple btn-flat" data-target="#modal-add" data-toggle="modal" style="margin: 0px 0px 8px;" type="button">Add New Tag</button>
            <?php } ?>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Title</th>
								<th>Description</th>
                                <th>Banner</th>
                                <th>is For Menu</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						$query = "SELECT * FROM tbl_tag	ORDER BY title";
						$rs = mysqli_query($con, $query);
						while($row = mysqli_fetch_object($rs)){
						?>
							<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
								<td><?php echo $i; ?></td>
								<td><?php echo $row->title; ?></td>
                                <td><?php echo substr($row->description, 0, 100); ?>...</td>
                                <td><?php if($row->is_featured == 1 && $row->banner_thumb != '' && $row->banner_img != ''){ ?>
                                	<img src="../site_image/tag/<?php echo $row->banner_thumb; ?>" width="130" />
								<?php } ?></td>
								<td>
                                	<?php if($row->is_menu == 0){ ?>
                                    <a href="javascript:void(0);" onClick="addMenu(<?php echo $row->recid; ?>);"> <img src="../images/gstar.png" width="21" /> </a>
									<?php }elseif($row->is_menu == 1){ ?>
                                    <a href="javascript:void(0);" onClick="remMenu(<?php echo $row->recid;?>);"> <img src="../images/ystar.png" /> </a>
									<?php } ?>
                                </td>
                                <td>
                                <?php if($tagPerm['edit']){ ?>
									<button data-target="#modal-edit<?php echo $row->recid; ?>" data-toggle="modal" class="btn btn-info btn-xs">Edit</button>
                                <?php }if($tagPerm['status']){ ?>
									<button type="button" class="btn btn-danger btn-xs" onClick="delete_t(<?php echo $row->recid; ?>);">Delete</button>
                                <?php } ?>
                                    
                                    <!-- edit popup start -->
                                    <div class="modal fade" id="modal-edit<?php echo $row->recid; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Edit Tag</h4>
                                                </div>
                                                <div class="modal-body panel-body">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Title</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control" required name="title" placeholder="Title" type="text" value="<?php echo $row->title; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Description</label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control" name="desc" placeholder="Description" cols="" rows="3" required style="height:260px;" ><?php echo $row->description; ?></textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Show in Menu?</label>
                                                        <div class="col-sm-8">
                                                            <div class="switch-button showcase-switch-button control-label sm">
                                                                <input id="switch-buttza<?php echo $i; ?>" name="menu" value="1" type="checkbox" <?php getChecked(1, $row->is_menu); ?> >
                                                                <label for="switch-buttza<?php echo $i; ?>"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Make it Featured?</label>
                                                        <div class="col-sm-8">
                                                            <div class="switch-button showcase-switch-button control-label sm">
                                                                <input id="switch-buttzaz<?php echo $i; ?>" name="featured" value="1" type="checkbox" <?php getChecked(1, $row->is_featured); ?>>
                                                                <label for="switch-buttzaz<?php echo $i; ?>"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group imgCon" >
                                                        <label class="col-sm-3 control-label">Banner Image</label>
                                                        <div class="col-sm-8">
                                                            <input name="img" type="file" >
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save Changes !</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <input type="hidden" name="action" value="tagEdit" />
                                                    <input type="hidden" name="data1" value="<?php echo $row->recid; ?>" />
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div><!-- edit popup end -->
                                    
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
	if(confirm('Do you want to Delete this Tag?')){
		$.get('delete.php', {'table' : 'tbl_tag', 'pk' : 'recid', 'id' : id}, function(data){ alert(data);	location.reload(); });
	}
}
function addFea(id){
	if(confirm('Do you Make Featured this Tag?')){
		$.get('change_status.php', {'table' : 'tbl_tag', 'pk_column' : 'recid', 'pk_val' : id, 'up_column' : 'is_featured', 'up_val' : 1}, function(data){
			alert(data); location.reload();
		});
	}
}
function remFea(id){
	if(confirm('Do you Remove Featured from this Tag?')){
		$.get('change_status.php', {'table' : 'tbl_tag', 'pk_column' : 'recid', 'pk_val' : id, 'up_column' : 'is_featured', 'up_val' : 0}, function(data){
			alert(data); location.reload();
		});
	}
}

function addMenu(id){
	if(confirm('Do you want to Show it in Menu?')){
		$.get('change_status.php', {'table' : 'tbl_tag', 'pk_column' : 'recid', 'pk_val' : id, 'up_column' : 'is_menu', 'up_val' : 1}, function(data){
			alert(data); location.reload();
		});
	}
}
function remMenu(id){
	if(confirm('Do you want to remove it from Menu?')){
		$.get('change_status.php', {'table' : 'tbl_tag', 'pk_column' : 'recid', 'pk_val' : id, 'up_column' : 'is_menu', 'up_val' : 0}, function(data){
			alert(data); location.reload();
		});
	}
}

/* for featured */
function showHide(){
	if($('.imgCon').css('display') == 'none'){ $('.imgCon').css('display', 'block'); }
	else{ $('.imgCon').css('display', 'none'); }
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
				<h4 class="modal-title" id="myModalLabel">Add New Tag</h4>
			</div>
			<div class="modal-body panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Title</label>
                    <div class="col-sm-8">
                    	<input class="form-control" required name="title" placeholder="Title" type="text" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-8">
                   		<textarea class="form-control" name="desc" placeholder="Description" cols="" rows="3" required style="height:300px;" ></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Show in Menu?</label>
                    <div class="col-sm-8">
                        <div class="switch-button showcase-switch-button control-label sm">
                            <input id="switch-buttzaa" name="menu" value="1" type="checkbox" >
                            <label for="switch-buttzaa"></label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Make it Featured?</label>
                    <div class="col-sm-8">
                        <div class="switch-button showcase-switch-button control-label sm">
                            <input id="switch-buttza" name="featured" value="1" type="checkbox" onchange="showHide();">
                            <label for="switch-buttza"></label>
                        </div>
                    </div>
                </div>
                <div class="form-group imgCon" style="display:none;">
                    <label class="col-sm-3 control-label">Banner Image</label>
                    <div class="col-sm-8">
                        <input name="img" type="file" >
                    </div>
                </div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Add Now !</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input type="hidden" name="action" value="tagAdd" />
			</div>
		</div>
		</form>
	</div>
</div><!-- add popup end -->
</body>
</html>