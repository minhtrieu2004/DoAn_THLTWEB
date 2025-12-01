<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$cart = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];

$totalQuantity = 0;
$totalPrice = 0.0;
$items = [];

foreach ($cart as $name => $item) {
    $qty = isset($item['quantity']) ? (int)$item['quantity'] : 0;
    $price = isset($item['price']) ? (float)$item['price'] : 0.0;
    $totalQuantity += $qty;
    $totalPrice += $price * $qty;
    $items[] = [
        'name' => $item['name'] ?? $name,
        'price' => $price,
        'quantity' => $qty
    ];
}

echo json_encode([
    'success' => true,
    'cart' => $items,
    'totalQuantity' => $totalQuantity,
    'totalPrice' => $totalPrice
]);

?>
