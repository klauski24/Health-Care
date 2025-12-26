<?php
include(__DIR__ . '/../connect.php');

$error = '';
$success = '';
$step = isset($_GET['step']) ? $_GET['step'] : 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($step == 1) {
        $email = trim($_POST['email']);

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Email không hợp lệ!';
        } else {
            $check_sql = "SELECT id FROM patients WHERE email = '$email'";
            $check_result = $conn->query($check_sql);

            if ($check_result->num_rows == 0) {
                $error = 'Email không tồn tại!';
            } else {
                $reset_token = rand(100000, 999999);
                $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));

                $conn->query("DELETE FROM password_resets WHERE email = '$email'");
                $sql = "INSERT INTO password_resets (email, reset_token, expires_at) 
                        VALUES ('$email', '$reset_token', '$expires_at')";

                if ($conn->query($sql)) {
                    $_SESSION['reset_email'] = $email;
                    $_SESSION['reset_code'] = $reset_token;
                    header('Location: quenmatkhau.php?step=2');
                    exit();
                } else {
                    $error = 'Lỗi hệ thống!';
                }
            }
        }
    } elseif ($step == 2) {
        $code = trim($_POST['code']);
        $email = $_SESSION['reset_email'] ?? '';

        if (empty($code) || !preg_match('/^[0-9]{6}$/', $code)) {
            $error = 'Mã phải có 6 số!';
        } else {
            $session_code = $_SESSION['reset_code'] ?? '';
            if ($code == $session_code) {
                $_SESSION['reset_verified'] = true;
                header('Location: quenmatkhau.php?step=3');
                exit();
            }

            $sql = "SELECT * FROM password_resets 
                    WHERE email = '$email' 
                    AND reset_token = '$code' 
                    AND expires_at > NOW()";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $_SESSION['reset_verified'] = true;
                header('Location: quenmatkhau.php?step=3');
                exit();
            } else {
                $error = 'Mã không đúng!';
            }
        }
    } elseif ($step == 3) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $email = $_SESSION['reset_email'] ?? '';

        if (empty($new_password) || empty($confirm_password)) {
            $error = 'Vui lòng nhập đầy đủ!';
        } elseif ($new_password !== $confirm_password) {
            $error = 'Mật khẩu không khớp!';
        } elseif (strlen($new_password) < 6) {
            $error = 'Mật khẩu ít nhất 6 ký tự!';
        } else {
            // KIỂM TRA MẬT KHẨU MỚI CÓ KHÁC MẬT KHẨU CŨ KHÔNG
            $check_password_sql = "SELECT password FROM patients WHERE email = '$email'";
            $password_result = $conn->query($check_password_sql);

            if ($password_result->num_rows > 0) {
                $row = $password_result->fetch_assoc();
                $old_hashed_password = $row['password'];

                // So sánh mật khẩu mới với mật khẩu cũ
                if (password_verify($new_password, $old_hashed_password)) {
                    $error = 'Mật khẩu mới phải khác mật khẩu cũ!';
                } else {
                    // Mật khẩu hợp lệ, tiến hành đổi
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_sql = "UPDATE patients SET password = '$hashed_password' WHERE email = '$email'";

                    if ($conn->query($update_sql)) {
                        $conn->query("DELETE FROM password_resets WHERE email = '$email'");
                        unset($_SESSION['reset_email'], $_SESSION['reset_verified'], $_SESSION['reset_code']);
                        $success = '✅ Đổi mật khẩu thành công!';
                        $step = 4;
                    } else {
                        $error = 'Lỗi hệ thống!';
                    }
                }
            } else {
                $error = 'Không tìm thấy thông tin tài khoản!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial;
        }

        body {
            background: #4361ee;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 10px;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #4361ee;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .form-body {
            padding: 20px;
        }

        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        .info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #4361ee;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn:hover {
            background: #3a0ca3;
        }

        .links {
            text-align: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .links a {
            color: #4361ee;
            text-decoration: none;
        }

        .hint {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Quên Mật Khẩu</h1>
            <p>Hệ thống y tế</p>
        </div>

        <div class="form-body">
            <?php
            if (!empty($error)) echo '<div class="alert error">' . $error . '</div>';
            if (!empty($success)) echo '<div class="alert success">' . $success . '</div>';
            ?>

            <?php if ($step == 1): ?>
                <form method="POST">
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Nhập email" required>
                    </div>
                    <div class="alert info">Nhập email để nhận mã xác nhận</div>
                    <button type="submit" class="btn">Gửi mã</button>
                </form>

            <?php elseif ($step == 2): ?>
                <form method="POST">
                    <div class="input-group">
                        <input type="text" name="code" placeholder="Nhập mã 6 số" maxlength="6" required>
                    </div>
                    <div class="alert info">
                        Mã gửi đến: <strong><?php echo $_SESSION['reset_email'] ?? ''; ?></strong><br>
                    </div>
                    <button type="submit" class="btn">Xác nhận</button>
                </form>

            <?php elseif ($step == 3): ?>
                <form method="POST">
                    <div class="input-group">
                        <input type="password" name="new_password" placeholder="Mật khẩu mới" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu" required>
                    </div>
                    <div class="hint">Mật khẩu mới phải khác mật khẩu cũ</div>
                    <button type="submit" class="btn">Đổi mật khẩu</button>
                </form>

            <?php elseif ($step == 4): ?>
                <div class="alert success">✅ Đổi mật khẩu thành công!</div>
                <div class="links"><a href="dangnhap.php">Đăng nhập ngay</a></div>
            <?php endif; ?>

            <div class="links"><a href="index.php?page_layout=login">← Quay lại đăng nhập</a></div>
        </div>
    </div>

    <!-- PHẦN GỬI EMAIL - CHỈ KHI Ở BƯỚC 2 -->
    <?php if ($step == 2 && isset($_SESSION['reset_email']) && isset($_SESSION['reset_code'])): ?>
        <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
        <script>
            emailjs.init('pYjzlb9-E1aY1Kfyr');

            let email = "<?php echo $_SESSION['reset_email']; ?>";
            let code = "<?php echo $_SESSION['reset_code']; ?>";

            if (email && code) {
                let emailData = {
                    to_email: email,
                    reset_code: code,
                    date: new Date().toLocaleDateString('vi-VN')
                };

                emailjs.send('service_7ymy4wv', 'template_j7mck1b', emailData)
                    .then(() => console.log('Đã gửi email'))
                    .catch(error => console.log('Lỗi gửi email:', error));
            }
        </script>
    <?php endif; ?>
</body>

</html>