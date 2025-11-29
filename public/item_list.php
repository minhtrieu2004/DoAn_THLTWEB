<?php
// Tổng số sản phẩm
$totalProducts = 36;

// Số sản phẩm mỗi trang
$perPage = 9;

// Tính tổng số trang
$totalPages = ceil($totalProducts / $perPage);

// Lấy trang hiện tại từ query string ?page=1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $totalPages) $page = $totalPages;

// Tính sản phẩm bắt đầu của trang
$start = ($page - 1) * $perPage;
?>

<?php
session_start();

// Tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xử lý thêm sản phẩm từ GET ?add=ProductName&price=100
if (isset($_GET['add']) && isset($_GET['price'])) {
    $name = $_GET['add'];
    $price = (float)$_GET['price'];

    if (isset($_SESSION['cart'][$name])) {
        $_SESSION['cart'][$name]['quantity']++;
    } else {
        $_SESSION['cart'][$name] = ['price' => $price, 'quantity' => 1];
    }

    header('Location: index.php'); // reload để tránh bấm F5 thêm tiếp
    exit;
}

// Đếm tổng số sản phẩm trong giỏ
$cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));
?>

<!DOCTYPE html>
<html lang="en">
<!-- test? -->
<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Shop cầu lông TnT</title>
  <!-- Favicon-->
  <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
  <!-- Bootstrap icons-->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"
    rel="stylesheet" />
  <!-- Core theme CSS (includes Bootstrap)-->
  <link href="../assets/css/styles.css" rel="stylesheet" />
</head>

