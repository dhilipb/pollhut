<section class="content">
	<img src="images/welcome.jpeg" class="float-right" />
	<h1>Welcome</h1>
	<p class="fs14 grey clear-after">
		PollHut is a place where you can create and share polls. <br>
		Register to create polls and let users vote on it!<br>
		Confused what to choose about something? Use PollHut to ask your question with your options. <br>
		Use PollHut as a research tool, by asking your questions and providing your options. 
	</p>
</section>
<div class="sep" style="margin-top: -10px;"></div>
<section>
	<h1>Browse</h1>
	<?php 
		require "includes/modules/browse.php"; 
	?>
</section>
	<div class="sep" style="margin-top: -10px;"></div>
<section class="content">
	<h1>Recent Polls 
		<span><a href="<?=linkify("list", "recent")?>" class="grey underline fs13">Browse All</a></span></h1>
	<?php
		$qry_post = select("*", "view_posts_recent", "WHERE public = 1 LIMIT 0, 6");
		require "includes/post/list.php";
		require "footer.php";
	?>
</section>