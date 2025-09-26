<?php
require_once '../includes/session_config.php';
require_once '../includes/dbc.inc.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = intval($_POST['order_id']);
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE order_id = :order_id");
    $stmt->execute([':status' => $status, ':order_id' => $orderId]);

    header("Location: order_details.php?order_id=$orderId&updated=1");
    exit;
}
