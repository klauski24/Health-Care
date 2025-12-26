<?php
session_start();

// 1. Xóa tất cả biến session
$_SESSION = array();

// 2. Xóa cookie session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hủy session
session_destroy();

// 4. Redirect
echo '<script>
    alert("Đã đăng xuất!");
    window.location.href = "../index.php";
</script>';
exit();
?>