
var ww = document.body.clientWidth;

$(document).ready(function() {	
		if (ww < 768) {
			$(".mainnav li ul li ul").remove();
		}
		
	$(".mainnav li a").each(function() {
		if ($(this).next().length > 0) {
			$(this).addClass("parent");
		};
	})

	
	$(".toggleMenu").click(function(e) {
		e.preventDefault();
		$(this).toggleClass("active");
		$(".mainnav").toggle();
	});
	adjustMenu();
})

$(window).bind('resize orientationchange', function() {
	ww = document.body.clientWidth;
	adjustMenu();
});

var adjustMenu = function() {
	if (ww < 768) {
		//$(".toggleMenu").css("display", "inline-block");
		$(".toggleMenu").css("display", "none");
		/*if (!$(".toggleMenu").hasClass("active")) {
			$(".mainnav").hide();
		} else {
			$(".mainnav").show();
		}*/
		$(".mainnav").show();
		
		$(".mainnav li").unbind('mouseenter mouseleave');
		$(".mainnav li a.parent").unbind('click').bind('click', function(e) {
			// must be attached to anchor element to prevent bubbling
				e.preventDefault();
			$(".mainnav li a.parent span.mens").bind('click', function() {
				window.location='mens/';
			});
			$(".mainnav li a.parent span.womens").bind('click', function() {
				window.location='womens/';
			});
			$(this).parent("li").toggleClass("hover");
		});
	} 
	else if (ww >= 768) {
		$(".toggleMenu").css("display", "none");
		$(".mainnav").show();
		$(".mainnav li").removeClass("hover");
		$(".mainnav li a").unbind('click');
		$(".mainnav li").unbind('mouseenter mouseleave').bind('mouseenter mouseleave', function() {
		 	// must be attached to li so that mouseleave is not triggered when hover over submenu
		 	$(this).toggleClass('hover');
		});
	}
}

