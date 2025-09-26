<?php require_once '../includes/session_config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php require_once 'navbar.php'; ?>

    <section class="add-product">
        <div class="container mt-5 mb-5">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <div class="card shadow-lg border-0 rounded-3">
                        <div class="card-body p-4">
                            <h2 class="card-title text-center mb-4">➕ Add Product</h2>

                            <form action="handlers/add_product_handler.php" method="post" enctype="multipart/form-data">

                                <div class="mb-3">
                                    <label for="pname" class="form-label">Product Name:</label>
                                    <input type="text" id="pname" class="form-control" name="pname" placeholder="Enter product name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="rating" class="form-label">Rating (0–5):</label>
                                    <input type="number" id="rating" class="form-control" name="rating" min="0" max="5" step="0.1" placeholder="e.g. 4.5">
                                </div>

                                <div class="mb-3">
                                    <label for="bought" class="form-label">How many bought:</label>
                                    <input type="number" id="bought" class="form-control" name="bought" min="1" placeholder="e.g. 150">
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (₹):</label>
                                    <input type="text" id="price" class="form-control" name="price" placeholder="Enter product price" required>
                                </div>

                                <div class="mb-3">
                                    <label for="emi" class="form-label">EMI Available:</label>
                                    <select name="emi" id="emi" class="form-select" required>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="category" class="form-label">Category:</label>
                                    <select name="cid" id="category" class="form-select" required>
                                        <option value="" disabled selected>Select a category</option>
                                        <option value="iphone">iPhone</option>
                                        <option value="ipad">iPad</option>
                                        <option value="mac">Mac</option>
                                        <option value="watch">Watch</option>
                                        <option value="others">Others</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Upload Image:</label>
                                    <input type="file" id="image" class="form-control" name="image" accept="image/*" required>
                                </div>

                                <?php
                                if (isset($_SESSION['add_product_errors'])) {
                                    echo "<div class='alert alert-danger'>";
                                    foreach ($_SESSION['add_product_errors'] as $error) {
                                        echo "<p class='mb-0'>$error</p>";
                                    }
                                    echo "</div>";
                                    unset($_SESSION['add_product_errors']);
                                } elseif (isset($_GET['success']) && $_GET['success'] === 'true') {
                                    echo "<div class='alert alert-success'>✅ Product added successfully.</div>";
                                }
                                ?>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg mt-3">Add Product</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
