<?php

session_start();
require 'includes/db_connect.php';
require 'includes/check_auth.php';
require 'actions/products.php';

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 15;
$offset = ($page - 1) * $per_page;


$countStmt = $conn->prepare('SELECT COUNT(*) FROM Products WHERE user_id = :uid');
$countStmt->execute(['uid' => $_SESSION['user_id']]);
$total_items = (int)$countStmt->fetchColumn();
$total_pages = max(1, (int)ceil($total_items / $per_page));

$stmt = $conn->prepare('SELECT * FROM Products WHERE user_id = :uid ORDER BY id DESC LIMIT :limit OFFSET :offset');
$stmt->bindValue(':uid', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>My Listings</title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
	<div class="container">
		<div class="back-link">
			<a href="index.php">← Retour à l'accueil</a>
		</div>

		<div class="header">
			<h1>Mes annonces</h1>
			<button class="btn-add" onclick="openAddModal()">+ Ajouter un produit</button>
		</div>

		<div id="alert" class="alert"></div>

		<div id="listings">
			<?php if (empty($products)): ?>
				<div class="empty-state">
					<p>Vous n'avez pas encore d'annonces. Cliquez sur "Ajouter un produit" pour en créer une.</p>
				</div>
			<?php else: ?>
				<?php foreach ($products as $prod): ?>
					<div class="product-card" data-product-id="<?= $prod['id'] ?>">
						<div class="product-header">
							<div class="product-name"><?= htmlspecialchars($prod['name']) ?></div>
							<div class="product-actions">
								<button class="btn-edit" onclick="openEditModal(<?= $prod['id'] ?>, '<?= addslashes($prod['name']) ?>', '<?= addslashes($prod['description'] ?? '') ?>')">Éditer</button>
								<button class="btn-offers" onclick="location.href='offers.php?id=<?= $prod['id'] ?>&name=<?= rawurlencode($prod['name']) ?>'">Offres</button>
								<button class="btn-delete" onclick="deleteProduct(<?= $prod['id'] ?>)">Supprimer</button>
							</div>
						</div>
						<div class="product-description"><?= htmlspecialchars($prod['description'] ?? 'Pas de description') ?></div>
						<div class="product-offers">Offres: <?= $prod['offers'] ?></div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>

	<?php if ($total_pages > 1): ?>
		<div class="pagination" aria-label="Pagination">
			<?php if ($page > 1): ?>
				<a href="?page=<?= $page - 1 ?>" class="pagination-btn">← Précédent</a>
			<?php else: ?>
				<span class="pagination-btn disabled">← Précédent</span>
			<?php endif; ?>

			<span class="pagination-info">Page <?= $page ?> / <?= $total_pages ?></span>

			<?php if ($page < $total_pages): ?>
				<a href="?page=<?= $page + 1 ?>" class="pagination-btn">Suivant →</a>
			<?php else: ?>
				<span class="pagination-btn disabled">Suivant →</span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<div id="addModal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<h2>Ajouter un produit</h2>
			</div>
			<form id="addForm">
				<input type="hidden" name="action" value="add">
				<div class="form-group">
					<label for="addName">Nom du produit:</label>
					<input type="text" id="addName" name="name" required>
				</div>
				<div class="form-group">
					<label for="addDescription">Description:</label>
					<textarea id="addDescription" name="description"></textarea>
				</div>
				<div class="modal-actions">
					<button type="button" class="btn-cancel" onclick="closeAddModal()">Annuler</button>
					<button type="submit" class="btn-submit">Ajouter</button>
				</div>
			</form>
		</div>
	</div>

	<div id="editModal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<h2>Éditer le produit</h2>
			</div>
			<form id="editForm">
				<input type="hidden" name="action" value="edit">
				<input type="hidden" id="editProductId" name="product_id">
				<div class="form-group">
					<label for="editName">Nom du produit:</label>
					<input type="text" id="editName" name="name" required>
				</div>
				<div class="form-group">
					<label for="editDescription">Description:</label>
					<textarea id="editDescription" name="description"></textarea>
				</div>
				<div class="modal-actions">
					<button type="button" class="btn-cancel" onclick="closeEditModal()">Annuler</button>
					<button type="submit" class="btn-submit">Mettre à jour</button>
				</div>
			</form>
		</div>
	</div>

	<script src="js/listings.js"></script>
</body>
</html>
