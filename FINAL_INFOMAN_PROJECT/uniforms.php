<?php

session_start();
if(!isset($_SESSION["user"])){
  header("Location: login.php"); // if no account is logged in, redirect to login.php
  die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type = "image/x-icon" href="nuexlogo.png">
    <link rel="stylesheet" href="UNIFstyle.css">
    <title>Uniforms</title>
    <style>
        .logout a {
            color: white;
            text-decoration: none; /* optional, removes underline */
        }
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
            <div class="profile"><a href = "profile.php"><img src="homeimages/prof.png" href ="profile.php"></a></div>
            <div class = "logout" id = "logout"><a alt="logout" href = "logout.php">LOGOUT</a></div>
        </div>
    </header>

    <main>
        <h1>UNIFORMS</h1>
        <div class="product-grid">
            <div class="product-grid">

                <!--fetch products data from the database -->
                <?php include ('get_uniforms.php'); ?>

                <?php while($row = $uniform_products -> fetch_assoc()) { ?>
                    <a href="single_product.php?product_id=<?php echo $row['product_id']; ?>" class="product-card no-underline">
                        <div class="product-img">   
                            <img src="productsimages/UNIFORMS/<?php echo $row['product_image1'];?>" alt="Product Image">
                        </div>
                        <div class="product-info">
                            <h2><?php echo $row['product_name']; ?></h2>
                            <p>₱<?php echo $row['product_price']; ?></p>
                        </div>
                    </a>
                <?php } ?>

            </div>
        </div>
    </main>
</body>
</html>