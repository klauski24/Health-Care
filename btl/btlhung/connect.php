<?php
// Thông tin kết nối
$servername = "localhost";
$username = "root";     // Mặc định XAMPP là root
$password = "";         // Mặc định XAMPP là rỗng
$dbname = "hc";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
// Để hiển thị tiếng Việt không bị lỗi font
mysqli_set_charset($conn, 'UTF8');
?>