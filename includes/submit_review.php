<?php
require_once 'session_config.php';
require_once 'dbc.inc.php';
/** @var PDO $pdo */ 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $pid = intval($_POST['pid']);
    $uid = $_SESSION['user_id'];
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if ($rating < 1 || $rating > 5) {
        header("Location: ../product.php?pid=$pid&error=Invalid rating");
        exit;
    }

    try {
        // 1. Check if user already reviewed this product
        $stmt = $pdo->prepare("SELECT id FROM reviews WHERE pid = :pid AND uid = :uid");
        $stmt->execute([':pid' => $pid, ':uid' => $uid]);
        if ($stmt->fetch()) {
            header("Location: ../product.php?pid=$pid&error=You have already reviewed this product.");
            exit;
        }

        // 2. Insert the Review
        $insertStmt = $pdo->prepare("INSERT INTO reviews (pid, uid, rating, comment) VALUES (:pid, :uid, :rating, :comment)");
        $insertStmt->execute([
            ':pid' => $pid,
            ':uid' => $uid,
            ':rating' => $rating,
            ':comment' => $comment
        ]);

        // 3. Recalculate Average Rating for the Product
        $avgStmt = $pdo->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE pid = :pid");
        $avgStmt->execute([':pid' => $pid]);
        $result = $avgStmt->fetch(PDO::FETCH_ASSOC);
        $newRating = round($result['avg_rating'], 1);

        // 4. Update Product Table with new Average
        $updateStmt = $pdo->prepare("UPDATE product SET rating = :rating WHERE pid = :pid");
        $updateStmt->execute([':rating' => $newRating, ':pid' => $pid]);

        header("Location: ../product.php?pid=$pid&success=Review submitted successfully");
        exit;

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: ../login.php");
    exit;
}