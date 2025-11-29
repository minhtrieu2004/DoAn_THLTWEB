<?php
// Nhúng header, bao gồm session_start() và CSDL nếu cần
require_once '../config/db.php';
require_once '../includes/header.php';
?>

</div>
<main class="flex-shrink-0">
    <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="container my-3">
            <div class="alert alert-danger text-center">
                <?php echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); ?>
            </div>
        </div>
    <?php endif; ?>

    <form action="login_handler.php" method="post">

        <div class="container my-5">
            <div class="row d-flex justify-content-center">

                <div class="col-lg-5 col-md-8">
                    <div class="card p-0 shadow-lg" style="background-color: #343a40;">

                        <div class="card-header text-white fw-bold text-center py-3" style="background-color: #676f64ff; font-size: 1.25rem;">
                            ĐĂNG NHẬP HỆ THỐNG
                        </div>

                        <div class="card-body p-4">

                            <div class="mb-3">
                                <label for="username" class="form-label text-white">Email hoặc Tên đăng nhập</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label text-white">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary fw-bold py-2" style="background-color: #cd7759; border-color: #cd7759;">
                                    ĐĂNG NHẬP
                                </button>
                            </div>

                            <div class="text-center">
                                <a href="#" class="text-info me-3" style="text-decoration: none;">Quên mật khẩu?</a>
                                <span class="text-muted">|</span>
                                <a href="register.php" class="text-info ms-3" style="text-decoration: none;">Đăng ký tài khoản mới</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</main>

<?php
// Nhúng footer
require_once '../includes/footer.php';
?>