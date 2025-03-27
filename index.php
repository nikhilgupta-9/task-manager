<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Task Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .container {
            background: rgba(0, 0, 0, 0.6);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .btn-custom {
            width: 150px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-3">Welcome to Task Manager</h1>
        <p class="lead">Manage your tasks efficiently with our simple and intuitive task management system.</p>

        <?php if (isset($_SESSION['user_id'])): ?>
            <h4>Welcome, <?= $_SESSION['username']; ?>!</h4>
            <a href="views/index.php" class="btn btn-success btn-custom">Go to Dashboard</a>
            <a href="auth/logout.php" class="btn btn-danger btn-custom">Logout</a>
        <?php else: ?>
            <a href="auth/login.php" class="btn btn-primary btn-custom">Login</a>
            <a href="auth/register.php" class="btn btn-warning btn-custom">Register</a>
        <?php endif; ?>
    </div>
</body>
</html>
