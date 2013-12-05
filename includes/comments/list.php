<div align="right" class="fs13" style="margin: 15px 0; border: solid #ccc; border-width: 1px 0">
	<? if ($this->mobile) { ?>
	<div class="sort-box" style="border: none; text-align: left;">
		<strong>Comment on this option</strong>
	</div>
	<? } else { ?>
	<div class="sort-box" style="border: none">
		<strong style="float: left;">Comment on this option</strong>
		<span id="searchoption">
				Search "<?=$option->name?>" on 
				<a href="http://www.google.com/search?q=<?=$option->name?>" target="_blank">Google</a> <?=BULLET?> 
				<a href="http://en.wikipedia.org/w/index.php?title=Special%3ASearch&search=<?=$option->name?>" target="_blank">Wikipedia</a>
		</span> |
		<?php sort_box(array("recent", "likes", "replies")); ?>
	</div>
	<? } ?>
	
	<div id="comment-box">
		<form action="?comments=add" method="post" class="fs13">
			<input type="hidden" name="redirect" value="<?=PATH?>" />
			<input type="hidden" name="option_id" value="<?=$option->id?>" />
			<div class="placeholder-text" style="text-align: left;">
				<? if (user()->loggedin) { ?>
					<label for="comment-input">Write your comment on "<?=$option->name?>"</label>
				<? } else { ?>
					<label for="comment-input">Please login to write your comment on "<?=$option->name?>"</label>
				<? } ?>
				<textarea name="comment" id="comment-input" class="text-input"></textarea>
			</div>

			<div align="right">
				<input type="submit" value="Comment" data-submit="Posting.." style="margin-top: 5px;" class="blue button" />
			</div>
		</form>
	</div>
</div>

<?php
/*
 * comments/list.php: List all the comments for a given post id
 * @param: POST: id
 */
$counter = 1;
$sort = get("sort");
if (isgetset("sort")) {
    $sort = ($sort === "recent" || $sort === "replies") ? $sort = "id" : $sort;
} else {
    $sort = "id";
}

$qry_comments = select("*", "view_comments", 
                        "WHERE post_id = '{$post->id}' AND opt_id = '{$option->id}'
                         AND comment_id = '0' ORDER BY $sort DESC") or die(mysql_error()); // comment_id = 0 (no replies)

if (rows($qry_comments) == 0) {
    echo "<div id=\"no-comments\">Be the first to comment on {$option->name}</div>";
} else {?>
    <div id="comment">
    <ul>
        <?    // COMMENTS
            while ($row_comments = assoc($qry_comments)) {
                $comment_id = $row_comments["id"];
        ?>
        <li>
            <div class="comment">
                <?=htmlentities(stripslashes($row_comments["comment"]))?>
                <div class="comment-info">
                    <div><span class="like"><?=intval($row_comments["likes"])?></span> <?=pluralize($row_comments["likes"], "like")?> | <?=like_comment($row_comments["id"])?></div>
                    <div><?=time_since($row_comments["timestamp"])?></div>
                    <div>by <a href="<?=linkify("u", $row_comments["username"])?>"><?=$row_comments["username"]?></a></div>
                    <div><a href='javascript:void(0)' data-id="<?=$comment_id?>" class="replylink">Reply to this message</a></div>
                </div>
            </div>
            <?php
                // REPLIES
                $qry_reply = select("*", "view_comments", 
                                    "WHERE comment_id = '$comment_id'");
                while ($row_reply = assoc($qry_reply)) {
            ?>
                <div class="reply">
                    <span class="info">
                        by <a href="<?=linkify('u', $row_reply["username"])?>"><?=$row_reply["username"]?></a> - <?=time_since($row_reply["timestamp"])?><br />
                        <a href='javascript:void(0)' data-id="<?=$comment_id?>" class="replylink">Reply</a> - 
                        <span class="like"><?=intval($row_reply["likes"])?></span> <?=pluralize($row_reply["likes"], "like")?> | <?=like_comment($row_reply["id"])?>
                    </span>
                    
                    <?=htmlentities(stripslashes($row_reply["comment"]))?>
                </div>
            <? } ?>
            <div id="replybox-<?=$comment_id?>" class="reply-box reply">                
                <form action="?comments=add" method="post">
					<input type="hidden" name="redirect" value="<?=PATH?>" />	
                    <input type="hidden" name="comment_id" value="<?=$comment_id?>" />
                    <input type="hidden" name="option_id" value="<?=$option->id?>">
                    <div class="placeholder-text">
                        <label for="replytext-<?=$comment_id?>">Your Reply</label>
                        <textarea class="text-input" name="comment" id="replytext-<?=$comment_id?>"></textarea>
                    </div>
                    <div style="text-align: right; margin-top: 5px;">
                        <input type="submit" value="Post Reply" data-submit="Posting.." class="blue button" />
                    </div>
                </form>
            </div>
        </li>
        <? } ?>
    </ul>
    </div>
<? }

function like_comment($id) {
    $likes = select("id", "tbl_comments_likes", 
    				"WHERE user_id = '".user()->id."' AND comment_id = '$id'") or die(mysql_error());
    if (rows($likes)) {
        return "<a href=\"#like\" data-id=\"$id\">Unlike</a>";
    } else {
        return "<a href=\"#like\" data-id=\"$id\">Like</a>";
    }
}