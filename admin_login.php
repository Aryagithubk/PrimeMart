<?php
session_start();
include '../includes/db_connect.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: ../admin/dashboard.php");  
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT admin_id, password FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        
        $_SESSION['admin_id'] = $admin['admin_id'];
        header("Location: ../admin/dashboard.php");  
        exit;
    } else {
        
        $error = 'Invalid admin username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../admin/style.css">
    <title>Admin Login - College Marketplace</title>
</head>
<body>
    <header>
        <h1>Admin Login</h1>
        <nav>
            <a href="../index.php">Home</a>
        </nav> 
    </header>

    <div class="login-container">
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="admin_login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Prime Mart</p>
    </footer>
</body>
</html>
