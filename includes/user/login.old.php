<?php
session_start();

$site = get("login");
// Facebook
if ($site === "facebook" && isgetset("code")) {
	$token_url = FACEBOOK_TOKEN_URL . $_GET["code"];
	$response = file_get_contents($token_url);
	parse_str($response, $params);
	
	$graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];
	$fb = json_decode(file_get_contents($graph_url));

	user() -> login("facebook", $fb);
	
	header("Location: " . (isset($_SESSION["redirect"]) ? $_SESSION["redirect"] : SITEURL));
	
} else if ($site === "facebook") {
	header("Location: " . FACEBOOK_API_URL);

	// Twitter
} else if ($site == "twitter" && isgetset("oauth_token") && isgetset("oauth_verifier")) {
	require ("includes/lib/oauth/twitter.php");

	if ($_GET["oauth_token"] !== $_SESSION["oauth_token"]) {
		session_destroy();
		header("Location: " . TWITTER_LINK);
	}

	/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
	$connection = new TwitterOAuth($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

	/* Request access tokens from twitter */
	$access_token = $connection -> getAccessToken($_GET['oauth_verifier']);

	/* Remove no longer needed request tokens */
	unset($_SESSION['oauth_token']);
	unset($_SESSION['oauth_token_secret']);

	$connection = new TwitterOAuth($access_token['oauth_token'], $access_token['oauth_token_secret']);
	$content = $connection -> get('account/verify_credentials');

	user() -> login("twitter", $content);

	header("Location: " . SITEURL);

} else if ($site === "twitter") {
	require ("includes/lib/oauth/twitter.php");

	/* Build TwitterOAuth object with client credentials. */
	$connection = new TwitterOAuth();

	/* Get temporary credentials. */
	$request_token = $connection -> getRequestToken(TWITTER_LINK);
	
	/* Save temporary credentials to session. */
	$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

	/* If last connection failed don't display authorization link. */
	switch ($connection->http_code) {
		case 200 :
		/* Build authorize URL and redirect user to Twitter. */
			$url = $connection -> getAuthorizeURL($token);
			print($url);
			header('Location: ' . $url);
			break;
		default :
		/* Show notification if something went wrong. */
			die($connection -> http_code . ': Could not connect to Twitter. Refresh the page or try again later.');
	}

	// Google
} else if ($site === "google") {
	require ("includes/lib/openid/openid.php");

	// Change 'localhost' to your domain name.
	$openid = new LightOpenID('pollhut.com');
	if (!$openid -> mode) {
		$openid -> identity = 'https://www.google.com/accounts/o8/id';
		$openid -> required = array('namePerson/first', 'namePerson/last', 'contact/email', );
		$openid -> returnUrl = GOOGLE_LINK;
		header('Location: ' . $openid -> authUrl());
	} else if ($openid -> mode == 'cancel') {
		header("Location: " . SITEURL . "/login");
	} else if ($openid -> validate()) {
		$attr = $openid -> getAttributes();

		// Add id to attributes
		$id = parse_url($openid -> identity);
		$id = substr($id["query"], 3);
		$attr["id"] = $id;

		user() -> login("google", $attr);
		header("Location: " . SITEURL);
	}
} else if (user()->loggedin) {
	header("Location: " . SITEURL);
} else {
	$this -> title = "Login";
	$_SESSION["redirect"] = strpos(REFERER, "logout") === false || strpos(REFERER, "login") === false 
							? REFERER : SITEURL;
?>
<small>Last Updated: <?=time_since(filemtime("includes/user/login.php"))
	?></small>
<div style="height: 180px">
	<div class="float-left" style="width: 33%; margin-right: 10px;">
		<h3>Sign in with Facebook</h3>
		<p class="small">
			Use Facebook to login to <?=SITENAME
			?>.
		</p>
		<p class="small">
			If you do not have a Facebook account, you
			can use one of the other two services or
			register at <a class="underline" href="//facebook.com">www.facebook.com</a>
		</p>
		<div class="social fb">
			<a href="<?=linkify("login","facebook")?>"></a>
		</div>
	</div>
	<div class="float-left" style="width: 33%; margin-right: 10px;">
		<h3>Sign in with Twitter</h3>
		<p class="small">
			Use Twitter to login to <?=SITENAME
			?>.
		</p>
		<p class="small">
			If you do not have a Twitter account, you
			can use one of the other two services or
			register at <a class="underline" href="//twitter.com/signup">www.twitter.com</a>
		</p>
		<div class="social tw">
			<a href="<?=linkify("login","twitter")?>"></a>
		</div>
	</div>
	<div class="float-left" style="width: 30%;">
		<h3>Sign in with Google</h3>
		<p class="small">
			Use Google to login to <?=SITENAME
			?>.
		</p>
		<p class="small">
			If you do not have a Google account, you
			can use one of the other two services or
			register at <a class="underline" href="//accounts.google.com/SignUp">www.google.com</a>
		</p>
		<div class="social gg">
			<a href="<?=linkify("login","google")?>"></a>
		</div>
	</div>
</div>

<h3>Worried?</h3>
<p>
	We promise you we will not post anything without your permission.
</p>
<strong>What we collect:</strong>
<ul class="bullet">
	<li>
		Your Name
	</li>
	<li>
		Your Gender
	</li>
	<li>
		Your Email Address
	</li>
</ul>

<h3>Benefits</h3>
<ul class="bullet">
	<li>Create polls</li>
	<li>Manage polls</li>
	<li>Comment on posts</li>
	<li>Add options to polls (where allowed)</li>
	<li>View post statistics</li>
</ul>
<?php
}
?>