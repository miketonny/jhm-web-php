<div id="nav">
	<ul>
    	<li> <a href="<?php echo siteUrl; ?>"> Home </a> </li>


        <?php $feaTagRs = exec_query("SELECT title FROM tbl_tag WHERE is_menu = 1", $con);
while ($feaTagRow = mysqli_fetch_object($feaTagRs)) {?>
        	<li> <a href="<?php echo siteUrl . 'online-sale/' . str_replace(' ', '-', $feaTagRow->title); ?>/"> <?php echo $feaTagRow->title; ?> </a> </li>
		<?php }?>

        <li> <a href="<?php echo siteUrl; ?>deals/"> Deals </a> </li>
     <div class="clr"> </div>
    </ul>
</div> <!--NAVIGATION-->