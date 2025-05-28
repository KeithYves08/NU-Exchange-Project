<?php
session_start();
include('database.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$sql = "SELECT username, first_name, last_name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch address from the orders table
$sql_address = "SELECT user_address FROM orders WHERE user_id = ? LIMIT 1";
$stmt_address = $conn->prepare($sql_address);
$stmt_address->bind_param("i", $user_id);
$stmt_address->execute();
$result_address = $stmt_address->get_result();
$address = $result_address->fetch_assoc();

// Fetch completed purchases from the order_items table
$sql_purchases = "SELECT product_name, product_price, product_quantity, product_image, order_date FROM order_items WHERE user_id = ?";
$stmt_purchases = $conn->prepare($sql_purchases);
$stmt_purchases->bind_param("i", $user_id);
$stmt_purchases->execute();
$result_purchases = $stmt_purchases->get_result();
$purchases = $result_purchases->fetch_all(MYSQLI_ASSOC);

// Pagination logic
$items_per_page = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Fetch completed purchases from the order_items table with pagination
$sql_purchases = "SELECT product_name, product_price, product_quantity, product_image, order_date FROM order_items WHERE user_id = ? LIMIT ? OFFSET ?";
$stmt_purchases = $conn->prepare($sql_purchases);
$stmt_purchases->bind_param("iii", $user_id, $items_per_page, $offset);
$stmt_purchases->execute();
$result_purchases = $stmt_purchases->get_result();
$purchases = $result_purchases->fetch_all(MYSQLI_ASSOC);

// Fetch total number of purchases for pagination
$sql_total_purchases = "SELECT COUNT(*) as total FROM order_items WHERE user_id = ?";
$stmt_total_purchases = $conn->prepare($sql_total_purchases);
$stmt_total_purchases->bind_param("i", $user_id);
$stmt_total_purchases->execute();
$result_total_purchases = $stmt_total_purchases->get_result();
$total_purchases = $result_total_purchases->fetch_assoc()['total'];
$total_pages = ceil($total_purchases / $items_per_page);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profilestyle.css">
    <link rel="icon" type="image/x-icon" href="nuexlogo.png">
    <style>
    #logout a:hover {
        color: #ffcc00;
    }
    </style>
</head>

<body>
    <header>
        <div class="logo">
            <a href="main_page.php"><img src="homeimages/NUEX.png" alt="Logo"></a>
        </div>
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

    <main>
        <aside class="profile-section">
            <div class="profile-info">
                <h2>Profile</h2>
                <?php if ($user): ?>
                    <p>Username: <span class="highlight"><?php echo htmlspecialchars($user['username']); ?></span></p>
                    <p>Name: <span class="highlight"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span></p>
                    <p>E-mail: <span class="highlight"><?php echo htmlspecialchars($user['email']); ?></span></p>
                    <p>Address: <span class="highlight"><?php echo htmlspecialchars($address['user_address']); ?></p>
                <?php else: ?>
                    <p>Error fetching user data.</p>
                <?php endif; ?>
            </div>
        </aside>

        <section class="main-content">
            <div class="tabs">
                <div class="complete">COMPLETED PURCHASES</div>
            </div>
            <div class="content-area">
                <?php if ($purchases): ?>
                    <?php foreach ($purchases as $purchase): ?>
                        <div class="content-box">
                            <div class="purchase-item">
                                <img src="productsimages/ALLPRODS/<?php echo htmlspecialchars($purchase['product_image']); ?>" alt="Product Image" class="product-image">
                                <div class="purchase-details">
                                    <p>Product Name: <span class="highlight"><?php echo htmlspecialchars($purchase['product_name']); ?></span> </p>
                                    <p>Total Price: <span class="highlight">₱<?php echo number_format($purchase['product_price'] * $purchase['product_quantity'], 2); ?></span></p>
                                    <p>Order Date: <span class="highlight"><?php echo htmlspecialchars($purchase['order_date']); ?></span></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No completed purchases yet.</p>
                <?php endif; ?>
                <!-- Pagination Links -->
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            </div>
            
        </section>
    </main>
</body>

</html>