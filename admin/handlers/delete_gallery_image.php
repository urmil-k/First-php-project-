<?php
require_once '../../includes/dbc.inc.php';
require_once '../../includes/session_config.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    die("Unauthorized");
}

if (isset($_GET['id']) && isset($_GET['pid'])) {
    $imgId = intval($_GET['id']);
    $pid = intval($_GET['pid']);

    // 1. Get the image path to delete the file
    $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE id = :id");
    $stmt->execute([':id' => $imgId]);
    $img = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($img) {
        $filePath = __DIR__ . '/../' . $img['image_path']; // Adjust path to point to admin/uploads
        
        // 2. Delete file from folder
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // 3. Delete record from database
        $delStmt = $pdo->prepare("DELETE FROM product_images WHERE id = :id");
        $delStmt->execute([':id' => $imgId]);
    }

    // Redirect back to the edit page
    header("Location: ../edit_product.php?pid=" . $pid . "&success=Image deleted");
    exit;
}