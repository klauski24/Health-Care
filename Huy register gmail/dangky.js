
<script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>

// Khởi tạo EmailJS với User ID của bạn
emailjs.init('pYjzlb9-E1aY1Kfyr');

// Hàm gửi email xác nhận
function sendConfirmationEmail(formData) {
    console.log('Đang gửi email xác nhận...', formData);

    // Dữ liệu email
    let emailData = {
        name: formData.full_name,
        email: formData.email,
        phone: formData.phone,
        cccd: formData.cccd.substring(0, 3) + '***' + formData.cccd.substring(formData.cccd.length - 3),
        date: new Date().toLocaleDateString('vi-VN'),
        time: new Date().toLocaleTimeString('vi-VN')
    };

    // Gửi email qua EmailJS
    emailjs.send('service_7ymy4wv', 'template_qifkek5', emailData)
        .then(function (response) {
            console.log('✅ Email gửi thành công!', response);
            // Có thể thêm thông báo hoặc cập nhật UI ở đây
        }, function (error) {
            console.log('❌ Lỗi gửi email:', error);
            // Vẫn thành công dù email có lỗi
        });
}

// Xử lý khi form submit
document.getElementById('registrationForm').addEventListener('submit', function (event) {
    // Lấy dữ liệu form
    let formData = {
        full_name: document.querySelector('input[name="full_name"]').value,
        email: document.querySelector('input[name="email"]').value,
        phone: document.querySelector('input[name="phone"]').value,
        cccd: document.querySelector('input[name="cccd"]').value
    };

    // Lưu vào sessionStorage để trang reload vẫn giữ
    sessionStorage.setItem('pendingEmailData', JSON.stringify(formData));
});

// Khi trang load xong
document.addEventListener('DOMContentLoaded', function () {
    // Kiểm tra nếu vừa đăng ký thành công
    let successAlert = document.querySelector('.alert.success');
    let pendingData = sessionStorage.getItem('pendingEmailData');

    if (successAlert && pendingData) {
        console.log('Đăng ký thành công, đang gửi email xác nhận...');

        let formData = JSON.parse(pendingData);
        sendConfirmationEmail(formData);

        // Xóa dữ liệu tạm
        sessionStorage.removeItem('pendingEmailData');

        // Thêm thông báo nhỏ
        setTimeout(function () {
            let emailNote = document.createElement('div');
            emailNote.className = 'alert info';
            emailNote.innerHTML = '📧 Email xác nhận đã được gửi đến ' + formData.email;
            document.querySelector('.form-body').insertBefore(emailNote, successAlert.nextSibling);
        }, 1000);
    }

    // Giới hạn ngày sinh không quá hôm nay
    let today = new Date().toISOString().split('T')[0];
    let birthDateInput = document.querySelector('input[name="birth_date"]');
    if (birthDateInput) {
        birthDateInput.max = today;
    }
});