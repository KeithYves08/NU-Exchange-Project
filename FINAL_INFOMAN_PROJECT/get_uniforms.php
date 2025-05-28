<?php

include 'database.php';

$stmt = $conn -> prepare("SELECT * FROM products WHERE product_category = 'uniforms' ORDER BY product_name ASC");

$stmt -> execute();

$uniform_products = $stmt -> get_result(); // array of products

?>