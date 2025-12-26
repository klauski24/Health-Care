<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đội ngũ y tế - Healthcare</title>
    <style>
            .lien {
                background-color: #130569f0;
                width: 100%;
                height: 10vh; 
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .lien p {     
                color: white;
                font-weight: bold;               
                font-size: 22px;
            }
            
            .tieude2 {
                padding-bottom: 50px;
                background-color: #fcfcfc;
            }

            h2 {
                color: #1d7f2aff;
                font-size: 30px;
                position: relative;
                display: inline-block;
                padding-bottom: 10px;
                text-transform: uppercase;
            }
            h2::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 3px;
                background: #30ae4b;
            }

            .col {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 30px;
                padding: 20px 10%;
            }

            /* Thẻ bác sĩ chung */
            .o {
                background: white;
                text-align: center;
                width: 260px;
                padding: 30px 15px;
                border-radius: 15px;
                transition: all 0.4s ease;
                box-shadow: 0 10px 25px rgba(0,0,0,0.05);
                border: 1px solid #eee;
                display: flex;
                flex-direction: column;
                align-items: center;
            } 
            .o:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 30px rgba(30, 144, 72, 0.2);
                border-color: #30ae4b;
            }

            .o img {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                object-fit: cover;
                margin-bottom: 15px;
                border: 4px solid #f0f0f0;
                transition: 0.3s;
            }

            /* --- PHÂN CẤP CHI TIẾT --- */
            
            /* 1. GIÁM ĐỐC - To nhất, Viền Vàng */
            .boss-style { width: 380px; border: 3px solid #b8860b; background: #fffcf0; }
            .boss-style img { width: 200px; height: 200px; border-color: #b8860b; }
            .boss-style h4 { font-size: 26px; }

            /* 2. PHÓ GIÁM ĐỐC - Viền Đỏ */
            .leader-style { width: 320px; border: 2.5px solid #d9534f; background: #fff9f9; }
            .leader-style img { width: 160px; height: 160px; border-color: #d9534f; }

            /* 3. TRƯỞNG KHOA - Viền Xanh Lá */
            .dept-head-style { border: 2px solid #30ae4b; background: #f9fffb; }
            .dept-head-style img { border-color: #30ae4b; }

            .o h4 { color: #130569; margin: 10px 0 5px 0; font-size: 20px; }
            .o span {
                display: block;
                font-weight: bold;
                font-size: 15px;
                text-transform: uppercase;
                margin-bottom: 10px;
                letter-spacing: 1px;
            }
            .o p { color: #666; font-size: 14px; line-height: 1.5; }

            /* Phần liên hệ nhanh */
            .quick-contact {
                background: #130569;
                color: white;
                padding: 60px 20px;
                text-align: center;
            }
            .btn-contact {
                display: inline-block;
                padding: 15px 40px;
                background: #30ae4b;
                color: white;
                border-radius: 30px;
                text-decoration: none;
                font-weight: bold;
                margin-top: 25px;
                transition: 0.3s;
            }
            .btn-contact:hover {
                transform: scale(1.05);
                background: #289640;
            }
    </style>
</head>
<body>
    <div class="lien">
        <p>HỆ THỐNG TỔ CHỨC Y TẾ</p>        
    </div>

    
    <div class="tieude2">
        <?php include 'connect.php'; ?>

        <div style="text-align:center; margin-top:50px;">
            <h2>Giám đốc viện</h2>
        </div>
        <div class="col">
            <?php
            $sql_gd = "SELECT * FROM doctors WHERE title LIKE '%Giám đốc%' AND title NOT LIKE '%Phó%' LIMIT 1";
            $res_gd = $conn->query($sql_gd);
            while($row = $res_gd->fetch_assoc()) {
                echo '<div class="o boss-style">
                        <img src="'.$row["image_url"].'" alt="Giám đốc">
                        <h4>'.$row["full_name"].'</h4>
                        <span style="color:#b8860b;">'.$row["title"].'</span>
                        <p>'.$row["specialty"].'</p>
                    </div>';
            }
            ?>
        </div>

        <div style="text-align:center; margin-top:60px;">
            <h2>Phó giám đốc viện</h2>
        </div>
        <div class="col">
            <?php
            $sql_pgd = "SELECT * FROM doctors WHERE title LIKE '%Phó Giám đốc%' ORDER BY doctor_id ASC";
            $res_pgd = $conn->query($sql_pgd);
            while($row = $res_pgd->fetch_assoc()) {
                echo '<div class="o leader-style">
                        <img src="'.$row["image_url"].'" alt="Phó Giám đốc">
                        <h4>'.$row["full_name"].'</h4>
                        <span style="color:#d9534f;">'.$row["title"].'</span>
                        <p>'.$row["specialty"].'</p>
                    </div>';
            }
            ?>
        </div>

        <div style="text-align:center; margin-top:60px;">
            <h2>Trưởng khoa chuyên môn</h2>
        </div>
        <div class="col">
            <?php
            $sql_tk = "SELECT * FROM doctors WHERE title LIKE '%Trưởng khoa%' ORDER BY doctor_id ASC";
            $res_tk = $conn->query($sql_tk);
            while($row = $res_tk->fetch_assoc()) {
                echo '<div class="o dept-head-style">
                        <img src="'.$row["image_url"].'" alt="Trưởng khoa">
                        <h4>'.$row["full_name"].'</h4>
                        <span style="color:#30ae4b;">'.$row["title"].'</span>
                        <p>'.$row["specialty"].'</p>
                    </div>';
            }
            ?>
        </div>

        <div style="text-align:center; margin-top:60px;">
            <h2>Đội ngũ bác sĩ điều trị</h2>
        </div>
        <div class="col">
            <?php
            // Lấy những người không thuộc 3 nhóm trên
            $sql_staff = "SELECT * FROM doctors WHERE title NOT LIKE '%Giám đốc%' AND title NOT LIKE '%Trưởng khoa%' ORDER BY doctor_id ASC";
            $res_staff = $conn->query($sql_staff);
            while($row = $res_staff->fetch_assoc()) {
                echo '<div class="o">
                        <img src="'.$row["image_url"].'" alt="Bác sĩ">
                        <h4>'.$row["full_name"].'</h4>
                        <span style="color:#0094d9;">'.$row["title"].'</span>
                        <p>'.$row["specialty"].'</p>
                    </div>';
            }
            ?>
        </div>
    </div>

    <div class="quick-contact">
        <h3>Liên hệ với chúng tôi để được tư vấn chuyên sâu</h3>
        <p>Đội ngũ chuyên gia luôn sẵn sàng hỗ trợ bạn 24/7.</p>
        <a href="contact.php" class="btn-contact">ĐẶT LỊCH HẸN TRỰC TUYẾN</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.o');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(40px)';
                card.style.transition = 'all 0.7s ease-out';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>