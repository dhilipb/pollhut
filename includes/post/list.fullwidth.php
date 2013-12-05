<?php
	
	while (($row_post = assoc($qry_post)) && $items_count < $reqd_items) {
    	// Check if the poll is private, if so, is it created by the user logged in
    	if (empty($row_post["public"]) && $row_post["username"] != user()->username) {
			$hidden_items++;
			continue;    
    	}
	    
		// Create post object
	    $post = new Post();
	    if (user()->loggedin)
			$post->db_assoc_full($row_post);
		else
	    	$post->db_assoc($row_post);
		
		if ($this->mobile)
			require "includes/mobile/chart/fullwidth.php";
		else
			require "includes/chart/fullwidth.php";
		
		if (view() == "frontpage" && $items_count % 2 != 0 && $items_count != $total_items-1 && !$this->mobile) {
			echo "<hr style=\"clear: both\">";
		}
		$items_count++;
	}
?>