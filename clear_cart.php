<?php
require_once 'includes/session_config.php';

unset($_SESSION['cart']);

header("Location: cart.php?success=" . urlencode("Cart cleared successfully!"));
exit;
