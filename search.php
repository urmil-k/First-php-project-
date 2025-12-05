<?php
require_once 'includes/session_config.php';
require_once 'includes/dbc.inc.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$products = [];

if ($query) {
    // Search by Product Name OR Category
    $stmt = $pdo->prepare("SELECT * FROM product WHERE pname LIKE :search OR category LIKE :search AND is_active = 1");
    $stmt->execute([':search' => "%$query%"]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - Apple Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <h2>🔎 Search Results for "<?= htmlspecialchars($query) ?>"</h2>
        <hr>

        <?php if (empty($products)): ?>
            <div class="alert alert-warning text-center mt-4">
                <h4>No products found matching your search.</h4>
                <a href="index.php" class="btn btn-primary mt-2">Go Home</a>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($products as $product): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="admin/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" 
                                 style="height: 200px; object-fit: contain;" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product['pname']) ?></h5>
                                <p class="card-text fw-bold">₹<?= number_format($product['price'], 2) ?></p>
                                <a href="product.php?pid=<?= $product['pid'] ?>" class="btn btn-outline-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>