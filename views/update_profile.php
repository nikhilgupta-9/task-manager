<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === "change_password") {
            $current_password = $_POST['current_password'];
            $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

            $query = $pdo->prepare("UPDATE users SET password = :new_password WHERE id = :user_id");
            $query->bindParam(':new_password', $new_password);
            $query->bindParam(':user_id', $user_id);
            $query->execute();
            header("Location: profile.php?success=password_updated");
            exit();
        }

        if ($_POST['action'] === "update_email") {
            $new_email = $_POST['new_email'];

            $query = $pdo->prepare("UPDATE users SET email = :new_email WHERE id = :user_id");
            $query->bindParam(':new_email', $new_email);
            $query->bindParam(':user_id', $user_id);
            $query->execute();
            header("Location: profile.php?success=email_updated");
            exit();
        }

        if ($_POST['action'] === "delete_account") {
            $query = $pdo->prepare("DELETE FROM users WHERE id = :user_id");
            $query->bindParam(':user_id', $user_id);
            $query->execute();
            session_destroy();
            header("Location: ../auth/login.php?message=account_deleted");
            exit();
        }

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
