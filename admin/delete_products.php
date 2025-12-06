<?php
session_start();
require '../config/db.php'; // Kết nối CSDL
// include '../public/header.php';
// Kiểm tra quyền admin nếu cần
// if (!isset($_SESSION['is_admin'])) {
//     header('Location: login.php');
//     exit;
// }

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = (int)$_GET['id'];

    try {
        // 1. Xóa bản ghi trong CSDL (Sử dụng Prepared Statement để an toàn)
        $sql = "DELETE FROM products WHERE product_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $product_id, PDO::PARAM_INT);
        $stmt->execute();

        // 2. Chuyển hướng và thông báo thành công
        $_SESSION['success'] = "Đã xóa sản phẩm ID {$product_id} thành công!";
        header('Location: ' . $_SERVER['HTTP_REFERER']); // Quay lại trang trước
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi CSDL khi xóa: " . $e->getMessage();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
} else {
    $_SESSION['error'] = "ID sản phẩm không hợp lệ.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

include '../public/footer.php';
