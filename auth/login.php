<?php
session_start();
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: ../views/index.php");
        exit;
    } else {
        $_SESSION['error'] = "Invalid credentials!";
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
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

<div class="login-container">
    <h2 class="text-center mb-4">Login to Task Manager</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Enter your Email" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Enter your Password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-custom">Login</button>
    </form>

    <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p>
</div>

</body>
</html>
