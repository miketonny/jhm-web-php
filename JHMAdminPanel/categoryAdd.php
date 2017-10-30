<?php include 'include/header.php'; ?>
<link rel="stylesheet" href="assets/css/farbtastic.css" type="text/css" />
<div class="warper container-fluid">
    <div class="page-header"><h1>Category <small>Add New Category</small></h1></div>
    <div class="row">

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Add New Category</div>
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
                                            echo '<option value="' . $cat_row->category_id . '">' . $cat_row->category_name . '</option>';
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Select Sub Category</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="subCat">
                                    <option value="0">Sub Category</option>
                                    <optgroup label="---" id="sub_category"></optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category Name</label>
                            <div class="col-sm-6">
                                <!--<div id="picker"></div>-->
                                <input class="form-control" required name="name" id="catt" placeholder="Category Name" type="text" onblur="getSlug(this.id)" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Slug</label>
                            <div class="col-sm-6">
                                <input class="form-control" required name="slug" id="cattSlug" placeholder="Slug" type="text" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                <textarea class="ckeditor" name="desc" placeholder="Description" ></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Make it Featured?</label>
                            <div class="col-sm-10">
                                <div class="switch-button showcase-switch-button control-label">
                                    <input id="switch-button-za" name="featured" value="1" type="checkbox" >
                                    <label for="switch-button-za"></label>  <small>(Optional)</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Show On Frontend ?</label>
                            <div class="col-sm-10">


                                <div class="switch-button showcase-switch-button control-label">
                                    <input id="switch-button-za1" name="flag" value="1" type="checkbox" >
                                    <label for="switch-button-za1"></label> 
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category Main Image</label>
                            <div class="col-sm-6">
                                <input name="category_image" type="file"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Select Color</label>
                            <div class="col-sm-10">
                            <div class="col-sm-5" style="border-right:#e0e0e0 thin solid;">
                                <div id="picker"></div>
                            </div>
                            <div class="col-sm-3">
                                <input class="form-control" name="color_code" id="color" placeholder="Select Color" value="#090909" type="text" />
                            </div>
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category Image</label>
                            <div class="col-sm-6">
                                <input name="img[]" type="file" multiple/> <small class="gray"> Press Ctrl and select multiple images</small>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
                                <input type="hidden" name="action" value="categoryAdd" />
                                <button class="btn btn-primary" type="submit"> Add Now ! </button>
                                <button class="btn btn-info" type="submit" name="nsubmit"> Add & New ! </button>
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
    function getSubCategory(cid) {
        $.get('adminAjax.php', {'action': 'getSubCategoryOnCategory', 'data1': cid, 'dataTempId': '3fda701b15a12dzd1c3e1d46d0f258m.cloud.uk'}, function (data) {
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
