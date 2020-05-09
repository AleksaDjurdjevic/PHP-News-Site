<?php

	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "";
	$dbname = "web_aplikacija";
	
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	if (!mysqli_connect_errno($conn)==0){
		die("Couldn't connect: " . mysqli_connect_error());
	}