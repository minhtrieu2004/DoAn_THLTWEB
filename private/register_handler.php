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

// 2. Xác thực cơ bản với ràng buộc chi tiết
$errors = [];

// Kiểm tra Full Name
if (empty($full_name)) {
    $errors[] = "Họ và tên không được để trống.";
} elseif (strlen($full_name) < 3) {
    $errors[] = "Họ và tên phải có ít nhất 3 ký tự.";
} elseif (strlen($full_name) > 100) {
    $errors[] = "Họ và tên không được vượt quá 100 ký tự.";
} elseif (preg_match('/[<>{}[\]\\\\\/`~!@#$%^&*()+=|;:\'",?]/u', $full_name)) {
    $errors[] = "Họ và tên không được chứa ký tự đặc biệt.";
}

// Kiểm tra Username
if (empty($username)) {
    $errors[] = "Tên đăng nhập không được để trống.";
} elseif (strlen($username) < 3) {
    $errors[] = "Tên đăng nhập phải có ít nhất 3 ký tự.";
} elseif (strlen($username) > 50) {
    $errors[] = "Tên đăng nhập không được vượt quá 50 ký tự.";
} elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $errors[] = "Tên đăng nhập chỉ chứa chữ cái, số và dấu gạch dưới.";
}

// Kiểm tra Email
if (empty($email)) {
    $errors[] = "Email không được để trống.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Định dạng Email không hợp lệ.";
} elseif (strlen($email) > 100) {
    $errors[] = "Email không được vượt quá 100 ký tự.";
}

// Kiểm tra Phone (nếu có)
if (!empty($phone)) {
    if (!preg_match('/^[0-9\+\-\s\(\)]+$/', $phone)) {
        $errors[] = "Số điện thoại chỉ chứa chữ số, +, -, (), và khoảng trắng.";
    } elseif (strlen($phone) > 20) {
        $errors[] = "Số điện thoại không được vượt quá 20 ký tự.";
    }
}

// Kiểm tra Address (nếu có)
if (!empty($address)) {
    if (strlen($address) > 200) {
        $errors[] = "Địa chỉ không được vượt quá 200 ký tự.";
    }
}

// Kiểm tra Password
if (empty($password)) {
    $errors[] = "Mật khẩu không được để trống.";
} elseif (strlen($password) < 6) {
    $errors[] = "Mật khẩu phải chứa ít nhất 6 ký tự.";
} elseif (strlen($password) > 100) {
    $errors[] = "Mật khẩu không được vượt quá 100 ký tự.";
} elseif (!preg_match('/[a-z]/', $password) || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
    $errors[] = "Mật khẩu phải chứa chữ thường, chữ hoa và chữ số.";
}

// Kiểm tra Confirm Password
if (empty($confirm_password)) {
    $errors[] = "Xác nhận mật khẩu không được để trống.";
} elseif ($password !== $confirm_password) {
    $errors[] = "Mật khẩu và xác nhận mật khẩu không khớp.";
}

// Nếu có lỗi, quay lại trang đăng ký
if (!empty($errors)) {
    $_SESSION['error'] = implode('<br>', $errors);
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
    $_SESSION['success'] = "✓ Đăng ký tài khoản thành công! <br>";
    header('Location: ../public/login.php');
    exit();
} catch (PDOException $e) {
    // Lỗi khi chèn dữ liệu
    $_SESSION['error'] = "Lỗi khi đăng ký: " . $e->getMessage();
    header('Location: ../public/register.php');
    exit();
}
