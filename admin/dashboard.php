<?php
require_once '../includes/dbc.inc.php';
require_once '../includes/session_config.php';
/** @var PDO $pdo */  
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../login.php');
    exit;
}

// 1. Fetch Basic Counts
$userStmt = $pdo->query("SELECT COUNT(*) FROM users");
$totalUsers = $userStmt ? (int)$userStmt->fetchColumn() : 0;

$productStmt = $pdo->query("SELECT COUNT(*) FROM product WHERE is_active = 1");
$totalProducts = $productStmt ? (int)$productStmt->fetchColumn() : 0; // Modified for Soft Delete

// 2. Calculate Total Revenue (Sum of all non-cancelled orders)
$revenueStmt = $pdo->query("SELECT SUM(total_price) FROM orders WHERE status != 'Cancelled'");
$totalRevenue = $revenueStmt->fetchColumn() ?: 0;

// 3. Get Order Counts by Status
$statusStmt = $pdo->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
$statusData = $statusStmt->fetchAll(PDO::FETCH_KEY_PAIR); 
$statusData = array_change_key_case($statusData, CASE_LOWER);

// 4. Fetch 5 Most Recent Orders
$recentStmt = $pdo->query("
    SELECT o.order_id, o.created_at, o.total_price, o.status, u.uname 
    FROM orders o 
    JOIN users u ON o.uid = u.uid 
    ORDER BY o.created_at DESC 
    LIMIT 5
");
$recentOrders = $recentStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        .dashboard-card {
            transition: transform 0.2s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .icon-box {
            font-size: 2.5rem;
            opacity: 0.8;
        }
    </style>
</head>

<body class="bg-light">
    <?php require_once 'navbar.php'; ?>

    <div class="container py-5">
        <h2 class="mb-4 fw-bold"><i class="bi bi-speedometer2"></i> Dashboard Overview</h2>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card text-white bg-success shadow dashboard-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-2">Total Revenue</h6>
                            <h2 class="mb-0">₹<?= number_format($totalRevenue, 2) ?></h2>
                        </div>
                        <div class="icon-box"><i class="bi bi-currency-rupee"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-primary shadow dashboard-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-2">Total Users</h6>
                            <h2 class="mb-0"><?= $totalUsers ?></h2>
                        </div>
                        <div class="icon-box"><i class="bi bi-people-fill"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-warning shadow dashboard-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-2 text-dark">Active Products</h6>
                            <h2 class="mb-0 text-dark"><?= $totalProducts ?></h2>
                        </div>
                        <div class="icon-box text-dark"><i class="bi bi-box-seam-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-white fw-bold">
                        📊 Order Status
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Pending
                            <span class="badge bg-warning text-dark rounded-pill"><?= $statusData['pending'] ?? 0 ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Shipped
                            <span class="badge bg-info text-dark rounded-pill"><?= $statusData['shipped'] ?? 0 ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Delivered
                            <span class="badge bg-success rounded-pill">
                                <?= ($statusData['delivered'] ?? 0) + ($statusData['completed'] ?? 0) ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Cancelled
                            <span class="badge bg-danger rounded-pill"><?= $statusData['cancelled'] ?? 0 ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow h-100">
                    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                        <span>🕒 Recent Orders</span>
                        <a href="order_view.php" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td>#<?= $order['order_id'] ?></td>
                                        <td><?= htmlspecialchars($order['uname']) ?></td>
                                        <td>₹<?= number_format($order['total_price']) ?></td>
                                        <td>
                                            <?php
                                            $badgeClass = match($order['status']) {
                                                'Pending' => 'bg-warning text-dark',
                                                'Shipped' => 'bg-info text-dark',
                                                'Delivered' => 'bg-success',
                                                'Cancelled' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= $order['status'] ?></span>
                                        </td>
                                        <td>
                                            <a href="order_details.php?order_id=<?= $order['order_id'] ?>" class="btn btn-sm btn-light border">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>