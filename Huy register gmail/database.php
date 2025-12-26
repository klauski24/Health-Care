<?php
// database.php - Chỉ chứa hàm kết nối database

function connectDatabase()
{
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'medical_system';

    // Tạo kết nối
    $conn = new mysqli($host, $username, $password);

    if ($conn->connect_error) {
        return null; // Trả về null nếu lỗi
    }

    // Tạo database nếu chưa tồn tại
    $conn->query("CREATE DATABASE IF NOT EXISTS $database");
    $conn->select_db($database);

    // Tạo bảng users
    $conn->query("CREATE TABLE IF NOT EXISTS users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Tạo bảng password_resets
    $conn->query("CREATE TABLE IF NOT EXISTS password_resets (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) NOT NULL,
        token VARCHAR(255) NOT NULL,
        expiry DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Thêm user test
    $test_email = "test@example.com";
    $test_password = password_hash("123456", PASSWORD_DEFAULT);
    $conn->query("INSERT IGNORE INTO users (username, email, password) 
                  VALUES ('test_user', '$test_email', '$test_password')");

    return $conn;
}

// KHÔNG echo bất cứ thứ gì ở đây!
