<?php

	session_start();
	unset ($_SESSION ['userID']);
	unset ($_SESSION ['userName']);
	session_destroy();
	header("Location: index.php");