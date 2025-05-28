<?php

include 'database.php';

$stmt = $conn -> prepare("SELECT * FROM products ORDER BY product_name ASC");

$stmt -> execute();

$all_products = $stmt -> get_result(); // array of products

?>