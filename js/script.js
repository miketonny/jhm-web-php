// function for open a accordion
function openAccordion(no){
	//$('.bodyAccordion').css('display', 'none');
	//$('#accordion-'+no).css('display', 'block');
	$('.bodyAccordion').removeClass('addEffect');
	$('#accordion-'+no).addClass('addEffect');
	disableOther(no);
}

// function for disable other, so user unable to access down level accoordion
function disableOther(no){
	var i;
	if(no != 6){
		$('.btnAccordion').removeAttr('disabled');
		i = no;
		while(i <= 6){
			$('#btn-'+i).attr('disabled', 'disabled');
			i++;
		}
	}
}

// disable any accordion
function disableOne(no){
	$('#btn-'+no).attr('disabled', 'disabled');
	$('#accordion-'+no).css('display', 'none');
}

// accordion 1 function ////////////////////////////////////////////
// function for chk checkouit method, guest or register
function chkCheckoutType(){
	var value;
	if(document.getElementById('checkout_guest').checked){
		document.getElementById('billPassRow').style.display = 'none';
		value = document.getElementById('checkout_guest').value;
		openAccordion(2);
		document.getElementById('isLogin').value = value;
	}
	else if(document.getElementById('checkout_register').checked){
		document.getElementById('billPassRow').style.display = 'table-row';
		value = document.getElementById('checkout_register').value;
		openAccordion(2);
		document.getElementById('isLogin').value = value;
	}
	else{ alert('Please select a checkout method !'); }
}
// validate email
function chkValidEmail(email){
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}
// chk login details
/*function chkLoginValues(){
	email = document.getElementById('loginEmail').value;
	if(chkValidEmail(email)){
		passField = document.getElementById('loginPassword');
		if(passField.value != ''){
			document.getElementById('billPassRow').style.display = 'none';
			openAccordion(2);
		}
		else{ alert('Something went wrong with Password !'); }
	}
	else{ alert('Invalid Email Address !'); }
}*/

// IF USER LOGIN, CONTINUE BUTTON FUNCTION
function loginContinue(){ openAccordion(2); }

///// accordion 2 function //////////////////////////////////////////
function chkBillData(checkoutType, url){
	bill_email = document.getElementById('bill_email').value;
	bill_first_name = document.getElementById('bill_first_name').value;
	bill_last_name = document.getElementById('bill_last_name').value;
	bill_phone = document.getElementById('bill_phone').value;
	billAddress = document.getElementById('billAddress').value;
	bill_postal_code = document.getElementById('bill_postal_code').value;
	bill_lat = document.getElementById('bill_lat').value;
	bill_lng = document.getElementById('bill_lng').value;
	bill_locality = document.getElementById('bill_locality').value;
	bill_city = document.getElementById('bill_city').value;
	//bill_lat == '' || bill_lng == ''
	if(bill_email == '' || bill_first_name == '' || bill_last_name == '' || bill_phone == '' || billAddress == '' || bill_postal_code == '' || bill_locality == '' || bill_city == ''){
		alert('Oops, Something is missing in Billing Information !');
	}
	else if(!chkValidEmail(bill_email)){
		alert('Invalid Email Address !');
	}
	else if(checkoutType == 'register' && document.getElementById('bill_password').value == ''){
		alert('Oops, Something is missing in Billing Information !');
	}
	else{
		if(document.getElementById('shipToThis').checked || document.getElementById('sameAsBilling').checked){
			// ship to this
			document.getElementById('ship_first_name').value = bill_first_name;
			document.getElementById('ship_last_name').value = bill_last_name;
			document.getElementById('ship_phone').value = bill_phone;
			document.getElementById('shipAddress').value = billAddress;
			document.getElementById('ship_postal_code').value = bill_postal_code;
			document.getElementById('ship_lat').value = bill_lat;
			document.getElementById('ship_lng').value = bill_lng;
			document.getElementById('ship_locality').value = bill_locality;
			document.getElementById('ship_city').value = bill_city;
			//openAccordion(4);
			// now chk shipping city n get shipping charges
			getShippingCharges(bill_city, bill_postal_code, url);
		}
		else{
			openAccordion(3);
		}
	}
}

