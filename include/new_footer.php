<footer  class="footer">
  <div class="container">
    <div class="foot-top">
      <div class="row">
      
      <div class="col-sm-4">
          <h4>STAY CONNECTED<span></span></h4>
          <ul class="foot-nav">
            <div class="top-search-form">
           
        <form action="<?php echo siteUrl; ?>action_model.php" method="post">
            <input type="hidden" value="newsletterSignUp" name="action" class="subscribe" />
       <div class="input-group"> <input type="email" id="footerEmail" type="email" required name="email" placeholder="Your Email Address" autocomplete="off" class="form-control">
           <span class="input-group-addon"> <button type="submit" value="SUBMIT" class="ser-butt-two">Subscribe</button> </span> </div> </form>  
        </div>
          </ul>
          <ul class="social">
            <li> <a href="#" class="fb"><img src="<?php echo siteUrl; ?>/images/new/soci-fb.png"></a></li>
            <li> <a href="#" class="tw"><img src="<?php echo siteUrl; ?>/images/new/soci-twit.png"></a></li>
            <!--<li> <a href="#" class="gp"><img src="<?php echo siteUrl; ?>/images/new/soci-youtube.png"> </a></li>-->
            <li> <a href="#" class="cm"><img src="<?php echo siteUrl; ?>/images/new/soci-insta.png"></a></li>
            <li> <a href="#" class="gp"><img src="<?php echo siteUrl; ?>/images/new/soci-chat.png"> </a></li>
            <!--<li> <a href="#" class="cm"><img src="<?php echo siteUrl; ?>/images/new/soci-chat1.png"></a></li>-->
          </ul>
        </div>
        
        <div class=" col-md-2 col-sm-3 col-xs-12 pull-right" >
          <h4>SUPPORT<span></span></h4>
          <ul class="foot-nav">
            <?php
    	$support = mysqli_fetch_object(exec_query("SELECT content FROM tbl_manage WHERE type = 'support'", $con))->content;
        $sArr = explode('|', $support);
		foreach($sArr as $key => $value){ if($value != '>'){ ?>
        	<li> <a href="#"> <?php echo $value; ?> </a> </li>
        <?php } } ?>
          </ul>
        </div>
         <div class=" col-md-2 col-sm-3 col-xs-12 pull-right">
          <h4>HELP <span></span></h4>
          <ul class="foot-nav">
            <li> <a href="<?php echo siteUrl; ?>accept_cards/">Payment</a></li>
            <li> <a href="<?php echo siteUrl; ?>faq/">FAQs</a></li>
            <li> <a href="<?php echo siteUrl; ?>contactUs/">Contact </a></li>
          </ul>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 pull-right">
          <h4>JHM SHOP<span></span></h4>
          <ul class="foot-nav">
            <li> <a href="<?php echo siteUrl; ?>about/"> About us</a></li>
            <li> <a href="<?php echo siteUrl; ?>return/"> Return Policy</a></li>
            <li> <a href="<?php echo siteUrl; ?>terms/"> Terms & Conditions</a></li>
            <li> <a href="<?php echo siteUrl; ?>shipDetail/"> Shipping Detail</a></li>
          </ul>
        </div>
    
      </div>
      <!--row-->
      
    </div>
    <!--foot-top-->
  </div>
  <!--container-->
  
</footer>
<script src="<?php echo siteUrl; ?>/js/new/bootstrap.min.js"></script>
<script src="<?php echo siteUrl; ?>/js/new/bootstrap-select.js"></script>
<script src="<?php echo siteUrl; ?>/js/new/main.js"></script>
<script src="<?php echo siteUrl; ?>js/new/enscroll-0.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $(".mega-dropdown").hover(            
        function() {
            $('.mega-dropdown-menu', this).not('.in .mega-dropdown-menu').stop(true,true).toggle();
            $(this).toggleClass('open');        
        },
        function() {
            $('.mega-dropdown-menu', this).not('.in .mega-dropdown-menu').stop(true,true).toggle();
            $(this).toggleClass('open');       
        }
    );	
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
<script>
$('#bodyDiv, #scrollbox3').enscroll({
  showOnHover: false,
  verticalTrackClass: 'track3',
  verticalHandleClass: 'handle3'
});
</script>
<script>
    function remove_product(user_id, product_id, price) {
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }
        else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == 1) {
                    getCartDataInDialog();
                }
                else {
                    alert('Oops!! Product not added to cart,');
                }
            }
        }
        xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=removeToCartFromCategory&product_id=" + product_id + "&price=" + price + "&dataTempId=c3vcfa1543652de90hch14lkf217900.cloud.uk", true);
        xmlhttp.send();
    }
</script>