<?php
session_start();
include '../includes/db_connect.php';


$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Marketplace - Home</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #1a1a1a; color: #f0f0f0; margin: 0; padding: 0;">

    <header style="background-color: #333; padding: 20px; text-align: center; color: white;">
        <h1 style="margin: 0;">Welcome to Prime Mart</h1>
        <nav style="margin-top: 15px;">
            <a href="../index.php" style="color: white; margin-right: 20px; text-decoration: none;">Home</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="../admin/dashboard.php" class="btn-dashboard" style="color: white; margin-right: 20px; text-decoration: none;">Dashboard</a>
            <?php endif; ?>
            <a href="cart.php" style="color: white; margin-right: 20px; text-decoration: none;">Cart</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php" style="color: white; margin-right: 20px; text-decoration: none;">Profile</a>
                <a href="logout.php" style="color: white; text-decoration: none;">Logout</a>
            <?php else: ?>
                <a href="login.php" style="color: white; margin-right: 20px; text-decoration: none;">Login</a>
                <a href="register.php" style="color: white; text-decoration: none;">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <section class="banner" style="background-image: url('../assets/images/5a821dc8-5e74-4ad8-909c-86f802c50f44.webp'); background-size: cover; background-position: center; height: calc(120vh); display: flex; align-items: center; justify-content: center; position: relative; color: white; text-align: center; overflow: hidden; margin-top: -1vh;">
            <div class="banner-overlay" style="background-color: rgba(0, 0, 0, 0.6); padding: 20px; border-radius: 8px; width: 80%; max-width: 800px;">
                <h1 style="font-size: 3rem; font-weight: bold; margin-bottom: 10px;">Welcome to the Student Market</h1>
                <p style="font-size: 1.2rem; margin-bottom: 20px;">Buy and sell essential products for engineering students!</p>
               
            </div>
        </section>

        <section class="products" style="padding: 40px 0; text-align: center;">
    <h2 style="font-size: 2.5rem; margin-bottom: 20px; color: #333;">Available Products</h2>
    <div class="product-grid" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px;">
        <?php foreach ($products as $product): ?>
            <div class="product-item" style="background-color: #fff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); padding: 20px; width: 250px; text-align: center; transition: transform 0.3s ease, box-shadow 0.3s ease; color: #333;">
                <img src="<?php echo isset($product['image_url']) ? htmlspecialchars($product['image_url']) : '../assets/images/default_image.jpg'; ?>" 
                     alt="<?php echo isset($product['NAME']) ? htmlspecialchars($product['NAME']) : 'Unnamed Product'; ?>" 
                     width="150" height="150" style="border-radius: 8px; margin-bottom: 15px; transition: transform 0.3s ease;">

                <h3 style="font-size: 1.3rem; color: #333;"><?php echo isset($product['NAME']) ? htmlspecialchars($product['NAME']) : 'Unnamed Product'; ?></h3>

                <p style="font-size: 1.1rem; color: #666;">$<?php echo isset($product['price']) ? number_format($product['price'], 2) : '0.00'; ?></p>

                <p style="font-size: 0.9rem; color: #888;"><?php echo isset($product['CONDITION']) ? htmlspecialchars($product['CONDITION']) : 'Unknown Condition'; ?></p>

                <a href="product.php?id=<?php echo htmlspecialchars($product['product_id']); ?>" class="view-product-btn" style="display: inline-block; padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; transition: background-color 0.3s, transform 0.3s;">View Product</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

    </main>

    <footer style="background-color: #333; color: white; text-align: center; padding: 20px;">
        <p style="margin: 0;">&copy; 2024 Prime Mart</p>
    </footer>

    <style>
        .product-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }

    
        .product-item:hover img {
            transform: scale(1.1);
        }


        @media (max-width: 768px) {
            .product-grid {
                flex-direction: column;
                align-items: center;
            }

            .product-item {
                width: 80%;
            }
        }

        @media (max-width: 480px) {
            .product-item {
                width: 100%;
            }
        }
    </style>

</body>
</html>
