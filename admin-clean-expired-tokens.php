<?php
require 'config.php';

// Session/authentication check (you can enhance this)
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("DELETE FROM PasswordResetRequests WHERE expires_at < NOW()");
        $stmt->execute();

        $count = $stmt->rowCount();
        $message = "✅ Cleanup successful. Deleted $count expired token(s).";
        $alertClass = 'alert-success';
    } catch (Exception $e) {
        $message = "❌ Error: " . $e->getMessage();
        $alertClass = 'alert-danger';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Cleanup Expired Tokens</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="card shadow mx-auto" style="max-width: 500px;">
      <div class="card-body">
        <h4 class="card-title mb-4 text-center">🧹 Expired Token Cleanup</h4>

        <?php if ($message): ?>
          <div class="alert <?= $alertClass ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
          <div class="d-grid">
            <button type="submit" class="btn btn-danger">Run Cleanup</button>
          </div>
        </form>

        <div class="mt-4 text-center">
          <a href="admin-dashboard.php" class="btn btn-outline-secondary btn-sm">← Back to Dashboard</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
