<header class="top-head container-fluid">
            <button type="button" class="navbar-toggle pull-left tooltip-btn" data-toggle="tooltip" data-placement="bottom" title="Toggle Sidebar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
			<?php /*<form role="search" class="navbar-left app-search pull-left hidden-xs">
              <input type="text" placeholder="Enter keywords..." class="form-control form-control-circle">
         	</form>*/ ?>
            <nav class=" navbar-default hidden-xs" role="navigation">
                <ul class="nav navbar-nav">
                <li><a href="../" class="tooltip-btn" data-toggle="tooltip" data-placement="bottom" title="Go to Dashboard!"><span class="glyphicon glyphicon-home"></span></a></li>
                <!--<li class="dropdown">
                  <a data-toggle="dropdown" class="dropdown-toggle" href="#">Dropdown <span class="caret"></span></a>
                  <ul role="menu" class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                    <li class="divider"></li>
                    <li><a href="#">One more separated link</a></li>
                  </ul>
                </li>-->
              </ul>
            </nav>
            
            <ul class="nav-toolbar">
            	<?php /*<li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-comments-o"></i> <span class="badge bg-warning">7</span></a>
                	<div class="dropdown-menu md arrow pull-right panel panel-default arrow-top-right messages-dropdown">
                        <div class="panel-heading">
                      	Messages
                        </div>
                        
                        <div class="list-group">
                            
                            <a href="#" class="list-group-item">
                            <div class="media">
                              <div class="user-status busy pull-left">
                              <img class="media-object img-circle pull-left" src="assets/images/avtar/user2.png" alt="user#1" width="40">
                              </div>
                              <div class="media-body">
                                <h5 class="media-heading">Lorem ipsum dolor sit consect....</h5>
                                <small class="text-muted">23 Sec ago</small>
                              </div>
                            </div>
                            </a>
                            <a href="#" class="list-group-item">
                            <div class="media">
                              <div class="user-status offline pull-left">
                              <img class="media-object img-circle pull-left" src="assets/images/avtar/user3.png" alt="user#1" width="40">
                              </div>
                              <div class="media-body">
                                <h5 class="media-heading">Nunc elementum, enim vitae</h5>
                                <small class="text-muted">23 Sec ago</small>
                              </div>
                            </div>
                            </a>
                            <a href="#" class="list-group-item">
                            <div class="media">
                              <div class="user-status invisibled pull-left">
                              <img class="media-object img-circle pull-left" src="assets/images/avtar/user4.png" alt="user#1" width="40">
                              </div>
                              <div class="media-body">
                                <h5 class="media-heading">Praesent lacinia, arcu eget</h5>
                                <small class="text-muted">23 Sec ago</small>
                              </div>
                            </div>
                            </a>
                            <a href="#" class="list-group-item">
                            <div class="media">
                              <div class="user-status online pull-left">
                              <img class="media-object img-circle pull-left" src="assets/images/avtar/user5.png" alt="user#1" width="40">
                              </div>
                              <div class="media-body">
                                <h5 class="media-heading">In mollis blandit tempor.</h5>
                                <small class="text-muted">23 Sec ago</small>
                              </div>
                            </div>
                            </a>
                            
                            <a href="#" class="btn btn-info btn-flat btn-block">View All Messages</a>

                        </div>
                        
                    </div>
                </li>
                <li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-bell-o"></i><span class="badge">3</span></a>
                	<div class="dropdown-menu arrow pull-right md panel panel-default arrow-top-right notifications">
                        <div class="panel-heading">
                      	Notification
                        </div>
                        
                        <div class="list-group">
                            
                            <a href="#" class="list-group-item">
                            <p>Installing App v1.2.1<small class="pull-right text-muted">45% Done</small></p>
                            <div class="progress progress-xs no-margn progress-striped active">
                              <div class="progress-bar"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                                <span class="sr-only">45% Complete</span>
                              </div>
                            </div>
                            </a>
                            
                            <a href="#" class="list-group-item">
                            Fusce dapibus molestie tincidunt. Quisque facilisis libero eget justo iaculis
                            </a>
                            
                            <a href="#" class="list-group-item">
                            <p>Server Status</p>
                            <div class="progress progress-xs no-margn">
                              <div class="progress-bar progress-bar-success" style="width: 35%">
                                <span class="sr-only">35% Complete (success)</span>
                              </div>
                              <div class="progress-bar progress-bar-warning" style="width: 20%">
                                <span class="sr-only">20% Complete (warning)</span>
                              </div>
                              <div class="progress-bar progress-bar-danger" style="width: 10%">
                                <span class="sr-only">10% Complete (danger)</span>
                              </div>
                            </div>
                            </a>
                            
                            
                            
                            <a href="#" class="list-group-item">
                            <div class="media">
                              <span class="label label-danger media-object img-circle pull-left">Danger</span>
                              <div class="media-body">
                                <h5 class="media-heading">Lorem ipsum dolor sit consect..</h5>
                              </div>
                            </div>
                            </a>
                            
                            
                            <a href="#" class="list-group-item">
                            <p>Server Status</p>
                            <div class="progress progress-xs no-margn">
                              <div style="width: 60%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar progress-bar-info">
                                <span class="sr-only">60% Complete (warning)</span>
                              </div>
                            </div>
    						</a>
                            

                        </div>
                        
                    </div>
                </li>*/

