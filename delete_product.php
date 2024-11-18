<?php
session_start();
include '../includes/config.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$product_id = $_GET['id'] ?? null;

if ($product_id) {
    
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);

    header("Location: dashboard.php");
    exit;
} else {
    echo "Invalid product ID.";
}
?>
