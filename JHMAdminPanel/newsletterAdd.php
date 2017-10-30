<?php include 'include/header.php';
/* email templkatre */
$rsEmail = exec_query("SELECT * FROM tbl_email_template WHERE type = 'newsletter'", $con);
$rowEmail = mysql_fetch_object($rsEmail);
$content = $rowEmail->content;
$contentHTML = html_entity_decode($content);
$subject = '';

if(isset($_GET['data1']) && $_GET['data1'] != ''){
	$id = $_GET['data1'];
	$rs1 = exec_query("SELECT * FROM tbl_newsletter WHERE recid = '$id'", $con);
	$row1 = mysql_fetch_object($rs1);
	$content = html_entity_decode($row1->content);
	$subject = $row1->subject;
}
else{
	$content = $contentHTML;
}
?>
<style>
#cke_1_contents{ height:420px !important; }
.modal-dialog{ width:900px !important; }
.tdd{ width: 25%; margin: 5px; padding: 5px; border: 1px solid rgb(236, 236, 236); }
</style>
<div class="warper container-fluid">
    <div class="page-header"><h1>Manage Newsletters</h1></div>
    
    <div class="row">
        <ul role="tablist" class="nav nav-tabs" id="myTab">
            <li class="active"><a data-toggle="tab" role="tab" href="#alltemp">All Newsletters</a></li>
            <?php if($newsletterPerm['add']){ ?>
            	<li><a data-toggle="tab" role="tab" href="#etempl">Send Newsletter</a></li>
            <?php } ?>
        </ul>

        <div class="tab-content" id="myTabContent">
            
            <div id="etempl" class="tab-pane tabs-up fade panel panel-default">
            	<form method="post" action="admin_action_model.php">
                <div class="panel-body">
                
                   	<div class="form-group" style="margin-bottom: 57px;">
	                    <label class="col-sm-2 control-label">Newsletter Subject</label>
    	                <div class="col-sm-4">
        	            	<input class="form-control" required name="subject" placeholder="Newsletter Subject" type="text" value="<?php echo $subject; ?>" />
            	        </div>
                	</div>
                    <div style="clear:both;"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Select Newsletter</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="nid" required>
                                <option value="">- SELECT NEWSLETTER -</option>
                                <?php $rs1 = exec_query("SELECT * FROM tbl_email_template WHERE type = 'newsletter'", $con);
                                while($row1 = mysql_fetch_object($rs1)){
                                    echo '<option value="'.$row1->recid.'">'.$row1->title.' ('.date('d M, Y h:i A', strtotime($row1->datetime)).')</option>';
                                }?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
						<div class="col-lg-9 col-lg-offset-3"><br/>
							<button class="btn btn-primary" type="button" data-target="#modal-add" data-toggle="modal"> Select Users ! </button>
                            
                            <!-- add popup start -->
                            <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Select Email Ids
                                            	<br/>
                                            	<button type="button" onclick="chkAll();" class="btn btn-primary btn-xs" style="float:left;">Select All</button>
                                                
                                                <input class="form-control input-sm" id="myEmail" name="email" placeholder="Email Id" type="text" style="float: left; margin: 0 10px 0 150px; width: 188px;" />
                                                <button type="button" onclick="searchNow(myEmail.value);" class="btn btn-primary btn-xs">Search</button>
                                            </h4>
                                        </div>
                                        <div class="modal-body panel-body">
                                            <div class="form-group">
                                            <table align="center" id="putCols">
                                            <tr>
                                            <?php $ii = 1;
                                            $rs = exec_query("SELECT email FROM tbl_user_newsletter", $con);
											while($row = mysql_fetch_object($rs)){ ?>
												
                                                <td class="tdd">
                                                	<input type="checkbox" name="email[]" value="<?php echo $row->email; ?>" />
                                                    <?php echo $row->email; ?>
                                                </td>
                                                
											<?php echo (($ii % 4)==0)?'</tr><tr>':''; $ii++; } ?>
                                            </tr>
                                            </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Send Now !</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <input type="hidden" name="action" value="addNewsletter" />
                                        </div>
                                    </div>
                                </div>
                            </div><!-- add popup end -->
                            
                            
                            
						</div>
					</div>
                </div>
                </form>
            </div>
            
            <div id="alltemp" class="tab-pane tabs-up fade in active panel panel-default">
            	<div class="panel-body">
                	
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Title</th>
								<th>Created on</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							$query = "SELECT * FROM tbl_newsletter ORDER BY recid DESC";
							$rs = mysql_query($query, $con);
							while($row = mysql_fetch_object($rs)){
							?>
								<tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
									<td><?php echo $i; ?></td>
									<td><?php echo substr($row->subject, 0, 200); ?>...</td>
									<td><?php echo date('d M, Y h:i A', strtotime($row->datetime)); ?></td>
									<td>
                                    <?php if($newsletterPerm['add'] || $newsletterPerm['edit']){ ?>
									<a target="_blank" href="userNewsletterPreview.php?data1=<?php echo $row->recid; ?>" class="btn btn-info btn-xs">Preview</a>
                                    <?php }if($newsletterPerm['status']){ ?>
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
</div>
<!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php'; ?>
<?php include 'include/formJs.php'; ?>
<!-- Data Table -->
<script src="assets/js/plugins/datatables/jquery.dataTables.js"></script>
<script src="assets/js/plugins/datatables/DT_bootstrap.js"></script>
<script src="assets/js/plugins/datatables/jquery.dataTables-conf.js"></script>

<script>
$('#ckeditor').ckeditor();
function delete_t(id){
	if(confirm('Do you want to Delete this Email Template?')){
		$.get('delete.php', {'table' : 'tbl_newsletter', 'pk' : 'recid', 'id' : id}, function(data){ alert(data); location.reload(); });
	}
}
function chkAll(){
	$('#modal-add input[type=checkbox]').each(function() {
		this.checked = true;
	});
}
function searchNow(email){
	if(email != ''){
		$.get('adminAjax.php', {'action' : 'getNewsletterEmailIds', 'email' : email}, function(data){
			document.getElementById('putCols').innerHTML = data;
		});
	}
	else{ alert('Invalid Email Id.'); }
}
</script>
</body>
</html>
<?php /*
<form method="post" action="admin_action_model.php">
<div class="panel-body">

	<div class="form-group">
		<label class="col-sm-2 control-label">Newsletter Subject</label>
		<div class="col-sm-8">
			<input class="form-control" required name="subject" placeholder="Newsletter Subject" type="text" style="margin-bottom:15px;" value="<?php echo $subject; ?>" />
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-10">
			<textarea class="form-control" id="ckeditor" name="content" placeholder="Description" cols="100" rows="10" ><?php echo $content; ?></textarea>
			<input type="hidden" name="action" value="addNewsletter" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-9 col-lg-offset-3"><br/>
			<button class="btn btn-primary" type="button" data-target="#modal-add" data-toggle="modal"> Select Users ! </button>
			
			
			<!-- add popup start -->
			<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title" id="myModalLabel">Select Email Ids</h4>
						</div>
						<div class="modal-body panel-body">
							<div class="form-group">
							<table align="center">
							<tr>
							<?php $ii = 1;
							$rs = exec_query("SELECT email FROM tbl_user_newsletter", $con);
							while($row = mysql_fetch_object($rs)){ ?>
								
								<td class="tdd">
									<input type="checkbox" name="email[]" value="<?php echo $row->email; ?>" />
									<?php echo $row->email; ?>
								</td>
								
							<?php echo (($ii % 4)==0)?'</tr><tr>':''; $ii++; } ?>
							</tr>
							</table>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">Send Now !</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div><!-- add popup end -->
			
			
			
		</div>
	</div>
</div>
</form>
*/ ?>