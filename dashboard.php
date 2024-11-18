<?php
session_start();
include '../includes/config.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}


$stmt = $pdo->query("SELECT COUNT(*) AS product_count FROM products");
$productCount = $stmt->fetch()['product_count'];

$stmt = $pdo->query("SELECT COUNT(*) AS order_count FROM orders");
$orderCount = $stmt->fetch()['order_count'];


$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./dashboard.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Admin Dashboard - College Marketplace</title>
</head>
<body>
    <header>
        <div class="container">
            <h1>Admin Dashboard</h1>
            <nav>
                <a href="../pages/home.php">Home</a>
                <a href="add_product.php">Add Product</a>
                <a href="manage_orders.php">Manage Orders</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="dashboard-container">
        <div class="stats-card">
            <h3>Total Products</h3>
            <p><?php echo $productCount; ?></p>
        </div>
        <div class="stats-card">
            <h3>Total Orders</h3>
            <p><?php echo $orderCount; ?></p>
        </div>

        <!-- Products List -->
        <h2>All Products</h2>
        <table class="product-list">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Condition</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo isset($product['product_id']) ? htmlspecialchars($product['product_id']) : 'N/A'; ?></td>
                        <td><?php echo isset($product['NAME']) ? htmlspecialchars($product['NAME']) : 'Unnamed Product'; ?></td>
                        <td><?php echo isset($product['description']) ? htmlspecialchars($product['description']) : 'No description available'; ?></td>
                        <td>$<?php echo isset($product['price']) ? number_format($product['price'], 2) : '0.00'; ?></td>
                        <td><?php echo isset($product['CONDITION']) ? ucfirst(htmlspecialchars($product['CONDITION'])) : 'Unknown'; ?></td>
                        <td><?php echo isset($product['stock']) ? htmlspecialchars($product['stock']) : 'Out of Stock'; ?></td>
                        <td>
                            <a href="delete_product.php?id=<?php echo isset($product['product_id']) ? htmlspecialchars($product['product_id']) : ''; ?>" class="delete-link" 
                               onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 Prime Mart</p>
    </footer>
</body>
</html>
