<?php
require_once 'includes/session_config.php';
require_once 'includes/dbc.inc.php';

header('Content-Type: application/json');

if (!isset($_GET['pid']) || !is_numeric($_GET['pid'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

$pid = intval($_GET['pid']);

// Fetch product
$stmt = $pdo->prepare("SELECT pid, pname, price, image FROM product WHERE pid = :pid");
$stmt->execute([':pid' => $pid]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$pid])) {
    $_SESSION['cart'][$pid]['quantity'] += 1;
} else {
    $_SESSION['cart'][$pid] = [
        'pid' => $product['pid'],
        'name' => $product['pname'],
        'price' => $product['price'],
        'image' => $product['image'],
        'quantity' => 1
    ];
}

// return updated cart count
$totalItems = array_sum(array_column($_SESSION['cart'], 'quantity'));

echo json_encode([
    'success' => true,
    'message' => 'Product added to cart',
    'cart_count' => $totalItems
]);
