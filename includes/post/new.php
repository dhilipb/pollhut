<?php

$error = false;
if (ispostset("newpost") || ispostset("editpost")) {
	$error = "";
	if (emptypost("post-title"))
		$error .= "<li>Title of the post</li>";
	if (emptypost("post-cat"))
		$error .= "<li>Please specify a category for the post</li>";
	if (emptypost("post-description"))
		$error .= "<li>Description of the post</li>";
	if (post("public") == "")
		$error .= "<li>Choose whether you want the post to be public or private</li>";
	
	
	$options = 0;
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, strlen('post-opt')) == 'post-opt'
				&& $v != "") {
			$options++;
		}
	}
	
	if ($options < 2)
		$error .= "<li>Enter at least two options</li>";
	
	if (!empty($error)) {
		$description = $_POST["post-description"];
	} else if (ispostset("newpost")) {
		
		// Occurs when you create a post with no errors
		$post = new Post();
		$post -> create();
		$header = "Location: " . linkify("post", $post);
		logstr($header);
		header($header);
	} else if (ispostset("editpost")) {
		
		// Occurs when you submit the edited post
		$post = new Post();
		$id = post("editpost");
		$post -> db($id);
		$post -> edit();
		header("Location: " . linkify("post", $post));
	}
} else if (isset($editpost)) {
	// Show form to edit the post
	$title = $editpost -> title;
	$cat = $editpost -> cat;
	$description = $editpost -> description;
	
	$_POST["post-cat"] = $editpost -> cat;
	$_POST["post-title"] = $editpost->title;
	$_POST["public"] = $editpost -> view_public ? '1' : '0';
	$_POST["newoptions"] = $editpost -> newoptions ? 1 : 0;
	
	if ($editpost -> choices > 1) {
		$_POST["choices"] =  2;
		$_POST["choices-max"] = $editpost->choices;  
	} else {
		$_POST["choices"] = $editpost->choices;
	}
	 
	$choiceX = $editpost -> choices < 0;
}

// Builds up categories for the drop down box
function catListOpt($id = 0) {
	$cat = post("post-cat");
	$qry_cat = select("id, name", "tbl_categories", "WHERE cat_id = '$id' ORDER BY id");
	if (!rows($qry_cat))
		return;

	while ($row_cat = assoc($qry_cat)) {
		$cat_id = $row_cat["id"];
		if ($id == 0) {
			echo "<optgroup label=\"" . ucwords($row_cat["name"]) . "\">";
			catListOpt($cat_id);
			echo "</optgroup>";
		} else {
			if ($cat == $cat_id)
				echo "<option value=\"$cat_id\" selected>" . ucwords($row_cat["name"]) . "</option>";
			else
				echo "<option value=\"$cat_id\">" . ucwords($row_cat["name"]) . "</option>";
		}
	}

}

$this -> title = isset($editpost) ? "Editing Poll" : "New Poll";

if (!empty($error)) {
	echo "<div class=\"status error\">" .
	"<strong>Please check the following fields:</strong>" .
	"<ul class=\"bullet\">$error</ul></div>";
}
?>
<style type="text/css">
	#post-cat {
	height: 40px;
	margin-top: 2px;
	font-size: 10.5pt;
	}
	#post-cat option, #post-cat optgroup {
		padding: 5px;
	}
	
	#newpoll fieldset {
		background-color: #f3f3f3;
		-moz-border-radius: 8px;
		-webkit-border-radius: 8px;
		margin-bottom: 10px;
		padding: 20px;
	}
	#addoptions {
		font-size: 12px;
		background: #C7C7C7;
		padding: 5px 7px;
		border-radius: 0 0 5px 5px;
		color: #111;
		float: right;
	}
	
	#opt_suggest {
		position: absolute;
		background-color: white;
		border: 1px solid #ccc;
		z-index: 10;
		box-shadow: 0 2px 5px #aaa;
		-moz-box-shadow: 0 2px 5px #aaa;
		display: none;
	}
	#opt_suggest li {
		padding: 2px 0 2px 10px;
		padding: 10px;
		cursor: pointer;
		font-size: 12px;
	}
	#opt_suggest li:hover, #opt_suggest li.active {
		background-color: #0A246A;
		color: white;
	}
	#newpost_options .placeholder-text {
		width: 330px;
	}
	#newpost_options .text-input.load {
		background-image: url('images/loading.gif');
		background-repeat: no-repeat;
		background-position: right center;
		padding-right: 20px;
		width: 310px;
	}
	#newpost_options .delete {
		width: 16px;
		height: 16px;
		background-image: url(images/delete.png);
		position: absolute;
		right: 10px;
		top: 20px;
		margin-top: -8px;
		cursor: pointer;
	}
	
</style>
<div class="fs12">
	All fields are required.
