<?php
class User {
	public $id;
	public $ext_id;
	public $username;
	public $password;
	public $email;
	public $loggedin;
	public $gender;
	public $age;

	public $timestamp;
	public $views;
	public $lastloggedin;
	public $posts;
	public $comments;
	public $likes;
	public $votes;

	public $points;

	public $latitude;
	public $longitude;

	public $admin;

	function __construct() {
		$this -> id = "0";
		$this -> email = "guest@guest";
		$this -> loggedin = FALSE;
	}

	function location($lat, $lng) {
		$this -> latitude = $lat;
		$this -> longitude = $lng;
		user($this);
	}

	function login($username, $password) {
		if (empty($username) || empty($password))
			return false;

		$userQuery = select("*", "view_users", "WHERE username = '$username' AND password = '$password'");
		if (rows($userQuery) > 0) {
			$this -> db_assoc($userQuery);
			insert("tbl_log_login", array("user_id" => $this -> id, "status" => 1));
			$this -> loggedin = TRUE;
			user($this);
			return true;
		} else {
			return false;
		}
	}

	function loginsocial($site, $contents) {

		if ($site === "facebook") {
			$id = "fb" . $contents -> id;
			$contents = array("email" => $contents -> email, "username" => "fb_" . (empty($contents -> username) ? $id : $contents -> username), "ext_id" => $id, "fullname" => $contents -> first_name . " " . $contents -> last_name, "gender" => strtolower($contents -> gender));
		} else if ($site === "twitter") {
			$id = "tw" . $contents -> id;
			$contents = array("username" => "tw_" . $contents -> screen_name, "ext_id" => $id, "fullname" => $contents -> name);
		} else if ($site === "google") {
			$id = $contents["id"];
			$username = explode("@", $contents["contact/email"]);
			$contents = array("email" => $contents["contact/email"], "username" => "g_" . $username[0], "ext_id" => $id, "fullname" => $contents["namePerson/first"] . " " . $contents["namePerson/last"]);
		} else {
			throw new Exception("Invalid Site $site, $contents");
		}

		$userQuery = select("id", "view_users", "WHERE ext_id = '$id'");

		if (rows($userQuery)) {
			update("tbl_users", array("fullname" => $contents["fullname"]), "WHERE ext_id = '$id'");
		} else {
			insert("tbl_users", $contents);
		}
		$this -> ext_id = $id;
		$this -> db();
		//$this->points(POINTS_LOGIN);

		insert("tbl_log_login", array("user_id" => $this -> id, "status" => 1));

		$this -> loggedin = TRUE;
		user($this);
	}

	function db() {
		if (!empty($this -> ext_id))
			$userQuery = select("*", "view_users", "WHERE ext_id = '{$this->ext_id}'");
		// refresh class
		else if (!empty($this -> id))
			$userQuery = select("*", "view_users", "WHERE id = '{$this->id}'");
		else if (!empty($this -> username))
			$userQuery = select("*", "view_users", "WHERE username = '{$this->username}'");

		$this -> db_assoc($userQuery);

		//$this -> points();
		user($this);
	}

	function db_assoc($userQuery) {
		$row = assoc($userQuery);
		$this -> id = $row["id"];
		$this -> username = $row["username"];
		$this -> password = $row["password"];
		$this -> email = $row["email"];
		$this -> timestamp = $row["timestamp"];
		$this -> gender = $row["gender"];
		$this -> age = $row["age"];

		$this -> views = $row["views"];
		$this -> lastloggedin = $row["lastloggedin"];
		$this -> posts = $row["posts"];
		$this -> comments = $row["comments"];
		$this -> likes = $row["likes"];
		$this -> votes = $row["votes"];

		$this -> admin = $row["group_id"] == "0";
	}

	/*
	 * Calculate points
	 */
	function points($p = 0) {

		if ($p != 0) {
			update("tbl_users", array("points" => "'points + $p"), "WHERE username = '" . $this -> username . "'");
		}

		$userQuery = select("points", "tbl_users", "WHERE username = '" . $this -> username . "'");
		$row = assoc($userQuery);
		$dbPoints = $row["points"];

		$points = $dbPoints;
		$points += $this -> posts * POINTS_POST;
		$points += $this -> comments * POINTS_COMMENT;
		$points += $this -> likes * POINTS_LIKE;
		$points += $this -> votes * POINTS_VOTE;

		$this -> points = $points;
		user($this);
		return $points;
	}

	/*
	 * Register
	 */
	function register() {
		insert("tbl_users", array("username" => post("reg_username"), "email" => post("reg_email"), "password" => post("reg_password"), "age" => post("reg_age"), "gender" => post("reg_gender"), "group_id" => "1"));
		mail(post("reg_email"), "Registration at " . SITENAME, "Hello " . post("reg_username") . ", \n\n" . "Thank you for registering at " . SITENAME . ".\n\n" . "The password you registered with us is: " . post("reg_password") . "\n\n" . "We hope you enjoy your stay at " . SITENAME . "\n" . "Please leave your feedback\n\n" . "This is an automated email and hence please do not reply.", 'From: PollHut <info@pollhut.com>');
		return true;
	}

}
?>