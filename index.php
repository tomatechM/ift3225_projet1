<?php
/*
 * Ce fichier contient du code inspiré de ressources en ligne :
 * - Notes de cours IFT3225
 * - Démos du cours IFT3225
 * - Tutoriels divers sur l'authentification PHP (StackOverflow, ChatGPT, etc.)
 */
session_start();

$is_authenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Accueil</title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="index-page">
	<div class="index-container">
		<?php if ($is_authenticated): ?>
			<!-- Dashboard pour utilisateur authentifié -->
			<div class="dashboard">
				<?php if ($_SESSION['admin']) {
				echo '<h1>Bienvenue, Admin</h1>';
				} else {
				echo '<h1>Bienvenue</h1>';
				}
				?>
				<p>Utilisateur ID: <?= htmlspecialchars($_SESSION['user_id']) ?></p>
				
				<div class="button-group">
					<a href="listings.php" class="btn-login">Mes annonces</a>
					<a href="all_listings.php" class="btn-login">Annonces</a>
					<a href="my_offers.php" class="btn-offers">Mes offres</a>
					<a href="offers.php" class="btn-offers">Offres</a>
					<form action="logout.php" method="post" style="flex: 1;">
						<button type="submit" class="btn-logout">Déconnexion</button>
					</form>
				</div>
			</div>
		<?php else: ?>
			<!-- Page d'accueil pour visiteur non authentifié -->
			<h1>Bienvenue</h1>
			<p>Connectez-vous pour accéder à vos annonces ou créez un compte.</p>
			
			<div class="button-group">
				<a href="login.php" class="btn-login">Se connecter</a>
				<a href="signup.php" class="btn-signup">S'inscrire</a>
			</div>
		<?php endif; ?>
	</div>
</body>
</html>
