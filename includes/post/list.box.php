<table width="100%" cellpadding="0" cellspacing="10" id="itemlist">
  <?php
  $width = floor(100 / ITEMS_PER_ROW);
  while (($row_post = assoc($qry_post)) && $items_count < $reqd_items) {
    // Check if the poll is private, if so, is it created by the user logged in
    if (empty($row_post["public"]) && $row_post["username"] != user()->username) {
		$hidden_items++;
		continue;    
    }
    
	// Create post object
    $post = new Post();
    $post->db($row_post["id"]);
    
	// Create Chart
    if ($items_count % ITEMS_PER_ROW == 0) echo "<tr>";
      echo "<td class=\"post_wrapper\" width=\"$width%\">";
      require("includes/chart/box.php");
      echo "</td>";
    if (($items_count+1) % ITEMS_PER_ROW == 0) echo "</tr>";
      $items_count++; 
  
  } 
  
  // create empty columns
  for ($i = 0; $i < (ITEMS_PER_ROW - ($items_count % ITEMS_PER_ROW)); $i++) {
    echo "<td width=\"$width%\"></td>";
    echo $i == ((ITEMS_PER_ROW - ($items_count % ITEMS_PER_ROW)) - 1) ? "</tr>" : null; 
  } 
  ?>
</table>