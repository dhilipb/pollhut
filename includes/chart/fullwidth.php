<?php
	$postlink = linkify("post", $post);
?>
<div class="chart-fw" <?=$style?>>
	<div class="chart">
		<? require_once ("includes/chart/get.php"); drawPostChart($post, 3); ?>
	</div>
	<div class="chart-info">
		<?php 
		// Post actions
		if (user()->loggedin) { ?>
		    <ul class="post_actions">
		    	<input type="hidden" name="post" value="<?=$post->id?>" />
		    <?php if ($post->username == user()->username || user()->admin) { ?>
		        <li class="deletepost">
		        	<a href="javascript:void(0)" data-delete="<?=$post->id?>" title="Delete Post"></a>
		        </li>
		    <? } ?>
		      	<li class="dislikepost <?=$post->disliked?"active":null?>">
		      		<a href="javascript:void(0)" title="Dislike Post"></a>
		      	</li>
		      	<li class="likepost <?=$post->liked?"active":null?>">
		      		<a href="javascript:void(0)" title="Like Post"></a>
		      	</li>
		      	<li class="favoritepost <?=$post->favorite?"active":null?>">
		      		<a href="javascript:void(0)" title="<?=$post->favorite?"Unf":"F"?>avourite Post"></a>		
		      	</li>
		    </ul>
		<? } ?>
		<h2>
			<a href="<?=$postlink?>">
				<?=$post->title?>
			</a>
		</h2>
		<div class="post-desc">
			<?php
				$trimdesc = trim($post->description);
				echo "<a href=\"$postlink\">";
				if (empty($trimdesc))
					echo "No Description";
				else if (strlen(strip_tags($post->description)) > DESC_LIMIT) 
					echo substr(strip_tags($post->description), 0, DESC_LIMIT);
				else 
					echo strip_tags($post->description);
				echo "</a>... <a class=\"viewlink\" href=\"$postlink\">View Poll</a>";
			?>
		</div>
		<ul class="post-info">
			<li><strong>Posted</strong>
				<a href="<?=$postlink?>">
					<?=ucwords(time_since($post->row["timestamp"]))?>
				</a>
			</li>
			<li><strong>In</strong>
				<a href="<?=linkify("cat", $post->row["cat_parent_alias"])?>">
					<?=$post->row["cat_parent_name"]?></a>
				 <?=BREADCRUMB?>
				<a href="<?=linkify("cat", $post->row["cat_alias"])?>">
					<?=$post->row["cat_name"]?>
				</a>
			</li>
			<li><strong>By</strong>
				<a href="<?=linkify("u", $post->username)?>">
					<?=$post->username?>
				</a>
			</li>
		</ul>
		<div class="clear"></div>
	</div>
</div>
