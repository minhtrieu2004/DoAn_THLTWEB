<?php
session_start();

// Nhận dữ liệu từ AJAX (POST)
$name  = $_POST['name'] ?? '';
$price = isset($_POST['price']) ? (float)$_POST['price'] : 0;

// Nếu không có giỏ hàng → tạo giỏ hàng mới
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Nếu sản phẩm đã tồn tại trong giỏ → tăng số lượng
if (isset($_SESSION['cart'][$name])) {
    $_SESSION['cart'][$name]['quantity']++;
} else {
    // Nếu sản phẩm chưa có → thêm mới
    $_SESSION['cart'][$name] = [
        'name' => $name,
        'price' => $price,
        'quantity' => 1
    ];
}

// Tính tổng số lượng của tất cả sản phẩm
$totalQuantity = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalQuantity += $item['quantity'];
}

// Trả về JSON để JS cập nhật số lượng hiện trên biểu tượng cart
echo json_encode([
    'success' => true,
    'totalQuantity' => $totalQuantity
]);
?>
