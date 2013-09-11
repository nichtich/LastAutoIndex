<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
	<title>LAI | <?php echo PATH_URI; ?></title>
	
	<?php _include(__DIR__.'/css_config.php'); ?>
	
	<link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/foundation.min.css">
	<link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/webicons.css">
	<link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/koottam.css">
	<link rel="stylesheet" href="<?php echo PATH_THEME; ?>/markdown.css.php">
	<link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/custom.css.php">
	<!-- <link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/color/blue.css"> -->
	<!-- <link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/color/green.css"> -->
	<!-- <link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/color/orange.css"> -->
	<!-- <link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/color/pink.css"> -->
	<!-- <link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/color/purple.css"> -->
	<!-- <link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/color/red.css"> -->
	<!-- <link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/color/yellow.css"> -->
	<!-- <link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/color/blue.css"> -->
	<!-- <link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/color/custom.css.php"> -->
	
	<link href="<?php echo PATH_THEME; ?>/js/vendor/google-code-prettify/prettify.css" type="text/css" rel="stylesheet" />
	
	<script src="<?php echo PATH_THEME; ?>/js/vendor/head.load.min.js"></script>
	
	<script type="text/javascript" >
		// lets load js files in parallel
		head.js(
			// random
			"<?php echo PATH_THEME; ?>/js/vendor/jquery-1.10.2.min.js",
			"<?php echo PATH_THEME; ?>/js/vendor/jquery.transit.min.js",
			"<?php echo PATH_THEME; ?>/js/vendor/custom.modernizr.js",
			"<?php echo PATH_THEME; ?>/js/vendor/google-code-prettify/prettify.js",
			// foundation
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.alerts.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.clearing.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.cookie.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.dropdown.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.forms.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.joyride.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.magellan.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.orbit.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.reveal.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.section.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.tooltips.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.topbar.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.interchange.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.placeholder.js",
			"<?php echo PATH_THEME; ?>/js/foundation/foundation.abide.js",
			
			function() {
				// those are loaded, but the page may not be ready here
				// let foundation load up anyway
				$(document).foundation();
			}
		);
		
		head.ready(function() {
			prettyPrint(); // code highlight
			
			// don't show invalid images
			$("img").error(function () { 
				// $(this).hide();
				$(this).css({visibility:"hidden"}); 
			});
		});
		
	</script>
	
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="<?php echo PATH_THEME; ?>/css/responsive-tables/ie.css">
	<![endif]-->
	
	
	<!-- IE Fix for HTML5 Tags -->
	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
	
	