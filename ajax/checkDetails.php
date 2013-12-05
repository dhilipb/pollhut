<?php
require_once("../includes/database.php");
require_once("../includes/functions.php");

if (ispostset("checkLogin")) {
    if (!ispostset("email") || !ispostset("password") || !checkLogin(post("email"), post("password")))
        die("FALSE");
        
} else if (ispostset("checkUsername")) {
    if (post("checkUsername") == "" || !checkUsername(post("checkUsername")))
        die("FALSE");
        
} else if (ispostset("checkEmail")) {
    if (ispostset("checkEmail") == ""|| !checkEmail(post("checkEmail")))
        die("FALSE");
}

die ("TRUE");
?>