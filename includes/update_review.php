<?php
require_once 'session_config.php';
require_once 'dbc.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $review_id = intval($_POST['review_id']);
    $pid = intval($_POST['pid']);
    $uid = $_SESSION['user_id'];
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if ($rating < 1 || $rating > 5) {
        header("Location: ../product.php?pid=$pid&error=Invalid rating");
        exit;
    }

    try {
        // 1. Update the Review
        $stmt = $pdo->prepare("UPDATE reviews SET rating = :rating, comment = :comment, created_at = NOW() WHERE id = :id AND uid = :uid");
        $stmt->execute([
            ':rating' => $rating,
            ':comment' => $comment,
            ':id' => $review_id,
            ':uid' => $uid
        ]);

        // 2. Recalculate Average Rating for the Product
        $avgStmt = $pdo->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE pid = :pid");
        $avgStmt->execute([':pid' => $pid]);
        $result = $avgStmt->fetch(PDO::FETCH_ASSOC);
        $newRating = round($result['avg_rating'], 1);

        // 3. Update Product Table
        $updateStmt = $pdo->prepare("UPDATE product SET rating = :rating WHERE pid = :pid");
        $updateStmt->execute([':rating' => $newRating, ':pid' => $pid]);

        header("Location: ../product.php?pid=$pid&success=Review updated successfully");
        exit;

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: ../login.php");
    exit;
}