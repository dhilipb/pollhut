<?php
require_once("../includes/classes/post.php");


if (ispostset("post_id")) {
    $post_id = post("post_id");
    $user = user();
    $user_id = $user->id;
	
	if (empty($post_id))
		die("INVALID");
	
    $condSql = "WHERE user_id = '$user_id' AND post_id = '$post_id'";

    $qry = select("id", "tbl_user_favorites", $condSql) or die(mysql_error());
    if (rows($qry) > 0) {
        delete("tbl_user_favorites", $condSql);
    } else {
        insert("tbl_user_favorites", array("user_id" => $user_id,
                                            "post_id" => $post_id));
    }
}
?>