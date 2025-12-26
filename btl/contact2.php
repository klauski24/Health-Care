<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Health Care - Liên hệ</title>
  <link rel="stylesheet" href="style2.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    /* CSS bổ sung dành riêng cho trang Contact */
    .contact-wrapper {
        max-width: 1100px;
        margin: 120px auto 50px;
        padding: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
    }

    /* Khối bên trái: Thông tin liên hệ */
    .contact-info {
        flex: 1;
        min-width: 300px;
    }

    .contact-info h2 {
        color: #1679C4;
        font-size: 30px;
        margin-bottom: 20px;
    }

    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        gap: 15px;
    }

    .info-item i {
        font-size: 20px;
        color: #add633;
        width: 40px;
        height: 40px;
        background: #f0f9e8;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    /* Khối bên phải: Form liên hệ */
    .contact-form-container {
        flex: 1.5;
        min-width: 350px;
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #444;
    }

    .form-group input, .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-family: inherit;
    }

    .submit-btn {
        background-color: #1679C4;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        transition: 0.3s;
    }

    .submit-btn:hover {
        background-color: #add633;
        transform: translateY(-2px);
    }

    /* Bản đồ Google Maps */
    .map-container {
        width: 100%;
        height: 300px;
        margin-top: 30px;
        border-radius: 10px;
        overflow: hidden;
    }



/* Đảm bảo iframe luôn lấp đầy khung */
.map-container iframe {
    width: 100%;
    height: 100%;
    filter: grayscale(10%); /* (Tùy chọn) Làm bản đồ hơi xám nhẹ để trông sang hơn */
    transition: 0.3s;
}

.map-container iframe:hover {
    filter: grayscale(0%); /* Hiện màu rõ khi di chuột vào */
}
  </style>
</head>
<body>

<header>
    <div class="logo-header">
      <img src="./Picture/logo.png" alt="Health Care Logo">
    </div>
    <div class="menu-header">
      <nav class="nav-header">
        <a href="index.php">Home</a>
        <a href="#">Services</a>
        <a href="doctors.php">Doctors</a>
        <a href="#">About Us</a>
        <a href="#">Contact</a>
      </nav>
      <nav class="nav-auth">
        <button>Login</button>
      </nav>
  </div>
</header>

<main class="contact-wrapper">
    <div class="contact-info">
        <h2>Liên hệ với chúng tôi</h2>
        <p style="margin-bottom: 30px; color: #666;">Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn 24/7. Hãy gửi yêu cầu cho chúng tôi ngay.</p>

        <div class="info-item">
            <i class="fa fa-map-marker"></i>
            <div>
                <strong>Địa chỉ:</strong>
                <p>Trường Đại học Mỏ - Địa chất</p>
            </div>
        </div>

        <div class="info-item">
            <i class="fa fa-phone"></i>
            <div>
                <strong>Điện thoại:</strong>
                <p>0338180117</p>
            </div>
        </div>

        <div class="info-item">
            <i class="fa fa-envelope"></i>
            <div>
                <strong>Email:</strong>
                <p>support@healthcare.vn</p>
            </div>
        </div>

        <div class="map-container">
            <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.014820440838!2d105.77134870929882!3d21.07207028050718!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3134552defbed8e9%3A0x1584f79c805eb017!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBN4buPIC0gxJDhu4thIGNo4bqldA!5e0!3m2!1svi!2s!4v1766649806908!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        </div>
    </div>

    <div class="contact-form-container">
        <form action="#" method="POST">
            <div class="form-group">
                <label>Họ và tên</label>
                <input type="text" name="name" placeholder="Ví dụ: Nguyễn Văn A" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="email@gmail.com" required>
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="tel" name="phone" placeholder="090x xxx xxx">
            </div>

            <div class="form-group">
                <label>Chuyên khoa cần tư vấn</label>
                <select style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                    <option>Tim mạch</option>
                    <option>Sản phụ khoa</option>
                    <option>Nhi khoa</option>
                    <option>Khác</option>
                </select>
            </div>

            <div class="form-group">
                <label>Nội dung lời nhắn</label>
                <textarea name="message" rows="5" placeholder="Bạn cần hỗ trợ điều gì?"></textarea>
            </div>

            <button type="submit" class="submit-btn">Gửi lời nhắn ngay</button>
        </form>
        <?php
        $host = "localhost";
        $username = "root"; 
        $password = "";     
        $dbname = "health_care"; 

        $conn = new mysqli($host, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Kết nối thất bại: " . $conn->connect_error);
        }

        // 2. Kiểm tra nếu người dùng nhấn nút gửi
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $ho_ten = mysqli_real_escape_string($conn, $_POST['name']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $sdt = mysqli_real_escape_string($conn, $_POST['phone']);
            $noi_dung = mysqli_real_escape_string($conn, $_POST['message']);


            $sql = "INSERT INTO lien_he (ho_ten, email, so_dien_thoai, noi_dung) 
                    VALUES ('$ho_ten', '$email', '$sdt', '$noi_dung')";

            if ($conn->query($sql) === TRUE) {
                echo "<p style='color:green; margin-top:15px;'>Lời nhắn của bạn đã được gửi thành công!</p>";
            } else {
                echo "Lỗi: " . $sql . "<br>" . $conn->error;
            }
        }

        $conn->close();
        ?>
    </div>
</main>

</body>
</html>