function chkShipData(url){
	ship_first_name = document.getElementById('ship_first_name').value;
	ship_last_name = document.getElementById('ship_last_name').value;
	ship_phone = document.getElementById('ship_phone').value;
	shipAddress = document.getElementById('shipAddress').value;
	ship_postal_code = document.getElementById('ship_postal_code').value;
	ship_lat = document.getElementById('ship_lat').value;
	ship_lng = document.getElementById('ship_lng').value;
	ship_locality = document.getElementById('ship_locality').value;
	ship_city = document.getElementById('ship_city').value;
	//ship_lat == '' || ship_lng == ''
	if(ship_first_name == '' || ship_last_name == '' || ship_phone == '' || shipAddress == '' || ship_postal_code == '' || ship_locality == '' || ship_city == ''){
		alert('Oops, Something is missing in Shipping Information !');
	}
	else{
		//openAccordion(4);	// now chk shipping city n get shipping charges
		getShippingCharges(ship_city, ship_postal_code, url);
	}
}

function getShippingCharges(city, postcode, url){
	if(postcode != ''){
		$('#overnightHide').css('display', 'none');
		$.get(url+"ajax.php", {"city" : city, 'postcode' : postcode, "action" : "getShippingCharges"}, function(data, status){
			if(data.valid){
				shipAmount = data.price;
				if(data.sectorCode == 'II'){ $('#overnightHide').css('display', 'block'); }
				
				$('#shipStandard').text(shipAmount);
				$('#shipSaturday').text(parseFloat(shipAmount) + parseFloat(4.00));
				$('#shipRural').text(parseFloat(shipAmount) + parseFloat(4.00));
				$('#shipOverNight').text(parseFloat(shipAmount) + parseFloat(20));
				
				document.getElementById('shippingFee').value = shipAmount;
				openAccordion(4);
				setTotalOperation();
			}
			else{
				alert('Some error occured !');
			}
		}, "json");
	}
	else{
		alert('Please Select an Address with Valid City !');
	}
}

function finalShipping(shipAmountExtra){
	document.getElementById('shippingFeeExtra').value = shipAmountExtra;
	setTotalOperation();
}

function setTotalOperation(){
	$('#applyPointLi').html('');
	shipAmountExtra = document.getElementById('shippingFeeExtra').value;
	shipAmountOriginal = document.getElementById('shippingFee').value;
	shipAmount = parseFloat(shipAmountExtra) + parseFloat(shipAmountOriginal);
	
	totalOrder = document.getElementById('forShipAmount').value;
	pointDeduct = document.getElementById('pointDeduct').value;
	
	total = parseFloat(shipAmount) + parseFloat(totalOrder) - parseFloat(pointDeduct);
	total = Math.round(total * 100) / 100;
	
	fTotal = formatCurrency(total); fshipAmount = formatCurrency(shipAmount); fpointDeduct = formatCurrency(pointDeduct);
	
	$('#grandTotalSpan').text(fTotal);
	$('#shippingLi').html('<h4>Shipping Charge <strong>$ '+fshipAmount+'</strong></h4>');
	if(pointDeduct > 0){ $('#applyPointLi').html('<h4>Point Deduction <strong>- $ '+fpointDeduct+'</strong></h4>'); }
	document.getElementById('cartValue').value = total;
	
	// for inner table preview last
	document.getElementById('shipTabletd').innerHTML = '$ <span>'+fshipAmount+'</span>';
	document.getElementById('tableTotaltd').innerHTML = '$ <span>'+fTotal+'</span>';
	//document.getElementById('pointTd').innerHTML = '- $ <span>'+fpointDeduct+'</span>';
}

function formatCurrency(no){
	if(no % 1 === 0){ a = no+'.00';}
	else{
		noArr = no.toString().split(".");
		no1 = noArr[0];
		no2 = noArr[1].substr(0, 2);
		if(no2.length == 1){ no2 = no2+'0'; }
		a = no1+'.'+no2;
	}
	return a;
}

function sameAsBillingFun(id, checkoutType, url){
	if(document.getElementById(id).checked){ chkBillData(checkoutType, url); }
}

