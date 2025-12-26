<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Health Care - Danh sách bác sĩ</title>
  <link rel="stylesheet" href="style2.css">
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
  </style>
</head>
<body>
<header>
    <div class="logo-header">
      <img src="./Picture/logo.png" alt="Health Care Logo">
    </div>
    <div class="menu-header">
      <nav class="nav-header">
        <a href="#">Home</a>
        <a href="#">Services</a>
        <a href="#">Doctors</a>
        <a href="#">About Us</a>
        <a href="contact2.php">Contact</a>
      </nav>
      <nav class="nav-auth">
        <a href="#">Register now</a>
        <button>Login</button>
        <i class="fa fa-search" aria-hidden="true"></i>
        <i class="fa fa-bars" aria-hidden="true"></i>
      </nav>
  </div>
</header>

<main>
  <div class="container">
    <h1>Danh sách bác sĩ</h1>
    
    <div class="search-container">
      <form method="GET" action="" style="display: flex; width: 100%; justify-content: center;">
        <input type="text" name="search" class="search-box" placeholder="Nhập tên bác sĩ cần tìm..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        
        <select name="chuyenkhoa_id" class="filter-select">
            <option value="">Tất cả chuyên khoa</option>
            <?php 
                include("connect.php");
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
            // Chỉ lấy những chuyên khoa có bác sĩ khớp tên đó
            $conditions[] = "id IN (SELECT id_chuyen_khoa FROM bac_si WHERE ho_ten LIKE '%$search%')";
        }

        $where_clause = "";
        if (count($conditions) > 0) {
            $where_clause = " WHERE " . implode(" AND ", $conditions);
        }

        // Lấy danh sách chuyên khoa thỏa mãn điều kiện
        $sql = "SELECT * FROM chuyen_khoa $where_clause";
        $result = mysqli_query($conn, $sql);

        while($row = mysqli_fetch_array($result)) {
            $idChuyenKhoa = $row['id'];
      ?>
            <div class="chuyenkhoa-header">
                <h2><?php echo $row["ten_chuyen_khoa"]; ?></h2>
                <br>
            </div>

            <?php 
                // Câu lệnh SQL lấy bác sĩ: Lọc theo chuyên khoa VÀ lọc theo tên bác sĩ (nếu có search)
                $bs_name_filter = "";
                if (!empty($_GET['search'])) {
                    $search_name = mysqli_real_escape_string($conn, $_GET['search']);
                    $bs_name_filter = " AND p.ho_ten LIKE '%$search_name%'";
                }

                $sql2 = "SELECT p.* FROM bac_si p 
                         WHERE p.id_chuyen_khoa = '$idChuyenKhoa' $bs_name_filter
                         ORDER BY 
                            FIELD(p.chuc_vu, 'Giám đốc', 'phó Giám đốc', 'trưởng khoa') ASC, 
                            FIELD(p.hoc_vi,'Giáo sư', 'Tiến sĩ', 'Thạc sĩ','Bác sĩ CKII', 'Bác sĩ CKI','Bác sĩ') ASC";
                            
                $result2 = mysqli_query($conn, $sql2);
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
                                <p><strong>Chức vụ:</strong> <?php echo $row2['chuc_vu_id']; ?></p>
                                <p><strong>Học vị:</strong> <?php echo $row2['hoc_vi']; ?></p>
                                <p><strong>Kinh nghiệm:</strong> <?php echo $row2['kinh_nghiem']; ?></p>
                            </div>
                        </div>
                        
                        <div class="detail-row" style="margin-top: 10px;">
                            <a class="xemthongtin" href="chitiet.php?id=<?php echo $row2["id"]; ?>" style="color: blue; text-decoration: none;">Xem chi tiết</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
      <?php } ?>
    </div>
  </div>
</main>

<script>
// Tự động submit khi thay đổi chuyên khoa (tùy chọn)
document.querySelector('.filter-select').addEventListener('change', function() {
    this.form.submit();
});
</script>
</body>
</html>