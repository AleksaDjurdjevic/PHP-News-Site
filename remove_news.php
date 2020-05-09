<?php
	require "check/conn.php";
	if (isset($_POST['newsRemove'])){
		$news_name = $_POST['newsName'];
		$position = $_POST['position'];
		$article_name = $_POST['articleName'];
		
		$query = "
			UPDATE news 
			SET article_title = 'Add News',
				paragraph_content = 'Add paragraph content here...'
			WHERE
			menu = ? AND position = ?
		;";
		$stmt = mysqli_prepare($conn, $query);
		mysqli_stmt_bind_param($stmt, "si", $news_name, $position);
		mysqli_stmt_execute($stmt);
		
		$article_name = strtolower($article_name);
		$article_name = str_replace(" ", "_", $article_name);
		$article_name = str_replace("/", "_", $article_name);
		
		$prepared_href = strtolower($news_name);
		$prepared_href = str_replace(" ", "_", $prepared_href);
		$prepared_href = str_replace("/", "_", $prepared_href);

		if(file_exists("news_articles/{$article_name}.php")){
			unlink("news_articles/{$article_name}.php");
		}
		
		header("Location: news_{$prepared_href}.php");
	}