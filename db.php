<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'category_db';
$port = 3307;
$message = '';

$conn = new mysqli($host, $user, $password, '', $port);

if ($conn->connect_error) {
    $message = "Connection failed: " . $conn->connect_error;
} else {
    $message = "Connected successfully";
}

$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`");
$conn->select_db($dbname);
$conn->query("
    CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product VARCHAR(255) NOT NULL,
        type VARCHAR(500) NOT NULL,
        quantity tinyint,
        status VARCHAR(50) NOT NULL
    )
");