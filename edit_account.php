<?php
require_once 'includes/session_config.php';
require_once 'includes/dbc.inc.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT uid, uname, email FROM users WHERE uid = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("❌ User not found.");
}

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? $user['username'];
    $email = $_POST['email'] ?? $user['email'];
    $password = $_POST['password'] ?? "";
    $confirmPassword = $_POST['confirm_password'] ?? "";

    if (empty($username) || empty($email)) {
        $message = "⚠️ Username and Email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "⚠️ Invalid email format.";
    } elseif (!empty($password) && $password !== $confirmPassword) {
        $message = "⚠️ Passwords do not match.";
    } else {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET uname = :username, email = :email, password = :password WHERE uid = :id");
            $update->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':id' => $_SESSION['user_id']
            ]);
        } else {
            $update = $pdo->prepare("UPDATE users SET uname = :username, email = :email WHERE uid = :id");
            $update->execute([
                ':username' => $username,
                ':email' => $email,
                ':id' => $_SESSION['user_id']
            ]);
        }
        $message = "✅ Account updated successfully!";
        // Refresh user info
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-warning">
                        <h4 class="mb-0">✏️ Edit Account</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($message): ?>
                            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control"
                                       value="<?= htmlspecialchars($user['uname']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                       value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>

                            <hr>
                            <p class="text-muted">🔑 Leave password fields blank if you don’t want to change it.</p>

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-success">💾 Save Changes</button>
                            <a href="account.php" class="btn btn-secondary">↩ Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
