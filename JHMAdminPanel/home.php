<?php include 'include/header.php';
$currYear = date('Y');

// for sparkline small graphs n 4 blocks start
$currDate = date('Y-m-d');
$pastDate = date('Y-m-d', strtotime('-15 days', strtotime($currDate)));
$past15Date = date('Y-m-d', strtotime('-15 days', strtotime($pastDate)));

$statsSaleData = getStats('order', $pastDate, $currDate, $con);
$statsUserData = getStats('user', $pastDate, $currDate, $con);
$statsProData = getStats('product', $pastDate, $currDate, $con);

$statsSaleDataOld = getStats('order', $past15Date, $pastDate, $con); echo '<br/>';
$statsUserDataOld = getStats('user', $past15Date, $pastDate, $con); echo '<br/>';
$statsProDataOld = getStats('product', $past15Date, $pastDate, $con);

$statsSaleData=20; $statsSaleDataOld=10;

// VALIDATE FIRST DATA ORDER
if($statsSaleData > $statsSaleDataOld){ $class11 = 'text-green'; $class12 = 'fa-caret-up'; }
else{ $class11 = 'text-red'; $class12 = 'fa-caret-down'; }

if(($statsSaleDataOld != 0 && $statsSaleData != 0) || ($statsSaleDataOld != 0 && $statsSaleData == 0)){	
	$diffSale = (int)(($statsSaleData / $statsSaleDataOld) * 100);
}
elseif($statsSaleDataOld == 0 && $statsSaleData != 0){
	$diffSale = $statsSaleData * 100;
}
else{ $diffSale = 0; }

// VALIDATE SECOND DATA USER
if($statsUserData > $statsUserDataOld){ $class21 = 'text-green'; $class22 = 'fa-caret-up'; }
else{ $class21 = 'text-red'; $class22 = 'fa-caret-down'; }

if(($statsUserDataOld != 0 && $statsUserData != 0) || ($statsUserDataOld != 0 && $statsUserData == 0)){	
	$diffUser = (int)(($statsUserData / $statsUserDataOld) * 100);
}
elseif($statsUserDataOld == 0 && $statsUserData != 0){
	$diffUser = $statsUserData * 100;
}
else{ $diffUser = 0; }

// VALIDATE THIRD DATA PRODUCT
if($statsProData > $statsProDataOld){ $class31 = 'text-green'; $class32 = 'fa-caret-up'; }
else{ $class31 = 'text-red'; $class32 = 'fa-caret-down'; }

if(($statsProDataOld != 0 && $statsProData != 0) || ($statsProDataOld != 0 && $statsProData == 0)){	
	$diffPro = (int)(($statsProData / $statsProDataOld) * 100);
}
elseif($statsProDataOld == 0 && $statsProData != 0){
	$diffPro = $statsProData * 100;
}
else{ $diffPro = 0; }
?>
<link rel="stylesheet" href="assets/css/plugins/calendar/calendar.css" /><!-- Calendar Styling  -->

<div class="warper container-fluid">
	<div class="page-header"><h1>Welcome Admin, <small><!--Start new page with this--></small></h1></div>
    
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="panel panel-default clearfix dashboard-stats rounded">
                <span id="dashboard-stats-sparkline1" class="sparkline transit"></span>
                
                <i class="fa fa-shopping-cart bg-danger transit stats-icon"></i>
                <h3 class="transit"><?php echo $statsSaleData; ?>
                <small class="<?php echo $class11; ?>"><i class="fa <?php echo $class12; ?>"></i> <?php echo $diffSale; ?>%</small></h3>
                <p class="text-muted transit">15 Days Sales</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="panel panel-default clearfix dashboard-stats rounded">
                <span id="dashboard-stats-sparkline2" class="sparkline transit"></span>
                <i class="fa fa-user bg-info transit stats-icon"></i>
                <h3 class="transit"><?php echo $statsUserData; ?>
                <small class="<?php echo $class21; ?>"><i class="fa <?php echo $class22; ?>"></i> <?php echo $diffUser; ?>%</small></h3>
                <p class="text-muted transit">15 Days Users</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="panel panel-default clearfix dashboard-stats rounded">
                <span id="dashboard-stats-sparkline3" class="sparkline transit"></span>
                <i class="fa fa-tags bg-success transit stats-icon"></i>
                <h3 class="transit"><?php echo $statsProData; ?>
                <small class="<?php echo $class31; ?>"><i class="fa <?php echo $class32; ?>"></i> <?php echo $diffPro; ?>%</small></h3>
                <p class="text-muted transit">Recent Products</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="panel panel-default clearfix dashboard-stats rounded">
                <span id="dashboard-stats-sparkline4" class="sparkline transit"></span>
                <i class="fa fa-warning bg-warning transit stats-icon"></i>
                <h3 class="transit">-344 <small class="text-red"><i class="fa fa-caret-down"></i> 20%</small></h3>
                <p class="text-muted transit">Churned Users</p>
            </div>
        </div>
    </div>
    <!-- order graph ------------------------------------------------------ -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Order Summary for Year <?php echo $currYear; ?></div>
                <div class="panel-body">
                    <div id="line-chart-my" style="height:250px;"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- order calendar ------------------------------------------------------ -->
    <div class="row">
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading">Order Calendar</div>
                <div class="panel-body">
                    <div class="panel-heading clean clearfix text-center">
                    <div class="btn-group pull-left">
                        <button class="btn btn-default btn-sm" data-calendar-nav="prev">&lt; Prev</button>
                        <button class="btn btn-sm btn-default" data-calendar-nav="today">Today</button>
                        <button class="btn btn-sm btn-default" data-calendar-nav="next">Next &gt;</button>
                    </div>
                    <b class="calender-title"></b> 
                    <div class="btn-group pull-right">
                        <button class="btn btn-sm btn-default" data-calendar-view="year">Year</button>
                        <button class="btn btn-sm btn-default active" data-calendar-view="month">Month</button>
                        <button class="btn btn-sm btn-default" data-calendar-view="week">Week</button>
                        <button class="btn btn-sm btn-default" data-calendar-view="day">Day</button>
                    </div>
                    </div>

                    <div class="panel-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
<!-- Warper Ends Here (working area) -->
<?php include 'include/footer.php'; ?>
<!-- JQuery v1.9.1 -->
<script src="assets/js/jquery/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="assets/js/plugins/underscore/underscore-min.js"></script>
<script src="assets/js/bootstrap/bootstrap.min.js"></script><!-- Bootstrap -->
<script src="assets/js/globalize/globalize.min.js"></script><!-- Globalize -->
<script src="assets/js/plugins/nicescroll/jquery.nicescroll.min.js"></script><!-- NanoScroll -->

<script src="assets/js/plugins/sparkline/jquery.sparkline.min.js"></script><!-- for small graphs gole waale Sparkline JS -->

<script src="assets/js/plugins/DevExpressChartJS/dx.chartjs.js"></script><!-- Chart JS -->
<script src="assets/js/plugins/DevExpressChartJS/demo-charts.js"></script><!-- For Demo Charts -->

<script src="assets/js/plugins/calendar/calendar.js"></script><!-- calendar Calendar JS -->
<script src="assets/js/plugins/calendar/calendar-conf.js"></script><!-- Calendar Conf -->

<script src="assets/js/app/custom.js" type="text/javascript"></script><!-- Custom JQuery -->

<?php include 'include/graph.php'; ?>
</body>
</html>
