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
                        <form id="registerForm" action="../private/register_handler.php" method="POST" novalidate>

                            <div class="mb-3">
                                <label for="full_name" class="form-label text-white">Họ và Tên *</label>
                                <input type="text" class="form-control validate-field" id="full_name" name="full_name"
                                    placeholder="Nhập họ và tên" required minlength="3" maxlength="100"
                                    title="Họ và tên không được chứa ký tự đặc biệt">
                                <small class="invalid-feedback d-block text-danger" id="error-full_name"></small>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label text-white">Tên đăng nhập *</label>
                                <input type="text" class="form-control validate-field" id="username" name="username"
                                    placeholder="Nhập tên đăng nhập (3-50 ký tự)" required minlength="3" maxlength="50"
                                    pattern="[a-zA-Z0-9_]+" title="Chỉ chứa chữ cái, số và dấu gạch dưới">
                                <small class="invalid-feedback d-block text-danger" id="error-username"></small>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label text-white">Email *</label>
                                <input type="email" class="form-control validate-field" id="email" name="email"
                                    placeholder="Nhập email" required maxlength="100">
                                <small class="invalid-feedback d-block text-danger" id="error-email"></small>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label text-white">Số điện thoại</label>
                                <input type="tel" class="form-control validate-field" id="phone" name="phone"
                                    placeholder="0xxxxxxxxx hoặc +84xxxxxxxxx" maxlength="20"
                                    pattern="[0-9\+\-\s\(\)]+" title="Số điện thoại hợp lệ">
                                <small class="invalid-feedback d-block text-danger" id="error-phone"></small>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label text-white">Địa chỉ</label>
                                <textarea class="form-control validate-field" id="address" name="address" rows="2"
                                    placeholder="Nhập địa chỉ của bạn" maxlength="200"></textarea>
                                <small class="invalid-feedback d-block text-danger" id="error-address"></small>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label text-white">Mật khẩu *</label>
                                <input type="password" class="form-control validate-field" id="password" name="password"
                                    placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)" required minlength="6" maxlength="100">
                                <small class="form-text text-muted d-block" style="color: #ccc !important;">Phải chứa: chữ thường, chữ hoa và chữ số</small>
                                <small class="invalid-feedback d-block text-danger" id="error-password"></small>
                            </div>

                            <div class="mb-4">
                                <label for="confirm_password" class="form-label text-white">Xác nhận Mật khẩu *</label>
                                <input type="password" class="form-control validate-field" id="confirm_password" name="confirm_password"
                                    placeholder="Xác nhận mật khẩu" required minlength="6" maxlength="100">
                                <small class="invalid-feedback d-block text-danger" id="error-confirm_password"></small>
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