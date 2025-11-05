<?php
require_once(__DIR__ . '/../LEARNING/config.php');

$token = $_GET['token'] ?? '';

if (empty($token)) {
    header("Location: forgot-password.php?error=missing_token");
    exit;
}

// Check token validity
$stmt = $pdo->prepare("SELECT * FROM PasswordResetTokens WHERE token = ? AND expires_at > NOW()");
$stmt->execute([$token]);
$resetData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resetData) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Invalid or expired token.</div></div>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://elitelearnersacademy.com/CSS/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto shadow p-4" style="max-width: 500px;">
            <h4 class="mb-3">Reset Your Password</h4>
            <form action="update-password.php" method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>


