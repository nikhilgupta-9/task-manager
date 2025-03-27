<?php
require '../config/db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("UPDATE tasks SET status = 'completed' WHERE id = ?");
    $stmt->execute([$_GET['id']]);

    header("Location: ../views/index.php");
    exit();
}
?>
