<?php
// admin/handlers/add_product_handler.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once '../../includes/dbc.inc.php';
    require_once '../../includes/session_config.php';

    // 1. Handle Main Image Upload (Same as before)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $fileName = basename($_FILES['image']['name']);
        $uniqueFileName = time() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "_", $fileName);
        $targetPath = $uploadDir . $uniqueFileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            try {
                // Insert Product Data
                $stmt = $pdo->prepare("
                    INSERT INTO product (pname, description, price, EMI_avail, image, category)
                    VALUES (:pname, :description, :price, :EMI_avail, :image, :category)
                ");
                
                $imageDbPath = 'uploads/' . $uniqueFileName;

                $stmt->execute([
                    ':pname' => $_POST['pname'],
                    ':description' => $_POST['description'] ?? '',
                    ':price' => $_POST['price'],
                    ':EMI_avail' => $_POST['emi'],
                    ':image' => $imageDbPath,
                    ':category' => $_POST['category'],
                ]);

                $pid = $pdo->lastInsertId(); // Get the ID of the product we just made

                // 2. NEW: Handle Gallery Uploads
                if (isset($_FILES['gallery'])) {
                    $count = count($_FILES['gallery']['name']);
                    
                    // Prepare statement once, execute multiple times
                    $stmtGallery = $pdo->prepare("INSERT INTO product_images (pid, image_path) VALUES (:pid, :path)");

                    for ($i = 0; $i < $count; $i++) {
                        if ($_FILES['gallery']['error'][$i] === UPLOAD_ERR_OK) {
                            $gName = basename($_FILES['gallery']['name'][$i]);
                            $gUnique = time() . '_' . $i . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "_", $gName);
                            $gPath = $uploadDir . $gUnique;
                            $gDbPath = 'uploads/' . $gUnique;

                            if (move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $gPath)) {
                                $stmtGallery->execute([':pid' => $pid, ':path' => $gDbPath]);
                            }
                        }
                    }
                }

                header('Location: ../add_product.php?success=true');
                exit;

            } catch (PDOException $e) {
                $_SESSION['add_product_errors'] = ['Database error: ' . $e->getMessage()];
                header('Location: ../add_product.php');
                exit;
            }
        } else {
            $_SESSION['add_product_errors'] = ['Failed to move main image.'];
            header('Location: ../add_product.php');
            exit;
        }
    } else {
        $_SESSION['add_product_errors'] = ['Main image is required.'];
        header('Location: ../add_product.php');
        exit;
    }
} else {
    header('Location: ../add_product.php');
    exit;
}