<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>
<?php chkParam($_SESSION['user'], siteUrl);
$user_id = $_SESSION['user'];
/* get cart sum */
$sumOfCart = getCartValue($user_id, $con);
/* remember that if there are some changes in this code, than also change, on cart page , cart.php paymentprocess */
$i = 1;
$grandTotal = 0;
$grandTotalOriginalPrice = 0;
$promotionDiscount = 0;
$promoCodeDiscount = 0;
$promoCodeChk = 'no';
$amGst = 0;
$cdate = date('Y-m-d H:i:s');
$cartQuery = "SELECT tbl_cart.*, tbl_product.product_name, tbl_product.product_description, tbl_product_color.color_code FROM tbl_cart
LEFT JOIN tbl_product ON tbl_product.product_id = tbl_cart.product_id
LEFT JOIN tbl_product_color ON tbl_product_color.color_id = tbl_cart.color_id
WHERE tbl_cart.user_id = '".$user_id."' ORDER BY tbl_cart.datetime DESC";
$pro_rs = mysql_query($cartQuery, $con);
$numCart = mysql_num_rows($pro_rs);
if($numCart > 0){
	while($pro_row = mysql_fetch_object($pro_rs)){
		include 'include/promotionCalc.php';
	}
}else{ redirect(siteUrl); die(); }
/* remember that if there are some changes in this code, than also change, on cart page , cart.php paymentprocess */
?>

    
  <section class="block-pt5">

    <div class="container">
        <div class="shopping-holder">  
    <div class="col-sm-8">
            	<div id="address">
        	<h3> Shipping Address </h3>
            <div id="existing">
            	<ul>  <strong>Select an existing address </strong>
                	<?php $address_rs = exec_query("SELECT * FROM tbl_order WHERE user_id = ".$user_id." AND od_shipping_postal_code != '' AND od_shipping_address != '' AND od_shipping_locality != '' GROUP BY od_shipping_postal_code, od_shipping_address, od_shipping_locality", $con);
					if(mysql_num_rows($address_rs) > 0){
						while($address_row = mysql_fetch_object($address_rs)){
							$odId = $address_row->order_id;
						?>
							<li>
                                <input name="radio" type="radio" value="<?php echo $odId; ?>" onclick="setAddressNow(this.value);" />
                                <strong>Address 1</strong>
                                <p> <?php echo $address_row->od_shipping_first_name.' '.$address_row->od_shipping_last_name; ?> </p>
                                <p> <?php echo $address_row->od_shipping_address; ?> </p>
                                <p> <?php echo $address_row->od_shipping_city; ?> </p>
                                <p> <?php echo $address_row->od_shipping_postal_code; ?> </p>
                                <p> <?php echo $address_row->od_shipping_phone; ?> </p>
                                
                                <input type="hidden" id="od_first_name<?php echo $odId; ?>" value="<?php echo $address_row->od_shipping_first_name; ?>" />
                                <input type="hidden" id="od_last_name<?php echo $odId; ?>" value="<?php echo $address_row->od_shipping_last_name; ?>" />
                                <input type="hidden" id="od_locality<?php echo $odId; ?>" value="<?php echo $address_row->od_shipping_locality; ?>" />
                                <input type="hidden" id="geocomplete<?php echo $odId; ?>" value="<?php echo $address_row->od_shipping_address; ?>" />
                                <input type="hidden" id="od_city<?php echo $odId; ?>" value="<?php echo $address_row->od_shipping_city; ?>" />
                                <input type="hidden" id="od_state<?php echo $odId; ?>" value="<?php echo $address_row->od_shipping_state; ?>" />
                                <input type="hidden" id="od_postal_code<?php echo $odId; ?>" value="<?php echo $address_row->od_shipping_postal_code; ?>" />
                                <input type="hidden" id="od_phone<?php echo $odId; ?>" value="<?php echo $address_row->od_shipping_phone; ?>" />
                                <input type="hidden" id="od_lat<?php echo $odId; ?>" value="<?php echo $address_row->od_shipping_lat; ?>" />
                                <input type="hidden" id="od_lng<?php echo $odId; ?>" value="<?php echo $address_row->od_shipping_lng; ?>" />
                            </li>
						<?php }
					}
					else{  }
					?>
                    
                </ul>
            </div>
            
            <div id="newaddress">
            	<form action="<?php echo siteUrl; ?>action_model.php" method="post">
                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                    	<td colspan="2"><strong>Enter a new address</strong></td>
                    </tr>
                    <tr>
                      	<td width="41%">First name *</td>
                      	<td width="59%"><input type="text" name="fname" required id="od_first_name" /></td>
                    </tr>
                    <tr>
                      	<td>Last name *</td>
                      	<td><input type="text" name="lname" required id="od_last_name" /></td>
                    </tr>
                    <tr>
                      	<td>Phone *</td>
                      	<td><input type="text" name="phone" required id="od_phone" /></td>
                    </tr>
                    <tr>
                      	<td>Alternate Phone</td>
                      	<td><input type="text" name="aphone" /></td>
                    </tr>
                    <tr>
                      	<td valign="top">Address *</td>
                      	<td valign="top">
                        	<input type="text" name="aaddress1" required id="geocomplete" onblur="return false;" />
                            <div class="map_canvas"></div>
                            <!--<textarea name="address" required style="width:155px;" id="od_address"></textarea>-->
                            <input name="lat" type="hidden" value="" id="od_lat">
                            <input name="lng" type="hidden" value="" id="od_lng">
                        </td>
                    </tr>
                    <tr>
                      	<td>Post Code *</td>
                      	<td><input type="text" name="postal_code" required id="od_postal_code" /></td>
                    </tr>
                    <tr>
                      	<td>Suburb</td>
                      	<td>
                        	<input name="formatted_address" id="fa" type="hidden" value="">
                        	<input type="text" name="route1" required id="od_locality" />
                        </td><!-- sublocality -->
                    </tr>
                    <tr>
                      	<td>City *</td>
                      	<td><input type="text" name="locality" required id="od_city" onchange="getSU(fa.value);" /></td>
                    </tr>
                    <tr style="display:none;">
                      	<td>State/Region *</td>
                      	<td><input type="text" name="administrative_area_level_1" required id="od_state" value="111" /></td>
                    </tr>
                    <tr style="display:none;"><!-- hidden hai ye clint ne hatwaya -->
                      	<td>Address Type</td>
                      	<td>
                        	<input type="radio" name="addType" value="home" style="width:0px;" required checked="checked" />
                        	<label for="radio"></label> Home 
                      		<input type="radio" name="addType" value="office" style="width:0px;" required />
                      		<label for="radio2"></label> Office
                    	</td>
                    </tr>
                    
                    <tr>
                      	<td>
                        	<input type="hidden" name="action" value="shipping_detail" />
                            <input type="hidden" name="isAddressCopied" id="isAddressCopied" value="0" />
                        </td>
                      	<td><input type="submit" value="Save & Continue" class="button_address" /></td>
                    </tr>
                </table>
                </form>
            </div>
            <div class="clr"> </div>
        </div> 
    </div> 
    <div class="col-sm-4">
        <?php include 'include/cartRightBar.php'; ?>
        </div>
    <div class="clr"> </div>
        </div>
        </div>
        
