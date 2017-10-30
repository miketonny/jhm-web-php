<?php include 'include/header.php'; ?>
<style> #cke_1_contents{ height:400px !important; } </style>
<div class="warper container-fluid">
    <div class="page-header"><h1>Email Templates <small> Add New Template</small></h1></div>
    
    <div class="dropdown tooltip-btn" data-toggle="tooltip" data-placement="bottom" style="text-align:right;" id="openIt">
    	<a href="#" data-toggle="dropdown"><span class="h1"><i class="glyphicon glyphicon-question-sign"></i></span></a>
        <div class="dropdown-menu lg pull-right arrow panel panel-default arrow-top-right">
            <div class="panel-heading">Pre Defined Words (Do not change!!!)</div>
            <div class="panel-body">
            	<ul id="putdata"></ul>
            </div>
        </div>
    </div>
    
    <div style="clear:both;"></div>
    
    <div class="panel panel-default">
        
        <form method="post" action="admin_action_model.php">
            <div class="panel-body">
                
                <div class="form-group" style="margin-bottom: 50px;">
                    <label class="col-sm-2 control-label">Template Type</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="type" required onChange="getWord(this.value);">
                            <option value="">- SELECT TEMPLATE TYPE -</option>
                            <?php $si_rs = exec_query("SELECT DISTINCT(type) FROM tbl_email_template ORDER BY type", $con);
                            while($si_row = mysql_fetch_object($si_rs)){
                                echo '<option>'.$si_row->type.'</option>';
                            }?>
                        </select>
                        <br/>
                    </div>
                </div>
                <div style="clear:both;"></div>
                <div class="form-group" style="margin-bottom: 50px;">
                    <label class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-4">
                        <input class="form-control" required name="title" placeholder="Title" type="text" />
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-10">
                        <textarea class="form-control" id="ckeditor" name="content" placeholder="Description" cols="100" rows="10" ></textarea>
                        <input type="hidden" name="action" value="addEmailTemplate" />
                        <input type="hidden" name="data1" value="<?php echo $row->recid; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-9 col-lg-offset-3">
                        <button class="btn btn-primary" type="submit" style="margin-top:17px;"> Add Now! </button>
                    </div>
                </div>
                
            </div>
        </form>
        
    </div>
</div>
<!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php'; ?>
<?php include 'include/formJs.php'; ?>
<script>
$('#ckeditor').ckeditor();
function getWord(type){
	var data;
	if(type == 'registration'){
		data = '<li><b>{jhm : username}</b> : Username</li> <li><b>{jhm : email}</b> : Email Address</li>';
	}else if(type == 'promocode'){
		data = '<li><b>{jhm : title}</b> : Promocode Title</li> <li><b>{jhm : code}</b> : Promocode</li> <li><b>{jhm : products}</b> : Products</li>';
	}else if(type == 'resetPassword'){
		data = '<li><b>{jhm : username}</b> : Username</li> <li><b>{jhm : email}</b> : Email Address</li> <li><b>{jhm : link}</b> : Password Reset Link</li>';
	}else if(type == 'orderConfirm'){
		data = '<li><b>{jhm : username}</b> : Username</li> <li><b>{jhm : orderNo}</b> : Order Number</li> <li><b>{jhm : product}</b> : Product</li> <li><b>{jhm : price}</b> : Price</li> <li><b>{jhm : qty}</b> : Qty</li> <li><b>{jhm : delDate}</b> : Delivery Date</li> <li><b>{jhm : delAddress}</b> : Delivery Address</li>';
	}else if(type == 'newsletter'){
		data = '';
	}else if(type == 'promotion'){
		data = '<li><b>{jhm : promotion_title}</b> : Promotion Title</li> <li><b>{jhm : promotion_detail}</b> : Promotion Detail</li>';
	}
	$('#putdata').html(data);
	$('#openIt').addClass(' dropdown tooltip-btn open ');
}
</script>
</body>
</html>