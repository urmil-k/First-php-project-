<?php
require_once 'includes/session_config.php';
require_once 'includes/dbc.inc.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$review_id = $_GET['review_id'] ?? null;

if (!$review_id) {
    die("❌ Invalid request.");
}

// Fetch the review to ensure it belongs to the logged-in user
$stmt = $pdo->prepare("SELECT * FROM reviews WHERE id = :id AND uid = :uid");
$stmt->execute([':id' => $review_id, ':uid' => $_SESSION['user_id']]);
$review = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$review) {
    die("❌ Review not found or access denied.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-warning">
                        <h4 class="mb-0">✏️ Edit Your Review</h4>
                    </div>
                    <div class="card-body">
                        <form action="includes/update_review.php" method="POST">
                            <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                            <input type="hidden" name="pid" value="<?= $review['pid'] ?>">

                            <div class="mb-3">
                                <label class="form-label">Rating:</label>
                                <select name="rating" class="form-select" required>
                                    <option value="5" <?= $review['rating'] == 5 ? 'selected' : '' ?>>⭐⭐⭐⭐⭐ (Excellent)</option>
                                    <option value="4" <?= $review['rating'] == 4 ? 'selected' : '' ?>>⭐⭐⭐⭐ (Good)</option>
                                    <option value="3" <?= $review['rating'] == 3 ? 'selected' : '' ?>>⭐⭐⭐ (Average)</option>
                                    <option value="2" <?= $review['rating'] == 2 ? 'selected' : '' ?>>⭐⭐ (Poor)</option>
                                    <option value="1" <?= $review['rating'] == 1 ? 'selected' : '' ?>>⭐ (Terrible)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Comment:</label>
                                <textarea name="comment" class="form-control" rows="4" required><?= htmlspecialchars($review['comment']) ?></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">💾 Update Review</button>
                                <a href="product.php?pid=<?= $review['pid'] ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>