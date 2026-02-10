<?php

function SQL_GET_OFFERS_BY_PRODUCT_ID($conn, $id) {

	$req = $conn->prepare("SELECT * FROM Offers WHERE product_id = :id");
	$req->execute(['id' => $id]);
	return $req->fetchAll();
}

function SQL_GET_USER_BY_OFFER_ID($conn, $id) {

	$req = $conn->prepare("SELECT UNIQUE u.username FROM Offers o JOIN Users u ON o.user_id = u.user_id WHERE o.id = :id");
	$req->execute(['id' => $id]);
	return $req->fetch();
}

function SQL_GET_PRODUCT_BY_OFFER_ID($conn, $id) {

	$req = $conn->prepare("SELECT * FROM Products WHERE id = :id");
	$req->execute(['id' => $id]);
	return $req->fetch();
}

?>
