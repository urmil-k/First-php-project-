<?php
require_once '../includes/dbc.inc.php';
require_once '../includes/session_config.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../login.php');
    exit;
}

if (isset($_GET['delete'])) {
    $userId = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE uid = :id");
    $stmt->execute([':id' => $userId]);
    header("Location: manage_user.php?success=User successfully deleted");
    exit;
}

$search = $_GET['search'] ?? '';
$searchQuery = htmlspecialchars($search);

if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE uname LIKE :search OR email LIKE :search");
    $stmt->execute([':search' => "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY uid DESC");
}

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Users - Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <?php require_once 'navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="mb-4">👥 Manage Users</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <form class="row g-3 mb-4" method="GET">
            <div class="col-auto">
                <input type="text" name="search" class="form-control" placeholder="Search by username or email" value="<?= $searchQuery ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="manage_user.php" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <?php if ($search): ?>
            <div class="alert alert-info">Search results for "<strong><?= $searchQuery ?></strong>":</div>
        <?php endif; ?>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Registered At</th>
                    <th>
                        <a href="add_user.php" class="btn btn-sm btn-success">Add User</a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['uid']) ?></td>
                            <td><?= htmlspecialchars($user['fname']) ?></td>
                            <td><?= htmlspecialchars($user['lname']) ?></td>
                            <td><?= htmlspecialchars($user['uname']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['created_at']) ?></td>
                            <td>
                                <a href="manage_user.php?delete=<?= $user['uid'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-secondary mt-3 mb-4">🔙 Back to Dashboard</a>
    </div>
</body>

</html>