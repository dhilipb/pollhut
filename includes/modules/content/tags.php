<?php
$tagsqry = select("name", "tbl_tags", "WHERE post_id = '" . $post -> id . "'");
?>
<ul id="post-tags" class="clear-after">
	<?php
	while ($row = assoc($tagsqry)) {
		echo "<li>";
		if ($post -> user_id == user() -> id)
			echo "<a class=\"tagdel\" href=''>x</a>";
		echo "<a class=\"tagname\" href=\"" . linkify("tags", $row["name"]) . "\">" . $row["name"] . "</a>
		</li>";
	}
	?>
</ul>
<form id="tag_add">
	<input type="text" name="option" value="Add your own.."/>
</form>
