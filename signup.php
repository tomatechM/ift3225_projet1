<?php
session_start();
include 'includes/db_connect.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sign up</title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
	<form action="" method="post">

	<input name="username" placeholder="Username" type="text">
	<input name="email" placeholder="Email" type="email">
	<input name="password" placeholder="Password" type="password">
	<input name="password2" placeholder="Password again" type="password">
	<button type="submit">Sign up</button>

	</form>

	<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		foreach ($_POST as $in) {
			if (empty($in)) {echo "<p>" . $in . " required</p>";}
		}
		$new_username = $_POST["username"];
		$new_email = $_POST["email"];
		$new_password = $_POST["password"];
		$new_password2 = $_POST["password2"];
		if ($new_password != $new_password2) {
			echo "<p>Passwords do not match.</p>";
		} else {
				$hashed = password_hash($new_password, PASSWORD_DEFAULT);
				$req = $conn->prepare("INSERT INTO Users (username, email, hashed_password) VALUES (:username, :email, :password);");
				try {
					if ($req->execute(['username' => $new_username, 'email' => $new_email, 'password' => $hashed])) {
						$_SESSION['authenticated'] = true;
						$_SESSION['username'] = $new_username;
						header("Location: index.php");
						exit;
					}
				} catch (PDOException $e) {
					if ($e->getCode() === '23000') {
						echo "Username or email already in use.";
					} else {
						echo $e->getCode();
					}
				}
		}
	}

	?>

</body>
</html>
