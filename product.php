<?php
require_once 'includes/session_config.php';
require_once 'includes/dbc.inc.php';

$pid = $_GET['pid'] ?? null;

if (!$pid || !is_numeric($pid)) {
    die("❌ Invalid product ID.");
}

// 1. Fetch Product
$stmt = $pdo->prepare("SELECT * FROM product WHERE pid = :pid AND is_active = 1");
$stmt->execute([':pid' => $pid]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("❌ Product not found.");
}

// 2. Fetch Gallery Images (NEW)
$stmtImg = $pdo->prepare("SELECT image_path FROM product_images WHERE pid = :pid");
$stmtImg->execute([':pid' => $pid]);
$gallery = $stmtImg->fetchAll(PDO::FETCH_COLUMN);

// Combine Main Image + Gallery into one array for the slider
$allImages = [];
// Main image (from 'product' table) is always first
if (!empty($product['image'])) {
    $allImages[] = 'admin/' . $product['image']; 
}
// Add gallery images (from 'product_images' table)
foreach ($gallery as $gImg) {
    $allImages[] = 'admin/' . $gImg;
}

// 3. Fetch Reviews
$stmtReviews = $pdo->prepare("
    SELECT r.*, u.uname 
    FROM reviews r 
    JOIN users u ON r.uid = u.uid 
    WHERE r.pid = :pid 
    ORDER BY r.created_at DESC
");
$stmtReviews->execute([':pid' => $pid]);
$reviews = $stmtReviews->fetchAll(PDO::FETCH_ASSOC);

// Helper function for Stars
function renderStars($rating) {
    $stars = '';
    for ($i = 0; $i < 5; $i++) {
        if ($rating >= $i + 1) {
            $stars .= '<i class="bi bi-star-fill text-warning"></i>';
        } elseif ($rating > $i) {
            $stars .= '<i class="bi bi-star-half text-warning"></i>';
        } else {
            $stars .= '<i class="bi bi-star text-secondary"></i>';
        }
    }
    return $stars;
}

$returnUrl = $_GET['return'] ?? 'index.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($product['pname']) ?> - Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .review-card {
            background-color: #f9f9f9;
            border-left: 4px solid #0d6efd;
        }
        /* Custom styles for thumbnail navigation */
        .img-thumbnail-nav {
            width: 60px; 
            height: 60px; 
            object-fit: contain; 
            cursor: pointer; 
            border: 1px solid #ddd;
            transition: border-color 0.2s;
        }
        .img-thumbnail-nav:hover {
            border-color: #0d6efd;
        }
    </style>
</head>

