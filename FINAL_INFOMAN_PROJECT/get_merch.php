<?php

include 'database.php';

$stmt = $conn -> prepare("SELECT * FROM products WHERE product_category = 'merchandise' ORDER BY product_name ASC");

$stmt -> execute();

$merch_products = $stmt -> get_result(); // array of products

?>