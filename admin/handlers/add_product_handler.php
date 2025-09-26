<?php
// admin/handler/add_product_handler.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once __DIR__ . '/../../includes/dbc.inc.php';
    require_once __DIR__ . '/../../includes/session_config.php';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        $uploadDir = __DIR__ . '/../uploads/';

        $fileName = basename($_FILES['image']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

        if (!in_array($fileExtension, $allowedExtensions)) {
            $_SESSION['add_product_errors'] = ['Only JPG, PNG, or PDF files are allowed.'];
            header('Location: ../add_product.php');
            exit;
        }

        $uniqueFileName = time() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "_", $fileName);
        $targetPath = $uploadDir . $uniqueFileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO product (pname, rating, how_many_bought, price, EMI_avail, image, cid)
                    VALUES (:pname, :rating, :how_many_bought, :price, :EMI_avail, :image, :cid)
                ");
                $imageDbPath = 'uploads/' . $uniqueFileName;

                $stmt->execute([
                    ':pname' => $_POST['pname'],
                    ':rating' => $_POST['rating'] ?? 0,
                    ':how_many_bought' => $_POST['bought'] ?? 0,
                    ':price' => $_POST['price'],
                    ':EMI_avail' => $_POST['emi'],
                    ':cid' => $_POST['cid'],
                    ':image' => $imageDbPath,
                ]);

                header('Location: ../add_product.php?success=true');
                exit;

            } catch (PDOException $e) {
                $_SESSION['add_product_errors'] = ['Database error: ' . $e->getMessage()];
                header('Location: ../add_product.php');
                exit;
            }

        } else {
            $_SESSION['add_product_errors'] = ['Failed to move uploaded file.'];
            header('Location: ../add_product.php');
            exit;
        }

    } else {
        $_SESSION['add_product_errors'] = ['No image file uploaded.'];
        header('Location: ../add_product.php');
        exit;
    }

} else {
    header('Location: ../add_product.php');
    exit;
}
