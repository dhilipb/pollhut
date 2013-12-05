<ul class="miniposts">
	<?php
		define("COMMENT_LEN", 200);
		$qry = select("comment, username, opt_id, opt_name, post_id, post_title", 
						"view_comments", 
						"WHERE public = 1 ORDER BY id DESC LIMIT 0, 5");
		
		while ($row = assoc($qry)) {
			$comment = (strlen($row["comment"]) > COMMENT_LEN ? substr($row["comment"], 0, COMMENT_LEN) . "..." : $row["comment"]);
			$opt_link = linkify("post", $row["post_id"] . "_" . $row["post_title"]) . "/" .
				linkify("comments", $row["opt_id"] . "_" . $row["opt_name"]);
	?>
	<li>
		<a href="<?=$opt_link?>"> <q><?=$comment
			?></q></a>
		- <span class="fs12">
			<a href="<?=linkify("u", $row["username"])?>">
				<?=$row["username"]?>
			</a></span>
		<ul class="post-info clear-after">
			<li>
				<strong>In</strong>
				<a href="<?=linkify("post", $row["post_id"] . "_" . $row["post_title"])?>"> <?=$row["post_title"]
				?></a> <?=BREADCRUMB?>
				<a href="<?=$opt_link?>"> <?=$row["opt_name"]
				?></a>
			</li>
		</ul>
	</li>
	<?
	}
	?>
</ul>