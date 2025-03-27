<?php


require '../config/db.php';
session_start();
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Correct field names based on AJAX request
    $title = trim($_POST['taskTitle']);
    $description = trim($_POST['taskDescription']);

    if (empty($title) || empty($description)) {
        echo "All fields are required.";
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $title, $description]);

        // Check if the row was inserted
        if ($stmt->rowCount() > 0) {
            echo "success";  // Response for AJAX success
        } else {
            echo "Task Not Added !!";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    exit; // Stop further execution
}
?>
