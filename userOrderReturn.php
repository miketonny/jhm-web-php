<?php include "include/new_top.php";?>
<?php include "include/new_header.php";?>
<?php
if (!isset($_SESSION['user']) || $_SESSION['user'] == '' || empty($_SESSION['user'])) {redirect(siteUrl);die();}
if (!isset($_GET['odId']) || $_GET['odId'] == '' || empty($_GET['odId'])) {redirect('userorder/');die();}
$user_id = $_SESSION['user'];
$orderData = $_GET['odId'];
$odId = substr($orderData, 7);

$chkOrder = exec_query("SELECT order_id FROM tbl_order WHERE order_id ='$odId' AND user_id = '$user_id'", $con);
if (!mysqli_num_rows($chkOrder)) {redirect('userorder/');die();}

$query = "SELECT ord.*, CONCAT_WS(' ', ord.od_shipping_first_name, ord.od_shipping_last_name) AS shipuname,
(SELECT COUNT(toi.order_item_id) FROM tbl_order_item toi WHERE toi.order_id = ord.order_id) AS items
FROM tbl_order ord
WHERE ord.order_id = '$odId'";
$rs = exec_query($query, $con);
$row = mysqli_fetch_object($rs);
$orderId = $row->order_id;
$orderNo = getOrderId($orderId, $con);
?>


<section class="block-pt5">
    <div class="container">

	<div  class="dashboard-holder">

  <div class="col-sm-12">
    	<h1 class="mainHeading">Cancel Order </h1>
            </div>
           <div class="col-md-3 col-sm-4">
      	<?php include 'include/userNavigation.php';?>
           </div>

<div class="col-md-9 col-sm-8">
<div class="clr"> </div>
<div id="returnPage">
			<h3>Order Return (<?php echo $orderNo; ?>)</h3>
        	<form action="<?php echo siteUrl; ?>action_model.php" method="post">
            <h3><?php echo 'NZD ' . $row->amount; ?></h3>
            <h3>Reason for applying return/credit *</h3>
			<select required="required" name="reason">
				<option value="">-- SELECT REASON --</option>
				<option>Delayed Delivery Cancellation</option>
				<option>Incorrect size ordered</option>
				<option>Duplicate order cancelled</option>
				<option>Product not required anymore</option>
				<option>Cash Issue- Cancel Order</option>
				<option>Ordered by mistake</option>
				<option>Fraud Order</option>
				<option>Wants to change style/color</option>
				<option>Others</option>
			</select>

			<h3>Additional Remarks</h3>
			<textarea required="required" name="exp" rows="8" ></textarea>
			<p>
				<button type="button" onclick="window.location='<?php echo siteUrl; ?>userorder/'" class="button nowidth">Discard</button>
				<button type="submit" name="submit" class="button nowidth">Submit Application</button>
			</p>
			<input type="hidden" name="action" value="orderCancelRequest" />
			<input type="hidden" name="data1" value="<?php echo $orderData; ?>" />
            </form>
        </div>


</div>

<div class="clr"> </div>
	</div>


  </div>
</section>





<?php include "include/new_footer.php";?>
<?php include "include/new_bottom.php";?>