<?php

require_once("../includes/classes/post.php");
if (!user()->loggedin)
	die("You need to login to do that.");

if (ispostset("option_id") && ispostset("post_id")) {
	$post = new Post();
	$post->db(post("post_id"));
	$optionqry = select("post_id", "tbl_options", "WHERE id = '".post('option_id')."'");
	$option = assoc($optionqry);
	
	if ($post->user_id == user()->id && $option["post_id"] == $post->id) {
		
		$id = array("option_id" => post('option_id'));
		delete("tbl_votes", $id);
		delete("tbl_comments", $id);
		delete("tbl_options", array('id' => post('option_id')));
		die("SUCCESS");
	} else {
		die("You are not the owner of this post. You cannot delete this post.");
	}
}
?>