<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the user ID
$user_id = $_SESSION['user_id'];

// Initialize the cart for the user if not already set
if (!isset($_SESSION['cart'][$user_id])) {
    $_SESSION['cart'][$user_id] = array();
}

if (isset($_POST['add_to_cart'])) {

    // if user has already added products to cart
    if (isset($_SESSION['cart'][$user_id])) {

        $products_array_ids = array_column($_SESSION['cart'][$user_id], "product_id");

        // if product has already been added to the cart or not
        if (!in_array($_POST['product_id'], $products_array_ids)) {

            $product_id = $_POST['product_id'];
            $product_name = $_POST['product_name'];
            $product_price = $_POST['product_price'];
            $product_image = $_POST['product_image'];
            $product_quantity = $_POST['product_quantity'];

            $product_array = array(
                'product_id' => $product_id,
                'product_name' => $product_name,
                'product_price' => $product_price,
                'product_image' => $product_image,
                'product_quantity' => $product_quantity
            );

            $_SESSION['cart'][$user_id][$product_id] = $product_array; // Store cart data using user_id
        } else {
            echo "<script>alert('Product is already added to cart!')</script>";
        }
    } else {
        // if this is the first product
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $product_quantity = $_POST['product_quantity'];

        $product_array = array(
            'product_id' => $product_id,
            'product_name' => $product_name,
            'product_price' => $product_price,
            'product_image' => $product_image,
            'product_quantity' => $product_quantity
        );

        $_SESSION['cart'][$user_id][$product_id] = $product_array; // Store cart data using user_id
    }

    // calculate total price
    calculateTotalPrice();
} else if (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$user_id][$product_id]);

    // calculate total price
    calculateTotalPrice();
} else if (isset($_POST['edit_quantity'])) {
    // we get id and quantity from the form
    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['quantities'][$product_id];

    // we get the product array from the session
    $product_array = $_SESSION['cart'][$user_id][$product_id];

    // we update the quantity
    $product_array['product_quantity'] = $product_quantity;

    // we update the session with the new product array
    $_SESSION['cart'][$user_id][$product_id] = $product_array;

    // calculate total price
    calculateTotalPrice();
} else if (isset($_POST['indiv_checkout'])) {
    // Redirect to checkout.php with specific product details
    $product_id = $_POST['product_id'];
    header("Location: checkout.php?product_id=$product_id");
    exit();
} else if (isset($_POST['checkout'])) {
    // Redirect to checkout.php with the total price
    header("Location: checkout.php?checkout_all=true");
    exit();
} else {
    if (!isset($_SESSION["user"])) {
        header("Location: login.php"); // if no account is logged in, redirect to login.php
        die();
    }
}

// function for calculating total price
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
    <link rel="stylesheet" href="cart.css">
    <link rel="icon" type="image/x-icon" href="nuexlogo.png">
    <title>Shopping Cart</title>
    <style>
    #logout a:hover {
        color: #ffcc00;
    }
    </style>
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

    <div class="cart-content">
        <h2>SHOPPING CART</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php 
                if(isset($_SESSION['cart'][$user_id]) && !empty($_SESSION['cart'][$user_id])) {
                    foreach($_SESSION['cart'][$user_id] as $key => $value){ 
                ?> 

                <tr>
                    <td>
                        <div style="text-align: center;">
                            <img src="productsimages/ALLPRODS/<?php echo $value['product_image']; ?>" alt="Product Image" style="width:100px; height:100px;">
                            <?php echo $value['product_name']; ?>
                        </div>
                    </td>

                    <td>₱<?php echo $value['product_price']; ?></td>
                    
                    <td>
                        <form method="POST" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>"/>
                            <input type="number" name="quantities[<?php echo $value['product_id']; ?>]" value="<?php echo $value['product_quantity']; ?>" min="1">
                            <input type="submit" class="button edit" value="Edit" name="edit_quantity"/>
                        </form>
                    </td>
                    <td>₱<?php echo $value['product_quantity'] * $value['product_price']; ?></td> 

                    <td>
                        <form method="POST" action="cart.php">      
                            <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>"/>
                            <input type="submit" name="remove_product" class="button delete" value="Remove"/>
                            <input type="submit" class="checkout-button" value="Check Out" name="indiv_checkout"/>
                        </form>        
                    </td>
                </tr>

                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
                }
                ?>

            </tbody>
        </table>
    </div>

    <div class="cart-footer">
        <div class="footer-options"></div>
        <div class="footer-total">
            <span>Total: <strong>₱<?php echo isset($_SESSION['total']) ? $_SESSION['total'] : '0'; ?></strong></span>
            <form method="POST" action="cart.php" >
                <input type="submit" class="checkout-button" value="Check Out" name="checkout"/>
            </form>
        </div>
    </div>

</body>
</html>