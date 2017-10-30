<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>
<?php $arrNum = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
$arrOther = array('@', '#', '$', '^', '&', '*', '+', '-', '|', ';', ':', '<', '>', '.', '/');
$arrAlpha = range('a', 'z');

function getBrandOnBrandPage($chr, $con, $type){
	$condi = '';
	if($type == '0-1'){
		global $arrNum;
		$chr = $type;
		foreach($arrNum AS $value1){ $condi.= " brand_name LIKE '".$value1."%' OR "; }
		$condi.= " brand_name LIKE '0%' ";
		$brandQ = "SELECT * FROM tbl_brand WHERE $condi";
	}
	elseif($type == 'Other'){
		global $arrOther;
		$chr = $type;
		foreach($arrOther AS $value2){ $condi.= " brand_name LIKE '".$value2."%' OR "; }
		$condi.= " brand_name LIKE '!%' ";
		$brandQ = "SELECT * FROM tbl_brand WHERE $condi";
	}
	else{
		$brandQ = "SELECT * FROM tbl_brand WHERE brand_name LIKE '".$chr."%' ORDER BY brand_name ";
	}
	$brandRs = exec_query($brandQ, $con);
	if(mysql_num_rows($brandRs)){ ?>
		<li>
			<h1><?php echo $chr; ?></h1>
			<ul>
			<?php while($brandRow = mysql_fetch_object($brandRs)){ ?>
				<li style="cursor:pointer;" onClick="window.location='<?php echo siteUrl; ?>all/b-<?php echo $brandRow->slug; ?>/'">
					<?php echo $brandRow->brand_name; ?>
                </li>
			<?php } ?>
			</ul>
		</li><?php
	}
}
?>


    
<link href="<?php echo siteUrl; ?>credit/ccvalidate.css" rel="stylesheet" type="text/css" />

  
<section class="block-pt5">
    <div class="container">
        <div class="other-page-holder">
             <div class="col-md-12">
<div class="brandx">
        	<h3> All Brands </h3>
            <ul>
            	<?php
                getBrandOnBrandPage('', $con, '0-1');
                getBrandOnBrandPage('', $con, 'Other');
				foreach($arrAlpha AS $val){	getBrandOnBrandPage($val, $con, 'alpha'); }
				?>
            </ul>
        </div>
             </div>
            <div class="clr"></div>
        </div>
            
    </div>
</section>
    

    
    
<?php include("include/new_footer.php"); ?>
<?php include("include/new_bottom.php"); ?>