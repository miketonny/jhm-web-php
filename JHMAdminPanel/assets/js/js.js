function getSlug(id){
	/*brand catt prod*/
	var string = document.getElementById(id).value;
	no = Math.floor(Math.random() * (9 - 0 + 1)) + 0;
	string = string.replace(/[^A-Z0-9]+/ig, "_");
	document.getElementById(id+'Slug').value = string;
}
function getSelOpt(id){ var opts; sel = document.getElementById(id);
    // loop through options in select list
    for (var i = 0; i < sel.options.length; i++) { var opt1;
		if(sel.options[i].selected == true && sel.options[i].value != '' && sel.options[i].value != 0 && sel.options[i].value != null){
			opt1 = sel.options[i].value;
			if(opts == ''){ opts = opt1 }else{ opts = opts+','+opt1 }
      	}
  	}
	if(opts != null && opts.indexOf("undefined,") != -1){ opts = opts.replace("undefined,",""); }
	return opts;
}