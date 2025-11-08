<?php
require_once '../includes/dbc.inc.php';
require_once '../includes/session_config.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../login.php');
    exit;
}

$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalProducts = $pdo->query("SELECT COUNT(*) FROM product")->fetchColumn();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        .card-icon {
            font-size: 2rem;
            margin-right: 10px;
        }
    </style>
</head>

<body class="bg-light">
    <?php require_once 'navbar.php'; ?>

    <div class="container py-5">
        <h2 class="mb-4"><i class="bi bi-speedometer2"></i>  Admin Dashboard</h2>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-success shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <span class="card-icon text-dark"><i class="bi bi-person-fill-check"></i></span>
                        <div>
                            <h5 class="card-title mb-1">Total Users</h5>
                            <h3><?= $totalUsers ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-primary shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <span class="card-icon text-primary">📦</span>
                        <div>
                            <h5 class="card-title mb-1">Total Products</h5>
                            <h3><?= $totalProducts ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div class="row g-3">
            <div class="col-md-4">
                <a href="product_list.php" class="btn btn-outline-primary w-100">📦 Manage Products</a>
            </div>
            <div class="col-md-4">
                <a href="manage_user.php" class="btn btn-outline-success w-100"><i class="bi bi-person-fill"></i> Manage Users</a>
            </div>
            <div class="col-md-4">
                <a href="add_product.php" class="btn btn-outline-warning w-100"><i class="bi bi-bag"></i> Add Product</a>
            </div>
        </div>

        <div class="text-end mt-4">
            <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>

</html>