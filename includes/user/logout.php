<?php
//$this->title = "Logout";
$log_login = insert("tbl_log_login", array("user_id" => user()->id, "status" => "0"));
	
session_destroy();
$user = new User();
user($user);
header("Location: /?logout");

//echo "<p>You have been successfully logged out.</p>
//   <p>Hope to see you soon!</p>";
?>