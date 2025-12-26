<?php
session_start();

// Nếu là trang đăng ký/đăng nhập, load toàn bộ file (không qua template)
if (isset($_GET['page_layout'])) {
    switch($_GET['page_layout']) {
        case 'register':
            include "dang-nhap-dang-ky/dangky.php";
            exit(); // Dừng lại, không load tiếp template
        case 'login':
            include "dang-nhap-dang-ky/dangnhap.php";
            exit(); // Dừng lại, không load tiếp template
        case 'forgot_password':
            include "dang-nhap-dang-ky/quenmatkhau.php";
            exit(); // Dừng lại, không load tiếp template
        case 'khamdichvu':
            include "about-us/khamdichvu.php";
            exit(); // Dừng lại, không load tiếp template
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chăm Sóc Sức Khỏe</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <header>
        <div class="logo-header">
            <img src="./Picture/logo.png" alt="Health Care Logo">
        </div>
        <div class="menu-header">
            <nav class="nav-header">
                <a href="index.php?page_layout=mainweb">Trang chủ</a>
                <a href="index.php?page_layout=services">Dịch vụ</a>
                <a href="index.php?page_layout=doctors">Bác sĩ</a>
                <a href="index.php?page_layout=about" id="about-link">Về chúng tôi</a>
                <a href="index.php?page_layout=contact">Liên hệ</a>
            </nav>
            <nav class="nav-auth">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <!-- Hiển thị khi đã đăng nhập -->
                    <div class="user-info">
                        <span>👤 <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                        <a href="dang-nhap-dang-ky/dashboard.php" class="btn-dashboard">Dashboard</a>
                        <a href="dang-nhap-dang-ky/logout.php" class="btn-logout">Đăng xuất</a>
                    </div>
                <?php else: ?>
                    <!-- Hiển thị khi chưa đăng nhập -->
                    <a href="index.php?page_layout=register" class="btn-register">Đăng ký</a>
                    <a href="index.php?page_layout=login" class="btn-login">Đăng nhập</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    
    <!-- Dropdown menu sẽ được tạo bởi JavaScript -->
    
        <?php  
            if(isset($_GET['page_layout'])){
                switch($_GET['page_layout']){
                    case 'mainweb':
                        include "mainweb.php";
                        break;
                    case 'khamdichvu':
                        include "about-us/khamdichvu.php";
                        break;
                    case 'thongtinkhamdichvu':
                        include "about-us/thongtinkhamdichvu.php";
                        break;
                    case 'services':
                        include "about-us/services.php";
                        break;
                    case 'doctors':
                        include "contact/doctors.php";
                        break;
                    case 'xemchitietdichvu':
                        include "about-us/xemchitietdichvu.php";
                        break;
                    case 'about':
                        include "about-us/about.php";
                        break;
                    case 'contact':
                        include "contact/contact2.php";
                        break;
                    case 'datlich':
                        include "dat-lich/index.php";
                        break;
                    case 'login':
                        include "dang-nhap-dang-ky/dangnhap.php";
                        break;
                    case 'register':
                        include "dang-nhap-dang-ky/dangky.php";
                        break;
                    case 'forgot_password':
                        include "dang-nhap-dang-ky/quenmatkhau.php";
                        break;
                    case 'dashboard':
                        include "dang-nhap-dang-ky/dashboard.php";
                        break;
                    case 'logout':
                        include "dang-nhap-dang-ky/logout.php";
                        break;
                    default:
                        include "mainweb.php";
                        break;
                    }
    } else {
        include "mainweb.php";
    }
    ?>
    <?php 
    // CHỈ include footer nếu KHÔNG PHẢI trang datlich
    if (!isset($_GET['page_layout']) || $_GET['page_layout'] !== 'datlich') {
        include "footer.php";
    }
    ?>
    
    <script>
        // Tạo dropdown menu cho "Về chúng tôi"
        document.addEventListener('DOMContentLoaded', function() {
            const aboutLink = document.getElementById('about-link');
            const navHeader = document.querySelector('.nav-header');
            
            if (aboutLink && navHeader) {
                // Tạo dropdown container
                const dropdownContainer = document.createElement('div');
                dropdownContainer.className = 'dropdown-container';
                dropdownContainer.style.position = 'relative';
                dropdownContainer.style.display = 'inline-block';
                
                // Di chuyển aboutLink vào dropdown container
                aboutLink.parentNode.insertBefore(dropdownContainer, aboutLink);
                dropdownContainer.appendChild(aboutLink);
                
                // Thêm mũi tên
                aboutLink.innerHTML = 'Về chúng tôi <span style="font-size:10px; margin-left:5px;"></span>';
                
                // Tạo dropdown menu
                const dropdownMenu = document.createElement('div');
                dropdownMenu.className = 'dropdown-menu';
                dropdownMenu.style.cssText = `
                    display: none;
                    position: absolute;
                    background: white;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    border-radius: 5px;
                    z-index: 1000;
                    min-width: 200px;
                    top: 100%;
                    left: 0;
                    padding: 5px 0;
                `;
                
                // Thêm các mục vào dropdown
                const menuItems = [
                    { text: 'Giới thiệu chung', href: 'index.php?page_layout=about&sub=gtc' },
                    { text: 'Nhiệm vụ', href: 'index.php?page_layout=about&sub=nhiemvu' },
                    { text: 'Đội ngũ lãnh đạo', href: 'index.php?page_layout=about&sub=doingu' },
                    { text: 'Tầm nhìn và sứ mệnh', href: 'index.php?page_layout=about&sub=tamnhin' }
                ];
                
                menuItems.forEach(item => {
                    const link = document.createElement('a');
                    link.href = item.href;
                    link.textContent = item.text;
                    link.style.cssText = `
                        color: #333;
                        text-decoration: none;
                        display: block;
                        padding: 10px 15px;
                        border-bottom: 1px solid #eee;
                        font-weight: normal;
                        font-size: 14px;
                        text-align: left;
                    `;
                    
                    link.addEventListener('mouseenter', function() {
                        this.style.backgroundColor = '#f5f5f5';
                        this.style.color = '#1679C4';
                    });
                    
                    link.addEventListener('mouseleave', function() {
                        this.style.backgroundColor = '';
                        this.style.color = '';
                    });
                    
                    dropdownMenu.appendChild(link);
                });
                
                // Xóa border-bottom cho item cuối
                const lastItem = dropdownMenu.querySelector('a:last-child');
                if (lastItem) {
                    lastItem.style.borderBottom = 'none';
                }
                
                dropdownContainer.appendChild(dropdownMenu);
                
                // Xử lý hover để hiển thị/ẩn dropdown
                dropdownContainer.addEventListener('mouseenter', function() {
                    dropdownMenu.style.display = 'block';
                    aboutLink.style.color = '#0d47a1';
                    aboutLink.style.borderBottom = '1px solid #0d47a1';
                });
                
                dropdownContainer.addEventListener('mouseleave', function() {
                    dropdownMenu.style.display = 'none';
                    aboutLink.style.color = '';
                    aboutLink.style.borderBottom = '';
                });
            }
        });
    </script>
</body>
</html>