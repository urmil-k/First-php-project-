<?php require_once 'includes/session_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>🔒 Forgot Password</h2>
        <form action="includes/forgot_password.inc.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Enter your registered email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <?php if (!empty($_SESSION['reset_msg'])): ?>
                <div class="alert alert-info"><?= $_SESSION['reset_msg'] ?></div>
                <?php unset($_SESSION['reset_msg']); ?>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Send Reset Link</button>
            <a href="login.php" class="btn btn-secondary">Back to Login</a>
        </form>
    </div>
</body>
</html>
