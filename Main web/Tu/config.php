<?php
// Kết nối database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'health_care';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập UTF-8
$conn->set_charset("utf8mb4");
?>