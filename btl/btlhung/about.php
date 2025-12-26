<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Về Chúng Tôi - Healthcare</title>
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
        a {
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
        a:hover {
            background-color: rgba(9, 151, 80, 1);
            color: white;
        }
        
    </style>
</head>
<body>
    <?php include 'connect.php'; ?> 
    <?php include 'index.php'; ?> 

    <main>
        <div class="tieude1">
            <img src="vien.jpg">
            <div>
                <div class="container">
                    <h1>Về Chúng Tôi</h1>
                    <p>AN TOÀN - CHẤT LƯỢNG - PHÁT TRIỂN</p>
                </div>
                <div class="container2">
                    <ul>
                        <li><a class="a1" href="about.php?page_layout=gtc">Giới thiệu chung</a></li>
                        <li><a class="a2" href="about.php?page_layout=nhiemvu">Nhiệm vụ</a></li>
                        <li><a class="a3" href="about.php?page_layout=doingu">Đội ngũ lãnh đạo</a></li>
                        <li><a class="a4" href="about.php?page_layout=tamnhin">Tầm nhìn và sứ mệnh</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </main>
    <?php
        if (isset($_GET['page_layout'])) {
            //echo "Xin chào " . $_SESSION['username'];
            switch ($_GET['page_layout']) {
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
                }
        } else {
            include 'gtc.php';
        }
    ?>
    <?php include 'footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>