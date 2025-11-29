<?php
session_start();
// Đảm bảo session_start() đã được gọi ở đầu file header.php
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Shop Cầu Lông</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"
        rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../assets/css/styles.css" rel="stylesheet" />
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="#!">SHOP TT</a>
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
                        <a class="nav-link active" aria-current="page" href="../public/index.php">Home</a>
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
                            <li><a class="dropdown-item" href="#!">All Products</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                            <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <?php if ($is_logged_in): ?>

                        <a href="profile.php" class="btn btn-primary me-2 fw-bold" style="background-color: #5c4942ff; border-color: #cd7759;">
                            <i class="bi bi-person-fill"></i> Hồ sơ (<?php echo htmlspecialchars($_SESSION['username']); ?>)
                        </a>

                        <a href="logout.php" class="btn btn-danger fw-bold me-2" style="background-color: #957d77ff;">
                            <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                        </a>

                    <?php else: ?>

                        <a href="login.php" class="btn btn-primary fw-bold me-2" style="background-color: #413531ff; border-color: #cd7759;">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                        </a>

                    <?php endif; ?>
                    <a href="../public/cart.php" class="btn btn-outline-dark d-flex align-items-center">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span id="card-count" class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>