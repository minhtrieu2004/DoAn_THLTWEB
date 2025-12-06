<?php
// Bắt đầu Session và Nhúng CSDL
session_start();
require_once '../config/db.php';

// Kiểm tra yêu cầu POST từ form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/login.php'); // Chuyển về trang đăng nhập nếu không phải POST
    exit();
}

// 1. Lấy dữ liệu từ form

$username_or_email = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Ghi log debug để kiểm tra dữ liệu POST (dev only)
$debug = "[" . date('Y-m-d H:i:s') . "] " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . " METHOD=" . ($_SERVER['REQUEST_METHOD'] ?? '') . " POST=" . print_r($_POST, true) . "\n";
file_put_contents(__DIR__ . '/../log/login_debug.log', $debug, FILE_APPEND | LOCK_EX);


// Kiểm tra dữ liệu rỗng
if (empty($username_or_email) || empty($password)) {
    $_SESSION['error'] = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
    header('Location: ../public/login.php');
    exit();
}

// 2. Chuẩn bị truy vấn (Tìm người dùng bằng username/email)
// Sử dụng Prepared Statement để ngăn SQL Injection
$sql = "SELECT user_id, username, password AS password_hash, email, role FROM users WHERE username = :user_name OR email = :user_email LIMIT 1";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':user_name' => $username_or_email,
    ':user_email' => $username_or_email
]);
$user = $stmt->fetch();

// 3. Xác thực người dùng
if ($user) {
    $stored_hash = $user['password_hash'];
    // 4. Xác minh mật khẩu (Sử dụng password_verify cho mật khẩu đã mã hóa)
    if (password_verify($password, trim($stored_hash))) {

        // Đăng nhập thành công!
        // Thiết lập biến Session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['loggedin'] = true;
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: ../admin/admin_products.php");
            exit;
        }
        // Chuyển hướng đến trang chủ hoặc trang dashboard
        else {
            header("Location: ../public/index.php");
            exit;
        }
    } else {
        // Mật khẩu không đúng
        $_SESSION['error'] = "Mật khẩu không chính xác.";
    }
} else {
    // Tên đăng nhập/Email không tồn tại
    $_SESSION['error'] = "Tên đăng nhập hoặc Email không tồn tại.";
}

// Nếu có lỗi, lưu thông báo lỗi và quay lại trang đăng nhập
header('Location: ../public/login.php');
exit();
