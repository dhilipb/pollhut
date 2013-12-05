<?php 

$pageqry = select("content, title", "tbl_pages", "WHERE alias = '".get("page")."'");

if (rows($pageqry) == 0) {
	throw new Exception("No page found");
} else {
	$page = assoc($pageqry);
	$this->title = $page["title"];
	
	if (substr($page["content"], 0, 2) == "?>") {
		eval($page["content"]);
	} else {
		echo $page["content"];
	}
	
}
	
?>