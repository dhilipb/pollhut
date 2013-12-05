<?php
/*
 * Get Description from Wikipedia
 * Or Get Search suggestions
 */
if (ispostset("title")) {	
	$para_limit = 3;
	$min_chars = 300;
	$title = post("title");
	$title = str_replace(" ", "_", $title);
	$url = "http://en.wikipedia.org/w/index.php?title=".$title."&printable=yes";
	
	require "../includes/lib/simple_html_dom.php";
	$html = file_get_html($url);
	$para_count = 0;
	
	$errorimg = "Wiktionary-logo-en.png";
	foreach($html->find(".infobox img") as $img) {
			if (strpos($img->src, $errorimg) === false) {
				echo "<div style='clear:both'>
						<img src='{$img->src}' alt='{$img->alt}' style='float: left; margin: 0 10px 10px 0; max-width: 200px; max-height: 200px;'/>";
			} else {
				die();
			}
			break;
	}
	
	
	echo "<h2>".$html->find("#firstHeading", 0)->innertext."</h2>\n";
	
	foreach($html->find(".mw-content-ltr p") as $elem) {
		$desc = trim(preg_replace('/\[[0-9]*\]/', '', $elem->plaintext));
		
		if ($desc != '† Appearances (Goals).') {
			echo "<p>" . $desc . "</p>";
			
			$para_count++;
			if ($para_count == $para_limit) break;
		}
		
	}
	echo "[Source: <a href=\"http://en.wikipedia.org/w/index.php?title=$title\">Wikipedia</a>]</div>";
	
} else if (ispostset("search")) {
	$search = urlencode(post("search"));
	$url = "http://en.wikipedia.org/w/api.php?action=opensearch&search=$search&format=json";
	
	$ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 	//url
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	//copy data to variable
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
    $contents = curl_exec($ch); 
    curl_close($ch); 
	
	//example: ["facebook",["Facebook","..."]]
	//die($contents);
	$val = substr($contents, strlen('["'.$search.'",["'), -3);
	$val = "<li>" . str_replace("\",\"", "</li><li>", $val) . "</li>";
	if ($val == "<li></li>") {
		echo "<li class='no'>No Results found</li>";
	} else {
		echo $val;
	}
}
?>