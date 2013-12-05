<?php	
	// post_id; title colour; show title; show top x options
    $post = new Post();
    $post->db(get("embed"));
	
	define(EMBED_SHOW, is_numeric(get("show")) ? get("show") : INFINITE);
?>
	<head>
		<base href="<?=SITEURL?>" />
		<link href="/style/chart.css" rel="stylesheet" />
		<?php
			
		?>
		<style type='text/css'>
			body {
				padding: 10px;
			}
			h1 {
				margin-bottom: 5px;
				font-size: 16px;
				display: <?=!emptyget('title') && get('title') == "false" ? "none" : "block"?> 
			}
			a {
				text-decoration: none;
				color: black;
			}
			h1 a {
				color: #<?=!emptyget("color") ? get('color') : "000"?>;
			}
			html, body {
				background: transparent !important;
			}
			* {
				font-family: Arial;
			}
		</style>
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
	<body>
		<?php
			require "includes/chart/box.php";
		?>
	</body>
<?php
	die();
?>