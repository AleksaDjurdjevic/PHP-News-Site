<?php
	require "config.php";
	html_header();
	$position = $_POST['position'];
	$old_menu = $_POST['menu'];
	$prepared_old_menu = prepareHref($old_menu);
	
	echo "
		<main id = 'logreg'>
			<form class = 'form' action = 'check/add_menu_check.php' method = 'POST'>
				<input type = 'hidden' name = 'position' value = '{$position}'>
				<input type = 'hidden' name = 'menu' value = '{$old_menu}'>
				<input type = 'text' name = 'menuName' placeholder = 'Menu Title..'><br>
				<button type = 'submit' name = 'addMenu'>Add Menu</button>
			</form>
		</main>
	";
	
	html_footer();
?>
