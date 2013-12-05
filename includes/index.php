<?php
	$content = "";
	
	if(isgetset("post"))
		$content = "includes/post/index.php";
	else if(isgetset("user") || isgetset("u"))
		$content = "includes/user/index.php";
	else if(isgetset("account"))
		$content = "includes/user/account.php";
	else if(isgetset("search") || isgetset("tags"))
		$content = "includes/search/search.php";
	else if(isgetset("page"))
		$content = "includes/page/index.php";
	else if(isgetset("comments"))
		$content = "includes/comments/index.php";
	else if(isgetset("cat") || isgetset("list"))
		$content = "includes/post/list.php";
	//else if(view() == "frontpage")
		//$content = "includes/layout/home.php";
	
	if(empty($content))
		throw new Exception("Unidentified Content");
	else
		require_once ($content);
?>