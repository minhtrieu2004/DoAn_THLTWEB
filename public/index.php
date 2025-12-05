<!-- Nav -->
<?php
include '../includes/header.php';
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  echo "<div class='container mt-3'><div class='alert alert-success'>Xin chào, " . $_SESSION['username'] . "! Bạn đã đăng nhập thành công.</div></div>";
}
?>
<?php
require '../config/db.php';

$sql = "SELECT * FROM products ORDER BY product_id DESC LIMIT 8";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- gioi thieu -->
<div class="hero-slider">
    <img src="../assets/images/play in  your own style.png" class="slide">
    <img src="../assets/images/TT.jpg" class="slide">
    <img src="../assets/images/4.jpg" class="slide">
</div>

<!-- Section-->
 <section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

            <?php foreach ($products as $row): ?>
                <div class="col mb-5">
                    <div class="card h-100" style="cursor: pointer;" onclick="viewProductDetail(<?= (int)$row['product_id'] ?>)" >

                        <!-- Hình ảnh -->
                        <img class="card-img-top" src="../<?= htmlspecialchars($row['image_main']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">

                        <!-- Chi tiết  -->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <h5 class="fw-bolder"><?= $row['name'] ?></h5>
                                $<?= $row['price'] ?>
                            </div>
                        </div>

                        <!-- View  -->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center">
                              <button class="btn btn-outline-dark mt-auto" 
                              onclick="event.stopPropagation(); 
                              addToCart('<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>', <?= (float)$row['price'] ?>)">Add to cart </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Footer-->
<?php
include '../includes/footer.php';
?>

</body>

</html>