<?php
// Kết nối MySQL (không chọn database ngay)
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'health_care';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Không thể kết nối MySQL: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$error = '';
$success = '';

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Vui lòng nhập email và mật khẩu!';
    } else {
        $stmt = $conn->prepare("SELECT id, full_name, password FROM patients WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // DEBUG: KIỂM TRA
            echo "<pre>User từ DB: ";
            print_r($user);
            echo "Password verify: " . (password_verify($password, $user['password']) ? 'TRUE' : 'FALSE');
            echo "</pre>";

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $email;

                // DEBUG: KIỂM TRA SESSION
                echo "<pre>Session sau khi lưu: ";
                print_r($_SESSION);
                echo "</pre>";

                $success = '✅ Đăng nhập thành công!';
                header("Location: index.php?page_layout=mainweb");
                exit(); 
            } else {
                $error = '❌ Mật khẩu không đúng!';
            }
        } else {
            $error = '❌ Email không tồn tại!';
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1a6dcc, #0d4d8c);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .box {
            width: 100%;
            max-width: 400px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }

        .title {
            text-align: center;
            margin-bottom: 25px;
        }

        .icon {
            font-size: 40px;
            display: block;
        }

        .title h2 {
            color: #1a6dcc;
            font-size: 22px;
            margin: 10px 0;
        }

        .message {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .input-field {
            position: relative;
        }

        .input-field input {
            width: 100%;
            padding: 12px 40px 12px 12px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }

        .input-field input:focus {
            outline: none;
            border-color: #1a6dcc;
        }

        .show-pwd {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            font-size: 18px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #1a6dcc;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn:hover {
            background: #0d4d8c;
        }

        .links {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .links a {
            color: #1a6dcc;
            text-decoration: none;
            margin: 0 8px;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="box">
        <div class="title">
            <span class="icon">👨‍⚕️</span>
            <h2>Đăng Nhập Hệ Thống</h2>
        </div>

        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <div class="input-field">
                    <input type="email" name="email"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                        placeholder="Email" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-field">
                    <input type="password" name="password" id="password"
                        placeholder="Mật khẩu" required>
                    <button type="button" class="show-pwd" onclick="togglePassword()">👁️</button>
                </div>
            </div>

            <button type="submit" class="btn">Đăng Nhập</button>
        </form>

        <div class="links">
            <a href="index.php?page_layout=register">Đăng ký</a> |
            <a href="index.php?page_layout=forgot_password">Quên mật khẩu?</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const button = document.querySelector('.show-pwd');

            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = '🙈';
            } else {
                input.type = 'password';
                button.textContent = '👁️';
            }
        }

        // Tự động focus vào ô email
        window.onload = function() {
            document.querySelector('input[name="email"]').focus();
        };
    </script>
</body>

</html>