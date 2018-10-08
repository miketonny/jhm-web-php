<?php include 'include/header.php'; ?>
<?php
$ref = !empty($_GET['ref']) ? base64_decode($_GET['ref']) : null;
if(!is_null($ref)) {
    list(, $timestamp) = explode('_', $ref);
}
?>
<div class="warper container-fluid">
    <div class="page-header"><h1>Stock Updates<?= !is_null($ref) ? ' <small>'.date('d M, Y h:i A', $timestamp).'</small>' : null ?></h1></div>
    <div class="panel panel-default">
        <div class="panel-body">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="toggleColumn-datatable">
                <?php if($ref === null) { ?>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Reference</th>
                        <th>Created On</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1;
                    $query = "SELECT `ref`, `timestamp`
                            FROM `tbl_update_log`
                            GROUP BY `ref`
                            ORDER BY `timestamp` DESC";
                    $rs = mysqli_query($con, $query);
                    while($row = mysqli_fetch_object($rs)){
                        ?>
                        <tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $row->ref; ?></td>
                            <td><?php echo date('d M, Y h:i A', strtotime($row->timestamp)); ?></td>
                            <td><button type="button" onClick="viewDetails('<?php echo base64_encode($row->ref); ?>');" class="btn btn-primary btn-xs">Details</button></td>
                        </tr>
                        <?php $i++; } ?>
                    </tbody>
                <?php } else { ?>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Updated by</th>
                        <th>New Qty</th>
                        <th>Old Qty</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1;
                    $query = "SELECT `u`.`old_qty`, `u`.`new_qty`, `u`.`timestamp`,
                                     `p`.`product_name`, `a`.`username`
                            FROM `tbl_update_log` AS `u`
                              INNER JOIN `tbl_product` AS `p` ON `p`.`product_id`=`u`.`product_id`
                              INNER JOIN `admin` AS `a` ON `a`.`recid`=`u`.`admin_id`
                            WHERE `ref`='{$ref}'
                            ORDER BY `u`.`product_id` DESC";
                    $rs = mysqli_query($con, $query);
                    while($row = mysqli_fetch_object($rs)){
                        ?>
                        <tr <?php if($i%2 == 0){ echo 'class="info"'; } ?>>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $row->product_name; ?></td>
                            <td><?php echo $row->username; ?></td>
                            <td><?php echo $row->old_qty; ?></td>
                            <td><?php echo $row->new_qty; ?></td>
                        </tr>
                        <?php $i++; } ?>
                    </tbody>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<!-- Warper Ends Here (working area) -->
<script>
    function viewDetails(ref) {
        window.location = '?ref=' + ref;
    }
</script>
<?php include 'include/footer.php'; ?>
<?php include 'include/tableJs.php'; ?>
</body>
</html>