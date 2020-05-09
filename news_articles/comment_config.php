		<?php
			$news_name = htmlentities($_GET['menu']);
			$prepared_news_name = prepareHref($news_name);
			$position = htmlentities($_GET['position']);
			$article_name = htmlentities(prepareHref($_GET['article']));
				
			require "../check/conn.php";
			// Upit koji vraća uvek komentare vezane za ime članka
			$query = "
				SELECT article_title, paragraph_content FROM news
				WHERE position = ? AND menu = ?
			;";
			$stmt = mysqli_prepare($conn, $query);
				mysqli_stmt_bind_param($stmt, "is", $position, $prepared_news_name);
				mysqli_stmt_execute($stmt);
			$res = mysqli_stmt_get_result($stmt);
			
			$img = glob("../images/{$news_name}{$position}.*");
			$info = pathinfo($img[0]);
			$ext = $info['extension'];
			//Upit kao rezultat ima jedan red stoga generiše se prvi deo stranice
			while ($row = mysqli_fetch_assoc($res)){
				echo "<main id = 'main'>
				<section class = 'newsWrapArticle'>
					<h1 class = 'newsHeading'>{$row['article_title']}</h1>
					<img src = '../images/{$news_name}{$position}.{$ext}' alt = '{$news_name}{$position}.jpg'>
					<p>";
						echo nl2br($row['paragraph_content']);
				echo"	</p>
				</section><hr><br>";
			}
				$query = "SELECT * FROM comments;";
				$res = mysqli_query($conn, $query);
				// Prikazivanje samo komentara vezanih za članak
				while ($row = mysqli_fetch_assoc($res)){
					if ($article_name == $row['article_name'] && $prepared_news_name == $row['menu']){
						echo "<article class = 'commentWrap'>";
							// Ako je ulogovan administrator za svaki komentar prikaži dugme za brisanje
							if (isset($_SESSION['userID']) && isset($_SESSION['userName'])){
								if($_SESSION['userID'] == '1' && $_SESSION['userName'] == 'Root'){
									echo "<form class = 'removeContent' action = 'remove_comment.php' method = 'POST'>
											<input type = 'hidden' name = 'articleName' value = '{$article_name}'>
											<input type = 'hidden' name = 'id' value = '{$row['id']}'>
											<input type = 'hidden' name = 'newsName' value = '{$prepared_news_name}'>
											<input type = 'hidden' name = 'position' value = '{$position}'>
											<input type = 'hidden' name = 'commentDate' value = '{$row['date']}'>
											<button type = 'submit' name = 'commentRemove'>Remove Comment</button>
										</form>";
								// Ako nije, vidi ko jeste i prikaži dugme za brisanje samo za njihove komentare
								}elseif($row['id'] == $_SESSION['userID'] && $row['username'] == $_SESSION['userName']){
									echo "<form class = 'removeContent' action = 'remove_comment.php' method = 'POST'>
											<input type = 'hidden' name = 'articleName' value = '{$article_name}'>
											<input type = 'hidden' name = 'id' value = '{$row['id']}'>
											<input type = 'hidden' name = 'newsName' value = '{$prepared_news_name}'>
											<input type = 'hidden' name = 'position' value = '{$position}'>
											<input type = 'hidden' name = 'commentDate' value = '{$row['date']}'>
											<button type = 'submit' name = 'commentRemove'>Remove Comment</button>
										</form>";
								}
								
							}
						echo "	<span><b>{$row['username']}</b> | {$row['date']}</span>
								<p>{$row['comment']}</p>
							</article><br>";
					}
				}
				// Ako je korisnik ulogovan, prikaži dugme za dodavanje komentara
				if (isset($_SESSION['userID']) && isset($_SESSION['userName'])){
					echo 	"<form class = 'commentForm' method = 'POST' action = 'add_comment.php'>
								<input type = 'hidden' name = 'newsName' value = '{$prepared_news_name}'>
								<input type = 'hidden' name = 'position' value = '{$position}'>
								<input type = 'hidden' name = 'articleName' value = '{$article_name}'>
								<textarea name = 'commentContent'></textarea>
								<button type = 'submit' name = 'commentSubmit'>Submit Comment</button>
							</form>";
				}else{
					//Ako nije, prikaži podrazumevani tekst
					echo "<p class = 'commentLogin'>You need to login to be able to comment.</p>";
				}
			echo "</main>";
			
		?>