// chk shipping and handling
function chkShipnHand(){
	standard = document.getElementById('standard');
	saturday = document.getElementById('saturday');
	rural = document.getElementById('rural');
	overnight = document.getElementById('overnight');
	
	if(standard.checked == true || saturday.checked == true || rural.checked == true || overnight.checked == true){
		openAccordion(5);
	}else{ alert('Please Choose a Shipping Method !'); }
}

// function for credit point
function processPoint(type){
	if(type == 'applyNow'){
		userPoint = Number(document.getElementById('pointInput').value);
		userTotalPoint = Number(document.getElementById('userTotalPoint').value);
		if((userPoint > 0) && (userPoint <= userTotalPoint)){
			document.getElementById('pointDeduct').value = userPoint; //1$=1p
			setTotalOperation();
			document.getElementById('applybutton').style.display='none';
			document.getElementById('clearbtn').style.display='none';
			
			document.getElementById('applied').style.display='';
			document.getElementById('removebutton').style.display='';
			
		}
		else{
			$('#applyPointLi').html('');
			alert('Error, Invalid Point to Apply!');
			document.getElementById('pointInput').value = '';
			document.getElementById('pointDeduct').value = 0;
			setTotalOperation();
		}
	}
	else if(type == 'removeIt'){
		$('#applyPointLi').html('');
		document.getElementById('pointDeduct').value = 0; //1$=1p
		document.getElementById('pointInput').value = '';
		setTotalOperation();
			document.getElementById('applybutton').style.display='';
			document.getElementById('clearbtn').style.display='';
			
			document.getElementById('applied').style.display='none';
			document.getElementById('removebutton').style.display='none';
	}
}

// payment box toggle, default open one /////////
function togglePaymentBox(id){
	$('#checkOutCreditcard, #checkoutPaypal').css('display', 'none');
	$('#'+id).css('display', 'block');
	//document.getElementById('checkOutCreditcardbtn').className="";
	//document.getElementById('checkoutPaypalbtn').className="";
	document.getElementById(id+'btn').className="activepayment";
}
//togglePaymentBox('checkOutCreditcard');

function chkCardData(){
	nameOnCard = document.getElementById('nameOnCard').value;
	cc_types = document.getElementById('cc-types').value;
	card_number = document.getElementById('card-number').value;
	cc_month = document.getElementById('cc-month').value;
	cc_year = document.getElementById('cc-year').value;
	cc_cvv = document.getElementById('cc-cvv').value;
	
	if(nameOnCard == '' || cc_types == '' || card_number == '' || cc_month == '' || cc_year == '' || cc_cvv == ''){
		alert('Oops, Something is missing in Payment Information !');
	}
	else{
		$('#paymentType').val('credit_card');
		openAccordion(6);
	}
}

// if payment process is paypal
function paypalPaymentCheckout(){
	$('#paymentType').val('paypal');
	openAccordion(6);
}

// accordion 2 and 3 function for set data from select to input
function setAddressDataFromSelect(id, type, siteUrl){
	$.get(siteUrl+"ajax.php", {"id" : id, "type" : type, "action" : "setAddressDataFromSelect"}, function(data, status){
		if(data.valid){
			if(type == 'bill'){
				document.getElementById('bill_first_name').value = data.fname;
				document.getElementById('bill_last_name').value = data.lname;
				document.getElementById('bill_phone').value = data.phone;
				document.getElementById('billAddress').value = data.address;
				document.getElementById('bill_postal_code').value = data.postal_code;
				document.getElementById('bill_lat').value = data.lat;
				document.getElementById('bill_lng').value = data.lng;
				document.getElementById('bill_locality').value = data.locality;
				document.getElementById('bill_city').value = data.city;
			}
			else{
				document.getElementById('ship_first_name').value = data.fname;
				document.getElementById('ship_last_name').value = data.lname;
				document.getElementById('ship_phone').value = data.phone;
				document.getElementById('shipAddress').value = data.address;
				document.getElementById('ship_postal_code').value = data.postal_code;
				document.getElementById('ship_lat').value = data.lat;
				document.getElementById('ship_lng').value = data.lng;
				document.getElementById('ship_locality').value = data.locality;
				document.getElementById('ship_city').value = data.city;
			}
		}
		else{
			alert('Some error occured while copy data !');
		}
	}, "json");
}

