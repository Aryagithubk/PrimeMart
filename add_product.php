<?php
session_start();
include '../includes/config.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['NAME'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $condition = $_POST['CONDITION'];
    $stock = $_POST['stock'];
    $image_url = $_POST['image_url']; 

    
    if (empty($name) || empty($description) || empty($price) || empty($condition) || empty($stock) || empty($image_url)) {
        $error = 'Please fill in all fields, including the image URL.';
    } else {
        
        $stmt = $pdo->prepare("INSERT INTO products (NAME, description, price, `CONDITION`, stock, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $condition, $stock, $image_url]);

        $success = 'Product added successfully!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./add_product.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Add Product - Admin Panel</title>
</head>
<body>
    <header>
        <div class="container">
            <h1>Admin Dashboard</h1>
            <nav>
                <a href="add_product.php">Add Product</a>
                <a href="manage_orders.php">Manage Orders</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="admin-container">

        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        
        <form action="add_product.php" method="POST">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="price">Price ($):</label>
            <input type="number" id="price" name="price" required step="0.01">

            <label for="condition">Condition:</label>
            <select id="condition" name="condition" required>
                <option value="new">New</option>
                <option value="used">Used</option>
            </select>

            <label for="stock">Stock Quantity:</label>
            <input type="number" id="stock" name="stock" required>

            
            <label for="image_url">Product Image URL:</label>
            <input type="url" id="image_url" name="image_url" required placeholder="Enter image URL (e.g., https://example.com/image.jpg)">

            <button type="submit">Add Product</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Prime Mart</p>
    </footer>
</body>
</html>
