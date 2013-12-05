<?php

require_once("../includes/classes/post.php");
if (!user()->loggedin)
	die("You need to login to do that.");

if (ispostset("post_id") && ispostset("name")) {
	$id = post("post_id");
	$option = post("name");
	
	if (empty($option))
		die("This field cannot be blank");
	
	$post = new Post();
	$post->db($id);

	$query = select("*", "tbl_options", "WHERE post_id = '$id' AND name = '$option'");
	if (!rows($query)) {
        insert("tbl_options", 
            array("post_id" => $id, 
                    "name" => $option,
                    "user_id" => $user->id));
		die("SUCCESS");
	} else {
		die("This option already exists");
	}
}
die("Invalid Access");
?>