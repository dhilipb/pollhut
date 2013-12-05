<section class="content">
	<h1>Welcome</h1>
	<p class="fs14 grey clear-after">
		PollHut is a place where you can create and share polls. <br>
		Register to create polls and let users vote on it!<br>
		Login to view hidden features.
	</p>
</section>
<div class="sep"></div>
<section class="content">
	<h1>Popular Polls 
		<span><a href="<?=linkify("list", "popular")?>" class="grey underline fs13">Browse All</a></span></h1>
	<?php
		$qry_post = select("*", "view_posts_popular_nodate", "WHERE public = 1 LIMIT 0, 6");
		require "includes/post/list.php";
	?>
</section>