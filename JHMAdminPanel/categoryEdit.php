<?php
include 'include/header.php';
chkParam($_GET['data1'], 'home.php');
$id = $_GET['data1'];
$cat = getCategory(array('*'), $id, $con);
$chk = 'checked="checked"';
?>
<link rel="stylesheet" href="assets/css/farbtastic.css" type="text/css" />
<div class="warper container-fluid">
    <div class="page-header"><h1>Category <small>Edit Category</small></h1></div>
    <div class="row">

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Category</div>
                <div class="panel-body">
                    <form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Select Main Category</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="mainCat" onchange="getSubCategory(this.value)">
                                    <option value="0">Main Category</option>
                                    <optgroup label="---">
                                        <?php
                                        $cat_rs = exec_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0 ORDER BY category_name", $con);
                                        while ($cat_row = mysql_fetch_object($cat_rs)) {
                                            ?>
                                            <option <?php getSelected($cat->superparent_id, $cat_row->category_id); ?> value="<?php echo $cat_row->category_id; ?>"><?php echo $cat_row->category_name; ?></option>
                                        <?php } ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Select Sub Category</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="subCat">
                                    <option value="0">Sub Category</option>
                                    <optgroup label="---" id="sub_category">
                                        <?php if ($cat->parent_id != 0) { ?> <option selected="selected" value="<?php echo $cat->parent_id; ?>"><?php echo getCategory(array('category_name'), $cat->parent_id, $con)->category_name; ?></option> <?php } ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category Name</label>
                            <div class="col-sm-6">
                                <!--<div id="picker"></div>-->
                                <input class="form-control" required name="name" id="catt" placeholder="Category Name" type="text" onblur="getSlug(this.id)" value="<?php echo $cat->category_name; ?>" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Slug</label>
                            <div class="col-sm-6">
                                <input class="form-control" required name="slug" id="cattSlug" placeholder="Slug" type="text"
                                       value="<?php echo $cat->slug; ?>" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                <textarea class="ckeditor" name="desc" placeholder="Description" ><?php echo $cat->category_description; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Make it Featured?</label>
                            <div class="col-sm-10">
                                <div class="switch-button showcase-switch-button control-label">
                                    <input id="switch-button-za" name="featured" value="1" type="checkbox" <?php echo ($cat->is_featured == 1) ? $chk : ''; ?> >
                                    <label for="switch-button-za"></label>  <small>(Optional)</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Show On Frontend ?</label>
                            <div class="col-sm-10">

                                <div class="switch-button showcase-switch-button control-label">
                                    <input id="switch-button-za1" name="flag" value="1" type="checkbox" <?php echo ($cat->flag == 1) ? $chk : ''; ?> >
                                    <label for="switch-button-za1"></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category Main Image</label>
                            <div class="col-sm-6">
                                <?php
                                if ($cat->category_image != "") {
                                    ?>

                                <img style="height:60px;" src="../site_image/category_image/<?php echo $cat->category_image;?>"/>
                                <?php } ?>
                                <input name="category_image" type="file"/>
                                <input type="hidden" name="cat_img" value="<?php echo $cat->category_image; ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Select Color</label>
                            <div class="col-sm-10">
                                <div class="col-sm-5" style="border-right:#e0e0e0 thin solid;">
                                    <div id="picker"></div>
                                </div>
                                <div class="col-sm-3">
                                    <input class="form-control" required name="color_code" id="color" placeholder="Select Color" style=" background-color: <?php echo ($cat->color_code!="") ? $cat->color_code : '#090909' ?>" value="<?php echo ($cat->color_code!="") ? $cat->color_code : '#090909' ?>" type="text" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category Image</label>
                            <div class="col-sm-6">
                                <input name="img[]" type="file" multiple/> <small class="gray"> Press Ctrl and select multiple images</small>
                                <!-- -0-------------- -->
                                <?php
                                $rsImg = getCategoryImg($id, $con);
                                if (mysql_num_rows($rsImg) > 0) {
                                    ?>
                                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner">
                                            <?php
                                            $ol = '';
                                            $i = 0;
                                            while ($rowImg = mysql_fetch_object($rsImg)) {
                                                $active = ($i == 0) ? 'active' : '';
                                                $ol .= '<li data-target="#carousel-example-generic" data-slide-to="' . $i . '" class="' . $active . '"></li>';
                                                ?>
                                                <div class="item <?php echo $active; ?>">
                                                    <img src="../site_image/category/<?php echo $rowImg->media_src; ?>" alt="Images..." style="">
                                                    <div class="carousel-caption">
                                                        <p> <button onclick="deleteImg(<?php echo $rowImg->recid; ?>)" type="button" class="btn btn-danger btn-xs"> <span class="glyphicon glyphicon-trash"></span> Delete Image</button> </p>
                                                    </div>
                                                </div>

                                                <?php
                                                $i++;
                                            }
                                            ?>
                                        </div>

                                        <ol class="carousel-indicators"><?php echo $ol; ?></ol>

                                        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                                            <span class="glyphicon glyphicon-chevron-left"></span>
                                        </a>
                                        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                                            <span class="glyphicon glyphicon-chevron-right"></span>
                                        </a>
                                    </div>
                                    <?php
                                } else {
                                    echo '<br/>No Image Found!';
                                }
                                ?>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
                                <input type="hidden" name="action" value="categoryEdit" />
                                <input type="hidden" value="<?php echo $id; ?>" name="data1" />
                                <input type="hidden" value="<?php echo $cat->slug; ?>" name="data2" />
                                <button class="btn btn-primary" type="submit"> Save Changes ! </button>
                                <button class="btn btn-info" type="submit" name="nsubmit"> Save & New ! </button>
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
    function deleteImg(id) {
        if (confirm('Do you want to Delete this Category?')) {
            $.get('delete.php', {'table': 'tbl_category_media', 'pk': 'recid', 'id': id}, function (data) {
                alert(data);
                location.reload();
            });
        }
    }
    function getSubCategory(cid) {
        $.get('adminAjax.php', {'action': 'getSubCategoryOnCategory', 'data1': cid, 'dataTempId': '3fca701c15a12dzdvc3ehd46dg8m.cloud'}, function (data) {
            $('#sub_category').html(data);
        });
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
