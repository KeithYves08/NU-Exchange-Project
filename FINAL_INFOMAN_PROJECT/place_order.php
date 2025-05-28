<?php

session_start();
include('database.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$message = '';

if(isset($_POST['place_order'])){

    // 1. get user info and store it in database
    $country = $_POST['country'];
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $email = $_POST['postal'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $region = $_POST['region'];
    $phone = $_POST['phone'];

    $order_cost = isset($_POST['product_price']) ? $_POST['product_price'] * $_POST['product_quantity'] : $_SESSION['total'];
    $order_status = 'on_hold';  
    $order_date = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_city, user_address, order_date) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)");
                        // protect against SQL injection

    if (!$stmt) {
        $message = "Error preparing statement: " . $conn->error;
    } else {
        $stmt->bind_param("isiisss", $order_cost, $order_status, $user_id, $phone, $city, $address, $order_date);

        if (!$stmt->execute()) {
            $message = "Error executing statement: " . $stmt->error;
        } else {
            $order_id = $stmt->insert_id;

            // 2. get products from cart (from session)
            if (isset($_POST['product_id'])) {
                // Single product checkout
                $product_id = $_POST['product_id'];
                $product_name = $_POST['product_name'];
                $product_image = $_POST['product_image'];
                $product_price = $_POST['product_price'];
                $product_quantity = $_POST['product_quantity'];

                $stmt1 = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)"); // protect against SQL injection
                                
                if (!$stmt1) {
                    $message = "Error preparing statement: " . $conn->error;
                } else {
                    $stmt1->bind_param("iissiiis", $order_id, $product_id, $product_name, $product_image, $product_price, $product_quantity, $user_id, $order_date);

                    if (!$stmt1->execute()) {
                        $message = "Error executing statement: " . $stmt1->error;
                    } else {
                        // Remove the purchased item from the cart
                        unset($_SESSION['cart'][$user_id][$product_id]);
                        // Recalculate the total price
                        calculateTotalPrice();
                        $message = "Order has been placed successfully!";
                    }
                }
            } else {
                // All products checkout
                if (isset($_SESSION['cart'][$user_id]) && is_array($_SESSION['cart'][$user_id])) {
                    foreach($_SESSION['cart'][$user_id] as $key => $value){

                        $product = $_SESSION['cart'][$user_id][$key];
                        $product_id = $product['product_id'];
                        $product_name = $product['product_name'];
                        $product_image = $product['product_image'];
                        $product_price = $product['product_price'];
                        $product_quantity = $product['product_quantity'];

                        $stmt1 = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"); // protect against SQL injection
                                        
                        if (!$stmt1) {
                            $message = "Error preparing statement: " . $conn->error;
                            break;
                        }

                        $stmt1->bind_param("iissiiis", $order_id, $product_id, $product_name, $product_image, $product_price, $product_quantity, $user_id, $order_date);

                        if (!$stmt1->execute()) {
                            $message = "Error executing statement: " . $stmt1->error;
                            break;
                        }

                        // Remove the purchased item from the cart
                        unset($_SESSION['cart'][$user_id][$product_id]);
                    }

                    // Recalculate the total price
                    calculateTotalPrice();

                    if (empty($message)) {
                        $message = "Order has been placed successfully!";
                    }
                } else {
                    $message = "No products in cart.";
                }
            }
        }
    }
} else {
    $message = "There was an error placing your order.";
}

// Function to calculate total price
function calculateTotalPrice() {
    global $user_id;

    $total = 0;

    foreach ($_SESSION['cart'][$user_id] as $key => $value) {
        $product = $_SESSION['cart'][$user_id][$key];
        $price = $product['product_price'];
        $quantity = $product['product_quantity'];
        $total += $price * $quantity;
    }

    $_SESSION['total'] = $total;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>
    <link rel="stylesheet" href="place_order.css">
    <link rel="icon" type="image/x-icon" href="nuexlogo.png">
</head>
<body>
    <div class="container">
        <div class="message">
            <h1>Order Status</h1>
            <p><?php echo htmlspecialchars($message); ?></p>
            <button onclick="window.location.href='main_page.php'">Go to Main Page</button>
        </div>
    </div>
</body>
</html>