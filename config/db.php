<?php
$host = 'localhost';
$db = 'shop_badminton';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';


$dsn = "mysql:host=$host; dbname=$db; charset=$charset";
$option = [
    //Báo lỗi ngoại lệ khi có lỗi CSDL xảy ra
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // Chế độ tìm nạp mặc định: trả về mảng kết hợp
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    //Tắt chế độ mô phỏng các truy vấn chuẩn bị (Prepared Statements) để bảo mật
    PDO::ATTR_EMULATE_PREPARES => FALSE,
];

try {
    //Khởi động đối tượng PDO và thiết lập kết nối
    $pdo = new PDO($dsn, $user, $pass, $option);
    //Biến $pdo là đối tượng kết nối sẽ dùng

} catch (\PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
