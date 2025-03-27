<?php
    
    $username = $_SESSION['username'] ?? 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <script src="https://kit.fontawesome.com/yourkitcode.js" crossorigin="anonymous"></script> <!-- For Icons -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">  <!-- Add this line -->
</head>
<body>

<!-- Navbar -->
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="../views/index.php">TaskManager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="../views/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="../views/tasks.php">Tasks</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>

                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown">
                    <?php if (!isset($_SESSION['user_image']) || empty($_SESSION['user_image'])): ?>
    <i class="fa-solid fa-user-circle text-white fs-4 me-2"></i>
<?php else: ?>
    <img src="<?php echo $_SESSION['user_image']; ?>" alt="User Avatar" class="rounded-circle me-2" width="30" height="30">
<?php endif; ?>
                        <span class="fw-semibold text-white"><?php echo htmlspecialchars($username); ?></span>
                        </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="../views/profile.php"><i class="fa-solid fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="../auth/logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
