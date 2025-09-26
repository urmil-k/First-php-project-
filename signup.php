<?php require_once 'includes/session_config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Apple Store Online</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="script.js"></script>
</head>

<body class="bg-light">

    <?php require_once 'includes/header.php'; ?>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh; margin-top: 20px;">
        <div class="card shadow-lg p-4 rounded-4" style="max-width: 500px; width: 100%;">
            <h2 class="text-center mb-4">📝 Register</h2>

            <form action="includes/signup.inc.php" method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fname" class="form-label">First Name</label>
                        <input type="text" id="fname" class="form-control" name="fname" placeholder="Enter first name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lname" class="form-label">Last Name</label>
                        <input type="text" id="lname" class="form-control" name="lname" placeholder="Enter last name" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="uname" class="form-label">Username</label>
                    <input type="text" id="uname" class="form-control" name="uname" placeholder="Choose a username" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" class="form-control" name="email" placeholder="Enter your email" required>
                </div>

                <div class="mb-3">
                    <label for="pass" class="form-label">Password</label>
                    <input type="password" id="pass" class="form-control" name="password" placeholder="Enter password" required>
                </div>

                <div class="mb-3">
                    <label for="conpass" class="form-label">Confirm Password</label>
                    <input type="password" id="conpass" class="form-control" name="compass" placeholder="Re-enter password" required>
                </div>

                <?php
                if (!empty($_SESSION['signup_errors'])) {
                    foreach ($_SESSION['signup_errors'] as $error) {
                        echo "<div class='alert alert-danger py-1'>$error</div>";
                    }
                    unset($_SESSION['signup_errors']);
                }
                ?>

                <div class="d-grid">
                    <button class="btn btn-primary" type="submit">Register</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
