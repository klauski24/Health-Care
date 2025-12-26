<?php
include(__DIR__ . '/../connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $code = $_POST['code'] ?? '';

    if ($email && $code) {
        $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Xóa token cũ
        $conn->query("DELETE FROM password_resets WHERE email = '$email'");

        // Lưu token mới
        $sql = "INSERT INTO password_resets (email, reset_token, expires_at) 
                VALUES ('$email', '$code', '$expires_at')";

        if ($conn->query($sql)) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
}
