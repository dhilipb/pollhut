<ul class="miniposts">
<?php
    $qry_hotposts = select("*", "view_posts_recent", "WHERE public = 1 LIMIT 0, 5");
    while ($row = assoc($qry_hotposts)) {
    	$post = new Post();
		$post->db_assoc($row);
    	if (empty($post->view_public) && $post->username != user()->username)
			continue;    
    ?>
        <li>
	        <a href="<?=linkify("post", $post)?>"><?=$post->title?></a>
	        <ul class="post-info clear-after">
	        	<li>
	        		<strong>By</strong>
	        		<a href="<?=linkify("u", $post->username)?>"><?=$post->row["username"]?></a>
	        	</li>
	        	<li>
	        		<strong>Views</strong> <?=(int)$post->views?>
	        	</li>
	        	<li>
	        		<strong>Votes</strong> <?=(int)$post->votes?>
	        	</li>
	        </ul>
        </li>
    <? }
?>
</ul>