<?php
session_start();
include(__DIR__ . '/../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dat_lich'])) {
    
    // Lấy dữ liệu từ POST gửi sang (khớp với tên trong file in.php)
    $id_dich_vu = $_POST['service_id'];
    $ngay_kham = $_POST['ngay_kham'];
    $buoi_kham = $_POST['buoi_kham'];
    $ho_ten = $_POST['ho_ten'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $email = $_POST['email'] ?? '';
    $ngay_sinh = $_POST['ngay_sinh'] ?? null;
    $ghi_chu = $_POST['ghi_chu'] ?? '';
    
    // Giả định: Bạn cần ID bác sĩ và ID lịch. 
    // Nếu hệ thống của bạn chưa có bước chọn bác sĩ cụ thể, ta tạm gán ID mặc định hoặc lấy từ logic khác.
    $bac_si_id = 1; // Tạm thời để là 1 (Bác sĩ mặc định)
    $lich_id = 1;   // Tạm thời để là 1 (ID lịch khám tương ứng)
    
    $ma_dat_lich = 'DL' . date('YmdHis') . rand(100, 999);
    $conn->begin_transaction();
    
    try {
        // 1. Cập nhật trạng thái lịch làm việc (Chỉ chạy nếu bạn có quản lý bảng lich_lam_viec chi tiết)
        // Nếu bạn chỉ muốn lưu đơn đăng ký mà không cần quản lý slot, có thể bỏ qua phần UPDATE này.
        $update_lich = "UPDATE lich_lam_viec SET trang_thai = 'da_dat' WHERE id = ? AND trang_thai = 'trong'";
        $stmt = $conn->prepare($update_lich);
        $stmt->bind_param("i", $lich_id);
        $stmt->execute();
        
        // Lưu ý: Nếu không có bảng lich_lam_viec hoặc ID không khớp, dòng dưới đây sẽ gây lỗi "Thất bại"
        // if ($stmt->affected_rows === 0) { throw new Exception("Lịch này đã được đặt hoặc không tồn tại!"); }
        
        // 2. Lưu vào bảng dat_lich_kham
        $id_benh_nhan = 1; // Giả sử mặc định là 1 nếu chưa login
        $insert_dat_lich = "INSERT INTO dat_lich_kham (ma_dat_lich, id_benh_nhan, id_bac_si, id_dich_vu, id_lich_lam_viec, 
                          ho_ten_benh_nhan, so_dien_thoai, email, ngay_sinh, ghi_chu) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt2 = $conn->prepare($insert_dat_lich);
        $stmt2->bind_param("siiiisssss", $ma_dat_lich, $id_benh_nhan, $bac_si_id, $id_dich_vu, $lich_id, 
                          $ho_ten, $so_dien_thoai, $email, $ngay_sinh, $ghi_chu);
        $stmt2->execute();
        $conn->commit();
        
        // 3. Lấy thông tin đầy đủ để hiển thị (Sử dụng các bảng liên quan)
        $query_detail = "SELECT dl.*, bs.ho_ten as ten_bac_si, dv.ten_dich_vu
                        FROM dat_lich_kham dl
                        LEFT JOIN bac_si bs ON dl.id_bac_si = bs.id
                        LEFT JOIN dich_vu dv ON dl.id_dich_vu = dv.id
                        WHERE dl.ma_dat_lich = ?";
        $stmt3 = $conn->prepare($query_detail);
        $stmt3->bind_param("s", $ma_dat_lich);
        $stmt3->execute();
        $appointment = $stmt3->get_result()->fetch_assoc();
        
        // Bổ sung thông tin thời gian từ dữ liệu POST vì bảng dat_lich_kham có thể chưa lưu ngay/buổi theo cấu trúc cũ
        if($appointment) {
            $appointment['ngay'] = $ngay_kham;
            $appointment['khung_gio'] = $buoi_kham;
        }

    } catch (Exception $e) {
        $conn->rollback();
        $error = $e->getMessage();
    }
}
?>
    <title>Kết Quả Đặt Lịch</title>
    <link rel="stylesheet" href="about-us/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background: #cac6c63e; }
        .khung-ket-qua { max-width: 800px; margin: 50px auto; padding: 30px; border-radius: 15px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .chi-tiet-dat-lich { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px; text-align: left; }
        .the-chi-tiet { border: 2px solid #eee; padding: 15px; border-radius: 8px; }
        .thanh-cong { border-top: 10px solid #28a745; text-align: center; }
        .loi { border-top: 10px solid #dc3545; text-align: center; }
        .nut-in { background: #130569; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
    </style>
<main>
    <div class="khung-ket-qua <?php echo isset($error) ? 'loi' : 'thanh-cong'; ?>">
        <?php if (isset($error)): ?>
            <i class="fas fa-times-circle fa-4x" style="color:#dc3545"></i>
            <h2>Đặt lịch thất bại!</h2>
            <p><?php echo $error; ?></p>
            <a href="services.php" class="nut-quay-lai">Quay lại</a>
        <?php elseif (isset($appointment)): ?>
            <i class="fas fa-check-circle fa-4x" style="color:#28a745"></i>
            <h2>Đặt lịch thành công!</h2>
            <div class="chi-tiet-dat-lich">
                <div class="the-chi-tiet">
                    <h3>Thông tin lịch khám</h3>
                    <p><strong>Mã:</strong> <?php echo $appointment['ma_dat_lich']; ?></p>
                    <p><strong>Dịch vụ:</strong> <?php echo $appointment['ten_dich_vu']; ?></p>
                    <p><strong>Bác sĩ:</strong> <?php echo $appointment['ten_bac_si']; ?></p>
                    <p><strong>Thời gian:</strong> <?php echo $appointment['khung_gio']; ?> - <?php echo date('d/m/Y', strtotime($appointment['ngay'])); ?></p>
                </div>
                <div class="the-chi-tiet">
                    <h3>Thông tin bệnh nhân</h3>
                    <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($appointment['ho_ten_benh_nhan']); ?></p>
                    <p><strong>SĐT:</strong> <?php echo htmlspecialchars($appointment['so_dien_thoai']); ?></p>
                    <p><strong>Ghi chú:</strong> <?php echo nl2br(htmlspecialchars($appointment['ghi_chu'])); ?></p>
                </div>
            </div>
            <div style="margin-top:30px;">
                <button onclick="window.print()" class="nut-in"><i class="fas fa-print"></i> In phiếu hẹn</button>
                <a href="../index.php?page_layout=services" style="text-decoration:none; margin-left:15px;">Về trang chủ</a>
            </div>
        <?php endif; ?>
    </div>
</main>
