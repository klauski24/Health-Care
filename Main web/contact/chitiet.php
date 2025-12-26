  <title>Health Care - Chi tiết bác sĩ</title>
    <link rel="stylesheet" href="style2.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    /* Tổng thể layout chuyên nghiệp */
    .profile-container {
        max-width: 1000px;
        margin: 120px auto 50px;
        background: #fff;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        display: flex; /* Chia đôi màn hình: ảnh trái, thông tin phải */
        gap: 40px;
        text-align: left; /* Ghi đè text-align: center cũ */
    }

    /* Cột trái: Ảnh và các thông tin cơ bản */
    .profile-left {
        flex: 1;
        text-align: center;
    }

    .profile-left img {
        width: 100%;
        max-width: 350px;
        height: 500px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        object-fit: cover;
    }

    /* Cột phải: Chi tiết chuyên môn */
    .profile-right {
        flex: 1.5;
    }

    .profile-right h2 {
        color: #1679C4;
        font-size: 32px;
        margin-top: 0;
        margin-bottom: 10px;
        border-bottom: 3px solid #add633;
        display: inline-block;
        padding-bottom: 5px;
    }

    .doctor-specialty {
        font-size: 18px;
        color: #666;
        font-weight: bold;
        margin-bottom: 20px;
        display: block;
    }

    /* Làm đẹp danh sách thông tin */
    .info-list {
        list-style: none;
        padding: 0;
    }

    .info-list li {
        margin-bottom: 15px;
        font-size: 16px;
        line-height: 1.6;
        color: #444;
        padding-bottom: 10px;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-list li strong {
        color: #1679C4;
        width: 250px;
        display: inline-block;
    }

    /* Nút quay lại */
    .back-btn {
        display: inline-block;
        margin-top: 30px;
        text-decoration: none;
        color: #1679C4;
        font-weight: bold;
        transition: 0.3s;
    }

    .back-btn:hover {
        color: #0d47a1;
        transform: translateX(-5px);
    }

    /* Responsive cho di động */
    .detail-content {
        color: #555;
        line-height: 1.8;
        max-height: 200px; /* Giới hạn chiều cao tối đa */
        overflow-y: auto;  /* Hiện thanh cuộn nếu nội dung vượt quá max-height */
        padding-right: 10px;
        font-size: 15px;
        text-align: justify;
    }
  </style>

<?php
include(__DIR__ . '/../connect.php');
$id = $_GET["id"];
$rs = $conn->query("SELECT p.*,sc.ten_chuyen_khoa 
    FROM bac_si p
    JOIN chuyen_khoa sc ON p.id_chuyen_khoa = sc.id
    WHERE p.id = $id");
$d = $rs->fetch_assoc();
?>

<main class="profile-container">
    <div class="profile-left">
        <img src="<?= $d["hinh_anh"] ?>" alt="<?= $d["ho_ten"] ?>">
       
        <a href="../index.php?page_layout=doctors" class="back-btn">⬅ Quay lại danh sách</a>
    </div>

    <div class="profile-right">
        <h2 style="margin-bottom: 5px;">Bác sĩ: <?= $d["ho_ten"] ?></h2>
        <p style="color: #666; font-weight: 600; margin-bottom: 30px;"><?= $d["ten_chuyen_khoa"] ?> | <?= $d["hoc_vi"] ?></p>

        <ul class="info-list">
            <li>
                <strong><i class="fa fa-id-badge"></i> Kinh nghiệm:</strong>
                <div class="detail-content"><?= $d["kinh_nghiem"] ?></div>
            <li>
                <strong><i class="fa fa-graduation-cap"></i> Quá trình đào tạo</strong>
                <div class="detail-content"><?= $d["qua_trinh_dao_tao"] ?></div>
            </li>
            
            <li>
                <strong><i class="fa fa-hospital-o"></i> Quá trình công tác</strong>
                <div class="detail-content"><?= $d["qua_trinh_cong_tac"] ?></div>
            </li>
            <li>
                <strong><i class="fa fa-certificate"></i> Chứng chỉ</strong>
                <div class="detail-content"><?= $d["chung_chi"] ?></div>
            <li>
                <strong><i class="fa fa-star"></i> Chuyên môn nghiệp vụ</strong>
                <div class="detail-content"><?= $d["chuyen_mon"] ?></div>
            </li>
        </ul>
    </div>
</main>
