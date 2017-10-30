function getXmlHttpRequestObject() {
	if (window.XMLHttpRequest) {
		return new XMLHttpRequest();
	} else if(window.ActiveXObject) {
		return new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		alert("Your Browser Sucks!");
	}
}

//Our XmlHttpRequest object to get the auto suggest
var searchReq = getXmlHttpRequestObject();

//Called from keyup on the search textbox.
//Starts the AJAX request.
function searchSuggestProduct(url) {
	if(document.getElementById('headerSearch').value != ''){
		if (searchReq.readyState == 4 || searchReq.readyState == 0) {
			var str = escape(document.getElementById('headerSearch').value);
			searchReq.open("GET", url+'ajax.php?action=searchSuggestProduct&data1='+str, true);
			searchReq.onreadystatechange = handleSearchSuggest;
			searchReq.send(null);
		}
	}
	else{
		document.getElementById('layer1').style.visibility = "hidden";
	}
}

//Called when the AJAX response is returned.
function handleSearchSuggest() { 
	if (searchReq.readyState == 4) {
	    var ss = document.getElementById('layer1');
		var str1 = document.getElementById('headerSearch');
		var curLeft=0;
		if (str1.offsetParent){
		    while (str1.offsetParent){
			curLeft += str1.offsetLeft;
			str1 = str1.offsetParent;
		    }
		}
		var str2 = document.getElementById('headerSearch');
		var curTop=20;
		if (str2.offsetParent){
		    while (str2.offsetParent){
			curTop += str2.offsetTop;
			str2 = str2.offsetParent;
		    }
		}
		var str = searchReq.responseText.split("\n");
		if(str.length == 1)	
		    document.getElementById('layer1').style.visibility = "hidden";
		else
		    ss.setAttribute('style','position:absolute; top:'+curTop+'; left:'+curLeft+'; ');
			ss.innerHTML = '<div id="results">';
		for(i = 0; i < str.length - 1; i++) {
			//Build our element string.  This is cleaner using the DOM, but //IE doesn't support dynamically added attributes. 
			valu= str[i].split("|");
			valu0 = "'"+valu[0]+"'";
			valu2 = "'"+valu[2]+"'";
			if(valu2 == 'bb'){ class1 = 'selected'; }else{ class1 = 'noclass'; }
			var suggest = '<li onmouseover="javascript:suggestOver(this);" ';
			suggest += 'onmouseout="javascript:suggestOut(this);" ';
			suggest += 'onclick="javascript:setSearch('+valu0+', '+valu[1]+', '+valu2+');" ';
			suggest += 'class="small '+class1+'">' +valu[0]+ '</li>';
			ss.innerHTML += suggest;
		}
			ss.innerHTML += '</div>';
	}
}

//Mouse over function
function suggestOver(div_value) {
	//div_value.className = 'suggest_link_over';
}
//Mouse out function
function suggestOut(div_value) {
	//div_value.className = 'suggest_link';
}
//Click function
function setSearch(name, id, type){
	name = name.replace("</a>", '');
	name = name.replace("'", ' ');
	dataToSetArr = name.split("in <b>");
	if(type == 'mainCat' || type == 'subCat' || type == 'subsubCat'){
		var dataToSet = dataToSetArr[1].replace('</b>', '');
		type = 'cat';
	}
	else if(type == 'brand'){
		var dataToSet = dataToSetArr[0];
	}
	
	document.getElementById('headerSearch').value = dataToSet;
	document.getElementById('headerSearchId').value = id;
	document.getElementById('headerSearchType').value = type;
	
	if(type == 'cat' || type == 'brand'){
		document.getElementById('headerSearchForm').submit();
	}
	document.getElementById('layer1').innerHTML = '';
	document.getElementById('layer1').style.visibility = "hidden";
}