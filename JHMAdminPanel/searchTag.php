<?php include 'include/header.php'; ?>
<div class="warper container-fluid">
	<div class="page-header"><h1>Manage Search Tags </h1></div>
    <?php if($stagPerm['add']){ ?>
    <button class="btn btn-purple btn-flat" data-target="#modal-add" data-toggle="modal" style="margin: 0px 0px 8px;" type="button">Add New Search Tag</button>
    <?php } ?>
    
    <div class="row">
        <ul role="tablist" class="nav nav-tabs" id="myTab">
            <li class="active"><a data-toggle="tab" role="tab" href="#atag">Admin Controlled Tags</a></li>
            <li><a data-toggle="tab" role="tab" href="#utag">User Search Tags</a></li>
        </ul>
        
        <div class="tab-content" id="myTabContent">
        
            <div id="atag" class="tab-pane tabs-up fade in active panel panel-default">
                <!--<form method="post" action="admin_action_model.php">-->
                <div class="panel panel-default">
				<div class="panel-body">
                <!--<button type="button" onClick="getIdsOrder();">Save Order</button>-->
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered myTable" id="toggleColumn-datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <!--<th>Order No.</th>-->
                            <th>Search Tag</th>
                            <th>Added On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $query = "SELECT * FROM tbl_search_admin ORDER BY order_no";
                    $rs = mysql_query($query, $con);
                    while($row = mysql_fetch_object($rs)){
                    ?>
                        <tr <?php //if($i%2 == 0){ echo 'class="info"'; } ?>>
                            <td class="index" id="<?php echo $row->recid; ?>"><?php echo $i; ?></td>
                            <?php /*<td>
                            	<input type="number" name="order" value="<?php echo $row->order_no; ?>" style="width:40px;" />
                                <input type="hidden" name="recid" value="<?php echo $row->recid; ?>" />
                            </td>*/ ?>
                            <td><?php echo $row->keyword; ?></td>
                            <td><?php echo date('d M, Y', strtotime($row->date)); ?></td>
                            <td>
                            <?php if($stagPerm['status']){
                            	if($row->is_featured == 0){ ?>
                                    <button onClick="addFea(<?php echo $row->recid; ?>);" class="btn btn-info btn-xs">Add Featured</button>
								<?php }elseif($row->is_featured == 1){ ?>
                                    <button onClick="remFea(<?php echo $row->recid; ?>);" class="btn btn-info btn-xs">Remove Featured</button>
								<?php } ?>
                                <button type="button" class="btn btn-danger btn-xs" onClick="delete_t(<?php echo $row->recid; ?>, 'tbl_search_admin');">Delete</button>
                            <?php }
                                
                          	if($stagPerm['edit']){ ?>
                                <button data-target="#modal-edit<?php echo $row->recid; ?>" data-toggle="modal" class="btn btn-info btn-xs">Edit</button>
                            <?php } ?>
                                
                                <!-- edit popup start -->
                                <div class="modal fade" id="modal-edit<?php echo $row->recid; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Edit Search Tag</h4>
                                            </div>
                                            <div class="modal-body panel-body">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Keyword</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" required name="keyword" placeholder="Keyword" type="text" value="<?php echo $row->keyword; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save Changes !</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <input type="hidden" name="action" value="adminSearchTagEdit" />
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
            
            <!--  0000-0-----------------------------------------------------------------------------0-0-00-00-0-00- -->
            
            <div id="utag" class="tab-pane tabs-up fade panel panel-default">
				<div class="panel panel-default">
				<div class="panel-body">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Search Tag</th>
                            <th>Occurence Count</th>
                            <th>Last Searched</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                    $i = 1;
                    $query = "SELECT tsh.recid, tsh.date, tsh.keyword, (SELECT COUNT( recid ) AS count FROM tbl_search_history WHERE tbl_search_history.keyword = tsh.keyword) AS count, '1' AS type FROM tbl_search_history tsh WHERE keyword != '' GROUP BY tsh.keyword ORDER BY count DESC";
                    $rs = mysql_query($query, $con);
                    while($row = mysql_fetch_object($rs)){
                    ?>
                    <tbody>
                        <tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $row->keyword; ?></td>
                            <td><?php echo $row->count; ?></td>
                            <td><?php echo date('d M, Y', strtotime($row->date)); ?></td>
                            <td>
                            <?php if($stagPerm['status']){ ?>
                                <button onclick="moveTag(<?php echo $row->recid; ?>)" class="btn btn-info btn-xs">Move in Admin Controlled Tag</button>
                                <button type="button" class="btn btn-danger btn-xs" onClick="delete_t(<?php echo $row->recid; ?>, 'tbl_search_history');">Delete</button>
                            <?php } ?>
                            </td>
                        </tr>
                    </tbody>
                    <?php $i++; } ?>
                </table>
                </div>
                </div>
			</div>
            
		</div>
   	</div>
</div>
        <!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php'; ?>
<script>
function delete_t(id, tab){
	if(confirm('Do you want to Delete this Tag?')){
		$.get('delete.php', {'table' : tab, 'pk' : 'recid', 'id' : id}, function(data){ alert(data); location.reload(); });
	}
}

function moveTag(id){
	if(confirm('Do you want to Move this in Admin Controlled Tag?')){
		$.get('adminAjax.php', {'action':'moveSearchTagToAdmin', 'data1':id}, function(data){ alert(data); location.reload(); });
	}
}

function addFea(id){
	if(confirm('Do you Make Featured this Tag?')){
		$.get('change_status.php', {'table' : 'tbl_search_admin', 'pk_column' : 'recid', 'pk_val' : id, 'up_column' : 'is_featured', 'up_val' : 1}, function(data){ alert(data); location.reload(); });
	}
}
function remFea(id){
	if(confirm('Do you Remove Featured from this Tag?')){
		$.get('change_status.php', {'table' : 'tbl_search_admin', 'pk_column' : 'recid', 'pk_val' : id, 'up_column' : 'is_featured', 'up_val' : 0}, function(data){ alert(data); location.reload(); });
	}
}
function getIdsOrder(ids, order){
	/*var order = '', ids = '';
	
	$('.myTable input[name=order]').each(function() {
		orderVal = 0;
		if(this.value != ''){ orderVal = this.value; }
		else{ orderVal = 0; }
		if (order == ''){ order = orderVal; }
		else{ order = order+','+orderVal; }
	});
	
	$('.myTable input[name=recid]').each(function() {
		idVal = 0;
		if(this.value != ''){ idVal = this.value; }
		else{ idVal = 0; }
		if (ids == ''){ ids = idVal; }
		else{ ids = ids+','+idVal; }
	});*/
	
	if(order != '' && ids != ''){
		$.get('adminAjax.php', {'action':'setSearchTagOrder', 'data1':ids, 'data2':order}, function(data){ alert(data); });
	}
	else{ alert('Oops!!! Something went wrong,'); }
}
</script>
<?php include 'include/tableJs.php'; ?>
<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">-->
<script src="http://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<script>
/////////// draggable
var fixHelperModified = function(e, tr) {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index) {
        $(this).width($originals.eq(index).width())
    });
    return $helper;
},
    updateIndex = function(e, ui) {
		var order = '', ids = '';
        $('td.index', ui.item.parent()).each(function (i) {
			var j, k;
            $(this).html(i + 1);
			j = i + 1;
			k = this.id;
			// for order
			if (order == ''){ order = j; }
			else{ order = order+','+j; }
			
			if (ids == ''){ ids = k; }
			else{ ids = ids+','+k; }
			//alert(i+1+'---'+this.id);
        });
		getIdsOrder(ids, order);
    };

$(".myTable tbody").sortable({
    helper: fixHelperModified,
    stop: updateIndex
}).disableSelection();
</script>
<!-- add popup start -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Add New Search Tag</h4>
			</div>
			<div class="modal-body panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Keyword</label>
                    <div class="col-sm-8">
                    	<input class="form-control" required name="keyword" placeholder="Keyword" type="text" />
                    </div>
                </div>
                
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Add Now !</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input type="hidden" name="action" value="adminSearchTagAdd" />
			</div>
		</div>
		</form>
	</div>
</div><!-- add popup end -->
</body>
</html>