// for credit card validations and address fields
$(document).ready(function() {
	// ccard /////////
	$('.cc-container').ccvalidate({ onvalidate: function(isValid) {
		if (!isValid) {
			alert('Incorrect Credit Card format');
			return false;
		}
		else{ chkCardData(5); }
	}});
	$('.cc-ddl-contents').css('display', 'none');
	$('.cc-ddl-header').toggle(function() {
		toggleContents($(this).parent().find('.cc-ddl-contents'));
	}, function() { toggleContents($(this).parent().find('.cc-ddl-contents')); });

	function toggleContents(el) {
		$('.cc-ddl-contents').css('display', 'none');
		if (el.css('display') == 'none') el.fadeIn("slow");
		else el.fadeOut("slow");
	}
	$('.cc-ddl-contents a').click(function() {
		$(this).parent().parent().find('.cc-ddl-o select').attr('selectedIndex', $('.cc-ddl-contents a').index(this));
		$(this).parent().parent().find('.cc-ddl-title').html($(this).html());
		$(this).parent().parent().find('.cc-ddl-contents').fadeOut("slow");
	});
	$(document).click(function() {
		$('.cc-ddl-contents').fadeOut("slow");
	});
	$('#check').click(function() {
		var cnumber = $('#cnumber').val();
		var type = $('#ctype').val();
		alert(isValidCreditCard(cnumber, type) ? 'Valid' : 'Invalid');
	});
	// address /////////
	$("#billAddress").geocomplete({
 		details: "form#chkoutForm tbody#billAddressBlock",
  		detailsAttribute: "data-geo"
	}).bind("geocode:result", function (event, result) {
		no = result.address_components[0].long_name;
		street = result.address_components[1].short_name;
		document.getElementById('billAddress').value = no+' '+street;
		
		fa = result.formatted_address;
		faArr = fa.split(',');
		document.getElementById('bill_locality').value = faArr[1];
	});
	
	$("#shipAddress").geocomplete({
 		details: "form#chkoutForm tbody#shipAddressBlock",
  		detailsAttribute: "data-geo"
	}).bind("geocode:result", function (event, result) {
		no = result.address_components[0].long_name;
		street = result.address_components[1].short_name;
		document.getElementById('shipAddress').value = no+' '+street;
		
		fa = result.formatted_address;
		faArr = fa.split(',');
		document.getElementById('ship_locality').value = faArr[1];
	});

	$("#find").click(function(){
  		$("#geocomplete").trigger("geocode");
	});
});

function doProcess(data1, data2, data3){
	if(chkValidEmail(data1) && data1 != '' && data2 != ''){
		data1 = str_rot13(data1);
		data2 = md5(data2);
		$.get(data3+"ajax.php", {'dataTempId' : 'doProcess.c3vafa154i52de90gffch14lkf217900.cloud.uk', "data1" : data1, "data2" : data2, "action" : "doProcess", }, function(data, status){
			if(data.process){
				document.getElementById('checkOutMethodBox').innerHTML = data.msg;
				document.getElementById('billPassRow').style.display = 'none';
				document.getElementById('bill_email').value = data.email;
				
				document.getElementById('userTotalPoint').value = data.point;
				$('#creditData').css('display', 'block');
				$('#creditPara').html('You have total <strong>'+data.point+'</strong> Points.');
				
				$('#addressBookBill').html(data.optionBill);
				$('#addressBookBill').trigger("update");
				$('#addressBookShip').html(data.optionShip);
				$('#addressBookShip').trigger("update");
			}
			else{
				alert(data.msg);
				document.getElementById('billPassRow').style.display = 'table-row';
			}
		}, "json");
	}
	else{ alert('Invalid Email Address or Password !'); }
}

