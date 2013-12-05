<?php

session_start();
require_once ("../includes/classes/post.php");

if (ispostset("post_id") && ispostset("vote")) {
	$post_id = post("post_id");
	$vote = post("vote") == "like" ? "1" : "0";
	$user =  user() -> id;
	$ip = $_SERVER['REMOTE_ADDR'];
	
	if ($user == 0)
		die("You need to login to like/dislike this post");

	$userSql = "AND (user_id = '$user' OR ip = '$ip')";

	if ($post_id == 0 && (!($val == 0 || $val == 1)))
		die("There has been some error in adding your vote. 'post=$post_id'");

	$qry = select("vote", "tbl_posts_likes", "WHERE post_id = '$post_id' 
                    		$userSql");

	if (!rows($qry)) {
		// Add
		insert("tbl_posts_likes", array("post_id" => $post_id, "vote" => $vote, "user_id" => $user, "ip" => $ip));

	} else {
		$row = assoc($qry);
		if ($vote == $row["vote"]) {
			// Delete
			delete("tbl_posts_likes", "WHERE (post_id = '$post_id' 
                                       AND vote = '$vote'
                                       $userSql);");
		} else {
			update("tbl_posts_likes", array("vote" => $vote), "WHERE post_id = '$post_id' $userSql");
		}
	}

	if (ispostset("nochart")) {
		die("SUCCESS");
	} else {
		$post = new Post();
		$post -> db($post_id);

		require_once ("../includes/chart/get.php");
		drawLikesChart($post);
	}
} else {
	die("Invalid Access");
}
?>