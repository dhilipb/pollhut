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
		// Tools list
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
		}
		
		// Title
		if (!empty($this -> title))
			echo '<h1 id="pagetitle" class="hr">' . $this -> title . "</h1>";
	} ?>
	
	<table width=100% cellpadding="0" cellspacing="0">
		<tr>
			<?
			if ($this -> module === "left") {
				echo '<td id="mod_left" class="position">';
				require_once ("includes/modules/left.php");
				echo '</td>';
			}
			echo '<td id="mod_content" class="position">';

				if ($this -> error)
					require ("404.php");
				else
					echo $this -> page_content;

			echo "</td>";
			if ($this -> module === "right") {
				echo '<td id="mod_right" class="position">';
				require_once ("includes/modules/right.php");
				echo '</td>';
			}
			?>
		</tr>
	</table>
	<?php
		require "footer.php";
	?>
</section>