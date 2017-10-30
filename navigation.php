<link rel="stylesheet" type="text/css" href="<?php echo siteUrl; ?>css/navstyle.css">
<a class="toggleMenu" href="#">≡</a>


<script type="text/javascript" src="<?php echo siteUrl; ?>js/navscript.js"></script>

<div id="alertMessageContainer" style="background:#fff; position:relative;"><?php /*echo $sitemessage */?></div>

<script>
function setMessage(text, classs){
	$('#alertMessageContainer').html('<div id="alert_message_div" class="'+classs+'"><span>&#9658;</span> <span class="alert-message">'+text+'</span></div>');
}
function activelogin(type){
	document.getElementById('loginWrapper').className='activelogin';
	activebox(type);
	document.getElementById('blackoverlay').style.display="block";
	document.getElementById('blackoverlay').className='loginWrapper';
}

function activebox(type){
	document.getElementById('loginWrapper').className='activelogin '+type;
}

function closebox(name){
	//document.getElementById('loginWrapper').className='';
	document.getElementById('blackoverlay').style.display='';
	document.getElementById('blackoverlay').className='';

	document.getElementById('askQuestionWrapper').className='';
	document.getElementById('forgetPasswordWrapper').className='';
	//document.getElementById('cartDialogWrapper').className='';
	<?php /* deatilo page popup */
if (isset($_GET['alias'])) {?>
	document.getElementById('chooseSignUp').className='';
	document.getElementById('rateProductWrapper').className='';
	<?php }?>
}
function openUserMenu(id){
	display = document.getElementById(id).style.display;
	if(display == 'block'){ document.getElementById(id).style.display = 'none'; }
	else{ document.getElementById(id).style.display = 'block'; }
}

function signUpChooseDialog(id, classs){
	document.getElementById(id).className=classs;
	document.getElementById('blackoverlay').style.display="block";
	document.getElementById('blackoverlay').className=id;
}
</script>

<?php /* cart product count */
$cartNo = 0;
if (isset($_SESSION['user'])) {$userID = $_SESSION['user'];} elseif (isset($_SESSION['tempUser'])) {$userID = $_SESSION['tempUser'];}
//echo "SELECT COUNT(cart_id) AS cartNo FROM tbl_cart WHERE user_id = '".$userID."'";
if (isset($userID) && $userID != '') {
	$cartCount = mysqli_fetch_object(exec_query("SELECT COUNT(cart_id) AS cartNo FROM tbl_cart WHERE user_id = '" . $userID . "'", $con));
	if (isset($cartCount->cartNo) && $cartCount->cartNo != '') {
		$cartNo = $cartCount->cartNo;
	}
}

?>
<div id="blackoverlay" onClick="closebox(this.className)"></div>
<!-- script for header search -->

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<script>
<?php /*source: '<?php echo siteUrl; ?>ajaxSearch.php',*/?>
$(function() {
	$( "#headerSearch" ).autocomplete({
		minLength: 1,
		source: 'https://<?php echo $_SERVER['SERVER_NAME']; ?>/ajaxSearch.php',
		//source: 'http://windows/jhmShop/ajaxSearch.php',
		focus: function( event, ui ) {
			name = ui.item.name;
			name = name.replace("<b>", '');
			name = name.replace("</b>", '');
			$( "#headerSearch" ).val( name );
			return false;
		},

		select: function( event, ui ) {
			type = ui.item.type;

			name = ui.item.name;
			name = name.replace("<b>", '');
			name = name.replace("</b>", '');

			$( "#headerSearch" ).val( name );
			$( "#headerSearchId" ).val( ui.item.id );
			$( "#headerSearchType" ).val( type );

			if(type == 'product'){
				window.location = ui.item.url;
			}
			else if(type == 'mainCat' || type == 'subCat' || type == 'subsubCat'){
				document.getElementById('headerSearchForm').submit();
			}
			else if(type == 'brand'){
				$( "#headerSearchData" ).val( ui.item.url );
				document.getElementById('headerSearchForm').submit();
			}
			return false;
		}

}).autocomplete( "instance" )._renderItem = function( ul, item ) {
		document.getElementById('headerSearch').className='ui-autocomplete-input';
		if(item.name != 'No'){
			return $( "<li>" ).append( "<a>" + item.name + "</a>" ).appendTo( ul );
		}
		else{
			document.getElementById('headerSearch').className='ui-autocomplete-input';
		}
	};
});
</script>
<!-- script for header search -->

<div id="header">
	<div id="nav" class="mobilenav">
		<ul class="mainnav">
	    	<li> <a href="<?php echo siteUrl; ?>"> Home </a> </li>

	        <?php $feaTagRs = exec_query("SELECT title FROM tbl_tag WHERE is_menu = 1", $con);
