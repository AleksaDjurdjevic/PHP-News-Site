<?php
	require "config.php";
	$token =  md5(time().microtime().uniqid()); 
	$_SESSION['token'] = $token;
	html_header();
	
	$news_name = $_POST['newsName'];
	$position = $_POST['position'];
		
	echo "
		<main id = 'logreg'>
			<form class = 'form' action = 'check/add_news_check.php' method = 'POST' enctype = 'multipart/form-data'>
					<input type = 'hidden' name = 'token' value = '{$token}'>
				<input type = 'hidden' name = 'position' value = '{$position}'>
				<input type = 'text' name = 'newsName' value = '{$news_name}' readonly><br>
				<input type = 'text' name = 'articleName' placeholder = 'Article Title..'><br>
				<textarea name = 'paragraphContent' placeholder = 'Place the content of the paragraph here..'></textarea><br>
				<input type= 'file' name = 'img'>
				<button type = 'submit' name = 'addNews'>Post News</button>
			</form>
		</main>
	";
	
	
	html_footer();
?>