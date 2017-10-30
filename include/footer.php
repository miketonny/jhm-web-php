<div id="footerWrapper">
	<div id="footer">
    <div id="newsletterBox">
   	  <div id="newsletter">
			<h3> STAY CONNECTED </h3>
		<form action="<?php echo siteUrl; ?>action_model.php" method="post">
				<input id="footerEmail" type="email" required name="email" placeholder="Your Email Address" />
				<input type="submit" value="SUBMIT" class="subscribe" />
				<input type="hidden" value="newsletterSignUp" name="action" class="subscribe" />
			</form>
		   <ul id="socialBar">
           	  <li><img src="<?php echo siteUrl; ?>images/social-facebook.png"/></li>
           	  <li><img src="<?php echo siteUrl; ?>images/social-twitter.png"/></li>
           	  <li><img src="<?php echo siteUrl; ?>images/social-youtube.png"/></li>
           	  <li><img src="<?php echo siteUrl; ?>images/social-instagram.png"/></li>
              <li><img src="<?php echo siteUrl; ?>images/social-wechat.png"/></li>
              <li><img src="<?php echo siteUrl; ?>images/social-webao.png"/></li>
           </ul>
		</div>
    </div>
	<ul><strong>JHM Shop</strong>
    	<li><!--<img src="<?php echo siteUrl; ?>images/icon_aout.png">--> <a href="<?php echo siteUrl; ?>about/"> About us </a> </li>
        <!--<li> <a href="<?php echo siteUrl; ?>career/"> Career </a> </li>-->
        <li><!--<img src="<?php echo siteUrl; ?>images/ReturnPolicy.png">--><a href="<?php echo siteUrl; ?>return/"> Return Policy </a> </li>
        <li><!--<img src="<?php echo siteUrl; ?>images/tems.png">--> <a href="<?php echo siteUrl; ?>terms/"> Terms & Conditions </a> </li>
        <li><!--<img src="<?php echo siteUrl; ?>images/shippindetail.png">--> <a href="<?php echo siteUrl; ?>shipDetail/"> Shipping Detail </a> </li>
    </ul>
    
    <ul><strong>Help</strong>
    	<!--<li> <a href="<?php echo siteUrl; ?>payment/"> Payment </a> </li>-->
        <li> <!--<img src="<?php echo siteUrl; ?>images/payment.png">--> <a href="<?php echo siteUrl; ?>accept_cards/"> Payment </a> </li>
        <!---<li> <a href="<?php echo siteUrl; ?>"> Shiping </a> </li>-->
        <!--<li> <a href="<?php echo siteUrl; ?>cancellationReturn/"> Cancellation & Returns </a> </li>-->
        <li> <!--<img src="<?php echo siteUrl; ?>images/11.png" />--> <a href="<?php echo siteUrl; ?>faq/"> FAQs </a> </li>
        <li> <!--<img src="<?php echo siteUrl; ?>images/icon_cotact.png">--><a href="<?php echo siteUrl; ?>contactUs/"> Contact Us </a> </li>
    </ul>
    
    <ul><strong>Support </strong><?php
    	$support = mysql_fetch_object(exec_query("SELECT content FROM tbl_manage WHERE type = 'support'", $con))->content;
        $sArr = explode('|', $support);
		foreach($sArr as $key => $value){ if($value != '>'){ ?>
        	<li> <a href="#"> <?php echo $value; ?> </a> </li>
        <?php } } ?>
    </ul>
    
    
    <div class="clr"> </div>
    
</div>
</div>
 
<a href="#" class="scrollToTop"></a>
<script>
$(document).ready(function(){
	//Check to see if the window is top if not then display button
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) { $('.scrollToTop').fadeIn(); }
		else { $('.scrollToTop').fadeOut(); }
	});
	//Click event to scroll to top
	$('.scrollToTop').click(function(){
		$('html, body').animate({scrollTop : 0},800);
		return false;
	});
});
</script>
<div id="overlay4Cart" class="overlay2" style="background: url('<?php echo siteUrl; ?>images/load.gif') no-repeat scroll 50% 270px rgba(255, 255, 255, 0.7); height: 900px; top:0px; position: fixed; width: 100%; z-index:1000; display:none;"></div>