<?php

function SQL_GET_OFFERS_BY_PRODUCT_ID($conn, $id) {
	$req = $conn->prepare("SELECT * FROM Offers WHERE product_id = :id");
	$req->execute(['id' => $id]);
	return $req->fetchAll();
}

function SQL_GET_USER_BY_OFFER_ID($conn, $offer_id) {
	// Retourne l'utilisateur qui a créé l'offre
	$req = $conn->prepare(
		"SELECT u.user_id, u.username FROM Offers o JOIN Users u ON o.user_id = u.user_id WHERE o.id = :id"
	);
	$req->execute(['id' => $offer_id]);
	return $req->fetch();
}

function SQL_GET_PRODUCT_BY_OFFER_ID($conn, $offer_id) {
	// Retourne le produit proposé en échange (offer_id référence Products.id)
	$req = $conn->prepare(
		"SELECT p.* FROM Offers o JOIN Products p ON o.offer_id = p.id WHERE o.id = :id"
	);
	$req->execute(['id' => $offer_id]);
	return $req->fetch();
}

?>