<?php

session_start();
require 'includes/db_connect.php';
require 'includes/check_auth.php';
require 'actions/offers_action.php';

if (isset($_GET['id']) && isset($_GET['name'])) {
	$id = htmlspecialchars($_GET['id']);
	$name = htmlspecialchars($_GET['name']);
} else {echo "not set";}
$offers = SQL_GET_OFFERS_BY_PRODUCT_ID($conn, $id);

?>
<html>
<head>
	<title>Offers</title>
</head>
<body>

<?php foreach ($offers as $offer) {
		
	$user = SQL_GET_USER_BY_OFFER_ID($conn, $offer['id']);
	$product = SQL_GET_PRODUCT_BY_OFFER_ID($conn, $offer['id']);
	echo "<p><b>{$user['username']}</b> is offering</p>";
	echo "<p><b>Product: </b>{$product['name']}</p>";
	echo "<p><b>Quantity: </b>{$offer['product_quantity']}</p>";
	echo "<p>In exchange for...</p>";
	echo "<p><b>Product: </b>$name</p>";
	echo "<p><b>Quantity: </b>{$offer['offer_quantity']}</p>";

}
?>

</body>
</html>
