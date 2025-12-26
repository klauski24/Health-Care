    <style>

        body { 
            margin: 0px;
        }
        .tieude1 {
            background-color: #130569f0;
            width: 100%;
            height: 30vh;
            
            display: flex;
            
        }
        .tieude1 img {
            width: 20%;
            height: 40vh;
            border-radius: 0px 5px 50px 0px;
            margin-right: 120px;
        }
        .container {             
        text-align: center;
        padding:10px;
        font-weight: bold;
        color: white;
        }
        .container2 ul {
            display: flex;
            justify-content: center;
            list-style: none;
        }
        .container2 a {
            text-decoration: none;
            color: black;
            padding: 11px;
            font-size: 17px;
            font-weight: bold;
            border: none;
            border-radius: 15px;
            background-color: white;
            margin-left: 20px;
        }
        .container2 a:hover {
            background-color: rgba(9, 151, 80, 1);
            color: white;
        }
        
    </style>
    <?php include 'connect.php'; ?> 
    <?php
    // Xác định sub-page hiện tại
    $sub_page = isset($_GET['sub']) ? $_GET['sub'] : 'gtc';
    ?>
    <main>
        <div class="tieude1">
            <img src="about-us/vien.png">
            <div>
                <div class="container">
                    <h1>Về Chúng Tôi</h1>
                    <p>AN TOÀN - CHẤT LƯỢNG - PHÁT TRIỂN</p>
                </div>
                <div class="container2">
                    <ul>
                        <!-- QUAN TRỌNG: DÙNG index.php?page_layout=about&sub=... -->
                        <li><a class="a1 <?php echo ($sub_page == 'gtc') ? 'active' : ''; ?>" 
                            href="index.php?page_layout=about&sub=gtc">Giới thiệu chung</a></li>
                        <li><a class="a2 <?php echo ($sub_page == 'nhiemvu') ? 'active' : ''; ?>" 
                            href="index.php?page_layout=about&sub=nhiemvu">Nhiệm vụ</a></li>
                        <li><a class="a3 <?php echo ($sub_page == 'doingu') ? 'active' : ''; ?>" 
                            href="index.php?page_layout=about&sub=doingu">Đội ngũ lãnh đạo</a></li>
                        <li><a class="a4 <?php echo ($sub_page == 'tamnhin') ? 'active' : ''; ?>" 
                            href="index.php?page_layout=about&sub=tamnhin">Tầm nhìn và sứ mệnh</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </main>
    <?php
    // Include nội dung theo sub-page
    switch($sub_page) {
        case 'gtc':
            include 'gtc.php';
            break;
        case 'nhiemvu':
            include 'nhiemvu.php';
            break;
        case 'doingu':
            include 'doingu.php';
            break;
        case 'tamnhin':
            include 'tamnhin.php';
            break;
        default:
            include 'gtc.php';
            break;
    }
    ?>
<?php
$conn->close();
?>