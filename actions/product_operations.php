
<?php
/*
 * Ce fichier contient du code inspiré de ressources en ligne :
 * - Notes de cours IFT3225
 * - Démos du cours IFT3225
 * - Tutoriels divers sur l'authentification PHP (StackOverflow, ChatGPT, etc.)
 */
session_start();
require '../includes/db_connect.php';
require '../includes/check_auth.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';
	$user_id = $_SESSION['user_id'];
	
	try {
		switch ($action) {
			case 'add':
				$name = $_POST['name'] ?? '';
				$description = $_POST['description'] ?? '';
				
				if (empty($name)) {
					$response['message'] = 'Le nom du produit est requis';
					break;
				}
				
				$req = $conn->prepare('INSERT INTO Products (user_id, name, description) VALUES (:user_id, :name, :description)');
				$req->execute([
					'user_id' => $user_id,
					'name' => $name,
					'description' => $description
				]);
				
				$product_id = $conn->lastInsertId();
				$response['success'] = true;
				$response['message'] = 'Produit ajouté avec succès';
				$response['product_id'] = $product_id;
				break;
				
			case 'edit':
				$product_id = $_POST['product_id'] ?? '';
				$name = $_POST['name'] ?? '';
				$description = $_POST['description'] ?? '';
				
				if (empty($product_id) || empty($name)) {
					$response['message'] = 'ID du produit et nom requis';
					break;
				}
				
				// Vérifier que le produit appartient à l'utilisateur
				$check = $conn->prepare('SELECT id FROM Products WHERE id = :id AND user_id = :user_id');
				$check->execute(['id' => $product_id, 'user_id' => $user_id]);
				
				if (!$check->fetch()) {
					$response['message'] = 'Accès refusé';
					break;
				}
				
				$req = $conn->prepare('UPDATE Products SET name = :name, description = :description WHERE id = :id');
				$req->execute([
					'name' => $name,
					'description' => $description,
					'id' => $product_id
				]);
				
				$response['success'] = true;
				$response['message'] = 'Produit modifié avec succès';
				break;
				
			case 'delete':
				$product_id = $_POST['product_id'] ?? '';
				
				if (empty($product_id)) {
					$response['message'] = 'ID du produit requis';
					break;
				}
				
				// Vérifier que le produit appartient à l'utilisateur
				$check = $conn->prepare('SELECT id FROM Products WHERE id = :id AND user_id = :user_id');
				$check->execute(['id' => $product_id, 'user_id' => $user_id]);
				
				if (!$check->fetch()) {
					$response['message'] = 'Accès refusé';
					break;
				}
				
				// Supprimer les offres liées d'abord
				$conn->prepare('DELETE FROM Offers WHERE product_id = :product_id OR offer_id = :offer_id')->execute(['product_id' => $product_id, 'offer_id' => $product_id]);
				
				// Supprimer le produit
				$req = $conn->prepare('DELETE FROM Products WHERE id = :id');
				$req->execute(['id' => $product_id]);
				
				$response['success'] = true;
				$response['message'] = 'Produit supprimé avec succès';
				break;
				
			default:
				$response['message'] = 'Action non reconnue';
		}
	} catch (PDOException $e) {
		$response['message'] = 'Erreur base de données: ' . $e->getMessage();
	}
}

echo json_encode($response);
?>
