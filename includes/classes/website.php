<?php
require_once ("includes/defines.php");
require_once ("includes/database.php");
require_once ("includes/functions.php");
require_once ("includes/classes/user.php");
require_once ("includes/classes/post.php");

class Website {
	private $module;
	private $tabslist;

	private $title;
	public $head;
	public $body;
	private $page_content;
	private $error;

	private $pointsAdded;

	// Meta tags
	private $description;
	private $keywords;

	// Tools list
	private $toolslist;

	private $mobile;

	function __construct() {
		require ("includes/defines.php");

		$this -> tabslist = array("popular", "recent", "views", "votes");
		$this -> module = "right";

		if (!isset($_SESSION["user"])) {
			$user = new User();
			user($user);
		}

		$this -> mobile = false;
		
		if (isgetset("embed")) {
			require "includes/lib/embed.php";
		} else {
			// create body
			ob_start();
			require_once("includes/".($this -> mobile ? "mobile/" : null)."layout/index.php");
			$this -> body = ob_get_contents();
			ob_end_clean();
	
			// create head
			ob_start();
			require_once ("includes/".($this -> mobile ? "mobile/" : null)."layout/header.php");
			$this -> head = ob_get_contents();
			ob_end_clean();
		}
	}

	function __destruct() {
		if (view() != "logout")
			session_start();
	}

}
?>