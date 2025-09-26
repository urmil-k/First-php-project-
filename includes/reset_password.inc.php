<?php
require_once 'dbc.inc.php';
require_once 'session_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $_SESSION['reset_error'] = "Passwords do not match.";
        header("Location: ../reset_password.php?token=$token");
        exit;
    }

    // Validate token
    $stmt = $pdo->prepare("SELECT uid, expires_at FROM password_resets WHERE token = :token");
    $stmt->execute([':token' => $token]);
    $reset = $stmt->fetch();

    if (!$reset || strtotime($reset['expires_at']) < time()) {
        $_SESSION['reset_error'] = "Invalid or expired token.";
        header("Location: ../reset_password.php?token=$token");
        exit;
    }

    $uid = $reset['uid'];
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // Update password
    $stmt = $pdo->prepare("UPDATE users SET password = :pwd WHERE uid = :uid");
    $stmt->execute([
        ':pwd' => $hashed,
        ':uid' => $uid
    ]);

    // Clean up
    $pdo->prepare("DELETE FROM password_resets WHERE uid = :uid")->execute([':uid' => $uid]);

    $_SESSION['reset_msg'] = "✅ Password updated. Please log in.";
    header("Location: ../login.php");
    exit;
}
