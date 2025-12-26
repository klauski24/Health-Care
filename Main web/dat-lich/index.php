<?php
if (ob_get_length()) ob_clean();
include 'connect.php';
// Xử lý reset để quay lại từ đầu sạch sẽ
if (isset($_GET['reset'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$khoa_query = "SELECT * FROM chuyen_khoa ORDER BY ten_chuyen_khoa";
$khoa_result = $conn->query($khoa_query);

$bac_si_result = null;
$selected_khoa = null;
if (isset($_POST['chon_khoa'])) {
    $selected_khoa = $_POST['chuyen_khoa'];
    $bac_si_query = "SELECT * FROM bac_si WHERE id_chuyen_khoa = ? ORDER BY ho_ten";
    $stmt = $conn->prepare($bac_si_query);
    $stmt->bind_param("i", $selected_khoa);
    $stmt->execute();
    $bac_si_result = $stmt->get_result();
    $_SESSION['chuyen_khoa'] = $selected_khoa;
}

$lich_result = null;
$selected_bac_si = null;
if (isset($_POST['chon_bac_si'])) {
    $selected_bac_si = $_POST['bac_si'];
    $_SESSION['bac_si'] = $selected_bac_si;
    $lich_query = "SELECT * FROM lich_lam_viec 
                   WHERE id_bac_si = ? 
                   AND trang_thai = 'trong'
                   AND ngay >= CURDATE()
                   ORDER BY ngay, khung_gio";
    $stmt = $conn->prepare($lich_query);
    $stmt->bind_param("i", $selected_bac_si);
    $stmt->execute();
    $lich_result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lịch Khám Bệnh</title>
    <link rel="stylesheet" href="dat-lich/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="khung-chua">
        <header>
            <div class="bieu-tuong">
                <i class="fas fa-calendar-plus me-2"></i>
                <h1>HỆ THỐNG ĐẶT LỊCH KHÁM BỆNH</h1>
            </div>
            <div class="cac-buoc">
                <div class="buoc <?php echo (!isset($_POST['chon_khoa']) && !isset($_POST['chon_bac_si'])) ? 'dang-kich-hoat' : 'da-hoan-thanh'; ?>">
                    <div class="so-buoc">1</div>
                    <div class="ten-buoc">Chọn Chuyên Khoa</div>
                </div>
                <div class="buoc <?php echo (isset($_POST['chon_khoa']) && !isset($_POST['chon_bac_si'])) ? 'dang-kich-hoat' : (isset($_POST['chon_bac_si']) ? 'da-hoan-thanh' : ''); ?>">
                    <div class="so-buoc">2</div>
                    <div class="ten-buoc">Chọn Bác Sĩ</div>
                </div>
                <div class="buoc <?php echo (isset($_POST['chon_bac_si'])) ? 'dang-kich-hoat' : ''; ?>">
                    <div class="so-buoc">3</div>
                    <div class="ten-buoc">Chọn Ngày & Giờ</div>
                </div>
                <div class="buoc">
                    <div class="so-buoc">4</div>
                    <div class="ten-buoc">Xác Nhận</div>
                </div>
            </div>
        </header>

        <main>
            <?php 
            // CHỈ HIỂN THỊ MỘT BƯỚC DUY NHẤT TÙY THEO TIẾN TRÌNH
            if (isset($_POST['chon_bac_si'])) { 
                // --- BƯỚC 3: CHỌN LỊCH KHÁM & THÔNG TIN BỆNH NHÂN (Đầy đủ trường) ---
            ?>
                <section class="phan-buoc dang-kich-hoat" id="buoc3">
                    <h2><i class="fas fa-calendar-alt"></i> Chọn Ngày & Giờ Khám</h2>
                    <?php 
                    if ($lich_result && $lich_result->num_rows > 0) {
                        $lich_theo_ngay = [];
                        while($lich = $lich_result->fetch_assoc()) {
                            $lich_theo_ngay[$lich['ngay']][] = $lich;
                        }
                        ksort($lich_theo_ngay);
                    ?>
                    <form method="POST" action="dat_lich.php" class="bieu-mau-luoi">
                        <input type="hidden" name="bac_si" value="<?php echo $selected_bac_si; ?>">
                        
                        <div class="luoi-ngay">
                            <?php foreach($lich_theo_ngay as $ngay => $khung_gios) { 
                                $date_obj = new DateTime($ngay);
                                $day_name = ['Monday' => 'Thứ Hai', 'Tuesday' => 'Thứ Ba', 'Wednesday' => 'Thứ Tư', 'Thursday' => 'Thứ Năm', 'Friday' => 'Thứ Sáu', 'Saturday' => 'Thứ Bảy', 'Sunday' => 'Chủ Nhật'][$date_obj->format('l')];
                            ?>
                            <div class="the-ngay">
                                <div class="dau-the-ngay">
                                    <div class="ten-ngay"><?php echo $day_name; ?></div>
                                    <div class="so-ngay"><?php echo $date_obj->format('d'); ?></div>
                                    <div class="thang-nam"><?php echo $date_obj->format('m/Y'); ?></div>
                                </div>
                                <div class="cac-khung-gio">
                                    <?php foreach($khung_gios as $lich) {
                                        $time_obj = new DateTime($lich['khung_gio']);
                                    ?>
                                    <div class="khung-gio">
                                        <input type="radio" name="lich_kham" id="lich_<?php echo $lich['id']; ?>" value="<?php echo $lich['id']; ?>" required>
                                        <label for="lich_<?php echo $lich['id']; ?>"><?php echo $time_obj->format('H:i'); ?></label>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        
                        <div class="thong-tin-benh-nhan">
                            <h3><i class="fas fa-user-injured"></i> Thông Tin Bệnh Nhân</h3>
                            <div class="hang-bieu-mau">
                                <div class="nhom-bieu-mau">
                                    <label for="ho_ten">Họ và tên *</label>
                                    <input type="text" id="ho_ten" name="ho_ten" required>
                                </div>
                                <div class="nhom-bieu-mau">
                                    <label for="so_dien_thoai">Số điện thoại *</label>
                                    <input type="tel" id="so_dien_thoai" name="so_dien_thoai" required>
                                </div>
                            </div>
                            <div class="hang-bieu-mau">
                                <div class="nhom-bieu-mau">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email">
                                </div>
                                <div class="nhom-bieu-mau">
                                    <label for="ngay_sinh">Ngày sinh</label>
                                    <input type="date" id="ngay_sinh" name="ngay_sinh">
                                </div>
                            </div>
                            <div class="nhom-bieu-mau">
                                <label for="ghi_chu">Ghi chú triệu chứng</label>
                                <textarea id="ghi_chu" name="ghi_chu" rows="3" placeholder="Mô tả triệu chứng..."></textarea>
                            </div>
                        </div>
                        
                        <div class="hanh-dong-bieu-mau">
                            <a href="?reset=1" class="nut-quay-lai">
                                <i class="fas fa-arrow-left"></i> Quay lại từ đầu
                            </a>
                            <button type="submit" name="dat_lich" class="nut-xac-nhan">
                                <i class="fas fa-calendar-check"></i> Đặt Lịch Ngay
                            </button>
                        </div>
                    </form>
                    <?php } else { ?>
                    <div class="trang-thai-trong">
                        <i class="fas fa-calendar-times"></i>
                        <p>Không tìm thấy lịch trống cho bác sĩ này.</p>
                        <a href="?reset=1" class="nut-quay-lai">Quay lại từ đầu</a>
                    </div>
                    <?php } ?>
                </section>

            <?php } elseif (isset($_POST['chon_khoa'])) { 
                // --- BƯỚC 2: CHỌN BÁC SĨ ---
            ?>
                <section class="phan-buoc dang-kich-hoat" id="buoc2">
                    <h2><i class="fas fa-user-md"></i> Chọn Bác Sĩ</h2>
                    <?php if ($bac_si_result && $bac_si_result->num_rows > 0) { ?>
                    <form method="POST" action="" class="bieu-mau-luoi">
                        <input type="hidden" name="chuyen_khoa" value="<?php echo $selected_khoa; ?>">
                        <div class="luoi-bac-si">
                            <?php while($doctor = $bac_si_result->fetch_assoc()) { ?>
                            <div class="the-bac-si">
                                <div class="anh-dai-dien-bac-si"><i class="fas fa-user-md"></i></div>
                                <div class="thong-tin-bac-si">
                                    <h3><?php echo htmlspecialchars($doctor['ho_ten']); ?></h3>
                                    <p><i class="fas fa-briefcase-medical"></i> Kinh nghiệm: <?php echo htmlspecialchars($doctor['kinh_nghiem']); ?></p>
                                    <?php if (!empty($doctor['mo_ta'])) { ?>
                                    <p class="mo-ta"><?php echo htmlspecialchars($doctor['mo_ta']); ?></p>
                                    <?php } ?>
                                </div>
                                <div class="nut-chon-bac-si">
                                    <input type="radio" name="bac_si" id="bac_si_<?php echo $doctor['id']; ?>" value="<?php echo $doctor['id']; ?>" required>
                                    <label for="bac_si_<?php echo $doctor['id']; ?>" style="width:85%;">Chọn bác sĩ</label>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="hanh-dong-bieu-mau">
                            <a href="?reset=1" class="nut-quay-lai"><i class="fas fa-arrow-left"></i> Quay lại</a>
                            <button type="submit" name="chon_bac_si" class="nut-tiep-theo">
                                <i class="fas fa-arrow-right"></i> Tiếp tục
                            </button>
                        </div>
                    </form>
                    <?php } else { ?>
                    <div class="trang-thai-trong">
                        <i class="fas fa-user-slash"></i>
                        <p>Không tìm thấy bác sĩ nào.</p>
                        <a href="?reset=1" class="nut-quay-lai">Quay lại chọn chuyên khoa</a>
                    </div>
                    <?php } ?>
                </section>

            <?php } else { 
                // --- BƯỚC 1: CHỌN CHUYÊN KHOA (Mặc định khi mới chạy) ---
            ?>
                <section class="phan-buoc dang-kich-hoat" id="buoc1">
                    <h2><i class="fas fa-clinic-medical"></i> Chọn Chuyên Khoa</h2>
                    <form method="POST" action="" class="bieu-mau-luoi">
                        <div class="khung-chon">
                            <label>Chuyên khoa:</label>
                            <select name="chuyen_khoa" id="chuyen_khoa" required>
                                <option value="">-- Vui lòng chọn chuyên khoa --</option>
                                <?php while($row = $khoa_result->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['ten_chuyen_khoa']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" name="chon_khoa" class="nut-tiep-theo">
                            <i class="fas fa-arrow-right"></i> Tiếp tục
                        </button>
                    </form>
                </section>
            <?php } ?>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const steps = document.querySelectorAll('.phan-buoc');
            steps.forEach((step) => {
                if (step.classList.contains('dang-kich-hoat')) {
                    step.style.display = 'block';
                    setTimeout(() => {
                        step.style.opacity = '1';
                        step.style.transform = 'translateY(0)';
                    }, 100);
                } else {
                    step.style.display = 'none';
                }
            });
        });

        const formFinal = document.querySelector('form[action="dat_lich.php"]');
        if (formFinal) {
            formFinal.addEventListener('submit', function(e) {
                const selectedTime = document.querySelector('input[name="lich_kham"]:checked');
                if (!selectedTime) {
                    e.preventDefault();
                    alert('Vui lòng chọn khung giờ khám!');
                    return false;
                }
                const patientName = document.getElementById('ho_ten').value;
                const phone = document.getElementById('so_dien_thoai').value;
                if (!patientName.trim() || !phone.trim()) {
                    e.preventDefault();
                    alert('Vui lòng điền đầy đủ thông tin bệnh nhân!');
                    return false;
                }
                return confirm('Bạn có chắc chắn muốn đặt lịch khám này?');
            });
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>