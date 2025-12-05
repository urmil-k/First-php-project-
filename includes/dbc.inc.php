<?php

$dbh = "localhost";
$dbname = "php_project";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$dbh;dbname=$dbname", $username, $password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $ex) {
die("❌ Database Connection Failed: " . $ex->getMessage());}