<?php
require_once("includes/database.php");
require_once("includes/config.php");

$qry_post = mysql_query("SELECT * FROM view_posts WHERE id = '".FEATUREDID."'", $conn) or die(mysql_error());
$description_limit = 100;
while ($row_post = mysql_fetch_assoc($qry_post)) {
	$post_id = $row_post["id"];
	$user = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : "-1";
	$alreadyVoted = mysql_query("SELECT * FROM view_stats_post WHERE post_id = '$post_id' AND (user = '$user' OR ip = '".$_SERVER["REMOTE_ADDR"]."') AND vote = '1'", $conn) or die(mysql_error());
	$alreadyVoted = mysql_num_rows($alreadyVoted) > 0; 
?>		
<table id="list-item-<?=$post_id?>" class="list-item" width="38%" cellspacing="0" cellpadding=0 style="float: right;">
<tr>
	<td class="chart" width="30%">
		<?php 
		require_once("includes/chart/get.php");
		  
		if (drawPostChart($post_id, 3)) {
			echo "<div class=\"more\"><a href=\"index.php?post=view&id=$post_id\">more</a></div>";
		} ?>
	</td>
	<td class="itemdtls">
		<h2>
		<a href="?post=view&id=<?=$post_id?>"><?=$row_post["title"]?></a>
		<?php if ($alreadyVoted) {?>
			<img src="images/voted.png" alt="You have already voted in this poll" />
		<? } ?>
		</h2>
		<p class="description">
			<?=stripslashes(strlen($row_post["description"])>$description_limit ? substr(htmlentities($row_post["description"]), 0, $description_limit)."... <a href=\"index.php?post=view&id=$post_id\">more</a>" : htmlentities($row_post["description"]))?>
		</p>
	</td>
</tr>
</table>
<? } ?>