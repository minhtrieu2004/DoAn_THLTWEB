<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require "../config/db.php";

// ===========================================
// 1. Nếu chưa đăng nhập → lấy giỏ guest
// ===========================================
if (!isset($_SESSION['user_id'])) {

    if (empty($_SESSION['cart_guest'])) {
        echo json_encode([
            "success" => true,
            "cart" => [],
            "totalPrice" => 0,
            "totalQuantity" => 0
        ]);
        exit;
    }

    $items = [];
    $totalPrice = 0;
    $totalQuantity = 0;

    foreach ($_SESSION['cart_guest'] as $product_id => $quantity) {

        // Lấy info sản phẩm từ DB
        $stmt = $pdo->prepare("SELECT name, price, image_main AS image FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) continue;

        $subtotal = $product['price'] * $quantity;

        $items[] = [
            "cart_item_id" => $product_id, // guest dùng product_id làm id tạm
            "product_id" => $product_id,
            "name" => $product['name'],
            "price" => $product['price'],
            "image" => $product['image'],
            "quantity" => $quantity
        ];

        $totalPrice += $subtotal;
        $totalQuantity += $quantity;
    }

    echo json_encode([
        "success" => true,
        "cart" => $items,
        "totalPrice" => $totalPrice,
        "totalQuantity" => $totalQuantity
    ]);
    exit;
}



// ===========================================
// 2. Nếu đã đăng nhập → giỏ hàng DB
// ===========================================
$user_id = $_SESSION['user_id'];

// Lấy cart_id
$stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart = $stmt->fetch(PDO::FETCH_ASSOC);

// Nếu chưa có giỏ hàng trong DB
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


// Lấy danh sách sản phẩm
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


// Tính tổng số lượng + tổng tiền
$totalPrice = 0;
$totalQuantity = 0;

foreach ($items as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
    $totalQuantity += $item['quantity'];
}


// Trả JSON
echo json_encode([
    "success" => true,
    "cart" => $items,
    "totalPrice" => $totalPrice,
    "totalQuantity" => $totalQuantity
]);
