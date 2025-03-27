<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];
$targetDir = "../public/uploads/";

// Create the directory if it doesn't exist
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);  // Create directory with full permissions
}

if ($_FILES['profile_image']['error'] == 0) {
    $fileName = time() . "_" . basename($_FILES["profile_image"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath)) {
        // Update database
        $stmt = $pdo->prepare("UPDATE users SET profile_img = ? WHERE id = ?");
        $stmt->execute([$targetFilePath, $user_id]);

        echo json_encode(['success' => true, 'imagePath' => $targetFilePath]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Upload failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
}
?>
