<?php
	require "conn.php";
	session_start();
	
	//This is for the header to be valid
	$news_name = htmlentities($_POST['newsName']);	
	$prepared_href = strtolower($news_name);
	$prepared_href = str_replace(" ", "_", $prepared_href);
	$prepared_href = str_replace("/", "_", $prepared_href);
	
	if(isset($_POST['addNews'])){
		if(!empty($_FILES)){
			if($_SESSION['token'] == $_POST['token']){
				// Proccessing of the uploaded file
				$article_name = htmlentities($_POST['articleName']);
				$paragraph_content = htmlentities($_POST['paragraphContent']);
				$position = htmlentities($_POST['position']);
				$file = $_FILES['img'];
				$fileName = $_FILES['img']['name'];
				$fileTmpName = $_FILES['img']['tmp_name'];
				$fileSize = $_FILES['img']['size'];
				$fileError = $_FILES['img']['error'];
				
				// Extracting the extention
				$fileExt = explode('.', $fileName);
				$fileExt = strtolower(end($fileExt));
				$allowedType = array("jpg", "png", "gif");
				
				// Check the validity of the extention and the whole file
				if(in_array($fileExt, $allowedType)){
					if($fileSize < 1000000){
						if($fileError === 0){
							$fileNewName = "{$news_name}{$position}.{$fileExt}";
							$preparedNewName = strtolower($fileNewName);
							$preparedNewName = str_replace(" ", "_", $preparedNewName);
							$fileDest = "C:/wamp64/www/Vezbanje_PHP/Web_aplikacija/images/{$preparedNewName}";
							
							move_uploaded_file($fileTmpName, $fileDest);
						}else{
							header("Location: ../news_{$prepared_href}.php?error=error");
							die();
						}
					}else{
						header("Location: ../news_{$prepared_href}.php?error=tooBig");
						die();
					}
				}else{
					header("Location: ../news_{$prepared_href}.php?error=invType");
					die();
				}
				// Image resizing
				switch($fileExt){
					
					case "jpg":
						$img = imagecreatefromjpeg("../images/{$preparedNewName}");

						$new_width = 248;
						$new_height = 186;
						
						$old_width = imageSX($img);
						$old_heigth = imageSY($img);

						$newImg = ImageCreateTrueColor($new_width, $new_height);
						imagecopyresampled ($newImg, $img, 0,0,0,0, $new_width, $new_height, $old_width, $old_heigth); 
						   
						imagejpeg($newImg,"../images/{$preparedNewName}"); 
						
						imagedestroy($newImg); 
						imagedestroy($img);
						break;
					case "png":
						$img = imagecreatefrompng("../images/{$preparedNewName}");

						$new_width = 248;
						$new_height = 186;
						
						$old_width = imageSX($img);
						$old_heigth = imageSY($img);

						$newImg = ImageCreateTrueColor($new_width, $new_height);
						imagecopyresampled ($newImg, $img, 0,0,0,0, $new_width, $new_height, $old_width, $old_heigth); 
						   
						imagepng($newImg,"../images/{$preparedNewName}"); 
						
						imagedestroy($newImg); 
						imagedestroy($img);
						break;
					case "gif":
						$img = imagecreatefromgif("../images/{$preparedNewName}");

						$new_width = 248;
						$new_height = 186;
						
						$old_width = imageSX($img);
						$old_heigth = imageSY($img);

						$newImg = ImageCreateTrueColor($new_width, $new_height);
						imagecopyresampled ($newImg, $img, 0,0,0,0, $new_width, $new_height, $old_width, $old_heigth); 
						   
						imagegif($newImg,"../images/{$preparedNewName}"); 
						
						imagedestroy($newImg); 
						imagedestroy($img);
						break;
				}
				
				// Creating a new php file for the article
				$prepared_filename = strtolower($article_name);
				$prepared_filename = str_replace(" ", "_", $prepared_filename);
				$prepared_filename = str_replace("/", "_", $prepared_filename);
				
				$new_page = fopen("../news_articles/{$prepared_filename}.php", "w");
				$contents_of_new_page = "<?php
											require 'config.php';
											html_header();
											require 'comment_config.php';
											html_footer();
										?>";
				fwrite($new_page, $contents_of_new_page);
				fclose($new_page);
				
				// Updating database
				$query = "
					UPDATE news 
					SET article_title = ?,
						paragraph_content = ?
					WHERE
					menu = ? AND position = ?
				;";
				
				$stmt = mysqli_prepare($conn, $query);
				mysqli_stmt_bind_param($stmt, "sssi", $article_name, $paragraph_content, $news_name, $position);
				mysqli_stmt_execute($stmt);
				
				header("Location: ../news_{$prepared_href}.php");
			}else{
				header("Location: ../news_{$prepared_href}.php?error=wrongToken");
			}	
		}else{
			header("Location: ../news_{$prepared_href}.php?error=noimg");
		}
	}else{
		header("Location: ../news_{$prepared_href}.php?error=noButtonSelected");
	}
	