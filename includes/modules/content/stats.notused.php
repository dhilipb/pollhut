
<div class="chart" style="width: 100%; margin: 0; border: none; padding: 0;">
	<?php 
		$guests_votes = mysql_fetch_assoc(mysql_query("SELECT SUM(vote) AS votes FROM view_stats_post WHERE user = '0' AND post_id = '$post_id'", $conn)) or die(mysql_error());
		$users_votes = mysql_fetch_assoc(mysql_query("SELECT SUM(vote) AS votes FROM view_stats_post WHERE user != '0' AND post_id = '$post_id'", $conn)) or die(mysql_error());
		
		echo (empty($guests_votes["votes"]) ? "0" : $guests_votes["votes"]) . " guests voted";
		echo "<br />";		
		echo (empty($users_votes["votes"]) ? "0" : $users_votes["votes"]) . " users voted";
		
	?>
</div>