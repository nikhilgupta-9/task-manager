<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch tasks for the logged-in user
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY status ASC, created_at DESC");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary"><i class="fa-solid fa-tasks"></i> My Task List</h2>
        <div>
            <a href="index.php" class="btn btn-success"><i class="fa-solid fa-plus"></i> Add New Task</a>
            <a href="logout.php" class="btn btn-danger"><i class="fa-solid fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="card shadow-lg p-4">
        <h4 class="fw-bold text-secondary"><i class="fa-solid fa-list-check"></i> Current Tasks</h4>
        <ul class="list-group">
            <?php foreach ($tasks as $task): ?>
                <?php if ($task['status'] == 'pending'): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark"><?= htmlspecialchars($task['title']) ?></span>
                        <div>
                            <button class="btn btn-outline-success btn-sm mark-complete" data-id="<?= $task['id'] ?>">
                                <i class="fa-solid fa-check"></i> Complete
                            </button>
                            <a href="delete_task.php?id=<?= $task['id'] ?>" class="btn btn-outline-danger btn-sm">
                                <i class="fa-solid fa-trash"></i> Delete
                            </a>
                        </div>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="card shadow-lg p-4 mt-4">
        <h4 class="fw-bold text-success"><i class="fa-solid fa-check-double"></i> Completed Tasks</h4>
        <ul class="list-group">
            <?php foreach ($tasks as $task): ?>
                <?php if ($task['status'] == 'completed'): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center text-muted">
                        <?= htmlspecialchars($task['title']) ?>
                        <span class="badge bg-success">Completed</span>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
    $(document).ready(function(){
        $(".mark-complete").click(function(){
            let taskId = $(this).data("id");
            $.ajax({
                url: "complete_task.php",
                method: "POST",
                data: { task_id: taskId },
                success: function(response) {
                    location.reload(); // Refresh to show updated tasks
                }
            });
        });
    });
</script>
