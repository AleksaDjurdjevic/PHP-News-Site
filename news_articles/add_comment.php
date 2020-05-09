<?php
	session_start();
	require "../check/conn.php";
	if (isset($_POST['commentSubmit'])){
		$comment = htmlentities($_POST['commentContent']);
		$date = date("Y-m-d H:i:s");
		$uid = $_SESSION['userID'];
		$uname = $_SESSION['userName'];
		$article_name = $_POST['articleName'];
		$position = $_POST['position'];
		$news_name = $_POST['newsName'];
		
		$query = "	INSERT INTO comments(id, comment, date, username, article_name, menu) 
					VALUES (?, ?, ?, ?, ?, ?);
				";
		$stmt = mysqli_prepare($conn, $query);
			mysqli_stmt_bind_param($stmt, "isssss", $uid, $comment, $date, $uname, $article_name, $news_name);
			mysqli_stmt_execute($stmt);
			
		$prepared_article = strtolower($article_name);
		$prepared_article = str_replace(" ", "_", $prepared_article);
		$prepared_article = str_replace("/", "_", $prepared_article);
			
		$prepared_news = strtolower($news_name);
		$prepared_news = str_replace(" ", "_", $prepared_news);
		$prepared_news = str_replace("/", "_", $prepared_news);
			
		header("Location: {$prepared_article}.php?position={$position}&menu={$prepared_news}&article={$prepared_article}");
	}