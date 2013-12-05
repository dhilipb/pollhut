<?php
    $content = "";
    
    switch(get("comments")) {
        case "add":
            require_once("includes/comments/add.php");
            break;
        default:
			if (isgetset("post"))
				require_once("includes/post/view.php");
			else
            	throw new Exception("Invalid Comment Task");
    }
?>