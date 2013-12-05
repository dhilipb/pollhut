<?php
// ADD VOTES
require_once ("../includes/classes/post.php");

if (ispostset("opt_id") && ispostset("post_id")) {
	$post_id = post("post_id");
	$opt_id = post("opt_id");
	$user =  user() -> id;
	$ip = $_SERVER['REMOTE_ADDR'];

	if (empty($post_id) || empty($opt_id))
		die("Invalid Vote");

	$userSql = "AND ((tbl_votes.user_id = '$user' AND tbl_votes.user_id > '0') 
    					OR (tbl_votes.user_id = '0' AND tbl_votes.ip = '$ip'))";

	if ($opt_id == 0)
		die("There has been some error in adding your vote.");

	// Getting the post
	$post = new Post();
	$post -> db($post_id);
	
	// deciding whether to insert vote or delete vote
	// occurs when a user clicks on a vote that he has voted already
	$addordel = select("id", "tbl_votes", "WHERE (option_id = '$opt_id') $userSql");
	$addordel = rows($addordel);
	if ($addordel === 0) {
		// Add
		session_start();
		$latitude =  user() -> latitude;
		$longitude = user() -> longitude;
		
		// checking the number of choices
		$votesMade = assoc(select("COUNT(tbl_votes.id) AS count", 
		"tbl_options INNER JOIN tbl_votes ON tbl_options.id = tbl_votes.option_id", 
		"WHERE tbl_options.post_id = '$post_id' $userSql 
			GROUP BY tbl_options.post_id"));
		
		if ($votesMade["count"] < $post -> choices || $post -> choices == -1)
			insert("tbl_votes", array("option_id" => $opt_id, "vote" => 1, "user_id" => $user, "ip" => $ip, "latitude" => $latitude, "longitude" => $longitude));
		else
			die("You have reached the max number of votes for this post.");
	} else {
		// Delete
		delete("tbl_votes", "WHERE (option_id = '$opt_id' $userSql);");

	}

	require_once ("../includes/chart/get.php");
	drawPostChart($post, post("limit"));

} else {
	die("Invalid Access");
}
?>