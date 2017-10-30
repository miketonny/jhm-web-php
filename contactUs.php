<?php include("include/new_top.php"); ?>
<?php include("include/new_header.php"); ?>

<style>
.paymentcard td input[type=text], .paymentcard td input[type=email], .paymentcard td select{ width:100%; margin-bottom: 10px } 
.paymentcard strong {
    width: auto;
    margin-left: 15px;
    display: inline-block;
    text-align: left;
}
</style>
<section class="block-pt5">
    <div class="container">
        <div class="other-page-holder">
         <div class="col-md-6 col-md-push-3">
        	
            	
            <div class="cc-container" >
                <p style="font-size:15px; margin:10px;"> JHM NZ Limited Customer service is ready to assist you. Please choose from the following options for information about your order, product information, store locations, and more. </p>
                <!--<h2 style="margin:10px;">Email Us</h2>-->
                <h2 style="margin-left: 311px;margin-bottom: 15px;">Email Us</h2>
                <div class="cc-contents">
                   
                    <form method="POST" id="form" action="<?php echo siteUrl; ?>action_model.php" >
                        
                        <table border="0" cellspacing="0" cellpadding="3" class="paymentcard" style=" margin: 0; width: 100%;">
                            <tr>
                                <td style=" width:125px" ><strong>First Name</strong></td>
                                <td>
                                	<input type="text" name="fname" id="fname" required placeholder="First Name" />
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Last Name</strong></td>
                                <td>
                                	<input type="text" name="lname" id="lname" required placeholder="Last Name" />
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Email Address</strong></td>
                                <td><input type="email" name="email" id="email" required placeholder="Email Address" /></td>
                            </tr>
                            <tr>
                                <td><strong>Subject (Select One)</strong></td>
                                <td>
                                    <select name="qtype" id="qtype" required>
                                        <option value="">- SELECT SUBJECT -</option>
                                        <option>Order Information</option>
                                        <option>Product Information</option>
                                        <option>Retail Store Information</option>
                                        <option>Website Feedback</option>
                                        <option>Compliment or Complaint</option>
                                        <option>General Feedback or Question</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top"><strong>Comment</strong></td>
                                <td colspan="3">
                                    <textarea id="desc" name="desc" required style="margin-top:0px; width:100%; height:150px;" placeholder="Comment (1000 word maximum)" ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center";>
                                	<input type="button" value="Submit" class="button_address" onclick="submitContact();" />
                                    <input type="hidden" name="action" value="contactUs" />
                                </td>
                            </tr>
                        </table>
                    </form>
                    
                </div>
                 <div class="clr"> </div>
                <p style="font-size:15px; margin:10px;"> All personal information is strictly confidential. </p>
            </div>
        </div>
         <div class="clr"> </div>
        </div>
    </div>
</section>



<?php include("include/new_footer.php"); ?>
<script>
function submitContact(){
	fname = document.getElementById('fname').value;
	lname = document.getElementById('lname').value;
	qtype = document.getElementById('qtype').value;
	email = document.getElementById('email').value;
	desc = document.getElementById('desc').value;
	
	if(fname == '' || lname == '' || qtype == '' || email == '' || desc == ''){
		alert('Oops! Something is missing.');
	}
	else{
		var xmlhttp;
		if(window.XMLHttpRequest){ xmlhttp = new XMLHttpRequest(); }
		else{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
				if(xmlhttp.responseText == 0){
					setMessage('Failed! Invalid Email Address.', 'alert alert-error');
				}
				else{
					setMessage('Thanks for contacting us. We will reply you soon.', 'alert alert-success');
				}
				// setmessage defined in header
			}
		}
		desc = encodeURIComponent(desc);
		xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=contactUs&fname="+fname+"&lname="+lname+"&qtype="+qtype+"&email="+email+"&desc="+desc+"&dataTempId=c3v7fa153302ded0hc314ld1f21679.cloud.uk", true);
		xmlhttp.send();
	}
}
</script>

<?php include("include/new_bottom.php"); ?>