<link rel="stylesheet" href="contact/style2.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    /* Thêm một chút CSS để dropdown đẹp hơn */
    .filter-select {
        padding: 12px;
        border: 2px solid #3498db;
        border-radius: 0;
        border-left: none;
        border-right: none;
        outline: none;
        font-size: 16px;
        background: white;
    }
    .container{
      margin-top:120px;
    }
</style>
<div class="container">
    <h1>Danh sách bác sĩ</h1>
    
    <div class="search-container">
        <!-- THAY ĐỔI FORM ACTION VÀ THÊM HIDDEN INPUT -->
        <form method="GET" action="index.php" style="display: flex; width: 100%; justify-content: center;">
            <!-- Hidden input để giữ page_layout -->
            <input type="hidden" name="page_layout" value="doctors">
            
            <input type="text" name="search" class="search-box" placeholder="Nhập tên bác sĩ cần tìm..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            
            <select name="chuyenkhoa_id" class="filter-select">
                <option value="">Tất cả chuyên khoa</option>
                <?php 
                    include(__DIR__ . '/../connect.php');
                    $list_ck = mysqli_query($conn, "SELECT * FROM chuyen_khoa");
                    while($ck = mysqli_fetch_array($list_ck)) {
                        $selected = (isset($_GET['chuyenkhoa_id']) && $_GET['chuyenkhoa_id'] == $ck['id']) ? 'selected' : '';
                        echo "<option value='".$ck['id']."' $selected>".$ck['ten_chuyen_khoa']."</option>";
                    }
                ?>
            </select>

            <button type="submit" class="search-button">
                <i class="fa fa-search" aria-hidden="true"></i> Tìm kiếm
            </button>
        </form>
    </div>
    
    <div class="doctor-container">
        <?php
            include(__DIR__ . '/../connect.php');

            // Xử lý logic lọc dữ liệu
            $conditions = array();

            // 1. Lọc theo chuyên khoa (nếu có chọn)
            if (!empty($_GET['chuyenkhoa_id'])) {
                $ck_id = mysqli_real_escape_string($conn, $_GET['chuyenkhoa_id']);
                $conditions[] = "id = '$ck_id'";
            }

            // 2. Lọc theo tên bác sĩ (nếu có nhập)
            if (!empty($_GET['search'])) {
                $search = mysqli_real_escape_string($conn, $_GET['search']);
                $conditions[] = "id IN (SELECT DISTINCT id_chuyen_khoa FROM bac_si WHERE ho_ten LIKE '%$search%')";
            }

            $where_clause = "";
            if (count($conditions) > 0) {
                $where_clause = " WHERE " . implode(" AND ", $conditions);
            }

            // Lấy danh sách chuyên khoa thỏa mãn điều kiện
            $sql = "SELECT * FROM chuyen_khoa $where_clause";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 0) {
                echo "<p style='text-align:center; margin: 50px 0;'>Không tìm thấy bác sĩ nào phù hợp.</p>";
            }

            while($row = mysqli_fetch_array($result)) {
                $idChuyenKhoa = $row['id'];
        ?>
            <div class="chuyenkhoa-header">
                <h2><?php echo $row["ten_chuyen_khoa"]; ?></h2>
                <br>
            </div>

            <?php 
                // Câu lệnh SQL lấy bác sĩ
                $bs_name_filter = "";
                if (!empty($_GET['search'])) {
                    $search_name = mysqli_real_escape_string($conn, $_GET['search']);
                    $bs_name_filter = " AND ho_ten LIKE '%$search_name%'";
                }

                // SỬA LỖI ORDER BY: thêm điều kiện kiểm tra chuc_vu không null
                $sql2 = "SELECT * FROM bac_si 
                         WHERE id_chuyen_khoa = '$idChuyenKhoa' $bs_name_filter
                         ORDER BY 
                            CASE 
                                WHEN chuc_vu = 'Giám đốc' THEN 1
                                WHEN chuc_vu = 'Phó Giám đốc' THEN 2
                                WHEN chuc_vu = 'Trưởng khoa' THEN 3
                                ELSE 4
                            END,
                            CASE 
                                WHEN hoc_vi = 'Giáo sư' THEN 1
                                WHEN hoc_vi = 'Tiến sĩ' THEN 2
                                WHEN hoc_vi = 'Thạc sĩ' THEN 3
                                WHEN hoc_vi = 'Bác sĩ CKII' THEN 4
                                WHEN hoc_vi = 'Bác sĩ CKI' THEN 5
                                WHEN hoc_vi = 'Bác sĩ' THEN 6
                                ELSE 7
                            END";
                            
                $result2 = mysqli_query($conn, $sql2);
                
                // Kiểm tra nếu có bác sĩ trong chuyên khoa này
                if (mysqli_num_rows($result2) > 0) {
            ?>
                <div style="display: flex; gap: 10px; overflow: scroll; scrollbar-width: none; width: 80%; margin: auto;">
                    <?php
                    while ($row2 = mysqli_fetch_array($result2)) {
                    ?>
                        <div class="doctor-card">
                            <div class="doctor-header">
                                <img src="<?php echo $row2['hinh_anh']; ?>" class="doctor-avatar" >
                                <div class="doctor-info">
                                    <h3 style="margin: 10px 0;"><?php echo $row2['ho_ten']; ?></h3>
                                    <p><strong>Chức vụ:</strong> <?php echo !empty($row2['chuc_vu']) ? $row2['chuc_vu'] : '---'; ?></p>
                                    <p><strong>Học vị:</strong> <?php echo $row2['hoc_vi']; ?></p>
                                    <p><strong>Kinh nghiệm:</strong> <?php echo $row2['kinh_nghiem']; ?></p>
                                </div>
                            </div>
                            
                            <div class="detail-row" style="margin-top: 10px;">
                                <a class="xemthongtin" href="contact/chitiet.php?id=<?php echo $row2["id"]; ?>" style="color: blue; text-decoration: none;">Xem chi tiết</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <p style="text-align:center; color:#666;">Không có bác sĩ nào trong chuyên khoa này.</p>
            <?php } ?>
        <?php } ?>
    </div>
</div>
<script>
    // Tự động submit khi thay đổi chuyên khoa
    document.querySelector('.filter-select').addEventListener('change', function() {
        this.form.submit();
    });
</script>