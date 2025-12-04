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

// 1. Fetch Product Data
$stmt = $pdo->prepare("SELECT * FROM product WHERE pid = :id");
$stmt->execute([':id' => $productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: product_list.php?error=" . urlencode("Product not found"));
    exit;
}

// 2. Fetch Gallery Images
$stmtImg = $pdo->prepare("SELECT * FROM product_images WHERE pid = :id");
$stmtImg->execute([':id' => $productId]);
$galleryImages = $stmtImg->fetchAll(PDO::FETCH_ASSOC);

// 3. Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pname = $_POST['pname'];
    $price = $_POST['price'];
    $description = $_POST['description'] ?? '';
    $rating = $_POST['rating'] ?? 0;
    $how_many_bought = $_POST['how_many_bought'] ?? 0;
    $EMI_avail = $_POST['EMI_avail'];
    $category = $_POST['category'];
    $newImagePath = $product['image'];

    // A. Handle Main Image Update
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileName = basename($_FILES['image']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $uniqueFileName = time() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "_", $fileName);
            $targetPath = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $oldFilePath = __DIR__ . '/' . $product['image'];
                if ($product['image'] && file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
                $newImagePath = 'uploads/' . $uniqueFileName;
            }
        }
    }

    // B. Handle New Gallery Images Upload
    if (isset($_FILES['gallery'])) {
        $count = count($_FILES['gallery']['name']);
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $stmtGallery = $pdo->prepare("INSERT INTO product_images (pid, image_path) VALUES (:pid, :path)");

        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['gallery']['error'][$i] === UPLOAD_ERR_OK) {
                $gName = basename($_FILES['gallery']['name'][$i]);
                $gUnique = time() . '_' . $i . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "_", $gName);
                $gPath = $uploadDir . $gUnique;
                $gDbPath = 'uploads/' . $gUnique;

                if (move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $gPath)) {
                    $stmtGallery->execute([':pid' => $productId, ':path' => $gDbPath]);
                }
            }
        }
    }

    // C. Update Database Record
    $stmt = $pdo->prepare("
        UPDATE product 
        SET pname = :pname, price = :price, description = :description, EMI_avail = :EMI_avail, image = :image, category = :cname
        WHERE pid = :id
    ");
    $stmt->execute([
        ':pname' => $pname,
        ':price' => $price,
        ':description' => $description,
        ':EMI_avail' => $EMI_avail,
        ':image' => $newImagePath,
        ':cname' => $category,
        ':id' => $productId
    ]);

    header("Location: edit_product.php?pid=$productId&success=" . urlencode("Product updated successfully"));
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <?php require_once 'navbar.php'; ?>
    
    <div class="container mt-5 mb-5">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-pencil-square"></i> Edit Product</h2>
                    <a href="product_list.php" class="btn btn-outline-secondary">Back to List</a>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="pname" class="form-control" required value="<?= htmlspecialchars($product['pname']) ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" name="price" step="0.01" class="form-control" required value="<?= htmlspecialchars($product['price']) ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Product Description:</label>
                        <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">EMI Available</label>
                        <select name="EMI_avail" class="form-select" required>
                            <option value="Yes" <?= $product['EMI_avail'] === 'Yes' ? 'selected' : '' ?>>Yes</option>
                            <option value="No" <?= $product['EMI_avail'] === 'No' ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="iphone" <?= $product['category'] === 'iphone' ? 'selected' : '' ?>>iPhone</option>
                            <option value="ipad" <?= $product['category'] === 'ipad' ? 'selected' : '' ?>>iPad</option>
                            <option value="mac" <?= $product['category'] === 'mac' ? 'selected' : '' ?>>Mac</option>
                            <option value="watch" <?= $product['category'] === 'watch' ? 'selected' : '' ?>>Watch</option>
                            <option value="others" <?= $product['category'] === 'others' ? 'selected' : '' ?>>Others</option>
                        </select>
                    </div>
           
                    <hr class="my-4">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Main Product Image</label>
                        <div class="card p-3 bg-light border">
                            <?php if ($product['image'] && file_exists(__DIR__ . '/' . $product['image'])): ?>
                                <div class="text-center mb-2">
                                    <img src="<?= htmlspecialchars($product['image']) ?>" style="max-height: 150px;" class="img-fluid rounded border">
                                    <div class="small text-muted mt-1">Current Thumbnail</div>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted p-3">No main image uploaded</div>
                            <?php endif; ?>
                            <label class="small text-muted">Replace Main Image:</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Gallery Images</label>
                        
                        <?php if (count($galleryImages) > 0): ?>
                            <div class="d-flex flex-wrap gap-3 mb-3">
                                <?php foreach ($galleryImages as $img): ?>
                                    <div class="position-relative border p-1 rounded bg-white">
                                        <img src="<?= htmlspecialchars($img['image_path']) ?>" style="width: 100px; height: 100px; object-fit: contain;">
                                        <a href="handlers/delete_gallery_image.php?id=<?= $img['id'] ?>&pid=<?= $productId ?>" 
                                           class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-decoration-none"
                                           onclick="return confirm('Delete this image?')">
                                            X
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted small">No gallery images found.</p>
                        <?php endif; ?>

                        <label class="small text-muted">Add More Gallery Images:</label>
                        <input type="file" name="gallery[]" class="form-control" multiple accept="image/*">
                        <div class="form-text">Hold Ctrl to select multiple files.</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>