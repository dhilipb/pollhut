<?php
/*
 * config.php: Contains configuration for the website
 */

// All Global variables should be upppercase

define("SITENAME", "Chart27");
define("DB_NAME", "cosmoses_compareem");
define("DB_USER", "cosmoses");
define("DB_PASS", "Infit3ch!");

define("DB_HOST", "infitechsolutions.co.uk");

$MOD_LEFT = true;
$MOD_RIGHT = false;
$PAGEERROR = false;
$TABSLIST = array("popular", "recent", "views", "votes");
$FRONTPAGE = isset($_GET["list"]) || empty($_SERVER["argv"]);
$POSTVIEW = isset($_GET["post"]) && $_GET["post"] != "delete" && $_GET["post"] != "new";

if (!$FRONTPAGE && !$POSTVIEW) { $MOD_LEFT = false; $MOD_RIGHT = true;}


?>