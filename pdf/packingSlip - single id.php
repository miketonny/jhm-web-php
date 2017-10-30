<?php session_start();
include '../include/config.php';
$ids = $_GET['id'];
$idArr = explode('-', $ids);

$order = mysql_fetch_object(mysql_query("SELECT tbl_order.*, tbl_shop.address, tbl_shop.shopname FROM tbl_order LEFT JOIN tbl_shop ON tbl_shop.recid = tbl_order.shop_id WHERE tbl_order.recid = '$id'"));

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$r = rand(10000000, 99999999);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HarisH'.$r);
$pdf->SetTitle('PackingSlip'.$r);
$pdf->SetSubject('PackingSlip'.$r);
$pdf->SetKeywords('PackingSlip'.$r);

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 048', PDF_HEADER_STRING);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

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

// add a page
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 7);

$ddate = date('d M, Y', strtotime($order->del_date));
if($order->del_date == '0000-00-00'){ $ddate = 'Not Set'; }

$tbl = '
<style>
td{
	font-family:Tahoma, Geneva, sans-serif
}
</style>
<table border="1" cellpadding="5" cellspacing="0" nobr="true">
	
	<tr>
  		<td>
			<table border="0" cellpadding="3" cellspacing="0" nobr="true">
				<tr>
  					<td align="left"><h3>HOLLYWOOD</h3></td>
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
						</table>
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table border="0" cellpadding="3" cellspacing="0" nobr="true" style="font-family:Tahoma, Geneva, sans-serif">
				<tr>
  					<td>
						<table border="0" cellpadding="3" cellspacing="0" nobr="true">
							<tr>';
					$i = 1;
					$qty = '';
					$cat_rs = mysql_query("SELECT * FROM tbl_category ORDER BY category", $con);
					while($cat_row = mysql_fetch_object($cat_rs)){
						
						$tbl .= '<td><h3>
											<table border="0" cellpadding="0" cellspacing="0" nobr="true">
												<tr>
													<td style="width:80px;">'.$cat_row->category.' </td>
													<td width="20"><span style="text-align:right;">Qty</span></td>
												</tr>
											</table>
										</h3>';
						
							$pro_rs = mysql_query("SELECT * FROM tbl_product WHERE tbl_product.group = '$cat_row->recid'", $con);
							while($pro_row = mysql_fetch_object($pro_rs)){
								$qty = '-';
							$chk_rs = mysql_query("SELECT od_qty FROM tbl_order_item WHERE pdid = '$pro_row->recid' AND odid = '$id'");
							$qty_chk = mysql_num_rows($chk_rs);
							if($qty_chk > 0){ $qty = mysql_fetch_object($chk_rs)->od_qty; }
								
							 
										
											
								$tbl .= '<table border="0" cellpadding="2" cellspacing="0" nobr="true">
									<tr>
										<td style="width:80px;">'.$pro_row->pname.'</td>
										<td width="20"><span style="text-align:right;">'.$qty.'</span></td>
									</tr>
								</table>';
								
								 
								
						}
						$tbl .= '</td>';
						$tbl .= (($i%4) == 0)?'</tr><tr>':'	';
						
					$i++; }
					
					$tbl .= '
				</tr>
			</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
 	
	<tr>
  		<td>
			<table border="0" cellpadding="3" cellspacing="0" nobr="true">
				<tr>
  					<td align="left">--</td>
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
<table border="1" cellpadding="5" cellspacing="0" nobr="true">
	
	<tr>
  		<td>
			<table border="0" cellpadding="3" cellspacing="0" nobr="true">
				<tr>
  					<td align="left"><h3>HOLLYWOOD</h3></td>
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
								<td align="right">Packing Slip No.: <b>#</b> (Consumable Products) </td>
							</tr>
							<tr>
								<td align="left">Delivery Address: <b>'.$order->address.'</b></td>
								<td align="right">Delivery Date: <b>'.$ddate.'</b></td>
							</tr>
						</table>
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table border="0" cellpadding="3" cellspacing="0" nobr="true" style="font-family:Tahoma, Geneva, sans-serif">
				<tr>
  					<td>
						<table border="0" cellpadding="3" cellspacing="0" nobr="true">
							<tr>';
					/*$i = 1;
					$cat_rs = mysql_query("SELECT * FROM tbl_category ORDER BY category", $con);
					while($cat_row = mysql_fetch_object($cat_rs)){
										
						$pro_rs = mysql_query("SELECT * FROM tbl_order_item WHERE odid = '$id' AND cat_id = '$cat_row->recid'", $con);
						while($pro_row = mysql_fetch_object($pro_rs)){
							$pro = mysql_fetch_object(mysql_query("SELECT pname, recid, price FROM tbl_product WHERE tbl_product.recid = '$pro_row->pdid'", $con));
								
								$tbl1 .= '<td style="width:24%;" valign="top" >
										<h3>
											<table border="0" cellpadding="0" cellspacing="0" nobr="true">
												<tr>
													<td style="width:120px;">'.$cat_row->category.' </td>
													<td width="30"><span style="text-align:right;">Qty</span></td>
												</tr>
											</table>
										</h3>';
											
								$tbl1 .= '<table border="0" cellpadding="2" cellspacing="0" nobr="true">
									<tr>
										<td style="width:120px;">'.$pro->pname.'</td>
										<td width="30"><span style="text-align:right;">'.$pro_row->od_qty.'</span></td>
									</tr>
								</table>';
								
								$tbl1 .= '<br/>&nbsp;<br/></td>';
								$tbl1 .= (($i%4) == 0)?'</tr><tr>':'	';
						}
						
					$i++; }*/
					$tbl1 .= '<td> -- </td>';
					
					$tbl1 .= '
				</tr>
			</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
 	
	<tr>
  		<td>
			<table border="0" cellpadding="3" cellspacing="0" nobr="true">
				<tr>
  					<td align="left">--</td>
				</tr>
			</table>
		</td>
	</tr>
	
</table>
';


$pdf->writeHTML($tbl, true, false, false, false, ''); // finished - khana pina

// add a page
$pdf->AddPage();
$pdf->writeHTML($tbl1, true, false, false, false, ''); // consumable - pattal done

//Close and output PDF document
$pdf->Output('invoice.pdf', 'I');