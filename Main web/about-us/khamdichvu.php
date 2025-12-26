<?php
include(__DIR__ . '/../connect.php');

// 1. Lấy thông tin dịch vụ từ URL (service_id)
$dich_vu = null;
if (isset($_GET['service_id'])) {
    $id_dv = intval($_GET['service_id']);
    // Truy vấn lấy tên dịch vụ từ bảng dich_vu
    $dv_query = "SELECT * FROM dich_vu WHERE id = $id_dv";
    $dv_result = $conn->query($dv_query);
    if ($dv_result && $dv_result->num_rows > 0) {
        $dich_vu = $dv_result->fetch_assoc();
    }
}

// Nếu không có dịch vụ, quay lại trang danh sách
if (!$dich_vu) {
    header("Location: services.php");
    exit();
}
?>

    <title>Đăng ký dịch vụ - <?php echo htmlspecialchars($dich_vu['ten_dich_vu']); ?></title>
    <link rel="stylesheet" href="about-us/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .phan-buoc { display: none; opacity: 0; transition: all 0.3s ease; }
        .phan-buoc.dang-kich-hoat { display: block; opacity: 1; }
        .khung-buoi { display: flex; gap: 20px; margin-top: 15px; }
        .chon-buoi { 
            flex: 1; padding: 15px; border: 1px solid #ddd; border-radius: 10px; 
            text-align: center; cursor: pointer; transition: 0.3s;
        }
        .chon-buoi:hover { background: #f0f4ff; }
        .chon-buoi.da-chon { background: #130569; color: white; border-color: #130569; }
        .tom-tat { background: #f9f9f9; padding: 20px; border-radius: 10px; border: 1px solid #eee; }
    </style>
<div class="kham-dich-vu-page">
    <div class="khung-chua">
        <header>
            <div class="bieu-tuong">
                <i class="fas fa-hospital-user"></i>
                <h1>HỆ THỐNG ĐẶT LỊCH KHÁM</h1>
            </div>
            <div class="cac-buoc">
                <div class="buoc dang-kich-hoat" id="head-1"><div class="so-buoc">1</div><div class="ten-buoc">Thời gian</div></div>
                <div class="buoc" id="head-2"><div class="so-buoc">2</div><div class="ten-buoc">Thông tin</div></div>
                <div class="buoc" id="head-3"><div class="so-buoc">3</div><div class="ten-buoc">Xác nhận</div></div>
            </div>
        </header>

        <main>
            <section class="phan-buoc dang-kich-hoat" id="buoc1">
                <h2><i class="fas fa-calendar-check"></i> Đăng ký: <?php echo htmlspecialchars($dich_vu['ten_dich_vu']); ?></h2>
                <div class="khung-chon">
                    <label>1. Chọn ngày bạn muốn khám:</label>
                    <input type="date" id="ngay-kham" min="<?php echo date('Y-m-d'); ?>" style="width: 100%; padding: 10px; margin-top:10px;">
                </div>
                
                <div style="margin-top: 25px;">
                    <label>2. Chọn buổi khám:</label>
                    <div class="khung-buoi">
                        <div class="chon-buoi" onclick="chonBuoi('Sáng', this)">
                            <i class="fas fa-sun"></i><br>Buổi Sáng (07:30 - 11:30)
                        </div>
                        <div class="chon-buoi" onclick="chonBuoi('Chiều', this)">
                            <i class="fas fa-cloud-sun"></i><br>Buổi Chiều (13:30 - 17:30)
                        </div>
                    </div>
                </div>

                <div class="hanh-dong-bieu-mau" style="justify-content: flex-end; margin-top: 30px;">
                    <button type="button" onclick="buocTiepTheo(2)" class="nut-tiep-theo">Tiếp theo <i class="fas fa-arrow-right"></i></button>
                </div>
            </section>

            <section class="phan-buoc" id="buoc2">
                <h2><i class="fas fa-id-card"></i> Thông Tin Bệnh Nhân</h2>
                <div class="thong-tin-benh-nhan">
                    <div class="hang-bieu-mau">
                        <div class="nhom-bieu-mau"><label>Họ và tên *</label><input type="text" id="ho_ten" required></div>
                        <div class="nhom-bieu-mau"><label>Số điện thoại *</label><input type="tel" id="so_dien_thoai" required></div>
                    </div>
                    <div class="hang-bieu-mau">
                        <div class="nhom-bieu-mau"><label>Email</label><input type="email" id="email"></div>
                        <div class="nhom-bieu-mau"><label>Ngày sinh</label><input type="date" id="ngay_sinh"></div>
                    </div>
                    <div class="nhom-bieu-mau">
                        <label>Ghi chú triệu chứng/Yêu cầu</label>
                        <textarea id="ghi_chu" rows="3" placeholder="Nhập tình trạng sức khỏe của bạn..."></textarea>
                    </div>
                </div>
                <div class="hanh-dong-bieu-mau">
                    <button type="button" onclick="buocTiepTheo(1)" class="nut-quay-lai">Quay lại</button>
                    <button type="button" onclick="buocTiepTheo(3)" class="nut-tiep-theo">Xem xác nhận</button>
                </div>
            </section>

            <section class="phan-buoc" id="buoc3">
                <h2><i class="fas fa-clipboard-check"></i> Xác Nhận Thông Tin Đăng Ký</h2>
                <div class="tom-tat">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <h4 style="color: #130569; border-bottom: 2px solid #ddd;">Dịch vụ đã chọn</h4>
                            <p><strong>Dịch vụ:</strong> <?php echo htmlspecialchars($dich_vu['ten_dich_vu']); ?></p>
                            <p><strong>Giá:</strong> <?php echo number_format($dich_vu['gia'], 0, ',', '.'); ?> VNĐ</p>
                            <p><strong>Ngày khám:</strong> <span id="conf-ngay"></span></p>
                            <p><strong>Buổi khám:</strong> <span id="conf-buoi"></span></p>
                        </div>
                        <div>
                            <h4 style="color: #130569; border-bottom: 2px solid #ddd;">Thông tin khách hàng</h4>
                            <p><strong>Họ tên:</strong> <span id="conf-ten"></span></p>
                            <p><strong>Số điện thoại:</strong> <span id="conf-sdt"></span></p>
                            <p><strong>Ghi chú:</strong> <i id="conf-note"></i></p>
                        </div>
                    </div>
                </div>
                <div class="hanh-dong-bieu-mau" style="margin-top: 30px;">
                    <button type="button" onclick="buocTiepTheo(2)" class="nut-quay-lai">Chỉnh sửa</button>
                    <button type="button" onclick="xacNhanGui()" class="nut-xac-nhan">XÁC NHẬN ĐẶT LỊCH</button>
                </div>
            </section>
        </main>
    </div>

    <script>
        let bookingData = {
            service_id: <?php echo $id_dv; ?>,
            ten_dich_vu: "<?php echo htmlspecialchars($dich_vu['ten_dich_vu']); ?>",
            ngay: "",
            buoi: ""
        };

        function chonBuoi(buoi, el) {
            bookingData.buoi = buoi;
            document.querySelectorAll('.chon-buoi').forEach(item => item.classList.remove('da-chon'));
            el.classList.add('da-chon');
        }

        function buocTiepTheo(n) {
            if (n === 2) {
                bookingData.ngay = document.getElementById('ngay-kham').value;
                if (!bookingData.ngay || !bookingData.buoi) return alert("Vui lòng chọn ngày và buổi khám!");
            }
            if (n === 3) {
                const ten = document.getElementById('ho_ten').value;
                const sdt = document.getElementById('so_dien_thoai').value;
                if (!ten || !sdt) return alert("Vui lòng nhập Họ tên và Số điện thoại!");

                document.getElementById('conf-ngay').innerText = bookingData.ngay;
                document.getElementById('conf-buoi').innerText = bookingData.buoi;
                document.getElementById('conf-ten').innerText = ten;
                document.getElementById('conf-sdt').innerText = sdt;
                document.getElementById('conf-note').innerText = document.getElementById('ghi_chu').value || "Không có";
            }

            document.querySelectorAll('.phan-buoc').forEach((p, i) => p.classList.toggle('dang-kich-hoat', i === (n-1)));
            document.querySelectorAll('.buoc').forEach((b, i) => {
                b.classList.toggle('dang-kich-hoat', i === (n-1));
                b.classList.toggle('da-hoan-thanh', i < (n-1));
            });
        }

        function xacNhanGui() {
            // Tạo một form tạm thời để gửi dữ liệu bằng POST sang dat_lich.php
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'about-us/thongtinkhamdichvu.php';

            const data = {
                'dat_lich': '1',
                'service_id': bookingData.service_id,
                'ngay_kham': bookingData.ngay,
                'buoi_kham': bookingData.buoi,
                'ho_ten': document.getElementById('ho_ten').value,
                'so_dien_thoai': document.getElementById('so_dien_thoai').value,
                'email': document.getElementById('email').value,
                'ngay_sinh': document.getElementById('ngay_sinh').value,
                'ghi_chu': document.getElementById('ghi_chu').value
            };

            for (const key in data) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = data[key];
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</div>
