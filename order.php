<?php
require_once 'includes/session_config.php';
require_once 'includes/dbc.inc.php';

// 1. Check Login & handle redirect with parameters
if (!isset($_SESSION['user_id'])) {
    $redirectUrl = "order.php";
    if (!empty($_SERVER['QUERY_STRING'])) {
        $redirectUrl .= "?" . $_SERVER['QUERY_STRING'];
    }
    header("Location: login.php?return=" . urlencode($redirectUrl));
    exit;
}

$userId = $_SESSION['user_id'];
$isBuyNow = false;
$buyNowPid = null;



if (isset($_GET['action']) && $_GET['action'] === 'buynow' && isset($_GET['pid'])) {
    // CASE A: User clicked "Order Now" on Product Page
    $isBuyNow = true;
    $buyNowPid = intval($_GET['pid']);

    // Fetch the single product directly from DB
    $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = :pid");
    $stmt->execute([':pid' => $buyNowPid]);
    $prod = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($prod) {
        // Create a temporary cart array just for this page
        $cart = [
            $buyNowPid => [
                'pid' => $prod['pid'],
                'name' => $prod['pname'],
                'price' => $prod['price'],
                'image' => $prod['image'],
                'quantity' => 1 // Default quantity is 1 for direct buy
            ]
        ];
    } else {
        die("❌ Product not found.");
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buynow_pid'])) {
    // CASE B: User is submitting the "Order Now" form
    $isBuyNow = true;
    $buyNowPid = intval($_POST['buynow_pid']);

    // Re-fetch product to ensure price security
    $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = :pid");
    $stmt->execute([':pid' => $buyNowPid]);
    $prod = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($prod) {
        $cart = [
            $buyNowPid => [
                'pid' => $prod['pid'],
                'name' => $prod['pname'],
                'price' => $prod['price'],
                'image' => $prod['image'],
                'quantity' => 1
            ]
        ];
    } else {
        die("❌ Invalid product in order.");
    }
} else {
    // CASE C: Standard Cart Checkout
    $cart = $_SESSION['cart'] ?? [];
}

// --------------------------------------------------------

if (empty($cart)) {
    // Redirect empty cart to home
    header("Location: index.php?error=empty_cart");
    exit;
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

            // Prepare the statement to update the 'how_many_bought' counter
$updateProductStmt = $pdo->prepare("UPDATE product SET how_many_bought = how_many_bought + :qty WHERE pid = :pid");

foreach ($cart as $item) {
    // 1. Insert into Order Items
    $stmtItem->execute([
        ':order_id' => $orderId,
        ':product_id' => $item['pid'],
        ':quantity' => $item['quantity'],
        ':price' => $item['price']
    ]);

    // 2. INCREMENT the 'how_many_bought' count for this product
    $updateProductStmt->execute([
        ':qty' => $item['quantity'],
        ':pid' => $item['pid']
    ]);
}

            $pdo->commit();
            
            // Only clear the session cart if this was a normal checkout!
            // If it was "Buy Now", we leave the user's cart alone.
            if (!$isBuyNow) {
                unset($_SESSION['cart']);
            }
            
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
        body { background-color: #f8f9fa; }
        .order-card { background: #fff; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        .note { color: red; font-weight: bold; }
        .cart-table thead { background: #343a40; color: #fff; }
        .cart-table tfoot th { background: #409970ff; color: #fff; font-size: 1.1rem; }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="order-card mx-auto" style="max-width: 900px;">
            <h2 class="text-center">🛒 Place Your Order</h2>

            <?php if ($success): ?>
                <div class="alert alert-success text-center">
                    <h4><?= htmlspecialchars($success) ?></h4>
                    <p class="mt-3">
                        <a href="generate_invoice.php?order_id=<?= $orderId ?>" class="btn btn-dark">📄 Download Invoice</a>
                        <a href="index.php" class="btn btn-primary">🏠 Back to Home</a>
                    </p>
                </div>
            <?php else: ?>

                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <h4 class="mb-3">
                    <?= $isBuyNow ? "⚡ Instant Checkout Item" : "Your Cart Summary" ?>
                </h4>
                
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
                    <?php if ($isBuyNow): ?>
                        <input type="hidden" name="buynow_pid" value="<?= htmlspecialchars($buyNowPid) ?>">
                    <?php endif; ?>

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
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required pattern="[0-9]{10}" title="Please enter a valid 10-digit mobile number">
                    </div>

                    <p class="note">⚠️ NOTE: Payment is accepted only as Cash on Delivery</p>

                    <div class="d-flex justify-content-between flex-wrap gap-2 mt-3">
                        <button type="submit" class="btn btn-success btn-lg">✅ Confirm Order</button>
                        
                        <?php if($isBuyNow): ?>
                             <a href="product.php?pid=<?= $buyNowPid ?>" class="btn btn-secondary">↩ Cancel</a>
                        <?php else: ?>
                             <a href="cart.php" class="btn btn-secondary">🛒 Back to Cart</a>
                        <?php endif; ?>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>