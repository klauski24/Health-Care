<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "medical_system";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password);

// Kiểm tra kết nối MySQL
if ($conn->connect_error) {
    die("Kết nối MySQL thất bại: " . $conn->connect_error);
}

// Tạo database nếu chưa tồn tại
$conn->query("CREATE DATABASE IF NOT EXISTS $database");

// Chọn database
$conn->select_db($database);

// Kiểm tra kết nối database
if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}

// Thiết lập charset
$conn->set_charset("utf8mb4");
