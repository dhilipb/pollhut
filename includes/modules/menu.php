<a href="#" id="cat-dropdown"> Categories </a>
<ul class="tabs">
	<li <?=!isgetset("cat") && view() === "frontpage" ? ' class="active"' : null?>>
		<a href="<?=linkify("cat", "")?>">All</a>
	</li>
	<?php
	$qry_cat = select("alias, name", "tbl_categories", "WHERE cat_id = '0'");
	while ($row_cat = assoc($qry_cat)) {
		$active = get("cat") == $row_cat["alias"] ? ' class="active"' : null;
		echo "<li{$active}><a href=\"" . linkify("cat", $row_cat["alias"]) . "\">" . ucwords($row_cat["name"]) . "</a></li>";
	}
	?>
	<li></li>
</ul>