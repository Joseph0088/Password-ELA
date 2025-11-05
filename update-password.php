<?php
require_once(__DIR__ . '/../LEARNING/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($token) || empty($newPassword) || empty($confirmPassword)) {
        die("All fields are required.");
    }

    if ($newPassword !== $confirmPassword) {
        die("Passwords do not match.");
    }

    if (strlen($newPassword) < 6) {
        die("Password must be at least 6 characters.");
    }

    // Check token validity
    $stmt = $pdo->prepare("SELECT * FROM PasswordResetTokens WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $resetData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resetData) {
        die("Invalid or expired token.");
    }

    $userID = $resetData['user_id'];

    // Hash password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password
    $updateStmt = $pdo->prepare("UPDATE Users SET password = ? WHERE id = ?");
    $updateStmt->execute([$hashedPassword, $userID]);

    // Delete used token
    $deleteStmt = $pdo->prepare("DELETE FROM PasswordResetTokens WHERE token = ?");
    $deleteStmt->execute([$token]);

    // Redirect with success
    header("Location: login.php?reset=success");
    exit;
} else {
    header("Location: forgot-password.php");
    exit;
}

