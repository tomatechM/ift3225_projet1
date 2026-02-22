
<?php
/*
 * Ce fichier contient du code inspiré de ressources en ligne :
 * - Notes de cours IFT3225
 * - Démos du cours IFT3225
 * - Tutoriels divers sur l'authentification PHP (StackOverflow, ChatGPT, etc.)
 */
session_start();
require 'includes/db_connect.php';
require 'includes/check_auth.php';
require 'actions/products.php';

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 15;
$offset = ($page - 1) * $per_page;


$countStmt = $conn->prepare('SELECT COUNT(*) FROM Products');
$countStmt->execute();
$total_items = (int)$countStmt->fetchColumn();
$total_pages = max(1, (int)ceil($total_items / $per_page));	

$stmt = $conn->prepare('SELECT * FROM Products ORDER BY id DESC LIMIT :limit OFFSET :offset');
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
	<title>Listings</title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<div class="container">
		<div class="back-link">
			<a href="index.php">← Retour à l'accueil</a>
		</div>

		<div class="header">
			<h1>Annonces</h1>
			<?php if ($_SESSION['admin']): ?>
			<button class="btn-add" onclick="openAddModal()">+ Ajouter un produit</button>
			<?php endif; ?>
		</div>

		<div style="margin-bottom: 20px;">
			<input type="text" id="searchBar" placeholder="Rechercher un produit..." style="width: 100%; max-width: 400px; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
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
								<?php if ($_SESSION['admin'] || ($prod['user_id'] == $_SESSION['user_id'])): ?>
								<button class="btn-edit" onclick="openEditModal(<?= $prod['id'] ?>, '<?= addslashes($prod['name']) ?>', '<?= addslashes($prod['description'] ?? '') ?>')">Éditer</button>
								<?php endif; ?>
								<button class="btn-offers" onclick="location.href='offers.php?id=<?= $prod['id'] ?>&name=<?= rawurlencode($prod['name']) ?>'">Offres</button>
								<?php if ($_SESSION['admin'] || ($prod['user_id'] == $_SESSION['user_id'])): ?>
								<button class="btn-delete" onclick="deleteProduct(<?= $prod['id'] ?>)">Supprimer</button>
								<?php endif; ?>
							</div>
						</div>
						<div class="product-description"><?= htmlspecialchars($prod['description'] ?? 'Pas de description') ?></div>
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
<?php if ($_SESSION['admin']): ?>
	<div id="addModal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<h2>Ajouter un produit</h2>
			</div>
			<form id="addForm">
				<input type="hidden" name="action" value="admin_add">
				<div class="form-group">
					<label for="addId">ID de l'usager:</label>
					<input type="text" id="addId" name="user_id" required>
				</div>
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
<?php endif; ?>
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
	<script>
	// Recherche asynchrone
	const searchBar = document.getElementById('searchBar');
	const listingsDiv = document.getElementById('listings');
	let lastSearch = '';

	if (searchBar) {
		searchBar.addEventListener('input', async function() {
			const query = this.value.trim();
			lastSearch = query;
			if (query.length === 0) {
				// Afficher tous les produits (reload page ou restaurer initial)
				window.location.reload();
				return;
			}
			try {
				const resp = await fetch('searchBarAll.php?q=' + encodeURIComponent(query));
				if (!resp.ok) return;
				const products = await resp.json();
				// Affichage dynamique
				let html = '';
				if (products.length === 0) {
					html = `<div class="empty-state"><p>Aucun produit trouvé.</p></div>`;
				} else {
					for (const prod of products) {
						html += `<div class="product-card" data-product-id="${prod.id}">
							<div class="product-header">
								<div class="product-name">${escapeHtml(prod.name)}</div>
								<div class="product-actions">
									<button class=\"btn-edit\" onclick=\"openEditModal(${prod.id}, '${jsEscape(prod.name)}', '${jsEscape(prod.description ?? '')}')\">Éditer</button>
									<button class=\"btn-offers\" onclick=\"location.href='offers.php?id=${prod.id}&name=${encodeURIComponent(prod.name)}'\">Offres</button>
									<button class=\"btn-delete\" onclick=\"deleteProduct(${prod.id})\">Supprimer</button>
								</div>
							</div>
							<div class="product-description">${escapeHtml(prod.description ?? 'Pas de description')}</div>
							<div class="product-offers">Offres: ${prod.offers}</div>
						</div>`;
					}
				}
				listingsDiv.innerHTML = html;
			} catch (e) {
				listingsDiv.innerHTML = `<div class='empty-state'><p>Erreur lors de la recherche.</p></div>`;
			}
		});
	}

	// Fonctions utilitaires pour échapper le HTML/JS
	function escapeHtml(text) {
		if (!text) return '';
		return text.replace(/[&<>"']/g, function(m) {
			return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[m];
		});
	}
	function jsEscape(str) {
		if (!str) return '';
		return str.replace(/'/g, "\\'").replace(/\n/g, '\\n').replace(/\r/g, '');
	}
	</script>
</body>
</html>
