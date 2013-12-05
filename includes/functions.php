<?php
/*
 * functions.php: Contains functions used within the website
 */

// Returns the view of current page
function view() {
	if (empty($_GET) || isgetset("logout"))
		return "frontpage";
	if (isgetset("post") && get("post") != "delete" && get("post") != "new")
		return "postview";
	if (get("account") === "favorites")
		return "fav";
	if (isgetset("search"))
		return "search";
	if (isgetset("logout"))
		return "logout";
	return false;
}

/*
 * Checks the links and returns a valid url for user with hyperlinks
 */
function linkify($var, $val) {
	$allowedGets = array();
	// Get variables allowed to append to the link
	switch ($var) {
		case "display" :
			$allowedGets = array("post");
		case "task" :
			$allowedGets = array("post");
			break;
		case "list_cat" :
		// List and cat
			$var = "list";
			$allowedGets = array("cat");
			break;
		case "comments" :
			$allowedGets = array("post", "sort");
			break;
		case "sort" :
			$allowedGets = array("u", "post", "comments", "show");
			break;
		case "show" :
			$allowedGets = array("u", "sort", "account");
			break;
		case "folder" :
			$allowedGets = array("account");
			break;
		case "pg" :
			$allowedGets = array("list", "account", "user");
			break;
		case "post" :
			if (is_object($val)) {
				$val = $val -> id . "_" . $val -> title;
			}
			break;
		default :
			break;
	}
	
	// add allowed gets
	foreach ($allowedGets as &$get) {
		if (isset($_GET[$get]))
			$url .= $get . "/" . seourl($_GET[$get]) . "/";
	}
	
	// current var and value
	if ($val != "")
		$url .= $var . "/" . seourl($val);
	
	// remove trailling slash
	if (substr($url, -1) == "/")
		$url = substr($url, 0, -1);
	
	// custom urls
	global $CUSTOM_URLS;
	$search = array();
	$replace = array();
	foreach($CUSTOM_URLS as $long => $short) {
		array_push($search, substr($long, 1));
		array_push($replace, substr($short, 1));
	}

	return "/" . str_replace($search, $replace, $url);
}

function seourl($url) {
	$url = str_replace("'", "", $url);
	$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
	$new_pattern = array("_", "_", "");

	return strtolower(preg_replace($old_pattern, $new_pattern, $url));
}

/*
 * Converts MySQL time to PHP date
 */
function mysql_phpdate($format, $sql) {
	return date($format, strtotime($sql));
}

/*
 * Works out the time since the entry post, takes a an argument in unix time (seconds)
 */
function time_since($time, $suffix = " ago") {
	return secs_in_words(time() - (is_numeric($time) ? $time : mysql_phpdate("U", $time))) . $suffix;
}

/*
 * Seconds in words
 */
function secs_in_words($secs) {
	$chunks = array( array(60 * 60 * 24 * 365, 'year'), 
					array(60 * 60 * 24 * 30, 'month'), 
					array(60 * 60 * 24 * 7, 'week'), 
					array(60 * 60 * 24, 'day'), 
					array(60 * 60, 'hour'), 
					array(60, 'minute'), 
					array(1, 'second'));

	$j = count($chunks);
	for ($i = 0; $i < $j; $i++) {

		$chunk_secs = $chunks[$i][0];
		$name = $chunks[$i][1];

		// finding the biggest chunk (if the chunk fits, break)
		if (($count = floor($secs / $chunk_secs)) != 0)
			break;
	}

	return ($count == 1) ? (($name == "hour") ? "an" : "a") . " $name" : "$count {$name}s";
}

/*
 * Returns true if an username does not exist, false otherwise
 */
function checkUsername($username) {

	$exp = explode("_", $username);
	$prefix = reset($exp);

	// should not start with the following prefix
	if ($prefix == "fb" || $prefix == "g" || $prefix == "tw" || $username == "guest")
		return false;

	// returns true if no rows are found, false otherwise
	return !rows(select("id", "tbl_users", "WHERE username = '$username'"));
}

/*
 * Returns true if the email address is valid, false otherwise
 */
function checkEmail($email) {
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		list($username, $domain) = explode('@', $email);
		if (!checkdnsrr($domain, 'MX')) {
			return false;
		}
		return true;
	}
	return false;
}

/*
 * Creates a yellow box
 */
function create_info_box($text) {
	echo "<div class=\"infobox\"><a href=\"#close\"><div class=\"close\"></div></a>$text</div>";
}

/*
 * Makes links into clickable hyperlinks
 */
function make_links_clickable($text) {
	$pattern = "@\b(https?://)?(([0-9a-zA-Z_!~*'().&=+$%-]+:)?[0-9a-zA-Z_!~*'().&=+$%-]+\@)?(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-zA-Z_!~*'()-]+\.)*([0-9a-zA-Z][0-9a-zA-Z-]{0,61})?[0-9a-zA-Z]\.[a-zA-Z]{2,6})(:[0-9]{1,4})?((/[0-9a-zA-Z_!~*'().;?:\@&=+$,%#-]+)*/?)@";

	$text = preg_replace($pattern, '<a href="\0">\0</a>', $text);

	return $text;
}

