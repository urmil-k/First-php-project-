<?php
require_once 'dbc.inc.php';
require_once 'session_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT uid FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        $uid = $user['uid'];
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour

        // Delete old tokens for this user
        $pdo->prepare("DELETE FROM password_resets WHERE uid = :uid")->execute([':uid' => $uid]);

        $stmt = $pdo->prepare("INSERT INTO password_resets (uid, token, expires_at) VALUES (:uid, :token, :expires)");
        $stmt->execute([
            ':uid' => $uid,
            ':token' => $token,
            ':expires' => $expires
        ]);

        $_SESSION['reset_msg'] = "Reset link : <a href='reset_password.php?token=$token'>Reset Password</a>";
    } else {
        $_SESSION['reset_msg'] = "No account found with that email.";
    }

    header("Location: ../forgot_password.php");
    exit;
}
