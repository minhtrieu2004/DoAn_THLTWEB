<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Dev debug: log incoming POST and session
$debug_log = "[" . date('Y-m-d H:i:s') . "] POST: " . print_r($_POST, true) . " SID: " . session_id() . "\n";
file_put_contents(__DIR__ . '/../log/add_cart_debug.log', $debug_log, FILE_APPEND | LOCK_EX);

// Nhận dữ liệu từ AJAX (POST)
$name  = isset($_POST['name']) ? (string)$_POST['name'] : '';
$price = isset($_POST['price']) ? (float)$_POST['price'] : 0.0;

// Kiểm tra dữ liệu
if (empty($name) || $price <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid product data'
    ]);
    exit();
}

// Nếu không có giỏ hàng → tạo giỏ hàng mới
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
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
    $totalQuantity += isset($item['quantity']) ? (int)$item['quantity'] : 0;
}

// Tính tổng giá
$totalPrice = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalPrice += (float)$item['price'] * (int)$item['quantity'];
}

// Log cart state for debug TRƯỚC khi session_write_close()
file_put_contents(__DIR__ . '/../log/add_cart_debug.log', "  CART after: " . print_r($_SESSION['cart'], true) . "\n", FILE_APPEND | LOCK_EX);

// Ensure session is written to disk before responding
session_write_close();

// Trả về JSON response
echo json_encode([
    'success' => true,
    'message' => 'Product added to cart',
    'cart' => $_SESSION['cart'],
    'totalQuantity' => $totalQuantity,
    'totalPrice' => $totalPrice
]);
