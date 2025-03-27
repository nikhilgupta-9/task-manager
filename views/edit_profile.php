<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $pdo->prepare("SELECT username, email, phone, profile_img, bio FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Set default profile image if not available
$profile_image = !empty($user['profile_img']) ? $user['profile_img'] : '../public/images/default-user.png';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $bio = $_POST['bio'];

    if (!empty($username) && !empty($email)) {
        $updateStmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, phone = ?, bio = ? WHERE id = ?");
        $updateStmt->execute([$username, $email, $phone, $bio, $user_id]);

        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: edit_profile.php");
        exit();
    } else {
        $_SESSION['error'] = "Username and Email are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile | My Social</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    
</head>
<body>
<?php
include "../includes/header.php";
?>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="profile-card">
        <div class="profile-header">Edit Profile</div>

        <!-- Profile Image Upload -->
        <div class="text-center">
            <div class="profile-pic">
                <img id="profilePreview" src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture">
                <label for="profileImageInput"><i class="fa-solid fa-camera"></i></label>
                <input type="file" id="profileImageInput" class="d-none">
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success mt-3"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger mt-3"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Profile Edit Form -->
        <form method="POST" class="mt-4">
            <div class="form-floating mb-3">
                <input type="text" name="username" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                <label for="username"><i class="fa-solid fa-user"></i> Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                <label for="email"><i class="fa-solid fa-envelope"></i> Email</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="phone" class="form-control" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                <label for="phone"><i class="fa-solid fa-phone"></i> Phone</label>
            </div>
            <div class="form-floating mb-3">
                <textarea name="bio" class="form-control" id="bio" rows="3"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                <label for="bio"><i class="fa-solid fa-quote-left"></i> About Me</label>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-save"></i> Save Changes</button>
        </form>

        <hr>
        <a href="profile.php" class="btn btn-light w-100"><i class="fa-solid fa-arrow-left"></i> Back to Profile</a>
    </div>
</div>
<?php
include "../includes/footer.php";
?>
<!-- jQuery and AJAX for Image Upload -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $("#profileImageInput").change(function(){
        var formData = new FormData();
        formData.append('profile_image', this.files[0]);

        $.ajax({
            url: '../actions/upload_profile_image.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                let data = JSON.parse(response);
                if (data.success) {
                    $("#profilePreview").attr("src", data.imagePath);
                } else {
                    alert(data.message);
                }
            }
        });
    });
});
</script>

</body>
</html>
