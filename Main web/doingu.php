    <style>
            .lien {
                background-color: #130569f0;
                width: 100%;
                height: 10vh; 
            }
            .lien p {     
                text-align: center;          
                color: white;
                padding: 10px;
                font-weight: bold;               
                margin: 0 auto;
                font-size: 22px;
            }
            
            .tieude2 {
                padding-bottom: 50px;
                background-color: #fcfcfc;
            }
            .search { 
                margin: 30px auto; 
                background-color: #f1f1f1;
                width: 300px;
                padding: 15px;
                border-radius: 25px; 
                
            }
            i {
                margin-left: 40px;
                margin-right: 10px; 
                color: #888;
            }
            input { 
                background-color: #f1f1f1;
                 border: none; 
                outline: none;
                font-size: 16px; 
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
            } /* tạo dòng xanh dưới tiêu đề */
                
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
             

            .o img {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                object-fit: cover;
                margin-bottom: 15px;
                border: 4px solid #f0f0f0;
                transition: 0.3s;
            }
            
            /* 1. GIÁM ĐỐC -  Viền Vàng */
            .boss { 
                width: 380px; 
                border: 3px solid #b8860b; 
                background: #fffcf0; 
            }
            .boss img { 
                width: 200px; 
                height: 200px; 
                border-color: #b8860b; 
            }

            /* 2. PHÓ GIÁM ĐỐC - Viền Đỏ */
            .bosss { 
                width: 320px; 
                border: 2.5px solid #d9534f; 
                background: #fff9f9; 
            }
            .bosss img { 
                width: 160px; 
                height: 160px; 
                border-color: #d9534f; 
            }

            /* 3. TRƯỞNG KHOA - Viền Xanh Lá */
            .truongkhoa { 
                border: 2px solid #30ae4b; 
                background: #f9fffb; 
            }
            .truongkhoa img { 
                border-color: #30ae4b; 
            }
            /* 4. Bsi bthg*/
            .o h4 { 
                color: #130569; 
                font-size: 20px; }
            .o span {  
                font-weight: bold;
                font-size: 15px;
            }
            .lienhe {
                background: #130569;
                color: white;
                padding: 10px;
                text-align: center;
                font-size: 25px;
            }
            .datlich {
                display: inline-block;
                padding: 10px;
                background: #30ae4b;
                color: white;
                border-radius: 30px;
                text-decoration: none;
                font-weight: bold;
                margin: 20px auto;
                transition: 0.3s;
            }
            .datlich:hover {
                transform: scale(1.05);
                background: #289640;
            }
    </style>
    <div class="lien">
        <p>Hệ thống tổ chức y tế</p>        
    </div> 
    <div class="tieude2">
        <div class="search">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" id="timkiem" placeholder="Nhập tên bác sĩ...">
        </div>
        <?php include 'connect.php'; ?>

        <div style="text-align:center; margin-top:60px;">
            <h2>Giám đốc viện</h2>
        </div>
        
        <div class="col">
            <?php
            $sql_gd = "SELECT b.*, c.ten_chuyen_khoa FROM bac_si b JOIN chuyen_khoa c ON b.id_chuyen_khoa=c.id WHERE chuc_vu LIKE '%Giám đốc%' AND chuc_vu NOT LIKE '%Phó%' ORDER BY id ASC";
            $res_gd = $conn->query($sql_gd);
            while($row = $res_gd->fetch_assoc()) {
                echo '<div class="o boss">
                        <img src="'.$row["hinh_anh"].'" alt="Giám đốc">
                        <h4>'.$row["ho_ten"].'</h4>
                        <span style="color:#b8860b;">'.$row["chuc_vu"].'</span>
                        <h5>'.$row["ten_chuyen_khoa"].'</h5>
                    </div>';
            }
            ?>
        </div>

        <div style="text-align:center; margin-top:60px;">
            <h2>Phó giám đốc viện</h2>
        </div>
        <div class="col">
            <?php
            $sql_pgd = "SELECT b.*, c.ten_chuyen_khoa FROM bac_si b JOIN chuyen_khoa c ON b.id_chuyen_khoa=c.id WHERE chuc_vu LIKE '%Phó Giám đốc%' ORDER BY id ASC";
            $res_pgd = $conn->query($sql_pgd);
            while($row = $res_pgd->fetch_assoc()) {
                echo '<div class="o bosss">
                        <img src="'.$row["hinh_anh"].'" alt="Phó Giám đốc">
                        <h4>'.$row["ho_ten"].'</h4>
                        <span style="color:#d9534f;">'.$row["chuc_vu"].'</span>
                        <h5>'.$row["ten_chuyen_khoa"].'</h5>
                    </div>';
            }
            ?>
        </div>

        <div style="text-align:center; margin-top:60px;">
            <h2>Trưởng khoa chuyên môn</h2>
        </div>
        <div class="col">
            <?php
            $sql_tk = "SELECT b.*, c.ten_chuyen_khoa FROM bac_si b JOIN chuyen_khoa c ON b.id_chuyen_khoa=c.id WHERE chuc_vu LIKE '%Trưởng khoa%' ORDER BY id ASC";
            $res_tk = $conn->query($sql_tk);
            while($row = $res_tk->fetch_assoc()) {
                echo '<div class="o truongkhoa">
                        <img src="'.$row["hinh_anh"].'" alt="Trưởng khoa">
                        <h4>'.$row["ho_ten"].'</h4>
                        <span style="color:#30ae4b;">'.$row["chuc_vu"].'</span>
                        <h5>'.$row["ten_chuyen_khoa"].'</h5>
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
            $sql_staff = "SELECT b.*, c.ten_chuyen_khoa FROM bac_si b JOIN chuyen_khoa c ON b.id_chuyen_khoa=c.id WHERE chuc_vu NOT LIKE '%Giám đốc%' AND chuc_vu NOT LIKE '%Trưởng khoa%' ORDER BY id ASC";
            $res_staff = $conn->query($sql_staff);
            while($row = $res_staff->fetch_assoc()) {
                echo '<div class="o">
                        <img src="'.$row["hinh_anh"].'" alt="Bác sĩ">
                        <h4>'.$row["ho_ten"].'</h4>
                        <span style="color:#0094d9;">'.$row["chuc_vu"].'</span>
                        <h5>'.$row["ten_chuyen_khoa"].'</h5>
                    </div>';
            }
            ?>
        </div>
    </div>

    <div class="lienhe">
        <h3>Liên hệ với chúng tôi</h3>
        <p>Healthcare - niềm tin của mọi gia đình.</p>
        <a href="book.php" class="datlich">ĐẶT LỊCH HẸN TRỰC TUYẾN</a>
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
    <script>
    // Hàm loại bỏ dấu tiếng Việt (Chuyển "Tiếng Việt" thành "Tieng Viet")
    function loaiBoDau(str) {
        return str.normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/đ/g, "d")
            .replace(/Đ/g, "D");
    }

    // Chức năng Tìm kiếm JS thông minh
    const timKiem = document.getElementById('timkiem');
    
    timKiem.addEventListener('keyup', function() {
        const tuKhoaRaw = this.value.toLowerCase(); // Chữ người dùng nhập
        const tuKhoaKhongDau = loaiBoDau(tuKhoaRaw); // Chữ người dùng nhập (đã bỏ dấu)
        
        const danhSachThe = document.querySelectorAll('.o');

        danhSachThe.forEach(the => {
            const tenDichVuRaw = the.querySelector('h4').innerText.toLowerCase();
            const tenDichVuKhongDau = loaiBoDau(tenDichVuRaw);

            // So sánh: Nếu tên gốc hoặc tên không dấu khớp với từ khóa
            if (tenDichVuRaw.includes(tuKhoaRaw) || tenDichVuKhongDau.includes(tuKhoaKhongDau)) {
                the.style.display = ""; 
            } else {
                the.style.display = "none";
            }
        });
    });
    </script>
