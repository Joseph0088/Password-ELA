<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require_once(__DIR__ . '/../LEARNING/config.php');

$email = $_POST['email'] ?? '';

if (empty($email)) {
    exit('Please enter your email.');
}

// Check if user exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    exit('No account found with that email.');
}

// Generate token
$token = bin2hex(random_bytes(32));
$expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

// Store token in DB
$stmt = $pdo->prepare("INSERT INTO PasswordResetRequests (email, token, expires_at) VALUES (?, ?, ?)");
$stmt->execute([$email, $token, $expires]);

// Prepare reset link
$resetLink = "https://elitelearnersacademy.com/reset-password.php?token=$token";

// Send email using PHPMailer
$mail = new PHPMailer(true);

try {
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ademy.com';
        $mail->Password = '&'; // Your real password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('ly@elitelearnersacademy.com', 'Elite Learners Academy Support');
        $mail->addAddress($email, $user['name'] ?? '');

        $mail->isHTML(true);
        $mail->Subject = 'Newsletter from Elite Learners Academy';



            // Email body with HTML template
        $mail->Body    = "

            <html>
            <head>
              <meta charset='UTF-8'>
              <title>Reset Your Password</title>
            </head>
            <body style='font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;'>
            
              <table style='max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
                <tr>
                  <td style='text-align: center;'>
                    <h2 style='color: #333;'>Reset Your Password</h2>
                    <p style='font-size: 16px; color: #555;'>We received a request to reset your password. Click the button below to set a new one:</p>
                    
                    <a href='$resetLink' style='display: inline-block; margin-top: 20px; background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Reset Password</a>
                    
                    <p style='margin-top: 30px; font-size: 14px; color: #999;'>If you didn't request this, you can safely ignore this email.</p>
                    <p style='font-size: 13px; color: #ccc;'>This link will expire in 30 minutes.</p>
                  </td>
                </tr>
              </table>
            
            </body>
            </html>

    ";

    $mail->send();
    echo 'A password reset link has been sent to your email.';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

