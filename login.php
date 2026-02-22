
<?php
/*
 * Ce fichier contient du code inspiré de ressources en ligne :
 * - Notes de cours IFT3225
 * - Démos du cours IFT3225
 * - Tutoriels divers sur l'authentification PHP (StackOverflow, ChatGPT, etc.)
 */
session_start();
require 'includes/db_connect.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$user_username = $_POST["username"];
	$user_password = $_POST["password"];

	if (!empty($user_password) && !empty($user_username)) {
		try {
			$req = $conn->prepare("SELECT user_id, username, email, hashed_password FROM Users WHERE username = :username or email = :username2");
			$req->execute(['username' => $user_username, 'username2' => $user_username]);
			$user = $req->fetch();

		} catch (PDOException $e) {
			echo $e;
		}	

		if (password_verify($user_password, $user['hashed_password'])) {
			$_SESSION['authenticated'] = true;
			$_SESSION['user_id'] = $user['user_id'];
			header("Location: index.php");
			exit;
		} else {
			echo "Invalid login credentials";
		}
	} else {
		echo "Enter your username and password";
	}
}		
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>


<div class="login-page">

	<h2>Connexion</h2>

	<form action="" method="POST">

		<input name="username" placeholder="Username" type="text">
		<input name="password" placeholder="Password" type="password">
		<div class="button-group">
			<button type="submit" class="btn-login">Se connecter</button>
			<a href="signup.php" class="btn-login">S'inscrire</a>
		</div>
		
	</form>
</div>
</html>
