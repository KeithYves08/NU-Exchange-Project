<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!empty($_SESSION['cart'][$user_id]) && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $product = $_SESSION['cart'][$user_id][$product_id];
} else if (!empty($_SESSION['cart'][$user_id]) && isset($_GET['checkout_all'])) {
    $checkout_all = true;
} else {
    // send user back to cart.php
    header("Location: cart.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <link rel="icon" type="image/x-icon" href="nuexlogo.png">
  <link rel="stylesheet" href="checkouts.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <header>
        <div class="logo"><a href="main_page.php"><img src="homeimages/NUEX.png"></a></div>
        <div class="search-container">
            <form action="searchpage.php" method="GET">
                <input type="text" name="search" placeholder="Search Products" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button type="submit" class="search-button"><img src="homeimages/search-icon.png"></button>
            </form>
        </div>
        <div class="profile-cart">
            <a href="cart.php">
                <div class="cart">
                    <img src="homeimages/cart.png">
                </div>
            </a> 
            <div class="profile"><a href="profile.php"><img src="homeimages/prof.png"></a></div>
            <div class="logout" id="logout"><a alt="logout" href="logout.php">LOGOUT</a></div>
        </div>
    </header>
    <div class="container">
        <div class="left">
            <h2>Delivery</h2>
            <form action="place_order.php" method="POST">
                <label for="country">Country</label>
                <select id="country" name="country">
                    <option value="Philippines">Philippines</option>
                    <option value="USA">USA</option>
                    <option value="Japan">Japan</option>
                    <option value="Korea">Korea</option>
                </select>
                
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="firstname" placeholder="First name" required>
                
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lastname" placeholder="Last name" required>
                
                <label for="postal">Email</label>
                <input type="text" id="postal" name="postal" placeholder="Email" required>
                
                <div class="flex-container">
                    <div>
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" placeholder="Address (Include your Barangay)" required>
                    </div>
                    <div>
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" placeholder="City" required>
                    </div>
                </div>
            
                <label for="region">Region</label>
                <select id="region" name="region">
                    <option value="Region1">Region I</option>
                    <option value="Region2">Region II</option>
                    <option value="Region3">Region III</option>
                    <option value="Region4A">Region IV-A (Calabarzon)</option>
                    <option value="Region4B">Region IV-B (Mimaropa)</option>
                    <option value="Region5">Region V</option>
                    <option value="Region6">CAR (Cordillera Administrative Region)</option>
                    <option value="Region7">NCR (National Capital Region)</option>
                </select> 
                
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Phone Number" required>
                
                <div class="payment-method">
                    <h2>Payment Method</h2>
                    <div>
                        <input type="radio" id="cod" name="shipping" value="COD">
                        <label for="cod">Cash on Delivery</label>
                    </div>
                    <div>
                        <input type="radio" id="credit" name="shipping" value="Credit Card">
                        <label for="credit">Credit Card</label>
                    </div>
                    <div>
                        <input type="radio" id="debit" name="shipping" value="Debit Card">
                        <label for="debit">Bank Transfer</label>
                    </div>
                    <div>
                        <input type="radio" id="gcash" name="shipping" value="Gcash">
                        <label for="gcash">Gcash</label>
                    </div>
                </div>

                <?php if(isset($product)): ?>
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $product['product_name']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $product['product_image']; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $product['product_price']; ?>">
                    <input type="hidden" name="product_quantity" value="<?php echo $product['product_quantity']; ?>">
                <?php endif; ?>

                <input type="submit" id="checkout" name="place_order" value="Place Order">
            </form>
        </div>
        <div class="right">
            <h2>Order Summary</h2>
            
            <?php if(isset($product)): ?>
                <div class="order-item">
                    <p><?php echo $product['product_name']; ?></p>
                    <p>₱<?php echo number_format($product['product_price'] * $product['product_quantity'], 2); ?></p>
                </div>
                <hr>
                <div class="total">
                    <p><strong>Total</strong></p>
                    <p><strong>₱<?php echo number_format($product['product_price'] * $product['product_quantity'], 2); ?></strong></p>
                </div>
            <?php elseif(isset($checkout_all)): ?>
                <?php foreach($_SESSION['cart'][$user_id] as $key => $value): ?>
                    <div class="order-item">
                        <p><?php echo $value['product_name']; ?></p>
                        <p>₱<?php echo number_format($value['product_price'] * $value['product_quantity'], 2); ?></p>
                    </div>
                <?php endforeach; ?>
                <hr>
                <div class="total">
                    <p><strong>Total</strong></p>
                    <p><strong>₱<?php echo number_format($_SESSION['total'], 2); ?></strong></p>
                </div>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>