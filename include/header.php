<script type="text/javascript" src="<?php echo siteUrl; ?>js/jquery.min.js"></script>
<style>
.suggest_link_over:hover{ color:#D93A40 !important; text-decoration:underline; background:#ececec; }
.signupType{ display:none !important; }
input#headerSearch:required { box-shadow:none; }
input#headerSearch:invalid { box-shadow:none; }
#ui-id-1{ height:auto !important; max-height:350px !important; overflow:auto; }
</style>

<div id="scrollHeader">
	<div id="scrollcontent">
    	<div id="scrolllogo">
   	    	<a href="<?php echo siteUrl; ?>"><img src="<?php echo siteUrl; ?>images/1minilog1.png" /></a>
        </div>
    	<div id="scrollnav"><?php include("navigation_top.php"); ?></div>
        <ul id="useritems">

            <?php if(isset($_SESSION['user']) && isset($_SESSION['user_email'])){?>
                <?php include 'include/userHeaderMenu.php'; ?>
            <?php } else { ?>
                <li class="links">
                    <a href="<?php echo siteUrl; ?>userlogin/" ><img src="<?php echo siteUrl; ?>images/login-icon2.png" /></a></a>
                </li><!-- href="javascript:void(0)" onclick="activelogin('loginscreen')" -->
            <?php } ?>
            <li class="carticon"><a href="<?php echo siteUrl; ?>cart/">
                <table><tr>
                    <td><img src="<?php echo siteUrl; ?>images/shopping-purse.png" /></td>
                </tr></table>
            </a>
            <!--<li class="carticon">
            	<a href="<?php echo siteUrl; ?>cart/">
                    <ul>
                	   <li><img src="<?php echo siteUrl; ?>images/shopping-purse.png" /></li>
                	   <li><span class="cartno"><?php echo (isset($cartNo)) ? $cartNo : '' ; ?></span></li>
                    </ul>
            	</a>
           	</li>-->
        </ul>
        <div id="clr">	</div>
    </div>
</div>

<!--<script type="text/javascript" src="<?php //echo siteUrl; ?>js/suggest.js" ></script>-->
