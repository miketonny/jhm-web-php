<?php
include 'include/header.php';
chkParam($_GET['data1'], 'home.php');
chkParam($_GET['data2'], 'home.php');
$id = $_GET['data1'];
$slug = $_GET['data2'];
$brand = getBrand(array('*'), $id, $con);
?>
<div class="warper container-fluid">
    <div class="page-header"><h1>Brand <small>Add New Brand</small></h1></div>
    <div class="row">

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Add New Brand</div>
                <div class="panel-body">
                    <form method="post" class="form-horizontal" action="admin_action_model.php" enctype="multipart/form-data">

                        <?php /* <div class="form-group">
                          <label class="col-sm-3 control-label">Select Main Category</label>
                          <div class="col-sm-5">
                          <?php
                          //$chk = 'checked="checked" disabled="disabled"';
                          $chk = 'checked="checked" onclick="return false"';
                          $catArray = array();
                          $brand_cat_rs = exec_query("SELECT category_id FROM tbl_brand WHERE slug = '$slug'", $con);
                          while($brand_cat_row = mysql_fetch_object($brand_cat_rs)){
                          $catArray[] = $brand_cat_row->category_id;
                          }
                          $cat_rs = exec_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0 ORDER BY category_name", $con);
                          while($cat_row = mysql_fetch_object($cat_rs)){ ?>
                          <div>
                          <span>
                          <input type="checkbox" name="category[]" value="<?php echo $cat_row->category_id; ?>" <?php if(in_array($cat_row->category_id, $catArray)){ echo $chk; } ?> >
                          </span>
                          <?php echo $cat_row->category_name; ?>
                          <input type="hidden" name="categoryNotUse[]" value="<?php echo $cat_row->category_id; ?>" />
                          </div>
                          <?php } ?>
                          </div>
                          </div> */ ?>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Brand Name</label>
                            <div class="col-sm-6">
                                <div id="picker"></div>
                                <input class="form-control" required name="name" id="brand" placeholder="Brand Name" type="text" value="<?php echo $brand->brand_name; ?>" onblur="getSlug(this.id)" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Slug</label>
                            <div class="col-sm-6">
                                <input class="form-control" required name="slug" id="brandSlug" placeholder="Slug" type="text"
                                       value="<?php echo $brand->slug; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Show On Frontend ?</label>
                            <div class="col-sm-6">
                             
                                <input type="checkbox" <?php echo ($brand->flag==1) ? "checked" : "";?> value="1" name="flag" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Brand Logo</label>
                            <div class="col-sm-6">
                                <input name="img" type="file" />
                                <?php $bImg = '';
                                if (isset($brand->brand_img) && $brand->brand_img != '') {
                                    $bImg = $brand->brand_img; ?>
                                    <img src="../site_image/brand_logo/<?php echo $brand->brand_img; ?>" width="191" class="img-circle" />
<?php } ?>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
                                <input type="hidden" name="action" value="brandEdit" />
                                <input type="hidden" value="<?php echo $bImg; ?>" name="dataImg" />
                                <input type="hidden" value="<?php echo $slug; ?>" name="data2" />
                                <input type="hidden" value="<?php echo $id; ?>" name="data1" />
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
<script type="text/javascript" src="assets/js/js.js"></script>
</body>
</html>
