<?php
require_once 'includes/session_config.php';
require_once 'includes/dbc.inc.php';

$pid = $_GET['pid'] ?? null;

if (!$pid || !is_numeric($pid)) {
    die("❌ Invalid product ID.");
}

$stmt = $pdo->prepare("SELECT * FROM product WHERE pid = :pid");
$stmt->execute([':pid' => $pid]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("❌ Product not found.");
}
$returnUrl = $_GET['return'] ?? 'index.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($product['pname']) ?> - Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="bg-light">
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-5">
                <?php
                $imagePath = 'admin/' . $product['image'];
                $filePath  = __DIR__ . '/admin/' . $product['image'];
                if (!empty($product['image']) && file_exists($filePath)): ?>
                    <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($product['pname']) ?>" class="img-fluid rounded" />
                <?php else: ?>
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height:300px;">
                        No Image Available
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-7">
                <h2><?= htmlspecialchars($product['pname']) ?></h2>
                <p class="fs-4">💵 ₹<?= number_format($product['price'], 2) ?></p>
                <p>⭐ Rating: <?= htmlspecialchars($product['rating']) ?></p>
                <p>👍 <?= htmlspecialchars($product['how_many_bought']) ?>+ people bought this</p>
                <p>💳 EMI: <?= htmlspecialchars($product['EMI_avail']) ?></p>


                <p><strong>Category:</strong> <?= htmlspecialchars($product['category']) ?></p>

               <div class="mt-4 d-flex gap-3">
    <button class="btn btn-primary btn-lg flex-fill d-flex justify-content-center align-items-center" 
            onclick="addToCart(<?= $product['pid'] ?>)">
        🛒 Add to Cart
    </button>

    <a href="<?= htmlspecialchars($returnUrl) ?>" 
       class="btn btn-outline-secondary btn-lg flex-fill d-flex justify-content-center align-items-center">
       🔙 Back
    </a>
</div>
</div>
        </div>
    </div>
    <script src="product_cart.js"></script>
        
</body>

</html>