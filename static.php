<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>
<?php 
if(!isset($_GET['type'])){ redirect(siteUrl); die(); }
$type = $_GET['type'];
$data = mysql_fetch_object(exec_query("SELECT * FROM tbl_manage WHERE type = '$type'", $con));
if($data->type == 'about'){ $h = 'About Us'; }
elseif($data->type == 'terms'){ $h = 'Terms & Conditions'; }
elseif($data->type == 'career'){ $h = 'Career'; }
elseif($data->type == 'return'){ $h = 'Return Policy'; }
elseif($data->type == 'shipping'){ $h = 'Shipping Detail'; }
elseif($data->type == 'accept_cards'){ $h = 'Payment'; }
elseif($data->type == 'cancellationReturn'){ $h = 'Cancellation & Returns'; }
elseif($data->type == 'payment'){ $h = 'Payment'; }
elseif($data->type == 'faq'){ $h = 'FAQ'; }
?>

   <section class="block-pt5">
    <div class="container">
        <div class="other-page-holder">   
    	<div  class="col-md-12">
    	<h2 style="font-weight:normal; padding-bottom:10px; border-bottom:1px solid #999; margin-bottom:15px;"> <?php echo $h; ?> </h2>
    	<?php echo $data->content; ?>
		<div style="clear:both;"></div>
    </div>
            
           <div class="clr"> </div>
        </div>
        </div>
        
</section> 

    
    
<?php include("include/new_footer.php"); ?>
<?php include("include/new_bottom.php"); ?>
