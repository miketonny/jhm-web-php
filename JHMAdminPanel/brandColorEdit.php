<?php
include 'include/header.php';
chkParam($_GET['data1'], 'home.php');
$id = $_GET['data1'];
$slug = $_GET['data2'];
$brand = getBrand(array('brand_name'), $id, $con);
?>
<link rel="stylesheet" href="assets/css/farbtastic.css" type="text/css" />
<div class="warper container-fluid">
    <div class="page-header"><h1>Color (<?php echo $brand->brand_name; ?>) <small>Add New Color in <?php echo $brand->brand_name; ?></small></h1></div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Click on Color Blocks to Delete Color</div>
                <div class="panel-body">
                    <form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                        <div class="form-group" style="padding:5px 5px 0;">
                            <div class="col-sm-12" id="colorpallet">
                                <table style="font-size: 13px; width: 100%;" class="table no-margn">
                                    <tr>
                                        <th>Color</th>
                                        <th>Name</th>
                                        <th>Display Name</th>
                                        <th>Action</th>
                                    </tr>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $col_rs = exec_query("SELECT * FROM tbl_color WHERE brand = '$id' ORDER BY color", $con);
                                        while ($col_row = mysql_fetch_object($col_rs)) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <input id="color<?php echo $i; ?>" type="radio" value="<?php echo $col_row->color_code; ?>" name="color[]"/>
                                                    <label for="color<?php echo $i; ?>" style="background-color:<?php echo $col_row->color_code; ?>; height:25px; width:25px;margin:0px;" class="tooltip-btn" title="" data-toggle="tooltip" data-original-title="<?php echo $col_row->color; ?>"><span class="glyphicon">&nbsp;</span></label>
                                                </td>
                                                <td><?php echo $col_row->color; ?></td>
                                                <td><?php echo $col_row->display_name; ?></td>
                                                <td>
                                                    <button class="btn btn-primary btn-xs" type="button" onclick="window.location = 'colorEdit.php?data1=<?php echo $col_row->color_id; ?>&dataLast1=<?php echo $_GET['data1']; ?>&dataLast2=<?php echo $_GET['data2']; ?>'"><i class="fa fa-edit"></i></button>
                                                    <button class="btn btn-primary btn-xs" type="button" onclick="deleteColor(<?php echo $col_row->color_id; ?>);"><i class="fa fa-remove"></i></button>
                                                </td>
                                            </tr>
    <?php $i++;
} ?>
                                    <tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <style>.my{ margin-bottom:5px; width:200px; }</style>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Add New Color in <?php echo $brand->brand_name; ?></div>
                <div class="panel-body">
                    <form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Select Color</label>
                            <div style="clear:both;"></div>
                            <div class="col-sm-6" style="border-right:#e0e0e0 thin solid;">
                                <div id="picker"></div>
                            </div>
                            <div class="col-sm-3">
                                <label class="col-sm-3 control-label my" style="text-align: left;">RGB</label>
                                <input class="form-control my" name="r" id="r" placeholder="'R' Value" maxlength="3" />
                                <input class="form-control my" name="g" id="g" placeholder="'G' Value" maxlength="3" />
                                <input class="form-control my" name="b" id="b" placeholder="'B' Value" maxlength="3" />
                                <button class="btn btn-primary" type="button" onclick="validateRGB();">Convert to Hex</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-6">
                                <input class="form-control" required name="colorCode" id="color" placeholder="Select Color" value="#090909" type="text" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Color Name</label>
                            <div class="col-sm-6">
                                <input class="form-control" required name="name" id="col" placeholder="Color Name" type="text" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Display Name</label>
                            <div class="col-sm-6">
                                <input class="form-control" required name="dname" placeholder="Display Name" type="text" value="" />
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
                                <input type="hidden" name="action" value="brandColorEdit" />
                                <input type="hidden" name="data1" value="<?php echo $id; ?>" />
                                <input type="hidden" name="data2" value="<?php echo $slug; ?>" />
                                <button class="btn btn-primary" type="submit"> Save Changes ! </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- Warper Ends Here (working area) -->

<?php include 'include/footer.php'; ?>    
<?php include 'include/formJs.php'; ?>
<script>
    $(document).ready(function () {
        $('#picker').farbtastic('#color');
    });
    function validateRGB() {
        r = document.getElementById('r').value;
        g = document.getElementById('g').value;
        b = document.getElementById('b').value;
        if (r == '' || g == '' || b == '' || isNaN(r) || isNaN(g) || isNaN(b) || r > 255 || g > 255 || b > 255) {
            alert('Invalid RGB Values!!!');
        }
        else {
            heX = rgbToHex(r, g, b);
            document.getElementById('color').value = '#' + heX;
            $.farbtastic('#picker').setColor('#' + heX);
        }
    }
    function rgbToHex(R, G, B) {
        return toHex(R) + toHex(G) + toHex(B);
    }
    function toHex(n) {
        n = parseInt(n, 10);
        if (isNaN(n))
            return "00";
        n = Math.max(0, Math.min(n, 255));
        return "0123456789ABCDEF".charAt((n - n % 16) / 16) + "0123456789ABCDEF".charAt(n % 16);
    }

    function deleteColor(id) {
        if (confirm('Do you want to Delete this Color?')) {
            $.get('delete.php', {'table': 'tbl_color', 'pk': 'color_id', 'id': id}, function (data) {
                alert(data);
                location.reload();
            });
        }
    }
</script>
<script type="text/javascript" src="assets/js/farbtastic.js"></script>
<script type="text/javascript" src="assets/js/js.js"></script>
</body>
</html>
