<?php

session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
	session_unset();
	session_destroy();
	header("Location: login.php");
	exit;
}

?>
