<?php
require_once '../includes/dbc.inc.php';
require_once '../includes/session_config.php';

/** @var PDO $pdo */

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../login.php');
    exit;
}

if (isset($_GET['delete'])) {
    $productId = intval($_GET['delete']);

    // Instead of deleting the row, we just mark it as inactive.
    // We KEEP the image files so past orders still look correct.
    $stmt = $pdo->prepare("UPDATE product SET is_active = 0 WHERE pid = :id");
    $stmt->execute([':id' => $productId]);

    header("Location: product_list.php?success=Product deactivated successfully");
    exit;
}

// --- FETCH PRODUCTS LOGIC ---
$search = $_GET['category'] ?? '';
$searchQuery = htmlspecialchars($search);

// Only fetch ACTIVE products for the main list
if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM product WHERE category = :cid AND is_active = 1 ORDER BY pid DESC");
    $stmt->execute([':cid' => $search]);
} else {
    $stmt = $pdo->query("SELECT * FROM product WHERE is_active = 1 ORDER BY pid DESC");
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Product List - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <?php require_once 'navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">📦 Manage Products</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form class="row g-3 mb-4" method="GET" action="product_list.php">
            <div class="col-auto">
                <select name="category" id="category" class="form-select">
                    <option value="" disabled <?= $searchQuery === '' ? 'selected' : '' ?>>-- Select Category --</option>
                    <option value="iphone" <?= $searchQuery === 'iphone' ? 'selected' : '' ?>>iPhone</option>
                    <option value="ipad" <?= $searchQuery === 'ipad' ? 'selected' : '' ?>>iPad</option>
                    <option value="mac" <?= $searchQuery === 'mac' ? 'selected' : '' ?>>Mac</option>
                    <option value="watch" <?= $searchQuery === 'watch' ? 'selected' : '' ?>>Watch</option>
                    <option value="others" <?= $searchQuery === 'others' ? 'selected' : '' ?>>Others</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="product_list.php" class="btn btn-secondary">Reset</a>
            </div>
            <div class="col-auto ms-auto">
                <a href="add_product.php" class="btn btn-success"><i class="bi bi-plus fs-5"></i> Add New Product</a>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>PID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Rating</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['pid']) ?></td>
                            <td>
                                <?= htmlspecialchars($product['pname']) ?>
                                <?php if(empty($product['is_active'])): ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>₹<?= number_format($product['price'], 2) ?></td>
                            <td><?= htmlspecialchars($product['rating']) ?></td>
                            <td><?= htmlspecialchars($product['category']) ?></td>
                            <td>
                                <?php if ($product['image'] && file_exists(__DIR__ . '/' . $product['image'])): ?>
                                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="Product Image" style="height: 50px;">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_product.php?pid=<?= $product['pid'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                
                                <a href="product_list.php?delete=<?= $product['pid'] ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure? This will remove the product from the shop but keep it in order history.');">
                                   Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No active products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-secondary mt-3 mb-4"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>

</body>
</html>