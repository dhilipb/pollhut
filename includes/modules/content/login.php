 <?php
	$ref = ispostset("referrer") ? 
				$_POST["referrer"] : 
				(get("user") == "login" || isgetset("login") ? 
						REFERER : 
						CURRENT_URL);
	if (!strpos($ref, 'logout') === false) {
		$ref = 'index.php';
	}
?>
<form method="post" id="loginform" action="<?=linkify("user", "login")?>">
	<div class="placeholder-text">
		<label>Username</label>
		<input name='username' type="text" class="text-input" />
	</div>
	<div class="placeholder-text">
		<label>Password</label>
		<input name="password" type="password" class="text-input" />
	</div>
	<div class="clear-after">
		<span class="float-left fs12" style="margin-top: 10px; width: 50%;">
			<a href="<?=linkify("user", "forgot")?>" style="display: block;">Forgot your username/password?</a>
		</span>
		<input type="hidden" name="referrer" value="<?=$ref?>" />
		<input type="submit" style="margin: 7px 0" class="float-right black button" value="Login" data-submit="Logging in.." />
	</div>
</form>