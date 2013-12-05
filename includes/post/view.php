<?php
/*
 * posts/view.php: View post
 */

$this -> module = "left";
if (!isgetset("comments") && !isgetset("display"))
	update("tbl_posts", array("views" => "'views + 1"), "WHERE id = '" . $post -> id . "'");

// Creating meta tags for SEO
$this -> description = "";

if (!$this -> mobile) {

	// Insert all the option names in description
	// and keywords
	$options = $post -> options();
	foreach ($options as $opt) {
		$name = $opt -> name;
		$this -> description .= "$name vs ";
		$this -> keywords .= ", $name";
	}

	$tags = $post -> tags();
	foreach ($tags as $tag) {
		$this -> keywords .= ", $tag";
	}
	$this -> keywords = substr($this -> keywords, 2, strlen($this -> keywords));
	// remove the first comma

	$this -> description = substr($this -> description, 0, strlen($this -> description) - 4);
	// to remove ' vs '
	$this -> description .= "... " . minify_code(strip_tags($post -> description));

	$this -> toolslist = array();
	if (user() -> loggedin) {
		if ($post -> username ==   user() -> username ||   user() -> admin) {
			$toolslist["Edit"] = linkify("task", "edit");
			$toolslist["Delete"] = "<a href='javascript:void(0)' data-delete='{$post->id}'>Delete</a>";
		}

		$toolslist[($post -> favorite ? "Remove from" : "Add to") . " Favourites"] = "#favoritePost";

		if (get("display") == "stats") {
			$toolslist["Show Comments"] = linkify("display", "comments");
		} else {
			$toolslist["Show Statistics"] = linkify("display", "stats");
		}
		$this -> toolslist = $toolslist;
	}
}
?>
<link href="/style/post-view.css" type="text/css" rel="stylesheet" />
<div id="viewpost">
	<?php if ($this->mobile) {
		require_once "includes/modules/content/votes.php";
		echo "<hr style='margin: 10px 0'/>";
	} ?>
	<!-- Post description -->
	<div id="postdesc" style="margin: 0;">
		<?=close_tags(stripslashes($post -> description));?>
	</div>
	<div style="border-top: thin solid #CCC; margin-top: 20px; clear: both">
		<p class="fs13">
			by <a href="<?=linkify("u", $post->username)?>" class="strong fs13"> <?=$post->username
			?></a> - <strong class="fs13"><?=intval($post->row["comments"])
			?></strong> <?=pluralize($post->row["comments"], "comment")?> in total - <strong><?=intval($post->row["views"])
			?></strong> <?=pluralize($post->row["views"], "view")?> - <strong><?=intval($post->row["votes"])
			?></strong> <?=pluralize($post->row["votes"], "vote")?> -
			<strong>in</strong> <a class="fs12" href="<?=linkify("cat", $post->row["cat_parent_alias"])?>"> <?=$post->row["cat_parent_name"]
			?></a>
			<?=BREADCRUMB
			?>
			<a class="fs12" href="<?=linkify("cat", $post->row["cat_alias"])?>"> <?=$post->row["cat_name"]
			?></a> - <strong><?=$post->view_public ? "public" : "private"?></strong> post
		</p>
		<? $permalink = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];?>
	</div>
	<? if (!$this->mobile) { ?>
		<!-- Share -->
		<div style="margin-bottom: 20px;"> 
			<!-- Embed -->
			<div id='embed' class="fs12" style="display: inline; position: relative; top: -5px; padding-right: 10px">
				Embed:
				<input type="text"
				style="border: 1px solid #ccc; padding: 5px; width: 200px;"
				value='<iframe src="<?=SITEURL?>/embed/<?=$post->id?>?color=0&title=true&show=all" style="width: 300px; height: <?=count($post->options) * 30 + 150?>px;" frameborder="0" allowtransparency="yes" />' />
			</div>
			
			<!-- Twitter Button -->
			<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
			<a href="http://twitter.com/share" class="twitter-share-button" data-text="<?=$this->title . ".. Vote Now!! #poll"?>">Tweet</a>
			<!-- Google +1 Button -->
			<g:plusone size="MEDIUM"></g:plusone>
			<script type="text/javascript">
				(function() {
					var po = document.createElement('script');
					po.type = 'text/javascript';
					po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0];
					s.parentNode.insertBefore(po, s);
				})();
		
			</script>
			<!-- Facebook Like Button -->
			<iframe src="http://www.facebook.com/plugins/like.php?href=<?=urlencode(CURRENT_URL)?>&amp;layout=button_count&amp;show_faces=true&amp;width=100&amp;action=like&amp;font&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>
		</div>
	<? } ?>
<?

if (get("display") === "stats" && user()->loggedin) {
require_once ("includes/post/stats.php");
} else {
// COMMENTS
unset($first_opt);
?>
<div id="option-tab">
	<ul class="clear-after">
		<?php
		$first_opt = reset($post -> options());
		foreach ($post->options() as $option) {
			$active = "";
			$link = linkify("comments", $option -> id . "_" . $option -> name);
			$current_optid = !isgetset("comments") ? $first_opt -> id : substr(get("comments"), 0, strpos(get("comments"), "_"));

			if ($current_optid == $option -> id) {
				$current_option = $option;
				$active = ' class="active"';
			}
			$count = $option -> commentscount();
			echo "<li{$active}>
<a href=\"$link\">{$option->name} ({$count})</a>
</li>";
		}
		?>
	</ul>
</div>
<?php
$option = $current_option;
require_once ("includes/comments/list.php");
}
?>
</div>