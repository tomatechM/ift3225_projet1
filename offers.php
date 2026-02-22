
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
require 'actions/offers_action.php';

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 15;
$offset = ($page - 1) * $per_page;


$countStmt = $conn->prepare('SELECT COUNT(*) FROM Offers WHERE user_id != :uid');
$countStmt->execute(['uid' => $_SESSION['user_id']]);
$total_items = (int)$countStmt->fetchColumn();
$total_pages = max(1, (int)ceil($total_items / $per_page));

// Si un id de produit est fourni -> afficher les offres pour ce produit
if (isset($_GET['id'])) {
	$product_id = (int) $_GET['id'];
	$product_name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
	$offers = SQL_GET_OFFERS_BY_PRODUCT_ID($conn, $product_id);
	$mode = 'by_product';
} else {
	// Liste globale des offres (exclut les offres créées par l'utilisateur connecté)
	$current_user = $_SESSION['user_id'] ?? 0;
	$stmt = $conn->prepare(
		"SELECT o.*, u.username, p_target.name AS target_name, p_offer.name AS offer_name
		 FROM Offers o
		 JOIN Users u ON o.user_id = u.user_id
		 LEFT JOIN Products p_target ON o.product_id = p_target.id
		 LEFT JOIN Products p_offer ON o.offer_id = p_offer.id
		 WHERE o.user_id != :uid
		 ORDER BY o.id DESC
		 LIMIT :limit
		 OFFSET :offset"
	);
	$stmt->execute(['uid' => $current_user, 'limit' => $per_page, 'offset' => $offset]);
	$offers = $stmt->fetchAll();
	$mode = 'global';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        $offer_id = $_POST['id'] ?? '';
        if ($action == "approve") {
                $res = SQL_APPROVE_BY_OFFER_ID($conn, $offer_id);
                $res['message'] = "Approbation enregistre";
        } elseif ($action == "reject") {
                $res = SQL_REJECT_BY_OFFER_ID($conn, $offer_id);
                $res['message'] = "Rejection enregistre";
        } elseif ($action == "pend") {
                $res = SQL_SET_PENDING_BY_OFFER_ID($conn, $offer_id);
                $res['message'] = "En attente";
        } elseif ($action == "delete") {
                $res = SQL_DELETE_BY_OFFER_ID($conn, $offer_id);
                $res['message'] = "Supprime";
        } else {
                $res['message'] = "Erreur";
        }
        header('Location: ' . $_SERVER['REQUEST_URI']);
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="assets/css/style.css">
	<style>
		.offer-item { background: white; padding: 16px; border-radius: 8px; margin-bottom: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);} 
		.offer-header { display:flex; justify-content:space-between; align-items:center; gap:12px; }
		.offer-meta { color:#666; font-size:0.95em; }
	</style>
</head>
<body>
	<div class="container">
		<div class="back-link"><a href="index.php">← Retour</a></div>

		<?php if ($mode === 'by_product'): ?>
			<h1>Offres pour « <?= htmlspecialchars($product_name) ?> »</h1>

			<?php if (empty($offers)): ?>
				<div class="empty-state">Aucune offre pour ce produit pour l'instant.</div>
			<?php else: ?>
				<?php foreach ($offers as $offer):
					$user = SQL_GET_USER_BY_OFFER_ID($conn, $offer['id']);
					$offered_product = SQL_GET_PRODUCT_BY_OFFER_ID($conn, $offer['id']);
				?>
					<div class="offer-item">
						<div class="offer-header">
							<div>
								<strong><?= htmlspecialchars($user['username'] ?? 'Utilisateur inconnu') ?></strong>
								<div class="offer-meta">Quantité proposée: <?= (int) $offer['product_quantity'] ?></div>
							</div>
                                                        <?php
                                                        $approveValue = ($offer['status'] == "Accepted") ? "pend" : "approve";
                                                        $rejectValue = ($offer['status'] == "Rejected") ? "pend" : "reject";
                                                        $approveText = ($offer['status'] == "Accepted") ? "Undo Accepter" : "Accepter";
                                                        $rejectText = ($offer['status'] == "Rejected") ? "Undo Rejeter" : "Rejeter";
                                                        $approveFunc = "";//($offer['status'] == "Accepted") ? "setAcceptPending(this)" : "acceptOffer(this)";
                                                        $rejectFunc = "";//($offer['status'] == "Rejected") ? "setRejectPending(this)" : "rejectOffer(this)";
                                                        ?>
                                                        <?php if ($offer['user_id'] == $_SESSION['user_id']): ?>
                                                        <form method="POST" class="approveForm">
                                                                <input type="hidden" name="action" value="<?= $approveValue ?>">
                                                                <input type="hidden" name="id" value="<?= $offer['id'] ?>">
                                                                <button type="submit" onclick="<?= $approveFunc ?>" class="btn-submit"><?= $approveText ?></button>
                                                        </form>
                                                        <form method="POST" class="rejectForm">
                                                                <input type="hidden" name="action" value="<?= $rejectValue ?>">
                                                                <input type="hidden" name="id" value="<?= $offer['id'] ?>">
                                                                <button type="submit" onclick="<?= $rejectFunc ?>" class="btn-submit"><?= $rejectText ?></button>
                                                        </form>
                                                        <?php endif; ?>
                                                        <?php if ($_SESSION['admin']): ?>
                                                        <form method="POST" class="deleteForm">
                                                                <input type="hidden" name="action" value="delete">
                                                                <input type="hidden" name="id" value="<?= $offer['id'] ?>">
                                                                <button type="submit" class="btn-submit">Delete</button>
							</form>
							<?php endif; ?>
                                                        <div style="text-align:right">
                                                                <div style="color:#667eea;font-weight:bold;">En échange</div>
                                                                <div style="color:#333;font-weight:bold;"><?= htmlspecialchars($offered_product['name'] ?? 'Produit supprimé') ?></div>
                                                                <div class="offer-meta">Quantité: <?= (int) $offer['offer_quantity'] ?></div>
                                                                <div class="offer-meta">Statut: <?= $offer['status'] ?></div>
                                                        </div>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

		<?php else: ?>
			<h1>Offres disponibles</h1>

			<?php if (empty($offers)): ?>
				<div class="empty-state">Aucune offre disponible pour l'instant.</div>
			<?php else: ?>
				<?php foreach ($offers as $offer): ?>
					<div class="offer-item">
						<div class="offer-header">
							<div>
								<strong><?= htmlspecialchars($offer['username'] ?? 'Utilisateur') ?></strong>
								<div class="offer-meta">Propose: <?= htmlspecialchars($offer['offer_name'] ?? '—') ?> (x<?= (int)$offer['offer_quantity'] ?>)</div>
							</div>
                                                        <?php
                                                        $approveValue = ($offer['status'] == "Accepted") ? "pend" : "approve";
                                                        $rejectValue = ($offer['status'] == "Rejected") ? "pend" : "reject";
                                                        $approveText = ($offer['status'] == "Accepted") ? "Undo Accepter" : "Accepter";
                                                        $rejectText = ($offer['status'] == "Rejected") ? "Undo Rejeter" : "Rejeter";
                                                        $approveFunc = "";//($offer['status'] == "Accepted") ? "setAcceptPending(this)" : "acceptOffer(this)";
                                                        $rejectFunc = "";//($offer['status'] == "Rejected") ? "setRejectPending(this)" : "rejectOffer(this)";
                                                        ?>
                                                        <?php if ($offer['user_id'] == $_SESSION['user_id']): ?>
                                                        <form method="POST" class="approveForm">
                                                                <input type="hidden" name="action" value="<?= $approveValue ?>">
                                                                <input type="hidden" name="id" value="<?= $offer['id'] ?>">
                                                                <button type="submit" onclick="<?= $approveFunc ?>" class="btn-submit"><?= $approveText ?></button>
                                                        </form>
                                                        <form method="POST" class="rejectForm">
                                                                <input type="hidden" name="action" value="<?= $rejectValue ?>">
                                                                <input type="hidden" name="id" value="<?= $offer['id'] ?>">
                                                                <button type="submit" onclick="<?= $rejectFunc ?>" class="btn-submit"><?= $rejectText ?></button>
                                                        </form>
                                                        <?php endif; ?>
                                                        <?php if ($_SESSION['admin']): ?>
                                                        <form method="POST" class="deleteForm">
                                                                <input type="hidden" name="action" value="delete">
                                                                <input type="hidden" name="id" value="<?= $offer['id'] ?>">
                                                                <button type="submit" class="btn-submit">Delete</button>
							</form>
							<?php endif; ?>
                                                        <div style="text-align:right">
                                                                <div style="color:#667eea;font-weight:bold;">En échange</div>
                                                                <div style="color:#333;font-weight:bold;"><?= htmlspecialchars($offered_product['name'] ?? 'Produit supprimé') ?></div>
                                                                <div class="offer-meta">Quantité: <?= (int) $offer['offer_quantity'] ?></div>
                                                                <div class="offer-meta">Statut: <?= $offer['status'] ?></div>
                                                        </div>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

		<?php endif; ?>

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
</body>
</html>
