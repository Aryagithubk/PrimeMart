<?php
session_start();
include '../includes/db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $stmt = $pdo->prepare("SELECT user_id, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        
        if ($user['role'] === 'admin') {
            
            header("Location: ../admin/dashboard.php");
        } else {
            
            header("Location: home.php");
        }
        exit;
    } else {

        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="./style.css">
    <title>Login - College Marketplace</title>
</head>
<body>
    <header>
        <h1>Login</h1>
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

    <div class="login-container">
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <p>Don’t have an account? <a href="register.php">Register here</a></p>
    </div>

    <footer>
        <p>&copy; 2024 Prime Mart</p>
    </footer>
</body>
</html>
