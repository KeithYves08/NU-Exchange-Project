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
    <link rel="stylesheet" href="MPstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="icon" type = "image/x-icon" href="nuexlogo.png">
    <title>NU Exchange</title>
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
        <div class="slider" style="--width: 25rem; --height: 25rem; --quantity: 8;">   
            <div class="list">
                <!--fetch products data from the database -->
                <?php include ('get_featured_products.php'); ?>

                <?php while($row = $featured_products -> fetch_assoc()) { ?>
                    <div class="item" style="--position: 1">
                        <a href="<?php echo "single_product.php?product_id=".$row['product_id']; ?>">
                            <img src="productsimages\FEATURED\<?php echo $row['product_image1']; ?>" alt="">
                        </a>
                    </div>
                <?php } ?>

            </div>
        </div>
        
        <section class="categories">
            <h2>CATEGORIES</h2>
            <div class="category-container">
                <button class="category-button" onclick ="location.href='uniforms.php'">
                    Uniforms
                    <img src="homeimages/CATEGORY PIC = UNIFORMS.png" alt="Uniforms">
                </button>
                <button class="category-button" onclick ="location.href='merch.php'">
                    Merchandise
                    <img src="homeimages/CATEGORY PIC = MERCH.png" alt="Merchandise">
                </button>
                <button class="category-button" onclick ="location.href='Misc.php'">
                    Miscellaneous
                    <img src="homeimages/CATEGORY PIC = MISC.png" alt="Miscellaneous">
                </button>
                <button class="category-button" onclick ="location.href='allprod.php'">
                    All Products
                    <img src="homeimages/CATEGORY PIC = ALL.png" alt="All Products">
                </button>
            </div>
        </section>


        <!-- New About Section -->
        <section class="about">
            <h2>About NU EXCHANGE</h2>
            <p>Your One-Stop Shop for NU Pride</p>
            <p>NU EXCHANGE is your go-to destination for all things NU! We're dedicated to providing students with a wide range of high-quality uniforms and merchandise that showcase your school spirit.</p>
            <p>Whether you're looking for the classic uniform, trendy merchandise, or unique souvenirs, we've got you covered. Our products are designed with comfort, durability, and style in mind, ensuring you look and feel your best while representing your beloved university.</p>
            <h3>Why Choose NU EXCHANGE?</h3>
            <ul>
                <li><strong>Authentic NU Products:</strong> We source our merchandise directly from official NU suppliers, guaranteeing authenticity and quality.</li>
                <li><strong>Wide Variety:</strong> From uniforms to t-shirts, hoodies, bags, and more, we offer a diverse selection to suit your preferences.</li>
                <li><strong>Affordable Prices:</strong> We strive to provide competitive pricing without compromising on quality.</li>
                <li><strong>Excellent Customer Service:</strong> Our friendly and knowledgeable staff is always ready to assist you with your needs.</li>
            </ul>
            <p>Join the NU Community</p>
            <p>Show your NU pride and connect with fellow students by shopping at NU EXCHANGE. Visit our store today or explore our online catalog to find the perfect items to represent your university.</p>
            <p>Let's #NUTogether!</p>
            <img src = "homeimages/29214279_881930665318916_2283838787949690880_n.png" id = "nullogo">
        </section>
    </main>
</body>
</html>