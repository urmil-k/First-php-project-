<?php
require_once 'includes/session_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantities'])) {
    foreach ($_POST['quantities'] as $pid => $qty) {
        $pid = intval($pid);
        $qty = max(1, intval($qty)); // Ensure at least 1
        $maxQty = 100;

        if ($qty > $maxQty) $qty = $maxQty;

        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid]['quantity'] = $qty;
        }
    }
}

$return = $_POST['return'] ?? $_SESSION['return'] ?? 'index.php';
header("Location: cart.php?success=Cart updated&return=" . urlencode($return));
exit;
