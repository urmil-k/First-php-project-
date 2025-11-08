<?php require_once '../includes/session_config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <?php require_once 'navbar.php'; ?>

    <section class="add-user">
        <div class="container mt-5 mb-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg border-0 rounded-3">
                        <div class="card-body p-4">
                            <h2 class="card-title text-center mb-4"><i class="bi bi-person-fill-add"></i> Add User</h2>

                            <form action="handlers/add_user_handler.php" method="post">

                                <div class="mb-3">
                                    <label for="fname" class="form-label">First Name:</label>
                                    <input type="text" id="fname" class="form-control" name="fname" placeholder="Enter first name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="lname" class="form-label">Last Name:</label>
                                    <input type="text" id="lname" class="form-control" name="lname" placeholder="Enter last name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="uname" class="form-label">Username:</label>
                                    <input type="text" id="uname" class="form-control" name="uname" placeholder="Choose a username" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" id="email" class="form-control" name="email" placeholder="Enter email" required>
                                </div>

                                <div class="mb-3">
                                    <label for="pass" class="form-label">Password:</label>
                                    <input type="password" id="pass" class="form-control" name="password" placeholder="Enter password" required>
                                </div>

                                <div class="mb-3">
                                    <label for="conpass" class="form-label">Confirm Password:</label>
                                    <input type="password" id="conpass" class="form-control" name="compass" placeholder="Re-enter password" required>
                                </div>

                                <?php 
                                if (isset($_SESSION['add_user_errors'])) {
                                    foreach ($_SESSION['add_user_errors'] as $error) {
                                        echo "<p class='text-danger'>$error</p>";
                                    }
                                    unset($_SESSION['add_user_errors']);
                                } elseif (isset($_GET['success']) && $_GET['success'] === 'true') {
                                    echo "<p class='text-success'>✅ User added successfully</p>";
                                }
                                ?>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg mt-2">Register User</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
