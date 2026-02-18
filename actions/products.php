<?php
/*
 * Ce fichier contient du code inspiré de ressources en ligne :
 * - Notes de cours IFT3225
 * - Démos du cours IFT3225
 * - Tutoriels divers sur l'authentification PHP (StackOverflow, ChatGPT, etc.)
 */
function GET_PRODUCTS_BY_USER_ID($conn, $id) {

	$req = $conn->prepare('SELECT * FROM Products WHERE user_id = :user_id');
	$req->execute(['user_id' => $id]);
	return $req->fetchAll();
}

?>
