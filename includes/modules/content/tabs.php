<ul class="tabs">
	<?php
	if (!isgetset("list") && empty($_GET))
		$_GET["list"] = "popular";
	foreach ($this->tabslist as $i => $value) {
		?>	<a href="<?=linkify("list", $value)?>">
				<li <?=$value == $_GET["list"] ? "class=\"active\"" : ""?> >
					<?=ucwords($value)?>
				</li>
			</a>
		<? } ?>
</ul>