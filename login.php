<?php require_once 'includes/session_config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Apple Store Online</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="script.js"></script>
</head>

<body class="bg-light">

    <?php require_once 'includes/header.php'; ?>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-lg p-4 rounded-4" style="max-width: 400px; width: 100%;">
            <h2 class="text-center mb-4">🔑 Login</h2>

            <form action="includes/login.inc.php" method="post">
                <div class="mb-3">
                    <label for="uname" class="form-label">Username</label>
                    <input type="text" id="uname" class="form-control" name="uname" placeholder="Enter your username" required>
                </div>

                <div class="mb-3">
                    <label for="pass" class="form-label">Password</label>
                    <input type="password" id="pass" class="form-control" name="password" placeholder="Enter your password" required>
                </div>

                <?php
                if (!empty($_SESSION['login_errors'])) {
                    foreach ($_SESSION['login_errors'] as $error) {
                        echo "<div class='alert alert-danger py-1'>$error</div>";
                    }
                    unset($_SESSION['login_errors']); 
                }
                ?>

                <div class="d-grid">
                    <button class="btn btn-primary" type="submit">Login</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <p class="mb-1">Don't have an account? <a href="signup.php">Sign up</a></p>
                <p><a href="forgot_password.php">Forgot your password?</a></p>
            </div>
        </div>
    </div>

</body>
</html>
