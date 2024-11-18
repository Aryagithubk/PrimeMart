<?php
session_start();
include '../includes/config.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->execute([$status, $order_id]);

    
    $message = "Order status updated successfully!";
}


$stmt = $pdo->query("SELECT o.order_id, o.total_price, o.status, u.username FROM orders o JOIN users u ON o.buyer_id = u.user_id ORDER BY o.order_id DESC");
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="./manage.css">
    <title>Manage Orders - Admin Panel</title>
</head>
<body>
    <header>
    <div class="container nav_admin">
            <h1>Admin Dashboard</h1>
            <nav class="nav_admin">
                <a href="../pages/home.php">Home</a>
                <a class="nav_admin" href="dashboard.php">Dashboard</a>
                <a class="nav_admin" href="add_product.php">Add Product</a>
                <a class="nav_admin" href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="orders-container">
        <h2>All Orders</h2>
        
        
        <?php if (isset($message)): ?>
            <p class="success-message"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Buyer</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($order['username']); ?></td>
                        <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                        <td><?php echo ucfirst($order['status']); ?></td>
                        <td>
                            <form action="manage_orders.php" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <select name="status" required>
                                    <option value="pending" <?php echo ($order['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="paid" <?php echo ($order['status'] === 'paid') ? 'selected' : ''; ?>>Paid</option>
                                    <option value="delivered" <?php echo ($order['status'] === 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; 2024 Prime Mart</p>
    </footer>
</body>
</html>
