<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: dangnhap.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #1a6dcc, #0d4d8c);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .welcome {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }

        .btn:hover {
            background: #218838;
        }

        .btn-logout {
            background: #dc3545;
        }

        .btn-logout:hover {
            background: #c82333;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="welcome">👋 Xin chào, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</div>
        <p>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    </div>

    <div class="info">
        <h3>Thông tin tài khoản</h3>
        <p>Bạn đã đăng nhập thành công vào hệ thống y tế.</p>
    </div>

    <div>
        <a href="#" class="btn">Hồ sơ bệnh án</a>
        <a href="#" class="btn">Lịch hẹn</a>
        <a href="#" class="btn">Đổi mật khẩu</a>
        <a href="logout.php" class="btn btn-logout">Đăng xuất</a>
    </div>
</body>

</html>