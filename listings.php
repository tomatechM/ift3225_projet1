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
<div id='listings'>
	<p>Click listing to see info</p>
<?php foreach ($products as $prod) {
	echo "<p class='listing' onclick='toggleInfo(this)'>{$prod['name']}</p>";
	echo "<div class='listing_info' style='display: none'>";
		echo "<p class='desc'>{$prod['description']}</p>";
		echo "<p class='offers'>0</p>";
	echo "</div>";
}
?>
</div>
</html>
