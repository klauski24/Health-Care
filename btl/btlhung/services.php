<?php
    // 1. Ket noi database (Su dung require_once de dam bao an toan)
    require_once 'connect.php';

    // 2. Lay danh sach dich vu tu bang 'services' moi cua ban
    // SQL sap xep theo ID moi nhat len dau
    $sql = "SELECT * FROM services ORDER BY id DESC";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dịch Vụ Cao Cấp - Healthcare</title>
    
    <style>
        /* --- PHAN CSS (Style tieng Viet khong dau) --- */
        :root {
            --mau-chinh: #130569;
            --mau-phu: #30ae4b;
            --mau-xanh-sang: #0094d9;
            --mau-nen: #f8f9fa;
            --mau-chu: #333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--mau-nen);
            color: var(--mau-chu);
            line-height: 1.6;
        }

        /* Banner dau trang */
        .banner-dich-vu {
            background: linear-gradient(135deg, rgba(19, 5, 105, 0.9), rgba(0, 148, 217, 0.8)), 
                        url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&q=80&w=2000');
            background-size: cover;
            background-position: center;
            height: 40vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 20px;
        }

        .banner-noi-dung h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Khung chua danh sach */
        .khung-tong {
            max-width: 1200px;
            margin: -50px auto 50px auto;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }

        .tieu-de-phu {
            text-align: center;
            margin-bottom: 40px;
            color: var(--mau-chinh);
        }

        .tieu-de-phu h2 {
            font-size: 2rem;
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
        }

        .tieu-de-phu h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--mau-phu);
            border-radius: 2px;
        }

        /* Luoi hien thi the */
        .luoi-dich-vu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        /* The dich vu chi tiet */
        .the-dich-vu {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            display: flex;
            flex-direction: column;
            opacity: 0; 
            transform: translateY(30px);
        }

        .the-dich-vu:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .anh-dich-vu {
            height: 220px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .gia-tien-nhan {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: var(--mau-phu);
            color: white;
            padding: 5px 15px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 0.9rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .noi-dung-the {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .noi-dung-the h3 {
            color: var(--mau-chinh);
            margin-bottom: 15px;
            font-size: 1.4rem;
        }

        .noi-dung-the p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .nut-dat-lich {
            margin-top: auto;
            display: inline-block;
            text-align: center;
            background: var(--mau-chinh);
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: bold;
            transition: 0.3s;
        }

        .nut-dat-lich:hover {
            background: var(--mau-xanh-sang);
            transform: scale(1.02);
        }

        @media (max-width: 768px) {
            .banner-noi-dung h1 { font-size: 2rem; }
            .khung-tong { margin-top: 20px; }
        }
    </style>
</head>
<body>

    <header class="banner-dich-vu">
        <div class="banner-noi-dung">
            <h1>Dịch Vụ Healthcare</h1>
            <p>Hệ thống chăm sóc sức khỏe toàn diện - Uy tín - Tận tâm</p>
        </div>
    </header>

    <main class="khung-tong">
        <div class="tieu-de-phu">
            <h2>Giải Pháp Y Tế Hàng Đầu</h2>
        </div>

        <div class="luoi-dich-vu">
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Chuan hoa du lieu tu database moi cua ban
                    $formatted_price = number_format($row["price"], 0, ',', '.');
                    $image_path = !empty($row["image"]) ? $row["image"] : 'https://via.placeholder.com/400x300?text=Healthcare';
            ?>
                <article class="the-dich-vu">
                    <div class="anh-dich-vu" style="background-image: url('<?php echo $image_path; ?>');">
                        <div class="gia-tien-nhan"><?php echo $formatted_price; ?> VNĐ</div>
                    </div>
                    
                    <div class="noi-dung-the">
                        <h3><?php echo htmlspecialchars($row["name"]); ?></h3>
                        <p><?php echo htmlspecialchars($row["description"]); ?></p>
                        
                        <a href="booking.php?service_id=<?php echo $row['id']; ?>" class="nut-dat-lich">
                            Đăng ký tư vấn ngay
                        </a>
                    </div>
                </article>
            <?php 
                }
            } else {
                echo "<div style='grid-column: 1/-1; text-align:center; padding: 50px;'>
                        <p>Hiện chưa có dịch vụ nào trong hệ thống.</p>
                      </div>";
            }
            ?>
        </div>
    </main>

    <script>
        // Hieu ung Fade-in khi cuon trang
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.the-dich-vu');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            cards.forEach(card => observer.observe(card));
        });
    </script>

</body>
</html>
<?php $conn->close(); ?>