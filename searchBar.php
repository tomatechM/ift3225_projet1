
<?php
/*
 * Ce fichier contient du code inspiré de ressources en ligne :
 * - Notes de cours IFT3225
 * - Démos du cours IFT3225
 * - Tutoriels divers sur l'authentification PHP (StackOverflow, ChatGPT, etc.)
 */
session_start();
include 'includes/db_connect.php';
require_once 'actions/products.php';

header('Content-Type: application/json');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$user_id = $_SESSION['user_id'] ?? null;

$result = [];
if ($user_id && strlen($q) > 0) {
    $stmt = $conn->prepare('SELECT * FROM Products WHERE user_id = :uid AND name LIKE :q ORDER BY id DESC LIMIT 20');
    $stmt->execute([
        'uid' => $user_id,
        'q' => $q . '%'
    ]);
    $result = $stmt->fetchAll();
}

echo json_encode($result);
exit;