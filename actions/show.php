<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized access"]);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM `tasks` WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tasks); // ✅ Ensure JSON response
} catch (Exception $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
exit;
?>
