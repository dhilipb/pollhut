<div class="modules" id="rightmodule">
	<?php if (isgetset("cat")) {
		require ("content/subcat.php");
	} ?>
	<?php if (!user()->loggedin && get("user") != "login") {
	?>
	<div class="module">
		<h1>Login</h1>
		<?php
			require ("content/login.php");
		?>
	</div>
	<?php }?>
	<div class="module">
		<h1>Recent Posts</h1>
		<?php
			require ("content/recentposts.php");
		?>
	</div>
	<div class="module">
		<h1>Latest Comments</h1>
		<?php
			require ("content/comments.php");
		?>
	</div>
</div>