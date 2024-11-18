<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_GET['order_id'])) {
    header("Location: home.php");
    exit;
}

$order_id = $_GET['order_id'];


$stmt = $pdo->prepare("SELECT o.order_id, o.total_price, o.payment_method, o.status, 
                              oi.product_id, oi.quantity, oi.price, p.name 
                       FROM orders o 
                       JOIN order_items oi ON o.order_id = oi.order_id
                       JOIN products p ON oi.product_id = p.product_id
                       WHERE o.order_id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetchAll();


$order_details = [];
$totalPrice = 0;
foreach ($order as $item) {
    $order_details[] = $item;
    $totalPrice += $item['price'] * $item['quantity'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - College Marketplace</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7; 
            color: #333; 
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333; 
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        .header-container h1 {
            margin: 0;
        }
        .thank-you-container {
            padding: 20px;
            margin: 0 auto;
            max-width: 800px;
            background-color: white; 
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            margin: 0 0 15px;
            color: #333;
        }
        p {
            color: #555; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc; 
        }
        table th {
            background-color: #f1f1f1; 
            font-weight: normal;
        }
        .qr-code img {
            max-width: 200px;
            margin-top: 15px;
        }
        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Thank You for Your Order</h1>
            <p>Welcome to Prime Mart</p>
        </div>
    </header>

    <div class="thank-you-container">
        <h2>Order Confirmation</h2>
        <p>Your order has been successfully placed. We will process it shortly.</p>

        <?php if (!empty($order)) : ?>
            <h3>Order Details:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_details as $item) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Payment Method: <?php echo ucfirst(htmlspecialchars($order[0]['payment_method'])); ?></h3>

            <?php if ($order[0]['payment_method'] === 'qr_code') : ?>
                <div class="qr-code">
                    <p>Scan the QR code to make the payment:</p>
            
                    <img src="path/to/your/qr_code_image.png" alt="QR Code">
                </div>
            <?php else : ?>
                <p>Thank you for visiting.</p>
            <?php endif; ?>
        <?php else : ?>
            <p>Contact support for assistance.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 Prime Mart</p>
    </footer>
</body>
</html>
