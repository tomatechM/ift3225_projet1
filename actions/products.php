<?php

function GET_PRODUCTS_BY_USER_ID($conn, $id) {

	$req = $conn->prepare('SELECT * FROM Products WHERE user_id = :user_id');
	$req->execute(['user_id' => $id]);
	return $req->fetchAll();
}

?>
