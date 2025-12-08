<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Bạn chưa đăng nhập"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id'] ?? 0);

// Lấy/ tạo cart
$stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart = $stmt->fetch();

if (!$cart) {
    $pdo->prepare("INSERT INTO carts (user_id, created_at) VALUES (?, NOW())")->execute([$user_id]);
    $cart_id = $pdo->lastInsertId();
} else {
    $cart_id = $cart["cart_id"];
}

// Kiểm tra sản phẩm đã tồn tại chưa
$stmt = $pdo->prepare("SELECT cart_item_id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
$stmt->execute([$cart_id, $product_id]);
$item = $stmt->fetch();

if ($item) {
    // tăng số lượng
    $newQty = $item["quantity"] + 1;
    $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?")
        ->execute([$newQty, $item["cart_item_id"]]);
} else {
    // thêm mới
    $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, 1)")
        ->execute([$cart_id, $product_id]);
}

// → Lấy tổng số lượng
$stmt = $pdo->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart_items WHERE cart_id = ?");
$stmt->execute([$cart_id]);
$totalQuantity = (int)$stmt->fetch()["totalQuantity"];

echo json_encode([
    "success" => true,
    "message" => "Đã thêm vào giỏ hàng",
    "totalQuantity" => $totalQuantity
]);
