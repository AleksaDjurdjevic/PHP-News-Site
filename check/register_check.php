<?php
	if(isset($_POST['regSubmit'])){
		require "conn.php";
		
		$username = htmlentities($_POST['UsrName']);
		$email = htmlentities($_POST['UsrEmail']);
		$pass = htmlentities($_POST['UsrPass']);
		$repeatpass = htmlentities($_POST['UsrPass2']);
		
		// Validation of the fields and handling on register.php
		if(empty($username)){
			header("Location: ../register.php?error=noUser");
			exit();
		}
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			header("Location: ../register.php?error=InvEmail");
			exit();
		}
		if(empty($pass)){
			header("Location: ../register.php?error=noPass");
			exit();
		}
		if(empty($repeatpass)){
			header("Location: ../register.php?error=noRepPass");
			exit();
		}
		if($repeatpass!==$pass){
			header("Location: ../register.php?error=noPassMatch");
			exit();
		}
		
		$query = "INSERT INTO users(username, password, email) VALUES (?, ?, ?);";
		$stmt = mysqli_prepare($conn, $query);
		$prepared_pass = password_hash($pass, PASSWORD_DEFAULT);
		mysqli_stmt_bind_param($stmt, "sss", $username, $prepared_pass, $email);
		mysqli_stmt_execute($stmt);
		
		header("Location: ../register.php?register=success");
	
		
		
	}	