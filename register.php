<?php

require "config.php";

html_header();
$paragraph_msg = "";

if(isset($_GET['error'])){
	$error = $_GET['error'];
	
	switch($error){
		case "noUser": $paragraph_msg = "Please enter your username."; break;
		case "noPass": $paragraph_msg = "Please enter your password."; break;
		case "InvEmail": $paragraph_msg = "Please enter a valid e-mail address."; break;
		case "noRepPass": $paragraph_msg = "Please repeat your password."; break;
		case "noPassMatch": $paragraph_msg = "Your passwords didn't match. Please try again."; break;
		default: $paragraph_msg = "Something went wrong, please try again."; break;
	}
}

if(isset($_GET['register'])){
	$paragraph_msg = "You have successfully created an account.";
}
?>
	
<main id = "logreg">
	<form class = "form" action = "check/register_check.php" method = "post">
		<input type = "text" name = "UsrName" placeholder = "Username"><br>
		<input type = "text" name = "UsrEmail" placeholder = "E-mail"><br>
		<input type = "password" name = "UsrPass" placeholder = "Password"><br>
		<input type = "password" name = "UsrPass2" placeholder = "Repeat password"><br>
		<button type = "submit" name = "regSubmit">Register</button>
	</form>
	<a href = "login.php">Login</a>
	
	<p> <?php echo $paragraph_msg; ?> </p>
</main>

<?php

html_footer();

?>