<?php include 'include/header.php'; ?>
        <div class="warper container-fluid">
        	
            <div class="page-header"><h1>User Points <small> All Point History</small></h1></div>
			<div class="panel panel-default">
				<div class="panel-body">
				
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>User</th>
								<th>Order</th>
                                <th>Point</th>
								<th>Date</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						$query = "SELECT tup.*, tu.email, ord.od_date, ord.amount FROM tbl_user_point tup
						LEFT JOIN tbl_user tu ON tu.user_id = tup.user_id
						LEFT JOIN tbl_order ord ON ord.order_id = tup.order_id
						ORDER BY tup.datetime DESC";
						$rs = mysql_query($query, $con);
						while($row = mysql_fetch_object($rs)){
							$orderNo = getOrderId($row->order_id);
						?>
							<tr <?php if($row->point > 0){ echo 'class="success"'; }else{ echo 'class="danger"'; } ?> >
								<td><?php echo $i; ?></td>
								<td><?php echo $row->email; ?></td>
                                <td>
									Order # : <strong><?php echo $orderNo; ?></strong><br/>
                                    Order Date : <?php echo date('d M, Y h:i A', strtotime($row->od_date)); ?><br/>
                                    Order Amount : $ <?php echo $row->amount; ?>
                                </td>
                                <td><?php echo str_replace('-', '', $row->point).' Points '; if($row->point > 0){ echo ' earned'; }else{ echo ' spend'; } ?></td>
                                <td><?php echo date('d M, Y h:i A', strtotime($row->datetime)); ?></td>
							</tr>
						<?php $i++; } ?>
						</tbody>
					</table>
				</div>
			</div>
        </div>
        <!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php'; ?>
<?php include 'include/tableJs.php'; ?>
</body>
</html>