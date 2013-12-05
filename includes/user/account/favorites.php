<div class="sort-box" style='margin-bottom: 10px;'>
<?php
	$this->title = "Favourites";
	sort_box(array("votes", "views", "recent"));
?>
</div>
<?php 
	require("includes/post/list.php")
?>