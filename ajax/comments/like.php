<?php
// COMMENTS LIKES


if (!$user->loggedin)
    die("Please login to like a comment.");

$comment_id = post("comment_id");

if (empty($comment_id))
    die("Invalid Access");
    
$check_query = select("id", "tbl_comments_likes", "WHERE user_id = '".$user->id."' 
                                            AND comment_id = '$comment_id' 
                                            AND vote = '1'");

if (rows($check_query) > 0)
	delete("tbl_comments_likes", array("user_id" => $user->id, 
                                      "comment_id" => $comment_id, 
                                      "vote" => 1));
else
	insert("tbl_comments_likes", array("user_id" => $user->id, 
                                      "comment_id" => $comment_id, 
                                      "vote" => 1));
echo "SUCCESS";
?>