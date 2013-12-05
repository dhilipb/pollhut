<?php
	/* Email Dialog box */
?>
<div class="close-button"></div>
<form action="<?=linkify("user", "email")?>" method="post">
    <div class="placeholder-text">
        <label for="email_name">Your name</label>
        <input type="text" id="email_name" name="name" class="text-input" />
    </div>
    <div class="placeholder-text">
        <label for="email_email">Your Email</label>
        <input type="text" id="email_email" name="email" class="text-input" <?=user()->loggedin ? "value='".user()->email."'" : null?> />
    </div>
    <div class="placeholder-text">
        <label for="email_recp">Recipients emails</label>
        <input type="text" id="email_recp" name="recp" class="text-input" />
    </div>
	<div class="fs11">Separate emails by comma</div>
	<br />
    <div class="placeholder-text">
        <label for="email_msg">Your message</label>
        <textarea id="email_msg" class="text-input" name="message"> </textarea>
    </div>
    <br />
    <input type="hidden" name="post" value="<?=get("post")?>" />
    <input type="submit" value="Send" data-submit="Sending.." class="blue button" /> 
</form>