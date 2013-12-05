<?php
ini_set("display_errors", 1);
require("../includes/functions.php");
require("../includes/defines.php");
require("../includes/database.php");
require("../includes/classes/user.php");

session_start();

$user = user();
$content = "";

if (ispostset("loggedin")) {
    if ($user->loggedin)
        die ("TRUE");
    else die("FALSE");
	
} else if (ispostset("tag")) {
    $allowed = array("add", "delete");
	if (in_array(post("tag"), $allowed))
		$content = "tag/" . post("tag") . ".php";
    
} else if (ispostset("comments")) {
    $allowed = array("like", "list");
	if (in_array(post("comments"), $allowed))
		$content = "comments/" . post("comments") . ".php";

} else if (ispostset("option")) {
	$allowed = array("add", "delete");
	if (in_array(post("option"), $allowed))
		$content = "option/" . post("option") . ".php";
	    
} else if (ispostset("post")) {
    $allowed = array("favorite", "like", "vote");
	if (in_array(post("post"), $allowed))
		$content = "post/" . post("post") . ".php";
		
} else if (ispostset("hover")) {
	$allowed = array("option");
	if (in_array(post("hover"), $allowed))
		$content = "hover/" . post("hover") . ".php";
} else if (isgetset("feedback")) {
	$content = "feedback.html";		
} else if (ispostset("feedback")) {
	// Send feedback
	if (mail("info@pollhut.com", "Feedback", $_POST["feedback"] . "\r\n" . (user()->loggedin ? "User: " . user()->username : null), "From: info@pollhut.com")) {
		die("Thank you for your feedback.");
	} else {
		die("Your feedback could not be sent. Please try again later");
	}
} else if (ispostset("wiki")) {
	// Get wiki information for new post description
	$content = "wiki.php";
} else if (ispostset("gender")) {
	// Store the user's gender
	if (empty($user->gender)) {
		update("tbl_users", array("gender"=>post("gender")), "WHERE id = '".$user->id."'");
		$user->db();	
	}
	die();
} else if (ispostset("location") || isgetset("location")) {
	session_start();
    // Getting the voter's location
	require("../includes/lib/ip2locationlite.class.php");
	$ipLite = new ip2location_lite;
	$ipLite->setKey('1d407f41c4ae0ff7b214341600083ca0bd80e1e9c0372667ab43fb4604a4f09e');
    $locations = $ipLite->getCity($_SERVER["REMOTE_ADDR"]);
    $user->location($locations["latitude"], $locations["longitude"]);
	die($user->latitude. ", ".$user->longitude);
	
} else if (isgetset('login')) {
	echo "<div class='black_bg'><div class='popup' style='width: 300px;'><h1>Login</h1>";
	require "../includes/modules/content/login.php";
	echo "</div></div>";
	die();
}

if (empty($content))
	die("ERROR");
else
	require_once($content);
?>