<?php
require_once 'includes/session_config.php';

include_once "includes/dbc.inc.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apple Store - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once "includes/header.php"; ?>
    <!-- Hero Section -->
    <section class="bg-dark text-light text-center p-5 mb-5">
        <div class="container">
            <h1 class="display-4 fw-bold">Welcome to Apple Store </h1>
            <p class="lead">Your one-stop hub for all Apple products.</p>
            <a href="category.php?category=iphone" class="btn btn-primary btn-lg">Shop Now</a>
        </div>
    </section>

    <!-- Categories -->
    <section class="container mb-5">
        <h2 class="text-center mb-4">Explore Categories</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <a href="category.php?category=iphone" class="text-decoration-none text-dark">
                        <img src="pic/iphone1.jpg" class="card-img-top" alt="iPhone" style="height:200px;object-fit:contain;">
                        <div class="card-body text-center">
                            <h5 class="card-title">iPhone</h5>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <a href="category.php?category=ipad" class="text-decoration-none text-dark">
                        <img src="pic/ipad1.jpg" class="card-img-top" alt="iPad" style="height:200px;object-fit:contain;">
                        <div class="card-body text-center">
                            <h5 class="card-title">iPad</h5>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <a href="category.php?category=mac" class="text-decoration-none text-dark">
                        <img src="pic/mac1.jpg" class="card-img-top" alt="Mac" style="height:200px;object-fit:contain;">
                        <div class="card-body text-center">
                            <h5 class="card-title">Mac</h5>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <a href="category.php?category=watch" class="text-decoration-none text-dark">
                        <img src="pic/watch1.jpg" class="card-img-top" alt="Watch" style="height:200px;object-fit:contain;">
                        <div class="card-body text-center">
                            <h5 class="card-title">Watch</h5>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <a href="category.php?category=others" class="text-decoration-none text-dark">
                        <img src="pic/tv1.jpg" class="card-img-top" alt="Other Products" style="height:200px;object-fit:contain;">
                        <div class="card-body text-center">
                            <h5 class="card-title">TV & Others</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="container mb-5">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row g-4">
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM product ORDER BY RAND() LIMIT 4");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($products) {
                    foreach ($products as $row) {
                        echo '
                            <div class="col-md-3">
                                <div class="card h-100 d-flex flex-column shadow-sm">
                                    <a href="product.php?pid=' . $row['pid'] . '" class="text-decoration-none text-dark">
                                        <img src="admin/' . $row['image'] . '" 
                                             class="card-img-top" 
                                             alt="' . htmlspecialchars($row['pname']) . '" 
                                             style="height:200px;object-fit:contain;">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">' . htmlspecialchars($row['pname']) . '</h5>
                                            <p class="text-muted">₹' . number_format($row['price']) . '</p>
                                        </div>
                                    </a>
                                    <div class="card-footer bg-white text-center mt-auto">
                                        <button type="button" class="btn btn-primary btn-sm w-100" onclick="addToCart(' . $row['pid'] . ')">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>';
                    }
                } else {
                    echo "<p class='text-center'>No products available yet.</p>";
                }
            } catch (PDOException $e) {
                echo "<p class='text-danger text-center'>Error fetching products: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>

        </div>
    </section>

    <!-- About -->
    <section class="bg-light py-5">
        <div class="container text-center">
            <h2>About Us</h2>
            <p class="lead">We bring you all the Apple products at your fingertips! Learn, explore, and shop for everything you need from home.</p>
            <p>Monthly pricing is after purchase using EMI with qualifying cards at 15.99% p.a. over a 12‑month tenure. Monthly pricing is rounded to the nearest rupee. Exact pricing will be provided by your card issuer, subject to your card issuer’s terms and conditions.</p>
            <a href="about.php" class="btn btn-dark">Learn More</a>
        </div>
    </section>

    <!-- Contact -->
    <section class="py-5 text-center">
        <div class="container">
            <h2>Have Questions?</h2>
            <p>Reach out to us for support or queries.</p>
            <a href="contactus.php" class="btn btn-primary">Contact Us</a>
        </div>
    </section>
    <?php include_once "includes/footer.php"; ?>
    <script src="product_cart.js"></script>
</body>

</html>