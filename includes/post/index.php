<?php
require_once ("includes/database.php");
$post_get = get("post");
$this->module = "right";

if ($post_get == "new" || $post_get == "delete") {
    if (user()->loggedin)
        require_once("includes/post/".$post_get.".php");
    else
        throw new Exception("LOGIN_FAILURE");
} else if (empty($post_get)) throw new Exception("No post ID found");
else {
    $post = new Post();
    $post->db($post_get);
    
    $this->title = $post->title;
    
    if (get("task") === "delete") {
        $post->delete();
        $this->title = "Delete Post";
        echo '<p>The post has been successfully deleted.</p>';
    } else if(get("task") == "edit") {
        $editpost = $post;  
        require_once("includes/post/new.php");
	} else if (!isgetset("task"))
        require_once("includes/post/view.php");
    else throw new Exception("Unknown Post Method");
}
?>