<?php
// get_product_data.php
require '../config/db.php';
// Thiết lập header để trình duyệt hiểu đây là dữ liệu JSON
header('Content-Type: application/json');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = (int)$_GET['id'];

    // Truy vấn CSDL bằng Prepared Statement
    $sql = "SELECT product_id, category_id, name, price, stock, description FROM products WHERE product_id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Trả về dữ liệu sản phẩm hoặc null nếu không tìm thấy
    echo json_encode($product);
} else {
    echo json_encode(null);
}
?>
