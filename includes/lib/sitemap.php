<?php
	function sitemap($url) {

?>
<url>
	<loc>
		<?=SITEURL . $url?>
	</loc>
	<changefreq>
		daily
	</changefreq>
	<priority>
		1.00
	</priority>
</url>
<?php
}
header("Content-Type: text/xml;charset=iso-8859-1");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
	<?php
	require_once ("includes/defines.php");
	require_once ("includes/database.php");
	require_once ("includes/functions.php");

	$qry = select("id, title", "tbl_posts", " ");
	while ($row = assoc($qry)) {
		sitemap(linkify("post", $row["id"] . "_" . $row["title"]));
	}

	$qry = select("alias", "tbl_categories", " ");
	while ($row = assoc($qry)) {
		sitemap(linkify("cat", $row["alias"]));
	}

	mysql_close();
?>
</urlset>