<?php
require_once '../includes/dbc.inc.php';
require_once '../includes/session_config.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['pid'])) {
    header("Location: product_list.php?error=" . urlencode("No product ID provided"));
    exit;
}

$productId = intval($_GET['pid']);

$stmt = $pdo->prepare("SELECT * FROM product WHERE pid = :id");
$stmt->execute([':id' => $productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: product_list.php?error=" . urlencode("Product not found"));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pname = $_POST['pname'];
    $price = $_POST['price'];
    $rating = $_POST['rating'] ?? 0;
    $how_many_bought = $_POST['how_many_bought'] ?? 0;
    $EMI_avail = $_POST['EMI_avail'];
    $category = $_POST['category'];
    $newImagePath = $product['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileName = basename($_FILES['image']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadDir = '../uploads/';
            $uniqueFileName = time() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "_", $fileName);
            $targetPath = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                if ($product['image'] && file_exists(__DIR__ . '/' . $product['image'])) {
                    unlink(__DIR__ . '/' . $product['image']);
                }
                $newImagePath = 'uploads/' . $uniqueFileName;
            }
        } else {
            $error = "Only JPG, PNG, and PDF files are allowed.";
        }
    }

    if (!isset($error)) {
        $stmt = $pdo->prepare("
            UPDATE product 
            SET pname = :pname, price = :price, rating = :rating, 
                how_many_bought = :how_many_bought, EMI_avail = :EMI_avail, image = :image, category = :cname
            WHERE pid = :id
        ");
        $stmt->execute([
            ':pname' => $pname,
            ':price' => $price,
            ':rating' => $rating,
            ':how_many_bought' => $how_many_bought,
            ':EMI_avail' => $EMI_avail,
            ':image' => $newImagePath,
            ':cname' => $category,
            ':id' => $productId
        ]);

        header("Location: product_list.php?success=" . urlencode("Product updated successfully"));
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2>Edit Product</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="mt-4">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="pname" class="form-control" required value="<?= htmlspecialchars($product['pname']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Price (₹)</label>
                <input type="number" name="price" step="0.01" class="form-control" required value="<?= htmlspecialchars($product['price']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Rating</label>
                <input type="number" name="rating" step="0.1" class="form-control" value="<?= htmlspecialchars($product['rating']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">How Many Bought</label>
                <input type="number" name="how_many_bought" class="form-control" value="<?= htmlspecialchars($product['how_many_bought']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">EMI Available (Yes/No)</label>
                <select name="EMI_avail" class="form-control" required>
                    <option value="Yes" <?= $product['EMI_avail'] === 'Yes' ? 'selected' : '' ?>>Yes</option>
                    <option value="No" <?= $product['EMI_avail'] === 'No' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            <label for="category">Category: </label>
            <select name="category" id="category">
                <option value="iphone" <?= $product['category'] === 'iphone' ? 'selected' : '' ?>>iPhone</option>
                <option value="ipad" <?= $product['category'] === 'ipad' ? 'selected' : '' ?>>iPad</option>
                <option value="mac" <?= $product['category'] === 'mac' ? 'selected' : '' ?>>Mac</option>
                <option value="watch" <?= $product['category'] === 'watch' ? 'selected' : '' ?>>Watch</option>
                <option value="others" <?= $product['category'] === 'others' ? 'selected' : '' ?>>Others</option>
            </select>
   
    <div class="mb-3">
        <label class="form-label">Product Image</label><br>
        <?php if ($product['image'] && file_exists(__DIR__ . '/' . $product['image'])): ?>
            <img src="<?= htmlspecialchars($product['image']) ?>" width="100" class="mb-2"><br>
        <?php endif; ?>
        <input type="file" name="image" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Update Product</button>
    <a href="product_list.php" class="btn btn-secondary ms-3">Cancel</a>
    </form>
    </div>
     </div>
</body>

</html>