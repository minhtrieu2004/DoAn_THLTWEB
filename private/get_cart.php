<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Bạn chưa đăng nhập"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy cart_id
$stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cart) {
    echo json_encode([
        "success" => true,
        "cart" => [],
        "totalPrice" => 0,
        "totalQuantity" => 0
    ]);
    exit;
}

$cart_id = $cart['cart_id'];

// Lấy item + sản phẩm
$sql = "SELECT 
            ci.cart_item_id, 
            ci.product_id, 
            ci.quantity,
            p.name,
            p.price,
            p.image_main AS image
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.product_id
        WHERE ci.cart_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$cart_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tính tổng
$totalPrice = 0;
$totalQuantity = 0;

foreach ($items as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
    $totalQuantity += $item['quantity'];
}

echo json_encode([
    "success" => true,
    "cart" => $items,
    "totalPrice" => $totalPrice,
    "totalQuantity" => $totalQuantity
]);
