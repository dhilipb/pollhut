<?php
ini_set("session.cookie_domain", ".pollhut.com");
ini_set('display_errors', '1');

// append new gets in url (?a=b) to old ones
$parse = parse_url($_SERVER["REQUEST_URI"]);
parse_str($parse["query"], $_GET2);
$_GET = array_merge($_GET, $_GET2);

// Sitemap
if (isset($_GET["sitemap"])) {
	require "includes/lib/sitemap.php";
	die();
}
			
session_start();

// temporary - to destroy all user session data
if (isset($_GET["destroy"])) {
	unset($_SESSION["user"]);
	session_destroy();

	die("Session destroyed");
}

require_once ("includes/classes/website.php");

$website = new Website();

echo '<!DOCTYPE html>';
echo '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">';
echo $website -> head;
echo $website -> body;
echo "</html>";

if ($conn)
	mysql_close($conn);
?>