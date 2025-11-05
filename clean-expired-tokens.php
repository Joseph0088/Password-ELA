<?php
require 'config.php'; // your DB connection

try {
    $stmt = $pdo->prepare("DELETE FROM PasswordResetRequests WHERE expires_at < NOW()");
    $stmt->execute();

    $deletedCount = $stmt->rowCount();

    echo "Cleanup complete. Deleted $deletedCount expired token(s).";
} catch (Exception $e) {
    echo "Error during cleanup: " . $e->getMessage();
}
