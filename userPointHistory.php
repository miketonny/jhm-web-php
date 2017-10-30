<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>
<?php 
if(!isset($_SESSION['user']) || $_SESSION['user'] == '' || empty($_SESSION['user'])){ redirect(siteUrl); }
$userId = $_SESSION['user'];
?>

<style>
.info{ background:#d9edf7; }
</style>

    
<div id="blackoverlayOrder" onClick="closeOrderDetail(this.className)" style="top:0px;"></div>

<section class="block-pt5">
    <div class="container">
        <div class="other-page-holder">

           <div class="col-md-12">
            
    	<h1 class="mainHeading">My Point History </h1>
        
        <div id="textWrapper">
        
        	<div id="leftWrapper" class="width100">
                <div id="profile">
                    <?php //include 'include/userNavigation.php'; ?>
		            <div id="clr"></div>
                    <div id="userpages">
                    	<table id="listingtable" width="100%" border="0" cellspacing="0" cellpadding="05">
                            <tr class="heading">
                                <td>#</td>
                                <td>Order</td>
                                <td>Point</td>
                                <td>Date</td>
                            </tr>
                            <?php
                            $i = 1;
                            $query = "SELECT tup.*, tu.email, ord.od_date, ord.amount FROM tbl_user_point tup
                            LEFT JOIN tbl_user tu ON tu.user_id = tup.user_id
                            LEFT JOIN tbl_order ord ON ord.order_id = tup.order_id
							WHERE tup.user_id = $userId
                            ORDER BY tup.datetime DESC";
                            $rs = mysql_query($query, $con);
                            while($row = mysql_fetch_object($rs)){
                                $orderNo = getOrderId($row->order_id);
                            ?>
                                <tr <?php if($row->point > 0){ echo 'class="success"'; }else{ echo 'class="danger"'; } ?> >
                                    <td><?php echo $i; ?></td>
                                    <!--<td><?php echo $row->email; ?></td>-->
                                    <td>
                                        Order # : <strong><?php echo $orderNo; ?></strong><br/>
                                        Order Date : <?php echo date('d M, Y h:i A', strtotime($row->od_date)); ?><br/>
                                        Order Amount : $ <?php echo $row->amount; ?>
                                    </td>
                                    <td><?php echo str_replace('-', '', $row->point).' Points '; if($row->point > 0){ echo ' earned'; }else{ echo ' spend'; } ?></td>
                                    <td><?php echo date('d M, Y h:i A', strtotime($row->datetime)); ?></td>
                                </tr>
                            <?php $i++; } ?>
                    	</table>
                    </div>
                </div>
                <div id="clr"></div>
            </div>
	        <div id="clr"></div>
        </div>
        <div style="clear:both;"></div>
    </div> 
            
            
  <div class="clr"> </div>
        </div>
        </div>
        
</section>
    

<?php include("include/new_footer.php"); ?>
<?php include("include/new_bottom.php"); ?>