<?php
$host = "localhost";
$dbname = "task_manager";
$username = "root";  // Change as per your database credentials
$password = "";      // Change as per your database credentials

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
