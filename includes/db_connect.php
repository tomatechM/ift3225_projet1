
<?php
/*
 * Ce fichier contient du code inspiré de ressources en ligne :
 * - Notes de cours IFT3225
 * - Démos du cours IFT3225
 * - Tutoriels divers sur l'authentification PHP (StackOverflow, ChatGPT, etc.)
 */
require_once __DIR__ . '/../db_credentials.php';

$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
$host = '127.0.0.1';
$dbname = 'joffre';
$username = 'snig';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
	$conn = new PDO($dsn, DB_USER, DB_PASS, $options);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	die("Connection failed: " . $e->getMessage());
}
?>
