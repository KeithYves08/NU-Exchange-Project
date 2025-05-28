<?php

include 'database.php';

if(isset($_GET['product_id'])){

    $product_id = $_GET['product_id'];

    $stmt = $conn -> prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt -> bind_param("i", $product_id);

    $stmt -> execute();
    
    $product = $stmt -> get_result(); // will return an array of products
}
else{
    header("Location: main_page.php"); // if no product is selected, redirect to main_page.php
}

?>

<!DOCTYPE html>
<html lang="en">

<?php while ($row = $product -> fetch_assoc()) { ?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type = "image/x-icon" href="nuexlogo.png">
  <title><?php echo $row['product_name'];?></title>
  <link rel="stylesheet" href="singleprod.css">
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
            <div class="profile"><a href = "profile.php"><img src="homeimages/prof.png" href ="profile.php"></a></div>
            <div class = "logout" id="logout"><a alt="logout" href = "logout.php">LOGOUT</a></div>
        </div>
    </header>

     

    <div class="main">
        <div class="product-images">
            <div class="slider-wrapper">
                <div class="slider">
                    <img id="slide1" src="productsimages\ALLPRODS\<?php echo $row['product_image1'];?>" alt="">
                    <img id="slide2" src="productsimages\ALLPRODS\<?php echo $row['product_image2'];?>" alt="">
                    <img id="slide3" src="/images/Size_Charts_Pants.png" alt="">
                </div>
                <div class="slider-nav">
                    <a href="#slide1"><span></span></a>
                    <a href="#slide2"><span></span></a>
                    <a href="#slide3"><span></span></a>
                </div>
            </div>
        </div>
        <div class="product-details">
            <h1><?php echo $row['product_name'];?></h1>
            <p>PHP <?php echo $row['product_price'];?></p>
            <ul>
                <li>Cotton</li>
                <li>Unisex</li>
                <li>Regular Fit</li>
                <li>Note: Actual product color may vary from the images shown</li>
            </ul>
            <p id="desc"><?php echo $row['product_description']; ?></p>
            <table class="measurements-table">
                <tr>
                  <th>MEASUREMENT</th>
                  <th>S</th>
                  <th>M</th>
                  <th>L</th>
                  <th>XL</th>
                </tr>
                <tr>
                  <td>BODY LENGTH</td>
                  <td>27</td>
                  <td>29</td>
                  <td>30.5</td>
                  <td>32</td>
                </tr>
                <tr>
                  <td>CHEST</td>
                  <td>22</td>
                  <td>24</td>
                  <td>24.5</td>
                  <td>28</td>
                </tr>
                <tr>
                  <td>SLEEVE LENGTH</td>
                  <td>22</td>
                  <td>23</td>
                  <td>24</td>
                  <td>25</td>
                </tr>
            </table>
            <div class="size-options-container">
                <h2>SIZE:</h2>
                <div class="size-options">
                    <button class="size-option">Small</button>
                    <button class="size-option">Medium</button>
                    <button class="size-option">Large</button>
                    <button class="size-option">Extra large</button>
                </div>
            </div>
            <div class="color-options">
                <h2>COLOR:</h2>
                <div class="color-option">
                    <div class="color-circle" style="background-color: #152238;"></div>
                </div>
            </div>

            <form method="POST" action="cart.php">
                <div class="quantity-add-to-cart-container">
                    <div class="quantity-selector">   
                        <input type="hidden" name="product_id" value="<?php echo $row['product_id'];?>"/>
                        <input type="hidden" name="product_image" value="<?php echo $row['product_image1'];?>"/>
                        <input type="hidden" name="product_name" value="<?php echo $row['product_name'];?>"/>
                        <input type="hidden" name="product_price" value="<?php echo $row['product_price'];?>"/>

                        <button type="button" onclick="decrementQuantity()">-</button>
                        <input type="number" id="product_quantity" name="product_quantity" value="1" min="1"/>
                        <button type="button" onclick="incrementQuantity()">+</button>
                    </div>
                    <button type="submit" class="add-to-cart" name="add_to_cart">Add to Cart</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function decrementQuantity() {
            var quantityInput = document.getElementById('product_quantity');
            var currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        }

        function incrementQuantity() {
            var quantityInput = document.getElementById('product_quantity');
            var currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
        }
    </script>
</body>

<?php } ?>

</html>