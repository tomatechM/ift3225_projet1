<?php

session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
	echo 'not set or not authenticated';
	session_unset();
	session_destroy();
	header("Location: login.php");
	exit;
}

?>