// library ///////////////////////////////////////////////////////////////////////////
function str_rot13(str) {
	return (str + '').replace(/[a-z]/gi, function(s){
		return String.fromCharCode(s.charCodeAt(0) + (s.toLowerCase() < 'n' ? 13 : -13));
	});
}

function utf8_encode(argString) {
  if (argString === null || typeof argString === 'undefined') {
    return '';
  }

  var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
  var utftext = '',
    start, end, stringl = 0;

  start = end = 0;
  stringl = string.length;
  for (var n = 0; n < stringl; n++) {
    var c1 = string.charCodeAt(n);
    var enc = null;

    if (c1 < 128) {
      end++;
    } else if (c1 > 127 && c1 < 2048) {
      enc = String.fromCharCode(
        (c1 >> 6) | 192, (c1 & 63) | 128
      );
    } else if ((c1 & 0xF800) != 0xD800) {
      enc = String.fromCharCode(
        (c1 >> 12) | 224, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128
      );
    } else { // surrogate pairs
      if ((c1 & 0xFC00) != 0xD800) {
        throw new RangeError('Unmatched trail surrogate at ' + n);
      }
      var c2 = string.charCodeAt(++n);
      if ((c2 & 0xFC00) != 0xDC00) {
        throw new RangeError('Unmatched lead surrogate at ' + (n - 1));
      }
      c1 = ((c1 & 0x3FF) << 10) + (c2 & 0x3FF) + 0x10000;
      enc = String.fromCharCode(
        (c1 >> 18) | 240, ((c1 >> 12) & 63) | 128, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128
      );
    }
    if (enc !== null) {
      if (end > start) {
        utftext += string.slice(start, end);
      }
      utftext += enc;
      start = end = n + 1;
    }
  }

  if (end > start) {
    utftext += string.slice(start, stringl);
  }

  return utftext;
}

