<?php

require_once '../includes/session_config.php';
require_once '../includes/dbc.inc.php';


if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM contactus ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - User Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">📩 User Contact Messages</h4>
            </div>
            <div class="card-body">
                <?php if (count($messages) > 0): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>userID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): ?>
                                <tr>
                                    <td><?= $msg['id'] ?></td>
                                    <td><?= htmlspecialchars($msg['uid']) ?></td>
                                    <td><?= htmlspecialchars($msg['name']) ?></td>
                                    <td><?= htmlspecialchars($msg['email']) ?></td>
                                    <td><?= htmlspecialchars($msg['subject']) ?></td>
                                    <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                                    <td><?= $msg['created_at'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No messages yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
