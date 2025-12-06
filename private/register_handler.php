<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/register.php');
    exit();
}

// 1. Lấy và làm sạch dữ liệu
$full_name = trim($_POST['full_name'] ?? '');
$username  = trim($_POST['username'] ?? '');
$email     = trim($_POST['email'] ?? '');
// << LẤY DỮ LIỆU MỚI >>
$phone     = trim($_POST['phone'] ?? '');
$address   = trim($_POST['address'] ?? '');
// << KẾT THÚC LẤY DỮ LIỆU MỚI >>
$password  = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// 2. Xác thực cơ bản
if (empty($full_name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $_SESSION['error'] = "Vui lòng nhập đầy đủ các trường bắt buộc.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Định dạng Email không hợp lệ.";
} elseif ($password !== $confirm_password) {
    $_SESSION['error'] = "Mật khẩu và xác nhận mật khẩu không khớp.";
} elseif (strlen($password) < 6) {
    $_SESSION['error'] = "Mật khẩu phải chứa ít nhất 6 ký tự.";
}

// Nếu có lỗi, quay lại trang đăng ký
if (isset($_SESSION['error'])) {
    header('Location: ../public/register.php');
    exit();
}

// 3. Kiểm tra Username/Email trùng lặp (Không thay đổi)
try {
    $sql_check = "SELECT user_id FROM users WHERE username = :username OR email = :email LIMIT 1";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':username' => $username, ':email' => $email]);

    if ($stmt_check->rowCount() > 0) {
        $_SESSION['error'] = "Tên đăng nhập hoặc Email này đã được sử dụng.";
        header('Location: ../public/register.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Lỗi kiểm tra trùng lặp: " . $e->getMessage();
    header('Location: ../public/register.php');
    exit();
}

// 4. Mã hóa Mật khẩu (Hashing)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 5. Lưu người dùng vào Database (ĐÃ SỬA CÂU LỆNH INSERT)
try {
    $sql_insert = "INSERT INTO users (full_name, username, email, phone, address, password, role) 
                   VALUES (:full_name, :username, :email, :phone, :address, :password_hash, 'user')";

    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->execute([
        ':full_name' => $full_name,
        ':username' => $username,
        ':email' => $email,
        // << THÊM BINDING CỦA PHONE VÀ ADDRESS >>
        ':phone' => $phone,
        ':address' => $address,
        // << KẾT THÚC BINDING MỚI >>
        ':password_hash' => $hashed_password, // Lưu chuỗi hash
    ]);

    // Đăng ký thành công
    $_SESSION['success'] = "Đăng ký tài khoản thành công! Vui lòng đăng nhập.";
    header('Location: ../public/login.php');
    exit();
} catch (PDOException $e) {
    // Lỗi khi chèn dữ liệu
    $_SESSION['error'] = "Lỗi khi đăng ký: " . $e->getMessage();
    header('Location: ../public/register.php');
    exit();
}
