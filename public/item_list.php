<?php
// Hiển thị giao diện giống item_list.php nhưng lấy dữ liệu từ DB (PDO)
include '../includes/header.php';
require_once '../config/db.php'; // cung cấp $pdo

// Số sản phẩm mỗi trang
$limit = 9;

// Trang hiện tại
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Lọc theo category nếu có
$category_id = null;
$where = '';
$params = [];

if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $category_id = (int)$_GET['category'];
    $where = 'WHERE category_id = :cat';
    $params[':cat'] = $category_id;
}

// Xử lý sắp xếp
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$orderBy = "ORDER BY created_at  DESC"; // mặc định: mới --> cũ nhất
//sắp xếp cũ --> mới
if ($sort == "oldest") {
    $orderBy = "ORDER BY created_at  ASC";
} 
//sắp xếp tăng dần
elseif ($sort == "price_asc") {
    $orderBy = "ORDER BY price ASC";
} 
//sắp xếp giảm dần
elseif ($sort == "price_desc") {
    $orderBy = "ORDER BY price DESC";
}

// giữ lại sắp xếp khi sang trang (phân trang)
$extraQuery = "";
if (!empty($_GET['sort'])) {
    $extraQuery .= "&sort=" . urlencode($_GET['sort']);
}
if (!empty($_GET['category'])) {
    $extraQuery .= "&category=" . urlencode($_GET['category']);
}

// Tính vị trí bắt đầu
$offset = ($page - 1) * $limit;

// Lấy danh sách sản phẩm (có thể có WHERE)
$sql = "SELECT * FROM products " . $where . " " . $orderBy . " LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, PDO::PARAM_INT);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy tổng số sản phẩm để tính số trang (có filter nếu có)
if ($where) {
    $count_sql = "SELECT COUNT(*) FROM products WHERE category_id = :cat";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->bindValue(':cat', $category_id, PDO::PARAM_INT);
    $count_stmt->execute();
    $total = (int)$count_stmt->fetchColumn();
} else {
    $total = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
}

$totalPages = ($total > 0) ? ceil($total / $limit) : 1;


?>


<!-- Bộ lọc -->
<div class="mb-4 text-end">
    <form method="GET" class="d-inline-block">
        <?php if ($category_id): ?>
            <input type="hidden" name="category" value="<?= $category_id ?>">
        <?php endif; ?>

        <select name="sort" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
            <option value="">-- Sắp xếp --</option>
            <option value="newest" <?= isset($_GET['sort']) && $_GET['sort']=='newest' ? 'selected' : '' ?>>Mới nhất</option>
            <option value="oldest" <?= isset($_GET['sort']) && $_GET['sort']=='oldest' ? 'selected' : '' ?>>Cũ nhất</option>
            <option value="price_asc" <?= isset($_GET['sort']) && $_GET['sort']=='price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
            <option value="price_desc" <?= isset($_GET['sort']) && $_GET['sort']=='price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
        </select>
    </form>
</div>

<!-- Section: danh sách sản phẩm -->
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $row): ?>
                    <div class="col-md-4 mb-5">
                        <div class="card h-100" style="cursor: pointer;" onclick="viewProductDetail(<?= (int)$row['product_id'] ?>)">
                            <img class="card-img-top" src="../<?= htmlspecialchars($row['image_main']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" />
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <h5 class="fw-bolder"><?= htmlspecialchars($row['name']) ?></h5>
                                    <?= number_format($row['price'], 0, ',', '.') ?> đ
                                </div>
                            </div>
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
                                <button class="btn btn-outline-dark mt-auto" 
                                onclick="event.stopPropagation(); 
                                addToCart('<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>', <?= (float)$row['price'] ?>)">Add to cart</button>
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
                <a class="btn btn-outline-secondary me-2" href="?page=<?= $page - 1 ?><?= $extraQuery ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a class="btn <?= ($i == $page) ? 'btn-dark' : 'btn-outline-dark' ?> me-2" href="?page=<?= $i ?><?= $extraQuery ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a class="btn btn-outline-secondary" href="?page=<?= $page + 1 ?><?= $extraQuery ?>">Next</a>
            <?php endif; ?>
        </div>
    </div>
</section>


<?php include '../includes/footer.php'; ?>

</body>

</html>