<?php
require_once 'connect.php';
$sql = "SELECT * FROM dich_vu ORDER BY id DESC";
$result = $conn->query($sql);
?>
    <style>
        .services-page {
            margin-top: 94px;
        }
        .tieude1 {
            background: linear-gradient(#130569, rgba(10, 122, 174, 0.8));
            height: 30vh;
            text-align: center;
            color: white;
            font-size: 30px;
        }
        .tieude11 {
            padding-top: 50px;
            
        }
        .tieude11 p {
            font-style: Italic;
        }
        .tieude1 h2 {
            margin-top: 0px;
        }
        .tieude2 {
            width: 70%;
            margin: 0 auto;
        }
        .gp {
            text-align: center;
            margin-bottom: 40px;
            color: #130569;
            font-size: 22px;
        }
        .gp1 {    
            border-top: 5px solid #30ae4b;
            width: 25%;
            margin: -20px auto;       
        }
        .search { 
            margin-top: 20px; 
        }
        input { 
            background-color: #f1f1f1;
            padding: 10px 20px; 
            width: 500px; 
            border-radius: 25px; 
            border: none; 
            outline: none; 
            font-size: 16px; 
        }
        .dichvu {
            display: grid;
            grid-template-columns: repeat(3, minmax(320px, 1fr));
            gap: 30px;
        }
        .the-dich-vu {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 10px rgba(69, 213, 61, 0.45);
            transition: all 0.4s ease;
        }
        .the-dich-vu:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(20, 161, 15, 0.81);
        }
        .anh-dich-vu {
            height: 220px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .giatien {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: #130569;
            color: white;
            padding: 5px 15px;
            border-radius: 30px;
            font-weight: bold;
        }
        .noi-dung-the {
            padding: 25px;
        }
        .noi-dung-the h2 {
            color: #130569;
        }
        .noi-dung-the p {
            color: #918989ff;
            font-size: 17px;
        }
        .xemchitiet {
            display: inline-block; /* để transform : scale hoạt động */
            text-align: center;
            background: #0cec4fff;
            color: white;
            text-decoration: none;
            padding: 10px;
            border-radius: 12px;
            font-weight: bold;
            transition: 0.3s;
        }
        .xemchitiet:hover {
            background: #98f38cff;
            transform: scale(1.1);
            /* transform: translateY(-5px); */
        }
        .datlich {
            display: inline-block; /* để transform : scale hoạt động */
            text-align: center;
            background: #130569;
            color: white;
            text-decoration: none;
            padding: 10px;
            border-radius: 12px;
            font-weight: bold;
            transition: 0.3s;
        }
        .datlich:hover {
            background: #c3bcf4ff;
            transform: scale(1.1);
        }
        .kcach {
            height: 50px; 
        }
    </style>
<div class="services-page">
    <div class="tieude1">
            <div class="tieude11">
                <h2>Dịch Vụ Healthcare</h2>
                <p>Hệ thống chăm sóc sức khỏe toàn diện - Uy tín - Tận tâm</p>
            </div>
    </div>
    <main class="tieude2">
        <div class="gp">
            <h2>Giải Pháp Y Tế Hàng Đầu</h2>
            <div class="gp1"></div>
            <div class="kcach"></div>
            <div class="search">
                <input type="text" id="timkiem" placeholder="Nhập tên dịch vụ...">
            </div>
        </div>
        <div class="dichvu">
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $gia = number_format($row["gia"], 0, ',', '.');
                    $anh = !empty($row["hinh_anh"]) ? $row["hinh_anh"] : 'https://via.placeholder.com/400x300';
            ?>
                <div class="the-dich-vu">
                    <div class="anh-dich-vu" style="background-image: url('<?php echo $anh; ?>');">
                        <div class="giatien"><?php echo $gia; ?> VNĐ</div>
                    </div>
                    <div class="noi-dung-the">
                        <h2 class="ten-dv"><?php echo htmlspecialchars($row["ten_dich_vu"]); ?></h2>
                        <p><?php echo htmlspecialchars($row["mo_ta"]); ?></p>
                        <div style="display: flex; justify-content: center; gap: 20px;">
                            <a href="index.php?page_layout=xemchitietdichvu&service_id=<?php echo $row['id']; ?>" 
                                class="xemchitiet" 
                                onclick="return xacNhanXem('<?php echo $row['ten_dich_vu']; ?>')">
                                Xem chi tiết
                            </a>
                            <a href="index.php?page_layout=khamdichvu&service_id=<?php echo $row['id']; ?>" 
                                class="datlich" 
                                onclick="return xacNhanDatLich('<?php echo $row['ten_dich_vu']; ?>')">
                                Đặt lịch khám
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
                }
            }
            ?>
        </div>
        <div class="kcach"></div>
    </main>

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
        
        const danhSachThe = document.querySelectorAll('.the-dich-vu');

        danhSachThe.forEach(the => {
            const tenDichVuRaw = the.querySelector('.ten-dv').innerText.toLowerCase();
            const tenDichVuKhongDau = loaiBoDau(tenDichVuRaw);

            // So sánh: Nếu tên gốc hoặc tên không dấu khớp với từ khóa
            if (tenDichVuRaw.includes(tuKhoaRaw) || tenDichVuKhongDau.includes(tuKhoaKhongDau)) {
                the.style.display = ""; 
            } else {
                the.style.display = "none";
            }
        });
    });

    function xacNhanXem(ten) {
        return confirm("Bạn muốn xem chi tiết gói dịch vụ: " + ten + " ư?");
    }
    function xacNhanDatLich(ten) {
        return confirm("Bạn muốn đăng ký đặt lịch cho dịch vụ: " + ten + " ư?");
    }
</script>
</div>