<body>
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
      <a class="navbar-brand" href="#!">SHOP TnT</a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#!">Trang chủ</a>
          </li>
          <li class="nav-item"><a class="nav-link" href="#!">About</a></li>
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              id="navbarDropdown"
              href="#"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false">Shop</a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#!">Tất cả sản phẩm</a></li>
              <li>
                <hr class="dropdown-divider" />
              </li>
              <li><a class="dropdown-item" href="#!">Hàng nổi bật</a></li>
              <li><a class="dropdown-item" href="#!">Hàng mới về </a></li>
            </ul>
          </li>
        </ul>
        <a href="cart.php" class="btn btn-outline-dark">
             <i class="bi-cart-fill me-1"></i>
                 Cart
                <span class="badge bg-dark text-white ms-1 rounded-pill" id="cartCount">
                  <?= $cartCount ?>
                </span>
        </a>
      </div>
    </div>
  </nav>
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
    <!-- page 1 -->
    <div class="row gx-4 gx-lg-5 justify-content-center page" id="page1">
      <!-- Dòng 1 -->
       <!-- Sản phẩm 1 -->
      <div class="col-md-4 mb-5">
        <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 1" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 1</h5>
              $40.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 1', 40)">Add to cart
            </button>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 2 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 2" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 2</h5>
              $50.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 2', 50)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 3 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 3" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 3</h5>
              $60.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 3', 60)">Add to cart
            </button>
          </div>
        </div>
      </div>

      <!-- Dòng 2 -->
      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 4 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 4" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 4</h5>
              $40.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 4', 40)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <!-- ... Sản phẩm 5 -->
       <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 5" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 5</h5>
              $40.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 5', 40)">Add to cart
            </button>
          </div>
        </div>
      </div>
       <!-- Sản phẩm 6 -->
      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 4 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 6" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 6</h5>
              $100.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 6', 100)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <!-- Dòng 3 -->
      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 7 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 7" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 7</h5>
              $80.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 7', 80)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <!-- ... Sản phẩm 8 -->
       <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 8" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 8</h5>
              $40.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 8', 40)">Add to cart
            </button>
          </div>
        </div>
      </div>
       <!-- Sản phẩm 9 -->
        <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 9" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 9</h5>
              $27.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 9', 27)">Add to cart
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!--  page2 -->
       
    <div class="row gx-4 gx-lg-5 justify-content-center page" id="page2">
      <!-- Dòng 1 -->
       <!-- Sản phẩm 10 -->
      <div class="col-md-4 mb-5">
        <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 10" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 10</h5>
              $55.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 10', 55)">Add to cart
            </button>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 11 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 11" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 11 </h5>
              $122.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
           <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 11', 122)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 12 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 12" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 12 </h5>
              $44.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 12', 44)">Add to cart
            </button>
          </div>
        </div>
      </div>

      <!-- Dòng 2 -->
      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 13 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 13" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 13</h5>
              $40.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 13', 40)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <!-- ... Sản phẩm 14 -->
       <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 14" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 14</h5>
              $50.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 14', 50)">Add to cart
            </button>
          </div>
        </div>
      </div>

       <!-- Sản phẩm 15 -->
      <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 15" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 15</h5>
              $90.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 15', 90)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <!-- Dòng 3 -->
      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 16 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 16" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 16</h5>
              $70.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 16', 70)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <!-- ... Sản phẩm 17 -->
       <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 17" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 17</h5>
              $25.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 17', 25)">Add to cart
            </button>
          </div>
        </div>
      </div>
       <!-- Sản phẩm 18 -->
        <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 18" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 18</h5>
              $30.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 18', 30)">Add to cart
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- page 3 -->
    <div class="row gx-4 gx-lg-5 justify-content-center page" id="page3">
      <!-- Dòng 1 -->
       <!-- Sản phẩm 19 -->
      <div class="col-md-4 mb-5">
        <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 19" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 19</h5>
              $40.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 19', 40)">Add to cart
            </button>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 20 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 20" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 20</h5>
              $170.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 20', 170)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 21 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 21" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 21</h5>
              $88.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 21', 88)">Add to cart
            </button>
          </div>
        </div>
      </div>

      <!-- Dòng 2 -->
      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 22 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 22" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 22</h5>
              $40.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 22', 40)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <!-- ... Sản phẩm 23 -->
       <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 23" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 23</h5>
              $100.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 23', 100)">Add to cart
            </button>
          </div>
        </div>
      </div>
       <!-- Sản phẩm 24 -->
      <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 24" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 24</h5>
              $125.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 24', 125)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <!-- Dòng 3 -->
      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 25 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 25" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 25</h5>
              $79.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 25', 79)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <!-- ... Sản phẩm 26 -->
       <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 26" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 26</h5>
              $71.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 26', 71)">Add to cart
            </button>
          </div>
        </div>
      </div>
       <!-- Sản phẩm 27 -->
        <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 27" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 27</h5>
              $21.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 27', 21)">Add to cart
            </button>
          </div>
        </div>
      </div>
    </div>
     <!-- page 4 -->
    <div class="row gx-4 gx-lg-5 justify-content-center page" id="page4">
      <!-- Dòng 1 -->
       <!-- Sản phẩm 28 -->
      <div class="col-md-4 mb-5">
        <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 28" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 28</h5>
              $23.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 28', 23)">Add to cart
            </button>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 29 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 29" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 29</h5>
              $280.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 29', 280)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 30 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 30" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 30</h5>
              $133.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 30', 133)">Add to cart
            </button>
          </div>
        </div>
      </div>

      <!-- Dòng 2 -->
      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 31 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 31" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 31</h5>
              $10.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 31', 10)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <!-- ... Sản phẩm 32 -->
       <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 32" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 32</h5>
              $40.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 32', 40)">Add to cart
            </button>
          </div>
        </div>
      </div>
       <!-- Sản phẩm 33 -->
      <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 33" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 33</h5>
              $40.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 33', 40)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <!-- Dòng 3 -->
      <div class="col-md-4 mb-5">
        <!-- Sản phẩm 34 -->
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 34" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 34</h5>
              $40.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 34', 40)">Add to cart
            </button>
          </div>
        </div>
      </div>
      <!-- ... Sản phẩm 35 -->
       <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 35" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 35</h5>
              $40.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 35', 40)">Add to cart
            </button>
          </div>
        </div>
      </div>
       <!-- Sản phẩm 36 -->
        <div class="col-md-4 mb-5">
         <div class="card h-100">
          <img class="card-img-top" src="image1.jpg" alt="Sản phẩm 36" />
          <div class="card-body p-4">
            <div class="text-center">
              <h5 class="fw-bolder">Sản phẩm 36</h5>
              $40.00
            </div>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <button class="btn btn-outline-dark mt-auto" 
              onclick="addToCart('Sản phẩm 36', 40)">Add to cart
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Nút phân trang -->
    <div class="d-flex justify-content-center mt-4">
      <button onclick="showPage(1)">1</button>
      <button onclick="showPage(2)">2</button>
      <button onclick="showPage(3)">3</button>
      <button onclick="showPage(4)">4</button>
    </div>
  </div>
</section>

<!-- JS Add to Cart -->
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
            // Cập nhật số lượng hiển thị trên nút Cart
            document.getElementById('cartCount').textContent = data.totalQuantity;
        }
    })
    .catch(error => console.error('Lỗi:', error));
}
</script>

<script>
function showPage(pageNumber) {
  const pages = document.querySelectorAll('.page');
  pages.forEach((p,i) => {
    p.style.display = (i+1 === pageNumber) ? 'flex' : 'none';
  });
}
// Mặc định hiển thị page 1
showPage(1);
</script>
  <!-- Footer-->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">
        Copyright &copy; Your Website 2023
      </p>
    </div>
  </footer>
  <!-- Bootstrap core JS-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Core theme JS-->
  <script src="js/scripts.js"></script>
</body>

</html>