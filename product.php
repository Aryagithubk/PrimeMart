<?php
session_start();
include '../includes/db_connect.php';

$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    header("Location: home.php");
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo "Product not found!";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $quantity = $_POST['quantity'] ?? 1;

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }

    
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $existingItem = $stmt->fetch();

        if ($existingItem) {
            
            $newQuantity = $existingItem['quantity'] + $quantity;
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$newQuantity, $user_id, $product_id]);
        } else {
            
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $product_id, $quantity]);
        }

    
        header("Location: cart.php");
        exit;
    } else {
        
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="./product.css">
    <title><?php echo htmlspecialchars($product['NAME'] ?? 'Product'); ?> - College Marketplace</title>
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($product['NAME'] ?? 'Product'); ?></h1>
        <nav>
            <a href="../index.php">Home</a>
            <a href="cart.php">Cart</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="product-container">
      
        
        <div class="product-details">
            <h2><?php echo htmlspecialchars($product['NAME'] ?? 'Product Name'); ?></h2>
            <p><?php echo htmlspecialchars($product['description'] ?? 'No description available'); ?></p>
            <p>Price: $<?php echo number_format($product['price'] ?? 0, 2); ?></p>
            <p>Condition: <?php echo ucfirst(htmlspecialchars($product['CONDITION'] ?? 'Unknown')); ?></p>

            
<form action="product.php?id=<?php echo $product['product_id']; ?>" method="POST">
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock'] ?? 1; ?>">
    <button type="submit" class="add-to-cart-btn" style="background-color: #6AA84F; color: white;">Add to Cart</button>
</form>



        </div>
    </div>

    <footer>
        <p>&copy; 2024 Prime Mart</p>
    </footer>
</body>
</html>
