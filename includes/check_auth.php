
<?php
/*
 * Ce fichier contient du code inspiré de ressources en ligne :
 * - Notes de cours IFT3225
 * - Démos du cours IFT3225
 * - Tutoriels divers sur l'authentification PHP (StackOverflow, ChatGPT, etc.)
 */

session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
	session_unset();
	session_destroy();
	header("Location: login.php");
	exit;
}

?>
