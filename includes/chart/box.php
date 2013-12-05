<h1>
	<a href="<?=linkify("post",$post)?>">
		<?=$post->title;?>
	</a>
</h1>
<div class="chart">
  <?php
    require_once ("includes/chart/get.php");
    drawPostChart($post, EMBED_SHOW);
  ?>
</div>