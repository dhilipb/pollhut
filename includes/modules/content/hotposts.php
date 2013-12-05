<ul class="miniposts">
<?php
    $qry_hotposts = select("*", "view_posts_popular_nodate", "LIMIT 0, 5");
    while ($row = assoc($qry_hotposts)) {
    	$post = new Post();
		$post->db_assoc($row);
    	if (empty($post->view_public) && $post->username != user()->username)
			continue;    
    ?>
        <li class="clear-after">
	        <a style="font-size: 13px" href="<?=linkify("post", $post)?>"><?=$post->title?></a>
	        <ul class="post-info">
	        	<li>
	        		<strong>By</strong>
	        		<a href="<?=linkify("u", $post->username)?>"><?=$post->row["username"]?></a>
	        	</li>
	        	<li>
	        		<strong>Views</strong> <?=$post->views?>
	        	</li>
	        	<li>
	        		<strong>Votes</strong> <?=$post->votes?>
	        	</li>
	        </ul>
        </li>
    <? }
?>
</ul>