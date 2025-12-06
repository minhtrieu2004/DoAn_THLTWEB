<?php
include '../config/db.php';
include '../includes/header.php';


// BƯỚC 1: Xử lý hiển thị danh mục
try {
    // Tên bảng của bạn là products
    $sql = "SELECT product_id, name, price, stock, description FROM products ORDER BY product_id ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $num = $stmt->rowCount();

    // TRUY VẤN DANH MỤC
    $sql_categories = "SELECT category_id, name FROM categories ORDER BY name ASC";
    $stmt_categories = $pdo->prepare($sql_categories);
    $stmt_categories->execute();
    $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Xử lý lỗi database nếu có
    die("Lỗi đọc dữ liệu: " . $e->getMessage());
}
?>

<?php
// Hiển thị thông báo (nếu có)
if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success mt-3" role="alert">
        <?php echo $_SESSION['success'];
        unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger mt-3" role="alert">
        <?php echo $_SESSION['error'];
        unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>


<div class="content">
    <h2 class="mb-2 mt-5">📋 Quản Lý Sản Phẩm</h2>
    <hr>

    <div class="mb-3 d-flex justify-content-end">
        <button class="btn btn-success"
            data-bs-toggle="modal"
            data-bs-target="#productModal"
            id="addNewProductBtn">
            + Thêm Sản phẩm Mới
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#ID</th>
                    <th>Tên Sản phẩm</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Mô tả ngắn</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <?php
                if ($num > 0) {
                    // Lặp qua từng dòng dữ liệu
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // Giải nén: Tạo các biến $product_id, $name, $price, $stock, $description
                        extract($row);

                        // ĐỊNH DẠNG LẠI GIÁ (Sửa lỗi hiển thị giá không có VNĐ)
                        $formatted_price = number_format($price, 0, ',', '.') . ' VNĐ';

                        echo "<tr data-id='{$product_id}'>";
                        echo "<td>" . htmlspecialchars($product_id) . "</td>";
                        echo "<td>" . htmlspecialchars($name) . "</td>";
                        echo "<td>" . $formatted_price . "</td>"; // Hiển thị Giá đã format
                        echo "<td>" . htmlspecialchars($stock) . "</td>";
                        // Cắt ngắn Mô tả
                        echo "<td>" . htmlspecialchars(mb_substr($description ?? '', 0, 50, 'UTF-8')) . "...</td>";
                        echo "<td>";
                        // Nút XEM;
                        echo "<a href='../public/product_detail.php?id={$product_id}' class='btn btn-sm btn-info'>Xem</a>";
                        // Nút Sửa
                        echo "<button class='btn btn-sm btn-warning me-1 edit-product-btn' 
                                                    data-bs-toggle='modal' 
                                                    data-bs-target='#productModal' 
                                                    data-id='{$product_id}'>Sửa</button>";

                        // Nút XÓA (Sử dụng data-id cho JS xử lý xóa)
                        echo "<button class='btn btn-sm btn-danger delete-product-btn' 
                                                    data-id='{$product_id}'>Xóa</button>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Chưa có sản phẩm nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="productForm" method="POST" action="create_products.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                <input type="hidden" id="productId" name="productId">

                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Thêm Sản phẩm Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="productName" class="form-label">Tên Sản phẩm</label>
                        <input type="text" class="form-control" id="productName" name="productName" required>
                    </div>

                    <div class="mb-3">
                        <label for="categoryId" class="form-label">Danh mục</label>
                        <select class="form-control" id="categoryId" name="categoryId" required>
                            <option value="">-- Chọn Danh mục --</option>
                            <?php
                            // Lặp qua dữ liệu danh mục đã truy vấn ở trên
                            foreach ($categories as $cat) {
                                echo "<option value='{$cat['category_id']}'>" . htmlspecialchars($cat['name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Giá (VNĐ)</label>
                        <input type="number" class="form-control" id="productPrice" name="productPrice" required>
                    </div>

                    <div class="mb-3">
                        <label for="productStock" class="form-label">Số lượng tồn kho</label>
                        <input type="number" class="form-control" id="productStock" name="productStock" required>
                    </div>

                    <div class="mb-3">
                        <label for="productImage" class="form-label">Ảnh chính</label>
                        <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*" required>
                    </div>

                    <div class="mb-3">
                        <label for="productDesc" class="form-label">Mô tả ngắn</label>
                        <textarea class="form-control" id="productDesc" name="productDesc" rows="3"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu Sản phẩm</button>
                </div>

            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/scripts.js"></script>