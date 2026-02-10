<?php

session_start();
require 'includes/db_connect.php';
require 'includes/check_auth.php';
require 'actions/products.php';

$products = GET_PRODUCTS_BY_USER_ID($conn, $_SESSION['user_id']);

?>

<!DOCTYPE html>
<html>
<head>
	<title>My Listings</title>
	<script src="js/listings.js"></script>
</head>
<body>
<div id='listings'>
	<p>Click listing to see info</p>
<?php foreach ($products as $prod) {
	echo "<p class='listing' onclick='toggleInfo(this)'>{$prod['name']}</p>";
	echo "<div class='listing_info' style='display: none'>";
	echo "<p><b>Description:</b></p><p class='desc'>{$prod['description']}</p>";
	$url = 'offers.php?id=' . urlencode($prod['id']) . '&name=' . urlencode($prod['name']);
	echo "<p><b>Offers</b></p><p class='offers'>{$prod['offers']} <em><a href=$url>See offers</a></em></p>";
	echo "</div>";
}
?>
</div>
</body>
</html>