$taxPerm = chkPermission('tax', $con);
$sizePerm = chkPermission('size', $con);
$configPerm = chkPermission('config', $con);
$tagPerm = chkPermission('tag', $con);
$stagPerm = chkPermission('stag', $con);
$emailTempPerm = chkPermission('emailtemp', $con);
$newsletterPerm = chkPermission('newsletter', $con);
?>
                <li class="dropdown tooltip-btn" data-toggle="tooltip" data-placement="bottom" title="Settings"><a href="#" data-toggle="dropdown"><i class="fa fa-gears"></i></a>
                	<div class="dropdown-menu lg pull-right arrow panel panel-default arrow-top-right">
                    	<div class="panel-heading"> <span class="fa fa-gears"> </span> Settings </div>
                        <div class="panel-body text-center">
                        	<div class="row">
                            
								<?php if($taxPerm[0]){ ?>
                                <div class="col-xs-6 col-sm-4"><a href="tax.php" class="text-info"><span class="h2"><i class="fa fa-line-chart"></i></span><p class="text-gray">Manage Tax</p></a></div>
								<?php }if($sizePerm[0]){ ?>
								<div class="col-xs-6 col-sm-4"><a href="size.php" class="text-purple"><span class="h2"><i class="fa fa-sort-numeric-asc"></i></span><p class="text-gray">Manage Size</p></a></div>
								<?php }if($configPerm[0]){ ?>
                                <div class="col-xs-6 col-sm-4"><a href="siteConfig.php" class="text-primary"><span class="h2"><i class="glyphicon glyphicon-cog"></i></span><p class="text-gray">Configuration</p></a></div>
                                <?php } ?>
                                <div class="col-lg-12 col-md-12 col-sm-12  hidden-xs"><hr></div>
                                
                                <?php if($tagPerm[0]){ ?>
                                <div class="col-xs-6 col-sm-4"><a href="tag.php" class="text-primary"><span class="h2"><i class="fa fa-tags"></i></span><p class="text-gray">Manage Tags</p></a></div>
                                <?php }if($stagPerm[0]){ ?>
                                <div class="col-xs-6 col-sm-4"><a href="searchTag.php" class="text-primary"><span class="h2"><i class="fa fa-tags"></i></span><p class="text-gray">Manage Search Tags</p></a></div>
                                <?php }if($emailTempPerm[0]){ ?>
                                <div class="col-xs-6 col-sm-4"><a href="manageTemplate.php" class="text-primary"><span class="h2"><i class="glyphicon glyphicon-asterisk"></i></span><p class="text-gray no-margn">Email Templates</p></a></div>
								<?php } ?>
                                <div class="col-lg-12 col-md-12 col-sm-12  hidden-xs"><hr></div>
                                
                                <?php if($newsletterPerm[0]){ ?>
                                <div class="col-xs-6 col-sm-4"><a href="newsletterAdd.php" class="text-primary"><span class="h2"><i class="glyphicon glyphicon-asterisk"></i></span><p class="text-gray no-margn">Newsletter</p></a></div>
                                <?php } ?>
                                <div class="col-xs-6 col-sm-4"><a href="profileEdit.php" class="text-primary"><span class="h2"><i class="glyphicon glyphicon-user"></i></span><p class="text-gray no-margn">Edit Profile</p></a></div>
                                <div class="col-xs-6 col-sm-4"><a href="logout.php?logout=true" class="text-red"><span class="h2"><i class="fa fa-sign-out"></i></span><p class="text-gray no-margn">Sign Out</p></a></div>
                                
								<div class="col-lg-12 col-md-12 col-sm-12  hidden-xs"><hr></div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </header><?php echo getMessage(); ?>
        <!-- Header Ends -->