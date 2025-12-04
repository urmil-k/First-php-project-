<?php
require_once '../includes/session_config.php';
require_once '../includes/dbc.inc.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../login.php');
    exit;
}

// --- NEW: Handle Delete Request ---
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    
    try {
        $stmt = $pdo->prepare("DELETE FROM contactus WHERE id = :id");
        $stmt->execute([':id' => $deleteId]);
        
        header("Location: user_contactus_messages.php?success=Message deleted successfully");
        exit;
    } catch (PDOException $e) {
        $error = "Error deleting message: " . $e->getMessage();
    }
}

// Fetch messages
$stmt = $pdo->query("SELECT * FROM contactus ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - User Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">📩 User Contact Messages</h2>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0 fs-5">All Messages</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <?php if (count($messages) > 0): ?>
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th style="width: 30%;">Message</th>
                                    <th>Date</th>
                                    <th>Action</th> </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $msg): ?>
                                    <tr>
                                        <td><?= $msg['id'] ?></td>
                                        <td><?= htmlspecialchars($msg['uid'] ?? 'Guest') ?></td>
                                        <td><?= htmlspecialchars($msg['name']) ?></td>
                                        <td><a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a></td>
                                        <td><?= htmlspecialchars($msg['subject']) ?></td>
                                        <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                                        <td><?= date("d M Y, h:i A", strtotime($msg['created_at'])) ?></td>
                                        <td>
                                            <a href="user_contactus_messages.php?delete_id=<?= $msg['id'] ?>" 
                                               class="btn btn-sm btn-danger px-4"
                                               onclick="return confirm('Are you sure you want to delete this message?');">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="p-4 text-center text-muted">No messages found.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>