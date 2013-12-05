<?php
class Option {
	public $id;
	public $name;
	public $votes;
	public $voted;
	public $comments_count;
	
	function __construct($id, $name, $votes) {
		$this -> id = $id;
		$this -> name = $name;
		$this -> votes = $votes;
		$this -> comments_count = 0;
		
		$already_voted = select("id", "tbl_votes", "WHERE option_id = '$id' 
                                    AND ((user_id = '" .   user() -> id . "' AND user_id > '0') 
                                    	OR (user_id = '0' AND ip = '" . $_SERVER["REMOTE_ADDR"] . "')) 
                                    AND vote = '1'");
		$this -> voted = rows($already_voted) > 0;
	}
	
	function commentscount() {
		$id = $this -> id;
		$count = assoc(select("count", "view_optionscomments", "WHERE id = '$id'"));
		return intval($count["count"]);
	}

}

class Post {
	public $id;
	public $timestamp;

	public $title;
	public $description;
	public $options;
	public $totalvotes;
	public $tags;
	public $cat;
	public $user_id;
	public $username;

	public $favorite;
	public $liked;
	public $disliked;

	public $choices;
	public $newoptions;
	public $view_public;

	public $views;
	public $votes;

	public $row;

	function __construct() {
		$this -> options = array();
	}

	function db($id) {
		// Post
		$query = select("*", "view_posts", "WHERE id = '$id'");

		if (!rows($query))
			throw new Exception("Cannot find post: $id");

		$this -> db_assoc_full(assoc($query));
		// }
		session_start();
		$_SESSION['post' . $id] = serialize($this);
		return true;
	}

	/*
	 * Refresh the whole post from db
	 */
	function db_assoc_full($row) {
		$this->db_assoc($row);

		$user = user();
		if ($user -> loggedin) {
			// Favorite
			$favQry = select("id", "tbl_user_favorites", "WHERE user_id = '" . $user -> id . "' AND post_id = '" . $this -> id . "'");
			if (rows($favQry) > 0)
				$this -> favorite = true;

			// Likes and dislikes
			$liked = select("vote", "tbl_posts_likes", "WHERE user_id = '" . $user -> id . "' AND post_id = '" . $this -> id . "'");
			if (rows($liked) > 0) {
				$liked = assoc($liked);

				if ($liked["vote"] == "1")
					$this -> liked = true;
				else if ($liked["vote"] == "0")
					$this -> disliked = true;
			}
		}
	}

	/*
	 * Only retrieve main fields (for faster loading)
	 */
	function db_assoc($row) {

		$this -> id = $row["id"];
		$this -> title = unesc($row["title"]);
		$this -> description = unesc($row["description"]);
		$this -> cat = unesc($row["cat"]);

		$this -> user_id = $row["user_id"];
		$this -> username = $row["username"];

		$this -> choices = $row["choices"];
		$this -> newoptions = $row["newoptions"] == 1 ? TRUE : FALSE;
		$this -> view_public = $row["public"] == 1 ? TRUE : FALSE;

		$this -> favorite = false;
		$this -> liked = false;
		$this -> disliked = false;

		$this -> views = $row["views"];
		$this -> votes = $row["votes"];

		$this -> row = $row;
	}

	function options() {
		$query = select("id, name, votes", "view_optionsvotes", "WHERE post_id = '{$this->id}' 
                         ORDER BY votes DESC, id ASC");

		// clear options
		$this -> options = array();
		// push all the options
		$this -> totalvotes = 0;
		while ($row = assoc($query)) {
			$votes = (empty($row["votes"]) ? "0" : $row["votes"]);
			array_push($this -> options, new Option($row["id"], stripslashes($row["name"]), $votes));
			$this -> totalvotes += intval($row["votes"]);
		}

		return $this -> options;
	}

	function tags() {
		$query = select("name", "tbl_tags", "WHERE post_id = '{$this->id}'");
		$this -> tags = array();
		while ($row = assoc($query)) {
			array_push($this -> tags, $row["name"]);
		}

		return $this -> tags;
	}

	function create() {
		
		$postvars = array("title" => post("post-title"), 
							"cat" => post("post-cat"), 
							"public" => post("public"), 
							"newoptions" => emptypost("newoptions") ? '0' : '1', 
							"choices" => post("choices") == "2" ? post("choices-max") : post("choices"), 
							"user_id" => user()->id,
							"description" => post("post-description"));
							
		insert("tbl_posts", $postvars);
		global $conn;
		$id = mysql_insert_id($conn);
		
		// Inserting Options
		for ($i = 1; $i <= 10; $i++) {
			$option = post("post-opt-$i");
			if (!empty($option))
				insert("tbl_options", array("post_id" => $id, "name" => $option, "user_id" => user() -> id));
		}

		$this -> db($id);
		user() -> db();
	}

	function edit() {
		if (user() -> id == $this -> user_id || user()->admin) {
			update("tbl_posts", array("title" => post("post-title"), 
										"cat" => post("post-cat"), 
										"public" => post("public"), 
										"newoptions" => emptypost("newoptions") ? '0' : '1', 
										"choices" => post("choices") == "2" ? post("choices-max") : post("choices"), 
										"description" => post("post-description")), 
					"WHERE id = '" . post("editpost") . "'");
					
			$this -> db(post("editpost"));
		} else
			throw new Exception("Unknown user editing a post");
	}

	function delete() {
		if (user() -> id == $this -> user_id || user()->admin) {
			if (empty($this -> options))
				$this -> options();

			foreach ($this->options as $opt) {
				$id = array("option_id" => $opt -> id);
				delete("tbl_votes", $id);
				delete("tbl_comments", $id);
			}
			
			$id = array("post_id" => $this -> id);
			delete("tbl_options", $id);
			delete("tbl_user_favorites", $id);
			delete("tbl_posts_likes", $id);
			delete("tbl_posts", array("id" => $this -> id));
			unset($_SESSION["post" . $this -> id]);
			user() -> db();
			return true;
		} else
			throw new Exception("Unknown User deleting a post");

	}

}
?>