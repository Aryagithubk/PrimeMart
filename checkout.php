<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT c.product_id, c.quantity, p.name, p.price 
                       FROM cart c 
                       JOIN products p ON c.product_id = p.product_id 
                       WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll();


$totalPrice = 0;
foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    
    
    $stmt = $pdo->prepare("INSERT INTO orders (buyer_id, total_price, status, payment_method) 
                           VALUES (?, ?, 'pending', ?)");
    $stmt->execute([$user_id, $totalPrice, $payment_method]);
    $order_id = $pdo->lastInsertId();

    
    foreach ($cartItems as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
    }

    
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);

    
    header("Location: thank_you.php?order_id=" . $order_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="./style.css">
    <title>Checkout - College Marketplace</title>
</head>
<body>
    <header>
        <h1>Checkout</h1>
        <a class="home_btn" href="./home.php">Home</a>
    </header>

    <div class="checkout-container">
        <h2>Review Your Order</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="order-summary">
            <p>Total Price: $<?php echo number_format($totalPrice, 2); ?></p>

            
            <form action="checkout.php" method="post">
                <label for="payment_method">Choose Payment Method:</label>
                <select name="payment_method" id="payment_method">
                    <option value="offline">Offline Payment</option>
                    <option value="qr_code">QR Code Payment</option>
                </select>
                <button type="submit" class="place-order-btn">Place Order</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Prime Mart</p>
    </footer>
</body>
</html>
