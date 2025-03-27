<?php
require '../config/db.php';
session_start();

// Check if request is POST and task ID is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $taskId = $_POST['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$taskId]);

        if ($stmt->rowCount() > 0) {
            echo "success";  // Response for AJAX success
        } else {
            echo "Task Not Found or Already Deleted!";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
