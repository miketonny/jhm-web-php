<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>
<?php

/* chk trnid
if(chkParam1($_GET['trnid']) && isset($_GET['trnid'])){	$trnid = $_GET['trnid']; }
elseif(chkParam1($_SESSION['trnid']) && isset($_SESSION['trnid'])){ $trnid = $_SESSION['trnid']; }
else{ redirect(siteUrl); }

if(chkParam1($_GET['user']) && isset($_GET['user'])){ $user = $_GET['user']; }
elseif(chkParam1($_SESSION['user']) && isset($_SESSION['user'])){ $user = $_SESSION['user']; }
else{ redirect(siteUrl); }

// get order detail
$order = mysql_fetch_object(mysql_query("SELECT order_id FROM tbl_order WHERE trnid = '".$trnid."'"));

if(isset($order->order_id) && $order->order_id != ''){
	$del_od = mysql_query("DELETE FROM tbl_order WHERE trnid = '$trnid'");
	$del_od = mysql_query("DELETE FROM tbl_order_item WHERE od_id = '$order->order_id'");
}
else{ redirect(siteUrl); }
unset($_SESSION['trnid']);
*/
?>

 <section class="block-pt5">
    <div class="container">   
        <div class="other-page-holder">
              <div class="col-md-12 ">
        <h1>Transaction Incomplete</h1>
        <table border="0" cellpadding="5" cellspacing="1" class="entryTable">
            <tr class="entryTableHeader">
                <td>Payment was not successful, please try again. If the problem persists, please contact our support team at 
<a href="mailto:support@jhm.co.nz">support@jhm.co.nz</a></td>
            </tr>
            <!--<tr>
                <td width="150" class="label" align="center">Some Problem in Placing Order, </td>
            </tr>-->
            <tr>
                <td class="label" align="center">
                	<!--<button type="button" class="button" onclick="window.location='<?php echo siteUrl; ?>product-search/'">Continue Shopping</button>-->
                    <button type="button" class="button" style="margin-left: 10px;" onclick="window.location='<?php echo siteUrl; ?>'">Home</button>
                </td>
            </tr>
        </table>
        
        
        
              </div>
        
         <div class="clr"> </div>
	</div>
        
        
    </div>
 </section>

<?php include("include/new_footer.php"); ?>

<script> //setTimeout(function(){ window.location='<?php //echo siteUrl; ?>'; }, 7000); </script>
<?php include("include/new_bottom.php"); ?>