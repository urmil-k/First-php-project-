<?php
require_once 'includes/session_config.php';
require_once 'includes/dbc.inc.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?return=order.php");
    exit;
}

$userId = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    die("<div class='alert alert-warning text-center mt-5'>Your cart is empty. Please add products before placing an order.</div>");
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = ($_POST['name'] ?? '');
    $email = ($_POST['email'] ?? '');
    $address = ($_POST['address'] ?? '');
    $phone = ($_POST['phone'] ?? '');

    if (empty($name)) $errors[] = "Name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($address)) $errors[] = "Shipping address is required.";
    if (empty($phone)) $errors[] = "Phone number is required.";

    if (empty($errors)) {
        $totalPrice = 0;
        foreach ($cart as $item) $totalPrice += $item['price'] * $item['quantity'];

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO orders (uid, name, email, address, phone, total_price, created_at) VALUES (:user_id, :name, :email, :address, :phone, :total_price, NOW())");
            $stmt->execute([
                ':user_id' => $userId,
                ':name' => $name,
                ':email' => $email,
                ':address' => $address,
                ':phone' => $phone,
                ':total_price' => $totalPrice
            ]);

            $orderId = $pdo->lastInsertId();
            $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, pid, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");

            foreach ($cart as $item) {
                $stmtItem->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['pid'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price']
                ]);
            }

            $pdo->commit();
            unset($_SESSION['cart']);
            $success = "✅ Order placed successfully! Your order ID is #$orderId.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Error placing order: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Place Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .order-card {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .note {
            color: red;
            font-weight: bold;
        }

        h2 {
            color: #343a40;
            margin-bottom: 1.5rem;
        }

        .btn-group-custom a,
        .btn-group-custom button {
            min-width: 150px;
        }

        .cart-table {
            border-radius: 8px;
            overflow: hidden;
        }

        .cart-table thead {
            background: #343a40;
            color: #fff;
        }

        .cart-table tbody tr:hover {
            background-color: #f1f1f1;
            transition: background 0.2s ease-in-out;
        }

        .cart-table tfoot th {
            background: #409970ff;
            color: #fff;
            font-size: 1.1rem;
        }

        .subtotal {
            font-weight: 600;
            color: #0d6efd;
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="order-card mx-auto" style="max-width: 900px;">
            <h2 class="text-center">🛒 Place Your Order</h2>

            <?php if ($success): ?>
                <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
                <div class="text-center mt-3">
                    <a href="index.php" class="btn btn-primary btn-lg">🏠 Back to Home</a>
                </div>
            <?php else: ?>

                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <h4 class="mb-3">Your Cart Summary</h4>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-hover cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Price (₹)</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Subtotal (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grandTotal = 0;
                            foreach ($cart as $item):
                                $subtotal = $item['price'] * $item['quantity'];
                                $grandTotal += $subtotal;
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td class="text-center">₹<?= number_format($item['price'], 2) ?></td>
                                    <td class="text-center"><?= $item['quantity'] ?></td>
                                    <td class="text-center subtotal">₹<?= number_format($subtotal, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Grand Total</th>
                                <th class="text-center">₹<?= number_format($grandTotal, 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>


                <form method="post" action="order.php">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Shipping Address *</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
                    </div>

                    <p class="note">⚠️ NOTE: Payment is accepted only as Cash on Delivery</p>

                    <div class="d-flex justify-content-between flex-wrap btn-group-custom gap-2 mt-3">
                        <button type="submit" class="btn btn-success">✅ Place Order</button>
                        <a href="cart.php" class="btn btn-secondary">🛒 Back to Cart</a>
                        <a href="index.php" class="btn btn-secondary">🏠 Home</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>