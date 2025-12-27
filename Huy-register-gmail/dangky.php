<?php
session_start();
include('connect.php');
ini_set('SMTP', 'huydoquang41@gmail.com');
ini_set('smtp_port', '587');
$error = '';
$success = '';
// error_reporting(E_ALL & ~E_WARNING);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $birth_date = $_POST['birth_date'];
    $cccd = trim($_POST['cccd']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($full_name) || empty($birth_date) || empty($cccd) || empty($email) || empty($phone) || empty($password)) {
        $error = 'Vui lòng điền đầy đủ thông tin!';
    } elseif ($password !== $confirm_password) {
        $error = 'Mật khẩu không khớp!';
    } elseif (strlen($password) < 6) {
        $error = 'Mật khẩu ít nhất 6 ký tự!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ!';
    } elseif (!preg_match('/^[0-9]{12}$/', $cccd)) {
        $error = 'CCCD phải có 9-12 chữ số!';
    } elseif (!preg_match('/^[0-9]{10,11}$/', $phone)) {
        $error = 'SĐT phải có 10-11 chữ số!';
    } else {
        // Kiểm tra CCCD/Email đã tồn tại chưa
        $check_sql = "SELECT id FROM patients WHERE cccd = '$cccd' OR email = '$email'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $error = 'CCCD hoặc Email đã tồn tại trong hệ thống!';
        } else {
            // Mã hóa mật khẩu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Lưu thông tin vào database
            $sql = "INSERT INTO patients (full_name, birth_date, cccd, email, phone, password) 
                    VALUES ('$full_name', '$birth_date', '$cccd', '$email', '$phone', '$hashed_password')";
            // $conn->query($sql)
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $success = '✅ Đăng ký thành công! Email xác nhận đang được gửi...';

                // Lưu session để JavaScript biết cần gửi email
                $_SESSION['just_registered'] = true;
                $_SESSION['reg_email'] = $email;
                $_SESSION['reg_name'] = $full_name;
                $_SESSION['reg_cccd'] = $cccd;
                $_SESSION['reg_phone'] = $phone;
            } else {
                $error = '❌ Lỗi lưu thông tin!';
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
    <title>Đăng Ký</title>
    <link href="dangky.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Đăng Ký Tài Khoản</h1>
            <p>Hệ thống quản lý y tế</p>
        </div>

        <div class="form-body">
            <?php
            if (!empty($error)) {
                echo '<div class="alert error">' . $error . '</div>';
            }
            if (!empty($success)) {
                echo '<div class="alert success">' . $success . '</div>';
            }
            ?>

            <form id="registrationForm" method="POST" action="">
                <div class="input-group">
                    <label>
                        Họ và tên :
                        <input type="text" id="full_name" name="full_name" placeholder="Nhập họ và tên " required>
                    </label>
                </div>

                <div class="input-group">
                    <label>
                        Ngày sinh :
                        <input type="date" id="birth_date" name="birth_date" required>
                    </label>
                </div>

                <div class="input-group">
                    <label>
                        Số CCCD :
                        <input type="text" id="cccd" name="cccd" placeholder="00123456789" required>
                    </label>
                </div>

                <div class="input-group">
                    <label>
                        Email :
                        <input type="email" id="email" name="email" placeholder="email@example.com" required>
                    </label>
                </div>

                <div class="input-group">
                    <label>
                        Số điện thoại :
                        <input type="tel" id="phone" name="phone" placeholder="0987654321" required>
                    </label>
                </div>

                <div class="input-group">
                    <label>
                        Mật khẩu :
                        <input type="password" id="password" name="password" placeholder="••••••" required>
                    </label>
                </div>

                <div class="input-group">
                    <label>
                        Xác nhận mật khẩu :
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••" required>
                    </label>
                </div>

                <button type="submit" class="btn">Đăng Ký Ngay</button>
            </form>

            <div class="links">
                <a href="dangnhap.php">Đăng nhập</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <script>
        // Khởi tạo EmailJS với User ID của bạn
        emailjs.init('pYjzlb9-E1aY1Kfyr');

        // Hàm gửi email xác nhận
        function sendConfirmationEmail(formData) {
            console.log('Đang gửi email xác nhận...', formData);

            // Dữ liệu email
            let emailData = {
                name: formData.full_name,
                email: formData.email,
                phone: formData.phone,
                cccd: formData.cccd.substring(0, 3) + '***' + formData.cccd.substring(formData.cccd.length - 3),
                date: new Date().toLocaleDateString('vi-VN'),
                time: new Date().toLocaleTimeString('vi-VN')
            };

            // Gửi email qua EmailJS
            emailjs.send('service_7ymy4wv', 'template_qifkek5', emailData)
                .then(function(response) {
                    console.log('✅ Email gửi thành công!', response);
                    // Có thể thêm thông báo hoặc cập nhật UI ở đây
                }, function(error) {
                    console.log('❌ Lỗi gửi email:', error);
                    // Vẫn thành công dù email có lỗi   
                });
        }

        // Xử lý khi form submit
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            // Lấy dữ liệu form bằng getElementById
            let formData = {
                full_name: document.getElementById('full_name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                cccd: document.getElementById('cccd').value
            };


            // Lưu vào sessionStorage để trang reload vẫn giữ
            sessionStorage.setItem('pendingEmailData', JSON.stringify(formData));
        });

        // Khi trang load xong
        document.addEventListener('DOMContentLoaded', function() {
            // Kiểm tra nếu vừa đăng ký thành công
            let successAlert = document.querySelector('.alert.success');
            let pendingData = sessionStorage.getItem('pendingEmailData');

            if (successAlert && pendingData) {
                console.log('Đăng ký thành công, đang gửi email xác nhận...');

                let formData = JSON.parse(pendingData);
                sendConfirmationEmail(formData);

                // Xóa dữ liệu tạm
                sessionStorage.removeItem('pendingEmailData');

                // Thêm thông báo nhỏ
                setTimeout(function() {
                    let emailNote = document.createElement('div');
                    emailNote.className = 'alert info';
                    emailNote.innerHTML = '📧 Email xác nhận đã được gửi đến ' + formData.email;
                    document.querySelector('.form-body').insertBefore(emailNote, successAlert.nextSibling);
                }, 1000);
            }

            // Giới hạn ngày sinh không quá hôm nay
            let today = new Date().toISOString().split('T')[0];
            let birthDateInput = document.getElementById('birth_date');
            if (birthDateInput) {
                birthDateInput.max = today;
            }
        });
    </script>
</body>

</html>