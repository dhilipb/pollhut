
<?
/*
 * subcat.php: Displays the categories list
 */
 
$qry_cat = select("*", "view_categories_parent", "where p_alias = '".get("cat")."'");
$alias = "c_alias";
$name = "c_name";
$title = "Sub Categories";

if (rows($qry_cat) == 0) {
	$qry_cat = select("DISTINCT p_alias, p_name", "view_categories_parent");
	$sub = false;
	$alias = "p_alias";
	$name = "p_name";
	$title = "Categories";
}
?>
	<div class="module">
	<h1><?=$title?></h1>
	<ul style="margin-bottom: 15px;">
	<?php
	
while ($row_cat = assoc($qry_cat)) {
	?>
	<li style="margin: 0 0 5px 10px;">
		<a href="<?=linkify("cat", $row_cat[$alias])?>" class="fs13">
			<?=ucwords($row_cat[$name])?>
		</a>
	</li>
<? } 

?>
</ul>
</div>