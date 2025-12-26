<?php
// send_email.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendRegistrationEmail($to, $name, $cccd, $phone) {
    $mail = new PHPMailer(true);
    
    try {
        // Cấu hình SMTP cho Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com'; // Thay bằng email của bạn
        $mail->Password = 'your-app-password'; // App Password từ Google
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Người gửi và người nhận
        $mail->setFrom('your-email@gmail.com', 'Health Care System');
        $mail->addAddress($to, $name);
        
        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = 'Đăng ký tài khoản thành công - Health Care';
        
        $mail->Body = "
            <h2>Chào mừng $name đến với Health Care!</h2>
            <p>Tài khoản của bạn đã được tạo thành công.</p>
            
            <h3>Thông tin đăng ký:</h3>
            <ul>
                <li><strong>Họ tên:</strong> $name</li>
                <li><strong>Email:</strong> $to</li>
                <li><strong>CCCD:</strong> " . substr($cccd, 0, 3) . '***' . substr($cccd, -3) . "</li>
                <li><strong>Số điện thoại:</strong> $phone</li>
                <li><strong>Ngày đăng ký:</strong> " . date('d/m/Y H:i:s') . "</li>
            </ul>
            
            <p>Vui lòng giữ bí mật thông tin tài khoản của bạn.</p>
            <p>Trân trọng,<br>Đội ngũ Health Care</p>
        ";
        
        $mail->AltBody = "Chào $name! Đăng ký thành công. Thông tin: $to, CCCD: " . substr($cccd, 0, 3) . '***' . substr($cccd, -3) . ", SĐT: $phone";
        
        if ($mail->send()) {
            return true;
        } else {
            error_log('PHPMailer Error: ' . $mail->ErrorInfo);
            return false;
        }
        
    } catch (Exception $e) {
        error_log('PHPMailer Exception: ' . $e->getMessage());
        return false;
    }
}
?>