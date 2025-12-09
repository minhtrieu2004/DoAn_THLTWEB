<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require "../config/db.php";

$action = $_POST['action'] ?? '';
$item_id = intval($_POST['item_id'] ?? 0);

// =============================================
// 1. Trường hợp KHÁCH CHƯA ĐĂNG NHẬP → xử lý SESSION
// =============================================
if (!isset($_SESSION['user_id'])) {

    // Nếu giỏ trống
    if (empty($_SESSION['cart_guest'])) {
        echo json_encode(['success' => true, 'cart' => [], 'totalPrice' => 0]);
        exit;
    }

    // Nếu muốn xóa sản phẩm
    if ($action === "remove") {
        unset($_SESSION['cart_guest'][$item_id]); // item_id = product_id
    }

    // Tăng số lượng
    if ($action === "increase") {
        $_SESSION['cart_guest'][$item_id]++;
    }

    // Giảm số lượng
    if ($action === "decrease") {
        $_SESSION['cart_guest'][$item_id]--;
        if ($_SESSION['cart_guest'][$item_id] <= 0) {
            unset($_SESSION['cart_guest'][$item_id]);
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Guest cart updated'
    ]);
    exit;
}




// =============================================
// 2. TRƯỜNG HỢP ĐÃ ĐĂNG NHẬP → xử lý trên DATABASE
// =============================================
$user_id = $_SESSION['user_id'];

// Lấy cart_item
$stmt = $pdo->prepare("SELECT * FROM cart_items WHERE cart_item_id = ?");
$stmt->execute([$item_id]);
$cartItem = $stmt->fetch();

if (!$cartItem) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
    exit;
}

$cart_item_id = $cartItem['cart_item_id'];
$quantity = $cartItem['quantity'];

// REMOVE
if ($action === 'remove') {
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_item_id = ?");
    $stmt->execute([$cart_item_id]);

    echo json_encode(['success' => true]);
    exit;
}

// DECREASE
if ($action === 'decrease') {
    if ($quantity > 1) {
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity - 1 WHERE cart_item_id = ?");
        $stmt->execute([$cart_item_id]);
    } else {
        // Quantity = 1 -> xóa
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_item_id = ?");
        $stmt->execute([$cart_item_id]);
    }
    echo json_encode(['success' => true]);
    exit;
}

// INCREASE
if ($action === 'increase') {
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE cart_item_id = ?");
    $stmt->execute([$cart_item_id]);

    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Action không hợp lệ']);
