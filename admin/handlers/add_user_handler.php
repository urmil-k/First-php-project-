<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $pwd = $_POST['password'];
    $confirmPwd = $_POST['compass'];
    
    try {
   
    require_once '../../includes/dbc.inc.php';
    require_once '../../includes/session_config.php';
    require_once '../../includes/signup_query.php' ;

    $errors = [];
    if (empty($fname) || empty($lname) || empty($uname)|| empty($email)|| empty($pwd)) {
        $errors["empty_input"] = "fill all fields";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["invalid_email"] =  "please enter correct email";
    }
   if (username_exists($pdo, $uname)) {
    $errors["invalid_username"] = "Username already taken.";
    }

    if ($pwd !== $confirmPwd) {
    $errors['password_mismatch'] = "Passwords do not match";
    }   
   
    if($errors) {
        $_SESSION['add_user_errors'] = $errors;
        header('location: ../add_user.php');
        die;
    }

    set_user($pdo,$fname,$lname,$uname,$email,$pwd);
    header('location: ../add_user.php?success=true');

    $pdo = null;
    $stmt = null;
    die;
 } 
    catch (PDOException $e) {
        die("query failed: ". $e -> getMessage());
    }

} else {
    header('location: ../add_user.php?error=user_not_added');
    exit;
}