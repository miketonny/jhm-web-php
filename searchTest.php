<?php session_start();
include("include/config.php");
include("include/functions.php");
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>jQuery UI Autocomplete - Custom data and display</title>

<script src="//code.jquery.com/jquery-1.10.2.js"></script>




<link rel="stylesheet" href="<?php echo siteUrl; ?>jquery/jRating.jquery.css" type="text/css" />
<script type="text/javascript" src="<?php echo siteUrl; ?>jquery/jRating.jquery.js" ></script>



<div class="exemple">
	<div class="exemple6" data-average="10" data-id="6"></div>
</div>
<div class="notice">
<script type="text/javascript">
  $(document).ready(function(){
    $(".exemple6").jRating({
	  length:10,
	  decimalLength:1,
	  showRateInfo:false
	});
  });
</script>



<style>
#project-label {
display: block;
margin-bottom: 1em;
}
#project-icon {
float: left;
height: 32px;
width: 32px;
}
#project-description {
margin: 0;
padding: 0;
}
</style>
<script>
$(function() {
	$( "#headerSearch" ).autocomplete({
		minLength: 0,
		source: 'ajaxSearch.php',
		focus: function( event, ui ) {
		$( "#headerSearch" ).val( ui.item.name );
		return false;
	},

	select: function( event, ui ) {
		$( "#headerSearch" ).val( ui.item.name );
		$( "#headerSearchId" ).val( ui.item.id );
		$( "#headerSearchType" ).html( ui.item.type );
		window.location = 'http://google.com';
		return false;
	}
	
}).autocomplete( "instance" )._renderItem = function( ul, item ) {
		return $( "<li>" )
		.append( "<a>" + item.name + "<br>" + item.type + "</a>" )
		.appendTo( ul );
	};
});
</script>
</head>
<body>


<input id="headerSearch" style="width:300px;" />

<input type="hidden" name="searchTextId" id="headerSearchId" />
<input type="hidden" name="searchTextType" id="headerSearchType" />
