<?php
if (user()->loggedin)
    header("Location: index.php");

if (ispostset("register")) {
    $this->module = "right";
    
    $username = post("reg_username");
	$email = post("reg_email");
	$password = post("reg_password");
	$verify = post("reg_verify");
	$age = post("reg_age");
	$gender = post("reg_gender");
	$error = "";
	
	if (empty($username)) {
		$error .= "<li>Username is empty</li>";
	} else if (!checkUsername($username)) {
		$error .= "<li>Username already exists. Please choose another one.</li>";
	}
	if (empty($email)) {
		$error .= "<li>Email is empty</li>";
	} else if (!checkEmail($email)) {
		$error .= "<li>Invalid email address</li>";
	}
	if (empty($password)) {
		$error .= "<li>Password is empty</li>";
	}
	if ($password != $verify) {
		$error .= "<li>Passwords do not match</li>";
	}
	if (empty($age)) {
		$error .= "<li>Please choose your age group</li>";
	}
	if (empty($gender)) {
		$error .= "<li>Please choose your gender</li>";
	}
	
    if (empty($error) && user()->register()) {
    	$this->title = "Register";
?>
        <p>Thank you for registering with us.</p>
		<p>You will soon receive a confirmation email to the email address you have registered with.</p>
		<p>To continue, please login.</p>
<?php        
    } else if (!empty($error)) {
		echo "<div class=\"status error\">" .
		"<strong>Please check the following fields:</strong>" .
		"<ul class=\"bullet\">" .
		$error .
		"</ul></div>";
    }
}
if (!empty($error) || !ispostset("register")) {
$this->title = "Register";
?>
<style type="text/css">
	.radio li {width: 100px;}
</style>
<script type="text/javascript">
	
</script>
<!-- ------- Register Page --------- -->
<div id="register">
    <form id="regform" method="post" action="<?=CURRENT_URL?>">
    	<h3>User Details</h3>
        <div class="placeholder-text">
            <label for="username">Username</label>
            <input class="text-input username" type=text <?=formval("reg_username")?> maxlength="15" autocomplete="off"/>
        </div>
        
        <div class="placeholder-text">
            <label for="email">Email</label>
            <input class="text-input email" type=text <?=formval("reg_email")?> maxlength="50" autocomplete="off"/>
            <div class="info">Your email address is required for password recovery</div>
        </div>
        <div class="placeholder-text">
            <label for="password">Password</label>
            <input class="text-input password" type=password <?=formval("reg_password")?> maxlength="50" autocomplete="off"/>
        </div>    
        <div class="placeholder-text">
            <label for="verify">Verify Password</label>
            <input class="text-input verify-password" type=password <?=formval("reg_verify")?> maxlength="50" autocomplete="off"/>
        </div>
        <h3>Age Group</h3>
        <ul id='' class="radio">
        	<?php
				foreach ($AGES as $k => $v) {
					echo "<li><label>
        			<input type=\"radio\"".formchecked("reg_age", $k)."/>
        			$v </label></li>";
				}
        	?>
        </ul>
        <h3>Gender</h3>
        <ul class="radio">
        	<li>
        		<label>
        			<input type="radio" <?=formchecked("reg_gender", "male")?> />
        			Male
        		</label>
        	</li>
        	<li>
        		<label>
        			<input type="radio" <?=formchecked("reg_gender", "female")?> />
        			Female
        		</label>
        	</li>
        </ul>
        <p class="fs12">By creating an account you agree to our <a href="<?=linkify("page","tos");?>" class="underline">Terms of Service</a> and <a href="<?=linkify("page","privacy");?>" class="underline">Privacy Policy</a></p>
        <input type=hidden name="register" value="1" />
        <input type="submit" class="blue button" data-submit="Registering.." value="Register" />
    </form>
</div>
<? } ?>