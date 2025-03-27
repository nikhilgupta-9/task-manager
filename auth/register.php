<?php
session_start();
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: register.php");
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: register.php");
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert user into database
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);
        $_SESSION['success'] = "Registration successful! You can now login.";
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Email or Username already exists!";
        header("Location: register.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Task Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-custom {
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
        }
        .text-center a {
            text-decoration: none;
            font-weight: bold;
            color: #667eea;
        }
        .text-center a:hover {
            color: #764ba2;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2 class="text-center mb-4">Register for Task Manager</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success text-center"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Enter your Username" required>
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Enter your Email" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Create a Password" required>
        </div>
        <div class="mb-3">
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-custom">Register</button>
    </form>

    <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>
