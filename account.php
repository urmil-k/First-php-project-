<?php
require_once 'includes/session_config.php';
require_once 'includes/dbc.inc.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch user info
$stmt = $pdo->prepare("SELECT uid, uname, email, created_at FROM users WHERE uid = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("❌ User not found.");
}

// Fetch user orders
$orderStmt = $pdo->prepare("
    SELECT o.order_id, o.created_at, o.total_price, o.status,
           p.pname, p.image, oi.quantity, oi.price
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN product p ON oi.pid = p.pid
    WHERE o.uid = :uid
    ORDER BY o.created_at DESC
");
$orderStmt->execute([':uid' => $_SESSION['user_id']]);
$orders = $orderStmt->fetchAll(PDO::FETCH_ASSOC);

// Group rows by order_id
$grouped = [];
foreach ($orders as $row) {
    $oid = $row['order_id'];

    if (!isset($grouped[$oid])) {
        $grouped[$oid] = [
            'order_id'    => $row['order_id'],
            'created_at'  => $row['created_at'],
            'total_price' => $row['total_price'],
            'status'      => $row['status'],
            'items'       => []
        ];
    }

    $grouped[$oid]['items'][] = [
        'pname'    => $row['pname'],
        'image'    => $row['image'],
        'quantity' => $row['quantity'],
        'price'    => $row['price']
    ];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Account Info -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-person-fill"></i> My Account</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Username:</strong> <?= htmlspecialchars($user['uname']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                        <p><strong>Member Since:</strong> <?= date("d M Y", strtotime($user['created_at'])) ?></p>
                        <hr>
                        <a href="edit_account.php" class="btn btn-warning">✏️ Edit Account</a>
                        <a href="logout.php" class="btn btn-danger">🚪 Logout</a>
                    </div>
                </div>

                <!-- Orders Section -->
                <div class="card shadow mb-4">
                    <div class="card-header  bg-success text-white">
                        <h5 class="mb-0">📦 My Orders</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($grouped)): ?>
                            <?php foreach ($grouped as $ord): ?>
                                <div class="mb-4 border rounded p-3">
                                    <h6>🧾 Order #<?= $ord['order_id'] ?> | <?= date("d M Y", strtotime($ord['created_at'])) ?></h6>
                                    <p>
                                        <strong>Status:</strong> <?= htmlspecialchars($ord['status']) ?> |
                                        <strong>Total:</strong> ₹<?= number_format($ord['total_price'], 2) ?>
                                    </p>
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Image</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($ord['items'] as $item): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($item['pname']) ?></td>
                                                    <td>
                                                        <?php if (!empty($item['image'])): ?>
                                                            <img src="admin/<?= htmlspecialchars($item['image']) ?>" width="50">
                                                        <?php else: ?>
                                                            ❌
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= (int)$item['quantity'] ?></td>
                                                    <td>₹<?= number_format($item['price'], 2) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <?php
                                    $status = trim(strtolower($ord['status']));
                                    if ($status != 'cancelled' && $status != 'delivered'): ?>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-danger mt-2" onclick="cancel_order(<?= $ord['order_id'] ?>)">
                                                 Cancel Order
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-end mt-2">
                                            <?php if ($ord['status'] == 'Cancelled'): ?>
                                                <span class="badge bg-danger">Cancelled</span>
                                            <?php elseif ($ord['status'] == 'Delivered'): ?>
                                                <span class="badge bg-success">Delivered</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>You have no orders yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function cancel_order(orderId) {
                if (confirm("Are you sure you want to cancel this order?")) {
                    fetch("cancel_order.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: "order_id=" + orderId
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert("Order cancelled successfully.");
                                location.reload();
                            } else {
                                alert("❌ " + data.message);
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            alert("Something went wrong!");
                        });
                }
            }
        </script>

</body>

</html>