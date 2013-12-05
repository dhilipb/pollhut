<?php	
	// CSS
	minifyfiles(array('style/mobile/typo.css', 'style/chart.css', 'style/main.css', 'style/mobile/layout.css'), 'style/style-min.mobile.css');
	
	// JS
	minifyfiles(array('js/functions.js', 'js/my.js', 'js/chart.js'), 'js/js-min.js');
	
	$title = empty($this->title) ? SITENAME . " - Create and share Polls" : $this->title . " - " . SITENAME;
?>
<head>
	<title><?=$title?></title>
	<meta http-equiv="CACHE-CONTROL" content="no-cache" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>  
	<base href="<?=SITEURL?>" />
	
	<!-- Favicon -->
	<link rel="icon" href="<?=SITEURL?>/images/favicon.png" type="image/png">
	<link rel="shortcut icon" href="<?=SITEURL?>/images/favicon.png" type="image/png">
	<link rel="apple-touch-icon" href="<?=SITEURL?>/images/favicon.png">
	
	<!-- Style -->
	<link href="<?=SITEURL?>/style/style-min.mobile.css" rel="stylesheet" />
	
	<!-- Javascript Files -->
	<script src="<?=JQUERY?>" type="text/javascript"></script>
	<script type="text/javascript">
		
		function script(src) {
			var s = document.createElement("script");
			s.src = src;
			s.type = 'text/javascript';
	  		s.async = true;
	  		( document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0] ).appendChild( s );
	  		return true;
		}
		
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-163217-10']);
		_gaq.push(['_setDomainName', 'pollhut.com']);
		_gaq.push(['_trackPageview']);
		script(('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js');
				
		if (script('/js/js-min.js')) {
			<?php
				// Get the user's coordinates
				if (empty(user()->longitude)) {
			        echo "\$.ajax({url: 'ajax/', type: 'post', data: 'location=1'});\n";
				}
				
			?>
		}
	</script>
</head>