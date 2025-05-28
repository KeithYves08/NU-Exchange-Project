<?php

include 'database.php';

$stmt = $conn -> prepare("SELECT * FROM products LIMIT 8");

$stmt -> execute();

$featured_products = $stmt -> get_result(); // array of products

?>