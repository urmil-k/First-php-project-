<?php
require_once 'includes/session_config.php';
require_once 'includes/dbc.inc.php';

$cname = strtolower(trim($_GET['category'] ?? ''));
$validCategories = ['iphone', 'ipad', 'mac', 'watch', 'others'];

if (!$cname) {
    die("Category is required.");
}
if (!in_array($cname, $validCategories)) {
    die("❌ Invalid category.");
}
// Fetch products from this category
$stmt = $pdo->prepare("SELECT * FROM product WHERE category = :cname");
$stmt->execute([':cname' => $cname]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title><?= htmlspecialchars($cname) ?> Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body class="bg-light">
    <?php include 'includes/header.php'; ?>
    <div class="container mt-5">
        <h2 class="mb-4"><?= htmlspecialchars($cname) ?> Products</h2>

        <?php if (empty($products)): ?>
            <div class="alert alert-warning">No products found in this category.</div>
        <?php endif; ?>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($products as $product): ?>
                <div class="col">
                    <div class="card h-100">
                        <?php
                        $imagePath = 'admin/' . $product['image'];
                        $filePath  = __DIR__ . '/admin/' . $product['image'];
                        ?>
                        <?php if (!empty($product['image']) && file_exists($filePath)): ?>
                            <img src="<?= htmlspecialchars($imagePath) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['pname']) ?>">
                        <?php else: ?>
                            <div class="p-5 bg-secondary text-white text-center">No Image</div>
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['pname']) ?></h5>
                            <p>💵 ₹<?= number_format($product['price'], 2) ?></p>
                            <p>⭐ Rating: <?= $product['rating'] ?></p>
                            <p>👍 <?= $product['how_many_bought'] ?> +people bought this</p>
                        </div>
                        <div class="card-footer d-flex gap-2">
                            <a href="product.php?pid=<?= $product['pid'] ?>&return=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                                class="btn btn-sm btn-outline-primary flex-fill d-flex justify-content-center align-items-center">
                                View
                            </a>
                            <button class="btn btn-primary btn-sm flex-fill d-flex justify-content-center align-items-center"
                                onclick="addToCart(<?= $product['pid'] ?>)">
                                🛒 Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4 mb-4 d-flex justify-content-center gap-3">
            <a href="index.php" class="btn btn-primary">🏠 Back to Home</a>
            <a href="cart.php?return=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-primary">🛒 View Cart</a>
        </div>

    </div>
    <script src="product_cart.js"></script>
</body>

</html>