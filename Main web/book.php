<?php
    require_once 'connect.php'; // 1. DATABASE (Kết nối)

    // 2. PHP (Xử lý lấy tên dịch vụ)
    $service_id = $_GET['service_id'] ?? 0;
    $res = $conn->query("SELECT name FROM services WHERE id = $service_id");
    $service = $res->fetch_assoc();

    // Xử lý gửi form
    $success = false;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['fullname'];
        $phone = $_POST['phone'];
        $date = $_POST['date'];
        $sid = $_POST['sid'];

        $sql = "INSERT INTO bookings (service_id, customer_name, phone, booking_date) VALUES ('$sid', '$name', '$phone', '$date')";
        if ($conn->query($sql)) { $success = true; }
    }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đặt lịch</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-book { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 400px; }
        h2 { color: #130569; text-align: center; }
        .input-group { margin-bottom: 15px; }
        input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #30ae4b; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
        .btn:hover { background: #130569; }
    </style>
</head>
<body>

    <div class="form-book">
        <h2>Đăng Ký Khám</h2>
        <p style="text-align: center;">Dịch vụ: <strong><?php echo $service['name'] ?? 'Chưa chọn'; ?></strong></p>
        
        <form id="bookingForm" method="POST">
            <input type="hidden" name="sid" value="<?php echo $service_id; ?>">
            <div class="input-group">
                <input type="text" name="fullname" placeholder="Họ và tên" required>
            </div>
            <div class="input-group">
                <input type="tel" name="phone" placeholder="Số điện thoại" required>
            </div>
            <div class="input-group">
                <input type="date" name="date" id="checkDate" required>
            </div>
            <button type="submit" class="btn">GỬI ĐĂNG KÝ</button>
        </form>
    </div>

    <script>
        // Chặn chọn ngày trước ngày hiện tại
        const dateInput = document.getElementById('checkDate');
        dateInput.min = new Date().toISOString().split("T")[0];

        // Thông báo khi PHP xử lý thành công
        <?php if($success): ?>
            alert("Đăng ký thành công! Chúng tôi sẽ sớm liên hệ với bạn.");
            window.location.href = "index.php"; // Quay lại trang chủ
        <?php endif; ?>
    </script>
</body>
</html>