<?php include "include/new_top.php";?>
<?php include "include/new_header.php";?>
<?php if (!isset($_SESSION['user']) || $_SESSION['user'] == '' || empty($_SESSION['user'])) {redirect(siteUrl);}
$userId = $_SESSION['user'];
?>

<section class="block-pt5">
    <div class="container">

	<div  class="dashboard-holder">

        <div class="col-sm-12">
    	<h1 class="mainHeading">Product Order </h1>
            </div>
           <div class="col-md-3 col-sm-4">
      	<?php include 'include/userNavigation.php';?>
           </div>

<div class="col-md-9 col-sm-8">
                <div class="clr"> </div>

<div id="userpages">
                    <h2> My Orders </h2>
                    <ul class="myorders">
					<?php $i = 1;
$cdate = date('Y-m-d H:i:s');
$query = "SELECT ord.*, tu.email, CONCAT_WS(' ', tu.first_name, tu.last_name) AS uname,
							CONCAT_WS(' ', ord.od_shipping_first_name, ord.od_shipping_last_name) AS shipuname,
							(SELECT COUNT(toi.order_item_id) FROM tbl_order_item toi WHERE toi.order_id = ord.order_id) AS items
							FROM tbl_order ord
							LEFT JOIN tbl_user tu ON tu.user_id = ord.user_id
							WHERE ord.user_id = '$userId'
							ORDER BY ord.order_id DESC";
$rs = mysqli_query($con, $query);
while ($row = mysqli_fetch_object($rs)) {
	if ($row->items == 0) {
		continue;
	}
	$isreturn = false;
	$orderId = $row->order_id;
	//check for backorder status
	$boQuery = "SELECT (CASE toi.isbackOrd WHEN 1 THEN 'Backorder' ELSE '' END) AS backOrd FROM tbl_order_item toi WHERE toi.order_id = '$orderId'";
	$boStats = mysqli_fetch_object(mysqli_query($con, $boQuery));
	$status = $row->status;
	$orderNo = getOrderId($orderId, $con);
	$oDate = $row->od_date;
	$oDate1Month = date('Y-m-d H:i:s', strtotime('+30 days', strtotime($oDate)));
	if ($oDate1Month > $cdate && $status != 3) {$isreturn = true;}
	?>
                    	<li>
                        	<span class="orderspan">
                            	<?php echo date('d M, Y h:i A', strtotime($oDate)); ?><br />
                                <strong><?php echo $orderNo; ?></strong>
								<p><?php echo $boStats->backOrd; ?></p>
                            </span>

                            <span class="productspan">
                            	<?php echo $row->items . ' items'; ?> <br />
                                <a href="javascript:void(0)" onclick="window.location='<?php echo siteUrl; ?>orderinfo/<?php echo $orderId; ?>'" > VIEW DETAIL </a>
                            </span>

                            <span class="amountspan">
                            <?php echo "NZD " . $row->amount; ?>
                            </span>

                            <span class="deliveryspan">
                            <?php $oStatus = '';
	if ($status == 0) {$oStatus = 'Processing';} elseif ($status == 1) {$oStatus = 'Dispatched';} elseif ($status == -1) {$oStatus = 'Rejected';} elseif ($status == 2) {$oStatus = 'Delivered';} elseif ($status == 3) {$oStatus = 'Cancellation Requested';} elseif ($status == 4) {$oStatus = 'Refund Procced';} elseif ($status == 5) {$oStatus = 'Return Accepted, Refunded';} elseif ($status == 6) {$oStatus = 'Return/Cancel Request Rejected';}
	echo $oStatus;
	?>
                            </span>

                            <span class="returnspan">
							<?php if ($status == 2 && $row->point_status == 0) {?>
                                    	<a href="javascript:void(0)" class="jhmbutton" style="display:none;" onclick="applyForCredit(<?php echo $orderId; ?>);" >Apply Order Credit</a>
									<?php }if ($status != 3 && $status != 6 && $status != 5 && $status != -1) {
		?>
										<button type="button" onclick="window.location='<?php echo siteUrl; ?>userOrderReturn/Order<?php echo rand(11, 99);
		echo $orderId; ?>'" >Apply for Return</button>
									<?php }?>
                            </span>

                        <div class="clr"> </div>
                        </li>
						<?php $i++;}?>
                    </ul>
				</div>


</div>



 <div class="clr"> </div>
	</div>


  </div>
</section>





<?php include "include/new_footer.php";?>
<script>
function openOrderDetail(id, classs){
	document.getElementById(id).className = 'orderInfoWrapper '+classs;
	document.getElementById('blackoverlayOrder').style.display="block";
	document.getElementById('blackoverlayOrder').className=id;
}
function closeOrderDetail(id){
	document.getElementById(id).className='orderInfoWrapper';
	document.getElementById('blackoverlayOrder').style.display='';
	document.getElementById('blackoverlayOrder').className='';
}
function applyForCredit(id){
	var xmlhttp;
	if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
	else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){ alert(xmlhttp.responseText); location.reload(); }
	}
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=applyForCredit&data1="+id+"&dataTempId=f3vcfs1543652ce90hch14lk6217900.cloud.uk", true);
	xmlhttp.send();
}
</script>

<?php include "include/new_bottom.php";?>