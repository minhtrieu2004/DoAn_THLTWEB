<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

switch ($action) {
    case 'increase':
        if ($name !== '' && isset($_SESSION['cart'][$name])) {
            $_SESSION['cart'][$name]['quantity']++;
        }
        break;
    case 'decrease':
        if ($name !== '' && isset($_SESSION['cart'][$name])) {
            $_SESSION['cart'][$name]['quantity']--;
            if ($_SESSION['cart'][$name]['quantity'] <= 0) {
                unset($_SESSION['cart'][$name]);
            }
        }
        break;
    case 'remove':
        // remove entire product (all quantity)
        if ($name !== '' && isset($_SESSION['cart'][$name])) {
            unset($_SESSION['cart'][$name]);
        }
        break;
    case 'clear':
        $_SESSION['cart'] = [];
        break;
    default:
        // unknown action
        break;
}

// Recalculate totals
$totalQuantity = 0;
$totalPrice = 0.0;
$items = [];
foreach ($_SESSION['cart'] as $k => $item) {
    $qty = isset($item['quantity']) ? (int)$item['quantity'] : 0;
    $price = isset($item['price']) ? (float)$item['price'] : 0.0;
    $totalQuantity += $qty;
    $totalPrice += $price * $qty;
    $items[] = [
        'name' => $item['name'] ?? $k,
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
