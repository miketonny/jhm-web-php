<script><?php
$graphSaleData = ''; $graphUserData = ''; $graphProData = '';
// get $currDate $pastDate from home.php
$idate = 1;
$loopPastDate = $pastDate;
while($loopPastDate <= $currDate){
	$saleData = getStatsData('order', $loopPastDate, $con);
	$userData = getStatsData('user', $loopPastDate, $con);
	$proData = getStatsData('product', $loopPastDate, $con);
	
	$comma = ($idate == 1)?'':',';
	$graphSaleData .= $comma.$saleData;
	$graphUserData .= $comma.$userData;
	$graphProData .= $comma.$proData;
	
	$loopPastDate = date('Y-m-d', strtotime('+1 day', strtotime($loopPastDate)));
	$idate++;
}?>
///////// small graphs////////
$("#dashboard-stats-sparkline1").sparkline([<?php echo $graphSaleData; ?>], {
    type: 'bar',
    height: '60',
    barColor: '#9D9EA5',
    negBarColor: '#e9573f'});
	
$("#dashboard-stats-sparkline2").sparkline([<?php echo $graphUserData; ?>], {
    type: 'bar',
    height: '60',
    barColor: '#9D9EA5',
    negBarColor: '#e9573f'});
	
$("#dashboard-stats-sparkline3").sparkline([<?php echo $graphProData; ?>], {
    type: 'bar',
    height: '60',
    barColor: '#9D9EA5',
    negBarColor: '#e9573f'});
	
$("#dashboard-stats-sparkline4").sparkline([5,10,7,2,0,-4,-2,4,5,-6,-1.5,2.2,4.7,-3.5,-0.7,-2,3.1,6], {
    type: 'bar',
    height: '60',
    barColor: '#9D9EA5',
    negBarColor: '#e9573f'});

////////big graph//////////	
$("#line-chart-my").dxChart({
    dataSource: [
		<?php $imon = 1;
		$monthArr = array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		foreach($monthArr AS $key => $month){
			echo ($imon != 1)?',':'';
			$fday = date('Y-m-d', strtotime($currYear.'-'.$imon.'-01'));
			$lday  = date('Y-m-t', strtotime($currYear.'-'.$imon.'-01'));
			$qOrder = "SELECT SUM(amount) AS sum FROM tbl_order WHERE DATE_FORMAT(od_date,'%Y-%m-%d') >= '$fday' AND DATE_FORMAT(od_date,'%Y-%m-%d') <= '$lday' AND payment_status = 'Paid'";
			$rsOrder = exec_query($qOrder, $con);
			$rowOrder = mysqli_fetch_object($rsOrder);
			$orderAmount = (isset($rowOrder->sum) && $rowOrder->sum > 0)?$rowOrder->sum:0;
			echo '{ month: '."'".$month."'".', order: '.$orderAmount.' }';
		$imon++; } ?>
	],
    commonSeriesSettings: { argumentField: "month" },
    series: [ { valueField: "order", name: "Order", color: "#fad733" } ],
    tooltip:{ enabled: true, font: { size: 16 } },
    legend: { visible: false },
	valueAxis:{ grid:{ color: '#9D9EA5', width: 0.2 } }
});
</script>