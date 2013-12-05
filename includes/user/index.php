<?php
    $content = "";
    if (isgetset("u")) {
        $content = "includes/user/view.php";
    } else if (isgetset("user")) {
    	if (get("user") == "email")
            $content = "includes/user/sendmail.php";
		
		if (user()->loggedin) {
	        if (get("user") == "logout")
	            $content = "includes/user/logout.php";
			else
				header("Location: /");
		} else {
			if (get("user") == "login")
	            $content = "includes/user/login.php";
	        else if (get("user") == "register")
	            $content = "includes/user/register.php";	
	        else if (get("user") == "forgot")
	            $content = "includes/user/forgot.php";	
		}
	}
    
    if (empty($content)) {
        throw new Exception("Invalid User Task");
    } else
        require_once($content);
?>