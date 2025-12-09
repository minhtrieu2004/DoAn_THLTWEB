<?php
function mergeGuestCartToUser($pdo, $user_id) {

    // Nếu guest không có sản phẩm → không làm gì
    if (empty($_SESSION['cart_guest'])) {
        return;
    }

    // Lấy cart_id của user
    $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $cart = $stmt->fetch();

    if (!$cart) {
        // Nếu user chưa có giỏ → tạo mới
        $pdo->prepare("INSERT INTO carts (user_id, created_at) VALUES (?, NOW())")
            ->execute([$user_id]);
        $cart_id = $pdo->lastInsertId();
    } else {
        $cart_id = $cart["cart_id"];
    }

    // Kiểm tra xem user đã có sản phẩm nào trong giỏ chưa
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cart_items WHERE cart_id = ?");
    $stmt->execute([$cart_id]);
    $userItemCount = $stmt->fetchColumn();

    // ❗ Nếu user đã có sản phẩm trong giỏ → KHÔNG merge guest
    if ($userItemCount > 0) {
        unset($_SESSION['cart_guest']); // xóa giỏ guest
        return; // không merge nữa
    }

    //  User chưa có sản phẩm → copy toàn bộ guest vào DB
    foreach ($_SESSION['cart_guest'] as $pid => $qty) {
        $pdo->prepare("
            INSERT INTO cart_items (cart_id, product_id, quantity)
            VALUES (?, ?, ?)
        ")->execute([$cart_id, $pid, $qty]);
    }

    // Xóa giỏ guest sau khi merge
    unset($_SESSION['cart_guest']);
}
?>
