<?php
require_once '../includes/session_config.php';
require_once '../includes/dbc.inc.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../login.php');
    exit;
}

// Fetch all orders with customer info
$stmt = $pdo->prepare("SELECT o.order_id, o.created_at, o.status, o.total_price,
                              u.uname AS customer_name, u.email
                       FROM orders o
                       JOIN users u ON o.uid = u.uid
                       ORDER BY o.created_at DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h2>All Orders</h2>
        <div class="card mt-4">
            <div class="card-body">
                <?php if (count($orders) === 0): ?>
                    <p>No orders found.</p>
                <?php else: ?>
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Placed At</th>
                                <th>Status</th>
                                <th>Total (₹)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($order['order_id']) ?></td>
                                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                    <td><?= htmlspecialchars($order['email']) ?></td>
                                    <td><?= htmlspecialchars($order['created_at']) ?></td>
                                    <td>
                                        <?php
                                        $status = strtolower(trim($order['status']));
                                        switch ($status) {
                                            case 'pending':
                                                $badge = 'warning';
                                                break;
                                            case 'shipped':
                                                $badge = 'info';
                                                break;
                                            case 'delivered':
                                                $badge = 'success';
                                                break;
                                            case 'cancelled':
                                                $badge = 'danger';
                                                break;
                                            default:
                                                $badge = 'secondary';
                                        }
                                        ?>
                                        <span class="badge bg-<?= $badge ?>">
                                            <?= htmlspecialchars(ucfirst($status)) ?>
                                        </span>
                                    </td>

                                    <td>₹<?= number_format($order['total_price'], 2) ?></td>
                                    <td>
                                        <a href="order_details.php?order_id=<?= $order['order_id'] ?>"
                                            class="btn btn-sm btn-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>