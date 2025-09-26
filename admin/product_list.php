<?php
require_once '../includes/dbc.inc.php';
require_once '../includes/session_config.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../login.php');
    exit;
}

if (isset($_GET['delete'])) {
    $productId = intval($_GET['delete']);

    $stmt = $pdo->prepare("SELECT image FROM product WHERE pid = :id");
    $stmt->execute([':id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product && !empty($product['image'])) {
        $imagePath = __DIR__ . '/' . $product['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM product WHERE pid = :id");
    $stmt->execute([':id' => $productId]);

    header("Location: product_list.php?success=Product successfully deleted");
    exit;
}


$search = $_GET['category'] ?? '';
$searchQuery = htmlspecialchars($search);

if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM product WHERE category = :cid ORDER BY pid DESC");
    $stmt->execute([':cid' => $search]);
} else {
    $stmt = $pdo->query("SELECT * FROM product ORDER BY pid DESC");
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Product List - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <a href="add_product.php" class="btn btn-success">➕ Add New Product</a>
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
                    <th>EMI Available</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['pid']) ?></td>
                            <td><?= htmlspecialchars($product['pname']) ?></td>
                            <td>₹<?= number_format($product['price'], 2) ?></td>
                            <td><?= htmlspecialchars($product['rating']) ?></td>
                            <td><?= htmlspecialchars($product['category']) ?></td>
                            <td><?= htmlspecialchars($product['EMI_avail']) ?></td>
                            <td>
                                <?php if ($product['image'] && file_exists(__DIR__ . '/' . $product['image'])): ?>
                                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="Product Image" style="height: 50px;">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_product.php?pid=<?= $product['pid'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="product_list.php?delete=<?= $product['pid'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-secondary mt-3 mb-4">🔙 Back to Dashboard</a>
    </div>

</body>

</html>