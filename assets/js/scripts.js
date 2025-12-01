/*!
 * Start Bootstrap - Shop Item v5.0.6 (https://startbootstrap.com/template/shop-item)
 * Copyright 2013-2023 Start Bootstrap
 * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-shop-item/blob/master/LICENSE)
 */
// This file is intentionally blank
// Use this file to add JavaScript to your project

// script.js

// Nhảy đến trang chi tiết sản phẩm
function viewProductDetail(productId) {
  window.location.href = `product_detail.php?id=${productId}`;
}

// Cập nhật số lượng giỏ hàng trên nút Cart
function updateCartCount() {
  fetch("../public/get_cart.php")
    .then((res) => res.json())
    .then((data) => {
      // Hỗ trợ nhiều id khác nhau trong header: 'cart-count', 'card-count', 'cartCount'
      const ids = ["cart-count", "card-count", "cartCount"];
      ids.forEach((id) => {
        const el = document.getElementById(id);
        if (el) el.textContent = data.totalQuantity;
      });
    });
}

// Thêm sản phẩm vào giỏ hàng (AJAX)
function addToCart(name, price) {
  fetch("../public/add_cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `name=${encodeURIComponent(name)}&price=${price}`,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        //thay đổi aler thành 1 hộp thông báo
        //alert('Đã thêm vào giỏ hàng!');
        updateCartCount();
      }
    });
}

// Cập nhật giỏ hàng: increase, decrease, remove, clear
function updateCart(action, name = "") {
  fetch("../public/update_cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `action=${action}&name=${encodeURIComponent(name)}`,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        updateCartCount();
        loadCart();
      }
    });
}

// Load giỏ hàng (dùng cho trang cart.php)
function loadCart() {
  fetch("../public/get_cart.php")
    .then((res) => res.json())
    .then((data) => {
      const tbody = document.getElementById("cart-items");
      const subtotalEl = document.getElementById("cart-subtotal");
      const totalEl = document.getElementById("cart-total");
      if (!tbody) return;

      tbody.innerHTML = "";

      if (data.cart.length === 0) {
        tbody.innerHTML =
          '<tr><td colspan="5" class="text-center">Giỏ hàng trống</td></tr>';
      } else {
        data.cart.forEach((item) => {
          const row = document.createElement("tr");
          const id = md5(item.name);
          row.innerHTML = `
                        <td>${item.name}</td>
                        <td>${item.price.toLocaleString()} đ</td>
                        <td>
                            <button class="btn btn-sm btn-secondary" onclick="updateCart('decrease','${
                              item.name
                            }')">-</button>
                            <span id="qty-${id}">${item.quantity}</span>
                            <button class="btn btn-sm btn-secondary" onclick="updateCart('increase','${
                              item.name
                            }')">+</button>
                        </td>
                        <td id="subtotal-${id}">${(
            item.price * item.quantity
          ).toLocaleString()} đ</td>
                        <td>
                            <button class="btn btn-sm btn-danger" onclick="updateCart('remove','${
                              item.name
                            }')">Xóa</button>
                        </td>
                    `;
          tbody.appendChild(row);
        });
      }

      if (subtotalEl)
        subtotalEl.textContent = data.totalPrice.toLocaleString() + " đ";
      if (totalEl)
        totalEl.textContent = data.totalPrice.toLocaleString() + " đ";
    });
}

// Hàm md5 giả lập JS (dùng tên sản phẩm làm id)
function md5(str) {
  return str.split("").reduce((a, b) => {
    a = (a << 5) - a + b.charCodeAt(0);
    return a & a;
  }, 0);
}

// Load ngay khi mở trang
document.addEventListener("DOMContentLoaded", function () {
  updateCartCount();
  loadCart();
});