<body class="bg-light">
    <?php require_once 'includes/header.php'; ?>

    <div class="container mt-5">

        <div class="row">
            <div class="col-md-5">
                <?php if (count($allImages) > 0): ?>
                    <div id="productCarousel" class="carousel slide border rounded shadow-sm bg-white" data-bs-ride="carousel">
                        
                        <div class="carousel-indicators">
                            <?php foreach ($allImages as $index => $img): ?>
                                <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
                            <?php endforeach; ?>
                        </div>

                        <div class="carousel-inner">
                            <?php foreach ($allImages as $index => $img): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <div style="height: 400px; display: flex; align-items: center; justify-content: center;">
                                        <?php if (file_exists(__DIR__ . '/' . $img)): ?>
                                            <img src="<?= htmlspecialchars($img) ?>" class="d-block w-100" style="max-height: 100%; object-fit: contain;" alt="Product Image">
                                        <?php else: ?>
                                            <div class="text-muted">Image Not Found</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (count($allImages) > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (count($allImages) > 1): ?>
                        <div class="d-flex mt-2 gap-2 overflow-auto">
                            <?php foreach ($allImages as $index => $img): ?>
                                <?php if (file_exists(__DIR__ . '/' . $img)): ?>
                                    <img src="<?= htmlspecialchars($img) ?>" 
                                         class="img-thumbnail-nav rounded"
                                         onclick="var carousel = new bootstrap.Carousel(document.getElementById('productCarousel')); carousel.to(<?= $index ?>);">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" style="height:400px;">
                        No Image Available
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-7">
                <h2 class="fw-bold"><?= htmlspecialchars($product['pname']) ?></h2>
                <p class="fs-4 text-primary"><i class="bi bi-currency-rupee"></i> <?= number_format($product['price'], 2) ?></p>
                
                <div class="mt-4 mb-4">
                    <h5 class="fw-bold">About this item</h5>
                    <div class="text-secondary" style="line-height: 1.6;">
                        <?php
                        if (!empty($product['description'])) {
                            echo nl2br(htmlspecialchars($product['description']));
                        } else {
                            echo "No description available for this product.";
                        }
                        ?>
                    </div>
                </div>

                <div class="mb-2 d-flex align-items-center">
                    <span class="me-2"><?= renderStars($product['rating']) ?></span>
                    <span class="fw-bold text-dark"><?= $product['rating'] ?>/5</span>
                    <span class="text-muted ms-2">(<?= count($reviews) ?> reviews)</span>
                </div>

                <p class="text-success"><i class="bi bi-bag-check-fill"></i> <strong><?= $product['how_many_bought'] ?></strong> bought this</p>
                <p>💳 EMI: <?= htmlspecialchars($product['EMI_avail']) ?></p>
                <p><strong>Category:</strong> <span class="badge bg-secondary"><?= htmlspecialchars($product['category']) ?></span></p>

                <div class="mt-4 d-flex gap-3">
                    <button class="btn btn-outline-primary btn-lg flex-fill" onclick="addToCart(<?= $product['pid'] ?>)">
                        🛒 Add to Cart
                    </button>
                    <a href="order.php?action=buynow&pid=<?= $product['pid'] ?>" class="btn btn-warning btn-lg flex-fill fw-bold">
                        ⚡ Buy Now
                    </a>
                </div>

                <div class="mt-3">
                    <a href="<?= htmlspecialchars($returnUrl) ?>" class="text-decoration-none text-secondary">&larr; Continue Shopping</a>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div class="row">
            <div class="col-md-8">
                <h3 class="mb-4">⭐ Customer Reviews</h3>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
                <?php elseif (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>

                <?php
                // Check if current user has already reviewed
                $myReview = null;
                if (isset($_SESSION['user_id'])) {
                    foreach ($reviews as $r) {
                        if ($r['uid'] == $_SESSION['user_id']) {
                            $myReview = $r;
                            break;
                        }
                    }
                }
                ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($myReview): ?>
                        <div class="card mb-4 border-primary">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <span>✅ Your Review</span>
                                <div>
                                    <a href="edit_review.php?review_id=<?= $myReview['id'] ?>" class="btn btn-sm btn-light text-primary fw-bold me-2">✏️ Edit</a>
                                    <a href="includes/delete_review.php?review_id=<?= $myReview['id'] ?>&pid=<?= $product['pid'] ?>"
                                       class="btn btn-sm btn-danger text-white fw-bold"
                                       onclick="return confirm('Are you sure you want to delete your review? This cannot be undone.');">
                                       🗑️ Delete
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <?= renderStars($myReview['rating']) ?>
                                    <span class="fw-bold ms-2"><?= $myReview['rating'] ?>/5</span>
                                </div>
                                <p class="card-text"><?= nl2br(htmlspecialchars($myReview['comment'])) ?></p>
                                <small class="text-muted">Posted on: <?= date('d M Y', strtotime($myReview['created_at'])) ?></small>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Write a Review</h5>
                                <form action="includes/submit_review.php" method="POST">
                                    <input type="hidden" name="pid" value="<?= $product['pid'] ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Rating:</label>
                                        <select name="rating" class="form-select w-auto" required>
                                            <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                                            <option value="4">⭐⭐⭐⭐ (Good)</option>
                                            <option value="3">⭐⭐⭐ (Average)</option>
                                            <option value="2">⭐⭐ (Poor)</option>
                                            <option value="1">⭐ (Terrible)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Comment:</label>
                                        <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit Review</button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        Please <a href="login.php?return=product.php?pid=<?= $pid ?>">login</a> to write a review.
                    </div>
                <?php endif; ?>

                <?php if (count($reviews) > 0): ?>
                    <?php foreach ($reviews as $review): ?>
                        <?php if ($myReview && $review['id'] == $myReview['id']) continue; ?>
                        <div class="card mb-3 review-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-bold"><?= htmlspecialchars($review['uname']) ?></h6>
                                    <small class="text-muted"><?= date('d M Y', strtotime($review['created_at'])) ?></small>
                                </div>
                                <div class="mb-2">
                                    <?= renderStars($review['rating']) ?>
                                </div>
                                <p class="card-text"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php if (!$myReview) echo '<p class="text-muted">No reviews yet. Be the first to review!</p>'; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="product_cart.js?v=<?= time() ?>"></script>
</body>
</html>