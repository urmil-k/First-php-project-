<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $pwd = $_POST['password'];
    $compwd = $_POST['compass'];
    
    try {
   
    require_once 'dbc.inc.php';
    require_once 'session_config.php';
    require_once 'signup_query.php' ;
    
    $errors = [];
    if (empty($fname) || empty($lname) || empty($uname)|| empty($email)|| empty($pwd)|| empty($compwd)) {
        $errors["empty_fileds"] = "fill all fields";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["invalid_email"] =  "please enter correct email";
    }
    if (username_exists($pdo ,$uname)) {
        $errors["invalid_username"] = "username already taken"; 
    }
    if ($pwd !== $compwd) {
        $errors["invalid_password"] = "enter correct password";
    }

    if($errors) {
        $_SESSION['signup_errors'] = $errors;
        header('location: ../signup.php');
        exit;
    }

    set_user($pdo,$fname,$lname,$uname,$email,$pwd);
    header('location: ../login.php');

    $pdo = null;
    $stmt = null;
    die;
 } 
    catch (PDOException $e) {
        die("query failed: ". $e -> getMessage());
    }

} else {
    header('location: ../signup.php');
    exit;
}