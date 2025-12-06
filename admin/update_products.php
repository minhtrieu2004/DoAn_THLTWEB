<?php
// update_products.php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update') {
    // 1. Lấy và làm sạch dữ liệu
    $product_id = (int)$_POST['productId'];
    $name = trim($_POST['productName']);
    $category_id = (int)$_POST['categoryId'];
    $price = (float)$_POST['productPrice'];
    $stock = (int)$_POST['productStock'];
    $description = trim($_POST['productDesc']);

    // (Bỏ qua logic xử lý file ảnh phức tạp hơn ở đây, chỉ cập nhật các trường text)

    try {
        // 2. Truy vấn UPDATE
        $sql = "UPDATE products SET 
                name = :name, 
                category_id = :cat, 
                price = :price, 
                stock = :stock, 
                description = :desc 
                WHERE product_id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':cat', $category_id, PDO::PARAM_INT);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
        $stmt->bindValue(':desc', $description);
        $stmt->bindValue(':id', $product_id, PDO::PARAM_INT);

        $stmt->execute();

        // 3. Thông báo và chuyển hướng
        $_SESSION['success'] = "Cập nhật sản phẩm ID {$product_id} thành công!";
        header('Location: ' . $_SERVER['HTTP_REFERER']); // Quay lại trang Admin
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi CSDL khi cập nhật: " . $e->getMessage();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
} else {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