</div>
<br />
<form id="newpoll" method="post" action="<?=PATH?>">
	<fieldset>
		<legend>
			Post Details
		</legend>
		<? // --- Title ---?>
		<div class="placeholder-text">
			<label for="post-title">Title</label>
			<input type="text" class="text-input" <?=formval("post-title")?>)/>
		</div>
		<small> The title of your post. Please ensure a duplicate post does not exist!</small>
		<? // --- Categories ---?>
		<select <?=formval("post-cat")?> class="text-input">
			<option disabled="true">Choose Category</option>
			<?php
			catListOpt();
			?>
		</select>
		<small> Choose the category your post best represents. </small>
		<? // --- Public/Private? ---?>
		<ul class="radio" style="margin-top: 5px;">
			<li>
				<label>
					<input type="radio" <?=formchecked("public", "1")?> <?=emptypost("public") ? "checked" : null?>>
					Public </label>
			</li>
			<li>
				<label>
					<input type="radio" <?=formchecked("public", "0")?>>
					Private </label>
			</li>
		</ul>
		<small> The privacy setting of your post. Public - anyone can see the post and vote/comment.
			Private - Only those with access to the link can see the post and vote/comment. </small>
	</fieldset>
	<fieldset>
		<legend>
			Options
		</legend>
		<? // --- Number of choices ---
		// 1 = single choice; 2 = multiple choice; -1 = max choices
		?>
		<ul class="radio">
			<li>
				<label>
					<input type="radio" <?=formchecked("choices", "1")?> <?=emptypost("choices") ? "checked" : null?>>
					Single vote</label>
			</li>
			<li>
				<label>
					<input type="radio" <?=formchecked("choices", "-1")?>>
					Multiple votes</label>
			</li>
			<li style="width: 120px;">
				<label>
					<input type="radio" <?=formchecked("choices", "2")?> id="choices-max-radio" >
					Max:
					<input type="text" <?=formval("choices-max")?>/>
				</label>
			</li>
		</ul>
		<small> Users can vote on either a single option in this poll or multiple options.
			You can limit how many options a user can vote by using the max field. </small>
		<? // --- Allow new options ---?>
		<ul class="radio" style="margin-top: 5px;">
			<li>
				<label>
					<input type="checkbox" <?=formchecked("newoptions", 1)?>>
					Allow users to add new options </label>
			</li>
		</ul>
		<small> Registered users can add new options. Enable this feature if you would like registered users to add new
			options to your option list. </small>
		<? // --- Options ---?>
		<? if (isset($editpost)) {
		?>
			<table id="newpost_options" width="100%" cellpadding="0" cellspacing="0" style="margin-top: 5px;">
			<?php
				$i = 1;
				foreach($editpost->options() as $opt) {
					$_POST["post-opt-" . $i] = $opt->name;
					if ($i-1 % 2 == 0)
						echo "<tr>";
			?> 
				<td>
					<div class="placeholder-text readonly">
						<div class="delete" o="<?=$opt->id?>" p=<?=$editpost->id?> ></div>
						<input type="text" class="text-input optbox" 
							readonly="readonly" <?=formval("post-opt-" . $i)?> autocomplete="off"/>
					</div>
				</td>
			<?php
					if ($i % 2 == 0)
						echo "</tr>";
					
					$i++;
				}
			?>
			</table>
		<?
			} else {
		?>
			<ul id="opt_suggest"></ul>
			<table id="newpost_options" width="100%" cellpadding="0" cellspacing="0" style="margin-top: 5px;">
			<?php
				$count = 10;
					
				for ($i = 1; $i <= $count; $i++) {
					if ($i-1 % 2 == 0)
					echo "<tr>";
			?> 
				<td>
					<div class="placeholder-text">
						<label for="post-opt-<?=$i?>">Option <span class="opt-count"><?=$i?></span></label>
						<input type="text" class="text-input optbox" <?=formval("post-opt-" . $i)?> autocomplete="off"/>
					</div>
				</td>
			<?php
					if ($i % 2 == 0)
						echo "</tr>";
				}
				?>
			</table>
			<a href="#addoptions" id="addoptions">Add more..</a>
		<? } ?>
	</fieldset>
	<fieldset>
		<legend>
			Description
		</legend>
		<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="/js/ckeditor/adapters/jquery.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#post-description").ckeditor({
					toolbar:  [
					 	{ name: 'document',		items : [ 'Source','-','DocProps','Preview','Print','-','Templates' ] },
					 	{ name: 'clipboard',	items : [ 'Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
					 	{ name: 'editing',		items : [ 'Find','Replace'] },
					 	{ name: 'basicstyles',	items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
					 	{ name: 'paragraph',	items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'] },
					 	{ name: 'links',		items : [ 'Link','Unlink','Anchor' ] },
					 	{ name: 'insert',		items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','Iframe' ] },
					 	'/',
					 	{ name: 'styles',		items : [ 'Styles','Format','Font','FontSize' ] },
					 	{ name: 'colors',		items : [ 'TextColor','BGColor' ] },
					 	{ name: 'tools',		items : [ 'Maximize'] }
					], height: "400px", contentCss: "style/typo.css"
				});
			});
		</script>
		<? // --- Description ---?>
		<small style="text-align: right; margin-top: -20px">
			<a href="#" class="addwiki">Add Information from Wikipedia</a>
		</small>
		<textarea name="post-description" id="post-description" 
				/><?=$description?></textarea>
	</fieldset>
	<?php
		if (isset($editpost)) {
			echo '<input type="hidden" name="editpost" value="' . $editpost->id . '" />';
		} else {
			echo '<input type="hidden" name="newpost" value="1" />';
		}
	?>
	<input type="submit" value="Submit" data-submit="Submitting.." class="blue button" />
</form>