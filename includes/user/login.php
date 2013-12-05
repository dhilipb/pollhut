<?php
	if (ispostset("username")) {
		if (user()->login(post("username"), post("password"))) {
			$ref = parse_url(post("referrer"));
			$ref = $ref["path"];
			if ($ref == "/user/logout" || $ref == "/user/register") {
				$ref = "/";
			}
			header("Location: " . $ref);
		} else {
			echo '<div class="status error">Invalid login details. Please try again</div>';
			require "includes/modules/content/login.php";
			echo "<h3 class='hr'>Forgot your details?</h3>";
			require "includes/user/forgot.php";
			$this->title = "Login Error";
		}
	} else {
		$this->title = "Login";
		require "includes/modules/content/login.php";
	}
?>