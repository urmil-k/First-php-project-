<?php

function username_exists(PDO $pdo, string $uname): bool {
    $stmt = $pdo->prepare("SELECT 1 FROM users WHERE uname = :username LIMIT 1");
    $stmt->execute([':username' => $uname]);
    return $stmt->fetchColumn() !== false;
}

function set_user(PDO $pdo, string $fname, string $lname, string $uname, string $email, string $password): void {
    $query = "INSERT INTO users (fname, lname, uname, email, password)
              VALUES (:fname, :lname, :uname, :email, :pwd)";

    $stmt = $pdo->prepare($query);

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':uname', $uname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':pwd', $hashedPassword);

    $stmt->execute();
}
