<?php
// 1. DATABASE: Kết nối và truy vấn thông tin
include(__DIR__ . '/../connect.php');

$id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;
$sql = "SELECT * FROM dich_vu WHERE id = $id";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    die("Dịch vụ không tồn tại!");
}
$row = $result->fetch_assoc();
?>
    <title><?php echo $row['ten_dich_vu']; ?> - Chi tiết dịch vụ</title>
    
    <style>
        body {
            margin: 0;
        }    
        .container { 
            width: 70%;
            margin: 50px auto;
            border-radius: 20px; 
            overflow: hidden; 
            box-shadow: 0 10px 30px rgba(76, 70, 70, 0.65); 
        }     
        .anh { 
            width: 100%; 
            height: 400px; 
            background: url('<?php echo $row['hinh_anh'] ?: "https://via.placeholder.com/1000x400"; ?>') center/cover; 
            position: relative;
        }
        .gia { 
            position: absolute; bottom: 20px; 
            right: 20px; 
            background: #30ae4b; 
            color: white; 
            padding: 10px 25px; 
            border-radius: 50px; 
            font-weight: bold; 
            font-size: 20px;
        }
        h1 { 
            color: #130569  ; 
            margin-top: 10px; 
        }
        .nghieng { 
            color: #888; 
            font-style: italic; 
            margin-bottom: 20px; 
            border-left: 4px solid #30ae4b;
            padding: 5px;
            font-size: 17px;
        }
        
        .quatrinh { 
            font-size: 18px; 
            color: #555; 
            margin-bottom: 50px; 
        }
        
        .ngang { 
            display: flex; 
            border-top: 3px solid #1f189dff; 
            padding-top: 30px; 
            margin-bottom: 30px;
            gap: 50px;
        }
        .quaylai {
            color: #2ee64dff;
            text-decoration: none;
            font-weight: bold;
            padding: 12px; 
        }
        .share { 
            background: #fc24243b;
            padding: 12px; 
            border-radius: 20px;  
            font-weight: bold; 
            transition: 0.3s; 
            cursor: pointer; 
            border: none;
        }
        .datlich { 
            padding: 12px; 
            border-radius: 20px; 
            text-decoration: none; 
            font-weight: bold; 
            transition: 0.3s; 
            cursor: pointer;
            background: #130569;
            color: white;

        }
        .datlich:hover { 
            color: black; 
            background: #8af8a2ff; 
            transform: translateY(-2px); 
        }
    </style>
<main>

    <div class="container">
        <div class="anh">
            <div class="gia"><?php echo number_format($row['gia'], 0, ',', '.'); ?> VNĐ</div>
        </div>
        
        <div style="padding: 40px;">
            <h1><?php echo htmlspecialchars($row['ten_dich_vu']); ?></h1>
            
            <p class="nghieng"><?php echo htmlspecialchars($row['mo_ta']); ?></p>
    
            <div class="quatrinh">
                <?php 
                    echo nl2br(htmlspecialchars($row['quy_trinh'] ?: "Hiện chưa có bài viết chi tiết cho dịch vụ này. Vui lòng liên hệ hotline để biết thêm thông tin.")); 
                ?>
            </div>
        </div>

        <div class="ngang">
            <a href="index.php?page_layout=services" class="quaylai">← Quay lại danh sách</a>
            <button onclick="shareService()" class="share">Chia sẻ dịch vụ</button>
            <a href="index.php?page_layout=khamdichvu&service_id=<?php echo $row['id']; ?>" class="datlich">Đặt lịch khám ngay</a> 
        </div>   
    </div>
    <div style="height: 50px;"></div>

    <script>
        function shareService() {
            const url = window.location.href;
            // Sử dụng Clipboard API của JS
            navigator.clipboard.writeText(url).then(() => {
                alert("Đã sao chép liên kết dịch vụ! Bạn có thể gửi cho người thân.");
            }).catch(err => {
                console.error('Lỗi khi sao chép: ', err);
            });
        }

        // Hiệu ứng Fade-in khi load trang
        document.body.style.opacity = 0;
        window.onload = function() {
            document.body.style.transition = "opacity 0.8s";
            document.body.style.opacity = 1;
        };
    </script>

</main>
<?php $conn->close(); ?>