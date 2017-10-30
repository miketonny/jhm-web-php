<link rel="stylesheet" href="slideshow/themes/default/default.css" type="text/css" media="screen" />
<link rel="stylesheet" href="slideshow/themes/light/light.css" type="text/css" media="screen" />
<link rel="stylesheet" href="slideshow/themes/dark/dark.css" type="text/css" media="screen" />
<link rel="stylesheet" href="slideshow/themes/bar/bar.css" type="text/css" media="screen" />
<link rel="stylesheet" href="slideshow/nivo-slider.css" type="text/css" media="screen" />

<div class="slider-wrapper theme-default">
            <div id="slider2" class="nivoSlider">
            	<?php $cdate = date('Y-m-d H:i:s');
				$slideRs = exec_query("SELECT tpm.banner, tp.slug FROM tbl_promotion_master tpm LEFT JOIN tbl_promotion tp ON tp.promo_id = tpm.promo_id WHERE (DATE_FORMAT(tp.start_date, '%Y-%m-%d %H:%i:%s') <= '$cdate' AND DATE_FORMAT(tp.end_date, '%Y-%m-%d %H:%i:%s') >= '$cdate') AND tpm.is_activate = 1", $con);
				while($slideRow = mysql_fetch_object($slideRs)){ ?>
                	<a href="<?php echo siteUrl; ?>promotionproduct/<?php echo $slideRow->slug; ?>/">
                    	<img data-thumb="site_image/promotion/<?php echo $slideRow->banner; ?>" src="site_image/promotion/<?php echo $slideRow->banner; ?>"/>
                    </a>
				<?php } ?>
            </div>
        </div>
    <script type="text/javascript" src="slideshow/jquery.nivo.slider.js"></script>
    <script type="text/javascript">
    $(window).load(function() {
        $('#slider2').nivoSlider({
			effect: 'sliceDownLeft',        // Specify sets like: 'fold,fade,sliceDown'
			slices: 15,                     // For slice animations
			boxCols: 8,                     // For box animations
			boxRows: 4,                     // For box animations
			animSpeed: 500,                 // Slide transition speed
			pauseTime: 3000,                // How long each slide will show
			startSlide: 0,                  // Set starting Slide (0 index)
			directionNav: true,             // Next & Prev navigation
			controlNav: true,               // 1,2,3... navigation
			controlNavThumbs: false,        // Use thumbnails for Control Nav
			pauseOnHover: true,             // Stop animation while hovering
			manualAdvance: false,           	// Force manual transitions
			prevText: 'Prev',               // Prev directionNav text
			nextText: 'Next',               // Next directionNav text
			randomStart: false,             // Start on a random slide
			beforeChange: function(){},     // Triggers before a slide transition
			afterChange: function(){},      // Triggers after a slide transition
			slideshowEnd: function(){},     // Triggers after all slides have been shown
			lastSlide: function(){},        // Triggers when last slide is shown
			afterLoad: function(){}         // Triggers when slider has loaded
		});
	});
    </script>