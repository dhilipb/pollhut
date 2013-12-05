<?php
	require("../includes/classes/post.php");
	$post = new Post();
	$post->db(post("post_id"));
    require_once("../includes/comments/list.php");
?>