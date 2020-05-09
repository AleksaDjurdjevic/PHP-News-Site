<?php
session_start();
	if(isset($_POST['loginSubmit'])){
		if($_SESSION['token'] == $_POST['token']){
			require "conn.php";
			$username = htmlentities($_POST['UsrName']);
			$pass = htmlentities($_POST['UsrPass']);
		
			// Fields validation and handling on login.php
			if(empty($username)){
				header("Location: ../login.php?error=noUser");
				exit();
			}
			if(empty($pass)){
				header("Location: ../login.php?error=noPass");
				exit();
			}
		
			$query = "SELECT * FROM users WHERE username = ? OR email= ?;";
			$stmt = mysqli_prepare($conn, $query);
			mysqli_stmt_bind_param($stmt, "ss", $username, $username);
			mysqli_stmt_execute($stmt);
			
			$res = mysqli_stmt_get_result($stmt);
			if($row = mysqli_fetch_assoc($res)){
				// If logging in is successful, start session and redirect the user
				if(password_verify($pass, $row["password"])){
					session_start();
					$_SESSION["userID"] = $row["id"];
					$_SESSION["userName"] = $row["username"];
					header('Location: ../index.php?login=success');
				}else if($pass !== $row["password"]){
					header("Location: ../login.php?error=wrongPass");
				}
			}else{
				header("Location: ../login.php?error=wrongUser");
			}
		}else{
			die("Error");
		}	
	}	