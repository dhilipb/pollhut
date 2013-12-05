<?php
/*
 * posts/list.php : Displays a list of posts with charts
 */

define("DESC_LIMIT", view() == "frontpage" || $this->mobile ? 100 : 300); // description character limit
    
// Setting Title if cat
if (isgetset("cat")) {
	$cat = select("*", "view_categories_parent", "WHERE c_alias = '" . get("cat") . "' 
													OR p_alias ='" . get("cat") . "'");
	$r = assoc($cat);
	if (rows($cat)) {
		if ($r["c_alias"] == get("cat")) {
			$title = $r["c_name"];
			//$breadcrumb = '<div class="fs13"><a href="">' . $r["p_name"] . '</a> > <a href="">' . $r["c_name"] . "</a></div>";
		} else if ($r["p_alias"] == get("cat")) {
			$title = $r["p_name"];
		}
		
		$this -> title = $title;
		$this -> toolslist = array("Popular" => linkify("list_cat", ""), 
									"Recent" => linkify("list_cat", "recent"), 
									"Views" => linkify("list_cat", "views"), 
									"Votes" => linkify("list_cat", "votes"));
	}
	

} else if (isgetset("list")) {
	$title = get("list");
	$title = $title == "views" ? "Top Viewed" : ($title == "votes" ? "Top Voted" : ucwords($title));
	$this -> title = $title . " Polls";
	$this -> toolslist = array("Popular" => linkify("list", "popular"), 
								"Recent" => linkify("list", "recent"), 
								"Views" => linkify("list", "views"), 
								"Votes" => linkify("list", "votes"));
}

// Get the tabs
$tab = isgetset("sort") ? get("sort") : "popular";
$list = isgetset("list") ? get("list") : (ispostset("list") ? post("list") : "popular");

// Check if tab is a valid tab
if (!in_array($tab, $this -> tabslist) || !in_array($list, $this -> tabslist)) {
	throw new Exception("Invalid Tab");
}
$tab = $list;

$reqd_items = ispostset("items") ? post("items") : POLLS_PER_PAGE;
$fullwidth = TRUE;

if (view() == "fav") {
	$tab = $tab == "recent" ? "id" : ($tab == "popular" ? "votes" : $tab);
	$qry_post = select("*", "view_user_favorites", "WHERE fav_user_id = '" .  user() -> id . "' 
	    ORDER BY $tab DESC");
} else if (isgetset("u")) {
	$public = get("show") == "private_posts" && $myProfile 
					? "0" : "1";
	
	$qry_post = select("*", "view_posts", "WHERE user_id = {$user->id} AND public = '$public'");
} else if (!isset($qry_post)) {
	
	// TODO REMOVE
	if ($tab == "popular" || $tab == "views")
		$tab .= "_nodate";

	// PAGING
	if (isgetset("pg"))
		$pg = "LIMIT " . (get("pg") - 1) * POLLS_PER_PAGE . ", " . (((get("pg") - 1) * POLLS_PER_PAGE) + POLLS_PER_PAGE);

	$user = isgetset("u") ? "WHERE username = '" . get("username") . "'" : NULL;
	$cat = isgetset("cat") ? "WHERE cat_alias = '" . get("cat") . "' OR cat_parent_alias = '" . get("cat") . "'" : NULL;
	$a = !empty($user) || !empty($cat) ? "and" : "WHERE";
	
	$qry_post = select("*", "view_posts_$tab", "$user $cat $a public = 1 $pg");
	$qry_post_page = select("id", "view_posts_$tab", "$user $cat $a public = 1");
	// query without page limitations
}

$items_count = 0; // used to count the number of elements
$total_items = rows($qry_post);
$hidden_items = 0;

echo '<div id="itemlist-wrapper'.(view() == "frontpage" ? '-home" class="clear-after' : null).'">';
if ($total_items == 0)
	echo "<br /><strong><em>No polls found</em></strong>";
else
	require ("includes/post/list.fullwidth.php");
echo "</div>";

if (isset($qry_post_page)) {
	$pages_count = ceil((rows($qry_post_page) - $hidden_items) / POLLS_PER_PAGE);
	$page = empty($_GET["pg"]) ? 1 : intval(get("pg"));

	if ($pages_count > 1) {
		echo '<a id="pagination" href=' . linkify("pg", $page + 1) . '>next page</a>';
	}
}
?>