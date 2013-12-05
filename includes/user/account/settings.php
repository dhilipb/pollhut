<?php
session_start();
require_once ("includes/database.php");
$this -> title = "Settings";

$user = user();
if (ispostset("submit")) {
	$values = array();
	$error = "";
	if (emptypost("st-username")) $error = "<li>Username is empty</li>";
	else if (post("st-username") != $user->username && !checkUsername(post("st-username")))
		$error = "<li>Username already exists</li>";
	else $values["username"] = post("st-username");
	
	if (emptypost("st-email")) $error = "<li>Email is empty</li>";
	else if (!checkEmail(post("st-email"))) $error = "<li>Invalid email address</li>";
	else $values["email"] = post("st-email");
	
	if (emptypost("st-gender")) $error = "<li>Please select your gender<li>";
	else $values["gender"] = post("st-gender");
	
	if (emptypost("st-age")) $error = "<li>Please select your age group<li>";
	else $values["age"] = post("st-age");
	
	if (!emptypost("st-currpass")) {
		if (emptypost("st-newpass")) {
			$error = "<li>New password is empty<li>";
		} else if (post("st-newpass") != post("st-verifypass")) {
			$error = "<li>Passwords do not match</li>";
		} else if (post("st-currpass") != $user->password) {
			$error = "<li>Invalid current password</li>";
		} else {
			$values["password"] = post("st-newpass");
		}
	} else if (emptypost("st-currpass") && !emptypost("st-newpass")) {
		$error = "<li>Enter your current password to change</li>";
	}
	
	if (empty($error)) {
		update("tbl_users", $values, "WHERE id = '{$user->id}'");
		$user -> db();
		$_SESSION["success_settings"] = "<div class=\"status\">" .
					"<strong>All fields updated successfully</strong>" .
					"</div>";
		header("Location: " . PATH);
		
	} else {
		echo "<div class=\"status error\">" .
		"<strong>Please check the following fields:</strong>" .
		"<ul class=\"bullet\">" .
		$error .
		"</ul></div>";
    }
} else {
	$_POST["st-username"] = $user->username;
	$_POST["st-email"] = $user->email;
	$_POST["st-age"] = $user->age;
	$_POST["st-gender"] = $user->gender;
	echo $_SESSION["success_settings"];
	unset($_SESSION["success_settings"]);
}
?>

<style type="text/css">
	.radio li {width: 100px;}
</style>
<form method="post" action="<?=PATH?>">
	<h3>User Details</h3>
	<div class="placeholder-text">
		<label for="username">Username</label>
		<input class="text-input" type='text' <?=formval("st-username")?> maxlength="15" autocomplete="off"/>
	</div>
	<div class="placeholder-text">
		<label for="email">Email</label>
		<input class="text-input" type='text' <?=formval("st-email")?> maxlength="50" autocomplete="off"/>
		<div class="info">
			Your email address is required for password recovery
		</div>
	</div>
	<h3>Change password</h3>
	<div class="placeholder-text">
		<label for="currpassword">Current Password</label>
		<input class="text-input" type='password' <?=formval("st-currpass")?> maxlength="50"/>
	</div>
	<div class="placeholder-text">
		<label for="password">New Password</label>
		<input class="text-input" type=password <?=formval("st-newpass")?> maxlength="50"/>
	</div>
	<div class="placeholder-text">
		<label for="verify">Verify Password</label>
		<input class="text-input" type=password <?=formval("st-verifypass")?> maxlength="50" />
		<div class="info">
			Enter your current password and your new password to change.
		</div>
	</div>
	<h3>Age Group</h3>
	<ul id='' class="radio">
        	<?php
				foreach ($AGES as $k => $v) {
					echo "<li><label>
        			<input type=\"radio\" ".formchecked("st-age", $k)."/>
        			$v </label></li>";
				}
        	?>
	</ul>
	<h3>Gender</h3>
	<ul class="radio">
		<li>
			<label>
				<input type="radio" <?=formchecked("st-gender", "male")?> />
				Male </label>
		</li>
		<li>
			<label>
				<input type="radio" <?=formchecked("st-gender", "female")?> />
				Female </label>
		</li>
	</ul>
	<br>
	<input type="hidden" name="submit" value=1 />
	<input type="submit" value="Submit" data-submit="Submitting.." class="blue button"/>
</form>