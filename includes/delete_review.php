<?php
require_once 'session_config.php';
require_once 'dbc.inc.php';

if (isset($_GET['review_id']) && isset($_SESSION['user_id'])) {
    $review_id = intval($_GET['review_id']);
    $uid = $_SESSION['user_id'];
    $pid = intval($_GET['pid']); // We pass this for redirection safety

    try {
        // 1. Verify Ownership (Security Check)
        // Ensure the review exists AND belongs to the logged-in user
        $stmt = $pdo->prepare("SELECT id FROM reviews WHERE id = :id AND uid = :uid");
        $stmt->execute([':id' => $review_id, ':uid' => $uid]);
        
        if (!$stmt->fetch()) {
            die("❌ Access Denied: You can only delete your own reviews.");
        }

        // 2. Delete the Review
        $deleteStmt = $pdo->prepare("DELETE FROM reviews WHERE id = :id");
        $deleteStmt->execute([':id' => $review_id]);

        // 3. Recalculate Average Rating
        $avgStmt = $pdo->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE pid = :pid");
        $avgStmt->execute([':pid' => $pid]);
        $result = $avgStmt->fetch(PDO::FETCH_ASSOC);
        
        // If there are no reviews left, average is 0. Otherwise, round it.
        $newRating = $result['avg_rating'] ? round($result['avg_rating'], 1) : 0;

        // 4. Update Product Table
        $updateStmt = $pdo->prepare("UPDATE product SET rating = :rating WHERE pid = :pid");
        $updateStmt->execute([':rating' => $newRating, ':pid' => $pid]);

        header("Location: ../product.php?pid=$pid&success=Review deleted successfully");
        exit;

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: ../login.php");
    exit;
}