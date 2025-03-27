<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = $_POST['task_id'] ?? '';
    $title = trim($_POST['taskTitle']);
    $description = trim($_POST['taskDescription']);
    $user_id = $_SESSION['user_id'];

    if (empty($task_id) || empty($title) || empty($description)) {
        echo "All fields are required.";
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$title, $description, $task_id, $user_id]);

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
