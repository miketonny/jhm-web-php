<?php include 'include/header.php';
chkParam($_GET['data1'], 'promoCodeAdd.php');
$id = $_GET['data1'];
?>
<style>#cke_1_contents{ height:420px !important; }</style>

        <div class="warper container-fluid">
            <div class="page-header"><h1>Promotion <small> Add Email Ids for Promo Code</small></h1></div>
        	<div class="row">
            	<form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                
                <div class="col-md-10">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Registered Users Email</div>
                        <div class="panel-body">
                            <div class="form-group">
                            	
								<div class="form-group" style="margin:6px auto;">
                                    <div class="col-sm-5">
                                        <select class="form-control input-sm" name="promo" onchange="getEmails(this.value);">
                                            <option value="">- SELECT USER GROUP -</option>
                                            <option value="all">All Users</option>
                                            <option value="top10">Top 10 Users</option>
											<option value="nonact1">Non Active User from 1 Month</option>
											<option value="mostact">Most Active Users</option>
                                        </select>
                                    </div>
                                </div>
								
                                <div class="col-sm-12">
                                	<small>(You can also add more "comma separated" email addresses)</small>
                                    <textarea class="form-control" name="userEmail" placeholder="Email Addresses" style="height:150px; font-size:12px;" id="emailText" required ></textarea>
                                </div>
                                <div style="clear:both;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-10">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Edit Email Content</div>
                        <div class="panel-body">
                            
                            <div class="form-group">
                            	
								<div class="col-sm-12">
                                    <textarea class="form-control" name="content" required id="ckeditor"><?php if(isset($_SESSION['emailStr'])){ echo $_SESSION['emailStr']; } ?></textarea>
                                </div>
                                <div style="clear:both;"></div>
                            
                                <div style="text-align: center; margin:13px 0px 0px 15px;">
                                    <input type="hidden" name="action" value="promoCodeAddEmail" />
                                    <button class="btn btn-primary" type="submit"> Submit ! </button>
                                    <input type="hidden" name="data1" value="<?php echo $id; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                </form>
            </div>
        </div>
        <!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php';
include 'include/formJs.php'; ?>
<?php $emailArr = array();
$email_rs = exec_query("SELECT email FROM tbl_user", $con);
while($email_row = mysql_fetch_object($email_rs)){ $emailArr[] = $email_row->email; }
$email = implode(',', $emailArr);
?>
<script>
$('#ckeditor').ckeditor();
function getEmails(val){
	if(val == 'all'){
		document.getElementById('emailText').value = '<?php echo $email; ?>';
	}
}
</script>
</body>
</html>