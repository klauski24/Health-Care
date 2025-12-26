

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
            .lien {
                background-color: #130569f0;
                width: 100%;
                height: 10vh; 
            }
            .lien p {     
                text-align: center;          
                color: white;
                padding: 10px;
                font-weight: bold;               
                margin: 0 auto;
                font-size: 22px;
            }
            .a4 {
                background: linear-gradient(to right, #0094d9 0,#30ae4b);
                color: white;
            }
            .a3 {
                    color: black;
                    background-color: white;
            }
            .a3:hover {
                background-color: rgba(9, 151, 80, 1);
                color: white;
            }

            .stats-container {
                display: flex;
                justify-content: space-around;
                align-items: center;
                background: linear-gradient(135deg, #130569, #004a99);
                padding: 50px 0;
                margin: 40px 10px;
                color: white;
                border-radius: 20px;
                text-align: center;
            }
            
            .stat-item {
                flex: 1;
            }
            
            .stat-number {
                font-size: 45px;
                font-weight: bold;
                margin-bottom: 10px;
            }
            
            /* Thêm dấu cộng hoặc đơn vị sau số */
            .stat-number::after {
                content: '+';
                font-size: 30px;
                margin-left: 5px;
                color: #30ae4b; /* Màu xanh lá điểm xuyết */
            }
            
            .stat-label {
                font-size: 16px;
                text-transform: uppercase;
                letter-spacing: 1px;
                opacity: 0.9;
            }

            .tieude2 {
                
                margin-top: 60px;
            } 
            .o {
                margin: 0 150px;
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 30px;
            }
            .oo {
                box-shadow: 0 10px 30px rgba(11, 11, 11, 0.1);              
                padding: 20px;
                height: 150px;
                border-radius: 15px;
                font-size: 17px;
                width: 350px;
                transition: 0.3s;
            }
            .oo:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(5, 96, 193, 0.38);
            }
    </style>
</head>
<body>
    <div class="lien">
            <p>Tầm Nhìn & Sứ mệnh</p>        
    </div>

    <div class="stats-container">
    <div class="stat-item">
        <div class="stat-number" data-target="10">0</div>
        <div class="stat-label">Năm kinh nghiệm</div>
    </div>
    <div class="stat-item">
        <div class="stat-number" data-target="200">0</div>
        <div class="stat-label">Đội ngũ Bác sĩ</div>
    </div>
    <div class="stat-item">
        <div class="stat-number" data-target="50000">0</div>
        <div class="stat-label">Khách hàng tin tưởng</div>
    </div>
    <div class="stat-item">
        <div class="stat-number" data-target="98">0</div>
        <div class="stat-label">% Hài lòng</div>
    </div>
</div>  

    <div class="tieude2">
            
                <div class="o">
                    <div class="oo">
                        <div class="icon">🚀</div>
                        <h3>Sứ mệnh</h3>
                        <p>Cung cấp giải pháp y tế tối ưu, chi phí hợp lý và tận tâm như người nhà.</p>
                    </div>
                    <div class="oo">
                        <div class="icon">👁️</div>
                        <h3>Tầm nhìn</h3>
                        <p>Trở thành hệ thống chăm sóc sức khỏe kỹ thuật số hàng đầu khu vực vào năm 2030.</p>
                    </div>
                    <div class="oo">
                        <div class="icon">❤️</div>
                        <h3>Giá trị cốt lõi</h3>
                        <p>Y đức - Chuyên nghiệp - Thấu hiểu - Đổi mới công nghệ.</p>
                    </div>
                </div>
            
        </div>
        <script>
    const counters = document.querySelectorAll('.stat-number');
    const speed = 100; // Tốc độ chạy

    const startCounters = () => {
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            let count = 0; // Luôn bắt đầu từ 0 để tính toán chính xác
            
            const updateCount = () => {
                const inc = target / speed;
                if (count < target) {
                    count += inc;
                    counter.innerText = Math.ceil(count);
                    setTimeout(updateCount, 20);
                } else {
                    counter.innerText = target;
                }
            };
            updateCount();
        });
    };

    // Sử dụng Intersection Observer (Xịn hơn window.onscroll)
    // Giúp kích hoạt chính xác khi phần tử xuất hiện trên màn hình
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                startCounters();
                observer.unobserve(entry.target); // Chỉ chạy 1 lần duy nhất
            }
        });
    }, { threshold: 0.5 }); // Khi thấy được 50% khung hình thì chạy

    observer.observe(document.querySelector('.stats-container'));
</script>
</body>
</html>