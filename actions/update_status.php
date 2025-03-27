<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = $_POST['task_id'] ?? '';
    $status = $_POST['status'] ?? '';
    $user_id = $_SESSION['user_id'];

    // Validate input
    $valid_statuses = ['pending', 'completed'];
    if (!in_array($status, $valid_statuses)) {
        echo "Invalid status value.";
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$status, $task_id, $user_id]);

        if ($stmt->rowCount() > 0) {
            echo "success";
        } else {
            echo "No changes made.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
