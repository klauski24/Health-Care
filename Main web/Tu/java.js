let thong_tin_dat_lich = {
    bac_si: null,
    ngay: "",
    id_khung_gio: null,
    gio_kham: "",
    ghi_chu: ""
};

function chonBacSi(du_lieu, phan_tu) {
    thong_tin_dat_lich.bac_si = du_lieu;
    document.querySelectorAll('.the-bac-si').forEach(the => the.classList.remove('da-chon'));
    phan_tu.classList.add('da-chon');
    document.getElementById('ten-bs-da-chon').innerText = "BS. " + du_lieu.ho_ten;
}

function layKhungGio() {
    if (!thong_tin_dat_lich.bac_si) return alert("Vui lòng chọn bác sĩ!");
    thong_tin_dat_lich.ngay = document.getElementById('ngay-kham').value;

    fetch(`get_schedule.php?id_bs=${thong_tin_dat_lich.bac_si.id}&ngay=${thong_tin_dat_lich.ngay}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('vung-chon-gio').innerHTML = html;
        });
}

function chonGio(id, gio, phan_tu) {
    thong_tin_dat_lich.id_khung_gio = id;
    thong_tin_dat_lich.gio_kham = gio;
    document.querySelectorAll('.khoang-thoi-gian').forEach(nut => nut.classList.remove('da-chon'));
    phan_tu.classList.add('da-chon');
}

function buocTiepTheo(buoc_so) {
    if (buoc_so === 2 && !thong_tin_dat_lich.bac_si) return alert("Bạn chưa chọn bác sĩ!");
    if (buoc_so === 3 && (!thong_tin_dat_lich.ngay || !thong_tin_dat_lich.id_khung_gio)) return alert("Vui lòng chọn ngày và giờ!");

    if (buoc_so === 4) {
        document.getElementById('bs-cuoi-cung').innerText = thong_tin_dat_lich.bac_si.ho_ten;
        document.getElementById('gio-cuoi-cung').innerText = `${thong_tin_dat_lich.gio_kham} ngày ${thong_tin_dat_lich.ngay}`;
        document.getElementById('ghi-chu-cuoi-cung').innerText = document.getElementById('trieu-chung-nhap').value || "Không có";
    }

    document.querySelectorAll('.phan-buoc').forEach((p, i) => p.classList.toggle('dang-kich-hoat', i === (buoc_so - 1)));

    document.querySelectorAll('.buoc').forEach((b, i) => {
        b.classList.toggle('dang-kich-hoat', i === (buoc_so - 1));
        b.classList.toggle('da-hoan-thanh', i < (buoc_so - 1));
    });
}

function xacNhanDatLich() {
    thong_tin_dat_lich.ghi_chu = document.getElementById('trieu-chung-nhap').value;

    fetch('save_booking.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(thong_tin_dat_lich)
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert("Đăng ký thành công!");
                window.location.href = "booking.php";
            } else {
                alert("Lỗi: " + data.message);
            }
        });
}
