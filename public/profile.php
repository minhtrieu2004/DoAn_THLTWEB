<?php
require_once '../config/db.php';
require_once '../includes/header.php';

// 1. Bắt buộc phải đăng nhập
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Truy vấn Database để lấy thông tin người dùng
$sql = "SELECT full_name, email, phone, address, created_at, username FROM users WHERE user_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// 3. Kiểm tra dữ liệu
if (!$user_data) {
    echo "Lỗi: Không tìm thấy dữ liệu người dùng!";
    require_once '../includes/footer.php';
    exit();
}
?>

<div class="container my-5">
    <h2>👋 Hồ sơ Cá nhân của <?php echo htmlspecialchars($user_data['full_name']); ?></h2>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Tên đăng nhập:</strong> <?php echo htmlspecialchars($user_data['username'] ?? ''); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email'] ?? ''); ?></p>
            <p><strong>Điện thoại:</strong> <?php echo htmlspecialchars($user_data['phone'] ?? ''); ?></p>
            <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($user_data['address']  ?? ''); ?></p>
            <p><strong>Ngày tham gia:</strong> <?php echo date("d/m/Y", strtotime($user_data['created_at'] ?? '')); ?></p>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>