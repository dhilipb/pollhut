<?php
	// Retrieving Content
	if (!$this -> error) {
		try {
			ob_start();
			require_once ("includes/index.php");
			$this -> page_content = ob_get_contents();
			ob_end_clean();
		} catch (Exception $exc) {
			$this -> error = TRUE;
		}
	}
?>
<section id="body" class="content">
	<? if (!$this->error) {
		/* Tools list
		if (is_array($this -> toolslist)) {
			echo "<ul id=\"toolslist\">";
			foreach ($this->toolslist as $name => $link) {
				$active = $link == PATH ? ' class="active"' : NULL;
				if (substr($link, 0, 1) == "<") {
					echo "<li{$active}>$link</li>";
				} else {
					echo "<li{$active}><a href=\"$link\">$name</a></li>";
				}
			}
			echo "</ul>";
		}*/
		
		// Title
		if (!empty($this -> title))
			echo '<h1 id="pagetitle" class="hr">' . $this -> title . "</h1>";
	} ?>
	
	<table width=100% cellpadding="0" cellspacing="0">
		<tr>
			<?
			echo '<div id="mod_content">';

				if ($this -> error)
					require ("404.php");
				else
					echo $this -> page_content;

			echo "</div>";
			?>
		</tr>
	</table>
	<?php
		//require "footer.php";
	?>
</section>