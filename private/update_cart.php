<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Chưa đăng nhập"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';
$item_id = intval($_POST['item_id'] ?? $_POST['cart_item_id'] ?? 0);

// Lấy cart_id của user
$stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart = $stmt->fetch();

if (!$cart) {
    echo json_encode(["success" => false, "message" => "Giỏ hàng không tồn tại"]);
    exit;
}

$cart_id = $cart['cart_id'];

// Kiểm tra item thuộc cart của user
$stmt = $pdo->prepare("SELECT cart_item_id, quantity FROM cart_items WHERE cart_item_id = ? AND cart_id = ?");
$stmt->execute([$item_id, $cart_id]);
$item = $stmt->fetch();

if (!$item) {
    echo json_encode(["success" => false, "message" => "Sản phẩm không tồn tại"]);
    exit;
}

// Xử lý action
$currentQty = $item['quantity'];
switch ($action) {
    case 'increase':
        $newQty = $currentQty + 1;
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
        $stmt->execute([$newQty, $item_id]);
        break;
    case 'decrease':
        $newQty = $currentQty - 1;
        if ($newQty <= 0) {
            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_item_id = ?");
            $stmt->execute([$item_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
            $stmt->execute([$newQty, $item_id]);
        }
        break;
    case 'remove':
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_item_id = ?");
        $stmt->execute([$item_id]);
        break;
}

echo json_encode(["success" => true, "message" => "Cập nhật thành công"]);
?>
