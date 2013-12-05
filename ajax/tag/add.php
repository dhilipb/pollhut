<?php
if (user() -> loggedin) {
	$post_id = post("post_id");
	$name = post("name");

	$tagqry = select("id", "tbl_tags", "WHERE post_id = '$post_id' AND name = '$name'");

	if (rows($tagqry))
		die("The tag already exists");
	else {
		if (insert("tbl_tags", array("post_id" => $post_id, "name" => $name)))
			die("<li><a class=\"tagname\" href=\"".linkify("tags", $name)."\">$name</a></li>");
		else
			echo mysql_error();
	}
} else {
	die("You need to login to do that.");
}
?>