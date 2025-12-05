<?php
require_once 'includes/session_config.php';
require_once 'includes/dbc.inc.php';

function renderStars($rating) {
    $stars = '';
    for ($i = 0; $i < 5; $i++) {
        if ($rating >= $i + 1) {
            $stars .= '<i class="bi bi-star-fill text-warning"></i>'; // Full Star
        } elseif ($rating > $i) {
            $stars .= '<i class="bi bi-star-half text-warning"></i>'; // Half Star
        } else {
            $stars .= '<i class="bi bi-star text-secondary"></i>'; // Empty Star
        }
    }
    return $stars;
}

$cname = strtolower(trim($_GET['category'] ?? ''));
$sort = $_GET['sort'] ?? 'newest';
$validCategories = ['iphone', 'ipad', 'mac', 'watch', 'others'];

if (!$cname || !in_array($cname, $validCategories)) {
    die("❌ Invalid category.");
}

// Sorting Logic
switch ($sort) {
    case 'price_low':
        $orderBy = "ORDER BY price ASC";
        break;
    case 'price_high':
        $orderBy = "ORDER BY price DESC";
        break;
    case 'rating':
        $orderBy = "ORDER BY rating DESC";
        break;
    default:
        $orderBy = "ORDER BY pid DESC"; 
        break;
}

// Fetch products
$stmt = $pdo->prepare("SELECT * FROM product WHERE category = :cname AND is_active = 1 $orderBy");
$stmt->execute([':cname' => $cname]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title><?= htmlspecialchars(ucfirst($cname)) ?> Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        #backToTopBtn {
            bottom: 20px;
            right: 20px;
            display: none;
            z-index: 99;
        }

        /* --- NEW CSS FOR TITLE TRUNCATION --- */
        .title-truncate {
            display: -webkit-box;
            -webkit-line-clamp: 2;       /* Limit to 2 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;     /* Add '...' at end */
            height: 3em;                 /* Force consistent height (approx 2 lines) */
            line-height: 1.5em;          /* Line height control */
            margin-bottom: 0.5rem;
        }
        
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px); /* Micro-interaction for better feel */
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body class="bg-light">
    <?php include 'includes/header.php'; ?>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><?= htmlspecialchars(ucfirst($cname)) ?> Products</h2>

            <form method="GET">
                <input type="hidden" name="category" value="<?= htmlspecialchars($cname) ?>">
                <div class="input-group">
                    <label class="input-group-text bg-white" for="sort">Sort By:</label>
                    <select name="sort" id="sort" class="form-select" onchange="this.form.submit()">
                        <option value="newest" <?= $sort == 'newest' ? 'selected' : '' ?>>Newest Arrivals</option>
                        <option value="price_low" <?= $sort == 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price_high" <?= $sort == 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                        <option value="rating" <?= $sort == 'rating' ? 'selected' : '' ?>>Top Rated</option>
                    </select>
                </div>
            </form>
        </div>

        <?php if (empty($products)): ?>
            <div class="alert alert-warning">No products found in this category.</div>
        <?php endif; ?>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($products as $product): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <?php
                        $imagePath = 'admin/' . $product['image'];
                        $filePath  = __DIR__ . '/admin/' . $product['image'];
                        ?>
                        <div style="height: 220px; display: flex; align-items: center; justify-content: center; background: #fff; padding: 10px;">
                            <?php if (!empty($product['image']) && file_exists($filePath)): ?>
                                <img src="<?= htmlspecialchars($imagePath) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['pname']) ?>" style="max-height: 100%; object-fit: contain;">
                            <?php else: ?>
                                <div class="text-muted">No Image</div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title title-truncate" title="<?= htmlspecialchars($product['pname']) ?>">
                                <?= htmlspecialchars($product['pname']) ?>
                            </h5>

                            <p class="fw-bold mb-1"><i class="bi bi-currency-rupee text-dark"></i><?= number_format($product['price'], 2) ?></p>
                            
                            <div class="mb-2">
                                <?= renderStars($product['rating']) ?>
                                <span class="text-muted small">(<?= $product['rating'] ?>)</span>
                            </div>
                            
                            <p class="card-text small text-muted mt-auto">
                                <i class="bi bi-person-check-fill"></i> <?= $product['how_many_bought'] ?> bought this
                            </p>
                        </div>
                        
                        <div class="card-footer bg-white border-top-0 d-flex gap-2 pb-3">
                            <a href="product.php?pid=<?= $product['pid'] ?>&return=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                                class="btn btn-sm btn-outline-primary flex-fill fw-bold">
                                View
                            </a>
                            <button class="btn btn-primary btn-sm flex-fill fw-bold"
                                onclick="addToCart(<?= $product['pid'] ?>)">
                                🛒 Add
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-5 mb-5 d-flex justify-content-center gap-3">
            <button onclick="topFunction()" id="backToTopBtn" class="btn btn-primary shadow">
                ↑ Top
            </button>
            <a href="cart.php?return=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-primary shadow">🛒 View Cart</a>
        </div>

    </div>
    <script src="product_cart.js?v=<?= time() ?>"></script>
    <script>
        let mybutton = document.getElementById("backToTopBtn");
        window.onscroll = function() { scrollFunction() };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
</body>
</html>