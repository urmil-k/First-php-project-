<?php
require_once 'includes/session_config.php';
require_once 'includes/dbc.inc.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

if (!isset($_POST['order_id'])) {
    echo json_encode(["success" => false, "message" => "No order ID provided"]);
    exit;
}

$order_id = (int) $_POST['order_id'];

// Verify ownership
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = :oid AND uid = :uid");
$stmt->execute([':oid' => $order_id, ':uid' => $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo json_encode(["success" => false, "message" => "Order not found"]);
    exit;
}

if ($order['status'] == 'Cancelled' || $order['status'] == 'Delivered') {
    echo json_encode(["success" => false, "message" => "This order cannot be cancelled"]);
    exit;
}

// Update status
$update = $pdo->prepare("UPDATE orders SET status = 'Cancelled' WHERE order_id = :oid");
$update->execute([':oid' => $order_id]);

echo json_encode(["success" => true]);