</section>
    

    
    
<?php include("include/new_footer.php"); ?>

<script src="//maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>
<script src="<?php echo siteUrl; ?>js/jquery.geocomplete.js"></script>
<script>
$(function(){
	$("#geocomplete").geocomplete({
    	map: ".map_canvas",
        details: "form",
        types: ["geocode", "establishment"]
    }).bind("geocode:result", function (event, result) {
		//console.log(result);		
		no = result.address_components[0].long_name;
		street = result.address_components[1].short_name;
		document.getElementById('geocomplete').value = no+' '+street;
		
		fa = result.formatted_address;
		faArr = fa.split(',');
		document.getElementById('od_locality').value = faArr[1];
	});

    $("#find").click(function(){
    	$("#geocomplete").trigger("geocode");
   	});
});
function getSU(fa){
	/*if(fa != ''){
		faArr = fa.split(',');
		//alert(faArr[1]);
		document.getElementById('od_locality').value = faArr[1];
	}*/
}
function setAddressNow(id){
	fields = ['od_first_name', 'od_last_name', 'od_locality', 'od_phone', 'od_postal_code', 'geocomplete', 'od_lat', 'od_lng', 'od_city', 'od_state'];
	length = fields.length;
	for (i = 0; i < length; i++){
		data = fields[i];
		document.getElementById(data).value = document.getElementById(data+id).value;
	}
	document.getElementById('isAddressCopied').value = 100;
}
function removePromoCode(){
	if(confirm('Do you want to Remove the Promo Code?')){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200){ alert(xmlhttp.responseText); location.reload(); }
		}
		xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=removePromoCode&dataTempId=tqa7xa153f02d0xq316ld1f20771.cloud.uk", true);
		xmlhttp.send();
	}
}
</script>

<?php include("include/new_bottom.php"); ?>