<?php include 'include/header.php';
$noDeal1 = mysql_fetch_object(exec_query("SELECT no FROM tbl_config WHERE type = 'dealsTopOffer'", $con));
$noDeal2 = mysql_fetch_object(exec_query("SELECT no FROM tbl_config WHERE type = 'dealsSuper'", $con));
$imgBanner = mysql_fetch_object(exec_query("SELECT other FROM tbl_config WHERE type = 'dealBanner'", $con));
?>
<style> .low-widd{ width:30% !important; } </style>
<div class="warper container-fluid">
	<div class="page-header"><h1>Promotion <small> Manage Deals</small></h1></div>
    
    <div class="panel panel-default">
        <div class="panel-body">
        	
            <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Deal Banner Image</div>
                    <div class="panel-body">
                        <form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                            <div class="form-group">
                                <label style="padding-right:0px;" class="col-sm-4 control-label">Select Image</label>
                                <div class="col-sm-7">
                                    <input required name="img" type="file" />
                                    <?php if(isset($imgBanner->other) && $imgBanner->other != ''){ ?>
										<img src="<?php echo siteUrl; ?>site_image/promotion/<?php echo $imgBanner->other; ?>" width="200" />
									<?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="action" value="dealBannerImage" />
                                <div class="col-lg-9 col-lg-offset-3">
                                	<button class="btn btn-primary btn-xs" type="submit"> Save Changes! </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
            
        	<div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">No. of TOP OFFER Deals</div>
                    <div class="panel-body">
                        <form method="post" class="form-horizontal" action="admin_action_model.php">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Enter No.</label>
                                <div class="col-sm-7">
                                    <input class="form-control input-sm" required name="no" placeholder="Enter No. of Deals" type="number" min="1" value="<?php echo $noDeal1->no; ?>" />
                                    <!--<small>Maximum Allow 16 Deals</small>-->
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="type" value="dealsTopOffer" />
                                <input type="hidden" name="action" value="config" />
                                <input type="hidden" name="redirect" value="adminDeal.php" />
                                <div class="col-lg-9 col-lg-offset-3">
                                    <?php if($configPerm['add'] || $configPerm['edit']){ ?>
                                        <button class="btn btn-primary btn-xs" type="submit"> Save Changes! </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">No. of SUPER Deals</div>
                    <div class="panel-body">
                        <form method="post" class="form-horizontal" action="admin_action_model.php">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Enter No.</label>
                                <div class="col-sm-7">
                                    <input class="form-control input-sm" required name="no" placeholder="Enter No. of Deals" type="number" min="1" value="<?php echo $noDeal2->no; ?>" />
                                    <!--<small>Maximum Allow 16 Deals</small>-->
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="type" value="dealsSuper" />
                                <input type="hidden" name="action" value="config" />
                                <input type="hidden" name="redirect" value="adminDeal.php" />
                                <div class="col-lg-9 col-lg-offset-3">
                                    <?php if($configPerm['add'] || $configPerm['edit']){ ?>
                                        <button class="btn btn-primary btn-xs" type="submit"> Save Changes! </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
            
        </div>
   	</div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="#" data-column="1" class="toggle-vis btn btn-default btn-sm">Title</a>
            <a href="#" data-column="2" class="toggle-vis btn btn-default btn-sm">Promo code (Min Cart Value)</a>
            <a href="#" data-column="3" class="toggle-vis btn btn-default btn-sm">Apply on</a>
            <a href="#" data-column="4" class="toggle-vis btn btn-default btn-sm">Value</a>
            <a href="#" data-column="5" class="toggle-vis btn btn-default btn-sm">Validity</a>
            <a href="#" data-column="6" class="toggle-vis btn btn-default btn-sm">Created On</a>
            <a href="#" data-column="7" class="toggle-vis btn btn-default btn-sm">Action</a>
        </div>
        <div class="panel-body">
            <?php if($promotionPerm['status']){ ?>
            <div style="margin-bottom:14px;">
                <span><button type="button" class="btn btn-purple btn-xs" onclick="showInSuper('multi');">Show in Super Deals</button></span> | 
                <button type="button" class="btn btn-purple btn-xs" onclick="multiPublish();">Publish</button> <small>Select Product and Press Publish</small>
            </div>
            <?php } ?>
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Value</th>
                        <th>Validity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="chkPro">
                <?php
                $i = 1;
                $query = "
                SELECT * FROM(
                    (SELECT tp.promo_id AS mainPId, tp.title, tp.is_super, tp.promo_type, tp.percent_or_amount, tp.promo_value, tp.start_date, tp.end_date, tp.created_on, tp.is_publish, 0 AS min_cart_value, 0 AS promo_code, 'tbl_promotion' AS ttable, 0 AS ids, 0 AS category_id FROM tbl_promotion tp)
                ) AS temp ORDER BY created_on DESC";
                $rs_c = mysql_query($query, $con);
                while($row_c = mysql_fetch_object($rs_c)){
                    $ct = $row_c->promo_type;
                    $stat = $row_c->is_publish;
                    $isExpire = false;
                    
                    if(strtotime($row_c->end_date) < strtotime(date('c'))){ ?>
                    <!-- add popup start -->
                    <div class="modal fade" id="modal-add<?php echo $i; ?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel">Extend Expiry Date</h4>
                                </div>
                                <div class="modal-body panel-body">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">To </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control col-sm-3 low-widd tdate" data-date-format="YYYY-MM-DD" id="tdate<?php echo $i; ?>" name="tdate" placeholder="To Date" required style="margin-right:5px;" />
                                            <input type="text" class="form-control col-sm-3 low-widd ttime" data-date-format="HH:mm" id="ttime<?php echo $i; ?>" name="ttime" placeholder="To Time" required value="<?php echo date('H:m'); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-info">Save</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <input type="hidden" name="action" value="extendPromotionExpiry" />
                                    <input type="hidden" name="data1" value="<?php echo $row_c->mainPId; ?>" />
                                    <input type="hidden" name="data2" value="<?php echo $row_c->ttable; ?>" />
                                </div>
                            </div>
                            </form>
                        </div>
                    </div><!-- add popup end -->
                    <?php } ?>
                    
                    <?php if($ct != 'allPro'){
                    /* if tbl is promotion, then fetch data from other table */
                        if($row_c->ttable == 'tbl_promotion'){
                            $pro_det_rs = exec_query("SELECT * FROM tbl_promotion_detail WHERE promo_id = '$row_c->mainPId'", $con);
                            $pro_det_row = mysql_fetch_object($pro_det_rs);
                            $idss = $pro_det_row->ids;
                            $catt = $pro_det_row->category_id;
                        }
                        else{
                            $idss = $row_c->ids;
                            $catt = $row_c->category_id;
                        }
                        $isCat = false;
                        if($ct == 'allCat'){
                            $label = 'Categories';	$table = 'tbl_category';	$col = 'category_id';	$colName = 'category_name';
                        }elseif($ct == 'allBrand'){
                            $label = 'Brands';		$table = 'tbl_brand';		$col = 'brand_id';		$colName = 'brand_name';	$isCat = true;
                        }elseif($ct == 'parPro'){
                            $label = 'Products';	$table = 'tbl_product';		$col = 'product_id';	$colName = 'product_name';
                        }
                        $dataName = '';
                        $catdataName = '';
                        
                        if($idss != ''){
                            $dataRs = exec_query("SELECT $colName FROM $table WHERE $col IN ($idss)", $con);
                            while($dataRow = mysql_fetch_object($dataRs)){
                                $dataName .= $dataRow->$colName.', ';
                            }
                        }
                        
                        if($isCat && $catt != ''){
                            $catdataRs = exec_query("SELECT category_name FROM tbl_category WHERE category_id IN ($catt)", $con);
                            while($catdataRow = mysql_fetch_object($catdataRs)){
                                $catdataName .= $catdataRow->category_name.', ';
                            }
                        }
                    ?>
                    
                    
                    <!-- add popup start for detail -->
                    <div class="modal fade" id="detail<?php echo $i; ?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel">Promotion on <?php echo $label; ?></h4>
                                </div>
                                <div class="modal-body panel-body">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <p><?php echo $dataName; ?></p>
                                            <?php if($catdataName != ''){
                                                echo "<hr/> <h4> with Categories </h4> <p>$catdataName<p>";
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div><!-- add popup end for detail -->
                    <?php } ?>
                    
                <tr <?php if($row_c->ttable == 'tbl_promotion'){ echo 'class="warning"'; }
                    if(strtotime($row_c->end_date) < strtotime(date('c'))){ $isExpire = true; echo 'style="opacity:0.37;"'; }
                ?>>
                    <td><?php echo $i; ?> <input type="checkbox" id="chk" name="chk" value="<?php echo $row_c->mainPId.'|'.$row_c->ttable; ?>" /> </td>
                    <td><?php echo $row_c->title; ?></td>
                    <td><?php
                        echo ($row_c->percent_or_amount == 'amount')?'<b>$</b> '.$row_c->promo_value:$row_c->promo_value.' <b>%</b>'; ?>
                    </td>
                    <td><?php
                        echo 'from: '.date('d M, Y h:i A', strtotime($row_c->start_date)).'<br/> to: '.date('d M, Y h:i A', strtotime($row_c->end_date)); ?>
                    </td>
                    <td>
                    <?php if($promotionPerm['status']){ ?>
                    	<?php if($row_c->is_super == 1){ ?>
                    	<button onClick="showInSuper(<?php echo $row_c->mainPId; ?>);" class="btn btn-primary btn-xs">Remove from Super</button>     
                        <?php }if($stat == 0){ ?>
                        <button onClick="publish(<?php echo $row_c->mainPId; ?>, '<?php echo $row_c->ttable; ?>');" class="btn btn-primary btn-xs">Publish</button>
                        <?php }elseif($stat == 1){ ?>
                        <button onClick="unpublish(<?php echo $row_c->mainPId; ?>, '<?php echo $row_c->ttable; ?>');" class="btn btn-primary btn-xs">Unpublish</button>
                    <?php } } ?>
                    <?php if($ct != 'allPro'){ ?>
                        <button class="btn btn-xs" data-target="#detail<?php echo $i; ?>" data-toggle="modal" type="button">Detail</button>
                    <?php } ?>
                    
                    <?php if($promotionPerm['edit']){
                        if($row_c->ttable == 'tbl_promotion'){ ?>
                        <button onClick="window.location='promotionEdit.php?data1=<?php echo $row_c->mainPId; ?>'" class="btn btn-info btn-xs">Edit</button>
                    <?php } } ?>
                    
                    <?php if($promotionPerm['status']){ ?>
                        <button type="button" class="btn btn-danger btn-xs" onClick="delete_co(<?php echo $row_c->mainPId; ?>, '<?php echo $row_c->ttable; ?>');">Delete</button>
                        <?php if($isExpire){ ?>
                        <button class="btn btn-purple btn-xs" data-target="#modal-add<?php echo $i; ?>" data-toggle="modal" type="button">Extend Expiry</button>
                    <?php } } ?>
                    </td>
                </tr>
                <?php
                    $i++;
                } ?>
                </tbody>
            </table>

        </div>
    </div>
            
        </div>
        <!-- Warper Ends Here (working area) -->
        
<?php include 'include/footer.php'; ?>
<script>
function delete_co(id, table){
	if(confirm('Do you want to Delete this Promotion?')){
		pk = (table == 'tbl_promotion') ? 'promo_id' : 'recid';
		$.get('delete.php', {'table' : table, 'pk' : pk, 'id' : id}, function(data){ alert(data); location.reload(); });
	}
}
function publish(id, table){
	pk = (table == 'tbl_promotion') ? 'promo_id' : 'recid' ;
	if(confirm('Do you want to Publish this Promotion?')){
		$.get('change_status.php', {'table' : table, 'pk_column' : pk, 'pk_val' : id, 'up_column' : 'is_publish', 'up_val' : 1}, function(data){
			alert(data); location.reload();
		});
	}
}
function unpublish(id, table){
	pk = (table == 'tbl_promotion') ? 'promo_id' : 'recid' ;
	if(confirm('Do you want to Unpublish this Promotion?')){
		$.get('change_status.php', {'table' : table, 'pk_column' : pk, 'pk_val' : id, 'up_column' : 'is_publish', 'up_val' : 0}, function(data){
			alert(data); location.reload();
		});
	}
}
function multiPublish(){
	var obj = '';
	$('#chkPro input[type=checkbox]:checked').each(function() {
		if(this.value != ''){
			if (obj == ''){ obj = this.value; }
			else{ obj = obj+','+this.value; }
		}
	});
	if(obj != ''){
		if(confirm('Do you want to Publish this Promotions?')){
			$.get('change_status.php', {'table' : 'koiTableNahiHai', 'pk_column' : 'koiColBhiNahiHai', 'pk_val' : obj, 'up_column' : 'is_publish', 'up_val' : 1, 'promotion' : true}, function(data){ alert(data); location.reload(); });
		}
	}
	else{ alert('Please Select atleast one Promotion!'); }
}

function showInSuper(id){
	if(id == 'multi'){
		var obj = '';
		$('#chkPro input[type=checkbox]:checked').each(function() {
			if(this.value != ''){
				if (obj == ''){ obj = this.value; }
				else{ obj = obj+','+this.value; }
			}
		});
		if(obj != ''){
			if(confirm('Do you want to Show this Promotions in Super Deals?')){
				$.get('change_status.php', {'table' : 'tbl_promotion', 'pk_column' : 'promo_id', 'pk_val' : obj, 'up_column' : 'is_super', 'up_val' : 1, 'multi' : true}, function(data){ alert(data); location.reload(); });
			}
		}
		else{ alert('Please Select atleast one Promotion!'); }
	}
	else{
		if(confirm('Do you want to Remove this Promotion from Super Deals?')){
			$.get('change_status.php', {'table' : 'tbl_promotion', 'pk_column' : 'promo_id', 'pk_val' : id, 'up_column' : 'is_super', 'up_val' : 0}, function(data){ alert(data); location.reload(); });
		}
	}
}
</script>
<?php include 'include/tableJs.php'; ?>
<!-- moment -->
<script src="assets/js/moment/moment.js"></script>
<!-- DateTime Picker -->
<script src="assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
<script>
$('.ttime').datetimepicker({pickDate: false});
$('.tdate').datetimepicker({pickTime: false});
</script>
</body>
</html>