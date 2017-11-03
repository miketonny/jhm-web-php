<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>
<style type="text/css">  
    .borderless td, .borderless th {
    border: none !Important;
    }
</style>

 <section class="block-pt5">
    <div class="container">   
        <div class="other-page-holder">
              <div class="table-responsive">
                <h1>Transaction Incomplete</h1>
                <table border="0" cellpadding="5" cellspacing="1" class="table borderless" style="text-align: center;">
                    <tr class="entryTableHeader">
                        <td>Payment was not successful, please try again. If the problem persists, please contact our support team at 
        <a href="mailto:support@jhm.co.nz">support@jhm.co.nz</a></td>
                    </tr>
                    <!--<tr>
                        <td width="150" class="label" align="center">Some Problem in Placing Order, </td>
                    </tr>-->
                    <tr>
                        <td class="label" align="center">
                        	<!--<button type="button" class="button" onclick="window.location='<?php echo siteUrl; ?>product-search/'">Continue Shopping</button>-->
                            <button type="button" class="button" style="margin-left: 10px;" onclick="window.location='<?php echo siteUrl; ?>'">Home</button>
                        </td>
                    </tr>
                </table>       
              </div>
        
         <div class="clr"> </div>
	</div>
        
        
    </div>
 </section>

<?php include("include/new_footer.php"); ?>

<script> //setTimeout(function(){ window.location='<?php //echo siteUrl; ?>'; }, 7000); </script>
<?php include("include/new_bottom.php"); ?>