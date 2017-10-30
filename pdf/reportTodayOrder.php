<?php error_reporting(0); session_start();
include '../include/config.php';
$tdate = date('Y-m-d');
// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$r = rand(10000000, 99999999);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HarisH'.$r);
$pdf->SetTitle('TodayOrdered'.$r);
$pdf->SetSubject('TodayOrdered'.$r);
$pdf->SetKeywords('TodayOrdered'.$r);

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

$tbl = '
<style>
td{
	font-family:Tahoma, Geneva, sans-serif; font-size:10px;
}
th{ font-weight:bold; font-size:10px; }
</style>
<table border="1" cellpadding="5" cellspacing="0" nobr="true">
	
	<tr>
  		<td>
			<table border="0" cellpadding="3" cellspacing="0" nobr="true">
				<tr>
  					<td align="left"><h3>List of shops that have not sent their order on '.date('d M, Y', strtotime($tdate)).'</h3></td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table border="0" cellpadding="3" cellspacing="0" nobr="true" style="font-family:Tahoma, Geneva, sans-serif">
				<tr>
  					<td>
						<table border="1" cellpadding="3" cellspacing="0" nobr="true">
							<tr style="background-color:#ececec;">
								<th>Customer Name</th>
								<th>Contact</th>
								<th>Contact No.</th>
							</tr>
							<tr>
								<th colspan="4">Customers have not send their orders,</th>
							</tr>
							';
							$shop = '';
							$query = "SELECT tbl_order.shop_id, tbl_shop.name, tbl_shop.shopname, tbl_shop.email, tbl_shop.phone FROM tbl_order
							LEFT JOIN tbl_shop ON tbl_shop.recid = tbl_order.shop_id
							WHERE DATE_FORMAT(tbl_order.datetime,'%Y-%m-%d') = '$tdate'";
							$rs = mysql_query($query);
							while($row = mysql_fetch_object($rs)){
								$shop .= ($shop == '') ? $row->shop_id : ','.$row->shop_id ;
							}
							$condi = ($shop == '')?'':" WHERE recid NOT IN ($shop) ";
							$query1 = "SELECT tbl_shop.name, tbl_shop.shopname, tbl_shop.email, tbl_shop.phone FROM tbl_shop $condi ";
							$rs1 = mysql_query($query1);
							while($row1 = mysql_fetch_object($rs1)){
								$tbl .= '<tr>
									<td>'.$row1->shopname.'</td>
									<td>'.$row1->name.'</td>
									<td>'.$row1->phone.'</td>
								</tr>';
							}
							$tbl .= '<tr>
								<th colspan="4">Customers have send their orders,</th>
							</tr>';
$query = "SELECT tbl_order.shop_id, tbl_shop.name, tbl_shop.shopname, tbl_shop.email, tbl_shop.phone FROM tbl_order
							LEFT JOIN tbl_shop ON tbl_shop.recid = tbl_order.shop_id
							WHERE DATE_FORMAT(tbl_order.datetime,'%Y-%m-%d') = '$tdate'";
							$rs = mysql_query($query);
							while($row = mysql_fetch_object($rs)){
								$tbl .= '<tr>
									<td>'.$row->shopname.'</td>
									<td>'.$row->name.'</td>
									<td>'.$row->phone.'</td>
								</tr>';
							}
							$tbl .= '
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

$pdf->writeHTML($tbl, true, false, false, false, '');

//Close and output PDF document
$pdf->Output('TodayOrdered.pdf', 'I');