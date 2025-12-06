/*!
 * Start Bootstrap - Shop Item v5.0.6 (https://startbootstrap.com/template/shop-item)
 * Copyright 2013-2023 Start Bootstrap
 * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-shop-item/blob/master/LICENSE)
 */
// This file is intentionally blank
// Use this file to add JavaScript to your project

// Hàm format tiền VND
function formatPrice(price) {
  // Format số VND theo chuẩn Việt Nam: 1.000.000 đ
  return new Intl.NumberFormat("vi-VN").format(price) + " VND";
}

// Nhảy đến trang chi tiết sản phẩm
function viewProductDetail(productId) {
  window.location.href = `product_detail.php?id=${productId}`;
}

// Cập nhật số lượng giỏ hàng trên nút Cart
function updateCartCount() {
  fetch("../private/get_cart.php")
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        // Hỗ trợ nhiều id khác nhau trong header: 'cart-count', 'card-count', 'cartCount'
        const ids = ["cart-count", "card-count", "cartCount"];
        ids.forEach((id) => {
          const el = document.getElementById(id);
          if (el) el.textContent = data.totalQuantity;
        });
      }
    })
    .catch((error) => console.error("Get cart error:", error));
}

// Thêm sản phẩm vào giỏ hàng (AJAX)
function addToCart(name, price) {
  fetch("../private/add_cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `name=${encodeURIComponent(name)}&price=${price}`,
  })
    .then((res) => res.json())
    .then((data) => {
      console.log("Add to cart response:", data);
      if (data.success) {
        updateCartCount();
      } else {
        alert("Lỗi: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Add to cart error:", error);
      alert("Lỗi khi thêm vào giỏ hàng");
    });
}

// Cập nhật giỏ hàng: increase, decrease, remove, clear
function updateCart(action, name = "") {
  fetch("../private/update_cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `action=${action}&name=${encodeURIComponent(name)}`,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        updateCartCount();
        loadCart();
      } else {
        alert("Lỗi khi cập nhật giỏ hàng");
      }
    })
    .catch((error) => {
      console.error("Update cart error:", error);
      alert("Lỗi khi cập nhật giỏ hàng");
    });
}

