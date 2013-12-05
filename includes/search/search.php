<?php
    $search = "";
    if (isgetset("search")) {
    	$search = get("search");
    	$this->title = "Search results for '$search'";
    } else if (isgetset("tags")) {
    	$search = get("tags");
    	$this->title = "Displaying results for tag '$search'";
    }
    
    if (empty($search)) {
        echo "Please enter a search term.";
    } else {
    	if (isgetset("search"))
			$qry_post = select("view_posts.*", "tbl_posts INNER JOIN view_posts ON (tbl_posts.id = view_posts.id)", 
							"WHERE MATCH(tbl_posts.title, tbl_posts.description) AGAINST ('$search' IN NATURAL LANGUAGE MODE)");
		else if (isgetset("tags"))
			$qry_post = select("view_posts.*", 
						"tbl_tags INNER JOIN view_posts ON (tbl_tags.post_id = view_posts.id)", 
						"WHERE tbl_tags.name = '".get("tags")."'");
        require_once("includes/post/list.php");
    }
?>