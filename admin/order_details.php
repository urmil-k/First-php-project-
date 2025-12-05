<?php
require_once '../includes/session_config.php';
require_once '../includes/dbc.inc.php';
/** @var PDO $pdo */  
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    die("Order ID is required.");
}

$orderId = intval($_GET['order_id']);

// Fetch order details
$stmt = $pdo->prepare("SELECT o.*, u.uname AS customer_name, u.email
    FROM orders o
    JOIN users u ON o.uid = u.uid
    WHERE o.order_id = :order_id");
$stmt->execute([':order_id' => $orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Order not found.");
}

// Fetch order items
$stmtItems = $pdo->prepare("SELECT oi.*, p.pname
    FROM order_items oi
    JOIN product p ON oi.pid = p.pid
    WHERE oi.order_id = :order_id");
$stmtItems->execute([':order_id' => $orderId]);
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order #<?= htmlspecialchars($orderId) ?> Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h2>Order Details — Order #<?= htmlspecialchars($orderId) ?></h2>
<div class="mb-4">
    <a href="order_view.php" class="btn btn-secondary">&larr; Back to Orders</a>
    <a href="../generate_invoice.php?order_id=<?= $orderId ?>" class="btn btn-info text-white">🖨️ Download Invoice</a>
</div>
        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success">Order status updated successfully!</div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <h5>Customer Info</h5>
                <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
                <p><strong>Ordered At:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
                <p><strong>Total Price:</strong> ₹<?= number_format($order['total_price'], 2) ?></p>
                <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($order['address'])) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>

               <form method="post" action="update_order_status.php" class="mt-3">
                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($orderId) ?>">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <label for="status" class="col-form-label"><strong>Status:</strong></label>
                        </div>
                        <div class="col-auto">
                            <?php 
                            $currentStatus = strtolower(trim($order['status'])); 
                            ?>
                            <select name="status" id="status" class="form-select">
                                <option value="pending"   <?= $currentStatus === 'pending'   ? 'selected' : '' ?>>Pending</option>
                                <option value="shipped"   <?= $currentStatus === 'shipped'   ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $currentStatus === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= $currentStatus === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <h4>Order Items</h4>
        <table class="table table-striped table-bordered mb-5 mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Price (₹)</th>
                    <th>Quantity</th>
                    <th>Subtotal (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item):
                    $subtotal = $item['price'] * $item['quantity'];
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['pname']) ?></td>
                        <td><?= number_format($item['price'], 2) ?></td>
                        <td><?= intval($item['quantity']) ?></td>
                        <td><?= number_format($subtotal, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>