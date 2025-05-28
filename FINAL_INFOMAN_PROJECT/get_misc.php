<?php

include 'database.php';

$stmt = $conn -> prepare("SELECT * FROM products WHERE product_category = 'miscellaneous'");

$stmt -> execute();

$misc_products = $stmt -> get_result(); // array of products

?>