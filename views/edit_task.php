<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['task_id']) || empty($_GET['task_id'])) {
    die("Invalid request.");
}

$task_id = $_GET['task_id'];
$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        die("Task not found.");
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">✏️ Edit Task</h2>

        <div id="taskMsg"></div>

        <div class="card p-3 shadow-sm">
            <form id="editTaskForm">
                <input type="hidden" name="task_id" id="task_id" value="<?= htmlspecialchars($task['id']) ?>">

                <div class="mb-3">
                    <label for="taskTitle" class="form-label">Task Title</label>
                    <input type="text" id="taskTitle" name="taskTitle" class="form-control" value="<?= htmlspecialchars($task['title']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="taskDescription" class="form-label">Task Description</label>
                    <textarea id="taskDescription" name="taskDescription" class="form-control" rows="3" required><?= htmlspecialchars($task['description']) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Task</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>

<script>
$(document).ready(function () {
    $("#editTaskForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "../actions/update.php", // Ensure this file exists
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                if (response.trim() === "success") {
                    $("#taskMsg").html('<div class="alert alert-success">Task updated successfully!</div>');
                } else {
                    $("#taskMsg").html('<div class="alert alert-danger">Error: ' + response + '</div>');
                }
            },
            error: function (xhr, status, error) {
                $("#taskMsg").html('<div class="alert alert-danger">AJAX Error: ' + xhr.responseText + '</div>');
            }
        });
    });
});
</script>
