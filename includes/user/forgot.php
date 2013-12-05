<?php
	$this->title = "Forgot your details?";
	if (ispostset("forgot")) {
		$email = post("email");
		$forgotqry = select("*", "tbl_users", "WHERE email = '$email'");
		while ($forgot = assoc($forgotqry)) {
			mail($email, "Password Recovery Details at Pollhut.com", 
				"The recovery details you requested are as follows: \r\n". 
				"Your username: " . $forgot["username"] . "\r\n".
				"Your password: " . $forgot["password"] . "\r\n",
				"From: info@pollhut.com");
		}
		die();
	} else {
		
?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#forgotpass").submit(function(e) {
			if ($("#forgotpass input[name=email]").val() == "") {
				error("Please enter your email");
			} else {
				$.post("/user/forgot", $(this).serialize(), function() {
					$("#forgotpass input[name=email]").val("");
					$("#forgotpass input[type=submit]").val("Email Sent!");
					error("Your email has been sent. Please check your inbox or spam folder for your details.");
				});
			}
			return false;
		});	
	});
</script>
<form id="forgotpass" method="post">
	<p class="fs14">
		Enter your email address and your username and password will be emailed to you.
	</p>
	<div class="placeholder-text">
		<label>Email address</label>
		<input type='text' class='text-input' name='email' />
	</div>
	<br />
	<input type="hidden" name="forgot" value=1 />
	<input type='submit' value="Submit" class="blue button" data-submit="Sending email.." />
</form>
<?php
	}
?>