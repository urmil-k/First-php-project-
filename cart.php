<?php
require_once 'includes/session_config.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;

$returnPage = $_GET['return'] ?? $_SESSION['return'] ?? 'index.php';
$_SESSION['return'] = $returnPage; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">🛒 Your Cart</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <?php if (empty($cart)): ?>
        <div class="alert alert-info text-center fs-5">Your cart is empty.</div>
        <div class="text-center mt-4">
            <a href="<?= htmlspecialchars($returnPage) ?>" class="btn btn-lg btn-primary">⬅️ Continue Shopping</a>
        </div>
    <?php else: ?>
        <form method="post" action="update_cart.php" class="shadow-lg p-4 bg-white rounded">
            <input type="hidden" name="return" value="<?= htmlspecialchars($returnPage) ?>">

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 80px;">Image</th>
                            <th>Product</th>
                            <th class="text-center">Price (₹)</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Subtotal</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $pid => $item):
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                            $imgPath = 'admin/' . $item['image'];
                        ?>
                            <tr>
                                <td class="text-center">
                                    <?php if (!empty($item['image']) && file_exists(__DIR__ . '/' . $imgPath)): ?>
                                        <img src="<?= $imgPath ?>" width="60" class="rounded" alt="<?= htmlspecialchars($item['name']) ?>">
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold"><?= htmlspecialchars($item['name']) ?></td>
                                <td class="text-center">₹<?= number_format($item['price'], 2) ?></td>
                                <td class="text-center">
                                    <input type="number" 
                                           name="quantities[<?= $pid ?>]" 
                                           value="<?= $item['quantity'] ?>" 
                                           min="1" 
                                           class="form-control text-center mx-auto" 
                                           style="width: 90px;">
                                </td>
                                <td class="text-center fw-bold">₹<?= number_format($subtotal, 2) ?></td>
                                <td class="text-center">
                                    <a href="remove_from_cart.php?pid=<?= $pid ?>&return=<?= urlencode($returnPage) ?>" 
                                       class="btn btn-sm btn-outline-danger">❌ Remove</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <h4 class="fw-bold">Total: ₹<?= number_format($total, 2) ?></h4>
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-warning">🔄 Update Cart</button>
                    <a href="order.php" class="btn btn-success">✅ Proceed to Checkout</a>
                    <a href="clear_cart.php?return=<?= urlencode($returnPage) ?>" class="btn btn-danger">🗑️ Clear Cart</a>
                    <a href="<?= htmlspecialchars($returnPage) ?>" class="btn btn-secondary">⬅️ Continue Shopping</a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
