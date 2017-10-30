<?php ob_start('ob_gzhandler'); 
error_reporting(0);
session_start();
include '../include/config.php';
// on the beginning of your script save original memory limit
$original_mem = ini_get('memory_limit');
// then set it to the value you think you need (experiment)
ini_set('memory_limit','640M');
ini_set('max_execution_time', 300);
$ids = $_REQUEST['id'];
$idArr = explode('-', $ids); 
// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1', false);
$r = rand(10000000, 99999999);
// set document information
$pdf->setFontSubsetting(false);// add by mefor impr per
$pdf->SetTitle('PackingSlip'.$r);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 7);

/////////////////////////////////////////////////////////////////////////////////////////// start for loop
foreach($idArr AS $id){
	$tbl = '';
	$tbl1 = '';
	$order = mysql_fetch_object(mysql_query("SELECT tbl_order.*, tbl_order.note AS onote,
	tbl_shop.address, tbl_shop.shopname FROM tbl_order
	LEFT JOIN tbl_shop ON tbl_shop.recid = tbl_order.shop_id
	WHERE tbl_order.recid = '$id'"));

	$pdf->SetFont('helvetica', '', 8);
	
	$ddate = date('d M, Y', strtotime($order->del_date));
	if($order->del_date == '0000-00-00'){ $ddate = 'Not Set'; }
	
	$tbl = '
	<style>
	td{
		font-family:Tahoma, Geneva, sans-serif;
	}
	</style>
	<table border="1" cellpadding="2" cellspacing="0" nobr="true">
		
		<tr>
			<td>
				<table border="0" cellpadding="3" cellspacing="0" nobr="true">
					<tr>
						<td align="left"><h3 style="font-size:10px;">HOLLYWOOD ('.$order->orderId.')</h3></td>
						<td align="right">Order Date: '.date('d M, Y h:i A', strtotime($order->datetime)).'</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr>
			<td>
				<table border="0" cellpadding="3" cellspacing="0" nobr="true">
					<tr>
						<td align="left">
							
							<table border="0" cellpadding="3" cellspacing="0" style="width:660px;">
								<tr>
									<td align="left">Shop Name: <b>'.$order->shopname.'</b></td>
									<td align="right">Packing Slip No.: <b>#</b> (Finished Products) </td>
								</tr>
								<tr>
									<td align="left">Delivery Address: <b>'.$order->address.'</b></td>
									<td align="right">Delivery Date: <b>'.$ddate.'</b></td>
								</tr>
<tr>
<td colspan="2" align="left"><b>Note: </b>'.$order->onote.'</td>
</tr>
							</table>
							
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr>
			<td>
				<table border="0" cellpadding="2" cellspacing="0" nobr="true" style="font-family:Tahoma, Geneva, sans-serif">
					<tr>
						<td>
							<table border="0" cellpadding="0" cellspacing="0" nobr="true">
								<tr>
									<td>
										<table border="0" cellpadding="0" cellspacing="0" width="100%" nobr="true">
								<tr>';
								$i = 1; $j = 1;
								$qty = '';
								$cat_rs = mysql_query("SELECT * FROM tbl_category WHERE type = 'finish' ORDER BY category", $con);
								$total = mysql_num_rows($cat_rs);
								
								/*$mod=$total%4;
								$tablePerCol = ($total-$mod) / 4;*/
								$tbl.='<td style="width:25%; padding:0px 2px; margin-bottom:20px; list-style:none; float: left;">';
								while($cat_row = mysql_fetch_object($cat_rs)){
									
									$tbl .= ' <h6 style="font-size:9px;">
												<table border="1" cellpadding="2" cellspacing="0" nobr="true">
													<tr>
										<td style="width:122px;">'.$cat_row->category.'    </td>
										<td width="33"><span style="text-align:right;">Qty</span></td>
													</tr>
												</table>';
							/*echo "SELECT *, (SELECT od_qty FROM tbl_order_item WHERE pdid=tbl_product.recid AND odid = '$id')  as od_qty FROM tbl_product WHERE tbl_product.group = '$cat_row->recid'";*/
									$pro_rs = mysql_query("SELECT *, (SELECT od_qty FROM tbl_order_item WHERE pdid=tbl_product.recid AND odid = '$id')  as od_qty FROM tbl_product WHERE tbl_product.group = '$cat_row->recid'", $con);
									while($pro_row = mysql_fetch_object($pro_rs)){
										$qty = '-'; 
										$qty_chk = $pro_row->od_qty;
										if($qty_chk > 0){ $qty = $pro_row->od_qty; }
												
									$tbl .= '<table border="1" cellpadding="1" cellspacing="0" nobr="true" style="font-size:10px; font-weight:normal;">
											<tr>
												<td style="width:122px;">'.$pro_row->pname.'</td>
												<td width="33"><span style="text-align:right;">'.$qty.'</span></td>
											</tr>
										</table>';
									}
									$tbl .= '</h6>';
									/*$tbl .= (($i%4) == 0)?'</tr><tr>':'	';*/
									
									//if($tablePerCol == $i){ 
									if(($j == 5 && $i == 5) || ($j == 12 && $i == 7) || ($j == 15 && $i == 3)){
										$tbl.= '</td><td style="width:25%; list-style:none; float: left;">';
										$i=0;
									}
									$i++; $j++;
								}
								
						$tbl .= '	</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
	</table>
	';
	
	$tbl1 = '
	<style>
	td{
		font-family:Tahoma, Geneva, sans-serif
	}
	</style>
	<table border="1" cellpadding="2" cellspacing="0" nobr="true">
		
		<tr>
			<td>
				<table border="0" cellpadding="3" cellspacing="0" nobr="true">
					<tr>
						<td align="left"><h3 >HOLLYWOOD ('.$order->orderId.')</h3></td>
						<td align="right">Order Date: '.date('d M, Y h:i A', strtotime($order->datetime)).'</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr>
			<td>
				<table border="0" cellpadding="2" cellspacing="0" nobr="true">
					<tr>
						<td align="left">
							
							<table border="0" cellpadding="3" cellspacing="0" style="width:660px;">
								<tr>
									<td align="left">Shop Name: <b>'.$order->shopname.'</b></td>
									<td align="right">Packing Slip No.: <b>#</b> (Consumable Products) </td>
								</tr>
								<tr>
									<td align="left">Delivery Address: <b>'.$order->address.'</b></td>
									<td align="right">Delivery Date: <b>'.$ddate.'</b></td>
								</tr>
<tr>
<td colspan="2" align="left"><b>Note: </b>'.$order->onote.'</td>
</tr>
							</table>
							
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr>
			<td>
				<table border="0" cellpadding="2" cellspacing="0" nobr="true" style="font-family:Tahoma, Geneva, sans-serif">
					<tr>
						<td>
							<table border="0" cellpadding="0" cellspacing="0" nobr="true">
								<tr>
									<td>
										<table border="0" cellpadding="0" cellspacing="0" width="100%" nobr="true">
								<tr>';
								$i = 1; $j = 1;
								$qty = '';
								$cat_rs = mysql_query("SELECT * FROM tbl_category WHERE type = 'consume' ORDER BY category", $con);
								$tbl1 .= '<td style="width:25%; padding:0px 2px; margin-bottom:20px; list-style:none; float: left;">';
								while($cat_row = mysql_fetch_object($cat_rs)){
							
									$tbl1 .= ' <h6 style="font-size:9px;">
												<table border="1" cellpadding="2" cellspacing="0" nobr="true">
													<tr>
										<td style="width:122px;">'.$cat_row->category.'     </td>
										<td width="33"><span style="text-align:right;">Qty</span></td>
													</tr>
												</table>';
									
									$pro_rs = mysql_query("SELECT * FROM tbl_product WHERE tbl_product.group = '$cat_row->recid'", $con);
									while($pro_row = mysql_fetch_object($pro_rs)){
										$qty = '-';
										$chk_rs = mysql_query("SELECT od_qty FROM tbl_order_item WHERE pdid = '$pro_row->recid' AND odid = '$id'");
										$qty_chk = mysql_num_rows($chk_rs);
										if($qty_chk > 0){ $qty = mysql_fetch_object($chk_rs)->od_qty; }
												
							
							$tbl1 .= '<table border="1" cellpadding="1" cellspacing="0" nobr="true" style="font-size:10px; font-weight:normal;">
											<tr>
												<td style="width:122px;">'.$pro_row->pname.'</td>
												<td width="33"><span style="text-align:right;">'.$qty.'</span></td>
											</tr>
										</table>';
									}
									$tbl1 .= '</h6>';
									
									if(($j == 2 && $i == 2) || ($j == 6 && $i == 4) || ($j == 8 && $i == 2)){
										$tbl1.= '</td><td style="width:25%; list-style:none; float: left;">';
										$i=0;
									}
									$i++; $j++;
								
								}
						
						$tbl1 .= '	</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
	</table>
	';
	
	//echo $tbl;
	// add a page
	$pdf->AddPage();
	$pdf->writeHTML($tbl, true, false, false, false, ''); // finished - khana pina
	// add a page
	$pdf->AddPage();
	$pdf->writeHTML($tbl1, true, false, false, false, ''); // consumable - pattal done
}
//Close and output PDF document
$pdf->Output('invoice.pdf', 'I');