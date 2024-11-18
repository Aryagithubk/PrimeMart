<?php
$host = 'localhost';
// $host = 'sql111.infinityfree.com';
$db = 'college_marketplace';
// $db = 'if0_37731725_college_marketplace';
$user = 'root';
// $user = 'if0_37731725';
$pass = '2004';
// $pass = 'YewJ462JhUypT';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
