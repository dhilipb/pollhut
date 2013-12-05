<?php
	$post_id = post("post_id");
	$user_id = $user->id;
	$name = post("name");
	
	$post_qry = select("id", "tbl_posts", "WHERE id = '$post_id' AND user_id = '$user_id'");
	if (rows($post_qry))
		die(delete("tbl_tags", "WHERE post_id = '$post_id' AND name = '$name'") ? "SUCCESS" : mysql_error());
	else
		die("You are not the creator of this post");
?>