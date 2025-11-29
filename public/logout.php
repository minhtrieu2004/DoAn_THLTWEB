<?php
// Bắt đầu session để có thể truy cập các biến session
session_start();

// 1. Xóa tất cả các biến session
// Đặt session thành một mảng trống
$_SESSION = array();

// 2. Nếu muốn hủy bỏ session cookie (tùy chọn)
// Lấy tham số session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Hủy bỏ session
session_destroy();

// 4. Chuyển hướng người dùng về trang chủ (hoặc trang đăng nhập)
header("Location: index.php");
exit();
