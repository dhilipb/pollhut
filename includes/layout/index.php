<body>
	<a href="ajax/feedback.html" class='modal' id="feedback_link"></a>
	<div id="topbar">
		<?php require "includes/modules/top.php"; ?>
	</div>
	<section id="header">
		<a id="logo" href="/">
			<div></div>
		</a>
		<div id="searchbox">
			<form method="get" action="/">
				<label id="lblsearch" for="txtsearch">search</label>
				<input id="txtsearch" type="text" name="search" />
				<input id="btnsearch" type="submit" value="search" />
			</form>
		</div>
	</section>
	<?php 
	if(view() == "frontpage") {
		require "home.php";
	} else {
		require "polls.php";
	} ?>
	<div class="sep" style="margin-top: -10px"></div>
</body>