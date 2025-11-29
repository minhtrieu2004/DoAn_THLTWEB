
<?php
// Hiển thị giao diện giống item_list.php nhưng lấy dữ liệu từ DB (PDO)
include '../includes/header.php';
require_once '../config/db.php'; // cung cấp $pdo

// Số sản phẩm mỗi trang
$limit = 9;

// Trang hiện tại
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Tính vị trí bắt đầu
$offset = ($page - 1) * $limit;

// Lấy danh sách sản phẩm
$sql = "SELECT product_id, name, image_main, price FROM products LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy tổng số sản phẩm để tính số trang
$total = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalPages = ($total > 0) ? ceil($total / $limit) : 1;

?>



<!-- Section: danh sách sản phẩm -->
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $row): ?>
                    <div class="col-md-4 mb-5">
                        <div class="card h-100">
                            <img class="card-img-top" src="../<?= htmlspecialchars($row['image_main']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" />
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <h5 class="fw-bolder"><?= htmlspecialchars($row['name']) ?></h5>
                                    <?= number_format($row['price'], 0, ',', '.') ?> đ
                                </div>
                            </div>
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
                                <button class="btn btn-outline-dark mt-auto" onclick="addToCart('<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>', <?= (float)$row['price'] ?>)">Add to cart</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Chưa có sản phẩm.</p>
            <?php endif; ?>
        </div>

        <!-- Phân trang -->
        <div class="d-flex justify-content-center mt-4">
            <?php if ($page > 1): ?>
                <a class="btn btn-outline-secondary me-2" href="?page=<?= $page-1 ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a class="btn <?= ($i == $page) ? 'btn-dark' : 'btn-outline-dark' ?> me-2" href="?page=<?= $i ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a class="btn btn-outline-secondary" href="?page=<?= $page+1 ?>">Next</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- JS Add to Cart (giống item_list, cập nhật cả id hiển thị số lượng có thể khác tên) -->
<script>
function addToCart(name, price) {
        fetch('add_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `name=${encodeURIComponent(name)}&price=${price}`
        })
        .then(response => response.json())
        .then(data => {
                if (data.success) {
                        alert('Đã thêm vào giỏ hàng!');
                        // Cập nhật số lượng hiển thị (có thể là id 'card-count' hoặc 'cartCount')
                        const el1 = document.getElementById('card-count');
                        const el2 = document.getElementById('cartCount');
                        if (el1) el1.textContent = data.totalQuantity;
                        if (el2) el2.textContent = data.totalQuantity;
                }
        })
        .catch(error => console.error('Lỗi:', error));
}
</script>

<?php include '../includes/footer.php'; ?>

</body>
</html>
