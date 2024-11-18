<?php
session_start();
include './includes/db_connect.php';


$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./style.css">
    <title>College Marketplace - Home</title>
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Welcome to College Marketplace</h1>
            <nav>
                <a href="./pages/home.php">Home</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    
                    <a href="./admin/dashboard.php" class="btn-dashboard">Dashboard</a>
                <?php endif; ?>
                <a href="./pages/cart.php">Cart</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="./pages/profile.php">Profile</a>
                    <a href="./pages/logout.php">Logout</a>
                <?php else: ?>
                    <a href="./pages/login.php">Login</a>
                    <a href="./pages/register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
     
<section class="banner" style="position: relative; height: 50vh; background-image: url('./assets/images/5a821dc8-5e74-4ad8-909c-86f802c50f44.webp'); background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; overflow: hidden;">
    
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: pink; background-image: url('./assets/images/hand-drawn-student-discount-sale-banner_23-2150639191.jpg'); background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; overflow: hidden;"></div>

    
    <h2 style="position: relative; color: black; font-size: 2.5rem; font-weight: bold; text-align: center; animation: moveText 10s infinite alternate;">
        Find the best deals on engineering supplies!!!
    </h2>
</section>


<style>
    
    @keyframes moveText {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-100%);
        }
    }
</style>

        
        <section class="products">
            <h2>Available Products</h2>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                        
                        <img src="<?php echo isset($product['image_url']) ? htmlspecialchars($product['image_url']) : './assets/images/default_image.jpg'; ?>" 
                             alt="<?php echo isset($product['NAME']) ? htmlspecialchars($product['NAME']) : 'Unnamed Product'; ?>" 
                             class="product-image">

            
                        <div class="product-details">
                            <h3><?php echo isset($product['NAME']) ? htmlspecialchars($product['NAME']) : 'Unnamed Product'; ?></h3>
                            <p>$<?php echo isset($product['price']) ? number_format($product['price'], 2) : '0.00'; ?></p>
                            <p class="product-condition"><?php echo isset($product['CONDITION']) ? htmlspecialchars($product['CONDITION']) : 'Unknown Condition'; ?></p>
                        </div>

                        
                        <a href="./pages/product.php?id=<?php echo htmlspecialchars($product['product_id']); ?>" class="view-product-btn">View Product</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Prime Mart</p>
    </footer>
</body>
</html>
