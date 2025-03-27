<?php
session_start();
require_once '../config/db.php'; // Ensure database connection is included

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];

try {
    $query = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Set default profile image if none is set
$profile_image = !empty($user['profile_img']) ? $user['profile_img'] : '../public/images/default_img.png';
$created_at = date("F d, Y", strtotime($user['created_at']));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

    <?php include '../includes/header.php'; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Profile Card -->
<div class="card shadow-lg p-4 text-center border-0 rounded-4">
    <div class="d-flex flex-column align-items-center">
        <!-- User Avatar Section -->
        <div class="position-relative">
            <img id="profileImage" src="<?php echo htmlspecialchars($profile_image); ?>" 
                 alt="User Avatar" class="rounded-circle border shadow-sm" 
                 width="130" height="130">

            <!-- Hidden File Input -->
            <input type="file" id="imageUpload" class="d-none" accept="image/*">

            <!-- Edit Icon Button -->
            <button class="position-absolute bottom-0 end-0 p-2 bg-light rounded-circle shadow border" 
                    onclick="document.getElementById('imageUpload').click()" title="Change Profile Picture">
                <i class="fa-solid fa-camera text-dark"></i>
            </button>
        </div>

        <!-- User Info -->
        <h3 class="fw-bold mt-3 mb-1 text-primary"><?php echo htmlspecialchars($user['username']); ?></h3>
        <p class="text-muted mb-1"><i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?></p>
        <p class="text-muted mb-1"><i class="fa-solid fa-phone"></i> <?php echo !empty($user['phone']) ? htmlspecialchars($user['phone']) : "Not Provided"; ?></p>
        <p class="text-muted"><i class="fa-solid fa-calendar"></i> Member Since: <?php echo date("F Y", strtotime($created_at)); ?></p>

        <!-- Divider -->
        <hr class="w-75 my-3">

        <!-- Action Buttons -->
        <div class="d-flex justify-content-center gap-3">
            <a href="edit_profile.php" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                <i class="fa-solid fa-user-edit me-2"></i> Edit Profile
            </a>
            <a href="../auth/logout.php" class="btn btn-outline-danger rounded-pill px-4 shadow-sm">
                <i class="fa-solid fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </div>
</div>





                <!-- User Bio Section -->
                <div class="card shadow-lg mt-4 p-4">
                    <h4 class="fw-bold"><i class="fa-solid fa-user"></i> About Me</h4>
                    <p class="text-muted">
                        <?php echo !empty($user['bio']) ? htmlspecialchars($user['bio']) : "No bio available."; ?>
                    </p>
                </div>

                <!-- Account Settings Section -->
                <div class="card shadow-lg mt-4 p-4">
                    <h4 class="fw-bold"><i class="fa-solid fa-cog"></i> Account Settings</h4>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fa-solid fa-key"></i>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change Password</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fa-solid fa-envelope"></i>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#updateEmailModal">Update Email</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fa-solid fa-trash"></i>
                            <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete Account</a>
                        </li>
                    </ul>
                </div>

                <!-- Change Password Modal -->
                <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="changePasswordLabel">Change Password</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="update_profile.php">
                                    <input type="hidden" name="action" value="change_password">
                                    <div class="mb-3">
                                        <label for="currentPassword" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="newPassword" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="newPassword" name="new_password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Email Modal -->
                <div class="modal fade" id="updateEmailModal" tabindex="-1" aria-labelledby="updateEmailLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateEmailLabel">Update Email</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="update_profile.php">
                                    <input type="hidden" name="action" value="update_email">
                                    <div class="mb-3">
                                        <label for="newEmail" class="form-label">New Email</label>
                                        <input type="email" class="form-control" id="newEmail" name="new_email" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Email</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Account Modal -->
                <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger" id="deleteAccountLabel">Confirm Account Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-danger fw-bold">⚠ Are you sure you want to delete your account? This action is irreversible.</p>
                                <form method="POST" action="update_profile.php">
                                    <input type="hidden" name="action" value="delete_account">
                                    <button type="submit" class="btn btn-danger">Yes, Delete My Account</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Log -->
                <div class="card shadow-lg mt-4 p-4">
                    <h4 class="fw-bold"><i class="fa-solid fa-list-check"></i> Recent Activity</h4>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fa-solid fa-check"></i> Completed task: "Fix UI Bug" <span class="text-muted">2 days ago</span></li>
                        <li class="list-group-item"><i class="fa-solid fa-plus"></i> Added new task: "Update API Integration" <span class="text-muted">4 days ago</span></li>
                        <li class="list-group-item"><i class="fa-solid fa-user-edit"></i> Updated profile info <span class="text-muted">1 week ago</span></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>


</body>

</html>
<!-- AJAX Script to Handle Image Upload -->
<script>
document.getElementById('imageUpload').addEventListener('change', function() {
    let formData = new FormData();
    formData.append('profile_image', this.files[0]);

    fetch('../actions/upload_profile_image.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('profileImage').src = data.imagePath;
        } else {
            alert('Error updating profile image!');
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>
<?php
include "../includes/footer.php";
?>