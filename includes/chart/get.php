<?php
    /*
    * chart/get.php: Retrieve a chart for a given post
    */
  
    function drawPostChart($post, $limit = INFINITE) {
        
        echo '<input type="hidden" name="post" value="'.$post->id.'" />';
        echo '<input type="hidden" name="limit" value="'.$limit.'" />';
        
        $highest = -1;
        $graph_counter = 1;
        $owner = $post->user_id == user()->id;
		$options = $post->options();
		
        foreach($options as $option) {
            if ($graph_counter > $limit) {
                echo "<a href=" . linkify("post", $post->id)." class=\"chart_more\">";
                echo "<div>" . (count($post->options) - $limit) . " more..</div>";
                echo "</a>";
                break;
            } else
                $graph_counter++;
                
            $votes = $option->votes;
            $voted = $option->voted;
            if ($highest == -1) {
                $highest = $votes;
                echo '<input type="hidden" name="highest" value="'.$highest.'" />';
            }
            
            // width of the chart item
            $width = $votes == $highest ? 100 : $votes/$highest*100;
            
            // class "leading" is added when the current chart item is the highest
            $leading = $votes == $highest && $highest != 0 ? "leading" : "";
            $percent = 0;
            if ($post->totalvotes != 0) 
            	$percent = round(($votes/$post->totalvotes)*100);
        ?>  
			<div class="chart-item <?=$leading?>">
				<input type="hidden" name="opt" value="<?=$option->id?>" />
				<input type="hidden" name="votes" value="<?=$votes?>" />
				<? //if (isgetset("post") && $owner && false) echo '<div class="delete"></div>';?>
				<div class="colour" style="width: <?=$width?>%"></div>
				<div class="text">
					<label class="chartcheck <?=$voted ? "checked" : null?>">
						<input type="checkbox" <?=$voted ? "checked=\"checked\"" : null?>/>
					</label>
					<span class="value" percent="<?=$percent?>%" value='<?=$votes?>'>
						<?=$percent?>%
					</span>
					<div class="label">
						<span><?=$option->name?></span>
					</div>
				</div>
			</div>
        <?php
        }
        
        if ($limit == INFINITE && $post->newoptions) {
        	?>
        		<form id="opt_add">
        			<input type="hidden" name="post" value="<?=$post->id?>" />
        			<input type="text" name="option" value="Add your own.."/>	
        		</form>
        	<?
        }
    }

    function drawLikesChart($post) {
    
        $qry = select("SUM(vote) AS likes, COUNT(vote) - SUM(vote) AS dislikes",
                    "tbl_posts_likes", "WHERE post_id = '".$post->id."' GROUP BY post_id");
        
        $row = assoc($qry);
        $likes = empty($row["likes"]) ? 0 : $row["likes"];
        $dislikes = empty($row["dislikes"]) ? 0 : $row["dislikes"];
        $highest = max($likes, $dislikes);
        
        $voted = user()->id != 0 ? check_if_liked($post->id) : -1;
        
        $l_leading = $likes == $highest && $highest != 0 ? "leading" : "";
        $d_leading = $dislikes == $highest && $highest != 0 ? "leading" : "";
        
        ?>
        <div class="chart-item <?=$l_leading?> like">
            <input type="hidden" name="opt" value="1" />
            <div class="colour" style="width: <?=$likes/$highest*100?>%"></div>
            <div class="text">
            	<label class="chartcheck <?=$voted==1?"checked":null?>">
            		<input type="checkbox" <?=$voted==1?"checked":null?>/>
            	</label>
            	<div class="label">Likes - <span class="vote"><?=$likes?></span></div>
            </div>
        </div>
        <div class="chart-item <?=$d_leading?> dislike">
            <input type="hidden" name="opt" value="0" />
            <div class="colour" style="width: <?=$dislikes/$highest*100?>%"></div>
            <div class="text">
            	<label class="chartcheck <?=$voted==0?"checked":null?>">
            		<input type="checkbox" <?=$voted==0?"checked":null?>/>
            	</label>
            	<div class="label">Dislikes - <span class="vote"><?=$dislikes?></span></div>
            </div>
        </div>
    <? } 
    
	/*
	 * $data = array("Name"=>Value)
	 */
    function drawArrayChart($data, $sort = true) {
    	$total = array_sum($data);
		if ($sort) arsort($data);
		$highest = reset($data);
		
		foreach($data as $name=>$value) {
			$percent = round(($value/$highest)*100);
			$percent_display = round(($value/$total)*100);
			if (!isset($first)) $first = "leading"; else $first = "";
			?>
				<div class="chart-item <?=$first?>" style="cursor: default;">
		            <div class="colour" style="width: <?=$percent?>%"></div>
					<div class="text">
						<span class="value" <?=$percent<100?"style=\"color: black\"":null?>" 
								percent="<?=$percent_display?>%" value="<?=$value?>">
							<?=$percent_display?>%
						</span>
						<div class="label" style="left: 10px">
							<span><?=$name?></span>
						</div>
					</div>
		        </div>
			<?php
		}
    }
    
    
    ?>