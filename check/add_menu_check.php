<?php
// This file handles the adding of categories of news (nav bar)
	require "conn.php";
	if(isset($_POST['addMenu'])){
		// Every category has its position and heading
		$position = $_POST['position'];
		$menu_name = $_POST['menuName'];
		$old_menu = $_POST['menu'];
		
		$prepared_old_menu = strtolower($old_menu);
		$prepared_old_menu = str_replace(" ", "_", $prepared_old_menu);
		$prepared_old_menu = str_replace("/", "_", $prepared_old_menu);
		// Every category has its own php file whose name begins with "news_" and ends with ".php"
		if(file_exists("../news_{$prepared_old_menu}.php")){
				unlink("../news_{$prepared_old_menu}.php");
			}
			// Deleting image that is linked to a category
			if(file_exists("../images/{$prepared_old_menu}{$position}.jpg")){
				unlink("../images/{$prepared_old_menu}{$position}.jpg");
			}
			if(file_exists("../images/{$prepared_old_menu}{$position}.png")){
				unlink("../images/{$prepared_old_menu}{$position}.png");
			}
			if(file_exists("../images/{$prepared_old_menu}{$position}.gif")){
				unlink("../images/{$prepared_old_menu}{$position}.gif");
			}

		
		$preparedMenu = strtolower($menu_name);
		$preparedMenu = str_replace(" ", "_", $preparedMenu);
		$preparedMenu = str_replace("/", "_", $preparedMenu);
		
		// Updating position in the main_menu table, where the data about the nab bar originates
		$query = "
				UPDATE main_menu 
				SET menu = ?
				WHERE
				id_menu = ?
				";
		$stmt = mysqli_prepare($conn, $query);
		mysqli_stmt_bind_param($stmt, "si", $menu_name, $position);
		mysqli_stmt_execute($stmt);
		
		//Resetting of articles for a new category
		$query = "
				UPDATE news 
				SET article_title = 'Add News',
					paragraph_content = 'Add paragraph content here...'
				WHERE
				menu = ?
				;";
		$stmt = mysqli_prepare($conn, $query);
		mysqli_stmt_bind_param($stmt, "s", $menu_name);
		mysqli_stmt_execute($stmt);
		
		// Generating new php file for the new category
		$new_page = fopen("../news_{$preparedMenu}.php", "w");
		$contents_of_new_page = "<?php
										require 'config.php';
										html_header();
										generateNews(\"{$menu_name}\");
										html_footer();
									?>";
		fwrite($new_page, $contents_of_new_page);
		fclose($new_page);	
		
		header("Location: ../index.php");
	} 