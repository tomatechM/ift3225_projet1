<?php
session_start();
session_unset();
require 'includes/db_connect.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
	<form action="" method="post">

	<input name="username" placeholder="Username" type="text">
	<input name="password" placeholder="Password" type="password">
	<button type="submit">Login</button>

	</form>

	<?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
			echo "All fields required";
		}		
	}
?>
</body>
</html>
