<?php
include '../includes/header.php';
require '../config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Không tìm thấy sản phẩm!</p>";
    exit;
}

$product_id = (int)$_GET['id'];

/* ---------------- Lấy sản phẩm theo ID ---------------- */
$sql = "SELECT * FROM products WHERE product_id = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<p>Sản phẩm không tồn tại!</p>";
    exit;
}

/* ---------------- Lấy 4 sản phẩm cùng category ---------------- */
$cat_id = $product['category_id'];

$related_sql = "SELECT * FROM products 
                WHERE category_id = :cat AND product_id != :id 
                ORDER BY product_id DESC 
                LIMIT 4";
$related_stmt = $pdo->prepare($related_sql);
$related_stmt->bindValue(':cat', $cat_id, PDO::PARAM_INT);
$related_stmt->bindValue(':id', $product_id, PDO::PARAM_INT);
$related_stmt->execute();
$related = $related_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Giao diện chi tiết sản phẩm -->
<section class="py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">

            <!-- Hình ảnh -->
            <div class="col-md-6">
                <img class="card-img-top mb-5 mb-md-0" 
                     src="../<?= htmlspecialchars($product['image_main']) ?>" 
                     alt="<?= htmlspecialchars($product['name']) ?>" />
            </div>

            <!-- Thông tin -->
            <div class="col-md-6">
                <div class="small mb-1">SKU: <?= $product['sku'] ?? "N/A" ?></div>
                <h1 class="display-5 fw-bolder"><?= htmlspecialchars($product['name']) ?></h1>
                <div class="fs-5 mb-5">
                    <span><?= number_format($product['price'], 0, ',', '.') ?> đ</span>
                </div>
                <p class="lead"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

                <div class="d-flex">
                    <!-- input ở dưới là nút nhập số lượng -->
                    <!-- <input class="form-control text-center me-3" id="inputQuantity" type="number" value="1" min="1" style="max-width: 3rem" /> -->
                    <button class="btn btn-outline-dark flex-shrink-0" 
                            onclick="addToCart('<?= htmlspecialchars($product['name'], ENT_QUOTES) ?>', <?= (float)$product['price'] ?>)">
                        <i class="bi-cart-fill me-1"></i> Add to cart
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- sản phẩm liên quan -->
<section class="py-5 bg-light">
    <div class="container px-4 px-lg-5 mt-5">
        <h2 class="fw-bolder mb-4">Sản phẩm liên quan</h2>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-4 justify-content-center">

            <?php foreach ($related as $row): ?>
                <div class="col mb-5">
                    <div class="card h-100" onclick="window.location='product_detail.php?id=<?= $row['product_id'] ?>'" style="cursor:pointer">

                        <img class="card-img-top" src="../<?= $row['image_main'] ?>" alt="<?= $row['name'] ?>" />

                        <div class="card-body p-4 text-center">
                            <h5 class="fw-bolder"><?= $row['name'] ?></h5>
                            <?= number_format($row['price'], 0, ',', '.') ?> đ
                        </div>

                        <div class="text-center pb-3">
                            <button class="btn btn-outline-dark mt-auto"
                                onclick="event.stopPropagation(); addToCart('<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>', <?= (float)$row['price'] ?>)">
                                Add to cart
                            </button>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
