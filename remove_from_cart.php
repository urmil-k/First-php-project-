<?php
require_once 'includes/session_config.php';

if (isset($_GET['pid'])) {
    $pid = intval($_GET['pid']);
    unset($_SESSION['cart'][$pid]);
}

$return = $_GET['return'] ?? $_SESSION['return'] ?? 'index.php';
header("Location: cart.php?success=Item removed&return=" . urlencode($return));
exit;
