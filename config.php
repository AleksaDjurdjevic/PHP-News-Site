<?php
	session_start();
//Function that formats the string that is later used to create dynamic content	
	function prepareHref($string){
		if (stripos($string, "_") == false){
			$prepared_href = strtolower($string);
			$prepared_href = str_replace(" ", "_", $prepared_href);
			$prepared_href = str_replace("/", "_", $prepared_href);
			return $prepared_href;
			
		}elseif (stripos($string, "_") !== false){
			$reverse_prepared_href = str_replace("_", " ", $string);
			$reverse_prepared_href = ucwords($reverse_prepared_href);
			return $reverse_prepared_href;
			
		}else{
			echo "Wrong string format.";
		}
	}
//First part of almost every php file
	function html_header(){
		require "check/conn.php";
			echo "			<!DOCTYPE html>
						<html lang = 'en'>
						<head>
							<title>Web Application</title>
							<meta charset = 'utf-8'>
							<link href = 'style.css' type = 'text/css' rel = 'stylesheet'>
							<link href='https://fonts.googleapis.com/css?family=Rubik:400,700&display=swap' rel='stylesheet'>
							<link href='https://fonts.googleapis.com/css?family=Dancing+Script&display=swap' rel='stylesheet'> 
						</head>
						<body>";
					// If user is logged in, show user info near the Logout option, if not, show "registration".
								if(isset($_SESSION['userID']) && isset($_SESSION['userName'])){
									echo "<h2 class = 'registerLogin'>{$_SESSION['userName']} | <a href = 'logout.php'>Logout</a></h2>";
								}else{
									echo "<h2 class = 'registerLogin'><a href = 'login.php'>Register | Login</a></h2>";
								}
			//Nav bar data is pulled from a database
			echo "
							<div id = 'wrapper'>
								<nav id = 'nav'>
									<ul> 
										<li><a href = 'index.php'>Home</a></li>";
										for ($i=1; $i<4; $i++){
											$query = "SELECT * FROM main_menu WHERE position = ?;";
											$stmt = mysqli_prepare($conn, $query);
											mysqli_stmt_bind_param($stmt, "i", $i);
											mysqli_stmt_execute($stmt);
											$res = mysqli_stmt_get_result($stmt);
											while($row = mysqli_fetch_assoc($res)){
												$main_menu_href = prepareHref($row['menu']);
												echo "<li><a href = 'news_{$main_menu_href}.php'>{$row['menu']}</a>";
												if(isset($_SESSION['userID']) && isset($_SESSION['userName'])){
													if($_SESSION['userID'] == '1' && $_SESSION['userName'] == 'Root'){
														echo 	"<form class = 'addMenu' action = 'add_menu.php' method = 'POST'>
																		<input type = 'hidden' name = 'position' value = '{$row['position']}'>
																		<input type = 'hidden' name = 'menu' value = '{$row['menu']}'>
																		<button type = 'submit' name = 'newsAdd'>X</button>
																	</form>";
													}
												}
												echo "</li>";
											}
										}
			echo "					</ul>
								</nav>";
			
	}
	function html_footer(){
		echo "					
							</div>
							<footer id = 'footer'>
								<p>Copyright &copy;"; echo date('Y'); echo "</p>
							</footer>
						</html>
				";
	}
// Generating 3 articles per category
	function generateNews($newsName){
		//If theres an error being sent from add_news_check it will show up behind main content
			if(isset($_GET['error'])){
				$error = $_GET['error'];
				switch($error){
					case "error": $paragraph_msg = "There was an error in your upload."; break;
					case "tooBig": $paragraph_msg = "Your file is too big."; break;
					case "invType": $paragraph_msg = "Your file is invalid."; break;
					default: $paragraph_msg = "Something went wrong, please try again."; break;
				}
				echo "<p>{$paragraph_msg}</p>";
			}
		require "check/conn.php";
		echo "<main id = 'main'>";
		for ($i=1; $i<4; $i++){
			// Position is refered to the position of the link in the nav bar
			$query = "SELECT * FROM news WHERE position = ? AND menu = ?;";
				$stmt = mysqli_prepare($conn, $query);
				mysqli_stmt_bind_param($stmt, "is", $i, $newsName);
				mysqli_stmt_execute($stmt);
			$res = mysqli_stmt_get_result($stmt);
			while($row = mysqli_fetch_assoc($res)){
				echo "<div class = 'newsWrap'>";
				// If admin is logged in, show additional buttons
				if(isset($_SESSION['userID']) && isset($_SESSION['userName'])){
					if($_SESSION['userID'] == '1' && $_SESSION['userName'] == 'Root'){
						if ($row['article_title'] == 'Add News'){
							echo 	"<form class = 'addNews' action = 'add_news.php' method = 'POST'>
									<input type = 'hidden' name = 'newsName' value = '{$row['menu']}'>
									<input type = 'hidden' name = 'position' value = '{$row['position']}'>
									<button type = 'submit' name = 'newsAdd'>Add News</button>
								</form>";
						}else{
							echo 	"<form class = 'removeContent' action = 'remove_news.php' method = 'POST'>
									<input type = 'hidden' name = 'articleName' value = '{$row['article_title']}'>
									<input type = 'hidden' name = 'newsName' value = '{$row['menu']}'>
									<input type = 'hidden' name = 'position' value = '{$row['position']}'>
									<button type = 'submit' name = 'newsRemove'>Remove News</button>
								</form>";
						}
						
						
					}	
				}
				// Extracting a snippet from an article
				$prepared_article_href = prepareHref($row["article_title"]);
				$prepared_menu_href = prepareHref($row['menu']);
				
				$short_paragraph = explode(". ", $row["paragraph_content"]);
				// If the article is removed, set placeholder values
				if ($row['article_title'] == 'Add News'){
					echo "<img class = 'articleImage' src = 'images/blank.jpg' alt = '{$prepared_menu_href}{$row['position']}.jpg'>
					<h2>{$row["article_title"]}</h2>
					<p>";
						echo $short_paragraph[0] . ".";
					echo "</p>
				</div>
			<hr>	";
				}else{
					$img = glob("images/{$prepared_menu_href}{$row['position']}.*");
					$info = pathinfo($img[0]);
					$ext = $info['extension'];
					
					// If article exists, set the right links to the article and image as well
					echo "<a href = 'news_articles/{$prepared_article_href}.php?position={$i}&menu={$prepared_menu_href}&article={$prepared_article_href}'>
					<img src = 'images/{$prepared_menu_href}{$row['position']}.{$ext}' alt = '{$prepared_menu_href}{$row['position']}.jpg'></a>
					<h2>{$row["article_title"]}</h2>
					<p>";
						echo $short_paragraph[0] . ".";
					echo "</p>
				</div>
			<hr>	";	
				}
				
				
			}
		}
		echo "</main>";
	}	
		
?>