function md5(str) {
  var xl;
  var rotateLeft = function(lValue, iShiftBits) {
    return (lValue << iShiftBits) | (lValue >>> (32 - iShiftBits));
  };

  var addUnsigned = function(lX, lY) {
    var lX4, lY4, lX8, lY8, lResult;
    lX8 = (lX & 0x80000000);
    lY8 = (lY & 0x80000000);
    lX4 = (lX & 0x40000000);
    lY4 = (lY & 0x40000000);
    lResult = (lX & 0x3FFFFFFF) + (lY & 0x3FFFFFFF);
    if (lX4 & lY4) {
      return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
    }
    if (lX4 | lY4) {
      if (lResult & 0x40000000) {
        return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
      } else {
        return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
      }
    } else {
      return (lResult ^ lX8 ^ lY8);
    }
  };

  var _F = function(x, y, z) {
    return (x & y) | ((~x) & z);
  };
  var _G = function(x, y, z) {
    return (x & z) | (y & (~z));
  };
  var _H = function(x, y, z) {
    return (x ^ y ^ z);
  };
  var _I = function(x, y, z) {
    return (y ^ (x | (~z)));
  };

  var _FF = function(a, b, c, d, x, s, ac) {
    a = addUnsigned(a, addUnsigned(addUnsigned(_F(b, c, d), x), ac));
    return addUnsigned(rotateLeft(a, s), b);
  };

  var _GG = function(a, b, c, d, x, s, ac) {
    a = addUnsigned(a, addUnsigned(addUnsigned(_G(b, c, d), x), ac));
    return addUnsigned(rotateLeft(a, s), b);
  };

  var _HH = function(a, b, c, d, x, s, ac) {
    a = addUnsigned(a, addUnsigned(addUnsigned(_H(b, c, d), x), ac));
    return addUnsigned(rotateLeft(a, s), b);
  };

  var _II = function(a, b, c, d, x, s, ac) {
    a = addUnsigned(a, addUnsigned(addUnsigned(_I(b, c, d), x), ac));
    return addUnsigned(rotateLeft(a, s), b);
  };

  var convertToWordArray = function(str) {
    var lWordCount;
    var lMessageLength = str.length;
    var lNumberOfWords_temp1 = lMessageLength + 8;
    var lNumberOfWords_temp2 = (lNumberOfWords_temp1 - (lNumberOfWords_temp1 % 64)) / 64;
    var lNumberOfWords = (lNumberOfWords_temp2 + 1) * 16;
    var lWordArray = new Array(lNumberOfWords - 1);
    var lBytePosition = 0;
    var lByteCount = 0;
    while (lByteCount < lMessageLength) {
      lWordCount = (lByteCount - (lByteCount % 4)) / 4;
      lBytePosition = (lByteCount % 4) * 8;
      lWordArray[lWordCount] = (lWordArray[lWordCount] | (str.charCodeAt(lByteCount) << lBytePosition));
      lByteCount++;
    }
    lWordCount = (lByteCount - (lByteCount % 4)) / 4;
    lBytePosition = (lByteCount % 4) * 8;
    lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80 << lBytePosition);
    lWordArray[lNumberOfWords - 2] = lMessageLength << 3;
    lWordArray[lNumberOfWords - 1] = lMessageLength >>> 29;
    return lWordArray;
  };

  var wordToHex = function(lValue) {
    var wordToHexValue = '',
      wordToHexValue_temp = '',
      lByte, lCount;
    for (lCount = 0; lCount <= 3; lCount++) {
      lByte = (lValue >>> (lCount * 8)) & 255;
      wordToHexValue_temp = '0' + lByte.toString(16);
      wordToHexValue = wordToHexValue + wordToHexValue_temp.substr(wordToHexValue_temp.length - 2, 2);
    }
    return wordToHexValue;
  };

  var x = [],
    k, AA, BB, CC, DD, a, b, c, d, S11 = 7,
    S12 = 12,
    S13 = 17,
    S14 = 22,
    S21 = 5,
    S22 = 9,
    S23 = 14,
    S24 = 20,
    S31 = 4,
    S32 = 11,
    S33 = 16,
    S34 = 23,
    S41 = 6,
    S42 = 10,
    S43 = 15,
    S44 = 21;

  str = this.utf8_encode(str);
  x = convertToWordArray(str);
  a = 0x67452301;
  b = 0xEFCDAB89;
  c = 0x98BADCFE;
  d = 0x10325476;

  xl = x.length;
  for (k = 0; k < xl; k += 16) {
    AA = a;
    BB = b;
    CC = c;
    DD = d;
    a = _FF(a, b, c, d, x[k + 0], S11, 0xD76AA478);
    d = _FF(d, a, b, c, x[k + 1], S12, 0xE8C7B756);
    c = _FF(c, d, a, b, x[k + 2], S13, 0x242070DB);
    b = _FF(b, c, d, a, x[k + 3], S14, 0xC1BDCEEE);
    a = _FF(a, b, c, d, x[k + 4], S11, 0xF57C0FAF);
    d = _FF(d, a, b, c, x[k + 5], S12, 0x4787C62A);
    c = _FF(c, d, a, b, x[k + 6], S13, 0xA8304613);
    b = _FF(b, c, d, a, x[k + 7], S14, 0xFD469501);
    a = _FF(a, b, c, d, x[k + 8], S11, 0x698098D8);
    d = _FF(d, a, b, c, x[k + 9], S12, 0x8B44F7AF);
    c = _FF(c, d, a, b, x[k + 10], S13, 0xFFFF5BB1);
    b = _FF(b, c, d, a, x[k + 11], S14, 0x895CD7BE);
    a = _FF(a, b, c, d, x[k + 12], S11, 0x6B901122);
    d = _FF(d, a, b, c, x[k + 13], S12, 0xFD987193);
    c = _FF(c, d, a, b, x[k + 14], S13, 0xA679438E);
    b = _FF(b, c, d, a, x[k + 15], S14, 0x49B40821);
    a = _GG(a, b, c, d, x[k + 1], S21, 0xF61E2562);
    d = _GG(d, a, b, c, x[k + 6], S22, 0xC040B340);
    c = _GG(c, d, a, b, x[k + 11], S23, 0x265E5A51);
    b = _GG(b, c, d, a, x[k + 0], S24, 0xE9B6C7AA);
    a = _GG(a, b, c, d, x[k + 5], S21, 0xD62F105D);
    d = _GG(d, a, b, c, x[k + 10], S22, 0x2441453);
    c = _GG(c, d, a, b, x[k + 15], S23, 0xD8A1E681);
    b = _GG(b, c, d, a, x[k + 4], S24, 0xE7D3FBC8);
    a = _GG(a, b, c, d, x[k + 9], S21, 0x21E1CDE6);
    d = _GG(d, a, b, c, x[k + 14], S22, 0xC33707D6);
    c = _GG(c, d, a, b, x[k + 3], S23, 0xF4D50D87);
    b = _GG(b, c, d, a, x[k + 8], S24, 0x455A14ED);
    a = _GG(a, b, c, d, x[k + 13], S21, 0xA9E3E905);
    d = _GG(d, a, b, c, x[k + 2], S22, 0xFCEFA3F8);
    c = _GG(c, d, a, b, x[k + 7], S23, 0x676F02D9);
    b = _GG(b, c, d, a, x[k + 12], S24, 0x8D2A4C8A);
    a = _HH(a, b, c, d, x[k + 5], S31, 0xFFFA3942);
    d = _HH(d, a, b, c, x[k + 8], S32, 0x8771F681);
    c = _HH(c, d, a, b, x[k + 11], S33, 0x6D9D6122);
    b = _HH(b, c, d, a, x[k + 14], S34, 0xFDE5380C);
    a = _HH(a, b, c, d, x[k + 1], S31, 0xA4BEEA44);
    d = _HH(d, a, b, c, x[k + 4], S32, 0x4BDECFA9);
    c = _HH(c, d, a, b, x[k + 7], S33, 0xF6BB4B60);
    b = _HH(b, c, d, a, x[k + 10], S34, 0xBEBFBC70);
    a = _HH(a, b, c, d, x[k + 13], S31, 0x289B7EC6);
    d = _HH(d, a, b, c, x[k + 0], S32, 0xEAA127FA);
    c = _HH(c, d, a, b, x[k + 3], S33, 0xD4EF3085);
    b = _HH(b, c, d, a, x[k + 6], S34, 0x4881D05);
    a = _HH(a, b, c, d, x[k + 9], S31, 0xD9D4D039);
    d = _HH(d, a, b, c, x[k + 12], S32, 0xE6DB99E5);
    c = _HH(c, d, a, b, x[k + 15], S33, 0x1FA27CF8);
    b = _HH(b, c, d, a, x[k + 2], S34, 0xC4AC5665);
    a = _II(a, b, c, d, x[k + 0], S41, 0xF4292244);
    d = _II(d, a, b, c, x[k + 7], S42, 0x432AFF97);
    c = _II(c, d, a, b, x[k + 14], S43, 0xAB9423A7);
    b = _II(b, c, d, a, x[k + 5], S44, 0xFC93A039);
    a = _II(a, b, c, d, x[k + 12], S41, 0x655B59C3);
    d = _II(d, a, b, c, x[k + 3], S42, 0x8F0CCC92);
    c = _II(c, d, a, b, x[k + 10], S43, 0xFFEFF47D);
    b = _II(b, c, d, a, x[k + 1], S44, 0x85845DD1);
    a = _II(a, b, c, d, x[k + 8], S41, 0x6FA87E4F);
    d = _II(d, a, b, c, x[k + 15], S42, 0xFE2CE6E0);
    c = _II(c, d, a, b, x[k + 6], S43, 0xA3014314);
    b = _II(b, c, d, a, x[k + 13], S44, 0x4E0811A1);
    a = _II(a, b, c, d, x[k + 4], S41, 0xF7537E82);
    d = _II(d, a, b, c, x[k + 11], S42, 0xBD3AF235);
    c = _II(c, d, a, b, x[k + 2], S43, 0x2AD7D2BB);
    b = _II(b, c, d, a, x[k + 9], S44, 0xEB86D391);
    a = addUnsigned(a, AA);
    b = addUnsigned(b, BB);
    c = addUnsigned(c, CC);
    d = addUnsigned(d, DD);
  }

  var temp = wordToHex(a) + wordToHex(b) + wordToHex(c) + wordToHex(d);

  return temp.toLowerCase();
}