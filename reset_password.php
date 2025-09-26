<?php require_once 'includes/session_config.php'; ?>
<?php $token = $_GET['token'] ?? ''; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>🔐 Reset Password</h2>
        <form action="includes/reset_password.inc.php" method="post">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <div class="mb-3">
                <label for="pass" class="form-label">New Password:</label>
                <input type="password" class="form-control" name="password" id="pass" required>
            </div>
            <div class="mb-3">
                <label for="conpass" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" name="confirm" id="conpass" required>
            </div>

            <?php if (!empty($_SESSION['reset_error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['reset_error'] ?></div>
                <?php unset($_SESSION['reset_error']); ?>
            <?php endif; ?>

            <button type="submit" class="btn btn-success">Update Password</button>
        </form>
    </div>
</body>
</html>