// Closes all tags in a given html code
function close_tags($html) {
	#put all opened tags into an array
	preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);

	$openedtags = $result[1];
	#put all closed tags into an array
	preg_match_all('#</([a-z]+)>#iU', $html, $result);
	$closedtags = $result[1];
	$len_opened = count($openedtags);

	# all tags are closed

	if (count($closedtags) == $len_opened) {
		return $html;
	}

	$openedtags = array_reverse($openedtags);

	# close tags

	for ($i = 0; $i < $len_opened; $i++) {
		if (!in_array($openedtags[$i], $closedtags)) {
			$html .= '</' . $openedtags[$i] . '>';
		} else {
			unset($closedtags[array_search($openedtags[$i], $closedtags)]);
		}

	}

	return $html;

}

// Creates a sort box
function sort_box($options, $linkify = "sort") {
	echo "Sort by: ";
	foreach ($options as $i) {
		$link = linkify($linkify, $i);
		$class = (get($linkify) == $i || 
						PATH == $link || 
						(!isgetset($linkify) && $i == $options[0])) ? "strong" : "";
		
		$bullet = $i != $options[count($options) - 1] ? BULLET : "";
		echo " <a href=\"$link\" class=\"sort $class\" rel=\"$linkify\">" . ucwords($i) . "</a> $bullet";
	}
}

// Check if an user has already liked a post
function check_if_liked($post_id) {
	$user =  user() -> id;
	$already_liked = select("id", "tbl_posts_likes", "WHERE post_id = '$post_id' 
			AND ((user_id = '$user' AND user_id > '0')
			OR ip = '" . $_SERVER["REMOTE_ADDR"] . "')");

	if (!rows($already_liked))
		return -1;
	else {
		$already_liked = assoc($already_liked);
		return $already_liked["vote"];
	}
}

// Convert word to a plural of the word if value is more than 1
function pluralize($value, $text) {
	return $value === "1" ? $text : $text . 's';
}

// Returns the POST variable of a given variable name
function post($text) {
	global $conn;
	if (!$conn) {
		return $_POST[$text];
	}
	return esc($_POST[$text]);
}

// Returns the GET variable of a given variable name
function get($text) {
	global $conn;
	if (!$conn) {
		return $_GET[$text];
	}
	return esc($_GET[$text]);
}

// check if a post or a get variable is set
function ispostset($text) {
	return isset($_POST[$text]);
}

function isgetset($text) {
	return isset($_GET[$text]);
}

function emptypost($text) {
	return empty($_POST[$text]);
}
function emptyget($text) {
	return empty($_GET[$text]);
}


// returns the user stored in session or sets it
function user($user = 0) {
	if (empty($user)) {
		session_start();
		return unserialize($_SESSION["user"]);
	} else
		$_SESSION["user"] = serialize($user);
}

// logs string to a file
function logstr($str) {
	system("echo $str >> log.txt");
}

// Minimizes the code for a given file or code
function minify($file) {
	return minify_code(file_get_contents($file));
}

function minify_code($code) {
	//return $code;
	/* remove comments */
	$code = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $code);
	/* remove tabs, spaces, newlines, etc. */
	$code = str_replace(array("\r\n", "\r", "\t", "\n", '  ', '    ', '     '), '', $code);
	/* remove other spaces before/after ) */
	$code = preg_replace(array('(( )+\))', '(\)( )+)'), ')', $code);
	return $code;
}


function minifyfiles($files, $out) {
	$put = false;
	foreach($files as $file) {
		$contents .= file_get_contents($file);
		if (filemtime($file) > filemtime($out)) {
			$put = true;
		}
	}
	
	if ($put)
		file_put_contents($out, minify_code($contents));
}

// returns the first element in a given array
function array_first($array) {
	//foreach ($array as $item)
	//return $item;
	return reset($array);
}

/*
 * Converts normal line breaks into HTML line breaks
 */
function add_newlines($str) {
	$str = str_replace("\r\n", "<br />", $str);
	return $str;
}

// ----------------------- Form -----------------------
function formval($val) {
	return "id=\"$val\" name=\"$val\" value=\"".post($val)."\"";
}
function formchecked($name, $val) {
	return "name=\"$name\" value=\"$val\" " . (post($name) == $val ? "checked=\"checked\"" : "");
}



function is_mobile() {
	$mobile_browser = 0;
	$agent = $_SERVER['HTTP_USER_AGENT'];
	
	if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($agent))) {
		$mobile_browser++;
	}
	
	/*if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
		$mobile_browser++;
	}*/   
	 
	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
	$mobile_agents = array(
		'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
		'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
		'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
		'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
		'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
		'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
		'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
		'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
		'wapr','webc','winw','winw','xda ','xda-');
	 
	if (in_array($mobile_ua,$mobile_agents)) {
		$mobile_browser++;
	}
	 
	// if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini') > 0) {
		// $mobile_browser++;
	// }
	 
	if (strpos(strtolower($agent),'windows') > 0) {
		$mobile_browser = 0;
	}
	
	// if (preg_match('/(symbian|smartphone|phone|android)/i', 
			// strtolower($agent))) {
		// $mobile_browser++;
	// }
	
	if (strpos(strtolower($agent),'ipad') > 0) {
		$mobile_browser = 0;
	}
	return $mobile_browser > 0;
}
?>