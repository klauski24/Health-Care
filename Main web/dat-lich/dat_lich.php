<?php
session_start();
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dat_lich'])) {
    $lich_id = $_POST['lich_kham'];
    $bac_si_id = $_POST['bac_si'];
    $ho_ten = $_POST['ho_ten'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $email = $_POST['email'] ?? '';
    $ngay_sinh = $_POST['ngay_sinh'] ?? null;
    $ghi_chu = $_POST['ghi_chu'] ?? '';
    
    $ma_dat_lich = 'DL' . date('YmdHis') . rand(100, 999);
    $conn->begin_transaction();
    
    try {
        $update_lich = "UPDATE lich_lam_viec SET trang_thai = 'da_dat' WHERE id = ? AND trang_thai = 'trong'";
        $stmt = $conn->prepare($update_lich);
        $stmt->bind_param("i", $lich_id);
        $stmt->execute();
        
        if ($stmt->affected_rows === 0) {
            throw new Exception("Lịch này đã được đặt hoặc không tồn tại!");
        }
        
        $create_table = "CREATE TABLE IF NOT EXISTS dat_lich (
            id INT PRIMARY KEY AUTO_INCREMENT,
            ma_dat_lich VARCHAR(50) UNIQUE NOT NULL,
            id_lich_lam_viec INT NOT NULL,
            id_bac_si INT NOT NULL,
            ho_ten_benh_nhan VARCHAR(100) NOT NULL,
            so_dien_thoai VARCHAR(20) NOT NULL,
            email VARCHAR(100),
            ngay_sinh DATE,
            ghi_chu TEXT,
            thoi_gian_dat DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (id_lich_lam_viec) REFERENCES lich_lam_viec(id),
            FOREIGN KEY (id_bac_si) REFERENCES bac_si(id)
        )";
        $conn->query($create_table);
        
        $insert_dat_lich = "INSERT INTO dat_lich (ma_dat_lich, id_lich_lam_viec, id_bac_si, ho_ten_benh_nhan, 
                          so_dien_thoai, email, ngay_sinh, ghi_chu) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($insert_dat_lich);
        $stmt2->bind_param("siisssss", $ma_dat_lich, $lich_id, $bac_si_id, $ho_ten, 
                          $so_dien_thoai, $email, $ngay_sinh, $ghi_chu);
        $stmt2->execute();
        $conn->commit();
        
        $query_detail = "SELECT dl.*, bs.ho_ten as ten_bac_si, llv.ngay, llv.khung_gio
                        FROM dat_lich dl
                        JOIN bac_si bs ON dl.id_bac_si = bs.id
                        JOIN lich_lam_viec llv ON dl.id_lich_lam_viec = llv.id
                        WHERE dl.ma_dat_lich = ?";
        $stmt3 = $conn->prepare($query_detail);
        $stmt3->bind_param("s", $ma_dat_lich);
        $stmt3->execute();
        $result = $stmt3->get_result();
        $appointment = $result->fetch_assoc();
        session_destroy();
    } catch (Exception $e) {
        $conn->rollback();
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận Đặt Lịch</title>
    <link rel="stylesheet" href="dat-lich/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="khung-chua">
        <header>
            <div class="bieu-tuong">
                <i class="fas fa-calendar-plus me-2"></i>
                <h1>XÁC NHẬN ĐẶT LỊCH</h1>
            </div>
        </header>

        <main>
            <?php if (isset($error)) { ?>
            <div class="khung-ket-qua loi">
                <div class="bieu-tuong-ket-qua loi">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h2>Đặt lịch thất bại!</h2>
                <p><?php echo htmlspecialchars($error); ?></p>
                <div class="hanh-dong-ket-qua">
                    <a href="index.php" class="nut-quay-lai">
                        <i class="fas fa-arrow-left"></i> Quay lại đặt lịch
                    </a>
                </div>
            </div>
            <?php } elseif (isset($appointment)) { ?>
            <div class="khung-ket-qua thanh-cong">
                <div class="bieu-tuong-ket-qua thanh-cong">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Đặt lịch thành công!</h2>
                <div class="chi-tiet-dat-lich">
                    <div class="the-chi-tiet">
                        <h3><i class="fas fa-info-circle"></i> Thông tin đặt lịch</h3>
                        <div class="hang-chi-tiet">
                            <span class="nhan-chi-tiet">Mã đặt lịch:</span>
                            <span class="gia-tri-chi-tiet noi-bat"><?php echo $appointment['ma_dat_lich']; ?></span>
                        </div>
                        <div class="hang-chi-tiet">
                            <span class="nhan-chi-tiet">Bác sĩ:</span>
                            <span class="gia-tri-chi-tiet"><?php echo htmlspecialchars($appointment['ten_bac_si']); ?></span>
                        </div>
                        <div class="hang-chi-tiet">
                            <span class="nhan-chi-tiet">Ngày khám:</span>
                            <span class="gia-tri-chi-tiet"><?php echo date('d/m/Y', strtotime($appointment['ngay'])); ?></span>
                        </div>
                        <div class="hang-chi-tiet">
                            <span class="nhan-chi-tiet">Giờ khám:</span>
                            <span class="gia-tri-chi-tiet"><?php echo date('H:i', strtotime($appointment['khung_gio'])); ?></span>
                        </div>
                    </div>
                    
                    <div class="the-chi-tiet">
                        <h3><i class="fas fa-user"></i> Thông tin bệnh nhân</h3>
                        <div class="hang-chi-tiet">
                            <span class="nhan-chi-tiet">Họ tên:</span>
                            <span class="gia-tri-chi-tiet"><?php echo htmlspecialchars($appointment['ho_ten_benh_nhan']); ?></span>
                        </div>
                        <div class="hang-chi-tiet">
                            <span class="nhan-chi-tiet">Số điện thoại:</span>
                            <span class="gia-tri-chi-tiet"><?php echo htmlspecialchars($appointment['so_dien_thoai']); ?></span>
                        </div>
                        <?php if (!empty($appointment['email'])) { ?>
                        <div class="hang-chi-tiet">
                            <span class="nhan-chi-tiet">Email:</span>
                            <span class="gia-tri-chi-tiet"><?php echo htmlspecialchars($appointment['email']); ?></span>
                        </div>
                        <?php } ?>
                        <?php if (!empty($appointment['ngay_sinh'])) { ?>
                        <div class="hang-chi-tiet">
                            <span class="nhan-chi-tiet">Ngày sinh:</span>
                            <span class="gia-tri-chi-tiet"><?php echo date('d/m/Y', strtotime($appointment['ngay_sinh'])); ?></span>    
                        </div>
                        <?php } ?>
                        <div class="hang-chi-tiet">
                            <span class="nhan-chi-tiet">Ghi chú:</span>
                            <span class="gia-tri-chi-tiet"><?php echo nl2br(htmlspecialchars($appointment['ghi_chu'])); ?></span>   
                        </div>  
                    </div>
                </div>
                
                <div class="huong-dan">
                    <h3><i class="fas fa-exclamation-circle"></i> Lưu ý quan trọng</h3>
                    <ul>
                        <li>Vui lòng đến trước 15 phút so với giờ hẹn để làm thủ tục</li>
                        <li>Mang theo CMND/CCCD và thẻ BHYT (nếu có)</li>
                        <li>Xuất trình mã đặt lịch tại quầy lễ tân</li>
                        <li>Có thể hủy lịch trước 24 giờ qua hotline</li>
                    </ul>
                </div>
                
                <div class="hanh-dong-ket-qua">
                    <button onclick="window.print()" class="nut-in">
                        <i class="fas fa-print"></i> In phiếu hẹn
                    </button>
                    <a href="index.php" class="nut-moi">
                        <i class="fas fa-plus-circle"></i> Đặt lịch mới
                    </a>
                </div>
            </div>
            <?php } else { ?>
            <div class="warning">
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h2>Không có dữ liệu đặt lịch</h2>
                <p>Vui lòng quay lại trang chủ để đặt lịch.</p>
                <div class="hanh-dong-ket-qua">
                    <a href="index.php" class="nut-quay-lai">
                        <i class="fas fa-home"></i> Về trang chủ
                    </a>
                </div>
            </div>
            <?php } ?>
        </main>
    </div>
    
    <script>
        // In phiếu hẹn
        function printAppointment() {
            window.print();
        }
        
        // Sao chép mã đặt lịch
        function copyAppointmentCode() {
            const code = "<?php echo isset($appointment) ? $appointment['ma_dat_lich'] : ''; ?>";
            if (code) {
                navigator.clipboard.writeText(code);
                alert('Đã sao chép mã đặt lịch: ' + code);
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
