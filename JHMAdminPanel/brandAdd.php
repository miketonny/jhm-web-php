<?php include 'include/header.php'; ?>
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
                          $cat_rs = exec_query("SELECT category_id, category_name FROM tbl_category WHERE parent_id = 0 AND superparent_id = 0 ORDER BY category_name", $con);
                          while($cat_row = mysql_fetch_object($cat_rs)){ ?>
                          <div>
                          <span>
                          <input class="span2" type="checkbox" name="category[]" value="<?php echo $cat_row->category_id; ?>">
                          </span>
                          <?php echo $cat_row->category_name; ?>
                          </div>
                          <?php } ?>
                          </div>
                          </div> */ ?>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Brand Name</label>
                            <div class="col-sm-6">
                                <div id="picker"></div>
                                <input class="form-control" required name="name" id="brand" placeholder="Brand Name" type="text" onblur="getSlug(this.id)" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Slug</label>
                            <div class="col-sm-6">
                                <input class="form-control" required name="slug" id="brandSlug" placeholder="Slug" type="text" />
                            </div>
                        </div>
                          <div class="form-group">
                            <label class="col-sm-3 control-label">Show On Frontend ?</label>
                            <div class="col-sm-6">
                             
                                <input type="checkbox"  value="1" name="flag" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Brand Logo</label>
                            <div class="col-sm-6">
                                <input name="img[]" type="file" />
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
                                <input type="hidden" name="action" value="brandAdd" />
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
<script type="text/javascript" src="assets/js/js.js"></script>
</body>
</html>
