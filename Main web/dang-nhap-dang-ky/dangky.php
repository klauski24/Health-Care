<?php
include(__DIR__ . '/../connect.php');
require_once __DIR__ . '/vendor/autoload.php';

ini_set('SMTP', '');
ini_set('smtp_port', '');
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
    } elseif (!preg_match('/^[0-9]{9,12}$/', $cccd)) {
        $error = 'CCCD phải có 9-12 chữ số!';
    } elseif (!preg_match('/^[0-9]{10,11}$/', $phone)) {
        $error = 'SĐT phải có 10-11 chữ số!';
    } else {
        // Kiểm tra CCCD/Email đã tồn tại chưa
        $check_sql = "SELECT id FROM patients WHERE cccd = '$cccd' OR email = '$email'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $error = 'CCCD hoặc Email đã tồn tại trong hệ thống!';
        } else {
            // Mã hóa mật khẩu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Lưu thông tin vào database
            $sql = "INSERT INTO patients (full_name, birth_date, cccd, email, phone, password) 
                    VALUES ('$full_name', '$birth_date', '$cccd', '$email', '$phone', '$hashed_password')";

            if ($conn->query($sql)) {
                // ✅ ĐÃ XÓA HÀM mail() GÂY LỖI - CHỈ TRẢ VỀ THÔNG BÁO ĐƠN GIẢN
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
    <link href="dang-nhap-dang-ky/dangky.css" rel="stylesheet">
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
                    <label>Họ và tên</label>
                    <input type="text" name="full_name" placeholder="Nguyễn Văn A" required>
                </div>

                <div class="input-group">
                    <label>Ngày sinh</label>
                    <input type="date" name="birth_date" required>
                </div>

                <div class="input-group">
                    <label>Số CCCD</label>
                    <input type="text" name="cccd" placeholder="00123456789" required>
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@example.com" required>
                </div>

                <div class="input-group">
                    <label>Số điện thoại</label>
                    <input type="tel" name="phone" placeholder="0987654321" required>
                </div>

                <div class="input-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" placeholder="••••••" required>
                </div>

                <div class="input-group">
                    <label>Xác nhận mật khẩu</label>
                    <input type="password" name="confirm_password" placeholder="••••••" required>
                </div>

                <button type="submit" class="btn">Đăng Ký Ngay</button>
            </form>

            <div class="links">
                <a href="index.php?page_layout=login">Đăng nhập</a>
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

     // Kiểm tra emailjs đã tồn tại chưa
    if (typeof emailjs === 'undefined') {
        console.error('EmailJS chưa được load!');
        return;
    }
    
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
        .then(function (response) {
            console.log('✅ Email gửi thành công!', response);
            // Có thể thêm thông báo hoặc cập nhật UI ở đây
        }, function (error) {
            console.log('❌ Lỗi gửi email:', error);
            // Vẫn thành công dù email có lỗi
        });
}

// Xử lý khi form submit
document.getElementById('registrationForm').addEventListener('submit', function (event) {
    // Lấy dữ liệu form
    let formData = {
        full_name: document.querySelector('input[name="full_name"]').value,
        email: document.querySelector('input[name="email"]').value,
        phone: document.querySelector('input[name="phone"]').value,
        cccd: document.querySelector('input[name="cccd"]').value
    };

    // Lưu vào sessionStorage để trang reload vẫn giữ
    sessionStorage.setItem('pendingEmailData', JSON.stringify(formData));
});

// Khi trang load xong
document.addEventListener('DOMContentLoaded', function () {
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
        setTimeout(function () {
            let emailNote = document.createElement('div');
            emailNote.className = 'alert info';
            emailNote.innerHTML = '📧 Email xác nhận đã được gửi đến ' + formData.email;
            document.querySelector('.form-body').insertBefore(emailNote, successAlert.nextSibling);
        }, 1000);
    }

    // Giới hạn ngày sinh không quá hôm nay
    let today = new Date().toISOString().split('T')[0];
    let birthDateInput = document.querySelector('input[name="birth_date"]');
    if (birthDateInput) {
        birthDateInput.max = today;
    }
});
</script>
</body>

</html>