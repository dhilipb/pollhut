<?php
if (!user()->loggedin)
    throw new Exception("Please login to post a comment.");

$option_id = post("option_id");
$user = user()->id;
$comment_id = post("comment_id") ? post("comment_id") : "0";
$comment = post("comment");
 
if (empty($option_id) || (empty($comment_id) && $comment_id != "0") || empty($comment))
    throw new Exception("Please enter some comments");

insert("tbl_comments", array("option_id" => $option_id, 
                            "user_id" => $user, 
                            "comment" => $comment, 
                            "comment_id" => $comment_id));
                            
$comment_id = select("MAX(id) AS id", "tbl_comments", " ");
$comment_id = $comment_id["id"];
header("Location: " . post("redirect"));
?>