// Load giỏ hàng (dùng cho trang cart.php)
function loadCart() {
  fetch("../private/get_cart.php")
    .then((res) => res.json())
    .then((data) => {
      if (!data.success) {
        console.error("Load cart error:", data);
        return;
      }

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
                        <td>${formatPrice(item.price)}</td>
                        <td>
                            <button class="btn btn-sm btn-secondary" onclick="updateCart('decrease','${
                              item.name
                            }')">-</button>
                            <span id="qty-${id}">${item.quantity}</span>
                            <button class="btn btn-sm btn-secondary" onclick="updateCart('increase','${
                              item.name
                            }')">+</button>
                        </td>
                        <td id="subtotal-${id}">${formatPrice(
            item.price * item.quantity
          )}</td>
                        <td>
                            <button class="btn btn-sm btn-danger" onclick="updateCart('remove','${
                              item.name
                            }')">Xóa</button>
                        </td>
                    `;
          tbody.appendChild(row);
        });
      }

      if (subtotalEl) subtotalEl.textContent = formatPrice(data.totalPrice);
      if (totalEl) totalEl.textContent = formatPrice(data.totalPrice);
    })
    .catch((error) => console.error("Load cart error:", error));
}

// Hàm md5 giả lập JS (dùng tên sản phẩm làm id)
function md5(str) {
  return str.split("").reduce((a, b) => {
    a = (a << 5) - a + b.charCodeAt(0);
    return a & a;
  }, 0);
}

// Thanh trượt chính: tự động xoay hình ảnh mỗi 5 giây
function initHeroSlider() {
  const slides = document.querySelectorAll(".hero-slider img.slide");
  if (slides.length === 0) return;

  let currentIndex = 0;

  const showSlide = () => {
    slides.forEach((slide) => slide.classList.remove("active"));
    slides[currentIndex].classList.add("active");
  };

  showSlide();

  // Xoay mỗi 5 giây
  setInterval(() => {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide();
  }, 5000);
}

// Load ngay khi mở trang
document.addEventListener("DOMContentLoaded", function () {
  updateCartCount();
  loadCart();
  initHeroSlider();
});

//xử lý sự kiện xóa sản phẩm với admin
document.addEventListener("DOMContentLoaded", function () {
  const tableBody = document.getElementById("productTableBody");

  // Lắng nghe sự kiện click trên các nút Xóa
  tableBody.addEventListener("click", function (e) {
    if (e.target.classList.contains("delete-product-btn")) {
      const productId = e.target.getAttribute("data-id");

      if (
        confirm(`Bạn có chắc chắn muốn xóa sản phẩm ID ${productId} không?`)
      ) {
        // Chuyển hướng đến trang xử lý xóa
        // Giả sử trang xử lý là 'delete_product.php'
        window.location.href = `../admin/delete_products.php?id=${productId}`;
      }
    }
  });
});

// xử lý update ở trang admin
document.addEventListener("DOMContentLoaded", function () {
  const tableBody = document.getElementById("productTableBody");
  const productForm = document.getElementById("productForm");
  const productModalLabel = document.getElementById("productModalLabel");
  const productIdInput = document.getElementById("productId");
  const actionInput = document.querySelector('input[name="action"]');
  const productImageInput = document.getElementById("productImage");
  const addNewProductBtn = document.getElementById("addNewProductBtn");

  // Lấy đối tượng Modal của Bootstrap
  const productModal = new bootstrap.Modal(
    document.getElementById("productModal")
  );

  // Hàm đặt Modal về chế độ Thêm Mới
  function resetModalForCreate() {
    productForm.reset();
    productModalLabel.textContent = "Thêm Sản phẩm Mới";
    productForm.action = "create_products.php"; // Trỏ đến file tạo mới
    actionInput.value = "create"; // Đặt action là create
    productImageInput.required = true; // Bắt buộc phải có ảnh khi thêm mới
    productIdInput.value = ""; // Xóa ID sản phẩm
  }

  // Gắn sự kiện cho nút Thêm Sản phẩm Mới
  addNewProductBtn.addEventListener("click", resetModalForCreate);

  // Đặt lại modal khi nó bị đóng (để tránh lỗi nếu người dùng đóng khi đang ở chế độ Sửa)
  document
    .getElementById("productModal")
    .addEventListener("hidden.bs.modal", resetModalForCreate);

  // Xử lý nút SỬA (AJAX để lấy dữ liệu)
  tableBody.addEventListener("click", function (e) {
    if (e.target.classList.contains("edit-product-btn")) {
      const productId = e.target.getAttribute("data-id");

      // 1. Đặt Modal ở chế độ SỬA
      productModalLabel.textContent = "Cập nhật Sản phẩm ID: " + productId;
      productForm.action = "../admin/update_products.php"; // Trỏ đến file cập nhật
      actionInput.value = "update"; // Đặt action là update
      productIdInput.value = productId; // Đặt ID vào trường ẩn
      productImageInput.required = false; // Ảnh không bắt buộc phải tải lên khi sửa

      // 2. Tải dữ liệu sản phẩm qua AJAX
      fetch(`../private/get_product_data.php?id=${productId}`)
        .then((response) => response.json())
        .then((data) => {
          if (data) {
            // 3. Điền dữ liệu vào form
            document.getElementById("productName").value = data.name;
            document.getElementById("categoryId").value = data.category_id;
            document.getElementById("productPrice").value = data.price;
            document.getElementById("productStock").value = data.stock;
            document.getElementById("productDesc").value = data.description;

            // Modal tự động mở do data-bs-toggle='modal' trên nút Sửa
          } else {
            alert("Không tìm thấy dữ liệu sản phẩm.");
            productModal.hide(); // Đóng modal nếu lỗi
          }
        })
        .catch((error) => {
          console.error("Lỗi khi tải dữ liệu:", error);
          alert("Đã xảy ra lỗi khi tải dữ liệu sản phẩm.");
          productModal.hide();
        });
    }
  });
});
