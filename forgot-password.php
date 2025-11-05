<?php
session_start();
$feedback = $_SESSION['feedback'] ?? '';
$alertClass = $_SESSION['alert'] ?? 'alert-info';
unset($_SESSION['feedback'], $_SESSION['alert']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h4 class="card-title mb-4 text-center">🔐 Forgot Your Password?</h4>
            <p class="text-muted text-center mb-4">Enter your email address to receive a password reset link.</p>

            <?php if ($feedback): ?>
              <div class="alert <?= $alertClass ?>"> <?= htmlspecialchars($feedback) ?> </div>
            <?php endif; ?>

            <form method="POST" action="send-reset-email.php" onsubmit="return validateForm();">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

              <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input 
                  type="email" 
                  class="form-control" 
                  id="email" 
                  name="email" 
                  placeholder="example@domain.com" 
                  required>
              </div>

              <div class="mb-3">
                <div class="g-recaptcha" data-sitekey="YOUR_RECAPTCHA_SITE_KEY"></div>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                  <span id="btnText">Send Reset Link</span>
                  <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
              </div>
            </form>

            <div class="mt-4 text-center">
              <a href="signin.php" class="btn btn-link">← Back to Login</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function validateForm() {
      const email = document.getElementById('email').value;
      if (!email || !email.includes('@')) {
        alert('Please enter a valid email address.');
        return false;
      }

      // Show spinner
      document.getElementById('btnText').classList.add('d-none');
      document.getElementById('btnSpinner').classList.remove('d-none');
      document.getElementById('submitBtn').disabled = true;
      return true;
    }
  </script>
</body>
</html>
