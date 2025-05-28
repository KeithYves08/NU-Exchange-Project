<?php
session_start();
if(!isset($_SESSION["user"])){
  header("Location: login.php"); // if no account is logged in, redirect to login.php
  die();
}

// Database connection
require_once "database.php";

$search_results = [];
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_name LIKE ?");
    $search_term = "%" . $search_query . "%";
    $stmt->bind_param("s", $search_term); // Bind only one variable
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="nuexlogo.png">
    <link rel="stylesheet" href="searchpage.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
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
            <div class="cart"><a href="cart.html"><img src="homeimages/cart.png"></a></div>
            <div class="profile"><a href="profile.php"><img src="homeimages/prof.png"></a></div>
            <div class="logout" id="logout"><a alt="logout" href="logout.php">LOGOUT</a></div>
        </div>
    </header>
    
    <main>
        <div class="search-results">
            <?php if (!empty($search_results)): ?>
                <h2 class="no-results">Search Results:</h2>
                <div class="product-grid">
                    <?php while ($product = array_shift($search_results)): ?>
                        <a href="single_product.php?product_id=<?php echo $product['product_id']; ?>" class="product-card no-underline">
                            <div class="product-img">
                                <img src="productsimages/ALLPRODS/<?php echo $product['product_image1']; ?>" alt="Product Image">   
                            </div>
                            <div class="product-info">
                                <h2><?php echo $product['product_name']; ?></h2>
                                <p>₱<?php echo $product['product_price']; ?></p>
                            </div>
                        </a>     
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <?php if (isset($search_query)): ?>
                    <p class="no-results">No results found for "<?php echo htmlspecialchars($search_query); ?>"</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>