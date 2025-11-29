<?php
// Nhúng header và database
require_once '../config/db.php';
require_once '../includes/header.php';
?>

<main class="flex-shrink-0">
    <?php if (!empty($_SESSION['error']) || !empty($_SESSION['success'])): ?>
        <div class="alert alert-<?php echo isset($_SESSION['error']) ? 'danger' : 'success'; ?> text-center my-3 container" style="max-width: 500px;">
            <?php
            echo htmlspecialchars($_SESSION['error'] ?? $_SESSION['success']);
            unset($_SESSION['error'], $_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <div class="container my-5">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-6 col-md-10">
                <div class="card p-0 shadow-lg" style="background-color: #343a40;">

                    <div class="card-header text-white fw-bold text-center py-3" style="background-color: #676f64ff; font-size: 1.25rem;">
                        ĐĂNG KÝ TÀI KHOẢN MỚI
                    </div>

                    <div class="card-body p-4">
                        <form action="register_handler.php" method="POST">

                            <div class="mb-3">
                                <label for="full_name" class="form-label text-white">Họ và Tên</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label text-white">Tên đăng nhập</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label text-white">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label text-white">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label text-white">Địa chỉ</label>
                                <input type="text" class="form-control" id="address" name="address">
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label text-white">Tên đăng nhập</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label text-white">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-4">
                                <label for="confirm_password" class="form-label text-white">Xác nhận Mật khẩu</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary fw-bold py-2" style="background-color: #cd7759; border-color: #cd7759;">
                                    ĐĂNG KÝ
                                </button>
                            </div>

                            <div class="text-center">
                                <a href="login.php" class="text-info" style="text-decoration: none;">Đã có tài khoản? Đăng nhập ngay!</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once '../includes/footer.php';
?>