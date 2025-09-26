<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uname = $_POST['uname'];
    $pwd = $_POST['password'];

    try {
        require_once 'dbc.inc.php';
        require_once 'session_config.php';

        $errors = [];
        if (empty($uname) || empty($pwd)) {
            $errors["empty_input"] = "Please fill in all fields.";
        }

        $query = "SELECT uid, uname, password FROM users WHERE uname = :uname";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':uname', $uname);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); 

        if (!$result || $uname !== $result['uname']) {
            $errors['username_wrong'] = "Incorrect username.";
        } elseif (!password_verify($pwd, $result['password'])) {
            $errors['password_wrong'] = "Incorrect password.";
        }

        if (!empty($errors)) {
            $_SESSION['login_errors'] = $errors;
            header('Location: ../login.php');
            exit;
        }

        // Login successful
        if ($result['uname'] === 'admin') {
            $_SESSION['is_admin'] = true;
            header('Location: ../admin/dashboard.php');
            exit;
        }

        $_SESSION['user_id'] = $result['uid'];
        $_SESSION['user_name'] = $result['uname'];
        header('Location: ../index.php');
        exit;

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }

} else {
    header('Location: ../login.php');
    exit;
}
