<?php
	
	require "config.php";
	
	html_header();
	
	// Writing "welcome" after login
	if(isset($_GET['login'])){
		if($_GET['login']=='success'){
			echo "<p class = 'news'><em>Welcome {$_SESSION['userName']}</em></p>";
		}
	}else{
		echo "<p class = 'news'><em>All PHP news in one place</em></p>";
	}
	
	html_footer();		
?>