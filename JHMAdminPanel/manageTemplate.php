<?php include 'include/header.php'; ?>
<div class="warper container-fluid">
    <div class="page-header"><h1>Email Templates <small> All Templates</small> </h1>
    	<?php if($emailTempPerm['add']){ ?>
	    	<button class="btn btn-info" type="button" onclick="window.location='mailTemplateAdd.php'">Add New Template</button>
        <?php } ?>
    </div>
    	<div class="panel panel-default">
  			<div class="panel-body">
        	
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Type</th>
                            <!--<th>Content</th>-->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $query = "SELECT * FROM tbl_email_template ORDER BY type";
                    $rs = mysql_query($query, $con);
                    while($row = mysql_fetch_object($rs)){
                    ?>
                        <tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $row->title; ?></td>
                            <td><?php echo $row->type; ?></td>
                            <!--<td><?php //echo substr(strip_tags($row->content, ''), 0, 200); ?>...</td>-->
                            <td>
                                <a href="mailTemplatePreview.php?data1=<?php echo $row->recid; ?>" target="_blank" class="btn btn-purple btn-xs">Preview</a>
                                <?php if($emailTempPerm['edit']){ ?>
                                	<button onClick="window.location='mailTemplateEdit.php?data1=<?php echo $row->recid; ?>'" class="btn btn-info btn-xs">Edit</button>
                                <?php }if($emailTempPerm['status']){ ?>
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
</div>
<!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php'; ?>
<?php include 'include/formJs.php'; ?>
<!-- Data Table -->
<script src="assets/js/plugins/datatables/jquery.dataTables.js"></script>
<script src="assets/js/plugins/datatables/DT_bootstrap.js"></script>
<script src="assets/js/plugins/datatables/jquery.dataTables-conf.js"></script>

<script>
function redirectMe(type){
	if(type != ''){
		window.location.href = 'manageTemplate.php?typeTemp='+type;
	}
}
$('#ckeditor, #ckeditor1, #ckeditor2, #ckeditor3, #ckeditor4').ckeditor();
function delete_t(id){
	if(confirm('Do you want to Delete this Email Template?')){
		$.get('delete.php', {'table' : 'tbl_email_template', 'pk' : 'recid', 'id' : id}, function(data){ alert(data); location.reload(); });
	}
}
</script>
</body>
</html>