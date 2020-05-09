<?php
	session_start();
	require "../check/conn.php";
	if (isset($_POST['commentRemove'])){
			$date = htmlentities($_POST['commentDate']);
			$uid = htmlentities($_POST['id']);
			$article_name = htmlentities($_POST['articleName']);
			$position = htmlentities($_POST['position']);
			$news_name = htmlentities($_POST['newsName']);
			
			$query = "DELETE FROM comments WHERE id = ? AND date = ?";
			$stmt = mysqli_prepare($conn, $query);
				mysqli_stmt_bind_param($stmt, "is", $uid, $date);
				mysqli_stmt_execute($stmt);
			$res = mysqli_stmt_get_result($stmt);
			
			$prepared_article = strtolower($article_name);
			$prepared_article = str_replace(" ", "_", $prepared_article);
			$prepared_article = str_replace("/", "_", $prepared_article);
			
			$prepared_news = strtolower($news_name);
			$prepared_news = str_replace(" ", "_", $prepared_news);
			$prepared_news = str_replace("/", "_", $prepared_news);
			
			header("Location: {$prepared_article}.php?position={$position}&menu={$prepared_news}&article={$prepared_article}");
	}