while ($feaTagRow = mysqli_fetch_object($feaTagRs)) {?>
	        	<li> <a href="<?php echo siteUrl . 'online-sale/' . str_replace(' ', '-', $feaTagRow->title); ?>/"> <?php echo $feaTagRow->title; ?> </a> </li>
			<?php }?>

	        <li> <a href="<?php echo siteUrl; ?>deals/"> Deals </a> </li>
	     <div class="clr"> </div>
	    </ul>
	</div>
    <div id="logo">
    	<a href="<?php echo siteUrl; ?>">
        	<img src="<?php echo siteUrl; ?>images/Logo_03.png" />
        </a>
    </div>

    <div id="search">
    	<form action="<?php echo siteUrl; ?>product-search/" method="get" id="headerSearchForm" >

            <input type="text" placeholder="Search..." required name="searchText" id="headerSearch"
            value="<?php echo (isset($_REQUEST['searchText']) && $_REQUEST['searchText'] != '') ? $_REQUEST['searchText'] : ''; ?>" />

            <input type="submit" value="" class="button" name="searchButton" />
            <input type="hidden" name="searchTextId" id="headerSearchId" value="" />
            <input type="hidden" name="searchTextType" id="headerSearchType" value="" />
            <!-- new field added for brand ki category, ryt nw only used in brand type search -->
            <input type="hidden" name="searchTextData" id="headerSearchData" value="" />
        </form>
        <ul><!-- most search tags here -->
        <?php
$qsh = "SELECT * FROM tbl_search_admin WHERE is_featured = 1 ORDER BY order_no LIMIT 0, 7";
$keyRs = exec_query($qsh, $con);
while ($keyRow = mysqli_fetch_object($keyRs)) {?>
        		<li> <a href="<?php echo siteUrl; ?>online-product-search/<?php echo $keyRow->keyword; ?>/"> <?php echo $keyRow->keyword; ?> </a> </li>
        <?php }?>
        </ul>
    </div>
    <div id="nav">
		<ul class="mainnav">
	    	<li> <a href="<?php echo siteUrl; ?>"> Home </a> </li>

	        <?php $feaTagRs = exec_query("SELECT title FROM tbl_tag WHERE is_menu = 1", $con);
while ($feaTagRow = mysqli_fetch_object($feaTagRs)) {?>
	        	<li> <a href="<?php echo siteUrl . 'online-sale/' . str_replace(' ', '-', $feaTagRow->title); ?>/"> <?php echo $feaTagRow->title; ?> </a> </li>
			<?php }?>

	        <li> <a href="<?php echo siteUrl; ?>deals/"> Deals </a> </li>
	     <div class="clr"> </div>
	    </ul>
	</div>
    <table id="useritems"><tr><td><ul>
    	<?php if (isset($_SESSION['user']) && isset($_SESSION['user_email'])) {?>
        	<?php include 'include/userHeaderMenu.php';?>
		<?php } else {?>
        <li class="links"><a href="<?php echo siteUrl; ?>userlogin/"><img src="<?php echo siteUrl; ?>images/login-icon2.png" /></a></li><!-- href="javascript:void(0)" onclick="activelogin('loginscreen')" -->
        <?php }?>
    	<li class="carticon"><a href="<?php echo siteUrl; ?>cart/">
    		<table><tr>
    			<td><img src="<?php echo siteUrl; ?>images/shopping-purse.png" /></td>
    			<td><p>MY CART (<?php echo (isset($cartNo)) ? $cartNo : ''; ?>)</p></td>
    		</tr></table>
       	</a>
        <div id="cartbox">
		   	<div id="shopingbag" style="border:none;">
           		<input type="hidden" id="cartCounter" value="<?php echo $cartNo; ?>" />
           	</div>
           	<a href="<?php echo siteUrl; ?>cart/" class="visitCart">Checkout Now</a>
        </div>
        </li>
    </ul></td></tr></table>
</div>

<?php
$sitemessage = @getMessage();
?>

<div id="quickkartclose" onclick="closequickcart()"></div>
<script>
$("#headerSearchForm").keyup(function(e){
	// only small abcd /////////////////////////// only capital ABCD /////////////////////// only 1234
	if((e.keyCode>=97 && e.keyCode<=122) || (e.keyCode>=65 && e.keyCode<=90) || (e.keyCode>=48 && e.keyCode<=57) || e.keyCode==32 || e.keyCode==8){
		if($('#headerSearch').val().length >= 1){
			document.getElementById('headerSearch').className='ui-autocomplete-input loading';
		}
	}
});

$(window).scroll( function() {
	var scrolled_val = $(document).scrollTop().valueOf();
	if(scrolled_val > 120){
		document.getElementById('scrollHeader').className = 'activated';
	}else{
		document.getElementById('scrollHeader').className = '';
	}
});

function closequickcart(){
	document.getElementById('cartbox').style.display = '';
	document.getElementById('quickkartclose').style.display = '';
}
// cart operation
function getCartDataInDialog(opendialog){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function(){
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
			document.getElementById('shopingbag').innerHTML = xmlhttp.responseText;
			if(opendialog != 0){
				document.getElementById('overlay4Cart').style.display = 'none';
				document.getElementById('cartbox').style.display = 'block';
				document.getElementById('quickkartclose').style.display = 'block';
				$('.scrollToTop').click();
				$('.cartno').text($('#cartCounter').val());
			}
			//signUpChooseDialog('cartDialogWrapper', 'top40');
		}
	}
	xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=getCartDataInDialog&dataTempId=sz7xa1g262d0xq316ld3fhu1.cloud.uk", true);
	xmlhttp.send();
}

getCartDataInDialog(0);
</script>

<div id="clr">	</div>