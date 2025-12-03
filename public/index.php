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

<!-- Header-->
<header class="bg-dark py-5">
  <div class="container px-4 px-lg-5 my-5">
    <div class="text-center text-white">
      <h1 class="display-4 fw-bolder">Shop in style</h1>
      <p class="lead fw-normal text-white-50 mb-0">
        With this shop hompeage template
      </p>
    </div>
  </div>
</header>
<!-- Section-->
 <section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

            <?php foreach ($products as $p): ?>
                <div class="col mb-5">
                    <div class="card h-100">

                        <!-- Hình ảnh -->
                        <img class="card-img-top" src="../<?= htmlspecialchars($p['image_main']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">

                        <!-- Chi tiết  -->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <h5 class="fw-bolder"><?= $p['name'] ?></h5>
                                $<?= $p['price'] ?>
                            </div>
                        </div>

                        <!-- View  -->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center">
                              <button class="btn btn-outline-dark mt-auto" onclick="addToCart('<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>', <?= (float)$p['price'] ?>)">Add to cart</button>
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