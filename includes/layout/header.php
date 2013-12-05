<?php	
	// CSS
	minifyfiles(array('style/desktop/typo.css', 'style/chart.css','style/main.css', 'style/desktop/layout.css'), 'style/style-min.css');
	
	// JS
	minifyfiles(array('js/functions.js', 'js/my.js', 'js/chart.js'), 'js/js-min.js');
	
	$title = empty($this->title) ? SITENAME . " - Create and share Polls" : $this->title . " - " . SITENAME;
	$this->description = empty($this->description) ? "Create, share and collaborate polls. PollHut allows anyone to create a poll and let users vote on it. You can also easily gather user's opinions." : $this->description; 
?>
<head>
	<title><?=$title?></title>
	<meta http-equiv="CACHE-CONTROL" content="no-cache" />
	<meta content="IE=7, IE=9" http-equiv="X-UA-Compatible" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<!-- Favicon -->
	<link rel="icon" href="<?=SITEURL?>/images/favicon.png" type="image/png" />
	<link rel="shortcut icon" href="<?=SITEURL?>/images/favicon.png" type="image/png" />
	<link rel="apple-touch-icon" href="<?=SITEURL?>/images/favicon.png" />
	
	<!-- Facebook -->
	<meta property="og:title" content="<?=$title?>" />
	<meta property="og:type" content="article" />
	<meta property="og:site_name" content="<?=SITENAME?>" />
	
	<!-- SEO -->
	<meta name="description" content="<?=$this->description?>" />
	<meta name="keywords" content="<?=SEO_KEYWORDS?><?=empty($this->keywords) ? null : ", ".$this->keywords?>" />
   	<base href="<?=SITEURL?>" />
	
	<!-- Style -->
	<link href="<?=SITEURL?>/style/style-min.css" rel="stylesheet" />
	
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
				
		if (script('js/js-min.js')) {
			<?php
				// Get the user's coordinates
				if (empty(user()->longitude)) {
			        echo "\$.ajax({url: 'ajax/', type: 'post', data: 'location=1'});\n";
				}
				
			?>
		}
	</script>
	
	<!--[if lte IE 8]>
		<script src="js/html5.js" type="text/javascript"></script>
		<script src="js/cufon.js" type="text/javascript"></script>
		<script src="js/Wow.js" type="text/javascript"></script>
		<script type="text/javascript">
			Cufon.replace('h1, h1 > a, #pagination', {fontFamily: 'Wow'});
			Cufon.replace('h2, h2 > a, h3, h3 *, .browse .title, #topbar .big a, fieldset > legend', {fontFamily: 'WowM'});
		</script>
	<![endif]-->
</head>