<section>
	<div id="error"></div>
	<ul>
		<?php
			if (isgetset("logout")) {
				echo "<li>You have been successfully logged out!</li>";
			}
		?>
		<? if (user()->loggedin) {
		?>
		<li class="big">
			<a href="<?=linkify("post", "new")?>" class="orange button"> Create a Poll </a>
		</li>
		<li class="normal">
			<a href="<?=linkify("u", user()->username)?>" style="border: 0px"><?=user()->username?></a>
		</li>
		<li class="normal">
			<a href="<?=linkify("account", "favorites")?>">Favourites</a>
		</li>
		<li class="normal">
			<a href="<?=linkify("account", "settings")?>">Settings</a>
		</li>
		<li class="normal">
			<a href="<?=linkify("user", "logout")?>">Logout</a>
		</li>
		<? } else {?>
		<li class="big">
			<a href="ajax/?login" class="blue button modal">Login</a>
		</li>
		<li class="big">
			<a href="<?=linkify("user", "register")?>" class="orange button">Register</a>
		</li>
		<? } ?>
	</ul>
</section>