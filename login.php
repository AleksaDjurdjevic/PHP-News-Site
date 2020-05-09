<?php

require "config.php";
$token =  md5(time().microtime().uniqid()); 
$_SESSION['token'] = $token;
html_header();
$paragraph_msg = "";

if(isset($_GET['error'])){
	$error = $_GET['error'];
	switch($error){
		case "noUser": $paragraph_msg = "Please enter your username."; break;
		case "noPass": $paragraph_msg = "Please enter your password."; break;
		case "wrongUser": $paragraph_msg = "User doesn't exist."; break;
		case "wrongPass": $paragraph_msg = "Wrong password."; break;
		default: $paragraph_msg = "Something went wrong, please try again."; break;
	}
}

if(isset($_GET['login'])){
	$paragraph_msg = "You have successfully created an account.";
}
?>
	
<main id = "logreg">
	<form class = "form" action = "check/login_check.php" method = "post">
		<input type = "hidden" name = "token" value = "<?php echo $token; ?>">
		<input type = "text" name = "UsrName" placeholder = "Username / E-mail"><br>
		<input type = "password" name = "UsrPass" placeholder = "Password"><br>
		<button type = "submit" name = "loginSubmit">Login</button>
	</form>
	<a href = "register.php">Register</a>
	
	<p> <?php echo $paragraph_msg; ?> </p>
</main>

<?php

html_footer();

?>